<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = ['issue_id', 'member_id', 'book_id', 'overdue_days', 'fine_amount', 'payment_status', 'paid_date'];
    protected $dates = ['paid_date'];

    public function issue()
    {
        return $this->belongsTo(BookIssue::class, 'issue_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
