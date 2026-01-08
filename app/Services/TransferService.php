<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

class TransferService
{
    public function execute(array $data)
    {
        $payer = User::findOrFail($data['payer']);
        $payee = User::findOrFail($data['payee']);
        $value = $data['value'];

        if ($payer->type === 'merchant') {
            throw new Exception("Lojistas não podem realizar transferências.");
        }

        if ($payer->wallet->balance < $value) {
            throw new Exception("Saldo insuficiente.");
        }

        if (!$this->authorizeTransaction()) {
            throw new \Exception("Transferência não autorizada pelo serviço externo.");
        }

        $result = DB::transaction(function () use ($payer, $payee, $value) {
            
            $payer->wallet->decrement('balance', $value);
            
            $payee->wallet->increment('balance', $value);

            return Transfer::create([
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value' => $value
            ]);
        });

        $this->sendNotification();

        return $result;
    }

    public function authorizeTransaction()
    {
        // O URL do autorizador simulado (verifique no PDF do seu desafio)
        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        if ($response->ok() && $response->json('data.authorization') === true) {
            return true;
        }

        return false;
    }

    public function sendNotification()
    {
        try {
            Http::post('https://util.devi.tools/api/v1/notify');
        } catch (Exception $e) {
            \Log::error("Falha ao enviar notificação");
        }
    }
}