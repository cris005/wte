<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class FeeTypeSeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\FeeType::class;
    protected array $rows = [
        // Direct equivalent to FEE1, FEE2, FEE3, FEE4 and FEE5
        ['id' => 1, 'name' => 'Peppermint Revenue'],
        ['id' => 2, 'name' => 'Bizmoto Revenue'],
        ['id' => 3, 'name' => 'Network Partner'],
        ['id' => 4, 'name' => 'Business Center'],
        ['id' => 5, 'name' => 'User Commission']
    ];
}
