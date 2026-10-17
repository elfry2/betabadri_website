@extends('layouts.admins')

@section('title', 'Kelola Mentor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 text-white fw-bold">Daftar Mentor</h3>
        <p class="text-secondary small mb-0">Tim pengajar dan staff ahli.</p>
    </div>
    <a href="{{ route('admin.mentors.create') }}" class="btn btn-primary fw-bold shadow-sm">
        <i class="fas fa-plus me-2"></i> Tambah Mentor
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success bg-success-soft text-success border-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-dark-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Foto</th>
                    <th>Nama & Role</th>
                    <th style="width: 15%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mentors as $item)
                <tr>
                    <td class="text-secondary">{{ $loop->iteration }}</td>

                    <td>
                        <div class="profile-pic rounded-circle overflow-hidden border border-secondary border-opacity-25" style="width: 50px; height: 50px;">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white fw-bold">
                                    {{ substr($item->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </td>

                    <td>
                        <div class="fw-bold text-white">{{ $item->name }}</div>
                        <div class="text-cyan small fw-bold text-uppercase ls-1">{{ $item->role }}</div>
                        @if(!empty($item->skills))
                            <div class="mt-1 d-flex flex-wrap gap-1">
                                @foreach(array_filter(array_map('trim', explode(',', $item->skills))) as $skill)
                                    <span class="badge bg-secondary bg-opacity-25 text-white-50 border border-secondary border-opacity-25" style="font-size: 0.65rem;">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.mentors.edit', $item->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.mentors.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus mentor ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                            <div class="p-3 bg-secondary bg-opacity-10 rounded-circle mb-3">
                                <i class="fas fa-user-graduate fa-2x text-secondary"></i>
                            </div>
                            <h6 class="text-white fw-bold">Belum ada data mentor</h6>
                            <p class="text-white-50 small mb-0">Silakan tambahkan data pengajar baru.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
