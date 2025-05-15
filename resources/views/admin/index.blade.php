@extends('layouts.app')

<style>
    .dropdown-menu .dropdown-item {
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f0f8ff;
        color: #0d6efd;
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
                <h5 class="fw-bold text-secondary mb-0">👥 User List</h5>
            </div>

            <div class="table-responsive rounded-4 overflow-hidden">
                <div class="mb-4 d-flex justify-content-end">
                    <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 Search users...">
                </div>
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="userTable">
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center fw-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        ⚙️ Actions
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.edit', $user->id) }}">
                                                ✏️ Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                            <td colspan="5" class="text-center text-muted">No users found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
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
                    url: "{{ route('admin.search') }}",
                    type: "GET",
                    data: { query: query },
                    success: function (data) {
                        $('#userTable').html(data);
                    }
                });
            });
        });
    </script>
@endsection
