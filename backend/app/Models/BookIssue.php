<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    use HasFactory;

    protected $table = 'book_issues';
    protected $fillable = ['member_id', 'book_id', 'issue_date', 'due_date', 'return_date', 'status'];
    protected $dates = ['issue_date', 'due_date', 'return_date'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class, 'issue_id');
    }
}
