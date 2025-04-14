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
@endsection

@section('scripts')
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
@endsection




