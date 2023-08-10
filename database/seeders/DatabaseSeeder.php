<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,

            WorkerSeeder::class,
            ClientSeeder::class,
            OrderSeeder::class,
            WorkerReviewSeeder::class,
            ProductSeeder::class,

            PostSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
