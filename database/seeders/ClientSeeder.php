<?php

namespace Database\Seeders;

use App\Models\Client;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    use DisableForiegnKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('workers');
        Client::factory(20)->create();
        $this->enableForiegnKeys();
    }
}
