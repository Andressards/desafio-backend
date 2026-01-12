# Plataforma de Pagamentos simplificada

Este projeto é uma implementação simplificada de uma plataforma de pagamentos. A aplicação permite a realização de transferências financeiras entre usuários comuns e lojistas, respeitando regras de negócio específicas e garantindo a integridade das transações.

## 1. Tecnologias Utilizadas

*   **PHP 8.2+**
*   **Laravel 11**
*   **MySQL 8.0**
*   **Docker (Laravel Sail)**
*   **PHPUnit** (Testes Unitários e de Funcionalidade)

## 2. Funcionalidades

*   **Cadastro de Usuários:** Diferenciação entre usuários comuns e lojistas.
*   **Carteira Digital:** Cada usuário possui uma carteira com saldo.
*   **Transferências:**
    *   Usuários comuns podem enviar dinheiro para qualquer usuário ou lojista.
    *   Lojistas **apenas recebem** transferências.
    *   Validação de saldo antes de cada operação.
    *   Consulta a um serviço autorizador externo antes de finalizar a transação.
    *   Envio de notificações após o recebimento de pagamentos.
*   **Transacionalidade:** Garantia de que, em caso de falha, o dinheiro retorne à carteira do pagador (rollback).

## 3. Arquitetura e Decisões Técnicas

Para este projeto, optei por uma estrutura que prioriza a **manutenibilidade** e a **separação de responsabilidades**:

*   **Service Layer:** Toda a lógica de negócio das transferências foi isolada no `TransferService`. Isso evita que o Controller fique sobrecarregado e facilita a criação de testes.
*   **Form Requests:** Utilizei o `TransferRequest` para centralizar as validações de entrada, garantindo que o serviço receba apenas dados válidos.
*   **Database Transactions:** Implementei o uso de `DB::transaction` para assegurar a atomicidade das operações financeiras.
*   **Tratamento de Exceções:** O sistema utiliza exceções para gerenciar fluxos de erro (como saldo insuficiente ou lojista tentando transferir), permitindo um retorno claro para a API.

## 4. Como Executar o Projeto

### Pré-requisitos
*   Docker instalado em sua máquina.

### Passo a Passo

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/Andressards/desafio-backend.git
    cd desafio-backend
    ```

2.  **Instale as dependências (via Docker):**
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

3.  **Configure o ambiente:**
    ```bash
    cp .env.example .env
    ```

4.  **Inicie os containers:**
    ```bash
    ./vendor/bin/sail up -d
    ```

5.  **Gere a chave da aplicação e execute as migrations:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Execute as migrations e seeders:**
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

## 5. Executando Testes

Para garantir a qualidade do código, foram implementados testes unitários e de integração. Para executá-los:

```bash
./vendor/bin/sail artisan test
```

## 6. Endpoints da API

### Transferência
`POST /api/transfer`

**Payload:**
```json
{
    "value": 100.00,
    "payer": 1,
    "payee": 2
}
```

**Respostas:**
*   `201 Created`: Transferência realizada com sucesso.
*   `400 Bad Request`: Erro de validação ou saldo insuficiente.
*   `403 Forbidden`: Lojista tentando realizar transferência.