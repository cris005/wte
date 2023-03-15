<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

abstract class AbstractSeeder extends Seeder
{
    /** @var string The Model's namespace */
    protected string $model = Model::class;

    /** @var bool Whether to autogenerate UUID for the inserts */
    protected bool $generateUuids = true;

    /** @var array The rows to be inserted */
    protected array $rows = [];

    /**
     * Insert the rows into the Model's table
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->rows as $row) {
            if ($this->generateUuids) {
                $row['uuid'] = Str::orderedUuid()->toString();
            }
            $this->model::create($row);
        }
    }
}
