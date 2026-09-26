<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pasien - PCOS TeenCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link class="rounded-full" rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">
    <nav class="bg-white border-b px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-purple-700">PCOS TeenCheck <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded ml-2">Pasien</span></h1>
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium">{{ $user->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-red-500 hover:underline">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-6 py-8">
        <!-- Hasil Skrining Explainable AI -->
        @if($latestScreening)
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
            <h2 class="text-lg font-bold mb-4 flex items-center">
                Hasil Skrining Terakhir
                @if($latestScreening->result_status == 'perlu_tinjauan_dokter')
                    <span class="ml-3 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Perlu Tinjauan Tenaga Kesehatan</span>
                @elseif($latestScreening->result_status == 'pemantauan')
                    <span class="ml-3 px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">Perlu Pemantauan Berkala</span>
                @else
                    <span class="ml-3 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">Kondisi Tergolong Normal</span>
                @endif
            </h2>

            <!-- Box Explainable AI -->
            <div class="bg-purple-50/60 border border-purple-100 rounded-lg p-4 mb-4">
                <h3 class="text-sm font-bold text-purple-900 mb-2">Penjelasan Sistem (Explainable AI / Transparansi Aturan):</h3>
                <ul class="list-disc list-inside space-y-1 text-sm text-purple-950">
                    @foreach($latestScreening->explanation_points as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="text-sm text-slate-700">
                <strong>Rekomendasi:</strong> {{ $latestScreening->recommendation }}
            </div>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Form Skrining Cepat -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-base mb-4 text-purple-800">Form Evaluasi Gejala Fisik</h3>
                <form action="{{ route('patient.screening') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_severe_acne" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                            <span class="text-sm">Jerawat berat / kistik menahun yang sulit sembuh</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_hirsutism" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                            <span class="text-sm">Pertumbuhan rambut tebal/kasar (dagu, dada, atau garis perut)</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_hair_thinning" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                            <span class="text-sm">Rambut kepala rontok berlebih / menipis di puncak</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 rounded-lg text-sm transition">
                        Perbarui Hasil Skrining
                    </button>
                </form>
            </div>

            <!-- Riwayat Menstruasi Longitudinal -->
<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-base text-purple-800">Riwayat Menstruasi Longitudinal</h3>
        <button type="button" onclick="document.getElementById('form-tambah-haid').classList.toggle('hidden')" class="text-xs bg-purple-600 hover:bg-purple-700 text-white font-semibold px-3 py-1.5 rounded-lg transition">
            + Tambah Catatan
        </button>
    </div>

    <!-- Alert Sukses Simpan -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs px-3 py-2 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Input (Default Hidden, klik tombol "+ Tambah Catatan" untuk buka) -->
    <form id="form-tambah-haid" action="{{ route('patient.menstrual.store') }}" method="POST" class="hidden bg-purple-50/50 p-4 rounded-lg border border-purple-100 mb-5 space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai:</label>
                <input type="date" name="start_date" required class="w-full border rounded px-2.5 py-1.5 text-xs bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai:</label>
                <input type="date" name="end_date" required class="w-full border rounded px-2.5 py-1.5 text-xs bg-white">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Intensitas Darah:</label>
                <select name="flow_intensity" class="w-full border rounded px-2.5 py-1.5 text-xs bg-white">
                    <option value="ringan">Ringan (Spotting/Bercak)</option>
                    <option value="sedang" selected>Sedang (Biasa)</option>
                    <option value="banyak">Banyak (Ganti pembalut sering)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Skala Nyeri (0-10):</label>
                <input type="number" name="pain_level" min="0" max="10" value="2" class="w-full border rounded px-2.5 py-1.5 text-xs bg-white">
            </div>
        </div>

        <div class="flex justify-end space-x-2 pt-1">
            <button type="button" onclick="document.getElementById('form-tambah-haid').classList.add('hidden')" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-1.5">Batal</button>
            <button type="submit" class="text-xs bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 py-1.5 rounded-lg shadow-sm">Simpan Siklus</button>
        </div>
    </form>

    <!-- List Riwayat -->
<div class="divide-y text-sm">
    @forelse($logs as $log)
    <div class="py-2.5 flex justify-between items-center group">
        <div>
            <div class="font-semibold text-slate-800">
                {{ \Carbon\Carbon::parse($log->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($log->end_date)->translatedFormat('d M Y') }}
            </div>
            <div class="text-xs text-slate-500">
                Intensitas: {{ ucfirst($log->flow_intensity) }} | Skala Nyeri: {{ $log->pain_level }}/10
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <div class="text-right">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $log->cycle_length_days > 45 || $log->cycle_length_days < 21 ? 'bg-red-100 text-red-700' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                    {{ $log->cycle_length_days }} Hari
                </span>
                <span class="block text-[10px] text-slate-400 mt-0.5">jarak siklus</span>
            </div>

            <!-- Tombol Hapus -->
            <form action="{{ route('patient.menstrual.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan menstruasi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus catatan" class="text-slate-400 hover:text-red-600 p-1 rounded transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-xs text-slate-400 text-center py-4">Belum ada catatan menstruasi.</p>
    @endforelse
</div>
</div>
        </div>
    </div>
</body>
</html>