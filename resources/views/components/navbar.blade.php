<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        {{-- Brand/Logo --}}
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <i class="fa-solid fa-cloud-sun-rain me-2"></i>
            <span>Weather</span>
        </a>

        {{-- Tombol Toggler untuk Mobile --}}
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Utama --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            {{-- Link Navigasi di Tengah --}}
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}"
                        href="{{ route('map') }}">Peta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('table') ? 'active' : '' }}"
                        href="{{ route('table') }}">Tabel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('weather.map') ? 'active' : '' }}"
                        href="{{ route('weather.map') }}">Peta Cuaca</a>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            API Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('api.points') }}" target="_blank">Points</a></li>
                            <li><a class="dropdown-item" href="{{ route('api.polylines') }}" target="_blank">Polylines</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('api.polygons') }}" target="_blank">Polygons</a>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>

            {{-- Link Aksi di Kanan --}}
            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm"><i class="fa-solid fa-right-to-bracket"></i></a>
                @endguest

                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-right-to-bracket"></i></button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Tambahkan CSS ini ke file CSS utama Anda atau di dalam tag <style> --}}
<style>
    .navbar {
        background-color: rgba(13, 17, 23, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        transition: background-color 0.3s ease;
    }

    .navbar-brand {
        font-size: 1.1rem;
        color: #f0f6fc;
    }

    .navbar .nav-link {
        color: #8b949e;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: color 0.2s ease, background-color 0.2s ease;
    }

    .navbar .nav-link:hover {
        color: #0281ff;
        background-color: rgba(173, 186, 199, 0.1);
    }

    .navbar .nav-link.active {
        color: #0080ff;
        font-weight: 600;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    .dropdown-menu {
        background-color: #161b22;
        border: 1px solid #30363d;
        border-radius: 8px;
    }

    .dropdown-item {
        color: #8b949e;
    }

    .dropdown-item:hover {
        color: #f0f6fc;
        background-color: #0d447a;
    }
</style>
