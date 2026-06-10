<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function step1()
    {
        $universities = University::orderBy('name')->get();
        return view('onboarding.step1', compact('universities'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'university_id' => 'nullable|exists:universities,id',
        ]);

        Auth::user()->update([
            'university_id' => $request->university_id,
        ]);

        return redirect()->route('onboarding.step2');
    }

    public function step2()
    {
        $user = Auth::user();
        $userSubjects = $user->subjects()->orderBy('name')->get();

        $suggestedSubjects = Subject::where('user_id', '!=', $user->id)
            ->select('name')
            ->groupBy('name')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->pluck('name')
            ->filter(fn ($name) => !$userSubjects->contains('name', $name))
            ->values();

        return view('onboarding.step2', compact('userSubjects', 'suggestedSubjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = Auth::user()->subjects()
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->exists();

        if (!$exists) {
            Auth::user()->subjects()->create([
                'name' => $request->name,
                'color_code' => $this->randomColor(),
            ]);
        }

        return redirect()->route('onboarding.step2');
    }

    public function addSuggestedSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = Auth::user()->subjects()
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->exists();

        if (!$exists) {
            Auth::user()->subjects()->create([
                'name' => $request->name,
                'color_code' => $this->randomColor(),
            ]);
        }

        return redirect()->route('onboarding.step2');
    }

    public function deleteSubject(Subject $subject)
    {
        if ($subject->user_id === Auth::id()) {
            $subject->delete();
        }

        return redirect()->route('onboarding.step2');
    }

    public function storeDashboardSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = Auth::user()->subjects()
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->exists();

        if (!$exists) {
            Auth::user()->subjects()->create([
                'name' => $request->name,
                'color_code' => $this->randomColor(),
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function complete()
    {
        Auth::user()->update(['is_opted_in' => true]);
        return redirect()->route('dashboard');
    }

    private function randomColor(): string
    {
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#F97316'];
        return $colors[array_rand($colors)];
    }
}
