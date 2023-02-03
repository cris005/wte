<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection = 'app';
    protected $table = 'ACCOUNT';
    protected $primaryKey = 'ID';
}
