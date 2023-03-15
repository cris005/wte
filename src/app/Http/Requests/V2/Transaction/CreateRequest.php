<?php

namespace App\Http\Requests\V2\Transaction;

use App\Http\Requests\AbstractTransactionRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends AbstractTransactionRequest
{
    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'user_id'            => Auth::check() ? ['prohibited', 'int', 'gt:0'] : ['required', 'int', 'gt:0', 'exists:App\Models\User\User,id'],
            'category_id'        => ['required', 'int', 'gt:0', 'exists:transaction_category,id'],
            'channel_id'         => ['nullable', 'int', 'gt:0', 'exists:transaction_channel,id'],
            'debit_account_id'   => ['required', 'int', 'gt:0', 'exists:App\Models\User\Wallet,ACCOUNT_ID'],
            'credit_account_id'  => ['required', 'int', 'gt:0', 'different:debit_account_id', 'exists:App\Models\User\Wallet,ACCOUNT_ID'],
            'amount'             => ['required', 'numeric', 'gt:0'],
            'origin_currency_id' => ['nullable', 'int', 'gt:0', 'exists:App\Models\Config\Currency,id'],
            'target_currency_id' => ['nullable', 'int', 'gt:0', 'exists:App\Models\Config\Currency,id'],
            'external_ref_no'    => ['nullable', 'string'],
            'remarks'            => ['nullable', 'string'],
            'fees'               => ['nullable', 'array', 'min:1'],
            'fees.*.account_id'  => ['required', 'int', 'gt:0', 'exists:App\Models\User\Wallet,ACCOUNT_ID'],
            'fees.*.amount'      => ['required', 'numeric', 'gt:0'],
            'fees.*.type_id'     => ['required', 'int', 'gt:0', 'exists:transaction_fee_type,id'],
            'metadata'           => ['nullable', 'array', 'min:1'],
            'metadata.*.key'     => ['required', 'string'],
            'metadata.*.value'   => ['required']
        ];
    }

    /** @inheritDoc */
    public function authorize(): bool
    {
        $user = Auth::user() ?? User::find($this->validated('user_id'));
        $wallet = $user->wallets->firstWhere('ACCOUNT_ID', $this->validated('debit_account_id'));
        return ! empty($wallet);
    }
}
