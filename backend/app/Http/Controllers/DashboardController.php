<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\BookIssue;
use App\Models\Fine;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $availableBooks = Book::sum('available_quantity');
        $issuedBooks = BookIssue::where('status', 'issued')->count();
        $totalMembers = Member::count();
        $overdueBooks = BookIssue::where('status', 'overdue')->count();
        $pendingFines = Fine::where('payment_status', 'pending')->sum('fine_amount');

        $recentIssues = BookIssue::with(['member', 'book'])
            ->where('status', 'issued')
            ->latest()
            ->take(5)
            ->get();

        $recentlyReturned = BookIssue::with(['member', 'book'])
            ->where('status', 'returned')
            ->latest()
            ->take(5)
            ->get();

        $overdueList = BookIssue::with(['member', 'book'])
            ->where('status', 'overdue')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'totalBooks' => $totalBooks,
                'availableBooks' => $availableBooks,
                'issuedBooks' => $issuedBooks,
                'totalMembers' => $totalMembers,
                'overdueBooks' => $overdueBooks,
                'pendingFines' => (float)$pendingFines
            ],
            'recentIssues' => $recentIssues,
            'recentlyReturned' => $recentlyReturned,
            'overdueList' => $overdueList
        ]);
    }
}
