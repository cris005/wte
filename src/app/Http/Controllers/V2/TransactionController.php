<?php

namespace App\Http\Controllers\V2;

use App\Exceptions\Transaction\NotFoundException;
use App\Exceptions\Wallet\AccountNotFoundException;
use App\Http\Controllers\AbstractRestController;
use App\Http\Requests\V2\Transaction\CreateRequest;
use App\Http\Requests\V2\Transaction\FetchRequest;
use App\Http\Requests\V2\Transaction\ListRequest;
use App\Http\Requests\V2\Transaction\ReversalRequest;
use App\Http\Requests\V2\Transaction\TransferRequest;
use App\Http\Requests\V2\Transaction\UpdateRequest;
use App\Http\Util\TransactionProcessor;
use App\Models\V2\Transaction\Fee;
use App\Models\V2\Transaction\Metadata;
use App\Models\V2\Transaction\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends AbstractRestController
{
    /**
     * Create a new Transaction record
     *
     * @param CreateRequest $request
     * @return JsonResponse
     * @bodyParam fees object[] List of transaction fees. Example: [{"account_id": 158930, "amount": 10.20, "type_id": 1}]
     * @bodyParam metadata object[] List of metadata entries. Example: [{"key": "partner_transaction_details", "value": {"bank_swift": "CUOBPHM2","recipient_name": "Juan dela Cruz","recipient_address": "300 Juan dela Street, WA 6021"}}]
     */
    public function create(CreateRequest $request): JsonResponse
    {
        $transaction = Transaction::create($request->validated());

        $fees = $request->validated('fees');
        if ($fees !== null) {
            foreach ($fees as $fee) {
                $fee['transaction_id'] = $transaction->id;
                Fee::create($fee);
            }
        }

        $metadata = $request->validated('metadata');
        if ($metadata !== null) {
            foreach ($metadata as $record) {
                $record['transaction_id'] = $transaction->id;
                Metadata::create($record);
            }
        }

        return $this->success([
            'uuid'      => $transaction->uuid,
            'ref_no'    => $transaction->ref_no,
            'status_id' => $transaction->status_id,
            'error_id'  => $transaction->error_id,
        ]);
    }

    /**
     * Update the values of a given Transaction record
     *
     * @param UpdateRequest $request
     * @param string $transactionUuid
     * @return JsonResponse
     * @throws NotFoundException
     * @response 204
     */
    public function update(UpdateRequest $request, string $transactionUuid): JsonResponse
    {
        $transaction = Transaction::where(['uuid' => $transactionUuid])->first();

        if ($transaction === null) {
            throw new NotFoundException();
        }

        $transaction->update($request->validated());
        return $this->success();
    }

    /**
     * Execute the fund movements of a given Transaction Record
     *
     * @param TransferRequest $request
     * @return JsonResponse
     * @throws Exception
     * @response 204
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        $processor = new TransactionProcessor($request->transaction);
        $processor->execute();
        return $this->successNoContent();
    }

    /**
     * Reverse/refund a transaction that has been executed
     *
     * @param ReversalRequest $request
     * @return JsonResponse
     * @throws Exception
     * @response 204
     */
    public function reverse(ReversalRequest $request): JsonResponse
    {
        $processor = new TransactionProcessor($request->transaction);
        $processor->reverse();
        return $this->successNoContent();
    }

    /**
     * Fetch a specific Transaction record
     *
     * @param FetchRequest $request
     * @param string $transactionUuid
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function fetch(FetchRequest $request, string $transactionUuid): JsonResponse
    {
        $user = Auth::user();
        $walletIdList = $user->wallets->pluck('ACCOUNT_ID');
        $transaction = Transaction::where(['uuid' => $transactionUuid])
            ->where(
                function (Builder $query) use ($user, $walletIdList) {
                    return $query
                        ->where('user_id', '=', $user->id)
                        ->orwhereIn('debit_account_id', $walletIdList)
                        ->orWhereIn('credit_account_id', $walletIdList);
                })
            ->first();

        if ($transaction === null) {
            throw new NotFoundException();
        }

        $includeFees = $request->validated('include_fees', true);
        $includeMeta = $request->validated('include_meta', true);

        // TODO: automatically set HAL relationships from Model
        if ($includeFees) {
            $embedded['fees'] = $transaction->fees;
            $transaction->unsetRelation('fees');
        }

        if ($includeMeta) {
            $embedded['meta'] = $transaction->meta;
            $transaction->unsetRelation('meta');
        }

        if ($includeFees || $includeMeta) {
            $transaction->setRelation('_embedded', collect($embedded));
        }

        return $this->success($transaction);
    }

    /**
     * Fetch all Transaction records that belong to a User
     *
     * @param ListRequest $request
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function fetchAll(ListRequest $request): JsonResponse
    {
        $user = Auth::user();
        $walletIdList = $user->wallets->pluck('ACCOUNT_ID');
        $transactions = Transaction::where($request->params)
            ->where(function (Builder $query) use ($walletIdList) {
                return $query
                    ->whereIn('debit_account_id', $walletIdList)
                    ->orWhereIn('credit_account_id', $walletIdList);
            })
            ->orderBy('id', 'desc')
            ->paginate(25);

        if ($transactions->isEmpty()) {
            throw new NotFoundException();
        }

        return $this->success($transactions, 'transactions');
    }

    /**
     * Fetch all Transaction records that belong to a Wallet
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

        $transactions = Transaction::where($request->params)
            ->where(['debit_account_id' => $wallet->ACCOUNT_ID])
            ->orWhere(['credit_account_id' => $wallet->ACCOUNT_ID])
            ->orderBy('id', 'desc')
            ->paginate(25);

        return $this->success($transactions, 'transactions');
    }
}
