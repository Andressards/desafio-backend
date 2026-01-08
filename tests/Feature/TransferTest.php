<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_between_common_users_is_successful()
    {
        Http::fake([
            'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => true]], 200),
            'https://util.devi.tools/api/v1/notify' => Http::response([], 200),
        ]);

        $payer = User::factory()->create(['type' => 'common']);
        $payer->wallet()->updateOrCreate([], ['balance' => 100.00]);

        $payee = User::factory()->create(['type' => 'common']);
        $payee->wallet()->updateOrCreate([], ['balance' => 0.00]);

        $response = $this->postJson('/api/transfer', [
            'value' => 50.00,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('wallets', ['user_id' => $payer->id, 'balance' => 50.00]);
        $this->assertDatabaseHas('wallets', ['user_id' => $payee->id, 'balance' => 50.00]);
    }
}