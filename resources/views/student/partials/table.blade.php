@foreach($students as $index => $student)
    <tr>
        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
        <td>{{ $student->full_name }}</td>
        <td>{{ $student->ic_number }}</td>
        <td>{{ $student->guardian_name }}</td>
        <td>{{ $student->contact_number }}</td>
        <td>{{ $student->email ?? 'N/A' }}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
@endforeach
