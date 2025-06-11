@extends('layouts.app')

@section('content')
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
    <div class="container py-4">
        <div class="card p-4 shadow-sm rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-secondary mb-0">👨‍🏫 Educators List</h5>
            </div>
            <div class="mb-4 d-flex justify-content-end">
                <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 Search educators...">
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-white">
                    <tr>
                        <th class="text-center">No</th>
                        @if(auth()->user()->role === 'admin')
                            <th>User ID</th>
                        @endif
                        <th>Name</th>
                        <th>Email</th>

                        @if(auth()->user()->role === 'admin')
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody id="educatorTable">
                    @forelse($educators as $index => $educator)
                        <tr>
                            <td class="text-center fw-bold">
                                {{ ($educators->currentPage() - 1) * $educators->perPage() + $loop->iteration }}
                            </td>
                            @if(auth()->user()->role === 'admin')
                                <td>{{ $educator->user_id }}</td>
                            @endif
                            <td>{{ $educator->name }}</td>
                            <td>{{ $educator->email }}</td>

                            @if(auth()->user()->role === 'admin')
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            ⚙️ Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('educator.edit', $educator->id) }}">
                                                    ✏️ Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('educator.destroy', $educator->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this educator?');">
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
                            @endif

                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 5 : 4 }}" class="text-center">No educators found.</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $educators->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const tableRows = document.querySelectorAll("#educatorTable tr");

            searchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase();

                tableRows.forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    row.style.display = rowText.includes(query) ? "" : "none";
                });
            });
        });
    </script>
@endsection
