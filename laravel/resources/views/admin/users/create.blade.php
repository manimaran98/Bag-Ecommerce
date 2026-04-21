@extends('admin.layout')

@section('title', 'New user')

@section('content')
<h1 class="h3 mb-4">New user</h1>
<form method="post" action="{{ route('admin.users.store') }}" class="card shadow-sm p-4" style="max-width: 520px;">
    @csrf
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Contact</label>
        <input type="text" name="contact" value="{{ old('contact') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" value="{{ old('address') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required minlength="8">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
