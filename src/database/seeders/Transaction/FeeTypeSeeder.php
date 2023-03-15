<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class FeeTypeSeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\FeeType::class;
    protected array $rows = [
        ['id' => 1, 'name' => 'Institution Revenue', 'description' => 'Parent company\'s share from the transaction'],
        ['id' => 2, 'name' => 'Subsidiary Revenue', 'description' => '1st Party product\'s share from the transaction'],
        ['id' => 3, 'name' => 'Network Partner', 'description' => 'Partner\'s share from the transaction'],
        ['id' => 4, 'name' => 'Business Center', 'description' => 'Business Center\'s share from the transaction'],
        ['id' => 5, 'name' => 'User Commission', 'description' => 'User\'s commission from the transaction']
    ];
}
