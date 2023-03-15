<?php

namespace App\Http\Util;

use App\Events\ReversalProcessed;
use App\Events\TransferProcessed;
use App\Exceptions\Journal\EmptyJournalException;
use App\Exceptions\Journal\InvalidReversalException;
use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Http\Factory\LoggerTrait;
use App\Models\User\Wallet;
use App\Models\V2\Journal\FeeTransaction;
use App\Models\V2\Journal\FundTransaction;
use App\Models\V2\Journal\JournalTransaction;
use App\Models\V2\Journal\ReversalTransaction;
use App\Models\V2\Transaction\Fee;
use App\Models\V2\Transaction\Transaction;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Collection;

class TransactionProcessor
{
    use LoggerTrait;

    /** @var Collection<Fee> */
    private Collection $fees;
    private Wallet $sender;
    private Wallet $beneficiary;

    public function __construct(private Transaction $transaction)
    {
        $this->fees = $this->transaction->fees;
        $this->sender = Wallet::where(['ACCOUNT_ID' => $this->transaction->debit_account_id])->first();
        $this->beneficiary = Wallet::where(['ACCOUNT_ID' => $this->transaction->credit_account_id])->first();
    }

    /**
     * Execute all credits and debits required to process a transaction
     *
     * @return void
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function execute(): void
    {
        if (! $this->isExecutable()) {
            throw new InsufficientBalanceException();
        }

        $this->transferFunds();
        $this->transferFees();
        $this->transaction->update(['status_id' => 1]);

        // TODO: trigger transfer event
        //TransferProcessed::dispatch();
    }

    /**
     * Transfer the funds from the sender to the beneficiary
     *
     * @return void
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function transferFunds(): void
    {
        $amount = Money::of($this->transaction->amount, 'PHP');

        if (! $this->sender->canDebit($amount)) {
            throw new InsufficientBalanceException();
        }

        $journalEntry = FundTransaction::create([
            'transaction_id' => $this->transaction->id,
            'amount' => (string) $amount->getAmount(),
            'debit_account_id' => $this->sender->ACCOUNT_ID,
            'debit_beg_balance' => $this->sender->BALANCE,
            'debit_end_balance' => Money::of($this->sender->BALANCE, 'PHP')->minus($amount)->getAmount(),
            'credit_account_id' => $this->beneficiary->ACCOUNT_ID,
            'credit_beg_balance' => $this->beneficiary->BALANCE,
            'credit_end_balance' => $amount->plus(Money::of($this->beneficiary->BALANCE, 'PHP'))->getAmount(),
        ]);

        $this->debit($this->sender, $amount);
        $this->credit($this->beneficiary, $amount);
        $journalEntry->update(['status_id' => 1]);
    }

    /**
     * Transfer the funds from the sender to the fee beneficiaries
     *
     * @return void
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function transferFees(): void
    {
        if ($this->fees->isEmpty()) {
            return;
        }

        $total = Money::of($this->fees->sum('amount'), 'PHP');
        if (! $this->sender->canDebit($total)) {
            throw new InsufficientBalanceException();
        }

        foreach ($this->fees as $fee) {
            $amount = Money::of($fee->amount, 'PHP');
            $feeBeneficiary = Wallet::where(['ACCOUNT_ID' => $fee->account_id])->first();
            $journalEntry = FeeTransaction::create([
                'transaction_id' => $this->transaction->id,
                'amount' => (string) $amount->getAmount(),
                'debit_account_id' => $this->sender->ACCOUNT_ID,
                'debit_beg_balance' => $this->sender->BALANCE,
                'debit_end_balance' => Money::of($this->sender->BALANCE, 'PHP')->minus($amount)->getAmount(),
                'credit_account_id' => $feeBeneficiary->ACCOUNT_ID,
                'credit_beg_balance' => $feeBeneficiary->BALANCE,
                'credit_end_balance' => $amount->plus(Money::of($feeBeneficiary->BALANCE, 'PHP'))->getAmount(),
            ]);

            $this->debit($this->sender, $amount);
            $this->credit($feeBeneficiary, $amount);
            $journalEntry->update(['status_id' => 1]);
        }
    }

    /**
     * Reverse debit/credit records of the transaction
     *
     * @param int $statusId Final status ID to assign the record once fully processed
     * @return void
     * @throws EmptyJournalException
     * @throws InsufficientBalanceException
     * @throws InvalidReversalException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function reverse(int $statusId = 2): void
    {
        $journalEntries = $this->transaction->journalTransactions;

        if ($journalEntries->isEmpty()) {
            throw new EmptyJournalException();
        }

        if (! $this->canReverse()) {
            throw new InvalidReversalException();
        }

        foreach ($journalEntries as $entry) {
            $amount = Money::of($entry->amount, 'PHP');
            $debitBegBalance = Money::of($entry->creditWallet->BALANCE, 'PHP');
            $creditBegBalance = Money::of($entry->debitWallet->BALANCE, 'PHP');
            $reversalEntry = ReversalTransaction::create([
                'transaction_id' => $this->transaction->id,
                'amount' => (string) $amount->getAmount(),
                'debit_account_id' => $entry->credit_account_id,
                'debit_beg_balance' => $debitBegBalance->getAmount(),
                'debit_end_balance' => $debitBegBalance->minus($amount)->getAmount(),
                'credit_account_id' => $entry->debit_account_id,
                'credit_beg_balance' => $creditBegBalance->getAmount(),
                'credit_end_balance' => $creditBegBalance->plus($amount)->getAmount(),
            ]);
            $this->debit($entry->creditWallet, $amount);
            $this->credit($entry->debitWallet, $amount);
            $reversalEntry->update(['status_id' => 1]);
        }

        $this->transaction->update(['status_id' => $statusId]);

        // TODO: trigger reversed event
        //ReversalProcessed::dispatch();
    }

    /**
     * Validate whether the debits of this transaction are executable
     *
     * @return bool
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function isExecutable(): bool
    {
        $amount = Money::of($this->transaction->amount, 'PHP');

        if (! $this->fees->isEmpty()) {
            foreach ($this->fees as $fee) {
                $feeAmount = Money::of($fee->amount, 'PHP');
                $amount = $amount->plus($feeAmount);
            }
        }

        return $this->sender->canDebit($amount);
    }

    /**
     * Validate whether the transaction can be reversed or not
     *
     * @return bool
     */
    private function canReverse(): bool
    {
        $params = [
            'type_id' => 210,
            'transaction_id' => $this->transaction->id
        ];
        return JournalTransaction::where($params)->get()->isEmpty();
    }

    /**
     * @param Wallet $wallet
     * @param Money $amount
     * @return void
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     * @throws InsufficientBalanceException
     */
    private function debit(Wallet $wallet, Money $amount): void
    {
        $data = [
            'transaction_id' => $this->transaction->id,
            'account_no' => $wallet->ACCOUNT_NO,
            'amount' => (string) $amount->getAmount(),
            'beg_balance' => $wallet->BALANCE,
        ];

        $wallet->debit($amount);

        $data['end_bal'] = $wallet->BALANCE;
        $this->log()->info('Account debited', $data);
    }

    /**
     * @param Wallet $wallet
     * @param Money $amount
     * @return void
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    private function credit(Wallet $wallet, Money $amount): void
    {
        $data = [
            'transaction_id' => $this->transaction->id,
            'account_no' => $wallet->ACCOUNT_NO,
            'amount' => (string) $amount->getAmount(),
            'beg_balance' => $wallet->BALANCE,
        ];

        $wallet->credit($amount);

        $data['end_bal'] = $wallet->BALANCE;
        $this->log()->info('Account credited', $data);
    }
}
