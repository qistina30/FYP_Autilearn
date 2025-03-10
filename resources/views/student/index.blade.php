@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Student List</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>IC Number</th>
                <th>Age</th>
                <th>Guardian Name</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->ic_number }}</td>
                    <td>{{ $student->age }}</td>
                    <td>{{ $student->guardian_name }}</td>
                    <td>{{ $student->contact_number }}</td>
                    <td>{{ $student->email ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('student.edit', $student->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('student.destroy', $student->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
