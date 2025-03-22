<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('activities')->insert([
            [
                'id' => 1,
                'name' => 'Find the Missing Letter',
                'description' => 'Identify and complete the missing letter in a sequence.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Catch the Letter',
                'description' => 'Identify and select the correct letter quickly.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Mathematics',
                'description' => 'Basic Addition.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

}
