<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Message::select('*', DB::raw("
                CASE
                    WHEN sender_id = ? THEN receiver_id
                    ELSE sender_id
                END AS peer_id
            "))
            ->addBinding([$user->id], 'select')
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('peer_id')
            ->map(function ($messages) use ($user) {
                $peer = User::find($messages->first()->peer_id);
                if (!$peer) return null;

                $lastMessage = $messages->first();
                $unread = $messages->where('receiver_id', $user->id)->whereNull('read_at')->count();

                return (object) [
                    'peer' => $peer,
                    'last_message' => $lastMessage->message,
                    'last_time' => $lastMessage->created_at,
                    'unread' => $unread,
                ];
            })
            ->filter()
            ->sortByDesc('last_time');

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return redirect()->route('messages.index');
        }

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($q) use ($currentUser, $user) {
                $q->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($currentUser, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return response()->json(['error' => 'Cannot message yourself.'], 422);
        }

        $data = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'message' => $data['message'],
        ]);

        $message->load('sender');

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
