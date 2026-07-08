<?php

namespace App\Http\Controllers;

use App\Models\ConnectRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRequestController extends Controller
{
    public function send(Request $request, User $receiver)
    {
        $sender = Auth::user();

        if ($receiver->id === $sender->id) {
            return response()->json(['error' => 'Cannot send a request to yourself.'], 422);
        }

        $existing = ConnectRequest::between($sender, $receiver)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return response()->json(['error' => 'You are already connected with this user.'], 422);
            }
            return response()->json(['error' => 'A pending request already exists.'], 422);
        }

        $data = $request->validate([
            'subject_name' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:200',
        ]);

        $connectRequest = ConnectRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'subject_name' => $data['subject_name'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => 'pending',
        ]);

        $connectRequest->load('sender');

        return response()->json([
            'success' => true,
            'connect_request' => $connectRequest,
        ]);
    }

    public function accept(ConnectRequest $connectRequest)
    {
        $user = Auth::user();

        if ($connectRequest->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if ($connectRequest->status !== 'pending') {
            return response()->json(['error' => 'This request is no longer pending.'], 422);
        }

        $connectRequest->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Connection request accepted. You can now chat.',
        ]);
    }

    public function reject(ConnectRequest $connectRequest)
    {
        $user = Auth::user();

        if ($connectRequest->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if ($connectRequest->status !== 'pending') {
            return response()->json(['error' => 'This request is no longer pending.'], 422);
        }

        $connectRequest->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Connection request rejected.',
        ]);
    }

    public function cancel(ConnectRequest $connectRequest)
    {
        $user = Auth::user();

        if ($connectRequest->sender_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if ($connectRequest->status !== 'pending') {
            return response()->json(['error' => 'This request is no longer pending.'], 422);
        }

        $connectRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Request cancelled.',
        ]);
    }

    public function pending()
    {
        $user = Auth::user();

        $incoming = ConnectRequest::with('sender')
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $outgoing = ConnectRequest::with('receiver')
            ->where('sender_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ]);
    }
}
