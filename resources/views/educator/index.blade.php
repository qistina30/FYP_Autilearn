@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Educators List</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($educators as $index => $educator)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $educator->user_id }}</td>
                    <td>{{ $educator->name }}</td>
                    <td>{{ $educator->email }}</td>
                    <td>
{{--                        <a href="{{ route('educator.show', $educator->id) }}" class="btn btn-info btn-sm">View</a>--}}
{{--                        <a href="{{ route('educator.edit', $educator->id) }}" class="btn btn-warning btn-sm">Edit</a>--}}
                        {{--<form action="{{ route('educator.destroy', $educator->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $educators->links() }}
        </div>
    </div>
@endsection
