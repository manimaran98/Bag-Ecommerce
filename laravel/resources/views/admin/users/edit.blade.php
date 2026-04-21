@extends('admin.layout')

@section('title', 'Edit user')

@section('content')
<h1 class="h3 mb-4">Edit user #{{ $user->id }}</h1>
<form method="post" action="{{ route('admin.users.update', $user) }}" class="card shadow-sm p-4" style="max-width: 520px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Contact</label>
        <input type="text" name="contact" value="{{ old('contact', $user->contact) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">New password (optional)</label>
        <input type="password" name="new_password" class="form-control" minlength="8" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back</a>
</form>
@endsection
