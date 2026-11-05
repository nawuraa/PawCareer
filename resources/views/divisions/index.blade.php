<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Divisions</title>
</head>
<body>
    <main>
        <h1>Divisions</h1>
        @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
        @if ($errors->has('division')) <p role="alert">{{ $errors->first('division') }}</p> @endif
        <p><a href="{{ route('divisions.create') }}">Create division</a> | <a href="{{ route('positions.index') }}">Manage positions</a></p>
        <table>
            <thead><tr><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($divisions as $division)
                    <tr>
                        <td>{{ $division->name }}</td>
                        <td>{{ $division->description }}</td>
                        <td>
                            <a href="{{ route('divisions.edit', $division) }}">Edit</a>
                            <form action="{{ route('divisions.destroy', $division) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No divisions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
