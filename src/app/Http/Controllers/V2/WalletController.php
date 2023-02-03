<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\AbstractRestController;
use App\Http\Requests\V2\BalanceRequest;
use App\Http\Requests\V2\GetWalletsRequest;
use Illuminate\Http\JsonResponse;

class WalletController extends AbstractRestController
{
    /**
     * Fetch details of a User's specific Wallet
     *
     * @param GetWalletsRequest $request
     * @param string $accountNo
     * @return JsonResponse
     */
    public function fetch(GetWalletsRequest $request, string $accountNo): JsonResponse
    {
        $user = auth()->user();
        $wallet = $accountNo === $user->account_no
            ? $user->mainWallet
            : $user->wallets->where('ACCOUNT_NO', $accountNo);

        return $this->success(['wallet' => $wallet]);
    }

    /**
     * Fetch details of all Wallets that belong to User
     *
     * @param GetWalletsRequest $request
     * @return JsonResponse
     */
    public function fetchAll(GetWalletsRequest $request): JsonResponse
    {
        return $this->success(['wallet' => auth()->user()->wallets]);
    }

    /**
     * Fetch the current User's Wallet Balance(s)
     *
     * @param BalanceRequest $request
     * @return JsonResponse
     */
    public function fetchBalance(BalanceRequest $request): JsonResponse
    {
        return $this->success(['balance' => auth()->user()->mainWallet->BALANCE]);
    }
}
