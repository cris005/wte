<?php

namespace App\Http\Requests;

use App\Http\Factory\JsonResponseFactory;
use App\Models\V2\Transaction\Transaction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Precognition;
use Illuminate\Support\Facades\Auth;

abstract class AbstractTransactionRequest extends FormRequest
{
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
     * @throws AuthorizationException
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
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        abort(JsonResponseFactory::failedValidation($validator));
    }

    /**
     * Handle a failed authorization
     *
     * @return void
     */
    protected function failedAuthorization(): void
    {
        abort(JsonResponseFactory::accessForbidden($this->failedAuthMessage));
    }

    /**
     * @param array $where
     * @return bool
     */
    protected function transactionBelongsToUser(array $where): bool
    {
        $transaction = Transaction::where($where)->first();
        return $transaction->user_id === Auth::user()->id;
    }
}
