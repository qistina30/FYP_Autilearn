<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLearningRecord extends Model
{
    use HasFactory;

    // Table Name (optional if it follows Laravel's naming convention)
    protected $table = 'student_learning_records';

    // Mass Assignable Fields
    protected $fillable = [
        'user_id',
        'activity_id',
        'score',
        'completed_at',
        'student_id',
    ];

    // Relationship: A learning record belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A learning record belongs to an activity
    public function activity()
    {
        return $this->belongsTo(LearningActivity::class, 'activity_id');
    }
}

