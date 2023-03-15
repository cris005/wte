<?php

namespace App\Models\V2\Wallet;

use Illuminate\Database\Eloquent\Model;

final class WalletView extends Model
{
    protected $connection = 'wallet';
    protected $table = 'vw_wallet';

    protected $casts = [
        'id'                => 'integer',
        'account_no'        => 'string',
        'balance_type_id'   => 'integer',
        'balance'           => 'string',
        'balance_available' => 'string',
        'currency_id'       => 'integer',
        'status_id'         => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
}
