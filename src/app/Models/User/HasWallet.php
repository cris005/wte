<?php

namespace App\Models\User;

trait HasWallet
{
    public function hasWallet(): bool
    {
        return false;
    }

    public function wallets()
    {
        return;
    }
}
