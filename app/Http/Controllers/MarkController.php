<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'assessment_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:1',
            'type' => 'required|string|max:50',
            'date' => 'required|date|before_or_equal:today',
        ]);

        $subject = Auth::user()->subjects()->find($request->subject_id);

        if (!$subject) {
            return back()->withErrors(['subject_id' => 'Subject not found.']);
        }

        if ($request->score > $request->max_score) {
            return back()->withErrors(['score' => 'Score cannot exceed maximum score.']);
        }

        $subject->marks()->create([
            'assessment_name' => $request->assessment_name,
            'score' => $request->score,
            'max_score' => $request->max_score,
            'type' => $request->type,
            'date' => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Mark recorded!');
    }

    public function destroy(Mark $mark)
    {
        $subject = Auth::user()->subjects()->find($mark->subject_id);

        if (!$subject) {
            return back()->withErrors(['error' => 'Unauthorized.']);
        }

        $mark->delete();

        return redirect()->route('dashboard')->with('success', 'Mark deleted.');
    }
}
