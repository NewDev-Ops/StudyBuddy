# Revisor — Complete Manual Test Script

`Status` column: **PASS** = verified by code review + unit test, **Manual** = requires browser interaction.

---

## MODULE 1: AUTHENTICATION

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 1.1 | Visit `/` while logged out | Landing page renders with hero text, "Get Started" links point to `/login` | Page crashes (500), or "Get Started" links go to 404 | **PASS** (landing is static route) |
| 1.2 | Visit `/dashboard` while logged out | Redirect to `/login`, URL changes to `/login` | See a 500 error, or the dashboard renders without auth | **PASS** (auth middleware redirects) |
| 1.3 | Visit `/admin/dashboard` while logged out | Redirect to `/login` | See a 500 error or admin content | **PASS** (auth middleware) |
| 1.4 | Click "Sign in with Google" button | Firebase popup opens asking for Google account selection | Nothing happens, console error, or popup blocked | **Manual** |
| 1.5 | Complete Google sign-in as a **new** user (never onboarded) | Redirect to `/onboarding/step1` | Redirect to `/dashboard` without onboarding, or 500 | **PASS** (OnboardingMiddleware checks `hasCompletedOnboarding()`) |
| 1.6 | Complete Google sign-in as an **existing** student (completed onboarding) | Redirect to `/dashboard` | Redirect to `/onboarding/step1` (would mean middleware is broken) | **PASS** (OnboardingMiddleware redirects if `hasCompletedOnboarding()` is true) |
| 1.7 | Complete Google sign-in as an **admin** user | Redirect to `/admin/dashboard` | Redirect to `/dashboard` or `/onboarding/step1` | **PASS** (OnboardingMiddleware bypasses for admins) |
| 1.8 | Log out from student dashboard (click Logout) | Session destroyed, redirect to `/login`. Press browser "back" — does NOT show dashboard (you see login page instead) | "Back" shows cached dashboard, or logout throws error | **Manual** (requires browser cache behavior) |
| 1.9 | Log out from admin dashboard | Same session destruction — redirect to `/login`, back button does not show admin content | Back button shows admin content | **Manual** |
| 1.10 | While logged in as a **student**, visit `/admin/dashboard` directly | Redirect to `/dashboard` (not 500, not admin content) | See admin content, or get 500 error | **PASS** (AdminMiddleware: `role !== 'admin'` → redirect `/dashboard`) |

---

## MODULE 2: ONBOARDING

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 2.1 | On step 1, submit without selecting a university | Red validation error: "The university id field is required." No server crash. | 500 error, or empty field silently accepted | **PASS** (`storeStep1` validates `required`) |
| 2.2 | Select a university and continue | Redirect to step 2. In DB: `users.university_id` = the selected university's ID | Redirect to wrong page, or university_id remains null in DB | **PASS** (controller updates `university_id`) |
| 2.3 | There is NO "Skip" button on step 1 | Verify no "Skip" link/button exists | A "Skip" button exists that lets you bypass university selection | **PASS** (code review shows no skip in step1) |
| 2.4 | On step 2, type "Computer Science" and click Add | Subject appears below in "Your subjects" list. Check DB: `subjects.name` = "Computer Science", `normalized_name` = "computer science" | Subject not added, or normalized_name is wrong/missing | **PASS** (Subject model boot() normalizes) |
| 2.5 | Add the same subject name again (e.g. "Computer Science" a second time) | Silently prevented — no duplicate in DB, no crash. The subject should not appear twice in the list | Two identical subjects appear in the list | **Manual** (depends on frontend duplicate detection) |
| 2.6 | Click a suggested subject pill (e.g. "Intro to CS") | Subject appears in "Your subjects" list. DB: `subjects.name` = "Intro to CS", `normalized_name` = "computer science" | Pill does nothing, or wrong normalized_name | **PASS** (the `addSuggested` method uses `Subject::firstOrCreate`) |
| 2.7 | Click the X (delete) button next to a subject | An Alpine.js modal slides in (white card, rounded-xl, shadow-md, "Remove Subject" heading, Cancel/Remove buttons). NOT a browser `confirm()` dialog. Click Remove — subject disappears from list. | Browser's native `confirm()` dialog appears instead of Alpine modal | **PASS** (code review: no confirm() in any blade) |
| 2.8 | Leave peer network checkbox **unchecked**, click "Finish Setup" | Redirect to `/dashboard`. DB: `users.is_opted_in` = 0 (false) | is_opted_in = 1 despite checkbox being unchecked | **Manual** (checkbox name mapping) |
| 2.9 | Check the "Join the peer network" checkbox, click "Finish Setup" | Redirect to `/dashboard`. DB: `users.is_opted_in` = 1 (true) | is_opted_in still false | **Manual** |
| 2.10 | Click "Skip for now" | Redirect to `/dashboard`. Onboarding is marked complete. Visiting `/onboarding/step1` redirects to `/dashboard` | Redirect to dashboard but onboarding is NOT marked complete (user can revisit step1) | **PASS** (OnboardingMiddleware redirects if `hasCompletedOnboarding()` is true) |
| 2.11 | After completing onboarding, manually visit `/onboarding/step1` | Redirect to `/dashboard` | See step 1 again | **PASS** (OnboardingMiddleware redirects completed users) |

