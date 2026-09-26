<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCOS TeenCheck - Skrining Awal Remaja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50 min-h-screen text-slate-800">
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="text-center mb-10">
            <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">MEDSPIN 2026</span>
            <h1 class="text-4xl font-extrabold text-purple-900 mt-4">PCOS TEENCHECK</h1>
            <p class="text-slate-600 mt-2 text-lg">Platform Skrining Awal PCOS Remaja Berbasis Explainable AI Penunjang Keputusan Klinis</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-purple-100">
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-8 rounded">
                <p class="text-sm text-amber-800">
                    <strong>Pemberitahuan Medis:</strong> Sistem ini merupakan alat bantu skrining awal dan penunjang keputusan klinis, bukan merupakan instrumen penegak diagnosis final.
                </p>
            </div>

            <h2 class="text-xl font-bold text-slate-800 mb-4 text-center">Pilih Akun Demo untuk Pengujian:</h2>
            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <a href="{{ route('quick.login', 'pasien') }}" class="group block p-6 border-2 border-pink-200 rounded-xl hover:border-pink-500 hover:shadow-lg transition bg-pink-50/30">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-xl">P</div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900 group-hover:text-pink-600">Masuk sebagai Pasien Remaja</h3>
                            <p class="text-xs text-slate-500 mt-1">Uji fitur pencatatan siklus, log gejala, dan hasil Explainable AI.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('quick.login', 'dokter') }}" class="group block p-6 border-2 border-indigo-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition bg-indigo-50/30">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xl">D</div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900 group-hover:text-indigo-600">Masuk sebagai Tenaga Medis / Dokter</h3>
                            <p class="text-xs text-slate-500 mt-1">Tinjau daftar pasien, faktor risiko transparan, & input evaluasi mFG.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>