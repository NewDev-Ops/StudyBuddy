<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUniversityController extends Controller
{
    public function index()
    {
        $universities = University::withCount('users')->orderBy('name')->get();
        return view('admin.universities.index', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('universities')->where(fn ($q) => $q->whereRaw('LOWER(name) = ?', [strtolower($request->name)]))],
            'location' => ['required', 'string', 'max:255'],
        ]);

        University::create($validated);

        return redirect()->route('admin.universities')->with('success', 'University added successfully.');
    }

    public function update(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('universities')->ignore($university->id)->where(fn ($q) => $q->whereRaw('LOWER(name) = ?', [strtolower($request->name)]))],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $university->update($validated);

        return redirect()->route('admin.universities')->with('success', 'University updated successfully.');
    }

    public function destroy(University $university)
    {
        $studentsCount = User::where('university_id', $university->id)->count();
        $university->delete();

        $message = 'University deleted successfully.';
        if ($studentsCount > 0) {
            $message .= " {$studentsCount} student(s) were unassigned (their data was not affected).";
        }

        return redirect()->route('admin.universities')->with('success', $message);
    }
}
