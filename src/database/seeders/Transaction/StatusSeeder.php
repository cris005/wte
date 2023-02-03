<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class StatusSeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\Status::class;
    protected array $rows = [
        ['id' => 1, 'name' => 'Success', 'description' => 'The transaction has been completed successfully'],
        ['id' => 2, 'name' => 'Failed', 'description' => 'The transaction encountered a scenario where it cannot continue or revert and is marked as failed'],
        ['id' => 3, 'name' => 'Pending', 'description' => 'This transaction is pending processing'],
        ['id' => 4, 'name' => 'Rejected', 'description' => 'The transaction was rejected'],
        ['id' => 5, 'name' => 'For Completion', 'description' => 'The whole transaction is pending completion'],
        ['id' => 6, 'name' => 'For Authorization', 'description' => 'The current transaction is requiring authorization from the Customer'],
        ['id' => 7, 'name' => 'For Pickup', 'description' => 'The current transaction requires pickup from the Customer'],
        ['id' => 8, 'name' => 'Cancelled', 'description' => 'This transaction has been cancelled'],
        ['id' => 9, 'name' => 'Reversed', 'description' => 'This transaction has been reversed'],
        ['id' => 11, 'name' => 'For Approval', 'description' => 'The transaction is requiring fulfillment'],
    ];
}
