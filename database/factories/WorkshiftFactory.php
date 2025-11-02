<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Event;
use App\Models\Workshift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workshift>
 */
class WorkshiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'doctor_id' => Doctor::inRandomOrder()->value('id')
        ];
    }

}
