@extends('pelamar.layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Lengkapi data diri agar kamu bisa melamar lowongan yang tersedia.')

@section('content')

@php
    $fields = [
        'jenis_kelamin', 'no_hp', 'alamat',
        'pendidikan_terakhir', 'pengalaman_kerja',
        'file_ktp', 'file_kk', 'file_cv', 'file_ijazah', 'file_sertifikat',
    ];
    $filled  = collect($fields)->filter(fn($f) => !empty($pelamar?->$f))->count();
    $total   = count($fields);
    $persen  = $total > 0 ? round(($filled / $total) * 100) : 0;
@endphp

{{-- Progress Kelengkapan --}}
<div class="mb-6 bg-white rounded-xl border border-slate-100 shadow-sm p-5">
    <div class="flex items-center justify-between mb-2">
        <p class="text-sm font-medium text-slate-700">Kelengkapan Profil</p>
        <span class="text-sm font-bold {{ $persen === 100 ? 'text-emerald-600' : 'text-blue-600' }}">{{ $persen }}%</span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-2">
        <div class="h-2 rounded-full transition-all duration-500
            {{ $persen === 100 ? 'bg-emerald-500' : 'bg-blue-500' }}"
            style="width: {{ $persen }}%"></div>
    </div>
    @if ($persen < 100)
    <p class="text-xs text-slate-400 mt-2">Lengkapi semua field agar bisa melamar lowongan.</p>
    @else
    <p class="text-xs text-emerald-600 mt-2 flex items-center gap-1"><i class="bi bi-check-circle-fill"></i> Profil kamu sudah lengkap!</p>
    @endif
</div>

<form action="{{ route('pelamar.profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Foto & Akun --}}
    <div class="space-y-6">

        {{-- Foto Profil --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 flex flex-col items-center text-center">
            <div class="relative mb-4">
                <img id="preview-foto"
                    src="{{ $pelamar?->foto ? asset('storage/' . $pelamar->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=2563eb&color=fff&size=128' }}"
                    class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-50">
                <label for="foto" class="absolute bottom-0 right-0 w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700 transition-colors">
                    <i class="bi bi-camera-fill text-white text-xs"></i>
                </label>
                <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
            </div>
            <p class="font-semibold text-slate-800">{{ auth()->user()->nama }}</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
            <span class="mt-2 inline-block px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-xs font-medium">Pelamar</span>
        </div>

        {{-- Ubah Password --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                <i class="bi bi-lock text-slate-400"></i> Ubah Password
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Kosongkan jika tidak diubah">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ulangi password baru">
                </div>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan: Data Diri & Dokumen --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Data Diri --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-5 flex items-center gap-2">
                <i class="bi bi-person text-slate-400"></i> Data Diri
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Nama Lengkap</label>
                    <input type="text" value="{{ auth()->user()->nama }}" disabled
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50 text-slate-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Email</label>
                    <input type="text" value="{{ auth()->user()->email }}" disabled
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50 text-slate-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Jenis Kelamin <span class="text-red-400">*</span></label>
                    <select name="jenis_kelamin"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ $pelamar?->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $pelamar?->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">No. HP <span class="text-red-400">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $pelamar?->no_hp) }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full text-sm border @error('no_hp') border-red-400 @else border-slate-200 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('no_hp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Pendidikan Terakhir <span class="text-red-400">*</span></label>
                    <select name="pendidikan_terakhir"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih --</option>
                        @foreach (['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $p)
                        <option value="{{ $p }}" {{ old('pendidikan_terakhir', $pelamar?->pendidikan_terakhir) === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    @error('pendidikan_terakhir')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Alamat <span class="text-red-400">*</span></label>
                    <textarea name="alamat" rows="2"
                        placeholder="Jl. Contoh No. 1, Kota..."
                        class="w-full text-sm border @error('alamat') border-red-400 @else border-slate-200 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('alamat', $pelamar?->alamat) }}</textarea>
                    @error('alamat')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Pengalaman Kerja</label>
                    <textarea name="pengalaman_kerja" rows="3"
                        placeholder="Ceritakan pengalaman kerja kamu (opsional)..."
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('pengalaman_kerja', $pelamar?->pengalaman_kerja) }}</textarea>
                </div>

            </div>
        </div>

        {{-- Upload Dokumen --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-5 flex items-center gap-2">
                <i class="bi bi-folder2-open text-slate-400"></i> Dokumen
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                @php
                    $dokumens = [
                        ['name' => 'file_cv',          'label' => 'CV / Resume',       'required' => true],
                        ['name' => 'file_ktp',         'label' => 'KTP',               'required' => true],
                        ['name' => 'file_kk',          'label' => 'Kartu Keluarga',    'required' => true],
                        ['name' => 'file_ijazah',      'label' => 'Ijazah',            'required' => false],
                        ['name' => 'file_sertifikat',  'label' => 'Sertifikat',        'required' => false],
                    ];
                @endphp

                @foreach ($dokumens as $dok)
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        {{ $dok['label'] }} @if($dok['required'])<span class="text-red-400">*</span>@endif
                    </label>
                    @if ($pelamar?->{$dok['name']})
                    <div class="flex items-center gap-2 mb-1.5">
                        <a href="{{ asset('storage/' . $pelamar->{$dok['name']}) }}" target="_blank"
                            class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline">
                            <i class="bi bi-file-earmark-text"></i> Lihat File
                        </a>
                        <span class="text-slate-300">|</span>
                        <span class="text-xs text-slate-400">Unggah ulang untuk mengganti</span>
                    </div>
                    @endif
                    <label class="flex items-center gap-2 w-full border border-dashed border-slate-300 rounded-lg px-3 py-2.5 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors">
                        <i class="bi bi-upload text-slate-400 text-sm"></i>
                        <span class="text-xs text-slate-400 truncate" id="label-{{ $dok['name'] }}">
                            Pilih file PDF/JPG/PNG
                        </span>
                        <input type="file" name="{{ $dok['name'] }}" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                            onchange="updateLabel(this, 'label-{{ $dok['name'] }}')">
                    </label>
                    @error($dok['name'])<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                @endforeach

            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="bi bi-save2"></i> Simpan Perubahan
            </button>
        </div>

    </div>
</div>

</form>
@endsection

@push('scripts')
<script>
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('preview-foto').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateLabel(input, labelId) {
        const label = document.getElementById(labelId);
        label.textContent = input.files[0] ? input.files[0].name : 'Pilih file PDF/JPG/PNG';
    }

    @if (session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#2563eb' });
    @endif

    @if (session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}', confirmButtonColor: '#2563eb' });
    @endif
</script>
@endpush