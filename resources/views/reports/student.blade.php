<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Academic Progress Report {{ $year }}</title>
<style>
    @page { margin: 20mm; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; color: #111; line-height: 1.5; }
    .cover { text-align: center; padding-top: 120px; }
    .cover h1 { font-size: 22pt; color: #2563EB; margin-bottom: 6px; }
    .cover .sub { font-size: 11pt; color: #6B7280; margin-bottom: 4px; }
    .cover .meta { font-size: 9pt; color: #9CA3AF; margin-top: 20px; }
    .brand { font-size: 8pt; color: #2563EB; margin-top: 40px; letter-spacing: 2px; text-transform: uppercase; }
    .section-title { font-size: 13pt; color: #2563EB; font-weight: bold; border-bottom: 2px solid #2563EB; padding-bottom: 4px; margin-top: 24px; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-bottom: 12px; }
    th { background-color: #2563EB; color: #fff; padding: 6px 8px; text-align: left; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 5px 8px; border-bottom: 1px solid #E5E7EB; }
    tr:nth-child(even) td { background-color: #F3F4F6; }
    .stat-grid { display: table; width: 100%; margin-bottom: 12px; }
    .stat-row { display: table-row; }
    .stat-cell { display: table-cell; padding: 8px 12px; border: 1px solid #E5E7EB; width: 50%; }
    .stat-label { font-size: 7pt; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; }
    .stat-value { font-size: 11pt; font-weight: bold; color: #111; }
    .truncate-note { font-size: 8pt; color: #9CA3AF; font-style: italic; margin-bottom: 8px; }
    .empty-row td { text-align: center; color: #9CA3AF; padding: 16px; font-style: italic; }
    .band-excellent { color: #059669; font-weight: bold; }
    .band-good { color: #2563EB; font-weight: bold; }
    .band-needs { color: #D97706; font-weight: bold; }
    .band-risk { color: #DC2626; font-weight: bold; }
    .band-none { color: #9CA3AF; }
    .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 7pt; color: #9CA3AF; border-top: 1px solid #E5E7EB; padding-top: 4px; }
    .page-number:before { content: "Page " counter(page) " of " counter(pages); }
</style>
</head>
<body>

<div class="cover">
    <h1>Academic Progress Report</h1>
    <div class="sub">{{ $year }}</div>
    <div class="meta">{{ $user->name }}</div>
    <div class="meta">{{ $user->university?->name ?? 'University not set' }}</div>
    <div class="meta">Generated on: {{ now()->format('F j, Y') }}</div>
    <div class="brand">Revisor &mdash; Built by students for students</div>
</div>

<div style="page-break-before: always;">

<h2 class="section-title">1. Study Summary</h2>

<table>
    <tr>
        <th style="width:50%">Metric</th>
        <th style="width:50%">Value</th>
    </tr>
    <tr>
        <td>Most Studied Subject</td>
        <td>{{ $wrapped?->most_studied_subject ? $wrapped->most_studied_subject . ' — ' . number_format($subjectBreakdown->firstWhere('name', $wrapped->most_studied_subject)?->total_minutes ?? 0) . ' min' : 'Not enough data yet' }}</td>
    </tr>
    <tr>
        <td>Most Neglected Subject</td>
        <td>{{ $wrapped?->most_neglected_subject ?? 'Not enough data yet' }}</td>
    </tr>
    <tr>
        <td>Highest Performing Subject</td>
        <td>{{ $wrapped?->highest_performing_subject ?? 'Not enough data yet' }}</td>
    </tr>
    <tr>
        <td>Total Revision Hours</td>
        <td>{{ $wrapped?->total_hours ? number_format($wrapped->total_hours, 1) . ' hours' : 'Not enough data yet' }}</td>
    </tr>
</table>

<h2 class="section-title">2. Subject Breakdown</h2>

<table>
    <tr>
        <th>Subject</th>
        <th style="text-align:center">Sessions</th>
        <th style="text-align:center">Revision Time</th>
        <th style="text-align:center">Marks</th>
        <th style="text-align:center">Avg %</th>
        <th>Performance</th>
    </tr>
    @forelse($subjectBreakdown as $sb)
    <tr>
        <td>{{ $sb->name }}</td>
        <td style="text-align:center">{{ $sb->session_count }}</td>
        <td style="text-align:center">{{ floor($sb->total_minutes / 60) }}h {{ $sb->total_minutes % 60 }}m</td>
        <td style="text-align:center">{{ $sb->mark_count }}</td>
        <td style="text-align:center">{{ $sb->avg_percentage !== null ? $sb->avg_percentage . '%' : 'No marks yet' }}</td>
        <td class="band-{{ $sb->band === 'Excellent' ? 'excellent' : ($sb->band === 'Good' ? 'good' : ($sb->band === 'Needs Attention' ? 'needs' : ($sb->band === 'At Risk' ? 'risk' : 'none'))) }}">{{ $sb->band }}</td>
    </tr>
    @empty
    <tr class="empty-row"><td colspan="6">No subjects recorded yet.</td></tr>
    @endforelse
</table>

<h2 class="section-title">3. Revision History</h2>

@if($displaySessions->isNotEmpty())
    @if($totalSessions > 20)
    <p class="truncate-note">Showing 20 most recent sessions of {{ $totalSessions }} total.</p>
    @endif
    <table>
        <tr>
            <th>Date</th>
            <th>Subject</th>
            <th style="text-align:center">Duration</th>
        </tr>
        @foreach($displaySessions as $session)
        <tr>
            <td>{{ $session->date->format('M d, Y') }}</td>
            <td>{{ $session->subject->name }}</td>
            <td style="text-align:center">{{ $session->duration_minutes }} min</td>
        </tr>
        @endforeach
    </table>
@else
    <table><tr class="empty-row"><td>No revision sessions recorded this year.</td></tr></table>
@endif

<h2 class="section-title">4. Mark History</h2>

@if($displayMarks->isNotEmpty())
    @if($totalMarks > 20)
    <p class="truncate-note">Showing 20 most recent marks of {{ $totalMarks }} total.</p>
    @endif
    <table>
        <tr>
            <th>Date</th>
            <th>Subject</th>
            <th>Assessment</th>
            <th>Type</th>
            <th style="text-align:center">Score</th>
            <th style="text-align:center">%</th>
        </tr>
        @foreach($displayMarks as $mark)
        <tr>
            <td>{{ $mark->date->format('M d, Y') }}</td>
            <td>{{ $mark->subject->name }}</td>
            <td>{{ $mark->assessment_name }}</td>
            <td>{{ $mark->type }}</td>
            <td style="text-align:center">{{ $mark->score }} / {{ $mark->max_score }}</td>
            <td style="text-align:center">{{ $mark->percentage() }}%</td>
        </tr>
        @endforeach
    </table>
@else
    <table><tr class="empty-row"><td>No marks recorded this year.</td></tr></table>
@endif

<h2 class="section-title">5. Peer Network Status</h2>

<table>
    <tr>
        <td>You are currently <strong>{{ $user->is_opted_in ? 'visible' : 'not visible' }}</strong> in the Revisor peer network.</td>
    </tr>
</table>

</div>

<div class="footer">
    Generated by Revisor &bull; {{ now()->format('F j, Y') }} &bull; Confidential &mdash; for personal use only &bull;
    <span class="page-number"></span>
</div>

</body>
</html>
