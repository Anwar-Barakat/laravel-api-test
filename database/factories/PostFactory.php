<?php

namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $worker_id  = Worker::inRandomOrder()->first()->id;
        return [
            'title'     => fake()->word(),
            'body'      => [],
            'worker_id' => $worker_id
        ];
    }

    // public function untitled()
    // {
    //     return $this->state([
    //         'title' => 'untitled'
    //     ]);
    // }
}
