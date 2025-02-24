<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'educator_id', 'score', 'time_taken', 'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id', 'user_id') // Match `educator_id` with `user_id`
        ->where('role', 'educator'); // Ensure it's an educator
    }
}

