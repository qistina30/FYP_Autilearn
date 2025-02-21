@extends('layouts.app')

@section('content')

    <head>
        <title>Select Student</title>
        <style>
            .student-container {
                text-align: center;
                margin-top: 20px;
            }
            .student-list {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                margin-top: 20px;
            }
            .student-item {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .student-item:hover {
                background-color: #45a049;
            }
        </style>
    </head>

    <body>
    <div class="container">
        <h1>Select Student</h1>
        <form action="{{ route('store.selected.student') }}" method="POST">
            @csrf
            <div class="student-container">
                <div class="student-list">
                    @foreach($students as $student)
                        <label class="student-item">
                            <input type="radio" name="student_id" value="{{ $student->id }}">
                            {{ $student->full_name }}
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary mt-3">Select Student</button>
            </div>
        </form>
    </div>
    </body>
    </html>

@endsection