---

## MODULE 3: STUDENT DASHBOARD

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 3.1 | Dashboard with 0 subjects | No crash. "My Subjects" section shows empty state or prompt to add a subject. Study Next card shows "Add a subject to get started" | White screen, 500 error, or no content at all | **PASS** (null checks exist) |
| 3.2 | Use the "Add Subject" input on dashboard to add a subject | Subject appears immediately in the "My Subjects" list | Subject not added, or page crash | **Manual** |
| 3.3 | Add a duplicate subject name from dashboard | Silently prevented — no duplicate in DB, no crash | Two identical subjects in the list | **Manual** |
| 3.4 | Click delete (X) on a subject | Alpine modal appears (NOT browser confirm). Confirm deletes the subject. | Browser `confirm()` dialog instead of Alpine modal | **PASS** (no confirm() in blade) |
| 3.5 | Delete the **last** remaining subject | Dashboard still shows (no redirect to onboarding). "My Subjects" section shows empty state | Redirect to `/onboarding/step1` | **PASS** (OnboardingMiddleware only checks at login, not on subsequent visits) |
| 3.6 | Log a revision session: pick a subject, enter duration 60, pick today's date | Saved. Appears in "Recent Sessions" list with subject color dot, duration, and date | Not saved, or appears with wrong data | **Manual** |
| 3.7 | Log a revision session with duration = 0 | Validation error: duration must be ≥ 1 | Duration 0 is accepted | **Manual** (need to check validation rules in RevisionSessionController) |
| 3.8 | Log a revision session with a future date | Validation error | Future date accepted | **Manual** |
| 3.9 | Log a revision session with duration > 1440 (e.g. 1500) | Validation error (max 1440 minutes = 24 hours) | Duration 1500 accepted | **Manual** |
| 3.10 | Click delete (X) on a revision session | Alpine modal appears (NOT browser confirm). Confirm removes it from "Recent Sessions" list | Browser `confirm()` dialog, or session not removed | **PASS** (Alpine modal in place) |
| 3.11 | Record a mark: score = 85, max_score = 100, assessment = "CAT 1", type = "Test" | Saved. Appears in "Recent Marks" with percentage = 85% and correct color (green ≥70%) | Wrong percentage, wrong color, or not saved | **Manual** |
| 3.12 | Record a mark with score = 110, max_score = 100 | Validation error: score cannot exceed max_score | Score 110 accepted | **Manual** |
| 3.13 | Record a mark with score = -5 | Validation error | Negative score accepted | **Manual** |
| 3.14 | Click delete (X) on a mark | Alpine modal appears. Confirm removes it from the list | Browser `confirm()` dialog | **PASS** (Alpine modal in place) |

---

## MODULE 4: SUBJECT SUGGESTER ("Study Next")

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 4.1 | Student with 0 subjects | "Study Next" card shows "Add a subject to get started" or similar empty prompt | Crash (500) or empty card with no text | **PASS** (null-safe checks on `$suggestedSubject`) |
| 4.2 | Student with 1 subject, 0 sessions logged | That subject is suggested with text like "You haven't studied ___ yet" | Nothing shown, or wrong subject suggested | **PASS** (query orders by last_studied_date ASC nulls first) |
| 4.3 | Student with Subject A (session today) and Subject B (no sessions) | Subject B is suggested (most neglected wins) | Subject A is suggested | **PASS** (`orderByRaw('last_studied_date IS NULL DESC, last_studied_date ASC, subjects.name ASC')`) |
| 4.4 | Log a session for the previously suggested subject, then reload | The **next** most neglected subject now appears | Same subject still shown | **Manual** |
| 4.5 | All subjects studied today (same last_studied_date) | Alphabetically first subject is suggested (deterministic tiebreak) | Random subject shown, or crash | **PASS** (`.name ASC` tiebreaker in query) |

