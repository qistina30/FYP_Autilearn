@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-primary fw-bold">
            📊 Overall Student Analytics
        </h2>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history fs-3 text-warning mb-2"></i>
                        <h6 class="text-muted">Average Time Taken</h6>
                        <h3 class="fw-bold text-warning">{{ number_format($averageTime, 2) }}</h3>
                        <small class="text-muted">seconds</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-arrow-repeat fs-3 text-info mb-2"></i>
                        <h6 class="text-muted">Attempts / Student</h6>
                        <h3 class="fw-bold text-info">{{ number_format($averageAttemptsPerStudent, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-bar-chart-fill fs-3 text-primary mb-2"></i>
                        <h6 class="text-muted">Avg. Score</h6>
                        <h3 class="fw-bold text-primary">{{ $overallAverageScore }}%</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-lightning-fill fs-3 text-danger mb-2"></i>
                        <h6 class="text-muted">Fastest Time</h6>
                        <h3 class="fw-bold text-danger">{{ $fastestTime->time_taken ?? 'N/A' }}</h3>
                        <small class="text-muted">seconds</small>
                    </div>
                </div>
            </div>
        </div>


        <div class="card p-4 mt-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-secondary">🏆 Best Score Per Student</h5>
                <button class="btn btn-outline-primary btn-sm" id="toggleScoresBtn">Show All</button>
            </div>
            <div class="table-responsive rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-white">
                    <tr>
                        <th scope="col">Rank</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Best Score</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody id="bestScoresBody">
                    @foreach($bestScoresTop5 as $index => $item)
                        <tr @class([
                                'table-warning' => $index === 0,
                                'table-light' => $index === 1,
                                'table-info' => $index === 2,
                            ])>
                            <td>
                                @if($index === 0)
                                    🥇
                                @elseif($index === 1)
                                    🥈
                                @elseif($index === 2)
                                    🥉
                                @else
                                    <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="fw-medium">{{ $item['student_name'] }}</td>
                            <td><span class="badge bg-success fs-6">{{ $item['best_score'] }}</span></td>
                            <td>
                                <a href="{{ route('report.show', ['id' => $item['student_id']]) }}" class="btn btn-sm btn-outline-primary">
                                    View Report
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@php
    $reportUrlBase = url('/report/show');
@endphp
@section('scripts')

    <script>
        const reportUrlBase = "{{ $reportUrlBase }}/";

        const top5 = `{!! $bestScoresTop5->map(function($item, $index) use($reportUrlBase) {
    $rank = $index === 0 ? '🥇' : ($index === 1 ? '🥈' : ($index === 2 ? '🥉' : '<span class="badge bg-secondary">' . ($index+1) . '</span>'));
    $rowClass = $index === 0 ? 'table-warning' : ($index === 1 ? 'table-light' : ($index === 2 ? 'table-info' : ''));
    $action = '<a href="' . $reportUrlBase . $item['student_id'] . '" class="btn btn-sm btn-outline-info">View Report</a>';
    return '<tr class="' . $rowClass . '"><td>' . $rank . '</td><td class="fw-medium">' . $item['student_name'] . '</td><td><span class="badge bg-success fs-6">' . $item['best_score'] . '</span></td><td>' . $action . '</td></tr>';
})->implode('') !!}`;


        const fullList = `{!! $bestScoresFull->map(function($item, $index) use($reportUrlBase) {
    $action = '<a href="' . $reportUrlBase . $item['student_id'] . '" class="btn btn-sm btn-outline-info">View Report</a>';
    return '<tr><td><span class="badge bg-secondary">' . ($index + 1) . '</span></td><td class="fw-medium">' . $item['student_name'] . '</td><td><span class="badge bg-success fs-6">' . $item['best_score'] . '</span></td><td>' . $action . '</td></tr>';
})->implode('') !!}`;


        document.getElementById('toggleScoresBtn').addEventListener('click', function () {
            const tbody = document.getElementById('bestScoresBody');
            const button = this;

            if (button.innerText === 'Show All') {
                tbody.innerHTML = fullList;
                button.innerText = 'Show Top 5';
            } else {
                tbody.innerHTML = top5;
                button.innerText = 'Show All';
            }
        });
    </script>
@endsection

