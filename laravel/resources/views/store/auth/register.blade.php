@extends('store.layouts.app')

@section('title', 'Register — '.config('app.name'))

@section('content')
@php $recaptchaSiteKey = config('ai_chat.captcha.recaptcha_site_key'); @endphp
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4 text-center">Create account</h1>
                    <form method="post" action="{{ route('register') }}" novalidate id="register-form">
                        @csrf
                        {{-- Honeypot: hidden from real users, bots fill it and get blocked --}}
                        <div style="display:none" aria-hidden="true">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                            <input type="hidden" name="_form_ts" value="{{ time() }}">
                        </div>
                        @if ($recaptchaSiteKey)
                            <input type="hidden" name="g-recaptcha-response" id="register-recaptcha-token">
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $e)
                                    <div>{{ $e }}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="username">Username</label>
                                <input class="form-control" type="text" name="username" id="username" value="{{ old('username') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="contact">Mobile</label>
                                <input class="form-control" type="text" name="contact" id="contact" value="{{ old('contact') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="address">Address</label>
                                <input class="form-control" type="text" name="address" id="address" value="{{ old('address') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="password_confirmation">Confirm password</label>
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-4" id="register-submit">Register</button>
                    </form>
                    <p class="text-center mt-3 mb-0 small text-secondary">
                        Already have an account? <a href="{{ route('login') }}">Login</a>
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
    const form = document.getElementById('register-form');
    const tokenInput = document.getElementById('register-recaptcha-token');
    const siteKey = @json($recaptchaSiteKey);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        grecaptcha.ready(function () {
            grecaptcha.execute(siteKey, { action: 'register' }).then(function (token) {
                tokenInput.value = token;
                form.submit();
            });
        });
    });
})();
</script>
@endpush
@endif
