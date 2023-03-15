<?php

namespace App\Http\Controllers\V2;

use App\Exceptions\Transaction\NotFoundException;
use App\Exceptions\Transaction\UnauthorizedUserException;
use App\Exceptions\Wallet\AccountNotFoundException;
use App\Http\Controllers\AbstractRestController;
use App\Http\Requests\V2\Journal\FetchRequest;
use App\Http\Requests\V2\Journal\ListRequest;
use App\Models\V2\Journal\JournalTransaction;
use App\Models\V2\Transaction\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class JournalController extends AbstractRestController
{
    /**
     * Fetch a specific Wallet Journal Record
     *
     * @param FetchRequest $request
     * @param string $journalUuid
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function fetch(FetchRequest $request, string $journalUuid): JsonResponse
    {
        $walletIdList = Auth::user()->wallets->pluck('ACCOUNT_ID');
        $entry = JournalTransaction::where(['uuid' => $journalUuid])
            ->where(
                function (Builder $query) use ($walletIdList) {
                    return $query
                        ->whereIn('debit_account_id', $walletIdList)
                        ->orWhereIn('credit_account_id', $walletIdList);
                })
            ->first();

        if ($entry === null) {
            throw new NotFoundException();
        }

        return $this->success($entry);
    }

    /**
     * Fetch all Wallet Journal Records that belong to a
     * given User qnd search based on parameters
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function fetchAll(ListRequest $request): JsonResponse
    {
        $walletIdList = Auth::user()->wallets->pluck('ACCOUNT_ID');
        $entries = JournalTransaction::where($request->params)
            ->where(function (Builder $query) use ($walletIdList) {
                return $query
                    ->whereIn('debit_account_id', $walletIdList)
                    ->orWhereIn('credit_account_id', $walletIdList);
            })
            ->orderBy('id', 'desc')
            ->paginate(25);

        return $this->success($entries, 'journal_entries');
    }

    /**
     * Fetch all Wallet Journal Records that belong to a
     * given Wallet
     *
     * @param ListRequest $request
     * @param int $walletId
     * @return JsonResponse
     * @throws AccountNotFoundException
     */
    public function fetchFromWallet(ListRequest $request, int $walletId): JsonResponse
    {
        $wallet = Auth::user()->wallets->where('ACCOUNT_ID', $walletId)->first();
        if ($wallet === null) {
            throw new AccountNotFoundException();
        }

        $entries = JournalTransaction::where($request->params)
            ->where(['debit_account_id' => $wallet->ACCOUNT_ID])
            ->orWhere(['credit_account_id' => $wallet->ACCOUNT_ID])
            ->orderBy('id', 'desc')
            ->paginate(25);

        return $this->success($entries, 'journal_entries');
    }

    /**
     * Fetch all Wallet Journal Records that belong to a
     * given Transaction
     *
     * @param FetchRequest $request
     * @param string $transactionUuid
     * @return JsonResponse
     * @throws NotFoundException|UnauthorizedUserException
     */
    public function fetchFromTransaction(FetchRequest $request, string $transactionUuid): JsonResponse
    {
        $walletIdList = Auth::user()->wallets->pluck('ACCOUNT_ID');
        $transaction = Transaction::where(['uuid' => $transactionUuid])
            ->where(
                function (Builder $query) use ($walletIdList) {
                    return $query
                        ->whereIn('debit_account_id', $walletIdList)
                        ->orWhereIn('credit_account_id', $walletIdList);
                })
            ->first();

        if ($transaction === null) {
            throw new NotFoundException();
        }

        return $this->success($transaction->journalTransactions, 'journal_entries');
    }
}
