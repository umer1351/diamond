<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
@endphp
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'ERP') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/themes/lite-purple.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-brand.css') }}">
    @if ($isRtl)
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-rtl.css') }}">
    @endif
</head>

<body class="{{ $isRtl ? 'text-right' : 'text-left' }}">
    <div class="auth-layout-wrap" style="background-image: url({{ asset('assets/images/photo-wide-4.jpg') }})">
        <div class="auth-content">
            <div class="card o-hidden" style="background-color:#ffffff9c">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4">
                            <h1 class="mb-3 text-18">{{ __('app.sign_in') }}</h1>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="login">{{ __('app.email_or_mobile') ?? 'Email or Mobile' }}</label>
                                    <input id="login"
                                        class="form-control form-control-rounded @error('login') is-invalid @enderror @error('email') is-invalid @enderror"
                                        name="login" value="{{ old('login', old('email')) }}" required
                                        autocomplete="username" autofocus>
                                    @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">{{ __('app.password') }}</label>
                                    <input id="password" type="password"
                                        class="form-control form-control-rounded @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group ">
                                    <div class="">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('app.remember_me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-rounded btn-primary btn-block mt-2">{{ __('app.sign_in') }}</button>
                            </form>

                            <div class="text-center my-3 text-muted" style="font-size:12px;letter-spacing:.08em">{{ __('app.or') ?? 'OR' }}</div>
                            <a href="{{ route('google.redirect') }}"
                               class="btn btn-rounded btn-block d-flex align-items-center justify-content-center"
                               style="background:#fff;color:#3c4043;border:1px solid #dadce0;gap:10px;font-weight:600">
                                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="" width="18" height="18" onerror="this.style.display='none'">
                                {{ __('app.continue_with_google') ?? 'Continue with Google' }}
                            </a>

                            @if (Route::has('password.request'))
                                <div class="mt-3 text-center">

                                    <a href="{{ route('password.request') }}" class="text-muted"><u>{{ __('app.forgot_password') }}</u></a>
                                </div>
                            @endif
                            @if (Route::has('register'))
                                <div class="mt-2 text-center">
                                    <a href="{{ route('register') }}" class="text-muted">{{ __('app.no_account_register') ?? "Don't have an account? Register" }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/common-bundle-script.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
