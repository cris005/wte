<?php

namespace App\Models\V1;

use App\Exceptions\Journal\InvalidRefNumException;
use App\Exceptions\Wallet\AccountNotFoundException;
use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Http\Factory\LoggerTrait;
use App\Models\User\Wallet;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @property int    $ID
 * @property int    $REFERENCE_NO
 * @property int    $API_USER_ID
 * @property int    $TRANSACTION_TYPE
 * @property int    $DEBIT_CLIENT_ID
 * @property string $DEBIT_ACCOUNT_NO
 * @property string $DEBIT_AMOUNT
 * @property string $DEBIT_BEG_BAL
 * @property string $DEBIT_END_BAL
 * @property int    $CREDIT_CLIENT_ID
 * @property string $CREDIT_ACCOUNT_NO
 * @property string $CREDIT_AMOUNT
 * @property string $CREDIT_BEG_BAL
 * @property string $CREDIT_END_BAL
 * @property int    $CURRENCY_ID
 * @property int    $STATUS
 * @property int    $REVERSE_JOURNAL_ID
 * @property Carbon $DATETIME_CREATED
 * @property Carbon $DATETIME_MODIFIED
 */
class Journal extends Model
{
    use LoggerTrait;

    public const CREATED_AT = 'DATETIME_CREATED';
    public const UPDATED_AT = 'DATETIME_MODIFIED';

    public const TYPE_TRANSFER   = 200;
    public const TYPE_REVERSAL   = 210;
    public const TYPE_FEE_CHARGE = 300;
    public const DEFAULT_CURRENCY_ID = 115;
    public const DEFAULT_CLIENT_ID = 1;

