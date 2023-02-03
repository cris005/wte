<?php

namespace Database\Seeders\Journal;

use App\Models\V2\Journal\Status;
use Database\Seeders\AbstractSeeder;

class StatusSeeder extends AbstractSeeder
{
    protected string $model = Status::class;
    protected array $rows = [
        ['id' => 1, 'name' => 'Success', 'description' => 'Successful funds movement'],
        ['id' => 2, 'name' => 'Failed', 'description' => 'Failed funds movement'],
        ['id' => 3, 'name' => 'Pending', 'description' => 'Pending funds movement'],
    ];
}
