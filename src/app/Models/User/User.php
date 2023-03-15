<?php

namespace App\Models\User;

use App\Models\Traits\HasTransactions;
use App\Models\Traits\HasWallets;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $account_no
 */
class User extends Authenticatable
{
    use HasWallets, HasTransactions, Notifiable;

    protected $connection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id'                => 'int',
        'account_no'        => 'string',
        'first_name'        => 'string',
        'middle_name'       => 'string',
        'last_name'         => 'string',
        'phone'             => 'string',
        'email'             => 'string',
        'email_verified_at' => 'datetime',
    ];
}
