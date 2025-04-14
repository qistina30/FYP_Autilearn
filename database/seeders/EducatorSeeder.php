<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EducatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $malayNames = [
            'Ahmad Faiz', 'Nur Aisyah', 'Muhammad Hafiz', 'Siti Zulaikha',
            'Aiman Hakim', 'Nurul Izzah', 'Afiqah Syahirah', 'Syafiq Roslan',
            'Alya Batrisyia', 'Haziq Imran', 'Farah Nabilah', 'Amirul Mukminin',
            'Zainab Azalea', 'Naufal Danish', 'Fatimah Zahra', 'Haikal Arif',
            'Qistina Maisarah', 'Irfan Daniel', 'Nadiah Sofia', 'Akmal Farhan'
        ];

        foreach (range(1, 20) as $i) {
            User::create([
                'user_id' => 'ED' . str_pad($i, 5, '0', STR_PAD_LEFT), // e.g. ED00001
                'name' => $malayNames[$i - 1], // Get name from array
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'educator',
            ]);
        }
    }
}
