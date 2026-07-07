<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Services\PeerService;
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

        // Participant check: must have an existing conversation to read messages
        $this->ensureParticipant($user, $currentUser, requireExistingConversation: true);

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

        // Participant check: block self-messaging
        $this->ensureParticipant($user, $currentUser);

        $isExistingConversation = Message::where(function ($q) use ($currentUser, $user) {
                $q->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($currentUser, $user) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', $currentUser->id);
            })->exists();

        if (!$isExistingConversation) {
            // New conversation — run eligibility gate
            if (!$user->is_opted_in) {
                return response()->json([
                    'error' => 'This user is not available for peer connection.'
                ], 422);
            }

            $suggestedSubject = $currentUser->suggestedSubject();
            if (!$suggestedSubject) {
                return response()->json([
                    'error' => 'Add subjects before connecting with peers.'
                ], 422);
            }

            $peerService = app(PeerService::class);
            $thresholds = $peerService->resolveThresholds(
                $currentUser,
                $suggestedSubject->normalized_name
            );

            $eligible = DB::selectOne("
                SELECT u.id,
                       ROUND(AVG(m.score * 100.0 / m.max_score), 1) AS avg_pct
                FROM users u
                INNER JOIN subjects s
                    ON s.user_id = u.id
                    AND s.normalized_name = ?
                INNER JOIN marks m ON m.subject_id = s.id
                WHERE u.id = ? AND u.is_opted_in = 1
                GROUP BY u.id
                HAVING avg_pct {$thresholds['comparison']} ?
            ", [
                $suggestedSubject->normalized_name,
                $user->id,
                $thresholds['threshold']
            ]);

            if (!$eligible) {
                return response()->json([
                    'error' => 'This user is not currently eligible as a peer suggestion.'
                ], 422);
            }
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

    private function ensureParticipant(User $otherUser, User $currentUser, bool $requireExistingConversation = false): void
    {
        if ($otherUser->id === $currentUser->id) {
            abort(403, 'Cannot message yourself.');
        }

        if ($requireExistingConversation) {
            $isParticipant = Message::where(function ($q) use ($currentUser, $otherUser) {
                    $q->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id);
                })->orWhere(function ($q) use ($currentUser, $otherUser) {
                    $q->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id);
                })->exists();

            if (!$isParticipant) {
                abort(403, 'You are not a participant in this conversation.');
            }
        }
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
