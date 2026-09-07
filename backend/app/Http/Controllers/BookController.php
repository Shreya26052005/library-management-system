<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('availability')) {
            if ($request->availability == 'available') {
                $query->where('available_quantity', '>', 0);
            } else {
                $query->where('available_quantity', 0);
            }
        }

        $books = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|unique:books',
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string',
            'publication_year' => 'required|integer|min:1900|max:2099',
            'quantity' => 'required|integer|min:1',
            'shelf_number' => 'required|string',
        ]);

        $validated['available_quantity'] = $validated['quantity'];
        $validated['status'] = 'available';

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book->load('category')
        ], 201);
    }

    public function show(Book $book)
    {
        return response()->json([
            'success' => true,
            'data' => $book->load('category')
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'isbn' => 'required|unique:books,isbn,' . $book->id,
            'title' => 'required|string',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string',
            'publication_year' => 'required|integer|min:1900|max:2099',
            'quantity' => 'required|integer|min:1',
            'shelf_number' => 'required|string',
        ]);

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book->load('category')
        ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }

    public function search($query)
    {
        $books = Book::with('category')
            ->where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }
}
