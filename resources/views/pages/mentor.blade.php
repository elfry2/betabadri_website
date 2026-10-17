@extends('layouts.app')

@section('title', __('Expert Instructors') . ' - Beta Badri Education')

@section('content')

<section class="program-hero position-relative d-flex align-items-center justify-content-center text-center"
         style="background-image: url('{{ asset('img/bg-mentor.jpg') }}'); min-height: 400px;">
    <div class="program-bg-overlay"></div>
    <div class="container position-relative z-2">
        <span class="badge bg-cyan-soft text-cyan px-3 py-2 rounded-pill mb-3 fw-bold border border-info" data-aos="fade-down">{{ __('Expert Instructors') }}</span>
        <h1 class="display-3 fw-bold text-white mb-3" data-aos="fade-up">{!! __('Our Family') !!}</h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="100">
            {{ __('Mentor Hero Desc') }}
        </p>
    </div>
</section>

{{-- CEO WELCOME SPEECH SECTION --}}
<section class="py-5 ceo-speech-section position-relative overflow-hidden">
    <div class="ceo-glow-orb top-0 end-0"></div>
    <div class="glow-bg-left position-absolute bottom-0 start-0"></div>

    <div class="container py-lg-4 position-relative z-2">
        <div class="ceo-speech-card p-4 p-md-5" data-aos="fade-up">
            <div class="row g-4 g-lg-5 align-items-center">
                {{-- CEO Photo Column --}}
                <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
                    <div class="ceo-portrait-frame">
                        <img src="{{ asset('img/ceo.jpg') }}" alt="CEO Beta Badri Education" class="img-fluid d-block">
                        <div class="ceo-portrait-overlay">
                            <!-- <span class="badge bg-cyan-soft text-cyan border border-info rounded-pill px-3 py-1 mb-2 fw-semibold small">
                                <i class="fas fa-crown me-1"></i> {{ __('Leadership') }}
                            </span> -->
                            <h4 class="text-white fw-bold mb-1">{{ __('CEO Name') }}</h4>
                            <p class="text-white-50 small mb-0">{{ __('CEO Title') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Speech Content Column --}}
                <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
                    <div class="ps-lg-2">
                        <div class="d-inline-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-cyan-soft text-cyan px-3 py-2 rounded-pill fw-bold border border-info">
                                <i class="fas fa-quote-left me-1"></i> {{ __('Welcome Speech') }}
                            </span>
                            <span class="text-white-50 small"><i class="fas fa-certificate text-warning me-1"></i> {{ __('Beta Badri Education') }}</span>
                        </div>

                        <h2 class="display-6 fw-bold text-white mb-4">
                            {!! __('Speech From CEO') !!}
                        </h2>

                        <div class="ceo-quote-box mb-4">
                            <p class="fst-italic text-info mb-0 fw-medium">
                                {{ __('CEO Speech Quote') }}
                            </p>
                        </div>

                        <div class="ceo-speech-text mb-4">
                            <p>
                                {{ __('CEO Speech P1') }}
                            </p>
                            <p>
                                {{ __('CEO Speech P2') }}
                            </p>
                            <p>
                                {{ __('CEO Speech P3') }}
                            </p>
                        </div>

                        <div class="ceo-signature-block d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle p-2 bg-primary bg-opacity-10 border border-primary border-opacity-25 text-cyan d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fas fa-signature fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-white fw-bold mb-0">{{ __('CEO Name') }}</h6>
                                    <small class="text-cyan">{{ __('CEO Title') }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="ceo-stat-pill text-center">
                                    <span class="d-block text-white fw-bold small">700+</span>
                                    <span class="text-white-50" style="font-size: 0.75rem;">{{ __('Siswa') }}</span>
                                </div>
                                <div class="ceo-stat-pill text-center">
                                    <span class="d-block text-white fw-bold small">100%</span>
                                    <span class="text-white-50" style="font-size: 0.75rem;">Hands-on</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-navy-dark position-relative overflow-hidden">
    <div class="glow-bg-left position-absolute top-0 start-0"></div>

    <div class="container py-5">
        {{--
        <div class="row mb-5 justify-content-center" data-aos="fade-up">
            <div class="col-md-10 text-center">
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 p-1 rounded-pill bg-black-pearl border border-secondary" id="mentor-filters">
                    <button class="btn btn-sm btn-active-cyan rounded-pill px-4 filter-btn" data-filter="all">{{ __('All') }}</button>
                    <button class="btn btn-sm btn-ghost text-white-50 rounded-pill px-3 filter-btn" data-filter="modular">{{ __('Modular Robotics') }}</button>
                    <button class="btn btn-sm btn-ghost text-white-50 rounded-pill px-3 filter-btn" data-filter="electronic">{{ __('Electronic Robotics') }}</button>
                    <button class="btn btn-sm btn-ghost text-white-50 rounded-pill px-3 filter-btn" data-filter="programming">{{ __('Programming') }}</button>
                    <button class="btn btn-sm btn-ghost text-white-50 rounded-pill px-3 filter-btn" data-filter="game">{{ __('Game Dev') }}</button>
                </div>
            </div>
        </div>
        --}}

        @php
            $visibleMentors = collect($mentors)->reject(function ($m) {
                $isTrashed = method_exists($m, 'trashed') && $m->trashed();
                $hasDeletedAt = isset($m->deleted_at) && !empty($m->deleted_at);
                return $isTrashed || $hasDeletedAt;
            });
        @endphp

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center" id="mentor-grid">
            @forelse($visibleMentors as $mentor)
            @if((method_exists($mentor, 'trashed') && $mentor->trashed()) || (!empty($mentor->deleted_at)))
                @continue
            @endif
            @php
                $role = strtolower($mentor->role);
                $classes = '';
                if (str_contains($role, 'game') || str_contains($role, 'unity') || str_contains($role, '3d')) $classes .= 'game ';
                if (str_contains($role, 'coding') || str_contains($role, 'program') || str_contains($role, 'web')) $classes .= 'programming ';
                if (str_contains($role, 'modular') || str_contains($role, 'lego')) $classes .= 'modular ';
                if (str_contains($role, 'electro') || str_contains($role, 'iot') || str_contains($role, 'arduino')) $classes .= 'electronic ';
                if (str_contains($role, 'robot') && !str_contains($role, 'modular') && !str_contains($role, 'electro')) $classes .= 'modular electronic ';
                if (str_contains($role, 'content')) $classes .= 'content';
            @endphp

            <div class="col mentor-item {{ $classes }}" data-aos="fade-up">
                <div class="mentor-card h-100">
                    <div class="mentor-img-wrapper position-relative overflow-hidden bg-dark">
                        @if($mentor->image)
                            <img src="{{ asset('storage/' . $mentor->image) }}" alt="{{ $mentor->name }}" class="img-fluid w-100 h-100 object-fit-cover transition-scale">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary bg-opacity-25 text-white-50">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                    </div>

                    <div class="card-body p-3 p-xl-4 text-center">
                        <h5 class="text-white fw-bold mb-1 fs-6">{{ $mentor->name }}</h5>
                        <p class="text-cyan small mb-3 text-uppercase letter-spacing-1 fw-bold" style="font-size: 0.75rem;">{{ $mentor->role }}</p>
                        <div class="divider-small mx-auto mb-3"></div>
                        <div class="d-flex flex-wrap justify-content-center gap-1">
                            @if(!empty($mentor->skills))
                                @foreach(array_filter(array_map('trim', explode(',', $mentor->skills))) as $skill)
                                    <span class="skill-badge">{{ $skill }}</span>
                                @endforeach
                            @elseif(str_contains($classes, 'game'))
                                <span class="skill-badge">Unity</span> <span class="skill-badge">C#</span>
                            @elseif(str_contains($classes, 'programming'))
                                <span class="skill-badge">Python</span> <span class="skill-badge">Web</span>
                            @elseif(str_contains($classes, 'electronic'))
                                <span class="skill-badge">IoT</span> <span class="skill-badge">Arduino</span>
                            @elseif(str_contains($classes, 'modular'))
                                <span class="skill-badge">Lego</span> <span class="skill-badge">Logic</span>
                            @elseif(str_contains($classes, 'content'))
                                <span class="skill-badge">Research</span> <span class="skill-badge">Creativity</span>
                            @else
                                <span class="skill-badge">{{ __('Expert') }}</span> <span class="skill-badge">{{ __('Teacher') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-white-50 py-5">
                <i class="fas fa-users-slash fa-3x mb-3"></i>
                <p>{{ __('No mentor data displayed') }}</p>
            </div>
            @endforelse
        </div>

        <div id="no-mentor-msg" class="text-center text-white-50 py-5 d-none">
            <i class="far fa-sad-tear fa-2x mb-3 opacity-50"></i>
            <p>{{ __('No mentor for this category') }}</p>
        </div>
    </div>
</section>

<section class="py-5 bg-black-pearl border-top border-secondary">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0">
                <h3 class="text-white fw-bold mb-2">{{ __('Want to Join as an Instructor?') }}</h3>
                <p class="text-white-50 mb-0">{{ __('Join Us Desc') }}</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="https://wa.me/6281376180003" class="btn btn-outline-light btn-lg px-5 rounded-pill">
                    <i class="fas fa-paper-plane me-2"></i> {{ __('Send CV') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- JAVASCRIPT FILTER --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const mentorItems = document.querySelectorAll('.mentor-item');
    const noMsg = document.getElementById('no-mentor-msg');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // 1. Ganti Warna Tombol Aktif
            filterBtns.forEach(b => {
                b.classList.remove('btn-active-cyan');
                b.classList.add('btn-ghost', 'text-white-50');
            });
            this.classList.remove('btn-ghost', 'text-white-50');
            this.classList.add('btn-active-cyan');

            // 2. Filter Item
            const filterValue = this.getAttribute('data-filter');
            let visibleCount = 0;

            mentorItems.forEach(item => {
                // Reset animasi
                item.classList.remove('animate__animated', 'animate__fadeIn');

                // Cek apakah item punya class yang sesuai dengan tombol
                if (filterValue === 'all' || item.classList.contains(filterValue)) {
                    item.classList.remove('d-none');
                    void item.offsetWidth; // Trigger reflow untuk restart animasi
                    item.classList.add('animate__animated', 'animate__fadeIn');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            // 3. Cek jika hasil 0
            if(visibleCount === 0) noMsg.classList.remove('d-none');
            else noMsg.classList.add('d-none');
        });
    });
});
</script>

<style>
    .mentor-img-wrapper {
        width: 100%;
        height: 270px;
        background-color: #0f172a;
    }
    .transition-scale { transition: transform 0.5s ease; }
    .mentor-card:hover .transition-scale { transform: scale(1.05); }

    .skill-badge {
        font-size: 0.7rem; padding: 4px 10px; border-radius: 20px;
        background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
        color: #94a3b8; display: inline-block;
    }

    @media (min-width: 992px) {
        #mentor-grid.row-cols-lg-5 > .col,
        #mentor-grid.row-cols-lg-5 > .mentor-item {
            flex: 0 0 auto;
            width: 20%;
        }
    }
</style>

@endsection
