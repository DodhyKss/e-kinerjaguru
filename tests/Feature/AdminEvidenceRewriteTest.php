<?php

namespace Tests\Feature;

use App\Models\AssessmentAspect;
use App\Models\Dimension;
use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationResult;
use App\Models\Guru;
use App\Models\Indicator;
use App\Models\Penilai;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Admin Pusat menyunting ulang pembuktian kinerja yang kosong,
 * termasuk pada evaluasi yang sudah selesai, dengan jejak audit.
 */
class AdminEvidenceRewriteTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $penilaiUser;

    private Evaluation $evaluation;

    private Indicator $indicator;

    private function seedData(string $status = 'completed'): void
    {
        $school = School::create(['nama' => 'SMK Negeri 1', 'kabupaten_id' => null, 'status' => 'aktif']);
        $periode = EvaluationPeriod::create([
            'school_id' => $school->id,
            'nama' => 'Periode 1',
            'tahun_ajaran' => '2025/2026',
            'semester' => 'ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status' => 'aktif',
        ]);

        $guruUser = User::create([
            'name' => 'Guru Uji',
            'email' => 'guru-uji@test.local',
            'password' => 'rahasia12345',
            'role' => 'guru',
            'school_id' => $school->id,
        ]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'school_id' => $school->id,
            'nama' => 'Guru Uji',
            'nip' => 'NIP-UJI',
            'jenis_kelamin' => 'L',
        ]);

        $this->penilaiUser = User::create([
            'name' => 'Asesor Uji',
            'email' => 'asesor-uji@test.local',
            'password' => 'rahasia12345',
            'role' => 'penilai',
            'school_id' => $school->id,
        ]);
        $penilai = Penilai::create([
            'user_id' => $this->penilaiUser->id,
            'school_id' => $school->id,
            'nama' => 'Asesor Uji',
            'jabatan' => 'Asesor Kompetensi',
            'instansi' => $school->nama,
        ]);

        $this->admin = User::create([
            'name' => 'Admin Pusat',
            'email' => 'pusat-uji@test.local',
            'password' => 'rahasia12345',
            'role' => 'admin',
        ]);

        $dimension = Dimension::create(['kode' => 'I', 'nama' => 'Sikap', 'urutan' => 1]);
        $this->indicator = Indicator::create([
            'dimension_id' => $dimension->id,
            'kode' => '1.1',
            'nama' => 'Indikator Uji',
            'deskripsi' => 'Deskripsi indikator uji.',
            'urutan' => 1,
            'urutan_keseluruhan' => 1,
        ]);
        Indicator::create([
            'dimension_id' => $dimension->id,
            'kode' => '1.2',
            'nama' => 'Indikator Kedua',
            'deskripsi' => 'Deskripsi indikator kedua.',
            'urutan' => 2,
            'urutan_keseluruhan' => 2,
        ]);
        foreach ([1, 2, 3, 4] as $level) {
            $this->indicator->achievementLevels()->create(['level' => $level, 'deskripsi' => 'Level '.$level]);
        }
        AssessmentAspect::create([
            'indicator_id' => $this->indicator->id,
            'metode' => 'observasi',
            'nomor' => 1,
            'aspek' => 'Aspek observasi uji',
            'target_responden' => ['guru'],
        ]);

        $this->evaluation = Evaluation::create([
            'evaluation_period_id' => $periode->id,
            'guru_id' => $guru->id,
            'penilai_id' => $penilai->id,
            'status' => $status,
        ]);
    }

    private function payload(): array
    {
        return [
            'level_capaian' => 4,
            'kesimpulan' => 'Pembuktian diisi oleh Admin Pusat karena kosong.',
            'observation_note' => 'Catatan observasi dari pusat.',
            'document_review_note' => null,
            'interview_note' => null,
            'observation' => [],
            'document_review' => [],
            'interview' => [],
        ];
    }

    public function test_admin_pusat_dapat_membuka_form_pembuktian_pada_evaluasi_selesai(): void
    {
        $this->seedData('completed');

        $this->actingAs($this->admin)
            ->get(route('evaluations.indicator', [$this->evaluation, $this->indicator]))
            ->assertOk()
            ->assertSee('menyunting pembuktian sebagai Admin Pusat');
    }

    public function test_admin_pusat_dapat_menyimpan_pembuktian_dan_jejak_audit_tercatat(): void
    {
        $this->seedData('completed');

        $this->actingAs($this->admin)
            ->post(route('evaluations.indicator.save', [$this->evaluation, $this->indicator]), $this->payload())
            ->assertRedirect(route('evaluations.show', $this->evaluation));

        $result = EvaluationResult::where('evaluation_id', $this->evaluation->id)
            ->where('indicator_id', $this->indicator->id)
            ->firstOrFail();

        $this->assertSame(4, (int) $result->level_capaian);
        $this->assertSame('selesai', $result->status);
        $this->assertTrue($result->wasEditedByAdmin());
        $this->assertSame($this->admin->id, (int) $result->updated_by_user_id);
        $this->assertSame('Admin Pusat', $result->updated_by_name);
        $this->assertNotNull($result->edited_at);
    }

    public function test_skor_evaluasi_dihitung_ulang_setelah_admin_menyunting(): void
    {
        $this->seedData('completed');

        $this->actingAs($this->admin)
            ->post(route('evaluations.indicator.save', [$this->evaluation, $this->indicator]), $this->payload());

        $this->evaluation->refresh();

        $this->assertSame(4, (int) $this->evaluation->total_skor);
        $this->assertSame(4.0, (float) $this->evaluation->rata_rata);
    }

    public function test_penilai_yang_menilai_tidak_menulis_jejak_audit_admin(): void
    {
        $this->seedData('in_progress');

        $this->actingAs($this->penilaiUser)
            ->post(route('evaluations.indicator.save', [$this->evaluation, $this->indicator]), $this->payload())
            ->assertRedirect();

        $result = EvaluationResult::where('evaluation_id', $this->evaluation->id)
            ->where('indicator_id', $this->indicator->id)
            ->firstOrFail();

        $this->assertSame('selesai', $result->status);
        $this->assertFalse($result->wasEditedByAdmin());
        $this->assertNull($result->updated_by_name);
    }

    public function test_admin_pusat_tidak_bisa_menjalankan_submit_evaluasi(): void
    {
        $this->seedData('in_progress');

        $this->actingAs($this->admin)
            ->post(route('evaluations.submit', $this->evaluation))
            ->assertForbidden();
    }

    public function test_filter_pembuktian_kosong_mencari_evaluasi_yang_belum_diisi(): void
    {
        $this->seedData('completed');

        // Semua indikator masih kosong karena belum ada yang menilai.
        $response = $this->actingAs($this->admin)
            ->get(route('evaluations.index', ['pembuktian_kosong' => 1]));

        $response->assertOk();
        $response->assertSee('Guru Uji');
    }

    public function test_admin_pusat_melihat_banner_audit_di_halaman_detail_indikator(): void
    {
        $this->seedData('completed');

        $this->actingAs($this->admin)
            ->post(route('evaluations.indicator.save', [$this->evaluation, $this->indicator]), $this->payload());

        $this->actingAs($this->admin)
            ->get(route('evaluations.indicator.show', [$this->evaluation, $this->indicator]))
            ->assertOk()
            ->assertSee('diperbaiki oleh Admin Pusat');
    }
}
