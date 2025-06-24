<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login - WebGIS Cuaca' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/auth-split.css') }}">
</head>

<body>
    <div class="auth-container">
        <div class="auth-left-panel">
            <div class="left-panel-content">
                <h2 class="left-panel-title">Selamat Datang Kembali</h2>
                <p class="left-panel-subtitle">
                    Lihat data cuaca interaktif dan jelajahi kondisi terkini.
                </p>
            </div>
        </div>

        <div class="auth-right-panel">
            <x-auth-session-status class="mb-4 text-success" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}" class="login-register-form">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" class="form-label" />
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <x-text-input id="email" class="form-control" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username"
                            placeholder="alamat@email.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" class="form-label" />
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <x-text-input id="password" class="form-control" type="password" name="password" required
                            autocomplete="current-password" placeholder="masukkan kata sandi" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label for="remember_me" class="d-flex align-items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="form-check-input me-2">
                        <span class="text-sm">{{ __('Ingat saya') }}</span>
                        </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-primary text-decoration-none" href="{{ route('password.request') }}">
                            {{ __('Lupa kata sandi?') }}
                            </a>
                    @endif
                </div>


                <div class="d-grid mb-3">
                    <x-primary-button class="btn btn-primary-gradient">
                        {{ __('Masuk') }}
                    </x-primary-button>
                </div>

                <div class="text-center">
                    <p class="form-footer-text">Belum punya akun? <a href="{{ route('register') }}"
                            class="hover-link">Daftar sekarang</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