    protected $connection = 'app';
    protected $table = 'WJOURNALS';
    protected $primaryKey = 'ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'REFERENCE_NO',
        'TRANSACTION_TYPE',
        'DEBIT_CLIENT_ID',
        'DEBIT_ACCOUNT_NO',
        'DEBIT_AMOUNT',
        'DEBIT_BEG_BAL',
        'DEBIT_END_BAL',
        'CREDIT_CLIENT_ID',
        'CREDIT_ACCOUNT_NO',
        'CREDIT_AMOUNT',
        'CREDIT_BEG_BAL',
        'CREDIT_END_BAL',
        'CURRENCY_ID',
        'STATUS',
        'REVERSE_JOURNAL_ID',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ID'                 => 'integer',
        'REFERENCE_NO'       => 'integer',
        'API_USER_ID'        => 'integer',
        'TRANSACTION_TYPE'   => 'integer',
        'DEBIT_CLIENT_ID'    => 'integer',
        'DEBIT_ACCOUNT_NO'   => 'string',
        'DEBIT_AMOUNT'       => 'string',
        'DEBIT_BEG_BAL'      => 'string',
        'DEBIT_END_BAL'      => 'string',
        'CREDIT_ACCOUNT_NO'  => 'string',
        'CREDIT_AMOUNT'      => 'string',
        'CREDIT_BEG_BAL'     => 'string',
        'CREDIT_END_BAL'     => 'string',
        'CURRENCY_ID'        => 'integer',
        'STATUS'             => 'integer',
        'REVERSE_JOURNAL_ID' => 'integer',
        'DATETIME_CREATED'   => 'datetime',
        'DATETIME_MODIFIED'  => 'datetime',
    ];

    /**
     * Fetch all transaction records related to a reference number
     *
     * @param int $refNum
     * @param array $types Transaction code types
     * @return Collection
     * @throws InvalidRefNumException
     */
    public static function fetch(int $refNum, array $types = [200, 300]): Collection
    {
        $transactions = self::query()
            ->where(['REFERENCE_NO' => $refNum])
            ->whereIn('TRANSACTION_TYPE', $types)
            ->get();

        if ($transactions->isEmpty()) {
            throw new InvalidRefNumException();
        }

        return $transactions;
    }

    /**
     * Generate a Journal transaction reference number
     *
     * @return int
     * @throws ModelNotFoundException
     */
    public static function createRefNum(): int
    {
        return Sequence::generate();
    }

    /**
     * Orchestrate the processes related to a transfer of funds
     *
     * @param string $debitAccountNo
     * @param string $creditAccountNo
     * @param Money $amount
     * @param array|null $fees
     * @return Journal
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function transfer(
        string $debitAccountNo,
        string $creditAccountNo,
        Money $amount,
        ?array $fees = null
    ): Journal
    {
        // Ensure the User has enough balance to fulfil the transaction
        $sender = Wallet::fetch($debitAccountNo);

        // Ensure no credit-debit is performed to the same wallet
        if (isset($fees[$debitAccountNo])) {
            unset($fees[$debitAccountNo]);
        }

        if (! $sender->canDebit($amount->plus(self::totalFees($fees)))) {
            throw new InsufficientBalanceException();
        }

        // Create the ledger transaction record
        $rawAmount = (string) $amount->getAmount();
        $transaction = self::create([
            'REFERENCE_NO' => self::createRefNum(),
            'TRANSACTION_TYPE' => self::TYPE_TRANSFER,
            'DEBIT_ACCOUNT_NO' => $debitAccountNo,
            'DEBIT_AMOUNT' => $rawAmount,
            'CREDIT_ACCOUNT_NO' => $creditAccountNo,
            'CREDIT_AMOUNT' => $rawAmount,
            'CURRENCY_ID' => self::DEFAULT_CURRENCY_ID,
        ]);

        // Transfer the fees and the transaction amount
        $transaction
            ->moveFunds()
            ->transferFees($fees);

        return $transaction;
    }

    /**
     * Reverse a transaction
     *
     * @return Journal
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     */
    public function reverse(): Journal
    {
        $reversedTransaction = self::create([
            'REFERENCE_NO' => $this->REFERENCE_NO,
            'TRANSACTION_TYPE' => self::TYPE_REVERSAL,
            'DEBIT_ACCOUNT_NO' => $this->CREDIT_ACCOUNT_NO,
            'DEBIT_AMOUNT' => $this->CREDIT_AMOUNT,
            'CREDIT_ACCOUNT_NO' => $this->DEBIT_ACCOUNT_NO,
            'CREDIT_AMOUNT' => $this->DEBIT_AMOUNT,
            'CURRENCY_ID' => self::DEFAULT_CURRENCY_ID,
        ]);

        return $reversedTransaction->moveFunds();
    }

    /**
     * Move funds between 2 Wallets
     *
     * @return static
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    private function moveFunds(): static
    {
        try {
            $this->debit();
        } catch (Exception $e) {
            $this->log()->error('Failed to debit account', [
                'ref_no' => $this->REFERENCE_NO,
                'account_no' => $this->DEBIT_ACCOUNT_NO,
                'amount' => $this->DEBIT_AMOUNT,
            ]);
            throw $e;
        }

        try {
            $this->credit();
        } catch (Exception $e) {
            $this->log()->error('Failed to credit account', [
                'ref_no' => $this->REFERENCE_NO,
                'account_no' => $this->CREDIT_ACCOUNT_NO,
                'amount' => $this->CREDIT_AMOUNT,
            ]);
            $this->reverseDebit();
            throw $e;
        }

        $this->STATUS = 1;
        $this->save();

        return $this;
    }

    /**
     * Execute the debit part of a transaction
     *
     * @return void
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    private function debit(): void
    {
        $sender = Wallet::fetch($this->DEBIT_ACCOUNT_NO);
        $this->DEBIT_BEG_BAL = $sender->BALANCE;
        $sender->debit(Money::of($this->DEBIT_AMOUNT, 'PHP'));
        $this->DEBIT_END_BAL = $sender->BALANCE;

        $this->log()->info('Account debited', [
            'ref_no' => $this->REFERENCE_NO,
            'account_no' => $this->DEBIT_ACCOUNT_NO,
            'amount' => $this->DEBIT_AMOUNT,
            'beg_balance' => $this->DEBIT_BEG_BAL,
            'end_bal' => $this->DEBIT_END_BAL
        ]);
    }

    /**
     * Check if this transaction has been reversed before
     *
     * @param int $refNum
     * @return bool
     */
    public static function canReverse(int $refNum): bool
    {
        return self::query()->where(['REFERENCE_NO' => $refNum, 'TRANSACTION_TYPE' => 210])->get()->isEmpty();
    }

    /**
     * Reverse a debit; used when the credit part of the transaction fails
     *
     * @return void
     * @throws AccountNotFoundException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    private function reverseDebit(): void
    {
        $sender = Wallet::fetch($this->DEBIT_ACCOUNT_NO);
        $this->DEBIT_BEG_BAL = $sender->BALANCE;
        $sender->credit(Money::of($this->DEBIT_AMOUNT, 'PHP'));
        $this->DEBIT_END_BAL = $sender->BALANCE;

        $this->log()->info('Reversed debit', [
            'ref_no' => $this->REFERENCE_NO,
            'account_no' => $this->DEBIT_ACCOUNT_NO,
            'amount' => $this->DEBIT_AMOUNT,
            'beg_balance' => $this->DEBIT_BEG_BAL,
            'end_bal' => $this->DEBIT_END_BAL
        ]);
    }

    /**
     * Execute the credit part of a transaction
     *
     * @return void
     * @throws AccountNotFoundException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    private function credit(): void
    {
        $beneficiary = Wallet::fetch($this->CREDIT_ACCOUNT_NO);
        $this->CREDIT_BEG_BAL = $beneficiary->BALANCE;
        $beneficiary->credit(Money::of($this->CREDIT_AMOUNT, 'PHP'));
        $this->CREDIT_END_BAL = $beneficiary->BALANCE;

        $this->log()->info('Account credited', [
            'ref_no' => $this->REFERENCE_NO,
            'account_no' => $this->CREDIT_ACCOUNT_NO,
            'amount' => $this->CREDIT_AMOUNT,
            'beg_balance' => $this->CREDIT_BEG_BAL,
            'end_bal' => $this->CREDIT_END_BAL
        ]);
    }

    /**
     * Transfer the fees to the relevant accounts
     *
     * @param array|null $fees
     * @return void
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    private function transferFees(?array $fees): void
    {
        if (empty($fees)) {
            return;
        }

        // Execute a transaction for every fee
        foreach ($fees as $creditAccountNo => $feeAmount) {
            if (Money::of($feeAmount, 'PHP')->isPositive()) {
                $feeTransaction = self::create([
                    'REFERENCE_NO' => $this->REFERENCE_NO,
                    'TRANSACTION_TYPE' => self::TYPE_FEE_CHARGE,
                    'DEBIT_ACCOUNT_NO' => $this->DEBIT_ACCOUNT_NO,
                    'DEBIT_AMOUNT' => $feeAmount,
                    'CREDIT_ACCOUNT_NO' => $creditAccountNo,
                    'CREDIT_AMOUNT' => $feeAmount,
                    'CURRENCY_ID' => self::DEFAULT_CURRENCY_ID,
                ]);
                $feeTransaction->moveFunds();
            }
        }
    }

    /**
     * Compute the total value of the fees
     *
     * @param array|null $feeStructure
     * @return Money
     * @throws UnknownCurrencyException
     */
    private static function totalFees(?array $feeStructure): Money
    {
        $total = ! empty($feeStructure) ? array_sum($feeStructure) : 0;
        return Money::of($total, 'PHP');
    }
}
