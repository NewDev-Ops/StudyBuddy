<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Revisor Platform Overview Report</title>
<style>
    @page { margin: 20mm; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; color: #111; line-height: 1.5; }
    .cover { text-align: center; padding-top: 120px; }
    .cover h1 { font-size: 20pt; color: #2563EB; margin-bottom: 6px; }
    .cover .sub { font-size: 11pt; color: #6B7280; }
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
    .empty-row td { text-align: center; color: #9CA3AF; padding: 16px; font-style: italic; }
    .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 7pt; color: #9CA3AF; border-top: 1px solid #E5E7EB; padding-top: 4px; }
    .page-number:before { content: "Page " counter(page) " of " counter(pages); }
</style>
</head>
<body>

<div class="cover">
    <h1>Revisor Platform Overview Report</h1>
    <div class="meta">Generated on: {{ $date }} by {{ $admin->name }}</div>
    <div class="brand">Revisor &mdash; Built by students for students</div>
</div>

<div style="page-break-before: always;">

<h2 class="section-title">1. User Statistics</h2>

<table>
    <tr><th style="width:50%">Metric</th><th style="width:50%">Value</th></tr>
    <tr><td>Total Registered Users</td><td>{{ $totalUsers }}</td></tr>
    <tr><td>Total Students</td><td>{{ $totalStudents }}</td></tr>
    <tr><td>Total Admins</td><td>{{ $totalAdmins }}</td></tr>
    <tr><td>Students Opted Into Peer Network</td><td>{{ $optedIn }} ({{ $optedInPct }}% of students)</td></tr>
    <tr><td>Completed Onboarding</td><td>{{ $onboarded }}</td></tr>
    <tr><td>Not Completed Onboarding</td><td>{{ $notOnboarded }}</td></tr>
    <tr><td>Universities Represented</td><td>{{ $universitiesRepresented }}</td></tr>
</table>

<h2 class="section-title">2. Academic Activity</h2>

<table>
    <tr><th style="width:50%">Metric</th><th style="width:50%">Value</th></tr>
    <tr><td>Total Subjects Created</td><td>{{ $totalSubjects }}</td></tr>
    <tr><td>Total Revision Sessions</td><td>{{ $totalSessions }}</td></tr>
    <tr><td>Total Revision Hours</td><td>{{ $totalHours }}</td></tr>
    <tr><td>Total Marks Recorded</td><td>{{ $totalMarks }}</td></tr>
    <tr><td>Average Marks Per Student</td><td>{{ $avgMarksPerStudent }}</td></tr>
</table>

<h2 class="section-title">3. Top 5 Most Popular Subjects</h2>

@if($topSubjects->isNotEmpty())
<table>
    <tr>
        <th>Subject</th>
        <th style="text-align:center">Students</th>
        <th style="text-align:center">Avg Mark %</th>
    </tr>
    @foreach($topSubjects as $ts)
    <tr>
        <td>{{ $ts->normalized_name }}</td>
        <td style="text-align:center">{{ $ts->student_count }}</td>
        <td style="text-align:center">{{ $ts->avg_percentage !== null ? $ts->avg_percentage . '%' : 'No marks' }}</td>
    </tr>
    @endforeach
</table>
@else
<table><tr class="empty-row"><td>No subjects recorded yet.</td></tr></table>
@endif

<h2 class="section-title">4. Universities</h2>

@if($universities->isNotEmpty())
<table>
    <tr>
        <th>Name</th>
        <th>Location</th>
        <th style="text-align:center">Students</th>
    </tr>
    @foreach($universities as $uni)
    <tr>
        <td>{{ $uni->name }}</td>
        <td>{{ $uni->location ?? 'N/A' }}</td>
        <td style="text-align:center">{{ $uni->users_count }}</td>
    </tr>
    @endforeach
</table>
@else
<table><tr class="empty-row"><td>No universities in the system.</td></tr></table>
@endif

<h2 class="section-title">5. Resources Summary</h2>

<p style="font-size:9pt;margin-bottom:8px;">Total resources: <strong>{{ $totalResources }}</strong></p>

@if($topTags->isNotEmpty())
<table>
    <tr>
        <th>Subject Tag</th>
        <th style="text-align:center">Resources</th>
    </tr>
    @foreach($topTags as $tag)
    <tr>
        <td>{{ $tag->subject_tag }}</td>
        <td style="text-align:center">{{ $tag->count }}</td>
    </tr>
    @endforeach
</table>
@else
<table><tr class="empty-row"><td>No resources in the system.</td></tr></table>
@endif

</div>

<div class="footer">
    Generated by Revisor &bull; {{ $date }} &bull; Confidential &bull;
    <span class="page-number"></span>
</div>

</body>
</html>
