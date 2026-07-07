<?php

namespace Tests\Feature;

use App\Models\ConnectRequest;
use App\Models\User;
use App\Models\Subject;
use App\Models\Message;
use App\Models\Mark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityFixesTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithOnboarding(array $overrides = []): User
    {
        $user = User::factory()->create(array_merge([
            'is_opted_in' => false,
        ], $overrides));

        // Completes onboarding: has at least one subject
        Subject::create([
            'user_id' => $user->id,
            'name' => 'General Study',
            'color_code' => '#6366f1',
        ]);

        return $user;
    }

    // ================================================================
    // A11 — CONNECT REQUEST REQUIRED FOR NEW CONVERSATIONS
    // ================================================================

    public function test_a11_rejects_new_conversation_without_accepted_request()
    {
        $sender = User::factory()->create(['is_opted_in' => true]);
        $target = User::factory()->create(['is_opted_in' => true]);

        $this->actingAs($sender);

        $response = $this->postJson("/messages/{$target->id}", [
            'message' => 'Hello!',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'You must send a connection request first. The recipient must accept before you can start chatting.']);
    }

    public function test_a11_allows_messaging_after_connect_request_accepted()
    {
        $sender = User::factory()->create(['is_opted_in' => true]);
        $target = User::factory()->create(['is_opted_in' => true]);

        // Create an accepted connect request
        ConnectRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $target->id,
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        $this->actingAs($sender);

        $response = $this->postJson("/messages/{$target->id}", [
            'message' => 'Hello!',
        ]);

        $response->assertStatus(200);
    }

    // ================================================================
    // A12 — CHAT PARTICIPANT AUTHORIZATION
    // ================================================================

    public function test_a12_shows_connect_page_with_no_existing_conversation()
    {
        $user = $this->createUserWithOnboarding();
        $target = $this->createUserWithOnboarding();

        $this->actingAs($user);

        $response = $this->get("/messages/{$target->id}");

        $response->assertStatus(200);
        $response->assertSee('Connect with');
    }

    public function test_a12_rejects_self_messaging_show()
    {
        $user = $this->createUserWithOnboarding();

        $this->actingAs($user);

        $response = $this->get("/messages/{$user->id}");

        $response->assertStatus(403);
    }

    // ================================================================
    // A14 — MESSAGE RATE LIMITING
    // ================================================================

    public function test_a14_rate_limits_message_store()
    {
        $sender = $this->createUserWithOnboarding(['is_opted_in' => true]);
        $target = $this->createUserWithOnboarding(['is_opted_in' => true]);

        Subject::create([
            'user_id' => $sender->id,
            'name' => 'Mathematics',
            'normalized_name' => 'mathematics',
            'color_code' => '#f59e0b',
        ]);

        // Create a prior message so eligibility gate is bypassed
        Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $target->id,
            'message' => 'Prior message establishing conversation',
        ]);

        $this->actingAs($sender);

        // Send 30 messages — should all succeed under 30/1min limit
        for ($i = 0; $i < 30; $i++) {
            $response = $this->postJson("/messages/{$target->id}", [
                'message' => "Message number {$i}",
            ]);
            $response->assertStatus(200);
        }

        // The 31st should be rate limited
        $response = $this->postJson("/messages/{$target->id}", [
            'message' => 'One too many',
        ]);

        $response->assertStatus(429);
    }

    public function test_a14_rate_limit_returns_json()
    {
        $sender = $this->createUserWithOnboarding(['is_opted_in' => true]);
        $target = $this->createUserWithOnboarding(['is_opted_in' => true]);

        Subject::create([
            'user_id' => $sender->id,
            'name' => 'Math',
            'normalized_name' => 'math',
            'color_code' => '#f59e0b',
        ]);

        Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $target->id,
            'message' => 'hi',
        ]);

        $this->actingAs($sender);

        for ($i = 0; $i < 30; $i++) {
            $this->postJson("/messages/{$target->id}", ['message' => "m{$i}"]);
        }

        $response = $this->postJson("/messages/{$target->id}", ['message' => 'overflow']);

        $response->assertStatus(429);
        $response->assertJsonStructure(['error', 'retry_after']);
    }

    // ================================================================
    // A6 — ALGORITHM TIEBREAK
    // ================================================================

    public function test_a6_subject_with_poor_marks_beats_subject_with_no_marks()
    {
        $user = User::factory()->create(['is_opted_in' => true]);

        // English: 0 minutes, has marks (35% exam average)
        $english = Subject::create([
            'user_id' => $user->id,
            'name' => 'English',
            'normalized_name' => 'english',
            'color_code' => '#10b981',
        ]);

        // Physics: 0 minutes, NO marks at all
        Subject::create([
            'user_id' => $user->id,
            'name' => 'Physics',
            'normalized_name' => 'physics',
            'color_code' => '#8b5cf6',
        ]);

        // Add a low exam mark for English
        Mark::create([
            'subject_id' => $english->id,
            'assessment_name' => 'Midterm Exam',
            'type' => 'Exam',
            'score' => 35,
            'max_score' => 100,
            'date' => '2026-06-15',
        ]);

        $suggested = $user->suggestedSubject();

        $this->assertNotNull($suggested, 'A subject should be suggested');
        $this->assertEquals(
            'English',
            $suggested->name,
            'English (poor marks) should be suggested over Physics (no marks) when neglect is equal'
        );

        // Also verify the computed scores
        $breakdown = $user->suggestionBreakdown;
        $this->assertNotNull($breakdown);
        $this->assertEquals('English', $breakdown['subject_name']);

        // English: priority = (1.0 * 0.5) + ((1.0 - 0.35) * 0.5) = 0.5 + 0.325 = 0.825
        $this->assertEquals(0.825, $breakdown['priority_score']);
    }
}
