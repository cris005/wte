<?php

namespace Database\Seeders;

use Database\Seeders\Journal\StatusSeeder as JournalStatusSeeder;
use Database\Seeders\Journal\TypeSeeder as JournalTypeSeeder;
use Database\Seeders\Transaction\CategorySeeder;
use Database\Seeders\Transaction\ChannelSeeder;
use Database\Seeders\Transaction\ErrorSeeder;
use Database\Seeders\Transaction\FeeTypeSeeder;
use Database\Seeders\Transaction\StatusSeeder;
use Database\Seeders\Transaction\TypeSeeder;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Populate the Wallet DB with default values.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            TypeSeeder::class,
            CategorySeeder::class,
            FeeTypeSeeder::class,
            StatusSeeder::class,
            ErrorSeeder::class,
            ChannelSeeder::class,
            JournalStatusSeeder::class,
            JournalTypeSeeder::class
        ]);
    }
}
