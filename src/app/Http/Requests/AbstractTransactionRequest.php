<?php

namespace App\Http\Requests;

use App\Exceptions\JsonResponseException;
use App\Exceptions\Transaction\NotFoundException;
use App\Http\Factory\JsonResponseFactory;
use App\Http\Factory\LoggerTrait;
use App\Models\V2\Transaction\Transaction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Precognition;
use Illuminate\Support\Facades\Auth;
use Throwable;

abstract class AbstractTransactionRequest extends FormRequest
{
    use LoggerTrait;

    public Transaction $transaction;

    /**
     * Message to show upon failed authorization check
     *
     * @see AbstractTransactionRequest::failedAuthorization()
     */
    protected string $failedAuthMessage = 'This User is not allowed to move funds from another User\'s Account.';

    /**
     * Orchestrate the validation and authorization workflow
     *
     * @return void
     * @throws JsonResponseException When a known Wallet or Journal exception is met
     * @throws AuthorizationException When a generic authorization failure is met
     * @throws Throwable When any other exception is met
     */
    public function validateResolved(): void
    {
        $this->prepareForValidation();

        $validator = $this->getValidatorInstance();
        if ($this->isPrecognitive()) {
            $validator->after(Precognition::afterValidationHook($this));
        }

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }
        $this->passedValidation();

        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }
    }

    /**
     * Perform the payload validation check
     *
     * @return array
     * @see AbstractTransactionRequest::failedValidation()
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Documentation for the Request's parameters
     *
     * @return array[]
     */
    public function bodyParameters(): array
    {
        return [];
    }

    /**
     * Perform the authorization check
     *
     * @return bool
     * @see AbstractTransactionRequest::failedAuthorization()
     * @throws
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Determine if the request passes the authorization checks
     *
     * @return bool
     * @throws AuthorizationException
     * @throws Throwable
     */
    protected function passesAuthorization(): bool
    {
        try {
            $passed = parent::passesAuthorization();
        } catch (Throwable $e) {
            $this->logError('Request has failed authorization');
            throw $e;
        }

        $this->logSuccess('Request has passed authorization');
        return $passed;
    }

    /** @inheritDoc */
    protected function passedValidation(): void
    {
        $this->logSuccess('Request inputs have passed validation');
    }

    /**
     * Handle a failed validation
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $this->logError('Request inputs have failed validation');
        abort(JsonResponseFactory::failedValidation($validator));
    }

    /**
     * Handle a failed authorization
     *
     * @return never
     * @throws JsonResponseException When a known Wallet or Journal exception is met
     * @throws AuthorizationException When any other exception is met
     */
    protected function failedAuthorization(): never
    {
        $this->logError('Request has failed authorization');
        abort(JsonResponseFactory::accessForbidden($this->failedAuthMessage));
    }

    protected function findTransaction(): void
    {
        $transaction = Transaction::where(['uuid' => $this->validated('transaction_uuid')])->first();
        if ($transaction === null) {
            throw new NotFoundException();
        }
        $this->transaction =  $transaction;
    }

    /**
     * Validate whether the given Transaction belongs to the current User
     *
     * @param Transaction $transaction
     * @return bool
     */
    protected function transactionBelongsToUser(Transaction $transaction): bool
    {
        return $transaction->user_id === Auth::id();
    }

    /**
     * Fetch basic identifying data about the Client
     *
     * @return array
     */
    protected function getClientInfo(): array
    {
        return [
            'request_id' => $this->attributes->get('request_id'),
        ];
    }

    protected function logError(string $message): void
    {
        $this->log()->error($message, $this->getClientInfo());
    }

    protected function logSuccess(string $message): void
    {
        $this->log()->info($message, $this->getClientInfo());
    }
}
