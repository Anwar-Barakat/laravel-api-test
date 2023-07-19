<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Database\Seeders\Traits\DisableForiegnKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    use TruncateTable, DisableForiegnKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForiegnKeys();
        $this->truncate('comments');

        Comment::factory(100)
            // ->for(Post::factory(3), 'post')
            ->create();

        $this->enableForiegnKeys();
    }
}
