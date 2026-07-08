<?php

namespace App\Http\Controllers;

use App\Models\ConnectRequest;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendingRequests = ConnectRequest::with('sender')
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $acceptedNotifications = ConnectRequest::with('receiver')
            ->where('sender_id', $user->id)
            ->where('status', 'accepted')
            ->where('responded_at', '>=', now()->subDays(7))
            ->orderBy('responded_at', 'desc')
            ->get();

        $rejectedNotifications = ConnectRequest::with('receiver')
            ->where('sender_id', $user->id)
            ->where('status', 'rejected')
            ->where('responded_at', '>=', now()->subDay())
            ->orderBy('responded_at', 'desc')
            ->get();

        return view('notifications.index', compact(
            'pendingRequests',
            'acceptedNotifications',
            'rejectedNotifications'
        ));
    }
}
