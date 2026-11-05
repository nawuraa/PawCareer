<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $position->exists ? 'Edit position' : 'Create position' }}</title>
</head>
<body>
    <main>
        <h1>{{ $position->exists ? 'Edit position' : 'Create position' }}</h1>
        @if ($errors->any())
            <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        @endif
        <form action="{{ $position->exists ? route('positions.update', $position) : route('positions.store') }}" method="POST">
            @csrf
            @if ($position->exists) @method('PUT') @endif
            <label for="division_id">Division</label>
            <select id="division_id" name="division_id" required>
                <option value="">Select a division</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}" @selected(old('division_id', $position->division_id) == $division->id)>{{ $division->name }}</option>
                @endforeach
            </select>
            <label for="name">Name</label>
            <input id="name" name="name" maxlength="100" required value="{{ old('name', $position->name) }}">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $position->description) }}</textarea>
            <button type="submit">Save</button>
        </form>
        <a href="{{ route('positions.index') }}">Back to positions</a>
    </main>
</body>
</html>
