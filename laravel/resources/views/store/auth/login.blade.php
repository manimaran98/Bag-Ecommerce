@extends('store.layouts.app')

@section('title', 'Login — '.config('app.name'))

@section('content')
@php $recaptchaSiteKey = config('ai_chat.captcha.recaptcha_site_key'); @endphp
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4 text-center">Login</h1>
                    <form method="post" action="{{ route('login') }}" novalidate id="login-form">
                        @csrf
                        {{-- Honeypot: hidden from real users, bots fill it and get blocked --}}
                        <div style="display:none" aria-hidden="true">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                            <input type="hidden" name="_form_ts" value="{{ time() }}">
                        </div>
                        @if ($recaptchaSiteKey)
                            <input type="hidden" name="g-recaptcha-response" id="login-recaptcha-token">
                        @endif
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
                        <button type="submit" class="btn btn-primary w-100" id="login-submit">Sign in</button>
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

@if ($recaptchaSiteKey)
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
<script>
(function () {
    const form = document.getElementById('login-form');
    const tokenInput = document.getElementById('login-recaptcha-token');
    const siteKey = @json($recaptchaSiteKey);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        grecaptcha.ready(function () {
            grecaptcha.execute(siteKey, { action: 'login' }).then(function (token) {
                tokenInput.value = token;
                form.submit();
            });
        });
    });
})();
</script>
@endpush
@endif
