@extends('layouts.admins')

@section('title', 'Edit Mentor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-white fw-bold">Edit Mentor</h4>
        <p class="text-secondary small mb-0">Perbarui profil pengajar.</p>
    </div>
    <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card">
            <form action="{{ route('admin.mentors.update', $mentor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-white-50 small mb-2">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control form-control-dark @error('name') is-invalid @enderror"
                               value="{{ old('name', $mentor->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="text-white-50 small mb-2">Role / Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="role"
                               class="form-control form-control-dark @error('role') is-invalid @enderror"
                               value="{{ old('role', $mentor->role) }}" required>
                        @error('role')
                            <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 small mb-2">Skill Badges (Keahlian)</label>
                    <input type="text" name="skills"
                           class="form-control form-control-dark @error('skills') is-invalid @enderror"
                           value="{{ old('skills', $mentor->skills) }}"
                           placeholder="Contoh: Python, Web, IoT (pisahkan dengan koma)">
                    <div class="text-white-50 small fst-italic mt-1">
                        <i class="fas fa-info-circle me-1"></i> Pisahkan setiap skill dengan tanda koma (,). Akan ditampilkan sebagai badge di halaman Family.
                    </div>
                    @error('skills')
                        <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="text-white-50 small mb-2">Foto Profil</label>

                    <div class="d-flex align-items-center gap-4 p-3 border border-secondary border-opacity-25 rounded bg-navy-lighter">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle overflow-hidden border border-secondary" style="width: 80px; height: 80px;">
                                @if($mentor->image)
                                    <img id="imgPreview" src="{{ asset('storage/' . $mentor->image) }}" alt="Current Photo" class="w-100 h-100 object-fit-cover">
                                @else
                                    <img id="imgPreview" src="#" alt="Preview" class="w-100 h-100 object-fit-cover d-none">
                                    <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white fw-bold h4 m-0">
                                        {{ substr($mentor->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <input type="file" name="image"
                                   class="form-control form-control-dark text-sm @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this)">

                            <div class="text-white-50 small fst-italic mt-2">
                                <i class="fas fa-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengubah foto.
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
                        <i class="fas fa-save me-2"></i> Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        var preview = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                // Pastikan class d-none hilang jika sebelumnya tidak ada gambar
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
