<?php

namespace App\Models\User;

use App\Exceptions\Wallet\AccountNotFoundException;
use App\Exceptions\Wallet\InsufficientBalanceException;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Wallet extends AbstractWallet
{
    /** @inheritDoc */
    public static function fetch(string $accountNo): static
    {
        try {
            return self::query()->where(['ACCOUNT_NO' => $accountNo])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new AccountNotFoundException($e);
        }
    }

    /** @inheritDoc */
    public function canDebit(Money $amount): bool
    {
        if ($this->NORMAL_BALANCE === self::BALANCE_ALLOW_NEGATIVE) {
            return true;
        }

        $begBalance = Money::of($this->BALANCE, 'PHP');
        return ! $begBalance->minus($amount)->isNegative();
    }

    /** @inheritDoc */
    public function debit(Money $amount): static
    {
        if (! $this->canDebit($amount)) {
            throw new InsufficientBalanceException();
        }

        $begBalance = Money::of($this->BALANCE, 'PHP');
        $endBalance = $begBalance->minus($amount);
        $this->BALANCE = (string) $endBalance->getAmount();
        $this->save();
        return $this;
    }

    /** @inheritDoc */
    public function credit(Money $amount): static
    {
        $begBalance = Money::of($this->BALANCE, 'PHP');
        $endBalance = $begBalance->plus($amount);
        $this->BALANCE = (string) $endBalance->getAmount();
        $this->save();
        return $this;
    }
}
