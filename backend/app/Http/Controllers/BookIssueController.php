<?php

namespace App\Http\Controllers;

use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Member;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = BookIssue::with(['member', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $issues = $query->paginate(15);
        return response()->json(['success' => true, 'data' => $issues]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date'
        ]);

        $book = Book::findOrFail($validated['book_id']);
        if ($book->available_quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Book not available'], 400);
        }

        $issue = BookIssue::create([
            'member_id' => $validated['member_id'],
            'book_id' => $validated['book_id'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'status' => 'issued'
        ]);

        $book->decrement('available_quantity');

        return response()->json(['success' => true, 'message' => 'Book issued successfully', 'data' => $issue->load(['member', 'book'])], 201);
    }

    public function show(BookIssue $issue)
    {
        return response()->json(['success' => true, 'data' => $issue->load(['member', 'book', 'fine'])]);
    }

    public function return(Request $request, $id)
    {
        $request->validate(['return_date' => 'required|date']);

        $issue = BookIssue::findOrFail($id);

        if ($issue->status == 'returned') {
            return response()->json(['success' => false, 'message' => 'Book already returned'], 400);
        }

        $returnDate = Carbon::parse($request->return_date);
        $dueDate = Carbon::parse($issue->due_date);
        $overdaysDays = $returnDate->diffInDays($dueDate);

        $issue->update([
            'return_date' => $returnDate,
            'status' => $overdaysDays > 0 ? 'overdue' : 'returned'
        ]);

        $issue->book->increment('available_quantity');

        if ($overdaysDays > 0) {
            Fine::firstOrCreate(
                ['issue_id' => $issue->id],
                [
                    'member_id' => $issue->member_id,
                    'book_id' => $issue->book_id,
                    'overdue_days' => $overdaysDays,
                    'fine_amount' => $overdaysDays * 5,
                    'payment_status' => 'pending'
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Book returned successfully', 'data' => $issue->load(['member', 'book', 'fine'])]);
    }

    public function search($query)
    {
        $issues = BookIssue::with(['member', 'book'])
            ->whereHas('member', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('book', function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->paginate(15);
        return response()->json(['success' => true, 'data' => $issues]);
    }
}
