<?php

namespace App\Http\Controllers;

use App\Models\RevisionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'notes' => 'nullable|string|max:1000',
            'date' => 'required|date|before_or_equal:today',
        ]);

        $subject = Auth::user()->subjects()->find($request->subject_id);

        if (!$subject) {
            return back()->withErrors(['subject_id' => 'Subject not found.']);
        }

        $subject->revisionSessions()->create([
            'duration_minutes' => $request->duration_minutes,
            'notes' => $request->notes,
            'date' => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Revision session logged!');
    }

    public function destroy(RevisionSession $revisionSession)
    {
        $subject = Auth::user()->subjects()->find($revisionSession->subject_id);

        if (!$subject) {
            return back()->withErrors(['error' => 'Unauthorized.']);
        }

        $revisionSession->delete();

        return redirect()->route('dashboard')->with('success', 'Session deleted.');
    }
}
