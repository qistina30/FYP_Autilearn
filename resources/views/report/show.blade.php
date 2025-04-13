@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4 text-primary fw-bold">
            📄 Report for {{ $student->full_name }}
        </h3>

        <!-- Metric Cards -->
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card shadow-lg border-0 text-center rounded-4">
                    <div class="card-body py-4">
                        <h6 class="text-muted">🎯 Average Score</h6>
                        <h3 class="text-success fw-bold">{{ number_format($averageScore, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-lg border-0 text-center rounded-4">
                    <div class="card-body py-4">
                        <h6 class="text-muted">⏱ Average Time</h6>
                        <h3 class="text-warning fw-bold">{{ number_format($averageTime, 2) }} <small class="text-muted">seconds</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-lg border-0 text-center rounded-4">
                    <div class="card-body py-4">
                        <h6 class="text-muted">📊 Total Attempts</h6>
                        <h3 class="text-info fw-bold">{{ $attempts }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section in Card -->
        <div class="card p-4 mt-5 shadow-sm rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-secondary mb-0">📘 All Attempts</h5>
            </div>

            {{--<p class="text-muted mt-2">
                📅 Attempts in last 7 days: <strong>{{ $last7DaysAttempts }}</strong>
            </p>--}}

            <div class="table-responsive rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Score</th>
                        <th>Time Taken (s)</th>
                        <th>Date</th>
                        <th>Assisted By</th>
                        <th>Feedback</th> {{-- new notes column --}}
                        <th>Action</th>   {{-- button column --}}
                    </tr>
                    </thead>

                    @foreach($progress as $index => $item)
                        <tr>
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td>
        <span class="badge bg-gradient bg-success fs-6 px-3 py-2 shadow-sm">
            {{ $item->score }}
        </span>
                            </td>
                            <td><span class="text-warning fw-semibold">{{ $item->time_taken }}</span></td>
                            <td class="text-muted">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="text-muted">
                                {{ $item->educator ? $item->educator->name : 'Not Assigned' }}
                            </td>

                            {{-- New Feedback Column --}}
                            <td>
                                @if($item->educator_notes)
                                    <span class="text-dark">{{ Str::limit($item->educator_notes, 50) }}</span>
                                @else
                                    <span class="text-muted fst-italic">No feedback yet</span>
                                @endif
                            </td>

                            {{-- Action Button (only show if assisted by logged-in educator) --}}
                            <td>
                                @if(Auth::user()->user_id === $item->educator_id)
                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#notesModal{{ $item->id }}">
                                        📝 {{ $item->educator_notes ? 'Edit' : 'Add' }} Notes
                                    </button>
                                @else
                                    <span class="text-muted small" title="You didn't assist this attempt.">—</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach


                </table>
                @foreach($progress as $item)
                    <!-- Notes Modal -->
                    <div class="modal fade" id="notesModal{{ $item->id }}" tabindex="-1" aria-labelledby="notesModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('educator.notes.store', $item->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="notesModalLabel{{ $item->id }}">
                                            📝 Educator Notes for Attempt #{{ $loop->iteration }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="educator_notes" class="form-control" rows="4" placeholder="Write your notes here...">{{ $item->educator_notes }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"
                                                onclick="return confirm('Are you sure you want to save these notes?')">
                                            Save Notes
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white p-4 mt-4 rounded-4 shadow-sm">
            <h5 class="fw-bold mb-3 text-primary">📉 Score vs. Time Taken</h5>
            <div class="chart-container" style="position: relative; height: 400px;">
                <canvas id="correlationChart"></canvas>
            </div>
            <p class="text-muted small mt-3">This scatter plot visualizes the relationship between time taken and scores. It may help determine whether spending more time leads to higher scores.</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dataPoints = {!! $progress->map(fn($p) => ['x' => (int)$p->time_taken, 'y' => (int)$p->score])->toJson() !!};

        const ctx = document.getElementById('correlationChart').getContext('2d');
        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Attempt',
                    data: dataPoints,
                    backgroundColor: '#0d6efd',
                    borderColor: '#0d6efd',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time Taken (seconds)',
                            color: '#6c757d',
                            font: { weight: 'bold' }
                        },
                        ticks: { color: '#6c757d' },
                        grid: { color: '#e9ecef' }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Score',
                            color: '#6c757d',
                            font: { weight: 'bold' }
                        },
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 10,
                            color: '#6c757d'
                        },
                        grid: { color: '#e9ecef' }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `Score: ${ctx.raw.y}, Time: ${ctx.raw.x}s`
                        },
                        backgroundColor: '#0d6efd',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
