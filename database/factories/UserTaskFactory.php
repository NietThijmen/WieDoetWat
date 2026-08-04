<?php

namespace Database\Factories;

use App\Models\UserTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserTask>
 */
class UserTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'task_id' => null,
            'due_at' => fake()->optional()->dateTimeBetween('+1 day', '+2 weeks'),
            'completed_at' => fake()->optional(0.3)->dateTimeBetween('-2 weeks', 'now'),
        ];
    }
}
