<?php

namespace App\Http\Requests\V2\Wallet;

use App\Http\Requests\AbstractTransactionRequest;
use App\Models\User\Wallet;
use App\Models\V2\Wallet\WalletView;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ListRequest extends AbstractTransactionRequest
{
    protected string $failedAuthMessage = 'Unauthorized to fetch this Account\'s Wallet';

    /** @var Collection<Wallet> List of Wallets */
    public Collection $wallets;

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'account_no' => ['nullable', 'numeric', 'gt:0', 'max_digits:12']
        ];
    }

    /** @inheritDoc */
    public function bodyParameters(): array
    {
        return [
            'account_no' => [
                'description' => 'The Wallet\'s Account Number to be fetched. Will default to all accounts, if not provided.',
                'example' => '010010586384',
            ],
        ];
    }

    /** @inheritDoc */
    public function authorize(): bool
    {
        $wallets = WalletView::where(['user_id' => Auth::id()])->get();
        $this->wallets = $wallets;
        return ! $wallets->isEmpty();
    }
}
