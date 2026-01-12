<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class TransferServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_fail_when_merchant_tries_to_transfer()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Lojistas não podem realizar transferências.");

        $service = new TransferService();

        $merchant = User::factory()->create(['type' => 'merchant']);
        $payee = User::factory()->create(['type' => 'common']);

        $service->execute([
            'payer' => $merchant->id,
            'payee' => $payee->id,
            'value' => 10.00
        ]);
    }
}