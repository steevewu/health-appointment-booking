<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Workshift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'workshift_id' => Workshift::factory(),
            'patient_id' => Patient::inRandomOrder()->value('id')
        ];
    }


    public function configure(){
        return $this->afterCreating(function (Appointment $workshift) {
            $workshift->forceFill(
                [
                    'status' => $this->faker->randomElement(['pending', 'confirmed']),
                ]
            );

            $workshift->save();
        });
    }
}
