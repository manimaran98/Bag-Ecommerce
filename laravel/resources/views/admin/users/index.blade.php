@extends('admin.layout')

@section('title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">New user</a>
</div>
<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>ID</th><th>Username</th><th>Name</th><th>Contact</th><th></th></tr></thead>
        <tbody>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->username }} @if($u->isAdmin())<span class="badge bg-warning text-dark">admin</span>@endif</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->contact }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    @if (!$u->isAdmin())
                    <form method="post" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
