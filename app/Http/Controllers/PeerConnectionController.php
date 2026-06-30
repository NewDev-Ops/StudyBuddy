<?php

namespace App\Http\Controllers;

use App\Mail\PeerConnectMail;
use App\Models\ConnectRequest;
use App\Models\User;
use App\Services\PeerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PeerConnectionController extends Controller
{
    public function sendRequest(Request $request, int $targetUserId)
    {
        $sender = Auth::user();

        $data = $request->validate([
            'note' => 'nullable|string|max:200',
        ]);

        $suggestedSubject = $sender->suggestedSubject();

        if (!$suggestedSubject || !$suggestedSubject->normalized_name) {
            return response()->json(['error' => 'No suggested subject found.'], 400);
        }

        $target = User::find($targetUserId);

        if (!$target) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($targetUserId === $sender->id) {
            return response()->json(['error' => 'You cannot send a connect request to yourself.'], 422);
        }

        // Re-validate eligibility using the same threshold logic as Peer Insights
        $peerService = app(PeerService::class);
        $thresholds = $peerService->resolveThresholds($sender, $suggestedSubject->normalized_name);

        $eligible = DB::table('users as u')
            ->selectRaw('u.id, ROUND(AVG(m.score * 100.0 / m.max_score), 1) AS avg_percentage')
            ->join('subjects as s', function ($join) use ($suggestedSubject) {
                $join->on('s.user_id', '=', 'u.id')
                     ->where('s.normalized_name', '=', $suggestedSubject->normalized_name);
            })
            ->join('marks as m', 'm.subject_id', '=', 's.id')
            ->where('u.id', $targetUserId)
            ->where('u.is_opted_in', 1)
            ->groupBy('u.id')
            ->having('avg_percentage', $thresholds['comparison'], $thresholds['threshold'])
            ->first();

        if (!$eligible) {
            return response()->json(['error' => 'This user is no longer eligible as a peer suggestion.'], 422);
        }

        // Rate limit: max 1 request per (sender, receiver) per 24 hours
        $recentPair = ConnectRequest::where('sender_id', $sender->id)
            ->where('receiver_id', $targetUserId)
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if ($recentPair) {
            return response()->json(['error' => 'You have already sent a connect request to this user in the last 24 hours.'], 429);
        }

        // Rate limit: max 10 requests per sender per day
        $dailyCount = ConnectRequest::where('sender_id', $sender->id)
            ->where('created_at', '>', now()->subDay())
            ->count();

        if ($dailyCount >= 10) {
            return response()->json(['error' => 'You have reached the daily limit of 10 connect requests.'], 429);
        }

        // Log the request
        $connectRequest = ConnectRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $targetUserId,
            'subject_name' => $suggestedSubject->name,
            'note' => $data['note'] ?? null,
        ]);

        // Send the email
        Mail::to($target->email)->send(new PeerConnectMail(
            sender: $sender,
            receiver: $target,
            subjectName: $suggestedSubject->name,
            note: $data['note'] ?? null,
        ));

        return response()->json([
            'success' => true,
            'message' => 'Connect request sent!',
        ]);
    }
}
