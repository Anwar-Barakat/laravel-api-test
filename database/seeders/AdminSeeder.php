<?php

namespace Database\Seeders;

use App\Models\Admin;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use DisableForiegnKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('admins');
        Admin::factory(10)->create();
        $this->enableForiegnKeys();
    }
}
