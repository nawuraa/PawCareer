<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PawCareer')</title>
    <style>
        body { font-family: system-ui, sans-serif; line-height: 1.5; margin: 0 auto; max-width: 1100px; padding: 1rem; }
        nav { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
        main { overflow-wrap: anywhere; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: .5rem; text-align: left; vertical-align: top; }
        label { display: block; margin-top: .75rem; }
        input, select, textarea, button { font: inherit; max-width: 100%; }
        textarea { display: block; min-height: 5rem; width: 100%; }
        .alert { padding: .75rem; margin: .75rem 0; border: 1px solid #999; }
        form { margin: .25rem 0; }
    </style>
</head>
<body>
    <nav aria-label="Main navigation">
        @if (auth()->user()?->role === 'division_manager')
            <a href="{{ route('requisitions.index') }}">Requisitions</a>
        @elseif (auth()->user()?->role === 'hrd')
            <a href="{{ route('divisions.index') }}">Divisions</a>
            <a href="{{ route('positions.index') }}">Positions</a>
            <a href="{{ route('job_postings.review') }}">Requisition review</a>
            <a href="{{ route('job-postings.index') }}">Job postings</a>
        @endif
    </nav>
    <main>
        @yield('content')
    </main>
</body>
</html>
