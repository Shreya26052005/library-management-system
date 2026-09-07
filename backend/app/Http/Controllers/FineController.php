<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $query = Fine::with(['issue', 'member', 'book']);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $fines = $query->paginate(15);
        return response()->json(['success' => true, 'data' => $fines]);
    }

    public function show(Fine $fine)
    {
        return response()->json(['success' => true, 'data' => $fine->load(['issue', 'member', 'book'])]);
    }

    public function markPaid(Request $request, Fine $fine)
    {
        $fine->update([
            'payment_status' => 'paid',
            'paid_date' => Carbon::now()
        ]);

        return response()->json(['success' => true, 'message' => 'Fine marked as paid', 'data' => $fine]);
    }

    public function search($query)
    {
        $fines = Fine::with(['issue', 'member', 'book'])
            ->whereHas('member', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('book', function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->paginate(15);
        return response()->json(['success' => true, 'data' => $fines]);
    }
}
