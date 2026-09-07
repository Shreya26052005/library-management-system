<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'category_id',
        'publisher',
        'publication_year',
        'quantity',
        'available_quantity',
        'shelf_number',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }
}
