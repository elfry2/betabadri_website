@extends('layouts.admins')

@section('title', 'Absensi & Kehadiran')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="mb-1 text-white fw-bold">Dashboard Absensi</h3>
        <p class="text-secondary small mb-0">Pantau kehadiran tim dan kelola absensi harian Anda.</p>
    </div>
    <div class="text-md-end p-3 rounded-3 bg-navy-lighter border border-secondary border-opacity-25 shadow-sm d-flex align-items-center gap-3">
        <div class="text-end">
            <h2 id="clock-display" class="text-white fw-bold mb-0" style="letter-spacing: 2px; font-family: monospace;">00.00.00</h2>
            <div class="text-cyan small fw-bold text-uppercase ls-1">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
        <i class="fas fa-clock fa-2x text-secondary opacity-25"></i>
    </div>
</div>

{{-- Notifikasi --}}
@if ($errors->any())
    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 mb-4 small">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-25 mb-4">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center p-3 h-100 border-start border-4 border-success bg-navy-lighter">
            <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success me-3">
                <i class="fas fa-user-check fa-lg"></i>
            </div>
            <div>
                <p class="text-secondary small mb-0 text-uppercase fw-bold">Hadir (Sesuai Filter)</p>
                <h3 class="text-white fw-bold mb-0">{{ $summary['present'] ?? 0 }}</h3>
                <small class="text-success small"><i class="fas fa-arrow-up"></i> Tepat Waktu</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center p-3 h-100 border-start border-4 border-warning bg-navy-lighter">
            <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning me-3">
                <i class="fas fa-user-clock fa-lg"></i>
            </div>
            <div>
                <p class="text-secondary small mb-0 text-uppercase fw-bold">Terlambat</p>
                <h3 class="text-white fw-bold mb-0">{{ $summary['late'] ?? 0 }}</h3>
                <small class="text-warning small">Perlu Perhatian</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center p-3 h-100 border-start border-4 border-danger bg-navy-lighter">
            <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger me-3">
                <i class="fas fa-user-times fa-lg"></i>
            </div>
            <div>
                <p class="text-secondary small mb-0 text-uppercase fw-bold">Tidak Masuk</p>
                <h3 class="text-white fw-bold mb-0">{{ $summary['absent'] ?? 0 }}</h3>
                <small class="text-danger small">Izin / Alpha</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 order-lg-2">
        <div class="content-card attendance-card sticky-lg-top" style="top: 100px; z-index: 10;">
            <div class="card-header border-bottom border-secondary border-opacity-25 pb-3 mb-3">
                <h6 class="text-white fw-bold mb-0">
                    <i class="fas fa-fingerprint text-primary me-2"></i> Absensi Saya
                </h6>
            </div>
            <div class="text-center">
                @if(!$todayAttendance)
                    <div class="mb-3 text-start px-md-0 px-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-white fw-bold mb-1">Absen Masuk</h5>
                            <span id="camera-mode-badge" class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 x-small">
                                <i class="fas fa-camera me-1"></i> Kamera Selfie
                            </span>
                        </div>
                        <p class="text-white-50 small mb-0">Posisikan wajah Anda pada kamera lalu ambil foto selfie.</p>
                    </div>

                    <form id="form-clock-in" action="{{ route('admin.attendance.clockIn') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                        <input type="file" name="image_in" id="image_in" class="d-none" accept="image/*">
                        
                        <!-- Native Mobile Camera Input: works on all phones over HTTP and HTTPS -->
                        <input type="file" id="mobile_camera_input" accept="image/*" capture="user" class="d-none" onchange="handleCapturedPhoto(this)">

                        <!-- Camera Viewport & Preview (Drawn out edge-to-edge on mobile) -->
                        <div class="camera-wrapper position-relative overflow-hidden mb-3 bg-black text-center d-flex align-items-center justify-content-center">
                            <!-- Desktop Live Video (if WebRTC is active) -->
                            <video id="camera-stream" autoplay playsinline muted class="w-100 d-none" style="object-fit: cover; aspect-ratio: 4/3; transform: scaleX(-1); display: none;"></video>
                            <canvas id="snapshot-canvas" class="d-none"></canvas>
                            
                            <!-- Captured Snapshot Preview -->
                            <img id="snapshot-preview" class="w-100 d-none" style="object-fit: cover; aspect-ratio: 4/3;" alt="Preview Selfie">

                            <!-- Camera Prompt Area (Fallback when WebRTC unavailable or on non-secure HTTP) -->
                            <div id="camera-prompt-box" class="camera-prompt-area p-4 d-flex flex-column align-items-center justify-content-center text-center cursor-pointer w-100 h-100 d-none" onclick="openCamera()">
                                <div class="avatar-camera-btn mb-3 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-camera fa-2x text-primary"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-1" id="prompt-box-title">Buka Kamera Selfie</h6>
                                <p class="text-white-50 x-small px-3 mb-3" id="prompt-box-desc">Ketuk di sini untuk mengambil foto selfie langsung dari kamera.</p>
                                <span class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" id="prompt-box-btn">
                                    <i class="fas fa-camera me-1"></i> Buka Kamera
                                </span>
                            </div>

                            <!-- Loading State -->
                            <div id="camera-loading" class="position-absolute top-50 start-50 translate-middle text-center w-100 px-3 d-none">
                                <div class="spinner-border text-primary spinner-border-sm mb-2" role="status"></div>
                                <div class="text-white-50 small">Menghubungkan kamera...</div>
                            </div>

                            <!-- Live Badge (Desktop WebRTC only) -->
                            <div id="camera-live-badge" class="position-absolute top-0 start-0 m-2 badge bg-danger bg-opacity-75 d-none">
                                <i class="fas fa-circle fa-xs me-1 text-danger animate-pulse"></i> LIVE
                            </div>
                        </div>

                        <!-- Camera Action Buttons -->
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" id="btn-snap" onclick="openCamera()" class="btn btn-outline-light w-100 py-2 fw-bold">
                                <i class="fas fa-camera me-1 text-primary"></i> <span id="snap-btn-text">Buka Kamera</span>
                            </button>
                            <button type="button" id="btn-retake" onclick="retakePhoto()" class="btn btn-outline-warning w-100 py-2 fw-bold d-none">
                                <i class="fas fa-redo me-1"></i> Foto Ulang
                            </button>
                        </div>

                        <!-- Fallback Manual Upload Toggle -->
                        <div class="mb-3 text-start">
                            <div id="manual-upload-wrapper" class="d-none mb-2">
                                <label class="text-white-50 x-small mb-1">Pilih File Foto Dari Galeri/Disk:</label>
                                <input type="file" id="image_in_manual" class="form-control form-control-dark form-control-sm" accept="image/*" onchange="handleManualFile(this)">
                            </div>
                            <button type="button" class="btn btn-link text-white-50 x-small p-0 text-decoration-none" onclick="toggleManualUpload()">
                                <i class="fas fa-paperclip me-1"></i> <span id="manual-upload-toggle-text">Masalah kamera? Upload file manual</span>
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <button type="button" id="btn-clock-in" onclick="handleClockIn(this)" class="btn btn-primary w-100 py-3 fw-bold shadow-lg" disabled>
                            <span>ABSEN MASUK</span> <i class="fas fa-map-marker-alt ms-2"></i>
                        </button>
                    </form>

                @elseif($todayAttendance->clock_out == null)
                    <div class="mb-4 text-start">
                        <div class="d-flex align-items-center mb-2 p-3 bg-success bg-opacity-10 rounded border border-success border-opacity-25">
                            <div class="me-3"><i class="fas fa-check-circle fa-2x text-success"></i></div>
                            <div>
                                <small class="text-success fw-bold text-uppercase">Berhasil Masuk ({{ $todayAttendance->work_mode }})</small>
                                <h4 class="text-white fw-bold mb-0">{{ $todayAttendance->clock_in }}</h4>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.attendance.clockOut', $todayAttendance->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3 text-start">
                            <label class="text-white-50 small mb-2">Ringkasan Kegiatan Hari Ini <span class="text-danger">*</span></label>
                            <textarea name="activity" class="form-control form-control-dark" rows="3" placeholder="Ceritakan singkat apa yang Anda kerjakan..." required maxlength="255"></textarea>
                        </div>
                        <div class="form-group mb-4 text-start">
                            <label class="text-white-50 small mb-2">Upload Bukti Kerja (File/Doc/Gambar) <span class="text-danger">*</span></label>
                            <input type="file" name="report_file" class="form-control form-control-dark text-sm" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                            <i class="fas fa-sign-out-alt me-2"></i> KIRIM & PULANG
                        </button>
                    </form>
                @else
                    <div class="py-4">
                        <i class="fas fa-clipboard-check fa-4x text-success mb-3"></i>
                        <h4 class="text-white fw-bold">Tugas Selesai!</h4>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <div class="text-center px-3 py-2 bg-navy-lighter rounded">
                                <small class="text-secondary d-block" style="font-size:0.6rem">MASUK</small>
                                <span class="text-white fw-bold">{{ $todayAttendance->clock_in }}</span>
                            </div>
                            <div class="text-center px-3 py-2 bg-navy-lighter rounded">
                                <small class="text-secondary d-block" style="font-size:0.6rem">PULANG</small>
                                <span class="text-white fw-bold">{{ $todayAttendance->clock_out }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8 order-lg-1">
        <div class="content-card mb-4 p-4">
            <h5 class="text-white fw-bold mb-3"><i class="fas fa-filter me-2 text-primary"></i> Filter Data</h5>
            <form action="" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="text-white-50 small mb-2">Rentang Waktu</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control form-control-dark" value="{{ request('start_date', date('Y-m-01')) }}">
                            <span class="input-group-text bg-dark border-secondary border-opacity-25 text-white">-</span>
                            <input type="date" name="end_date" class="form-control form-control-dark" value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                    @if(auth()->user()->role === 'admin')
                    <div class="col-md-3">
                        <label class="text-white-50 small mb-2">Role Staff</label>
                        <select name="role" class="form-control form-control-dark form-select-dark">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="mentor" {{ request('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-bold flex-grow-1"><i class="fas fa-search me-1"></i> Cari</button>
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-sync-alt"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="content-card">
            <div class="card-header d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-white fw-bold mb-0">Riwayat Kehadiran</h6>
                <a href="{{ route('admin.attendance.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i> Export .CSV
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Staff</th>
                            <th>Mode</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $key => $item)
                        <tr>
                            <td class="text-secondary">{{ $attendances->firstItem() + $key }}</td>
                            <td>
                                <div class="text-white fw-bold small">{{ $item->user->name }}</div>
                                <div class="text-secondary" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 {{ $item->work_mode == 'WFO' ? 'bg-info text-info border-info' : 'bg-primary text-primary border-primary' }} border small">
                                    {{ $item->work_mode ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-success fw-bold d-block" style="font-size: 0.75rem;">{{ $item->clock_in ?? '-' }}</span>
                                    <span class="text-white-50 d-block mt-1" style="font-size: 0.75rem;">{{ $item->clock_out ?? '--:--' }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="modal" data-bs-target="#showModal{{ $item->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg" style="background-color: #1e293b;">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25 p-4">
                                        <h5 class="modal-title text-white fw-bold"><i class="fas fa-info-circle text-info me-2"></i> Detail Kehadiran</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 text-start">
                                        <div class="row g-4">
                                            <div class="col-md-6 border-end border-secondary border-opacity-25">
                                                <h6 class="text-secondary small fw-bold text-uppercase mb-3">Foto Selfie Masuk</h6>
                                                @if($item->image_in)
                                                    <img src="{{ asset('storage/' . $item->image_in) }}" class="img-fluid rounded border border-secondary border-opacity-25 w-100" style="max-height: 350px; object-fit: cover;">
                                                @else
                                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center text-secondary small" style="height: 250px;">Tidak ada foto</div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 text-start">
                                                <h6 class="text-secondary small fw-bold text-uppercase mb-3">Laporan Kegiatan</h6>
                                                <div class="mb-3">
                                                    <label class="text-secondary x-small d-block mb-1">RINGKASAN:</label>
                                                    <div class="p-3 bg-navy-lighter rounded text-white small italic border border-secondary border-opacity-10">
                                                        "{{ $item->activity ?? 'Belum ada ringkasan.' }}"
                                                    </div>
                                                </div>
                                                @if($item->report_file)
                                                <div class="mb-3">
                                                    <label class="text-secondary x-small d-block mb-1">BUKTI KERJA:</label>
                                                    <a href="{{ asset('storage/' . $item->report_file) }}" target="_blank" class="btn btn-sm btn-navy-lighter text-white border border-secondary border-opacity-25 w-100 text-start">
                                                        <i class="fas fa-file-download me-2 text-success"></i> Lihat Lampiran
                                                    </a>
                                                </div>
                                                @endif
                                                <div class="mt-4 pt-3 border-top border-secondary border-opacity-10">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small class="text-secondary d-block">MASUK:</small>
                                                            <span class="text-success fw-bold">{{ $item->clock_in ?? '--:--' }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-secondary d-block">PULANG:</small>
                                                            <span class="text-danger fw-bold">{{ $item->clock_out ?? '--:--' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-white-50 small">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 px-2">
                {{ $attendances->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('clock-display').textContent = timeString.replace(/:/g, '.');
    }
    setInterval(updateClock, 1000);
    updateClock();

    let streamTrack = null;
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const isSecure = window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    let isWebRTCActive = false;

    function setupCameraMode() {
        const modeBadge = document.getElementById('camera-mode-badge');
        const snapBtnText = document.getElementById('snap-btn-text');

        // Attempt In-Page Live Camera first on ALL devices (Mobile and Desktop)
        if (isSecure && navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            if (modeBadge) modeBadge.innerHTML = '<i class="fas fa-video me-1"></i> Kamera Langsung';
            if (snapBtnText) snapBtnText.textContent = 'Ambil Foto';
            initLiveWebcam();
        } else {
            // Automatic Fallback: Non-secure HTTP (e.g. http://192.168.x.x:8000) or browser without WebRTC
            switchToNativeCameraMode(
                isMobile 
                    ? 'Kamera Langsung memerlukan koneksi HTTPS. Menggunakan kamera HP.' 
                    : 'Kamera langsung memerlukan koneksi aman (HTTPS).'
            );
        }
    }

    async function initLiveWebcam() {
        const video = document.getElementById('camera-stream');
        const promptBox = document.getElementById('camera-prompt-box');
        const loading = document.getElementById('camera-loading');
        const liveBadge = document.getElementById('camera-live-badge');
        const snapBtn = document.getElementById('btn-snap');
        const snapBtnText = document.getElementById('snap-btn-text');
        const modeBadge = document.getElementById('camera-mode-badge');

        if (!video) return;

        if (promptBox) promptBox.classList.add('d-none');
        if (loading) loading.classList.remove('d-none');

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            });

            video.srcObject = stream;
            streamTrack = stream.getVideoTracks()[0];

            video.onloadedmetadata = () => {
                video.play().then(() => {
                    video.style.display = 'block';
                    video.classList.remove('d-none');
                    if (loading) loading.classList.add('d-none');
                    if (liveBadge) liveBadge.classList.remove('d-none');
                    if (snapBtn) snapBtn.disabled = false;
                    if (snapBtnText) snapBtnText.textContent = 'Ambil Foto';
                    if (modeBadge) modeBadge.innerHTML = '<i class="fas fa-video me-1"></i> Kamera Langsung';
                    isWebRTCActive = true;
                }).catch((playErr) => {
                    console.warn('Autoplay prevented, user interaction required:', playErr);
                    if (loading) loading.classList.add('d-none');
                    showActivateLiveButton();
                });
            };
        } catch (err) {
            console.warn('WebRTC camera initialization failed, falling back to native phone camera:', err);
            if (loading) loading.classList.add('d-none');
            switchToNativeCameraMode('Izin kamera langsung tidak aktif. Menggunakan kamera HP.');
        }
    }

    function showActivateLiveButton() {
        const promptBox = document.getElementById('camera-prompt-box');
        const title = document.getElementById('prompt-box-title');
        const desc = document.getElementById('prompt-box-desc');
        const btn = document.getElementById('prompt-box-btn');
        const snapBtnText = document.getElementById('snap-btn-text');

        if (title) title.textContent = 'Ketuk untuk Aktifkan Kamera';
        if (desc) desc.textContent = 'Browser memerlukan ketukan layar untuk menyalakan kamera langsung.';
        if (btn) btn.innerHTML = '<i class="fas fa-play me-1"></i> Mulai Kamera Langsung';
        if (snapBtnText) snapBtnText.textContent = 'Mulai Kamera Langsung';
        if (promptBox) promptBox.classList.remove('d-none');
    }

    function switchToNativeCameraMode(reason) {
        const modeBadge = document.getElementById('camera-mode-badge');
        const snapBtnText = document.getElementById('snap-btn-text');
        const promptBox = document.getElementById('camera-prompt-box');
        const title = document.getElementById('prompt-box-title');
        const desc = document.getElementById('prompt-box-desc');
        const btn = document.getElementById('prompt-box-btn');
        const video = document.getElementById('camera-stream');

        isWebRTCActive = false;
        if (video) video.classList.add('d-none');

        if (modeBadge) modeBadge.innerHTML = '<i class="fas fa-camera me-1"></i> Kamera HP';
        if (snapBtnText) snapBtnText.textContent = 'Buka Kamera HP';

        if (title) title.textContent = 'Buka Kamera Selfie';
        if (desc) desc.textContent = reason || 'Ketuk untuk mengambil foto selfie langsung dari kamera.';
        if (btn) btn.innerHTML = '<i class="fas fa-camera me-1"></i> Buka Kamera HP';

        if (promptBox) promptBox.classList.remove('d-none');
    }

    function openCamera() {
        const video = document.getElementById('camera-stream');

        // If live video is currently active and visible, take the snapshot
        if (isWebRTCActive && video && !video.classList.contains('d-none')) {
            takeWebRTCSnapshot();
            return;
        }

        // If live stream is ready but paused due to mobile autoplay policy
        if (video && video.srcObject && video.paused) {
            video.play().then(() => {
                video.style.display = 'block';
                video.classList.remove('d-none');
                document.getElementById('camera-prompt-box').classList.add('d-none');
                document.getElementById('camera-live-badge').classList.remove('d-none');
                document.getElementById('snap-btn-text').textContent = 'Ambil Foto';
                isWebRTCActive = true;
            });
            return;
        }

        // If on HTTPS / secure context and WebRTC hasn't run yet, try starting live camera
        if (isSecure && navigator.mediaDevices && navigator.mediaDevices.getUserMedia && !isWebRTCActive) {
            initLiveWebcam();
            return;
        }

        // Fallback: trigger native mobile camera input (capture="user")
        const mobileInput = document.getElementById('mobile_camera_input');
        if (mobileInput) {
            mobileInput.click();
        }
    }

    function takeWebRTCSnapshot() {
        const video = document.getElementById('camera-stream');
        const canvas = document.getElementById('snapshot-canvas');
        if (!video || !canvas) return;

        const w = video.videoWidth || 640;
        const h = video.videoHeight || 480;
        canvas.width = w;
        canvas.height = h;

        const ctx = canvas.getContext('2d');
        ctx.translate(w, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, w, h);

        canvas.toBlob((blob) => {
            if (!blob) {
                alert('Gagal mengambil foto. Silakan coba lagi.');
                return;
            }
            const file = new File([blob], 'selfie-' + Date.now() + '.jpg', { type: 'image/jpeg' });
            applyPhotoToFile(file, URL.createObjectURL(blob));
        }, 'image/jpeg', 0.85);
    }

    function handleCapturedPhoto(input) {
        if (!input.files || input.files.length === 0) return;
        const rawFile = input.files[0];
        compressAndSetFile(rawFile);
    }

    function compressAndSetFile(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const maxDim = 1280;
                let w = img.width;
                let h = img.height;

                if (w > maxDim || h > maxDim) {
                    if (w > h) {
                        h = Math.round((h * maxDim) / w);
                        w = maxDim;
                    } else {
                        w = Math.round((w * maxDim) / h);
                        h = maxDim;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);

                canvas.toBlob((blob) => {
                    if (!blob) {
                        applyPhotoToFile(file, e.target.result);
                        return;
                    }
                    const compressedFile = new File([blob], 'selfie-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                    applyPhotoToFile(compressedFile, URL.createObjectURL(blob));
                }, 'image/jpeg', 0.85);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function applyPhotoToFile(file, previewUrl) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('image_in').files = dt.files;

        const preview = document.getElementById('snapshot-preview');
        const video = document.getElementById('camera-stream');
        const promptBox = document.getElementById('camera-prompt-box');
        const snapBtn = document.getElementById('btn-snap');
        const retakeBtn = document.getElementById('btn-retake');
        const clockInBtn = document.getElementById('btn-clock-in');
        const liveBadge = document.getElementById('camera-live-badge');

        if (preview) {
            preview.src = previewUrl;
            preview.classList.remove('d-none');
        }
        if (video) video.classList.add('d-none');
        if (promptBox) promptBox.classList.add('d-none');
        if (liveBadge) liveBadge.classList.add('d-none');

        if (snapBtn) snapBtn.classList.add('d-none');
        if (retakeBtn) retakeBtn.classList.remove('d-none');
        if (clockInBtn) clockInBtn.disabled = false;
    }

    function retakePhoto() {
        const video = document.getElementById('camera-stream');
        const preview = document.getElementById('snapshot-preview');
        const promptBox = document.getElementById('camera-prompt-box');
        const snapBtn = document.getElementById('btn-snap');
        const retakeBtn = document.getElementById('btn-retake');
        const clockInBtn = document.getElementById('btn-clock-in');
        const liveBadge = document.getElementById('camera-live-badge');
        const fileInput = document.getElementById('image_in');
        const mobileInput = document.getElementById('mobile_camera_input');

        if (fileInput) fileInput.value = '';
        if (mobileInput) mobileInput.value = '';

        if (preview) {
            preview.classList.add('d-none');
            preview.src = '';
        }

        if (isWebRTCActive && video) {
            video.classList.remove('d-none');
            if (liveBadge) liveBadge.classList.remove('d-none');
            const snapBtnText = document.getElementById('snap-btn-text');
            if (snapBtnText) snapBtnText.textContent = 'Ambil Foto';
        } else {
            if (promptBox) promptBox.classList.remove('d-none');
            const snapBtnText = document.getElementById('snap-btn-text');
            if (snapBtnText) snapBtnText.textContent = 'Buka Kamera HP';
        }

        if (snapBtn) {
            snapBtn.classList.remove('d-none');
            snapBtn.disabled = false;
        }
        if (retakeBtn) retakeBtn.classList.add('d-none');
        if (clockInBtn) clockInBtn.disabled = true;
    }

    function toggleManualUpload(forceOpen = false) {
        const wrapper = document.getElementById('manual-upload-wrapper');
        const text = document.getElementById('manual-upload-toggle-text');
        if (!wrapper) return;

        const isHidden = wrapper.classList.contains('d-none');
        if (forceOpen || isHidden) {
            wrapper.classList.remove('d-none');
            text.textContent = 'Sembunyikan upload manual';
        } else {
            wrapper.classList.add('d-none');
            text.textContent = 'Masalah kamera? Upload file manual';
        }
    }

    function handleManualFile(input) {
        if (!input.files || input.files.length === 0) return;
        compressAndSetFile(input.files[0]);
    }

    function handleClockIn(btn) {
        const form = document.getElementById('form-clock-in');
        const fileInput = document.getElementById('image_in');
        const originalText = btn.innerHTML;

        if (!fileInput.files || fileInput.files.length === 0) {
            alert("Harap ambil foto selfie terlebih dahulu.");
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mendeteksi Lokasi...';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;

                    if (streamTrack) {
                        try { streamTrack.stop(); } catch (e) {}
                    }

                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';
                    form.submit();
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    let msg = "Gagal mendapatkan lokasi GPS.";
                    if (error.code === error.PERMISSION_DENIED) {
                        msg = "Izin lokasi GPS ditolak. Harap izinkan akses lokasi di pengaturan browser HP Anda.";
                    } else if (error.code === error.TIMEOUT) {
                        msg = "Waktu pencarian GPS habis. Pastikan sinyal GPS aktif.";
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        msg = "Lokasi tidak dapat ditentukan. Pastikan GPS HP aktif.";
                    }
                    alert(msg);
                },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
            );
        } else {
            alert("Browser tidak mendukung geolokasi GPS.");
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('camera-prompt-box')) {
            setupCameraMode();
        }
    });
</script>

<style>
    .ls-1 { letter-spacing: 1px; }
    .btn-icon {
        width: 32px; height: 32px; padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px;
    }
    .bg-navy-lighter { background-color: rgba(30, 41, 59, 0.5); }
    .x-small { font-size: 0.7rem; }
    .cursor-pointer { cursor: pointer; }

    .camera-wrapper {
        min-height: 240px;
        background-color: #0b1120;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .avatar-camera-btn {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: rgba(67, 97, 238, 0.15);
        border: 2px dashed rgba(67, 97, 238, 0.5);
        transition: transform 0.2s ease;
    }
    .camera-prompt-area:hover .avatar-camera-btn {
        transform: scale(1.08);
        background: rgba(67, 97, 238, 0.25);
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.25; }
    }
    .animate-pulse {
        animation: pulse-dot 1.2s infinite ease-in-out;
    }

    /* Mobile Breakout: Draw out of container on mobile screens */
    @media (max-width: 991.98px) {
        .attendance-card {
            margin-left: -15px;
            margin-right: -15px;
            border-radius: 14px;
            padding: 20px 16px !important;
        }
    }
    @media (max-width: 576px) {
        .attendance-card {
            margin-left: -20px;
            margin-right: -20px;
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
            padding: 20px 16px !important;
        }
        .attendance-card .camera-wrapper {
            margin-left: -16px;
            margin-right: -16px;
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
        }
    }
</style>
@endsection
