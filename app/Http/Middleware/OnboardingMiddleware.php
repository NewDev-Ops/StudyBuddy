<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OnboardingMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->hasCompletedOnboarding() && !$request->is('onboarding*')) {
            return redirect()->route('onboarding.step1');
        }

        if ($user && $user->hasCompletedOnboarding() && $request->is('onboarding*')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
