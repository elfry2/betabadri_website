@extends('layouts.admins')

@section('title', 'Tambah Mentor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-white fw-bold">Tambah Mentor</h4>
        <p class="text-secondary small mb-0">Tambahkan tim pengajar baru.</p>
    </div>
    <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card">
            <form action="{{ route('admin.mentors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-white-50 small mb-2">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control form-control-dark @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Nama Mentor" required>
                        @error('name')
                            <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BAGIAN INI YANG DIUBAH MENJADI DROPDOWN --}}
                    <div class="col-md-6 mb-3">
                        <label class="text-white-50 small mb-2">Role / Jabatan <span class="text-danger">*</span></label>
                        <select name="role" class="form-select form-control-dark @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="CEO" {{ old('role') == 'CEO' ? 'selected' : '' }}>CEO</option>
                            <option value="Content Creator" {{ old('role') == 'Content Creator' ? 'selected' : '' }}>Content Creator</option>
                            <option value="Robotics Modular" {{ old('role') == 'Robotics Modular' ? 'selected' : '' }}>Robotics Modular</option>
                            <option value="Robotics Electronic" {{ old('role') == 'Robotics Electronic' ? 'selected' : '' }}>Robotics Electronic</option>
                            <option value="Programming" {{ old('role') == 'Programming' ? 'selected' : '' }}>Programming</option>
                            <option value="Game Development" {{ old('role') == 'Game Development' ? 'selected' : '' }}>Game Development</option>
                            <option value="Assistant Mentor" {{ old('role') == 'Assistant Mentor' ? 'selected' : '' }}>Assistant Mentor</option>
                            <option value="Curriculum Developer" {{ old('role') == 'Curriculum Developer' ? 'selected' : '' }}>Curriculum Developer</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- END PERUBAHAN --}}
                </div>

                <div class="mb-4">
                    <label class="text-white-50 small mb-2">Foto Profil <span class="text-danger">*</span></label>

                    <div class="d-flex align-items-center gap-4 p-3 border border-secondary border-opacity-25 rounded bg-navy-lighter">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle overflow-hidden border border-secondary bg-dark position-relative d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <img id="imgPreview" src="#" alt="Preview" class="w-100 h-100 object-fit-cover d-none">
                                <i id="iconPlaceholder" class="fas fa-user fa-2x text-secondary opacity-50"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <input type="file" name="image"
                                   class="form-control form-control-dark text-sm @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this)" required>

                            <div class="text-white-50 small fst-italic mt-2">
                                <i class="fas fa-info-circle me-1"></i> Format: JPG/PNG, Rasio 1:1 disarankan.
                            </div>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top border-secondary border-opacity-25 mt-2">
                    <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        var preview = document.getElementById('imgPreview');
        var placeholder = document.getElementById('iconPlaceholder');

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none'); // Tampilkan gambar
                if(placeholder) placeholder.classList.add('d-none'); // Sembunyikan icon
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    /* Styling agar Select box sesuai tema dark */
    .form-select.form-control-dark {
        background-color: #0b1120;
        color: white;
        border: 1px solid rgba(255,255,255,0.15);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    }
    .form-select.form-control-dark:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
</style>

@endsection
