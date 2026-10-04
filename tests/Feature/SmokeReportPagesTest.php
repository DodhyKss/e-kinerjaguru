<?php

namespace Tests\Feature;

use App\Models\Dimension;
use App\Models\EvaluationPeriod;
use App\Models\Indicator;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SmokeReportPagesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('halaman')]
    public function test_halaman_tidak_error(string $url): void
    {
        $provinsi = Provinsi::create(['nama' => 'Jawa Barat']);
        $kabupaten = Kabupaten::create(['provinsi_id' => $provinsi->id, 'nama' => 'Kota Vokasi']);
        $school = School::create(['nama' => 'SMK 1', 'kabupaten_id' => $kabupaten->id, 'status' => 'aktif']);

        // Periode aktif wajib ada: blok perhitungan level indikator pada
        // GraphicalReportController hanya jalan bila ada periode aktif.
        EvaluationPeriod::create([
            'school_id' => $school->id,
            'nama' => 'Periode Aktif',
            'tahun_ajaran' => '2025/2026',
            'semester' => 'ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status' => 'aktif',
        ]);

        // Minimal satu indikator: query level capaian hanya dijalankan bila
        // ada indikator yang perlu di-loop.
        $dimension = Dimension::create(['kode' => 'I', 'nama' => 'Sikap', 'urutan' => 1]);
        Indicator::create([
            'dimension_id' => $dimension->id,
            'kode' => '1.1',
            'nama' => 'Indikator Smoke',
            'deskripsi' => 'Deskripsi.',
            'urutan' => 1,
            'urutan_keseluruhan' => 1,
        ]);

        $admin = User::create([
            'name' => 'Admin Pusat', 'email' => 'a@test.local',
            'password' => 'rahasia12345', 'role' => 'admin',
        ]);

        // tanpa filter
        $this->actingAs($admin)->get($url)->assertOk();
        // dengan filter wilayah + status
        $this->actingAs($admin)->get($url.'?'.http_build_query([
            'provinsi_id' => $provinsi->id,
            'kabupaten_id' => $kabupaten->id,
        ]))->assertOk();
    }

    public static function halaman(): array
    {
        return [
            'laporan' => ['/reports'],
            'grafik' => ['/reports/grafik'],
            'ranking' => ['/reports/ranking'],
            'rekapitulasi' => ['/reports/rekapitulasi'],
            'monitoring' => ['/monitoring'],
        ];
    }
}
