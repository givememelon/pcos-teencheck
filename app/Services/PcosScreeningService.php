<?php

namespace App\Services;

use Carbon\Carbon;

class PcosScreeningService
{
    public function evaluate($patient, $menstrualLogs, array $symptoms): array
    {
        $explanations = [];
        $isOvulatoryDysfunction = false;
        $isHyperandrogenism = false;

        $age = Carbon::parse($patient->birth_date)->age;
        $gynAge = max(0, $age - ($patient->menarche_age ?? 12));

        // 1. Cek kecukupan data (Sesuai Proposal: Jangan paksakan hasil jika data tidak cukup)
        if ($menstrualLogs->count() < 3) {
            return [
                'status' => 'data_kurang',
                'gyn_age' => $gynAge,
                'explanations' => [
                    'Sistem mendeteksi pencatatan siklus menstruasi masih kurang dari 3 siklus.',
                    'Kriteria skrining remaja membutuhkan data longitudinal minimal 3 siklus untuk membedakan variasi normal vs abnormal.'
                ],
                'recommendation' => 'Lanjutkan pencatatan siklus setiap bulan. Jika mengalami nyeri hebat mendadak, segera temui dokter.'
            ];
        }

        // 2. Evaluasi Siklus Menstruasi Berdasarkan Usia Ginekologis
        $abnormalCycles = 0;
        foreach ($menstrualLogs as $log) {
            $len = $log->cycle_length_days;
            if ($gynAge < 1) {
                // Fisiologis pubertas tahun ke-1 masih anovulatori normal
            } elseif ($gynAge >= 1 && $gynAge < 3) {
                if ($len < 21 || $len > 45) $abnormalCycles++;
            } else {
                if ($len < 21 || $len > 35) $abnormalCycles++;
            }
        }

        if ($gynAge < 1) {
            $explanations[] = "Usia ginekologis pasien adalah {$gynAge} tahun (< 1 tahun pasca menarche). Variasi siklus pada periode ini secara medis dikategorikan sebagai adaptasi hormonal pubertas normal.";
        } elseif ($abnormalCycles >= 2) {
            $isOvulatoryDysfunction = true;
            $rangeText = ($gynAge < 3) ? '21–45 hari' : '21–35 hari';
            $explanations[] = "Ditemukan {$abnormalCycles} siklus di luar rentang acuan konsensus remaja ({$rangeText}), mengindikasikan adanya disfungsi ovulasi / oligomenore.";
        } else {
            $explanations[] = "Pola interval menstruasi berada dalam batas rentang acuan untuk usia ginekologis pasien.";
        }

        // 3. Evaluasi Ciri Hiperandrogenisme Klinis
        if (!empty($symptoms['has_hirsutism'])) {
            $isHyperandrogenism = true;
            $explanations[] = "Terdapat tanda hirsutisme (pertumbuhan rambut kasar/terminal berlebih pada area dagu, leher, atau garis tengah perut).";
        }
        if (!empty($symptoms['has_severe_acne'])) {
            $isHyperandrogenism = true;
            $explanations[] = "Terdapat riwayat jerawat kistik/berat yang menetap dan tidak membaik dengan perawatan kulit standar.";
        }
        if (!empty($symptoms['has_hair_thinning'])) {
            $explanations[] = "Terdapat indikasi penipisan rambut pada area puncak kepala (alopecia androgenik).";
        }

        // 4. Klasifikasi Risiko Terstruktur
        if ($isOvulatoryDysfunction && $isHyperandrogenism) {
            $status = 'perlu_tinjauan_dokter';
            $recommendation = 'Ditemukan dua parameter kunci (disfungsi ovulasi + hiperandrogenisme klinis). Sangat dianjurkan untuk berkonsultasi langsung dengan dokter spesialis guna evaluasi klinis lanjutan.';
        } elseif ($isOvulatoryDysfunction || $isHyperandrogenism) {
            $status = 'pemantauan';
            $recommendation = 'Ditemukan satu parameter yang memerlukan pemantauan berkala. Teruskan pencatatan siklus dan perhatikan apakah gejala hiperandrogenisme bertambah.';
        } else {
            $status = 'normal';
            $recommendation = 'Pola siklus dan indikator klinis saat ini tidak mengarah pada kriteria risiko PCOS. Tetap terapkan pola hidup sehat dan catat siklus berkala.';
        }

        return [
            'status' => $status,
            'gyn_age' => $gynAge,
            'explanations' => $explanations,
            'recommendation' => $recommendation
        ];
    }
}