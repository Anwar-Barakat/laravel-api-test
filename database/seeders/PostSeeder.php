<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
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

        $posts = Post::factory(10)
            ->has(Comment::factory(3), 'comments')
            ->untitled()->create();
        $posts->each(function (Post $post) {
            $post->users()->sync([User::inRandomOrder()->first()->id]);
        });

        $this->enableForiegnKeys();
    }
}
