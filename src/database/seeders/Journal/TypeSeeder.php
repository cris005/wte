<?php

namespace Database\Seeders\Journal;

use App\Models\V2\Journal\Type;
use Database\Seeders\AbstractSeeder;

class TypeSeeder extends AbstractSeeder
{
    protected string $model = Type::class;
    protected array $rows = [
        ['id' => 200, 'name' => 'Transfer', 'description' => 'Transfer of funds between accounts'],
        ['id' => 210, 'name' => 'Reversal', 'description' => 'Reversal of a transfer of funds'],
        ['id' => 300, 'name' => 'Fee',      'description' => 'Fee charge of a transaction'],
        ['id' => 400, 'name' => 'Interest', 'description' => 'Interest accrual'],
    ];
}

