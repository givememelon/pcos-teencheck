<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MenstrualLog;
use App\Models\Screening;
use App\Models\ClinicalEvaluation;
use App\Services\PcosScreeningService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WebController extends Controller
{
    public function index() {
        return view('welcome');
    }

    public function quickLogin($role) {
        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
            return redirect()->route($role == 'dokter' ? 'doctor.dashboard' : 'patient.dashboard');
        }
        return back();
    }

    public function patientDashboard() {
        $user = Auth::user();
        $logs = $user->menstrualLogs()->orderBy('start_date', 'desc')->get();
        $latestScreening = $user->screenings()->latest()->first();
        return view('patient.dashboard', compact('user', 'logs', 'latestScreening'));
    }

    public function runScreening(Request $request, PcosScreeningService $service) {
        $user = Auth::user();
        $logs = $user->menstrualLogs;
        
        $symptoms = [
            'has_severe_acne' => $request->has('has_severe_acne'),
            'has_hirsutism' => $request->has('has_hirsutism'),
            'has_hair_thinning' => $request->has('has_hair_thinning')
        ];

        $result = $service->evaluate($user, $logs, $symptoms);

        $screening = Screening::create([
            'user_id' => $user->id,
            'gynecological_age' => $result['gyn_age'] ?? 0,
            'has_severe_acne' => $symptoms['has_severe_acne'],
            'has_hirsutism' => $symptoms['has_hirsutism'],
            'has_hair_thinning' => $symptoms['has_hair_thinning'],
            'result_status' => $result['status'],
            'explanation_points' => $result['explanations'],
            'recommendation' => $result['recommendation']
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Skrining berhasil diperbarui.');
    }

    public function doctorDashboard() {
        $screenings = Screening::with(['user', 'clinicalEvaluation'])->latest()->get();
        return view('doctor.dashboard', compact('screenings'));
    }

    public function saveDoctorReview(Request $request, $screeningId) {
        ClinicalEvaluation::updateOrCreate(
            ['screening_id' => $screeningId],
            [
                'doctor_id' => Auth::id(),
                'mfg_score' => $request->mfg_score,
                'clinical_notes' => $request->clinical_notes,
                'action_plan' => $request->action_plan
            ]
        );
        return back()->with('success', 'Review klinis berhasil disimpan.');
    }

    public function storeMenstrualLog(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'flow_intensity' => 'required|in:ringan,sedang,banyak',
        'pain_level' => 'required|integer|min:0|max:10',
    ]);

    $user = Auth::user();

    // 1. Hitung panjang siklus otomatis dari log haid terakhir sebelumnya
    $lastLog = $user->menstrualLogs()
        ->where('start_date', '<', $request->start_date)
        ->orderBy('start_date', 'desc')
        ->first();

    $cycleLength = 28; // Nilai acuan default jika ini siklus pertama yang dicatat
    if ($lastLog) {
        $cycleLength = Carbon::parse($lastLog->start_date)->diffInDays(Carbon::parse($request->start_date));
    }

    // 2. Simpan catatan baru
    $user->menstrualLogs()->create([
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'cycle_length_days' => $cycleLength,
        'flow_intensity' => $request->flow_intensity,
        'pain_level' => $request->pain_level,
    ]);

    return redirect()->route('patient.dashboard')->with('success', 'Catatan menstruasi berhasil ditambahkan!');
}

public function destroyMenstrualLog($id)
{
    $user = Auth::user();
    
    // Cari data log milik pasien yang sedang login agar tidak bisa menghapus milik orang lain
    $log = $user->menstrualLogs()->where('id', $id)->firstOrFail();
    $log->delete();

    return redirect()->route('patient.dashboard')->with('success', 'Catatan menstruasi berhasil dihapus.');
}

}