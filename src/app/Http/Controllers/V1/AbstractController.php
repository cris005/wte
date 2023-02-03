<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\Ledger\InvalidFeesException;
use App\Exceptions\Wallet\AccountNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Factory\LoggerTrait;
use App\Models\V1\Journal;
use Illuminate\Http\JsonResponse;

abstract class AbstractController extends Controller
{
    use LoggerTrait;

    public const MSG_ERR_INTERNAL = 'Internal exception';

    protected string $transactionType = '';
    protected string $responseKey = '';

    /**
     * Validate that the fee accounts exist
     *
     * @param array|null $fees
     * @return void
     * @throws InvalidFeesException
     */
    protected function validateFees(?array $fees): void
    {
        if (empty($fees)) {
            return;
        }

        try {
            foreach ($fees as $account => $fee) {
                \App\Models\User\Wallet::fetch($account);
            }
        } catch (AccountNotFoundException $e) {
            $this->log()->error('Invalid fee structure', ['fees' => $fees]);
            throw new InvalidFeesException($e);
        }
    }

    /**
     * Build and log an error response
     *
     * @param string $message
     * @param int|null $responseCode
     * @param array $additionalDetails
     * @return JsonResponse
     */
    protected function error(string $message, ?int $responseCode = null, array $additionalDetails = []): JsonResponse
    {
        $responseCode = $responseCode ?? config('codes.error.internal');

        $refNum = Journal::createRefNum();
        $body = [$this->responseKey => array_merge([
            'message'       => $message,
            'RefNum'        => $refNum,
            'STATUS'        => config('codes.status.failed'),
            'RESPONSE_CODE' => $responseCode,
        ], $additionalDetails)];

        $this->log()->error('Process failed', [
            'ref_no' => $refNum,
            'type'   => $this->transactionType,
            'code'   => $responseCode
        ]);
        return response()->json($body);
    }

    /**
     * Build and log a success response
     *
     * @param int $refNum
     * @param array $additionalDetails
     * @return JsonResponse
     */
    protected function success(int $refNum, array $additionalDetails = []): JsonResponse
    {
        $successCode = config('codes.status.success');
        $body = [$this->responseKey => array_merge([
            'message'       => 'Transaction processed successfully',
            'RefNum'        => $refNum,
            'STATUS'        => $successCode,
            'RESPONSE_CODE' => $successCode,
        ], $additionalDetails)];

        $this->log()->info('Process completed', [
            'ref_no' => $refNum,
            'type'   => $this->transactionType,
            'code'   => $successCode
        ]);
        return response()->json($body);
    }

    /**
     * Log the start of a process
     *
     * @param string $transactionType
     * @return void
     */
    protected function logStart(string $transactionType): void
    {
        $this->transactionType = $transactionType;
        $this->responseKey = $transactionType . 'Response';
        $this->log()->notice('Process started', [
            'type'   => $transactionType,
        ]);
    }
}
