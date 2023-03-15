<?php

namespace Database\Seeders\Transaction;

use App\Models\V2\Transaction\Type;
use Database\Seeders\AbstractSeeder;

class TypeSeeder extends AbstractSeeder
{
    protected string $model = Type::class;
    protected array $rows = [
        ['id' => 3, 'code' => 1200, 'name' => 'FUND TRANSFER', 'description' => 'Fund Transfer'],
        ['id' => 4, 'code' => 1250, 'name' => 'CASHIN', 'description' => 'Deposit'],
        ['id' => 5, 'code' => 1251, 'name' => 'REFERRAL BONUS', 'description' => 'Agent Referral Bonus'],
        ['id' => 6, 'code' => 1252, 'name' => 'LOAN CASH IN', 'description' => 'AGENT LOAN CASH IN'],
        ['id' => 7, 'code' => 1300, 'name' => 'CASHOUT COMPLETE', 'description' => 'AGENT CASHOUT COMPLETE'],
        ['id' => 8, 'code' => 1360, 'name' => 'CASHOUT REQUEST', 'description' => 'AGENT CASHOUT REQUEST'],
        ['id' => 9, 'code' => 1400, 'name' => 'SEND REMITTANCE', 'description' => 'AGENT SEND REMITTANCE'],
        ['id' => 10, 'code' => 1500, 'name' => 'PAYOUT REMITTANCE', 'description' => 'AGENT PAYOUT REMITTANCE'],
        ['id' => 12, 'code' => 1700, 'name' => 'BILL PAYMENT', 'description' => 'AGENT BILL PAYMENT'],
        ['id' => 16, 'code' => 2200, 'name' => 'BC TRANSFER', 'description' => 'BUSINESS CENTER TRANSFER'],
        ['id' => 17, 'code' => 2330, 'name' => 'BC CASHIN', 'description' => 'BUSINESS CENTER CASHIN'],
        ['id' => 18, 'code' => 2350, 'name' => 'BC CASHOUT', 'description' => 'BUSINESS CENTER CASHOUT COMPLETE'],
        ['id' => 19, 'code' => 2360, 'name' => 'BC CASHOUT REQUEST', 'description' => 'BUSINESS CENTER CASHOUT REQUEST'],
        ['id' => 20, 'code' => 2400, 'name' => 'BC SEND REMITTANCE', 'description' => 'BUSINESS CENTER SEND REMITTANCE'],
        ['id' => 21, 'code' => 2500, 'name' => 'BC PAYOUT REMITTANCE', 'description' => 'BUSINESS CENTER PAYOUT REMITTANCE'],
        ['id' => 22, 'code' => 2600, 'name' => 'BC ELOAD', 'description' => 'BUSINESS CENTER ELOAD'],
        ['id' => 23, 'code' => 2700, 'name' => 'BC BILL PAYMENT', 'description' => 'BUSINESS CENTER BILL PAYMENT'],
        ['id' => 24, 'code' => 3280, 'name' => 'BC TOPUP', 'description' => 'BUSINESS CENTER TOPUP'],
        ['id' => 25, 'code' => 3100, 'name' => 'PREFUND', 'description' => 'PREFUNDING'],
        ['id' => 26, 'code' => 3200, 'name' => 'WITHDRAWAL', 'description' => 'SETTLEMENT WITHDRAWAL'],
        ['id' => 27, 'code' => 3300, 'name' => 'SETTLEMENT TRANSFER', 'description' => 'SETTLEMENT TRANSFER'],
        ['id' => 28, 'code' => 4000, 'name' => 'BC ENROLLMENT', 'description' => 'BUSINESS CENTER ENROLLMENT'],
        ['id' => 31, 'code' => 5300, 'name' => 'INSURANCE', 'description' => 'AGENT INSURANCE'],
        ['id' => 35, 'code' => 9000, 'name' => 'REVERSAL', 'description' => 'REVERSAL'],
        ['id' => 36, 'code' => 9100, 'name' => 'FEE REVERSAL', 'description' => 'FEE REVERSAL'],
    ];
}
