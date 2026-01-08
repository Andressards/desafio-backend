<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|exists:users,id',
            'payee' => 'required|exists:users,id|different:payer',
        ];
    }

    public function messages(): array
    {
        return [
            'value.min' => 'O valor deve ser pelo menos 1 centavo.',
            'payee.different' => 'Você não pode transferir dinheiro para si mesmo.',
            'payer.exists' => 'Usuário pagador não encontrado.',
        ];
    }
}
