<?php

namespace Database\Seeders;

use App\Models\Order;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    use DisableForiegnKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('orders');
        Order::factory(20)->create();
        $this->enableForiegnKeys();
    }
}
