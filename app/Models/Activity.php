<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $table = 'activities';

    public function studentProgress(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentProgress::class, 'activity_id');
    }
}
