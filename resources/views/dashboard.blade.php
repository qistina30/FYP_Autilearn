@extends('layouts.app')
<style>
.card-chart-equal {
height: 100%;
min-height: 400px;
}

.card-body canvas {
width: 100% !important;
height: 300px !important;
}
</style>
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <div class="container py-4">
        @if($role === 'admin')
            <div class="mb-4">
                <h2 class="fw-bold text-primary"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h2>
                <p class="text-muted">Welcome back, Admin! Here’s a summary of your platform’s activity.</p>
            </div>

            {{-- Summary Cards --}}
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person-badge fs-3 text-primary mb-2"></i>
                            <h6 class="text-muted">Total Educators</h6>
                            <h3 class="fw-bold text-primary">{{ $totalEducators }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people fs-3 text-warning mb-2"></i>
                            <h6 class="text-muted">Total Guardians & Students</h6>
                            <h3 class="fw-bold text-warning">{{ $totalGuardians }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person-lines-fill fs-3 text-success mb-2"></i>
                            <h6 class="text-muted">Most Active Educator</h6>
                            <h5 class="fw-bold text-success">{{ $mostActiveEducatorName }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle fs-3 text-info mb-2"></i>
                            <h6 class="text-muted">Most Active Student</h6>
                            <h5 class="fw-bold text-info mb-1">{{ $mostActiveStudentName }}</h5>
                            <small class="text-muted">Attempts: {{ $mostActiveStudentAttempts }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light fw-semibold">
                            <i class="bi bi-pie-chart-fill me-2"></i>Activity Attempt Status
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 300px; width: 100%;">
                                <canvas id="statusPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light fw-semibold">
                            <i class="bi bi-graph-up-arrow me-2"></i>Student Progress Over Time
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 300px; width: 100%;">
                                <canvas id="progressLineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>

    @if($role === 'guardian')
        <div class="container py-4">
            <div class="mb-4 text-center">
                <h2 class="fw-bold text-primary"><i class="bi bi-house-heart-fill me-2"></i>Guardian Dashboard</h2>
                <p class="text-muted fs-6">Welcome! Here's an overview of your child's recent learning activity.</p>
            </div>

            @forelse($childrenData as $child)
                <div class="card shadow-sm mb-4 border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                            <div>
                                <h4 class="fw-bold text-primary mb-1 ">
                                    <i class="bi bi-person-circle me-2 "></i>{{ $child['full_name'] }}
                                </h4>
                                @php
                                    $trendColor = match($child['trend']) {
                                        'Improving' => 'success',
                                        'Consistent' => 'warning',
                                        'Declining' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $trendColor }} px-3 py-2 fs-6">
                    <i class="bi bi-bar-chart-line-fill me-1"></i>
                    Performance: {{ $child['trend'] }}
                </span>
                            </div>

                            <div class="mt-3 mt-md-0">
                                <a href="{{ route('report.show', ['id' => $child['id']]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-text me-1"></i> View Detailed Report
                                </a>
                            </div>
                        </div>

                        <hr class="mb-4">

                        <div class="row text-center text-md-start mb-3">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="small text-muted">Latest Score</div>
                                <div class="fw-semibold">
                                    {{ $child['score'] !== null ? $child['score'] . ' / 40 (' . $child['percentage'] . '%)' : 'Not Attempted Yet' }}
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="small text-muted">Time Spent</div>
                                <div class="fw-semibold">{{ $child['time_taken'] ?? '-' }} seconds</div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="small text-muted">Assisted By</div>
                                <div class="fw-semibold">
                                    <div class="fw-semibold">{{ $child['assisted_by'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="small text-muted">Last Attempt</div>
                                <div class="fw-semibold">{{ $child['last_attempt'] }}</div>
                            </div>


                        </div>

                    @if(count($child['recent_scores']) > 0)
                            <div class="mt-3">
                                <canvas id="scoreChart{{ $loop->index }}" style="height: 280px;"></canvas>
                            </div>
                        @endif
                    </div>
                </div>

            @empty
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>No student records found under your profile.
                </div>
            @endforelse
        </div>
    @endif

    <script>
        @foreach($childrenData as $index => $child)
        @if(count($child['recent_scores']) > 0)
        const ctx{{ $index }} = document.getElementById('scoreChart{{ $index }}').getContext('2d');
        const gradient{{ $index }} = ctx{{ $index }}.createLinearGradient(0, 0, 0, 150);
        gradient{{ $index }}.addColorStop(0, 'rgba(0, 123, 255, 0.4)');
        gradient{{ $index }}.addColorStop(1, 'rgba(0, 123, 255, 0.05)');

        new Chart(ctx{{ $index }}, {
            type: 'line',
            data: {
                labels: [...Array({{ count($child['recent_scores']) }}).keys()].map(i => 'Attempt ' + (i + 1)),
                datasets: [{
                    label: 'Score (out of 40)',
                    data: {!! json_encode($child['recent_scores']) !!},
                    fill: true,
                    backgroundColor: gradient{{ $index }},
                    borderColor: '#0d6efd',
                    borderWidth: 2,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            color: '#333',
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#f8f9fa',
                        titleColor: '#212529',
                        bodyColor: '#212529',
                        borderColor: '#dee2e6',
                        borderWidth: 1,
                        callbacks: {
                            label: context => ` Score: ${context.raw} / 40`
                        }
                    },
                    title: {
                        display: true,
                        text: 'Recent Score Trend',
                        color: '#0d6efd',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 40,
                        ticks: {
                            stepSize: 5
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            borderDash: [4, 4]
                        },
                        title: {
                            display: true,
                            text: 'Score',
                            color: '#6c757d',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Attempts',
                            color: '#6c757d',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
        @endif
        @endforeach
    </script>


@endsection

@section('scripts')
    @if($role === 'admin')
    {{-- Pie Chart: Attempt Status --}}
    <script>
        const ctx2 = document.getElementById('statusPieChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Not Attempted'],
                datasets: [{
                    data: [{{ $totalCompleted }}, {{ $totalNotAttempted }}],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderColor: '#fff',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Attempt Status Breakdown',
                        font: {
                            size: 18
                        },
                        padding: {
                            top: 10,
                            bottom: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = context.chart._metasets[context.datasetIndex].total;
                                let value = context.raw;
                                let percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#6c757d',
                            font: {
                                size: 14
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / sum) * 100).toFixed(1) + "%";
                            return percentage;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // You need to include this plugin separately
        });
    </script>


    {{-- Line Chart: Progress --}}
    <script>
        const dates = {!! json_encode($dates) !!};
        const completed = {!! json_encode($completed) !!};

        const ctx = document.getElementById('progressLineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Completed Tasks',
                    data: completed,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Student Completion Trend'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `Completed: ${tooltipItem.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Completions'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endif
@endsection




