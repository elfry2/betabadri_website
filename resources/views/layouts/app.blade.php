<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beta Badri Education')</title>

    {{-- CSS Libraries --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/themes/splide-default.min.css">

    {{-- Local Assets --}}
    <link href="{{ asset('vendor/venobox/venobox.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

    {{-- Custom CSS --}}
    <link href="{{ asset('css/public/navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/beranda.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/program.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/mentor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/visi.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/kontak.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/galeri.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/karir.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public/login.css') }}" rel="stylesheet">

</head>
<body>

    {{-- HEADER / NAVBAR --}}
    <header id="header" class="fixed-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <a class="navbar-brand p-0" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo-betabadri-dark.png') }}" alt="Logo" class="logo-image">
                </a>

                <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto align-items-center">

                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">{{ __('Beranda') }}</a></li>

                        <li class="nav-item dropdown custom-dropdown d-flex align-items-center h-100">
                            <a class="nav-link pe-2" href="{{ url('/page/programs') }}" id="programsDropdown">
                                {{ __('Program') }} <i class="fas fa-chevron-down" style="font-size: 10px; transition: transform 0.3s;"></i>
                            </a>

                            <ul class="dropdown-menu border-0 shadow-lg animate-slide">
                                <li><a class="dropdown-item py-2" href="{{ route('programs.modular') }}">{{ __('Robotik Modular') }}</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('programs.electronika') }}">{{ __('Robotik Elektronika') }}</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('programs.programming') }}">{{ __('Programming') }}</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('programs.game') }}">{{ __('Game Development') }}</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="{{ route('public.family') }}">{{ __('Family') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/page/visi') }}">{{ __('Visi') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/page/galeri') }}">{{ __('Galeri') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/page/kontak') }}">{{ __('Kontak') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/page/karir') }}">{{ __('Karir') }}</a></li>

                        {{-- Language Switcher --}}
                        <div class="dropdown ms-3">
                            <button class="btn btn-dark border-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                @if(app()->getLocale() == 'id')
                                    <img src="https://flagcdn.com/w20/id.png" alt="ID" style="width: 18px; height: auto;">
                                    <span class="small fw-bold">ID</span>
                                @else
                                    <img src="https://flagcdn.com/w20/us.png" alt="EN" style="width: 18px; height: auto;">
                                    <span class="small fw-bold">EN</span>
                                @endif
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary shadow-lg mt-2 p-1">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 text-white rounded {{ app()->getLocale() == 'id' ? 'bg-primary' : '' }}"
                                    href="{{ route('change.lang', 'id') }}">
                                        <img src="https://flagcdn.com/w20/id.png" style="width: 20px;">
                                        <span>Indonesia</span>
                                        @if(app()->getLocale() == 'id') <i class="fas fa-check ms-auto small"></i> @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 text-white rounded mt-1 {{ app()->getLocale() == 'en' ? 'bg-primary' : '' }}"
                                    href="{{ route('change.lang', 'en') }}">
                                        <img src="https://flagcdn.com/w20/us.png" style="width: 20px;">
                                        <span>English</span>
                                        @if(app()->getLocale() == 'en') <i class="fas fa-check ms-auto small"></i> @endif
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <li class="nav-item ms-lg-4">
                            <a href="{{ url('page/login') }}" class="btn btn-cta-green">{{ __('MASUK') }}</a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="site-footer">
        <div class="container py-5">
            <div class="row g-4 justify-content-between">

                <div class="col-lg-4 col-md-6">
                    <a href="{{ url('/') }}" class="d-block mb-4">
                        <img src="{{ asset('img/logo-betabadri-dark.png') }}" class="logo-footer" alt="Logo Beta Badri">
                    </a>
                    <ul class="list-unstyled contact-info text-light opacity-75">
                        <li class="mb-3 d-flex align-items-start gap-3">
                            <i class="fas fa-phone mt-1 text-primary"></i>
                            <span>+62 813-7618-0003</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start gap-3">
                            <i class="fas fa-envelope mt-1 text-primary"></i>
                            <span>Ymnm000@hotmail.com</span>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-primary"></i>
                            <span>Jl. Dirgantara Timur, Sidumulyo Tim.,<br>Kec. Marpoyan Damai, Kota Pekanbaru, Riau 28289</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white fw-bold mb-4">{{ __('Tautan') }}</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ url('/page/programs') }}">{{ __('Solusi') }}</a></li>
                        <li><a href="{{ url('/page/vision') }}">{{ __('Visi') }}</a></li>
                        <li><a href="{{ url('/page/galeri') }}">{{ __('Galeri') }}</a></li>
                        <li><a href="{{ url('/page/karir') }}">{{ __('Karir') }}</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white fw-bold mb-4">{{ __('Program Kami') }}</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('programs.modular') }}">{{ __('Robotik Modular') }}</a></li>
                        <li><a href="{{ route('programs.electronika') }}">{{ __('Robotik Elektronika') }}</a></li>
                        <li><a href="{{ route('programs.programming') }}">{{ __('Programming') }}</a></li>
                        <li><a href="{{ route('programs.game') }}">{{ __('Game Development') }}</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white fw-bold mb-4">{{ __('Ikuti Kami') }}</h5>
                    <div class="d-flex gap-3 mb-4 social-links">
                        <a href="https://www.facebook.com/profile.php?id=61576559004159#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.tiktok.com/@betabadrieducation?lang=id-ID" class="social-btn"><i class="fab fa-tiktok"></i></a>
                        <a href="https://www.linkedin.com/in/pt-beta-badri-education-72537a366/" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/betabadri_education/" class="social-btn"><i class="fab fa-instagram"></i></a>
                    </div>
                    <p class="small text-white-50 copyright">
                        &copy; 2025 Beta Badri Education.<br>All Rights Reserved.
                    </p>
                </div>

            </div>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.9.3/venobox.min.js"></script>

    <script src="{{ asset('vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('vendor/aos/aos.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/beranda/public.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const header = document.querySelector('#header');
            const navbarCollapse = document.querySelector('#navbarMain');

            // Saat menu dibuka -> Tambah class 'mobile-menu-open'
            navbarCollapse.addEventListener('show.bs.collapse', function () {
                header.classList.add('mobile-menu-open');
            });

            // Saat menu ditutup -> Cek scroll
            navbarCollapse.addEventListener('hide.bs.collapse', function () {
                if (window.scrollY <= 20) {
                    header.classList.remove('mobile-menu-open');
                }
            });
        });
    </script>
</body>
</html>
