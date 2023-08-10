<?php

namespace Database\Seeders;

use App\Models\Worker;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    use DisableForiegnKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('workers');
        Worker::factory(20)->create();
        $this->enableForiegnKeys();
    }
}
