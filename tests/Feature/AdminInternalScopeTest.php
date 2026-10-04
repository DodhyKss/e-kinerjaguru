<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Penilai;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Isolasi data antar sekolah untuk role admin_internal.
 *
 * Aturan yang diuji:
 * - daftar guru/asesor/evaluasi hanya milik sekolahnya sendiri
 * - school_id dari request tidak pernah dipercaya
 * - akun admin (pusat/internal) tidak boleh disentuh
 */
class AdminInternalScopeTest extends TestCase
{
    use RefreshDatabase;

    private School $sekolahA;

    private School $sekolahB;

    private User $adminInternal;

    private function seedData(): void
    {
        $this->sekolahA = School::create(['nama' => 'SMA Negeri A', 'status' => 'aktif']);
        $this->sekolahB = School::create(['nama' => 'SMA Negeri B', 'status' => 'aktif']);

        $this->adminInternal = User::create([
            'name' => 'Admin Internal A',
            'email' => 'adminint-a@test.local',
            'password' => 'rahasia12345',
            'role' => 'admin_internal',
            'school_id' => $this->sekolahA->id,
        ]);
    }

    private function guru(School $school, string $nama): Guru
    {
        $user = User::create([
            'name' => $nama,
            'email' => str($nama)->slug().'@test.local',
            'password' => 'rahasia12345',
            'role' => 'guru',
            'school_id' => $school->id,
        ]);

        return Guru::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'nama' => $nama,
            'nip' => 'NIP'.$user->id,
            'jenis_kelamin' => 'L',
        ]);
    }

    public function test_admin_internal_hanya_melihat_guru_sekolahnya(): void
    {
        $this->seedData();
        $guruA = $this->guru($this->sekolahA, 'Zeta Ananda');
        $guruB = $this->guru($this->sekolahB, 'Yuni Brahmana');

        $response = $this->actingAs($this->adminInternal)->get(route('gurus.index'));

        $response->assertOk();
        $response->assertSee($guruA->nama);
        $response->assertDontSee($guruB->nama);
    }

    public function test_admin_internal_tidak_bisa_membuka_edit_guru_sekolah_lain(): void
    {
        $this->seedData();
        $guruB = $this->guru($this->sekolahB, 'Yuni Brahmana');

        $this->actingAs($this->adminInternal)
            ->get(route('gurus.edit', $guruB))
            ->assertForbidden();
    }

    public function test_admin_internal_tidak_bisa_menghapus_guru_sekolah_lain(): void
    {
        $this->seedData();
        $guruB = $this->guru($this->sekolahB, 'Yuni Brahmana');

        $this->actingAs($this->adminInternal)
            ->delete(route('gurus.destroy', $guruB))
            ->assertForbidden();

        $this->assertDatabaseHas('gurus', ['id' => $guruB->id]);
    }

    public function test_school_id_dari_request_diabaikan_saat_menambah_guru(): void
    {
        $this->seedData();
        $mataPelajaran = MataPelajaran::create([
            'kelompok_mapel_id' => null,
            'nama' => 'Matematika',
        ]);

        $payload = [
            'school_id' => $this->sekolahB->id, // mencoba menulis ke sekolah lain
            'nama' => 'Guru Baru',
            'nip' => 'NIP-BARU-1',
            'nuptk' => null,
            'mata_pelajaran_id' => $mataPelajaran->id,
            'kompetensi_keahlian_id' => null,
            'pangkat_golongan_id' => null,
            'jabatan_fungsional_id' => null,
            'jenis_kelamin' => 'L',
            'no_telepon' => null,
            'email' => 'guru-baru@test.local',
        ];

        $this->actingAs($this->adminInternal)
            ->post(route('gurus.store'), $payload)
            ->assertRedirect(route('gurus.index'));

        // school_id harus dipaksa ke sekolah admin internal, bukan sekolah dari request.
        $this->assertDatabaseHas('gurus', [
            'nama' => 'Guru Baru',
            'school_id' => $this->sekolahA->id,
        ]);
    }

    public function test_admin_internal_tidak_bisa_reset_password_akun_admin(): void
    {
        $this->seedData();
        $adminPusat = User::create([
            'name' => 'Admin Pusat',
            'email' => 'pusat@test.local',
            'password' => 'rahasia12345',
            'role' => 'admin',
        ]);

        $this->actingAs($this->adminInternal)
            ->post(route('users.reset-password', $adminPusat), [
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertRedirect();

        $this->assertTrue(
            Hash::check('rahasia12345', $adminPusat->fresh()->password),
            'Password Admin Pusat tidak boleh berubah oleh Admin Internal Sekolah.'
        );
    }

    public function test_admin_internal_tidak_bisa_reset_password_akun_sekolah_lain(): void
    {
        $this->seedData();
        $guruB = $this->guru($this->sekolahB, 'Yuni Brahmana');

        $this->actingAs($this->adminInternal)
            ->post(route('users.reset-password', $guruB->user), [
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertRedirect();

        $this->assertTrue(
            Hash::check('rahasia12345', $guruB->user->fresh()->password),
            'Password akun sekolah lain tidak boleh berubah.'
        );
    }

    public function test_admin_internal_bisa_reset_password_guru_sekolahnya(): void
    {
        $this->seedData();
        $guruA = $this->guru($this->sekolahA, 'Zeta Ananda');

        $this->actingAs($this->adminInternal)
            ->post(route('users.reset-password', $guruA->user), [
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertRedirect();

        $this->assertTrue(
            Hash::check('passwordbaru123', $guruA->user->fresh()->password)
        );
    }

    public function test_admin_internal_tidak_bisa_menugaskan_guru_sekolah_lain_ke_asesor(): void
    {
        $this->seedData();
        $guruB = $this->guru($this->sekolahB, 'Yuni Brahmana');

        $payload = [
            'school_id' => $this->sekolahA->id,
            'pangkat_golongan_id' => null,
            'nama' => 'Asesor Baru',
            'nip' => 'NIP-ASESOR-1',
            'jabatan' => 'Asesor Kompetensi',
            'instansi' => $this->sekolahA->nama,
            'no_telepon' => null,
            'email' => 'asesor-baru@test.local',
            'guru_ids' => [$guruB->id], // guru milik sekolah lain
        ];

        $this->actingAs($this->adminInternal)
            ->post(route('penilais.store'), $payload)
            ->assertSessionHasErrors('guru_ids.0');

        $this->assertDatabaseCount('penilais', 0);
    }

    public function test_admin_internal_tidak_bisa_membuka_edit_asesor_sekolah_lain(): void
    {
        $this->seedData();
        $userB = User::create([
            'name' => 'Asesor B',
            'email' => 'asesor-b@test.local',
            'password' => 'rahasia12345',
            'role' => 'penilai',
            'school_id' => $this->sekolahB->id,
        ]);
        $penilaiB = Penilai::create([
            'user_id' => $userB->id,
            'school_id' => $this->sekolahB->id,
            'nama' => 'Asesor B',
            'jabatan' => 'Asesor Kompetensi',
            'instansi' => $this->sekolahB->nama,
        ]);

        $this->actingAs($this->adminInternal)
            ->get(route('penilais.edit', $penilaiB))
            ->assertForbidden();
    }

    public function test_admin_internal_tidak_bisa_membuat_akun_admin_internal(): void
    {
        $this->seedData();

        $this->actingAs($this->adminInternal)
            ->post(route('users.store'), [
                'name' => 'Admin Internal Ngawur',
                'email' => 'ngawur@test.local',
                'school_id' => $this->sekolahB->id,
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'ngawur@test.local']);
    }

    public function test_admin_internal_melihat_hanya_akun_sekolahnya(): void
    {
        $this->seedData();
        $this->guru($this->sekolahA, 'Zeta Ananda');
        $this->guru($this->sekolahB, 'Yuni Brahmana');

        $response = $this->actingAs($this->adminInternal)->get(route('users.index'));

        $response->assertOk();
        $response->assertSee('Zeta Ananda');
        $response->assertDontSee('Yuni Brahmana');
    }

    public function test_admin_pusat_tetap_bisa_membuat_akun_admin_internal(): void
    {
        $this->seedData();
        $adminPusat = User::create([
            'name' => 'Admin Pusat',
            'email' => 'pusat@test.local',
            'password' => 'rahasia12345',
            'role' => 'admin',
        ]);

        $this->actingAs($adminPusat)
            ->post(route('users.store'), [
                'name' => 'Admin Internal A',
                'email' => 'adminint-a2@test.local',
                'school_id' => $this->sekolahA->id,
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'adminint-a2@test.local',
            'role' => 'admin_internal',
            'school_id' => $this->sekolahA->id,
        ]);
    }

    /**
     * Regresi: seluruh halaman form yang memakai query sekolah harus bisa
     * dibuka oleh admin internal tanpa SQL error.
     */
    public function test_admin_internal_dapat_membuka_semua_halaman_form(): void
    {
        $this->seedData();
        $guruA = $this->guru($this->sekolahA, 'Zeta Ananda');
        $penilaiA = $this->penilai($this->sekolahA, 'Asesor Internal');

        $urls = [
            route('gurus.create'),
            route('gurus.edit', $guruA),
            route('gurus.createFromPenilai', ['penilai_id' => $penilaiA->id]),
            route('penilais.create'),
            route('penilais.edit', $penilaiA),
            route('penilais.createFromGuru', ['guru_id' => $guruA->id]),
            route('users.index'),
            route('gurus.index'),
            route('penilais.index'),
            route('evaluations.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($this->adminInternal)
                ->get($url)
                ->assertOk();
        }
    }

    /**
     * Halaman form admin internal hanya boleh menawarkan sekolahnya sendiri.
     */
    public function test_form_tidak_menawarkan_sekolah_lain(): void
    {
        $this->seedData();

        $response = $this->actingAs($this->adminInternal)->get(route('penilais.create'));
        $response->assertOk();
        $response->assertSee($this->sekolahA->nama);
        $response->assertDontSee($this->sekolahB->nama);

        $responseGuru = $this->actingAs($this->adminInternal)->get(route('gurus.create'));
        $responseGuru->assertOk();
        $responseGuru->assertSee($this->sekolahA->nama);
        $responseGuru->assertDontSee($this->sekolahB->nama);
    }

    private function penilai(School $school, string $nama): Penilai
    {
        $user = User::create([
            'name' => $nama,
            'email' => str($nama)->slug().'@test.local',
            'password' => 'rahasia12345',
            'role' => 'penilai',
            'school_id' => $school->id,
        ]);

        return Penilai::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'nama' => $nama,
            'jabatan' => 'Asesor Kompetensi',
            'instansi' => $school->nama,
        ]);
    }
}