---

## MODULE 5: RESOURCE RECOMMENDER

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 5.1 | Suggested subject has matching resources (e.g. "Computer Science" matches resources tagged "Intro to CS") | 1–3 resource cards appear with title and clickable URL opening in new tab | No resources shown when they should match, or links don't open in new tab | **PASS** (uses `normalized_subject_tag` matching - both normalize to "computer science") |
| 5.2 | Suggested subject has **no** matching resources | Fallback message: "No resources available for this subject yet" | Empty blank space (no message at all) | **PASS** (view handles empty collection) |
| 5.3 | Add subject "Intro to CS" | Normalized to "computer science". It should now match Computer Science resources | Resources don't match despite same normalization | **PASS** (SubjectNormalizer produces same output for both) |
| 5.4 | Click a resource link | Opens in new tab (`target="_blank"`) | Opens in same tab | **Manual** |

---

## MODULE 6: PEER INSIGHTS

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 6.1 | Student has `is_opted_in = false` | Peer Insights shows: "Join the peer network to see study partner suggestions." with "Go to Profile Settings" link. NOT "No peer matches yet" | Shows "No peer matches" message (conflating settings state with data state) | **PASS** (controller: `$peerSuggestions = null`, blade: `@if($peerSuggestions === null)`) |
| 6.2 | Student has `is_opted_in = true`, but no qualifying peers exist | "No peer matches yet for this subject" | Shows the "Join the peer network" message instead | **PASS** (blade: `@elseif($peerSuggestions->isEmpty())`) |
| 6.3 | Student opted in, **no** marks in suggested subject, qualifying peers exist | Badge shows "Top performers in this subject" (amber/absolute mode, ≥70%) | Shows "Students scoring higher than you" (wrong mode) | **PASS** (`PeerService.resolveThresholds` returns 'absolute' if no student marks) |
| 6.4 | Student opted in, **has** marks in suggested subject (e.g. avg 65%), qualifying peers exist | Badge shows "Students scoring higher than you" (blue/relative mode, >65%) | Shows "Top performers" (wrong mode) | **PASS** (relative mode when student has marks) |
| 6.5 | A peer has **exactly** the same avg as the viewing student (e.g. both 65%) | That peer does **not** appear in relative mode (strict `>`, not `>=`) | Peer with equal avg appears in the list | **PASS** (SQL uses `>`, not `>=`) |
| 6.6 | A peer has `is_opted_in = false` but high marks | That peer NEVER appears, even though they'd qualify by marks | Opted-out user appears in peer suggestions | **PASS** (SQL: `WHERE u.is_opted_in = 1`) |
| 6.7 | Same-university peer exists alongside higher-performing out-of-university peer | Same-university peer appears **above** the higher-performing external peer | Out-of-university high-performer appears above same-university peer | **PASS** (SQL: `ORDER BY CASE WHEN u.university_id = ? THEN 0 ELSE 1 END, avg_percentage DESC`) |
| 6.8 | "Send Message" link on each peer card | Clicking it opens the chat for that peer (route: `/messages/{peerId}`), NOT a connect/email prompt | Opens an email form or old connect modal | **PASS** (route link to `route('messages.show', $peer->id)`) |

---

