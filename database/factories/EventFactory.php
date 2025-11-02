<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $startDate = $this->faker->dateTimeBetween('-3 year', '+1 month');

        $durationHours = $this->faker->numberBetween(1, 4);

        $endDate = (clone $startDate)->modify("+{$durationHours} hours");
        

        return [
            'title' => $this->faker->sentence(3),
            'start_at' => $startDate,
            'end_at' => $endDate,
        ];
        
    }
}
