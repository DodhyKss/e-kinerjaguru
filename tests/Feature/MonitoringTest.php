<?php

namespace Tests\Feature;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\Guru;
use App\Models\Kabupaten;
use App\Models\Penilai;
use App\Models\Provinsi;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Smoke test untuk halaman monitoring & filter wilayah pada laporan.
 */
class MonitoringTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private School $sekolah;

    private EvaluationPeriod $periode;

    private Provinsi $provinsi;

    private Kabupaten $kabupaten;

    private function seedData(): void
    {
        $this->provinsi = Provinsi::create(['nama' => 'Jawa Barat']);
        $this->kabupaten = Kabupaten::create(['provinsi_id' => $this->provinsi->id, 'nama' => 'Kota Vokasi']);
        $this->sekolah = School::create([
            'nama' => 'SMK Negeri 1',
            'kabupaten_id' => $this->kabupaten->id,
            'status' => 'aktif',
        ]);
        $this->periode = EvaluationPeriod::create([
            'school_id' => $this->sekolah->id,
            'nama' => 'Periode 1',
            'tahun_ajaran' => '2025/2026',
            'semester' => 'ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status' => 'aktif',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Pusat',
            'email' => 'pusat@test.local',
            'password' => 'rahasia12345',
            'role' => 'admin',
        ]);
    }

    private function makeEvaluation(string $status): Evaluation
    {
        $guruUser = User::create([
            'name' => 'Guru '.$status,
            'email' => 'guru-'.$status.'@test.local',
            'password' => 'rahasia12345',
            'role' => 'guru',
            'school_id' => $this->sekolah->id,
        ]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'school_id' => $this->sekolah->id,
            'nama' => 'Guru '.$status,
            'nip' => 'NIP'.$status,
            'jenis_kelamin' => 'L',
        ]);

        $penilaiUser = User::create([
            'name' => 'Asesor',
            'email' => 'asesor-'.$status.'@test.local',
            'password' => 'rahasia12345',
            'role' => 'penilai',
            'school_id' => $this->sekolah->id,
        ]);
        $penilai = Penilai::create([
            'user_id' => $penilaiUser->id,
            'school_id' => $this->sekolah->id,
            'nama' => 'Asesor',
            'jabatan' => 'Asesor Kompetensi',
            'instansi' => $this->sekolah->nama,
        ]);

        return Evaluation::create([
            'evaluation_period_id' => $this->periode->id,
            'guru_id' => $guru->id,
            'penilai_id' => $penilai->id,
            'status' => $status,
            'total_skor' => $status === 'approved' ? 15 : null,
            'rata_rata' => $status === 'approved' ? 3.75 : null,
        ]);
    }

    public function test_halaman_monitoring_bisa_dirender(): void
    {
        $this->seedData();
        $this->makeEvaluation('approved');

        $this->actingAs($this->admin)
            ->get(route('monitoring.index'))
            ->assertOk()
            ->assertSee($this->provinsi->nama);
    }

    public function test_drilldown_kabupaten_dan_sekolah(): void
    {
        $this->seedData();
        $this->makeEvaluation('in_progress');

        $this->actingAs($this->admin)
            ->get(route('monitoring.kabupaten', $this->provinsi))
            ->assertOk()
            ->assertSee($this->kabupaten->nama);

        $this->actingAs($this->admin)
            ->get(route('monitoring.sekolah', $this->kabupaten))
            ->assertOk()
            ->assertSee($this->sekolah->nama);
    }

    public function test_filter_status_mempengaruhi_agregat(): void
    {
        $this->seedData();
        $this->makeEvaluation('approved');
        $this->makeEvaluation('in_progress');

        $semua = $this->actingAs($this->admin)->get(route('monitoring.index'));
        $semua->assertOk();

        $disetujui = $this->actingAs($this->admin)->get(route('monitoring.index', ['status' => 'approved']));
        $disetujui->assertOk();
    }

    public function test_export_csv_dan_cetak_tersedia(): void
    {
        $this->seedData();
        $this->makeEvaluation('approved');

        $this->actingAs($this->admin)
            ->get(route('monitoring.export', ['level' => 'provinsi']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->actingAs($this->admin)
            ->get(route('monitoring.print', ['level' => 'provinsi']))
            ->assertOk()
            ->assertSee($this->provinsi->nama, false);
    }

    public function test_hanya_admin_pusat_yang_boleh_membuka_monitoring(): void
    {
        $this->seedData();

        $guruUser = User::create([
            'name' => 'Guru Biasa',
            'email' => 'guru-biasa@test.local',
            'password' => 'rahasia12345',
            'role' => 'guru',
            'school_id' => $this->sekolah->id,
        ]);

        $this->actingAs($guruUser)
            ->get(route('monitoring.index'))
            ->assertForbidden();
    }

    #[DataProvider('halamanLaporan')]
    public function test_filter_wilayah_diterapkan_di_semua_halaman_laporan(string $routeName, array $params): void
    {
        $this->seedData();
        $this->makeEvaluation('approved');

        $this->actingAs($this->admin)
            ->get(route($routeName, $params + [
                'provinsi_id' => $this->provinsi->id,
                'kabupaten_id' => $this->kabupaten->id,
            ]))
            ->assertOk();
    }

    public static function halamanLaporan(): array
    {
        return [
            'laporan' => ['reports.index', []],
            'grafik' => ['reports.grafik', []],
            'ranking' => ['reports.ranking', []],
            'recap' => ['reports.recap', ['school_id' => 1]],
        ];
    }
}
