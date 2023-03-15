<?php

namespace App\Http\Controllers\V2;

use App\Exceptions\Wallet\AccountNotFoundException;
use App\Http\Controllers\AbstractRestController;
use App\Http\Requests\V2\Wallet\CreateRequest;
use App\Http\Requests\V2\Wallet\FetchRequest;
use App\Http\Requests\V2\Wallet\ListRequest;
use App\Models\V2\Wallet\WalletView;
use Illuminate\Http\JsonResponse;

class WalletController extends AbstractRestController
{
    /**
     * Create a new Transaction record
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        // TODO: create a new model and trigger the Created event
        return $this->success();
    }

    /**
     * Fetch the details of a given Wallet
     *
     * @param FetchRequest $request
     * @param string $walletId
     * @return JsonResponse
     * @throws AccountNotFoundException
     */
    public function fetch(FetchRequest $request, string $walletId): JsonResponse
    {
        $result = WalletView::firstWhere('id', $walletId);

        if ($result === null) {
            throw new AccountNotFoundException();
        }

        return $this->success($result);
    }

    /**
     * Fetch details of all Wallets that belong to the User
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function fetchAll(ListRequest $request): JsonResponse
    {
        return $this->success($request->wallets, 'wallets');
    }

    /**
     * Fetch the balance of a given Wallet
     *
     * @param FetchRequest $request
     * @param string $walletId
     * @return JsonResponse
     * @throws AccountNotFoundException
     */
    public function fetchBalance(FetchRequest $request, string $walletId): JsonResponse
    {
        $result = WalletView::firstWhere('id', $walletId);

        if ($result === null) {
            throw new AccountNotFoundException();
        }

        return $this->success($this->formatBalance($result));
    }

    /**
     * Fetch the current User's Wallet Balance(s)
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function fetchAllBalances(ListRequest $request): JsonResponse
    {
        $wallets = [];
        foreach ($request->wallets as $wallet) {
            $wallets[] = $this->formatBalance($wallet);
        }

        return $this->success(collect($wallets), 'balances');
    }

    /**
     * Format the balances of a given Collection of Wallets
     *
     * @param WalletView $wallet
     * @return array
     */
    private function formatBalance(WalletView $wallet): array
    {
        return [
            'account_no'       => $wallet->account_no,
            'amount_total'     => $wallet->balance,
            'amount_available' => $wallet->balance_available,
            'currency'         => 'PHP', // TODO: make this dynamic
            'currency_id'      => $wallet->currency_id,
        ];
    }
}
