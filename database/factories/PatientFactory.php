<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
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
            'user_id' => User::factory(),
            'fullname' => $this->faker->name,
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (Patient $patient) {
            $faker = \Faker\Factory::create();
            // Assign role to the associated user
            $user = $patient->user;

            // Manually update protected fields
            $user->forceFill([
                'created_at' => $faker->dateTimeBetween('-5 year', 'now'),
                'updated_at' => now(),
            ]);

            // Assign role (Spatie or similar role management assumed)
            $user->assignRole('patient');
            $user->save();
        });
    }

}
