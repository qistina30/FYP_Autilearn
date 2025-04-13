@extends('layouts.app')
<style>
    .dropdown-menu .dropdown-item {
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f0f8ff; /* Light blue highlight */
        color: #0d6efd; /* Bootstrap primary */
        font-weight: 500;
    }

    .dropdown-menu .dropdown-item.text-danger:hover {
        background-color: #ffe5e5;
        color: #dc3545;
    }
</style>

@section('content')
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card p-4 shadow-sm rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-secondary mb-0">🎓 Student List</h5>
                <a href="{{ route('educator.add-student') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-user-plus"></i>Add Student
                </a>
            </div>

            <div class="table-responsive rounded-4 overflow-hidden">
                <div class="mb-4 d-flex justify-content-end">
                    <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 Search students...">
                </div>
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Full Name</th>
                        <th>IC Number</th>
                        <th>Guardian Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody  id="studentTable">
                    @forelse($students as $index => $student)
                        <tr>
                            <td class="text-center fw-bold">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->ic_number }}</td>
                            <td>{{ $student->guardian_name }}</td>
                            <td>{{ $student->contact_number }}</td>
                            <td>{{ $student->email ?? 'N/A' }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        ⚙️ Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.show', $student->id) }}">
                                                👁️ View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.edit', $student->id) }}">
                                                ✏️ Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('student.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No students found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $students->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#searchInput').on('keyup', function () {
            let query = $(this).val();

            $.ajax({
                url: "{{ route('student.search') }}",
                type: "GET",
                data: { query: query },
                success: function (data) {
                    $('#studentTable').html(data);
                }
            });
        });
    });
</script>
@endsection

