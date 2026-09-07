<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Fine;
use App\Models\Member;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $totalBooks = Book::count();
        $availableBooks = Book::sum('available_quantity');
        $issuedBooks = BookIssue::where('status', 'issued')->count();
        $returnedBooks = BookIssue::where('status', 'returned')->count();
        $overdueBooks = BookIssue::where('status', 'overdue')->count();
        $totalMembers = Member::count();
        $pendingFines = Fine::where('payment_status', 'pending')->count();
        $paidFines = Fine::where('payment_status', 'paid')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalBooks' => $totalBooks,
                'availableBooks' => $availableBooks,
                'issuedBooks' => $issuedBooks,
                'returnedBooks' => $returnedBooks,
                'overdueBooks' => $overdueBooks,
                'totalMembers' => $totalMembers,
                'pendingFines' => $pendingFines,
                'paidFines' => $paidFines
            ]
        ]);
    }
}
