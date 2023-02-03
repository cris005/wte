<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\AbstractRestController;
use App\Http\Requests\V2\BalanceRequest;
use App\Http\Requests\V2\GetTransactionsRequest;
use App\Http\Requests\V2\ReversalRequest;
use App\Http\Requests\V2\TransferRequest;
use Illuminate\Http\JsonResponse;

class TransactionController extends AbstractRestController
{
    /**
     * Execute the fund movements of a given Bizmoto Transaction
     *
     * @param TransferRequest $request
     * @return JsonResponse
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        return $this->success();
    }

    /**
     * Reverse/refund a transaction that has been executed
     *
     * @param ReversalRequest $request
     * @return JsonResponse
     */
    public function reverse(ReversalRequest $request): JsonResponse
    {
        return $this->success();
    }

    /**
     * Fetch all Wallet Journal Records that belong
     * to a Transaction reference number
     *
     * @param GetTransactionsRequest $request
     * @return JsonResponse
     */
    public function fetch(GetTransactionsRequest $request): JsonResponse
    {
        return $this->success();
    }
}