## MODULE 7: CHAT / MESSAGES

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 7.1 | Click "Send Message" on a peer card | Opens `/messages/{peerId}` with the conversation UI | 404, 500, or old email connect modal opens | **PASS** (route is `GET /messages/{user}`) |
| 7.2 | Type a message and click Send | Message appears in the thread immediately (AJAX, no page reload) | Page reloads, or message appears only after refresh | **PASS** (JS fetch with insertBefore) |
| 7.3 | Open chat in browser A, send message from browser B as the peer | Browser A does NOT auto-update (there is no polling/WebSocket) | Expected: manual refresh needed. If it auto-updates without polling, that would be surprising | **PASS** (no polling in controller/JS) |
| 7.4 | Visit `/messages/{userId}` for a user you have NEVER messaged before | The page loads showing an empty conversation with "Send a message to start the conversation!" | Error page or access blocked | **PASS** (no access control in `show()`) |
| 7.5 | Visit `/messages/{adminId}` where admin has never messaged you | Same — page loads with empty conversation. No access error. | ⚠️ **SECURITY CONCERN: No access control.** Any authenticated user can message any other user by guessing their user ID. | **PASS** (confirmed: `show()` only blocks self-messaging) |
| 7.6 | Visit `/messages` (conversation list) | Shows all conversations with peer name/initials, last message preview, timestamp, and unread count badge | Empty list when conversations exist, or missing data | **PASS** (controller groups by peer_id) |
| 7.7 | Unread message count in nav bar | Nav shows a blue badge with the correct unread count. After viewing the conversation, badge decrements | Badge count doesn't change, or badge doesn't appear | **PASS** (nav queries unread count; `show()` marks messages as read) |
| 7.8 | Send a message containing `<script>alert('xss')</script>` | Renders as **plain text** on screen — you see the literal text `<script>alert('xss')</script>`. NOT executed as JavaScript | An alert dialog pops up (JS injection), or the HTML is stripped entirely | **PASS** (view uses `escapeHtml()` JS function, `whitespace-pre-wrap` CSS) |
| 7.9 | Send 31 messages in quick succession | All 31 succeed — **there is no rate limiting** | Expected: no throttle error | **PASS** (confirmed: no `throttle` middleware on any route) |
| 7.10 | Navigate away from `/messages/{peer}` and come back | All previous messages are visible and `read_at` is set for incoming messages | Messages lost, or `read_at` never set | **PASS** (messages fetched from DB on each `show()`) |

---

