<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::paginate(15);
        return response()->json(['success' => true, 'data' => $members]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|unique:members',
            'name' => 'required|string',
            'enrollment_number' => 'required|unique:members',
            'email' => 'required|email|unique:members',
            'phone' => 'required|string',
            'course' => 'required|string',
            'year' => 'required|integer|min:1|max:4',
            'address' => 'required|string',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,inactive'
        ]);

        $member = Member::create($validated);
        return response()->json(['success' => true, 'message' => 'Member created', 'data' => $member], 201);
    }

    public function show(Member $member)
    {
        return response()->json(['success' => true, 'data' => $member]);
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'member_id' => 'required|unique:members,member_id,' . $member->id,
            'name' => 'required|string',
            'enrollment_number' => 'required|unique:members,enrollment_number,' . $member->id,
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string',
            'course' => 'required|string',
            'year' => 'required|integer|min:1|max:4',
            'address' => 'required|string',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,inactive'
        ]);

        $member->update($validated);
        return response()->json(['success' => true, 'message' => 'Member updated', 'data' => $member]);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json(['success' => true, 'message' => 'Member deleted']);
    }

    public function search($query)
    {
        $members = Member::where('name', 'like', "%{$query}%")
            ->orWhere('member_id', 'like', "%{$query}%")
            ->orWhere('enrollment_number', 'like', "%{$query}%")
            ->paginate(15);
        return response()->json(['success' => true, 'data' => $members]);
    }
}
