<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treatment>
 */
class TreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // The appointment_id MUST belong to an appointment with 'confirmed' status.
        // We use a closure here to ensure a confirmed appointment is created if needed.
        $appointment = Appointment::factory()->state(['status' => 'confirmed'])->create();

        return [
            'appointment_id' => $appointment->id,
        ];
    }
}
