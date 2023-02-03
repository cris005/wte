<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class CategorySeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\Category::class;
    protected array $rows = [
        /**
         * Name        -> DATA5
         * Description -> DATA2
         */
        ['type_id' => 1, 'name' => 'REGISTRATION', 'description' => 'AGENT ACTIVATION'],
        ['type_id' => 2, 'name' => 'BALANCE INQUIRY', 'description' => 'AGENT BALANCE INQUIRY'],
        ['type_id' => 5, 'name' => 'REFERRAL BONUS', 'description' => 'AGENT REFERRAL BONUS'],
        ['type_id' => 6, 'name' => 'LOAN CASH IN', 'description' => 'AGENT LOAN CASH IN'],
        ['type_id' => 7, 'name' => 'CASHOUT COMPLETE', 'description' => 'AGENT CASHOUT COMPLETE'],
        ['type_id' => 8, 'name' => 'CASHOUT REQUEST', 'description' => 'AGENT CASHOUT REQUEST'],
        ['type_id' => 9, 'name' => 'SEND REMITTANCE', 'description' => 'AGENT SEND REMITTANCE'],
        ['type_id' => 10, 'name' => 'PAYOUT REMITTANCE', 'description' => 'AGENT PAYOUT REMITTANCE'],
        ['type_id' => 11, 'name' => 'ELOAD', 'description' => 'AGENT ELOAD'],
        ['type_id' => 12, 'name' => 'Bill Payment', 'description' => 'AGENT BILL PAYMENT'],
        ['type_id' => 13, 'name' => 'TOPUP', 'description' => 'AGENT TOPUP'],
        ['type_id' => 14, 'name' => 'CHANGE MPIN', 'description' => 'AGENT CHANGE MPIN'],
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
        ['type_id' => 30, 'name' => 'BIZMOTINDA PAYMENT', 'description' => 'BIZMOTINDA'],
        ['type_id' => 31, 'name' => 'INSURANCE', 'description' => 'AGENT INSURANCE'],
        ['type_id' => 32, 'name' => 'GCASH PAYMENT', 'description' => 'GCASH PAYMENT'],
        ['type_id' => 33, 'name' => 'BIZMOLOAN', 'description' => 'BIZMOLOAN'],
        ['type_id' => 34, 'name' => 'BIZMOGO', 'description' => 'BIZMOGO'],
        ['type_id' => 35, 'name' => 'REVERSAL', 'description' => 'REVERSAL'],
        ['type_id' => 36, 'name' => 'FEE REVERSAL', 'description' => 'FEE REVERSAL'],
        // Fund Transfer
        ['type_id' => 3, 'name' => 'Bizmoto', 'description' => 'Fund Transfer'],
        ['type_id' => 3, 'name' => 'Netbank', 'description' => 'Fund Transfer'],
        // Cash In
        ['type_id' => 4, 'name' => 'BPI', 'description' => 'Deposit'],
        ['type_id' => 4, 'name' => 'ECPay', 'description' => 'Deposit'],
        ['type_id' => 4, 'name' => 'Dragonpay', 'description' => 'Deposit'],
        ['type_id' => 4, 'name' => 'Maya', 'description' => 'Deposit'],
        ['type_id' => 4, 'name' => 'Cebuana', 'description' => 'Deposit'],
    ];
}
