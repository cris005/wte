<?php

namespace Database\Seeders\Transaction;

use App\Models\V2\Transaction\Category;
use Database\Seeders\AbstractSeeder;

class CategorySeeder extends AbstractSeeder
{
    protected string $model = Category::class;
    protected array $rows = [
        ['type_id' => 5, 'name' => 'REFERRAL BONUS', 'description' => 'AGENT REFERRAL BONUS'],
        ['type_id' => 6, 'name' => 'LOAN CASH IN', 'description' => 'AGENT LOAN CASH IN'],
        ['type_id' => 7, 'name' => 'CASHOUT COMPLETE', 'description' => 'AGENT CASHOUT COMPLETE'],
        ['type_id' => 8, 'name' => 'CASHOUT REQUEST', 'description' => 'AGENT CASHOUT REQUEST'],
        ['type_id' => 9, 'name' => 'SEND REMITTANCE', 'description' => 'AGENT SEND REMITTANCE'],
        ['type_id' => 10, 'name' => 'PAYOUT REMITTANCE', 'description' => 'AGENT PAYOUT REMITTANCE'],
        ['type_id' => 15, 'name' => 'BC BALANCE INQUIRY', 'description' => 'BUSINESS CENTER BALANCE INQUIRY'],
        ['type_id' => 16, 'name' => 'BC TRANSFER', 'description' => 'BUSINESS CENTER TRANSFER'],
        ['type_id' => 17, 'name' => 'BC CASHIN', 'description' => 'BUSINESS CENTER CASHIN'],
        ['type_id' => 18, 'name' => 'BC CASHOUT', 'description' => 'BUSINESS CENTER CASHOUT COMPLETE'],
        ['type_id' => 19, 'name' => 'BC CASHOUT REQUEST', 'description' => 'BUSINESS CENTER CASHOUT REQUEST'],
        ['type_id' => 20, 'name' => 'BC SEND REMITTANCE', 'description' => 'BUSINESS CENTER SEND REMITTANCE'],
        ['type_id' => 21, 'name' => 'BC PAYOUT REMITTANCE', 'description' => 'BUSINESS CENTER PAYOUT REMITTANCE'],
        ['type_id' => 22, 'name' => 'BC ELOAD', 'description' => 'BUSINESS CENTER ELOAD'],
        ['type_id' => 23, 'name' => 'BC BILL PAYMENT', 'description' => 'BUSINESS CENTER BILL PAYMENT'],
        ['type_id' => 24, 'name' => 'BC TOPUP', 'description' => 'BUSINESS CENTER TOPUP'],
        ['type_id' => 25, 'name' => 'PREFUND', 'description' => 'PREFUNDING'],
        ['type_id' => 26, 'name' => 'WITHDRAWAL', 'description' => 'SETTLEMENT WITHDRAWAL'],
        ['type_id' => 27, 'name' => 'SETTLEMENT TRANSFER', 'description' => 'SETTLEMENT TRANSFER'],
        ['type_id' => 28, 'name' => 'BC ENROLLMENT', 'description' => 'BUSINESS CENTER ENROLLMENT'],
        ['type_id' => 29, 'name' => 'PURCHASE TICKET', 'description' => 'BIZRAFF'],
        ['type_id' => 31, 'name' => 'INSURANCE', 'description' => 'AGENT INSURANCE'],
        ['type_id' => 35, 'name' => 'REVERSAL', 'description' => 'REVERSAL'],
        ['type_id' => 36, 'name' => 'FEE REVERSAL', 'description' => 'FEE REVERSAL'],
    ];
}
