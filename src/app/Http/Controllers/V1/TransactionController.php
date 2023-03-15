<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\Journal\InvalidFeesException;
use App\Exceptions\Wallet\AccountNotFoundException;
use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Http\Requests\V1\BalanceInquiryRequest;
use App\Http\Requests\V1\ReversalRequest;
use App\Http\Requests\V1\TransferRequest;
use App\Models\V1\Journal;
use Brick\Money\Money;
use Exception;
use Illuminate\Http\JsonResponse;

class TransactionController extends AbstractController
{
    /**
     * Execute the fund movements (credits and debits) required to
     * complete the transaction associated to the provided Ref. No.
     *
     * @param TransferRequest $request
     * @return JsonResponse
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        $this->logStart($request->transactionType);
        $data = $request->validated();

        // Add the "Fee" to the "Accounts" fee structure
        $feeAccount = config('accounts.fees.default');
        if (! empty($data['Fee']) && ! isset($data['Accounts'][$feeAccount])) {
            $data['Accounts'][$feeAccount] = $data['Fee'];
        }

        try {
            $this->validateFees($data['Accounts'] ?? null);

            $transaction = Journal::transfer(
                $data['Source'],
                $data['Target'],
                Money::of($data['Amount'], 'PHP'),
                $data['Accounts'] ?? null
            );
        } catch (AccountNotFoundException|InsufficientBalanceException|InvalidFeesException $e) {
            return $this->error($e->getMessage(), $e->getCode(), [
                'Source'        => null,
                'Target'        => null,
                'Amount'        => 0,
                'Fee'           => 0,
            ]);
        } catch (Exception $e) {
            $this->log()->exception(self::MSG_ERR_INTERNAL, $e);
            return $this->error(self::MSG_ERR_INTERNAL);
        }

        return $this->success($transaction->REFERENCE_NO, [
            'Source'        => $data['Source'],
            'Target'        => $data['Target'],
            'Amount'        => $data['Amount'],
            'Fee'           => $data['Fee'] ?? 0,
        ]);
    }

    /**
     * Reverse/refund a transaction that has been executed
     *
     * @param ReversalRequest $request
     * @return JsonResponse
     */
    public function reverse(ReversalRequest $request): JsonResponse
    {
        $this->logStart($request->transactionType);

        try {
            foreach ($request->transactions as $transaction) {
                $transaction->reverse();
            }
        } catch (AccountNotFoundException|InsufficientBalanceException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            $this->log()->exception(self::MSG_ERR_INTERNAL, $e);
            return $this->error(self::MSG_ERR_INTERNAL);
        }

        return $this->success($request->validated('RefNum'));
    }

    /**
     * Find the balance for the given Account number
     *
     * @param BalanceInquiryRequest $request
     * @return JsonResponse
     */
    public function balanceInquiry(BalanceInquiryRequest $request): JsonResponse
    {
        $this->logStart($request->transactionType);
        return $this->success(Journal::createRefNum(), [
            'ACCOUNT_STATUS' => $request->wallet->STATUS_ID,
            'ACCOUNT_NO' => $request->wallet->ACCOUNT_NO,
            'BALANCE' => $request->wallet->BALANCE,
            'Status' => config('codes.status.success'),
            'AVAILABLE_BALANCE' => $request->wallet->BALANCE,
            'AvailableBalance' => $request->wallet->BALANCE
        ]);
    }
}
