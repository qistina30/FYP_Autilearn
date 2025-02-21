<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'ic_number',
        'age',
        'address',
        'parent_name',
        'contact_number',
        'educator_user_id',
        'email',
    ];

    /**
     * Get the educator that added the student.
     */
    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_user_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
