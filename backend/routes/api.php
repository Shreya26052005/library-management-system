<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookIssueController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ReportController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Books
    Route::apiResource('books', BookController::class);
    Route::get('/books/search/{query}', [BookController::class, 'search']);

    // Categories
    Route::apiResource('categories', CategoryController::class);
    Route::get('/categories/search/{query}', [CategoryController::class, 'search']);

    // Members
    Route::apiResource('members', MemberController::class);
    Route::get('/members/search/{query}', [MemberController::class, 'search']);

    // Book Issues
    Route::apiResource('issues', BookIssueController::class, ['only' => ['index', 'store', 'show']]);
    Route::post('/issues/{id}/return', [BookIssueController::class, 'return']);
    Route::get('/issues/search/{query}', [BookIssueController::class, 'search']);

    // Fines
    Route::apiResource('fines', FineController::class, ['only' => ['index', 'show']]);
    Route::put('/fines/{id}/mark-paid', [FineController::class, 'markPaid']);
    Route::get('/fines/search/{query}', [FineController::class, 'search']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index']);
});
