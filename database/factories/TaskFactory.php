<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'due_date'    => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'status'      => fake()->randomElement(TaskStatus::cases())->value,
        ];
    }
}