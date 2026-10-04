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
use Tests\TestCase;

/**
 * Cakupan laporan untuk role non-Admin-Pusat.
 *
 * Aturan yang diuji:
 * - Data yang tampil hanya milik sekolah/penugasan aktornya.
 * - Dropdown periode hanya memuat periode sekolahnya.
 * - Parameter wilayah (provinsi/kabupaten) diabaikan untuk role selain Admin Pusat,
 *   sehingga tidak bisa dipakai membaca data sekolah lain.
 */
class ReportScopeTest extends TestCase
{
    use RefreshDatabase;

    private School $sekolahA;

    private School $sekolahB;

    private EvaluationPeriod $periodeA;

    private EvaluationPeriod $periodeB;

    private User $guruA;

    private User $guruB;

    private Penilai $asesorA;

    private User $kepsekA;

    private User $adminInternalA;

    private function seedData(): void
    {
        $provinsi = Provinsi::create(['nama' => 'Jawa Barat']);
        $kabA = Kabupaten::create(['provinsi_id' => $provinsi->id, 'nama' => 'Kabupaten Alfa']);
        $kabB = Kabupaten::create(['provinsi_id' => $provinsi->id, 'nama' => 'Kabupaten Beta']);
        $kabLain = Kabupaten::create(['provinsi_id' => Provinsi::create(['nama' => 'Jawa Timur'])->id, 'nama' => 'Kabupaten Gamma']);

        $this->sekolahA = School::create(['nama' => 'SMA Negeri Alfa', 'kabupaten_id' => $kabA->id, 'status' => 'aktif']);
        $this->sekolahB = School::create(['nama' => 'SMA Negeri Beta', 'kabupaten_id' => $kabB->id, 'status' => 'aktif']);
        School::create(['nama' => 'SMA Negeri Gamma', 'kabupaten_id' => $kabLain->id, 'status' => 'aktif']);

        $this->periodeA = $this->periode('Periode Alfa', $this->sekolahA);
        $this->periodeB = $this->periode('Periode Beta', $this->sekolahB);

        $this->guruA = $this->guru($this->sekolahA, 'Ananda Alfa');
        $this->guruB = $this->guru($this->sekolahB, 'Brahmana Beta');

        $this->asesorA = $this->asesor($this->sekolahA);
        $asesorB = $this->asesor($this->sekolahB);

        Evaluation::create([
            'evaluation_period_id' => $this->periodeA->id,
            'guru_id' => $this->guruA->id,
            'penilai_id' => $this->asesorA->id,
            'status' => 'in_progress',
        ]);
        Evaluation::create([
            'evaluation_period_id' => $this->periodeB->id,
            'guru_id' => $this->guruB->id,
            'penilai_id' => $asesorB->id,
            'status' => 'in_progress',
        ]);

        $this->kepsekA = User::create([
            'name' => 'Kepsek Alfa', 'email' => 'kepsek.a@t.local',
            'password' => 'rahasia12345', 'role' => 'kepala_sekolah', 'school_id' => $this->sekolahA->id,
        ]);
        $this->adminInternalA = User::create([
            'name' => 'Admin Internal Alfa', 'email' => 'adminint.a@t.local',
            'password' => 'rahasia12345', 'role' => 'admin_internal', 'school_id' => $this->sekolahA->id,
        ]);
    }

    private function periode(string $nama, School $school): EvaluationPeriod
    {
        return EvaluationPeriod::create([
            'school_id' => $school->id, 'nama' => $nama, 'tahun_ajaran' => '2025/2026',
            'semester' => 'ganjil', 'tanggal_mulai' => '2025-07-01', 'tanggal_selesai' => '2025-12-31',
            'status' => 'aktif',
        ]);
    }

    private function guru(School $school, string $nama): User
    {
        $user = User::create([
            'name' => $nama, 'email' => str($nama)->slug().'@t.local',
            'password' => 'rahasia12345', 'role' => 'guru', 'school_id' => $school->id,
        ]);

        Guru::create([
            'user_id' => $user->id, 'school_id' => $school->id, 'nama' => $nama,
            'nip' => 'NIP'.str($nama)->slug(), 'jenis_kelamin' => 'L',
        ]);

        return $user;
    }

    private function asesor(School $school): Penilai
    {
        $user = User::create([
            'name' => 'Asesor '.$school->nama, 'email' => 'asesor.'.$school->id.'@t.local',
            'password' => 'rahasia12345', 'role' => 'penilai', 'school_id' => $school->id,
        ]);

        return Penilai::create([
            'user_id' => $user->id, 'school_id' => $school->id, 'nama' => 'Asesor '.$school->nama,
            'jabatan' => 'Asesor Kompetensi', 'instansi' => $school->nama,
        ]);
    }

    public function test_admin_internal_tidak_melihat_evaluasi_sekolah_lain(): void
    {
        $this->seedData();

        $this->actingAs($this->adminInternalA)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Ananda Alfa')
            ->assertDontSee('Brahmana Beta');
    }

    public function test_kepsek_tidak_melihat_evaluasi_sekolah_lain(): void
    {
        $this->seedData();

        $this->actingAs($this->kepsekA)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Ananda Alfa')
            ->assertDontSee('Brahmana Beta');
    }

    public function test_guru_hanya_melihat_evaluasinya_sendiri(): void
    {
        $this->seedData();

        $this->actingAs($this->guruA)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Ananda Alfa')
            ->assertDontSee('Brahmana Beta');
    }

    public function test_asesor_hanya_melihat_penugasannya_sendiri(): void
    {
        $this->seedData();

        $this->actingAs($this->asesorA->user)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertDontSee('Brahmana Beta');
    }

    public function test_parameter_wilayah_diabaikan_untuk_bukan_admin_pusat(): void
    {
        $this->seedData();
        $kabB = Kabupaten::where('nama', 'Kabupaten Beta')->first();

        // Admin internal sneakily asks for another school's kabupaten.
        $this->actingAs($this->adminInternalA)
            ->get(route('reports.index', ['kabupaten_id' => $kabB->id]))
            ->assertOk()
            ->assertDontSee('Brahmana Beta');

        $this->actingAs($this->kepsekA)
            ->get(route('reports.index', ['kabupaten_id' => $kabB->id]))
            ->assertOk()
            ->assertDontSee('Brahmana Beta');
    }

    public function test_dropdown_periode_hanya_milik_sekolahnya(): void
    {
        $this->seedData();

        $this->actingAs($this->adminInternalA)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Periode Alfa')
            ->assertDontSee('Periode Beta');

        $this->actingAs($this->kepsekA)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Periode Alfa')
            ->assertDontSee('Periode Beta');
    }

    public function test_admin_pusat_tetap_melihat_semua(): void
    {
        $this->seedData();
        $admin = User::create([
            'name' => 'Admin Pusat', 'email' => 'pusat@t.local',
            'password' => 'rahasia12345', 'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Ananda Alfa')
            ->assertSee('Brahmana Beta')
            ->assertSee('Periode Alfa')
            ->assertSee('Periode Beta');
    }

    public function test_halaman_laporan_lain_tetap_terbuka_untuk_kepsek_dan_admin_internal(): void
    {
        $this->seedData();

        foreach ([$this->kepsekA, $this->adminInternalA] as $user) {
            $this->actingAs($user)->get(route('reports.ranking'))->assertOk();
            $this->actingAs($user)->get(route('reports.recap'))->assertOk();
            $this->actingAs($user)->get(route('reports.grafik'))->assertOk();
        }
    }
}
