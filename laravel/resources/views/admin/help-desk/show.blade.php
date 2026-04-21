@extends('admin.layout')

@section('title', 'Ticket #'.$ticket->id)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.help-desk.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to list</a>
</div>
<h1 class="h4 mb-3">Ticket #{{ $ticket->id }}</h1>

<dl class="row mb-4">
    <dt class="col-sm-2">Status</dt>
    <dd class="col-sm-10"><span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : 'secondary' }}">{{ $ticket->status }}</span></dd>
    <dt class="col-sm-2">From</dt>
    <dd class="col-sm-10">
        @if ($ticket->user)
            {{ $ticket->user->username }} — {{ $ticket->user->contact }}<br>
            <span class="text-muted small">{{ $ticket->user->name }}</span>
        @else
            {{ $ticket->guest_name }}<br>
            <a href="mailto:{{ $ticket->guest_email }}">{{ $ticket->guest_email }}</a>
        @endif
    </dd>
    <dt class="col-sm-2">Created</dt>
    <dd class="col-sm-10">{{ $ticket->created_at?->format('Y-m-d H:i') }}</dd>
</dl>

<div class="card shadow-sm mb-4">
    <div class="card-header">Message</div>
    <div class="card-body"><pre class="mb-0" style="white-space: pre-wrap;">{{ $ticket->body }}</pre></div>
</div>

<form method="post" action="{{ route('admin.help-desk.update', $ticket) }}" class="d-flex flex-wrap align-items-center gap-2">
    @csrf
    @method('PATCH')
    <label class="form-label mb-0">Update status</label>
    <select name="status" class="form-select form-select-sm" style="width: auto;">
        <option value="open" @selected($ticket->status === 'open')>Open</option>
        <option value="resolved" @selected($ticket->status === 'resolved')>Resolved</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Save</button>
</form>
@endsection
