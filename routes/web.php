<?php
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/quick-login/{role}', [WebController::class, 'quickLogin'])->name('quick.login');

Route::middleware('auth')->group(function () {
    Route::get('/patient/dashboard', [WebController::class, 'patientDashboard'])->name('patient.dashboard');
    Route::post('/patient/screening', [WebController::class, 'runScreening'])->name('patient.screening');
    Route::post('/patient/menstrual-log', [WebController::class, 'storeMenstrualLog'])->name('patient.menstrual.store');
    Route::delete('/patient/menstrual-log/{id}', [WebController::class, 'destroyMenstrualLog'])->name('patient.menstrual.destroy');
    Route::get('/doctor/dashboard', [WebController::class, 'doctorDashboard'])->name('doctor.dashboard');
    Route::post('/doctor/review/{id}', [WebController::class, 'saveDoctorReview'])->name('doctor.review');
    Route::post('/logout', function () {
        auth()->logout();
        return redirect()->route('home');
    })->name('logout');
});
