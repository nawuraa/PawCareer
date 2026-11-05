<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $division->exists ? 'Edit division' : 'Create division' }}</title>
</head>
<body>
    <main>
        <h1>{{ $division->exists ? 'Edit division' : 'Create division' }}</h1>
        @if ($errors->any())
            <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        @endif
        <form action="{{ $division->exists ? route('divisions.update', $division) : route('divisions.store') }}" method="POST">
            @csrf
            @if ($division->exists) @method('PUT') @endif
            <label for="name">Name</label>
            <input id="name" name="name" maxlength="100" required value="{{ old('name', $division->name) }}">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $division->description) }}</textarea>
            <button type="submit">Save</button>
        </form>
        <a href="{{ route('divisions.index') }}">Back to divisions</a>
    </main>
</body>
</html>
