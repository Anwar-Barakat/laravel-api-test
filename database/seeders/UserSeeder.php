<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use TruncateTable, DisableForiegnKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('users');
        User::factory(10)->create();
        $this->enableForiegnKeys();
    }
}
