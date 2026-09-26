<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MenstrualLog;
use App\Models\Screening;
use App\Services\PcosScreeningService;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $service = new PcosScreeningService();

        // 1. Akun Dokter
        $doctor = User::create([
            'name' => 'dr. Sarah Sp.A / Obgyn',
            'email' => 'dokter@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter'
        ]);

        // 2. Pasien Skenario A (Risiko Tinggi - Perlu Tinjauan)
        $pasienA = User::create([
            'name' => 'Alya Rahmawati (17 thn)',
            'email' => 'alya@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'birth_date' => '2009-02-15',
            'menarche_age' => 12 // Gyn age: 5 thn
        ]);

        // Log 3 siklus panjang (oligomenore: 52 hari, 58 hari, 48 hari)
        MenstrualLog::create(['user_id' => $pasienA->id, 'start_date' => '2026-05-01', 'end_date' => '2026-05-06', 'cycle_length_days' => 52, 'flow_intensity' => 'sedang', 'pain_level' => 6]);
        MenstrualLog::create(['user_id' => $pasienA->id, 'start_date' => '2026-06-22', 'end_date' => '2026-06-27', 'cycle_length_days' => 58, 'flow_intensity' => 'banyak', 'pain_level' => 7]);
        MenstrualLog::create(['user_id' => $pasienA->id, 'start_date' => '2026-08-09', 'end_date' => '2026-08-14', 'cycle_length_days' => 48, 'flow_intensity' => 'sedang', 'pain_level' => 5]);

        $evalA = $service->evaluate($pasienA, $pasienA->menstrualLogs, [
            'has_severe_acne' => true,
            'has_hirsutism' => true,
            'has_hair_thinning' => false
        ]);

        Screening::create([
            'user_id' => $pasienA->id,
            'gynecological_age' => $evalA['gyn_age'],
            'has_severe_acne' => true,
            'has_hirsutism' => true,
            'result_status' => $evalA['status'],
            'explanation_points' => $evalA['explanations'],
            'recommendation' => $evalA['recommendation']
        ]);

        // 3. Pasien Skenario B (Pubertas Awal / Normal Fisiologis)
        $pasienB = User::create([
            'name' => 'Nadia Putri (13 thn)',
            'email' => 'nadia@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'birth_date' => '2013-05-10',
            'menarche_age' => 13 // Gyn age: 0 thn (< 1 tahun)
        ]);
        MenstrualLog::create(['user_id' => $pasienB->id, 'start_date' => '2026-06-01', 'end_date' => '2026-06-05', 'cycle_length_days' => 40]);
        MenstrualLog::create(['user_id' => $pasienB->id, 'start_date' => '2026-07-15', 'end_date' => '2026-07-20', 'cycle_length_days' => 45]);
        MenstrualLog::create(['user_id' => $pasienB->id, 'start_date' => '2026-08-30', 'end_date' => '2026-09-04', 'cycle_length_days' => 46]);

        $evalB = $service->evaluate($pasienB, $pasienB->menstrualLogs, ['has_severe_acne' => false, 'has_hirsutism' => false]);
        Screening::create([
            'user_id' => $pasienB->id,
            'gynecological_age' => $evalB['gyn_age'],
            'has_severe_acne' => false,
            'has_hirsutism' => false,
            'result_status' => $evalB['status'],
            'explanation_points' => $evalB['explanations'],
            'recommendation' => $evalB['recommendation']
        ]);
    }
}