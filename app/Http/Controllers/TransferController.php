<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransferService;

class TransferController extends Controller
{
    public function store(Request $request, TransferService $transferService)
    {
        $transfer = $transferService->execute($request->all());

        return response()->json($transfer, 201);
    }
}