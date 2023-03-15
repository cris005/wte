<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class ChannelSeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\Channel::class;
    protected array $rows = [
        ['id' => 1, 'name' => 'Mobile', 'description' => 'Origin from the [insert 1st party name] Mobile App'],
        ['id' => 2, 'name' => 'Admin', 'description' => 'Origin from the [insert 1st party name] Admin tool'],
        ['id' => 3, 'name' => '3rd Party Mobile', 'description' => 'Origin from the [insert 3rd party name] Mobile App'],
        ['id' => 4, 'name' => '3rd Party Admin', 'description' => 'Origin from the [insert 3rd party name] Admin tool'],
    ];
}
