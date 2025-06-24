<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Register - WebGIS Cuaca' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/auth-split.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left-panel">
            <div class="left-panel-content">
                <h2 class="left-panel-title">Mulai dengan WebGIS Cuaca</h2>
                <p class="left-panel-subtitle">
                    Lengkapi langkah-langkah mudah ini untuk mendaftar akun Anda.
                </p>
            </div>
        </div>

        <div class="auth-right-panel">
            <form method="POST" action="{{ route('register') }}" class="login-register-form">
                @csrf
                <div class="row gx-3">
                    <div class="col-md-6 mb-3">
                        <x-input-label for="first_name" :value="__('Nama Depan')" class="form-label" />
                        <div class="input-group">
                            <x-text-input id="first_name" class="form-control" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" placeholder="mis. John" />
                        </div>
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-input-label for="last_name" :value="__('Nama Belakang')" class="form-label" />
                        <div class="input-group">
                            <x-text-input id="last_name" class="form-control" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" placeholder="mis. Doe" />
                        </div>
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" class="form-label" />
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="alamat@email.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" class="form-label" />
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="minimal 8 karakter" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="form-label" />
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="ulangi kata sandi" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="d-grid mb-3">
                    <x-primary-button class="btn btn-primary-gradient">
                        {{ __('Daftar Akun') }}
                    </x-primary-button>
                </div>

                <div class="text-center">
                    <p class="form-footer-text">Sudah punya akun? <a href="{{ route('login') }}" class="hover-link">Masuk di sini</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
