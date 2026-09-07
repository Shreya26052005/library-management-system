<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'name',
        'enrollment_number',
        'email',
        'phone',
        'course',
        'year',
        'address',
        'registration_date',
        'status'
    ];

    protected $dates = ['registration_date'];

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}
