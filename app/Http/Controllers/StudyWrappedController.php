<?php

namespace App\Http\Controllers;

use App\Models\StudyWrapped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyWrappedController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = $request->integer('year', now()->year);

        if ($request->boolean('regenerate')) {
            $wrapped = StudyWrapped::generateForUser($user, $year);
        } else {
            $wrapped = StudyWrapped::where('user_id', $user->id)
                ->where('year', $year)
                ->first();

            if (!$wrapped) {
                $wrapped = StudyWrapped::generateForUser($user, $year);
            }
        }

        return view('study-wrapped', compact('wrapped', 'year'));
    }
}
