<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(15);
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $category = Category::create($validated);
        return response()->json(['success' => true, 'message' => 'Category created', 'data' => $category], 201);
    }

    public function show(Category $category)
    {
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $category->update($validated);
        return response()->json(['success' => true, 'message' => 'Category updated', 'data' => $category]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted']);
    }

    public function search($query)
    {
        $categories = Category::where('name', 'like', "%{$query}%")->paginate(15);
        return response()->json(['success' => true, 'data' => $categories]);
    }
}
