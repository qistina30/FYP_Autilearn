<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearningActivitySeeder extends Seeder
{
    public function run()
    {
        DB::table('learning_activities')->insert([
            ['title' => 'Basic Alphabet Recognition', 'description' => 'Learn basic alphabet recognition.', 'level' => 'basic'],
            ['title' => 'Intermediate Alphabet Matching', 'description' => 'Match uppercase and lowercase letters.', 'level' => 'intermediate'],
            ['title' => 'Advanced Spelling Challenge', 'description' => 'Test your spelling skills with difficult words.', 'level' => 'hard'],
        ]);
    }
}
