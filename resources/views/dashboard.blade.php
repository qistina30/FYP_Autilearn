@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @if($role === 'admin')
            <div class="container">
                <h2 class="mb-4 text-primary fw-bold">📊 Admin Dashboard</h2>

                <div class="row g-4">

                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Educators</h6>
                                <h4 class="text-info fw-bold">{{ $totalEducators }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Guardians</h6>
                                <h4 class="text-warning fw-bold">{{ $totalGuardians }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Students</h6>
                                <h4 class="text-danger fw-bold">{{ $totalStudents }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-4 mt-5 shadow-sm">
                    {{--<div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold text-secondary">👨‍🏫 Recent Activities</h5>
                        <button class="btn btn-outline-primary btn-sm" id="toggleActivitiesBtn">Show All</button>
                    </div>
                    <div class="table-responsive rounded-4 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary text-white">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Activity</th>
                                <th scope="col">User</th>
                                <th scope="col">Timestamp</th>
                            </tr>
                            </thead>
                            <tbody id="recentActivitiesBody">
                            @foreach($recentActivities as $index => $activity)
                                <tr @class([
                                'table-warning' => $index === 0,
                                'table-light' => $index === 1,
                                'table-info' => $index === 2,
                            ])>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->user->name }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>--}}
                </div>

                <div class="card p-4 mt-5 shadow-sm">
                    {{--<div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold text-secondary">📊 User Performance Analytics</h5>
                        <button class="btn btn-outline-primary btn-sm" id="togglePerformanceBtn">View Full Report</button>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-muted">Average Score (Educators)</h6>
                                    <h4 class="text-success fw-bold">{{ number_format($averageScore, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-muted">Average Time Taken (Guardians)</h6>
                                    <h4 class="text-warning fw-bold">{{ number_format($averageTime, 2) }} <small class="text-muted">seconds</small></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-muted">Most Active Users</h6>
                                    <h4 class="text-info fw-bold">{{ $mostActiveUsers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
        @endif

    @if($role === 'guardian')
            <div class="alert alert-info">
                👋 Welcome, Guardian!
            </div>
            @if(count($children))
                <ul class="list-group">
                    @foreach($children as $child)
                        <li class="list-group-item">{{ $child->name }}</li>
                    @endforeach
                </ul>
            @else
                <p>No children registered.</p>
            @endif
        @else
            <div class="alert alert-secondary">
                Welcome to your dashboard!
            </div>
        @endif
    </div>
@endsection
