@extends('admin.layout')

@section('title', 'Help desk')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0">Help desk</h1>
    <div class="btn-group btn-group-sm">
        <a href="{{ route('admin.help-desk.index', ['status' => 'open']) }}" class="btn btn-outline-primary @if($filter === 'open') active @endif">Open</a>
        <a href="{{ route('admin.help-desk.index', ['status' => 'resolved']) }}" class="btn btn-outline-primary @if($filter === 'resolved') active @endif">Resolved</a>
        <a href="{{ route('admin.help-desk.index', ['status' => 'all']) }}" class="btn btn-outline-primary @if($filter === 'all') active @endif">All</a>
    </div>
</div>

<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>From</th>
            <th>Preview</th>
            <th>Status</th>
            <th>Created</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($requests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>
                    @if ($r->user)
                        {{ $r->user->username }} (user #{{ $r->user_id }})
                    @else
                        {{ $r->guest_name }}<br><span class="text-muted">{{ $r->guest_email }}</span>
                    @endif
                </td>
                <td>{{ \Illuminate\Support\Str::limit($r->body, 80) }}</td>
                <td><span class="badge bg-{{ $r->status === 'open' ? 'warning' : 'secondary' }}">{{ $r->status }}</span></td>
                <td>{{ $r->created_at?->format('Y-m-d H:i') }}</td>
                <td><a href="{{ route('admin.help-desk.show', $r) }}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-secondary py-4">No tickets.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $requests->links() }}</div>
@endsection
