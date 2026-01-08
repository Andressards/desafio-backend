<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransferService;
use App\Http\Requests\TransferRequest;

class TransferController extends Controller
{
    public function store(TransferRequest $request, TransferService $transferService)
    {
        $transfer = $transferService->execute($request->validated());

        return response()->json($transfer, 201);
    }
}