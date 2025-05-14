<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an AdminController User
        User::create([
            'name' => 'AdminController',
            'email' => 'admin@school.com',
            'password' => Hash::make('password123'), // Change for security
            'role' => 'admin',
            'user_id' => 1, // Set based on your logic
        ]);

        // Create Pre-registered Educators
        $educators = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@school.com',
                'password' => Hash::make('password123'),
                'role' => 'educator',
                'user_id' => 'ED10001',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@school.com',
                'password' => Hash::make('password123'),
                'role' => 'educator',
                'user_id' => 'ED10002',
            ],
        ];

        foreach ($educators as $educator) {
            User::create($educator);
        }
        $this->call([
            EducatorSeeder::class,
        ]);
    }

}

