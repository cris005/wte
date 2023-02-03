<?php

use App\Http\Controllers\V1\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('rest')
    ->controller(TransactionController::class)
    ->group(base_path('routes/api/v1/transaction.php'));
