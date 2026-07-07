<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:5000',
            'category' => 'nullable|string|in:Bug,Suggestion,Other',
        ]);

        Feedback::create($data);

        return redirect()->back()->with('feedback_sent', true);
    }

    public function adminIndex()
    {
        $feedback = Feedback::latest()->get();

        return view('admin.feedback.index', compact('feedback'));
    }

    public function markRead(Feedback $feedback)
    {
        $feedback->update(['is_read' => true]);

        return redirect()->route('admin.feedback');
    }
}
