@forelse($users as $index => $user)
    <tr>
        <td class="text-center fw-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
        <td>{{ $user->user_id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td class="text-capitalize">{{ $user->role }}</td>
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
