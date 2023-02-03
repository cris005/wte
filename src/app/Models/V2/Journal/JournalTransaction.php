<?php

namespace App\Models\V2\Journal;

use App\Models\V2\Wallet\CreditWallet;
use App\Models\V2\Wallet\DebitWallet;
use Brick\Money\Money;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * @property int          $id
 * @property string       $uuid
 * @property int          $transaction_id
 * @property string       $transaction_ref_no
 * @property int          $type_id
 * @property Money        $amount
 * @property int          $currency_id
 * @property string       $debit_account_no
 * @property Money        $debit_beg_balance
 * @property Money        $debit_end_balance
 * @property string       $credit_account_no
 * @property Money        $credit_beg_balance
 * @property Money        $credit_end_balance
 * @property CreditWallet $creditWallet
 * @property DebitWallet  $debitWallet
 * @property int          $status_id
 * @property Carbon       $created_at
 * @property Carbon       $updated_at

 */
abstract class JournalTransaction extends AbstractJournalModel
{
    public const DEFAULT_CURRENCY_ID = 115;
    public const DEFAULT_STATUS_ID = 115;
    public const TRANSACTION_TYPE_ID = 200;

    protected $table = 'ledger_transaction';

    protected $fillable = [
        'transaction_id',
        'transaction_ref_no',
        'currency_id',
        'debit_account_no',
        'credit_account_no',
        'status_id'
    ];

    protected $casts = [
        'int'                => 'int',
        'uuid'               => 'string',
        'transaction_id'     => 'int',
        'transaction_ref_no' => 'string',
        'type_id'            => 'int',
        'currency_id'        => 'int',
        'debit_account_no'   => 'string',
        'credit_account_no'  => 'string',
        'status_id'          => 'int',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    /** @inheritDoc */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function (JournalTransaction $transaction) {
            $transaction->forceFill([
                'uuid'    => Uuid::uuid4()->toString(),
                'type_id' => static::TRANSACTION_TYPE_ID,
            ]);

            $transaction->fill([
                'status_id'   => static::DEFAULT_STATUS_ID,
                'currency_id' => static::DEFAULT_CURRENCY_ID,
            ]);
        });
    }

    public function process(): void
    {
        $this->debit();
        $this->credit();
    }

    public function debit(): void
    {
        // ...
    }

    public function credit(): void
    {
        // ...
    }

    public static function fetch(): static
    {
        // ...
    }
}
