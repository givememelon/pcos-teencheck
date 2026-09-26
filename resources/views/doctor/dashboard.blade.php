<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tenaga Medis - PCOS TeenCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link class="rounded-full" rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">
    <nav class="bg-indigo-900 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">PCOS TeenCheck <span class="text-xs bg-indigo-700 text-indigo-200 px-2 py-0.5 rounded ml-2">Portal Klinis Dokter</span></h1>
        <div class="flex items-center space-x-4">
            <span class="text-sm">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-indigo-300 hover:text-white">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Daftar Pasien Skrining Remaja</h2>

        <div class="space-y-6">
            @foreach($screenings as $s)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex justify-between items-start border-b pb-4 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $s->user->name }}</h3>
                        <p class="text-xs text-slate-500">Usia Menarche: {{ $s->user->menarche_age }} thn | Usia Ginekologis: {{ $s->gynecological_age }} thn</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $s->result_status == 'perlu_tinjauan_dokter' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ strtoupper(str_replace('_', ' ', $s->result_status)) }}
                    </span>
                </div>

                <!-- Faktor Penjelas Explainable AI -->
                <div class="bg-slate-50 p-4 rounded-lg border text-sm mb-4">
                    <p class="font-semibold text-slate-700 mb-1">Faktor yang Mendasari Keputusan AI:</p>
                    <ul class="list-disc list-inside text-slate-600 text-xs space-y-1">
                        @foreach($s->explanation_points as $ep)
                            <li>{{ $ep }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Form Evaluasi Dokter -->
                <form action="{{ route('doctor.review', $s->id) }}" method="POST" class="mt-4 pt-4 border-t grid md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Skor mFG (Hirsutisme):</label>
                        <input type="number" name="mfg_score" value="{{ optional($s->clinicalEvaluation)->mfg_score }}" placeholder="Skala 0-36" class="w-full border rounded px-3 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Rencana Tindak Lanjut:</label>
                        <input type="text" name="action_plan" value="{{ optional($s->clinicalEvaluation)->action_plan }}" placeholder="Cth: USG Abdominal / Diet / Edukasi" class="w-full border rounded px-3 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Klinis Dokter:</label>
                        <textarea name="clinical_notes" rows="1" placeholder="Catatan evaluasi..." class="w-full border rounded px-3 py-1.5 text-sm">{{ optional($s->clinicalEvaluation)->clinical_notes }}</textarea>
                    </div>
                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded">
                            Simpan Evaluasi Klinis
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>