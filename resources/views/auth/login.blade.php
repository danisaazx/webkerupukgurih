{{--
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" /> -->

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <!-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> -->

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>--}}




<!DOCTYPE html>
<html>
<head>
    <title>Login - Kerupuk GURIH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .wave-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 0;
            line-height: 0;
        }

        .wave-container svg {
            display: block;
            width: 100%;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
            animation: fadeInUp 0.7s ease forwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(13,110,253,0.12);
        }

        .card-body {
            padding: 2rem 2.5rem;
        }

        .login-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
        }

        .input-group .form-control:focus {
            z-index: 3;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #3b82f6);
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-show-pass {
            border: 1px solid #ced4da;
            border-left: none;
            background: white;
            color: #6c757d;
            cursor: pointer;
            border-radius: 0 0.375rem 0.375rem 0;
            padding: 0 0.75rem;
        }

        .btn-show-pass:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body>

<!-- Animasi Gelombang -->
<div class="wave-container">
    <svg viewBox="0 0 1440 180" xmlns="http://www.w3.org/2000/svg">
        <path fill="#0d6efd22">
            <animate attributeName="d" dur="6s" repeatCount="indefinite"
                values="M0,60 C360,120 720,0 1080,60 C1260,90 1380,40 1440,60 L1440,180 L0,180 Z;
                        M0,80 C360,20 720,100 1080,40 C1260,10 1380,80 1440,50 L1440,180 L0,180 Z;
                        M0,60 C360,120 720,0 1080,60 C1260,90 1380,40 1440,60 L1440,180 L0,180 Z"/>
        </path>
    </svg>
    <svg viewBox="0 0 1440 140" xmlns="http://www.w3.org/2000/svg" style="margin-top:-60px;">
        <path fill="#0d6efd55">
            <animate attributeName="d" dur="4s" repeatCount="indefinite"
                values="M0,70 C300,20 600,100 900,50 C1100,20 1300,80 1440,60 L1440,140 L0,140 Z;
                        M0,50 C300,90 600,20 900,80 C1100,100 1300,30 1440,70 L1440,140 L0,140 Z;
                        M0,70 C300,20 600,100 900,50 C1100,20 1300,80 1440,60 L1440,140 L0,140 Z"/>
        </path>
    </svg>
    <svg viewBox="0 0 1440 100" xmlns="http://www.w3.org/2000/svg" style="margin-top:-50px;">
        <path fill="#0d6efdaa">
            <animate attributeName="d" dur="5s" repeatCount="indefinite"
                values="M0,50 C400,90 800,10 1200,60 C1320,75 1400,40 1440,50 L1440,100 L0,100 Z;
                        M0,30 C400,10 800,80 1200,30 C1320,15 1400,70 1440,40 L1440,100 L0,100 Z;
                        M0,50 C400,90 800,10 1200,60 C1320,75 1400,40 1440,50 L1440,100 L0,100 Z"/>
        </path>
    </svg>
</div>

<!-- Form Login -->
<div class="login-wrapper">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-3">
                <i class="bi bi-box-seam fs-1 text-primary"></i>
            </div>
            <div class="login-title">Management Stock</div>
            <div class="login-subtitle">Kerupuk "GURIH"</div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                        <button type="button" class="btn-show-pass" onclick="togglePassword()" id="toggleBtn">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <button class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>