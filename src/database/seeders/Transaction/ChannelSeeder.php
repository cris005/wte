<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class ChannelSeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\Channel::class;
    protected array $rows = [
        ['id' => 1, 'name' => 'Bizmoto Mobile'],
        ['id' => 2, 'name' => 'Bizmoto Admin'],
        ['id' => 3, 'name' => 'Mass-Specc Mobile'],
        ['id' => 4, 'name' => 'Mass-Specc Admin'],
    ];
}