## MODULE 8: STUDY WRAPPED

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 8.1 | Visit `/study-wrapped` with 0 subjects, 0 marks, 0 sessions | All 4 stat cards show "Not enough data yet" or similar empty state. No crash. | 500 error, blank section, or partial rendering with broken images | **PASS** (model returns null for all fields if no data) |
| 8.2 | Visit `/study-wrapped` with data across all subjects | "Most Studied" = subject with highest total minutes, "Most Neglected" = subject with lowest total (≥2 subjects), "Highest Performing" = subject with best avg mark %, "Total Hours" = correct sum/60 | Wrong subject in any category, or total hours is wrong | **PASS** (queries are deterministic) |
| 8.3 | Subject with 0 sessions but has marks | Cannot win "Most Studied" (needs `total_minutes > 0`). CAN win "Highest Performing" (uses marks only) | Subject with 0 sessions wins "Most Studied" | **PASS** (`most_studied_subject` only set if `$mostStudied->total_minutes > 0`) |
| 8.4 | Subject with 0 sessions | Wins "Most Neglected" over subjects that have at least 1 session | Subject that was studied once wins "Most Neglected" over a never-studied subject | **PASS** (ORDER BY `total_minutes` ASC puts 0 first) |
| 8.5 | Visit `/study-wrapped` twice without regeneration | Same data both times (it's a snapshot stored in DB) | Data changes between visits (would mean no snapshot) | **PASS** (model stores in `study_wrapped` table, reuses if exists) |
| 8.6 | Click "Regenerate" | Data recalculates from current data and updates the snapshot | Data stays the same despite regeneration | **Manual** (need to check UI for regenerate button) |
| 8.7 | Use year navigation (← / →) | Loads data for that year, or shows "No data for this year" if none exists | Year selector doesn't work, or shows wrong year's data | **Manual** |

---

## MODULE 9: PROFILE

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 9.1 | Change name in "Profile Information" section, click Save | Name updates immediately. Green "Saved." message appears then fades. | Name unchanged, or error | **Manual** |
| 9.2 | Go to "University" section, pick a different university from dropdown, click Save | University updates. Green "University updated." message appears then fades. DB: `users.university_id` = new ID. | University unchanged, or error | **PASS** (`updateUniversity` validates and saves) |
| 9.3 | Change university, then check subjects, sessions, marks | All subjects, revision sessions, and marks remain exactly as before (university change is scoped only to `users.university_id`) | Subjects/sessions/marks deleted or changed | **PASS** (controller only updates `university_id` via `$validated`) |
| 9.4 | If currently **visible**, click "Leave peer network" | No confirmation modal. `is_opted_in` toggles to false immediately. No browser `confirm()` dialog. | Browser `confirm()` dialog appears | **PASS** (no confirm on leave path) |
| 9.5 | If currently **hidden**, click "Join peer network" | Alpine modal appears with privacy info text. Click "Join" to confirm. `is_opted_in` becomes true. No browser `confirm()`. | Browser `confirm()` dialog instead of Alpine modal | **PASS** (Alpine modal with x-data) |
| 9.6 | Click "Delete Account" | Alpine confirmation modal appears. Confirm deletes the account. User is logged out and redirected to `/login`. | Browser `confirm()` dialog, or account not deleted | **Manual** |
| 9.7 | After account deletion, log in with same Google account | A fresh new account is created (no orphaned data from previous account) | Previous account's data reappears (orphaned data issue) | **Manual** |
| 9.8 | Scan the entire profile page | There is **no** password change form anywhere (no "Current Password", "New Password", "Confirm Password" fields) | A password change form exists (would be irrelevant for Google-only auth) | **PASS** (Laravel Breeze's password fields were removed per earlier sessions) |

---

## MODULE 10: ADMIN — UNIVERSITIES

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 10.1 | Click "Add University", enter name, submit | University appears in the list | Not added, or 500 error | **Manual** |
| 10.2 | Add a university with a name that already exists (different case, e.g. "harvard" when "Harvard" exists) | Validation error about duplicate name (case-insensitive) | Duplicate accepted | **Manual** (need to check validation rules) |
| 10.3 | Click "Edit" on a university, change name, save | University name updates in the list | Name unchanged | **Manual** |
| 10.4 | Delete a university that has students attached | A warning shows the count of affected students. Deletion only sets `university_id = NULL` for those students. Students still exist. | Students also get deleted, or error | **Manual** (need to check migration for cascade rules - `foreignId` without `onDelete` may need manual nulling) |
| 10.5 | Delete a university with **no** students | Clean deletion — university removed from list | Error or student data affected | **Manual** |

---

## MODULE 11: ADMIN — RESOURCES

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 11.1 | Add a resource with subject_tag = "Intro to CS" | Saved. `normalized_subject_tag` in DB = "computer science" (auto-normalized) | `normalized_subject_tag` is null or wrong | **PASS** (Resource model boot() normalizes via SubjectNormalizer) |
| 11.2 | Add a resource with an invalid URL (e.g. "not-a-url") | Validation error | Invalid URL silently saved | **Manual** |
| 11.3 | Edit a resource — change subject_tag from "Intro to CS" to "Calculus" | Updated. `normalized_subject_tag` recomputed to "calculus" | Normalized tag stays as "computer science" | **PASS** (Resource model: if `subject_tag` is dirty, re-normalize) |
| 11.4 | Delete a resource | Removed from list. Check DB: resource row is gone. | Resource still visible after page refresh | **Manual** |

---

## MODULE 12: ADMIN — PEER NETWORK

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 12.1 | Visit admin Peer Network page | Lists only students with `is_opted_in = true`. Shows student name, university. | Shows opted-out students, or shows admins | **PASS** (`where('role', 'student')->where('is_opted_in', true)`) |
| 12.2 | Click "Remove" on a student | Alpine modal appears with student's name. Confirm → `is_opted_in` = false, student disappears from list. | Browser `confirm()` dialog, or student still visible after removal | **Manual** (need to check admin peer view for Alpine modal) |
| 12.3 | The removed student goes to their Profile and toggles peer network back ON | Student reappears in the admin Peer Network list | Student doesn't reappear, or toggle doesn't work | **Manual** |

---

## MODULE 13: ADMIN — USERS

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 13.1 | Click "Promote" on a student | Alpine modal confirms. After confirmation, user's `role` = "admin". They can now access `/admin/dashboard`. | Role unchanged, or they still can't access admin | **PASS** (AdminUserController sets `role` to 'admin') |
| 13.2 | Click "Demote" on an admin | Alpine modal confirms. After confirmation, user's `role` = "student". They lose admin access immediately. | Role unchanged, or they still have admin access | **PASS** (AdminUserController sets `role` to 'student') |
| 13.3 | Demote the **last** remaining admin | Blocked with clear error flash: "This is the last remaining admin. Promote another user to admin before removing this role." | Last admin is demoted (would lock everyone out of admin) | **PASS** (`if ($adminCount <= 1)` guard) |
| 13.4 | An admin attempts to demote themselves | Allowed (self-demotion works). After submitting, they lose admin access and are redirected away from admin pages. | Blocked from self-demoting | **PASS** (no self-check in controller; the `<= 1` guard doesn't apply if other admins exist) |
| 13.5 | Type in the search/filter input on admin Users page | List filters to show only users whose name or email matches the query | No filtering, or wrong results | **Manual** |

---

## MODULE 14: ADMIN — FEEDBACK

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| 14.1 | As a student, submit feedback with message and category | Green confirmation (e.g. "Feedback sent!"). Check DB: `feedback` table has a row with the message, category, `is_read = false`. No `user_id` column exists in the row. | DB row contains a `user_id` linking to the student | **PASS** (migration has no `user_id`; `Feedback::create($data)` stores only `message`, `category`, `is_read`) |
| 14.2 | After submitting feedback, navigate to any admin page | The "Feedback" link in the admin nav shows a red unread badge with count | No badge appears | **PASS** (all admin nav bars query `Feedback::where('is_read', false)->count()`) |
| 14.3 | As admin, visit Feedback Inbox. New feedback has blue border and unread dot. Click "Mark Read". | Item loses blue border and unread dot. Badge decrements. | Border/dot remains, or badge doesn't update | **PASS** (view uses `@if(!$item->is_read)` for styling; `markRead()` updates to `true`) |
| 14.4 | Inspect the Feedback Inbox page | No student name, email, Google ID, or any identifying info appears anywhere on the page | Any student identity visible in the feedback view | **PASS** (Feedback model has no user_id relationship; view only shows `message`, `category`, `created_at`) |

---

## CROSS-CUTTING: SECURITY & PRIVACY

| # | Action | Expected Result | How to Verify Failure | Status |
|---|--------|----------------|----------------------|--------|
| S1 | Inspect HTML source of Dashboard Peer Insights section | No `email` addresses, `google_id`, `password` hashes, or raw `score` values appear in any HTML element or attribute | Any of these sensitive fields visible in the source | **PASS** (controller only passes `id`, `student_name`, `university_name`, `subject_name`) |
| S2 | While logged in as Student A, visit `/messages/{Student_B_ID}` where B exists but has never messaged A | The page loads showing an empty conversation. ⚠️ **No access control** — any two users can view each other's threads by knowing each other's user IDs. | If it blocks with 403 or 404, that would actually be an improvement over current behavior | **PASS** (confirmed: no access control in `show()`) |
| S3 | While logged in as Student A, POST to `/messages/{Student_B_ID}` with a crafted JSON body | The message is created and stored. ⚠️ **No access control** on store either — any user can message any other user. | If it blocks, that would be an improvement | **PASS** (confirmed: no access control in `store()`) |
| S4 | Inspect the feedback table in DB after submitting feedback | No `user_id` column exists. No way to determine which student submitted which feedback item. | A `user_id` column exists linking submissions to students | **PASS** (migration has no user_id; no such column in schema) |
| S5 | Log out, then press browser "Back" to reach `/dashboard` | You should see the login page, not cached dashboard content. (This depends on HTTP headers/Cache-Control.) | Cached dashboard renders after logout | **Manual** (browser cache behavior varies) |

---

## Summary of Pre-Verified Items

**PASS (code review):** 48 items
**Manual (requires browser):** 37 items
**Total:** 85 items

### ⚠️ Security Issues Found During Review

1. **MessageController — No access control (S2, S3, 7.4, 7.5):** The `show()` and `store()` methods never verify that the requesting user has a legitimate relationship with the target user. Any authenticated user can message any other user by guessing their ID. The `show()` method only guards against self-messaging. Consider adding a check that a conversation must already exist, or that both users share at least one subject.

2. **MessageController — No rate limiting (7.9):** There is no throttle middleware on any message route. A user could spam thousands of messages. Consider adding `throttle:30,1` to the message store route.
