@extends('layout.template')

@section('content')
    <div class="scroll-container">
        {{-- SECTION 1: HERO --}}
        <section id="hero"
            class="scroll-section full-screen d-flex flex-column justify-content-center align-items-center text-center">
            <div class="content-wrapper">
                <h1 class="hero-title display-2 text-white fw-bold mb-3">WebGIS Cuaca</h1>
                <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 600px;">
                    WebGIS Cuaca menyajikan data cuaca kompleks dalam satu tampilan. Dengan antarmuka yang simpel, dapatkan
                    wawasan cuaca, sederhanakan pengalaman pengguna, dan lihat data untuk lokasi Anda.
                </p>
                <a href="{{ route('map') }}" class="btn btn-get-access btn-lg">
                    Lihat Peta <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </section>

        {{-- SECTION 2: FITUR UNGGULAN --}}
        <section id="features"
            class="scroll-section full-screen d-flex flex-column justify-content-center align-items-center">
            <div class="container text-center">
                <div class="content-wrapper">
                    <h2 class="section-title text-white fw-bold mb-5">Fitur Unggulan</h2>
                    <div class="row g-5 align-items-stretch">
                        <div class="col-md-4 feature-card">
                            <div class="card-inner">
                                <i class="fas fa-map-marked-alt fa-3x text-primary mb-3"></i>
                                <h3 class="h5 text-white">Peta Interaktif</h3>
                                <p class="text-white-50">Visualisasikan lokasi dan data cuaca secara langsung pada peta yang
                                    responsif dan mudah digunakan.</p>
                            </div>
                        </div>
                        <div class="col-md-4 feature-card">
                            <div class="card-inner">
                                <i class="fas fa-cloud-sun-rain fa-3x text-primary mb-3"></i>
                                <h3 class="h5 text-white">Cuaca Real-time</h3>
                                <p class="text-white-50">Dapatkan informasi cuaca terkini termasuk suhu, kelembapan, dan
                                    kondisi lainnya.</p>
                            </div>
                        </div>
                        <div class="col-md-4 feature-card">
                            <div class="card-inner">
                                <i class="fas fa-table fa-3x text-primary mb-3"></i>
                                <h3 class="h5 text-white">Tabel Data Interaktif</h3>
                                <p class="text-white-50">Akses data feature point, polyline, dan polygon dalam format tabel
                                    yang terstruktur.</p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <footer id="about" class="scroll-section d-flex flex-column justify-content-center align-items-center pt-5 pb-4">
            <div class="container text-center">
                <div class="content-wrapper">
                    <h2 class="section-title text-white fw-bold mb-4">Tentang WebGIS Cuaca</h2>
                    <p class="text-white-50 mx-auto text-center" style="max-width: 700px;">
                        Aplikasi ini merupakan implementasi dari WebGIS (Web Geographic Information System) yang dirancang
                        untuk menampilkan data cuaca secara dinamis. Dibangun sebagai bagian dari praktikum Pemrograman
                        Geospasial Web, proyek ini mengintegrasikan library MapBox sebagai Basemap dengan data dari OpenWeatherMap untuk
                        memberikan visualisasi yang kaya dan informatif.</p>
                    <hr class="my-5 bg-white-50">
                    <div class="row">
                        <div class="col-md-6 text-md-start">
                            <h5 class="text-white">Informasi Pengembang</h5>
                            <ul class="list-unstyled text-white-50">
                                <li>Muhammad Fauzil Adhim Sulistyo</li>
                                <li>23/524853/SV/23514 | PGWEBL A</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="text-white">Terhubung</h5>
                            <div class="social-icons">
                                <a href="https://github.com/fauziiladhim1" target="_blank" class="text-white-50 me-3 fs-4"><i class="fab fa-github"></i></a>
                                <a href="https://www.linkedin.com/in/fauziiladhim/" target="_blank" class="text-white-50 fs-4"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                    <p class="text-white-50 mt-5 mb-0">© {{ date('Y') }} WebGIS Cuaca. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('styles')
    <style>
        /* Scroll behavior */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Hilangkan seluruh scrollbar (Firefox, Edge, WebKit) */
        html,
        body,
        .scroll-container {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge legacy */
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar,
        .scroll-container::-webkit-scrollbar {
            display: none;
            /* Chrome/Safari/Edge Chromium */
        }


        html {
            scrollbar-width: none;
            scroll-snap-type: y mandatory;
            overflow-x: hidden;
        }

        body {
            &::-webkit-scrollbar {
                display: none;
            }

            background-color: #0d1117;
            color: #c9d1d9;
            font-family: 'Inter',
            sans-serif;
            overflow-y: scroll;
        }

        .scroll-container {
            width: 100%;
            height: 100vh;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
        }

        .scroll-section {
            height: 100vh;
            width: 100%;
            scroll-snap-align: start;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
        }

        .content-wrapper {
            opacity: 0;
            transform: translateY(60px);
            transition: opacity 0.8s ease-out, transform 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .scroll-section.is-visible .content-wrapper {
            opacity: 1;
            transform: translateY(0);
        }

        /* Hero section */
        #hero {
            background: radial-gradient(ellipse at top, #1a3c6b 0%, #0d1117 70%);
            position: relative;
            overflow: hidden;
        }

        #hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://source.unsplash.com/random/1920x1080/?weather') no-repeat center center/cover;
            opacity: 0.15;
            z-index: 0;
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 5rem);
            letter-spacing: -2px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-get-access {
            background: linear-gradient(90deg, #58a6ff, #3b82f6);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3);
        }

        .btn-get-access:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(88, 166, 255, 0.5);
            background: linear-gradient(90deg, #3b82f6, #58a6ff);
        }

        /* Features Section */
        #features {
            background: #0d1117;
        }

        .feature-card {
            position: relative;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .card-inner {
            background: rgba(22, 27, 34, 0.8);
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .feature-card:hover .card-inner {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(88, 166, 255, 0.2);
        }

        /* About Section */
        #about {
            background: linear-gradient(180deg, #0d1117 0%, #161b22 100%);
        }

        .social-icons a {
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .social-icons a:hover {
            color: #58a6ff !important;
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: clamp(2rem, 6vw, 3.5rem);
            }

            .btn-get-access {
                padding: 12px 20px;
                font-size: 0.9rem;
            }

            .feature-card {
                margin-bottom: 1.5rem;
            }

            .scroll-section {
                padding: 0 15px;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                transform: translateY(30px);
            }

            .section-title {
                font-size: 1.8rem;
            }

            .row {
                flex-direction: column;
                text-align: center;
            }

            .col-md-6.text-md-start,
            .col-md-6.text-md-end {
                text-align: center !important;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.scroll-section');
            const navLinks = document.querySelectorAll('nav a');

            const options = {
                root: null,
                threshold: 0.5,
                rootMargin: '0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        const id = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.toggle('active', link.getAttribute('href') ===
                                `#${id}`);
                        });
                    } else {
                        entry.target.classList.remove('is-visible');
                    }
                });
            }, options);

            sections.forEach(section => observer.observe(section));

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            const hero = document.querySelector('#hero');
            window.addEventListener('scroll', () => {
                const scrollPosition = window.scrollY;
                hero.style.backgroundPositionY = `${scrollPosition * 0.3}px`;
            });
        });
    </script>
@endsection
