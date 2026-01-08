<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
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

        return DB::transaction(function () use ($payer, $payee, $value) {
            
            $payer->wallet->decrement('balance', $value);
            
            $payee->wallet->increment('balance', $value);

            return Transfer::create([
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value' => $value
            ]);
        });
    }
}