@extends('store.layouts.app')

@section('title', 'Login — '.config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4 text-center">Login</h1>
                    <form method="post" action="{{ route('login') }}" novalidate>
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $e)
                                    <div>{{ $e }}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" id="username" value="{{ old('username') }}" required autocomplete="username">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" type="password" name="password" id="password" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </form>
                    <p class="text-center mt-3 mb-0 small text-secondary">
                        No account? <a href="{{ route('register') }}">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
