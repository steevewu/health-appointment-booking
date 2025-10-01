<?php

namespace Database\Seeders;

use App\Models\User;
use Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        User::create(
            [
                'email' => 'admin@pka.com',
                'password' => 'admin',
            ]
        )->forceFill(
            [
                'email_verified_at' => Date::now()
            ]
        )->assignRole('admin')->save();



        User::create(
            [
                'email' => 'doctor1@pka.com',
                'password' => 'doctor',
            ]
        )->forceFill(
            [
                'email_verified_at' => Date::now()
            ]
        )->assignRole('doctor')->save();
    }
}
