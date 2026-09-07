<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Member;
use App\Models\BookIssue;
use App\Models\Fine;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@library.com',
            'password' => Hash::make('1234567'),
            'role' => 'admin'
        ]);

        // Create Librarian User
        User::create([
            'name' => 'Librarian User',
            'email' => 'librarian@library.com',
            'password' => Hash::make('1234567'),
            'role' => 'librarian'
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Fiction', 'description' => 'Fictional stories and novels', 'status' => 'active'],
            ['name' => 'Science', 'description' => 'Science and technology books', 'status' => 'active'],
            ['name' => 'History', 'description' => 'Historical books and references', 'status' => 'active'],
            ['name' => 'Programming', 'description' => 'Programming and coding books', 'status' => 'active'],
            ['name' => 'Business', 'description' => 'Business and management books', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Books
        $books = [
            ['isbn' => '978-0-06-112008-4', 'title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'category_id' => 1, 'publisher' => 'Penguin', 'publication_year' => 1960, 'quantity' => 5, 'available_quantity' => 5, 'shelf_number' => 'A1', 'status' => 'available'],
            ['isbn' => '978-0-7432-7356-5', 'title' => '1984', 'author' => 'George Orwell', 'category_id' => 1, 'publisher' => 'Penguin', 'publication_year' => 1949, 'quantity' => 3, 'available_quantity' => 3, 'shelf_number' => 'A2', 'status' => 'available'],
            ['isbn' => '978-0-14-028329-7', 'title' => 'Brave New World', 'author' => 'Aldous Huxley', 'category_id' => 1, 'publisher' => 'Penguin', 'publication_year' => 1932, 'quantity' => 4, 'available_quantity' => 4, 'shelf_number' => 'A3', 'status' => 'available'],
            ['isbn' => '978-0-13-468599-1', 'title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'category_id' => 2, 'publisher' => 'Bantam', 'publication_year' => 1988, 'quantity' => 2, 'available_quantity' => 2, 'shelf_number' => 'B1', 'status' => 'available'],
            ['isbn' => '978-0-7432-7357-2', 'title' => 'The Selfish Gene', 'author' => 'Richard Dawkins', 'category_id' => 2, 'publisher' => 'Oxford', 'publication_year' => 1976, 'quantity' => 3, 'available_quantity' => 3, 'shelf_number' => 'B2', 'status' => 'available'],
            ['isbn' => '978-0-06-092546-7', 'title' => 'The Guns of August', 'author' => 'Barbara Tuchman', 'category_id' => 3, 'publisher' => 'Macmillan', 'publication_year' => 1962, 'quantity' => 2, 'available_quantity' => 2, 'shelf_number' => 'C1', 'status' => 'available'],
            ['isbn' => '978-0-393-05081-8', 'title' => 'A People\'s History of the United States', 'author' => 'Howard Zinn', 'category_id' => 3, 'publisher' => 'Harper', 'publication_year' => 1980, 'quantity' => 3, 'available_quantity' => 3, 'shelf_number' => 'C2', 'status' => 'available'],
            ['isbn' => '978-0-13-110362-7', 'title' => 'The C Programming Language', 'author' => 'Brian Kernighan', 'category_id' => 4, 'publisher' => 'Prentice Hall', 'publication_year' => 1988, 'quantity' => 4, 'available_quantity' => 4, 'shelf_number' => 'D1', 'status' => 'available'],
            ['isbn' => '978-0-201-63361-0', 'title' => 'Design Patterns', 'author' => 'Gang of Four', 'category_id' => 4, 'publisher' => 'Addison-Wesley', 'publication_year' => 1994, 'quantity' => 3, 'available_quantity' => 2, 'shelf_number' => 'D2', 'status' => 'available'],
            ['isbn' => '978-1-491-95438-8', 'title' => 'Learning JavaScript', 'author' => 'Ethan Brown', 'category_id' => 4, 'publisher' => 'O\'Reilly', 'publication_year' => 2016, 'quantity' => 5, 'available_quantity' => 5, 'shelf_number' => 'D3', 'status' => 'available'],
            ['isbn' => '978-0-06-015806-2', 'title' => 'Good to Great', 'author' => 'Jim Collins', 'category_id' => 5, 'publisher' => 'HarperBusiness', 'publication_year' => 2001, 'quantity' => 2, 'available_quantity' => 2, 'shelf_number' => 'E1', 'status' => 'available'],
            ['isbn' => '978-0-465-05254-8', 'title' => 'The Lean Startup', 'author' => 'Eric Ries', 'category_id' => 5, 'publisher' => 'Crown Business', 'publication_year' => 2011, 'quantity' => 3, 'available_quantity' => 3, 'shelf_number' => 'E2', 'status' => 'available'],
            ['isbn' => '978-0-446-69532-5', 'title' => 'Thinking, Fast and Slow', 'author' => 'Daniel Kahneman', 'category_id' => 5, 'publisher' => 'Farrar', 'publication_year' => 2011, 'quantity' => 4, 'available_quantity' => 4, 'shelf_number' => 'E3', 'status' => 'available'],
            ['isbn' => '978-0-7134-8666-3', 'title' => 'The Art of War', 'author' => 'Sun Tzu', 'category_id' => 3, 'publisher' => 'Penguin', 'publication_year' => 2002, 'quantity' => 3, 'available_quantity' => 3, 'shelf_number' => 'C3', 'status' => 'available'],
            ['isbn' => '978-0-596-00712-6', 'title' => 'PHP and MySQL Web Development', 'author' => 'Luke Welling', 'category_id' => 4, 'publisher' => 'Sams', 'publication_year' => 2005, 'quantity' => 2, 'available_quantity' => 2, 'shelf_number' => 'D4', 'status' => 'available'],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // Create Members
        $members = [
            ['member_id' => 'MEM001', 'name' => 'Aarav Patel', 'enrollment_number' => 'BCA001', 'email' => 'aarav@college.com', 'phone' => '9876543210', 'course' => 'BCA', 'year' => 1, 'address' => 'Delhi', 'registration_date' => Carbon::now()->subMonths(6), 'status' => 'active'],
            ['member_id' => 'MEM002', 'name' => 'Priya Sharma', 'enrollment_number' => 'BCA002', 'email' => 'priya@college.com', 'phone' => '9876543211', 'course' => 'BCA', 'year' => 1, 'address' => 'Mumbai', 'registration_date' => Carbon::now()->subMonths(5), 'status' => 'active'],
            ['member_id' => 'MEM003', 'name' => 'Rajesh Kumar', 'enrollment_number' => 'BCA003', 'email' => 'rajesh@college.com', 'phone' => '9876543212', 'course' => 'BCA', 'year' => 2, 'address' => 'Bangalore', 'registration_date' => Carbon::now()->subMonths(12), 'status' => 'active'],
            ['member_id' => 'MEM004', 'name' => 'Neha Singh', 'enrollment_number' => 'BCA004', 'email' => 'neha@college.com', 'phone' => '9876543213', 'course' => 'BCA', 'year' => 2, 'address' => 'Chennai', 'registration_date' => Carbon::now()->subMonths(11), 'status' => 'active'],
            ['member_id' => 'MEM005', 'name' => 'Vikram Verma', 'enrollment_number' => 'BCA005', 'email' => 'vikram@college.com', 'phone' => '9876543214', 'course' => 'BCA', 'year' => 3, 'address' => 'Hyderabad', 'registration_date' => Carbon::now()->subMonths(18), 'status' => 'active'],
            ['member_id' => 'MEM006', 'name' => 'Ananya Gupta', 'enrollment_number' => 'BCA006', 'email' => 'ananya@college.com', 'phone' => '9876543215', 'course' => 'BCA', 'year' => 1, 'address' => 'Pune', 'registration_date' => Carbon::now()->subMonths(4), 'status' => 'active'],
            ['member_id' => 'MEM007', 'name' => 'Arjun Desai', 'enrollment_number' => 'BCA007', 'email' => 'arjun@college.com', 'phone' => '9876543216', 'course' => 'BCA', 'year' => 3, 'address' => 'Ahmedabad', 'registration_date' => Carbon::now()->subMonths(20), 'status' => 'active'],
            ['member_id' => 'MEM008', 'name' => 'Divya Nair', 'enrollment_number' => 'BCA008', 'email' => 'divya@college.com', 'phone' => '9876543217', 'course' => 'BCA', 'year' => 2, 'address' => 'Kochi', 'registration_date' => Carbon::now()->subMonths(10), 'status' => 'active'],
            ['member_id' => 'MEM009', 'name' => 'Sanjay Reddy', 'enrollment_number' => 'BCA009', 'email' => 'sanjay@college.com', 'phone' => '9876543218', 'course' => 'BCA', 'year' => 1, 'address' => 'Lucknow', 'registration_date' => Carbon::now()->subMonths(3), 'status' => 'active'],
            ['member_id' => 'MEM010', 'name' => 'Kavya Iyer', 'enrollment_number' => 'BCA010', 'email' => 'kavya@college.com', 'phone' => '9876543219', 'course' => 'BCA', 'year' => 2, 'address' => 'Jaipur', 'registration_date' => Carbon::now()->subMonths(8), 'status' => 'active'],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }

        // Create Book Issues
        $now = Carbon::now();
        BookIssue::create([
            'member_id' => 1,
            'book_id' => 1,
            'issue_date' => $now->copy()->subDays(20),
            'due_date' => $now->copy()->subDays(6),
            'return_date' => $now->copy()->subDays(2),
            'status' => 'returned'
        ]);

        BookIssue::create([
            'member_id' => 2,
            'book_id' => 2,
            'issue_date' => $now->copy()->subDays(15),
            'due_date' => $now->copy()->addDays(5),
            'return_date' => null,
            'status' => 'issued'
        ]);

        BookIssue::create([
            'member_id' => 3,
            'book_id' => 3,
            'issue_date' => $now->copy()->subDays(25),
            'due_date' => $now->copy()->subDays(11),
            'return_date' => null,
            'status' => 'overdue'
        ]);

        BookIssue::create([
            'member_id' => 4,
            'book_id' => 4,
            'issue_date' => $now->copy()->subDays(10),
            'due_date' => $now->copy()->addDays(4),
            'return_date' => null,
            'status' => 'issued'
        ]);

        BookIssue::create([
            'member_id' => 5,
            'book_id' => 9,
            'issue_date' => $now->copy()->subDays(22),
            'due_date' => $now->copy()->subDays(8),
            'return_date' => null,
            'status' => 'overdue'
        ]);

        // Create Fines for Overdue Books
        Fine::create([
            'issue_id' => 3,
            'member_id' => 3,
            'book_id' => 3,
            'overdue_days' => 11,
            'fine_amount' => 55.00,
            'payment_status' => 'pending',
            'paid_date' => null
        ]);

        Fine::create([
            'issue_id' => 5,
            'member_id' => 5,
            'book_id' => 9,
            'overdue_days' => 8,
            'fine_amount' => 40.00,
            'payment_status' => 'pending',
            'paid_date' => null
        ]);
    }
}
