<!DOCTYPE html>
<html>
<head>
    <title>Student Progress Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; }
        .report-meta { text-align: right; font-size: 12px; margin-bottom: 20px; }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f8f8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th {
            background-color: #eee;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Student Progress Report</h2>
    <p>{{ $student->full_name }}</p>
</div>

<div class="report-meta">
    <strong>Report Generated:</strong> {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
</div>

<div class="card">
    <p><strong>Average Score:</strong> {{ number_format($averageScore, 2) }}</p>
    <p><strong>Average Time:</strong> {{ number_format($averageTime, 2) }} seconds</p>
    <p><strong>Total Attempts:</strong> {{ $attempts }}</p>
</div>

<div class="section-title">Detailed Attempts</div>
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Score</th>
        <th>Time Taken (s)</th>
        <th>Date</th>
        <th>Assisted By</th>
        <th>Feedback</th>
    </tr>
    </thead>
    <tbody>
    @foreach($progress as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->score }}</td>
            <td>{{ $item->time_taken }}</td>
            <td>{{ $item->updated_at->format('d M Y H:i') }}</td>
            <td>{{ $item->educator ? $item->educator->name : 'Not Assigned' }}</td>
            <td>{{ $item->educator_notes ?? 'No feedback yet' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
