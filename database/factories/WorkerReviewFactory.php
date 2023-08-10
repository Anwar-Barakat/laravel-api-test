<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkerReview>
 */
class WorkerReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $workerId = Worker::inRandomOrder()->first()->id;
        $clientId = Client::inRandomOrder()->first()->id;

        return [
            'worker_id' => $workerId,
            'client_id' => $clientId,
            'comment' => fake()->sentence(150),
            'rate' => fake()->numberBetween(1, 10),
        ];
    }
}
