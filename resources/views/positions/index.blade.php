<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Positions</title>
</head>
<body>
    <main>
        <h1>Positions</h1>
        @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
        <p><a href="{{ route('positions.create') }}">Create position</a> | <a href="{{ route('divisions.index') }}">Manage divisions</a></p>
        <table>
            <thead><tr><th>Division</th><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($positions as $position)
                    <tr>
                        <td>{{ $position->division?->name ?? 'Unavailable division' }}</td>
                        <td>{{ $position->name }}</td>
                        <td>{{ $position->description }}</td>
                        <td>
                            <a href="{{ route('positions.edit', $position) }}">Edit</a>
                            <form action="{{ route('positions.destroy', $position) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No positions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
