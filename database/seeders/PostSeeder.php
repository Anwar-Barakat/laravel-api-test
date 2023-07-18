<?php

namespace Database\Seeders;

use App\Models\Post;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    use TruncateTable, DisableForiegnKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('posts');
        Post::factory(50)->untitled()->create();
        $this->enableForiegnKeys();
    }
}