<?php

namespace App\Models\V2\Transaction;

use App\Models\AbstractModel;
use App\Models\Traits\HasFees;
use App\Models\Traits\HasJournalTransactions;
use App\Models\Traits\HasMetadata;
use Hidehalo\Nanoid\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Transaction extends AbstractModel
{
    use HasFees, HasMetadata, HasJournalTransactions;

    public const DEFAULT_CURRENCY_ID = 115;
    public const DEFAULT_STATUS_ID = 3;
    public const DEFAULT_ERROR_ID = 1;
    public const DEFAULT_CHANNEL_ID = 1;

    protected $table = 'transaction';

    protected $fillable = [
        'uuid',
        'ref_no',
        'user_id',
        'category_id',
        'channel_id',
        'status_id',
        'error_id',
        'debit_account_id',
        'credit_account_id',
        'amount',
        'origin_currency_id',
        'target_currency_id',
        'external_ref_no',
        'remarks',
    ];

    /** @inheritDoc */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function (Transaction $transaction) {
            $transaction->uuid = Str::orderedUuid()->toString();
            $transaction->ref_no = $transaction->generateRefNo();
            $transaction->status_id = static::DEFAULT_STATUS_ID;
            $transaction->error_id = static::DEFAULT_ERROR_ID;
            $transaction->channel_id = $transaction?->channel_id ?? static::DEFAULT_CHANNEL_ID;
            $transaction->origin_currency_id = $transaction?->origin_currency_id ?? static::DEFAULT_CURRENCY_ID;
            $transaction->target_currency_id = $transaction?->target_currency_id ?? static::DEFAULT_CURRENCY_ID;
            $transaction->user_id = $transaction?->user_id ?? Auth::id();
        });
    }

    /**
     * Generate a {@link https://github.com/ai/nanoid NanoID} string
     * to be used as the Reference Number of a Transaction Record
     *
     * @param string $prefix Alphanumeric string to prefix the NanoID generated
     * @return string The NanoID generated
     */
    private function generateRefNo(string $prefix = ''): string
    {
        $generator =  new Client();

        if (! empty($prefix)) {
            // Remove non-alphanumeric characters
            $prefix = preg_replace('/[^A-Za-z0-9]/', '', $prefix);
        } else {
            $prefix = (string) config('factory.nanoid.default_prefix');
        }

        /**
         * The prefix provides a familiar and repetitive feeling to the string.
         * This makes it "easy to overlook" in transaction history, so it is not
         * as distracting.
         */
        return $prefix . $generator->formattedId(config('factory.nanoid.allowed_chars'), 10);
    }
}
