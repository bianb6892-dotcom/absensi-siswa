<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NilaiFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function makeGuru(): User
    {
        return User::create([
            'name' => 'Guru PPLG',
            'email' => 'guru@test.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'kelas' => 'X PPLG',
        ]);
    }

    private function makeSiswa(): User
    {
        return User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'kelas' => 'X PPLG',
            'nis' => '2026001',
        ]);
    }

    private function makeOrtu(): array
    {
        $ortuUser = User::create([
            'name' => 'Bapak Budi',
            'email' => 'ortu@test.com',
            'password' => bcrypt('password'),
            'role' => 'ortu',
        ]);
        $ortu = OrangTua::create(['user_id' => $ortuUser->id]);

        return [$ortuUser, $ortu];
    }

    public function test_guru_can_open_nilai_pages(): void
    {
        $guru = $this->makeGuru();
        $this->actingAs($guru)
            ->get('/guru/nilai/setengah-semester?kelas=X%20PPLG&tahun=2026/2027')
            ->assertOk();
        $this->actingAs($guru)
            ->get('/guru/nilai/akhir-semester?kelas=X%20PPLG&tahun=2026/2027')
            ->assertOk();
    }

    public function test_guru_can_store_nilai_manual(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $this->actingAs($guru)
            ->post('/guru/nilai/setengah-semester', [
                'kelas' => 'X PPLG',
                'tahun_ajaran' => '2026/2027',
                'mata_pelajaran' => 'Matematika',
                'nilai' => [$siswa->id => 88.5],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('nilai', [
            'user_id' => $siswa->id,
            'periode' => 'setengah_semester',
            'mata_pelajaran' => 'Matematika',
            'tahun_ajaran' => '2026/2027',
        ]);
    }

    public function test_store_updates_existing_nilai(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        foreach ([75, 95] as $nilai) {
            $this->actingAs($guru)
                ->post('/guru/nilai/akhir-semester', [
                    'kelas' => 'X PPLG',
                    'tahun_ajaran' => '2026/2027',
                    'mata_pelajaran' => 'Bahasa Inggris',
                    'nilai' => [$siswa->id => $nilai],
                ])->assertRedirect();
        }

        $this->assertSame(1, Nilai::where('user_id', $siswa->id)->count());
        $this->assertSame('95.00', Nilai::first()->nilai);
    }

    public function test_invalid_nilai_rejected(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $this->actingAs($guru)
            ->post('/guru/nilai/setengah-semester', [
                'kelas' => 'X PPLG',
                'tahun_ajaran' => '2026/2027',
                'mata_pelajaran' => 'Matematika',
                'nilai' => [$siswa->id => 150],
            ])
            ->assertSessionHasErrors('nilai.'.$siswa->id);
    }

    public function test_guru_can_delete_nilai(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();
        $nilai = Nilai::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'tahun_ajaran' => '2026/2027',
            'periode' => 'setengah_semester',
            'mata_pelajaran' => 'Matematika',
            'nilai' => 80,
        ]);

        $this->actingAs($guru)
            ->delete('/guru/nilai/'.$nilai->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('nilai', ['id' => $nilai->id]);
    }

    public function test_ortu_can_view_nilai_children(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtu();
        $siswa = $this->makeSiswa();
        $siswa->update(['ortu_id' => $ortu->id]);

        Nilai::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'tahun_ajaran' => '2026/2027',
            'periode' => 'setengah_semester',
            'mata_pelajaran' => 'Matematika',
            'nilai' => 90,
        ]);

        $this->actingAs($ortuUser)
            ->get('/ortu/nilai?tahun=2026/2027')
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee('Matematika')
            ->assertSee('90');
    }

    public function test_guru_can_import_nilai_from_csv(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $csv = "nama,mata_pelajaran,nilai\nBudi Santoso,Matematika,85\nBudi Santoso,Bahasa Indonesia,90\n";
        $file = UploadedFile::fake()->createWithContent('nilai.csv', $csv);

        $this->actingAs($guru)
            ->post('/guru/nilai/setengah-semester/import', [
                'file' => $file,
                'kelas' => 'X PPLG',
                'tahun_ajaran' => '2026/2027',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('nilai', ['user_id' => $siswa->id, 'mata_pelajaran' => 'Matematika', 'nilai' => '85.00']);
        $this->assertDatabaseHas('nilai', ['user_id' => $siswa->id, 'mata_pelajaran' => 'Bahasa Indonesia', 'nilai' => '90.00']);
    }

    public function test_guru_can_import_absensi_from_csv(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $csv = "nama,keterangan\nBudi Santoso,hadir\n";
        $file = UploadedFile::fake()->createWithContent('absensi.csv', $csv);

        $this->actingAs($guru)
            ->post('/guru/absensi/import', [
                'file' => $file,
                'kelas' => 'X PPLG',
                'tanggal' => '2026-08-19',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('absensis', [
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'keterangan' => 'hadir',
        ]);
        $this->assertSame(1, Absensi::where('user_id', $siswa->id)
            ->whereDate('tanggal', '2026-08-19')
            ->count());
    }

    public function test_guru_can_download_absensi_template(): void
    {
        $guru = $this->makeGuru();

        $this->actingAs($guru)
            ->get('/guru/absensi/template')
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="template_absensi.csv"');
    }

    public function test_guru_absensi_page_still_works(): void
    {
        $guru = $this->makeGuru();
        $this->makeSiswa();

        $this->actingAs($guru)
            ->get('/guru/absensi/create?kelas=X%20PPLG')
            ->assertOk();

        $this->actingAs($guru)
            ->get('/guru/absensi?kelas=X%20PPLG')
            ->assertOk();
    }

    public function test_ortu_cannot_see_others_child(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtu();
        $otherUser = User::create([
            'name' => 'Orang Lain',
            'email' => 'lain@test.com',
            'password' => bcrypt('password'),
            'role' => 'ortu',
        ]);
        $otherOrtu = OrangTua::create(['user_id' => $otherUser->id]);
        $siswa = $this->makeSiswa();
        $siswa->update(['ortu_id' => $otherOrtu->id]);

        $this->actingAs($ortuUser)
            ->get('/ortu/nilai?tahun=2026/2027')
            ->assertOk()
            ->assertDontSee('Budi Santoso');
    }

    public function test_nilai_requires_login(): void
    {
        $this->get('/guru/nilai/setengah-semester')->assertRedirect('/login');
    }

    public function test_nilai_forbidden_for_non_guru(): void
    {
        $ortu = User::create([
            'name' => 'Orang Tua',
            'email' => 'ortu2@test.com',
            'password' => bcrypt('password'),
            'role' => 'ortu',
        ]);

        $this->actingAs($ortu)
            ->get('/guru/nilai/setengah-semester')
            ->assertForbidden();
    }
}
