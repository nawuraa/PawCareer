@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <h1>Notifications</h1>

    @if (session('success'))
        <p role="status">{{ session('success') }}</p>
    @endif

    @if ($notifications->whereNull('read_at')->count() > 0)
        <form action="{{ route('applicant.notifications.read_all') }}" method="POST">
            @csrf
            <button type="submit">Mark all as read</button>
        </form>
    @endif

    <ul>
        @forelse ($notifications as $notification)
            <li style="margin-bottom: 1rem; {{ is_null($notification->read_at) ? 'font-weight: bold;' : '' }}">
                <div>{{ $notification->data['message'] ?? 'Application update' }}</div>
                @if (!empty($notification->data['reason']))
                    <p>Note: {{ $notification->data['reason'] }}</p>
                @endif
                @if (!empty($notification->data['note']))
                    <p>Note: {{ $notification->data['note'] }}</p>
                @endif
                @if (!empty($notification->data['start_date']))
                    <p>Start date: {{ $notification->data['start_date'] }}</p>
                @endif
                <small>{{ \Carbon\Carbon::parse($notification->created_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</small>

                @if (is_null($notification->read_at))
                    <form action="{{ route('applicant.notifications.read', $notification) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit">Mark as read</button>
                    </form>
                @endif
            </li>
        @empty
            <li>You have no notifications yet.</li>
        @endforelse
    </ul>
@endsection