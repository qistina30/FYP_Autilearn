@extends('layouts.app')

@section('content')
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
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="educatorTable">
                    @forelse($educators as $index => $educator)
                        <tr>
                            <td class="text-center fw-bold">
                                {{ ($educators->currentPage() - 1) * $educators->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $educator->user_id }}</td>
                            <td>{{ $educator->name }}</td>
                            <td>{{ $educator->email }}</td>
                            <td class="text-center">
                                 <a href="{{ route('educator.edit', $educator->id) }}" class="btn btn-warning btn-sm me-1">✏️ Edit</a>
                                 <form action="{{ route('educator.destroy', $educator->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this educator?');">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No educators found.</td>
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
