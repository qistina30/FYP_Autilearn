<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {/*
        $faker = Faker::create('ms_MY'); // Malay locale

        for ($i = 0; $i < 20; $i++) {
            User::create([
                'user_id' => 'STU' . str_pad($i + 1, 5, '0', STR_PAD_LEFT), // e.g. STU00001
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'), // default password
                'role' => 'student',
            ]);
        }*/
    }
}
