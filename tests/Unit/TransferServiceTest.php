<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\TransferService;
use Exception;

class TransferServiceTest extends TestCase
{
    public function test_it_should_fail_when_merchant_tries_to_transfer()
    {
        $service = new TransferService();
        
        $merchant = new User(['type' => 'merchant']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Lojistas não podem realizar transferências.");
    }
}