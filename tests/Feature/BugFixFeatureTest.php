<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Notifikasi;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BugFixFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

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

    public function test_guest_cannot_access_register_page(): void
    {
        $this->get('/admin/register')->assertRedirect('/login');
        $this->post('/admin/register', [])->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_register_page(): void
    {
        $this->actingAs($this->makeGuru())->get('/admin/register')->assertForbidden();
        $this->actingAs($this->makeOrtu()[0])->post('/admin/register', [])->assertForbidden();
    }

    public function test_admin_register_as_ortu_creates_orang_tua_row(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post('/admin/register', [
                'name' => 'Ibu Ani',
                'email' => 'ibu@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'ortu',
            ])
            ->assertRedirect('/admin/users');

        $user = User::where('email', 'ibu@test.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('orang_tua', ['user_id' => $user->id]);
    }

    public function test_admin_register_as_guru_without_kelas_succeeds(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post('/admin/register', [
                'name' => 'Guru Baru',
                'email' => 'gurubaru@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'guru',
            ])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'gurubaru@test.com',
            'role' => 'guru',
        ]);
    }

    public function test_admin_register_as_siswa_requires_kelas(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/admin/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswabaru@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'siswa',
        ]);

        $response->assertSessionHasErrors('kelas');

        $this->actingAs($admin)->post('/admin/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswabaru@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'siswa',
            'kelas' => 'X PPLG',
        ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'siswabaru@test.com',
            'role' => 'siswa',
            'kelas' => 'X PPLG',
        ]);
    }

    public function test_register_cannot_create_admin_account(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post('/admin/register', [
                'name' => 'Hacker',
                'email' => 'hacker@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'admin',
            ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'hacker@test.com']);
    }

    public function test_register_page_does_not_offer_admin_role(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/register')
            ->assertOk()
            ->assertDontSee('value="admin"', false);
    }

    public function test_admin_can_link_new_siswa_to_real_orang_tua_row(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtu();

        $this->actingAs($this->makeAdmin())
            ->post('/admin/register', [
                'name' => 'Siswa Baru',
                'email' => 'siswabaru@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'siswa',
                'kelas' => 'X PPLG',
                'ortu_id' => $ortu->id,
            ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'siswabaru@test.com',
            'ortu_id' => $ortu->id,
        ]);
    }

    public function test_admin_can_change_role_to_valid_roles(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();

        $this->actingAs($admin)
            ->put("/admin/users/{$siswa->id}/role", ['role' => 'guru'])
            ->assertRedirect('/admin/users');

        $this->actingAs($admin)
            ->put("/admin/users/{$siswa->id}/role", ['role' => 'ortu'])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('orang_tua', ['user_id' => $siswa->id]);
    }

    public function test_admin_can_open_all_absensi_pages(): void
    {
        $admin = $this->makeAdmin();
        $this->makeSiswa();

        $this->actingAs($admin)->get('/admin/absensi')->assertOk();
        $this->actingAs($admin)->get('/admin/absensi/create')->assertOk();
        $this->actingAs($admin)->get('/admin/absensi/template')->assertOk();
        $this->actingAs($admin)->get('/admin/import-absensi')->assertOk();
        $this->actingAs($admin)->get('/admin/import-absensi/template')->assertOk();
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    }

    public function test_absensi_cepat_is_for_guru_only(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();

        $this->actingAs($admin)->get('/absensi-cepat')->assertForbidden();
        $this->actingAs($this->makeGuru())->get('/absensi-cepat')->assertOk();
        $this->actingAs($siswa)->get('/absensi-cepat')->assertForbidden();
    }

    public function test_siswa_can_open_guru_dashboard_and_absensi_pages(): void
    {
        $siswa = $this->makeSiswa();

        $this->actingAs($siswa)->get('/guru/dashboard')->assertOk();
        $this->actingAs($siswa)->get('/guru/absensi')->assertOk();
    }

    public function test_admin_can_import_students_via_excel(): void
    {
        $admin = $this->makeAdmin();

        $csv = "nis,nama\n"
            ."2026001,Budi Santoso\n"
            ."2026002,Ani Rahayu\n";

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $this->actingAs($admin)
            ->post('/admin/users/import', ['file' => $file, 'kelas' => 'X PPLG'])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => '2026001@siswa.com',
            'name' => 'Budi Santoso',
            'role' => 'siswa',
            'kelas' => 'X PPLG',
            'nis' => '2026001',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => '2026002@siswa.com',
            'name' => 'Ani Rahayu',
            'role' => 'siswa',
            'kelas' => 'X PPLG',
            'nis' => '2026002',
        ]);
    }

    public function test_user_import_skips_invalid_rows(): void
    {
        $admin = $this->makeAdmin();

        $csv = "nis,nama\n"
            ."2026001,Budi Santoso\n"
            .",Siswa Tanpa NIS\n";

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $this->actingAs($admin)
            ->post('/admin/users/import', ['file' => $file, 'kelas' => 'X PPLG'])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', ['nis' => '2026001']);
        $this->assertDatabaseMissing('users', ['name' => 'Siswa Tanpa NIS']);
    }

    public function test_user_import_requires_kelas(): void
    {
        $admin = $this->makeAdmin();

        $csv = "nis,nama\n2026001,Budi Santoso\n";
        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $this->actingAs($admin)
            ->post('/admin/users/import', ['file' => $file])
            ->assertSessionHasErrors('kelas');
    }

    public function test_admin_can_download_user_template(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get('/admin/users/template')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
    }

    public function test_admin_can_fill_nis_for_existing_student(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa(); // dibuat tanpa NIS
        $this->assertNull($siswa->fresh()->nis);

        $this->actingAs($admin)
            ->put('/admin/users/'.$siswa->id, [
                'name' => $siswa->name,
                'email' => $siswa->email,
                'role' => 'siswa',
                'ortu_id' => '',
                'nis' => '2026099',
            ])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'id' => $siswa->id,
            'nis' => '2026099',
        ]);
    }

    public function test_admin_can_keep_existing_nis_when_editing_other_fields(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();
        $siswa->update(['nis' => '2026001']);

        $this->actingAs($admin)
            ->put('/admin/users/'.$siswa->id, [
                'name' => 'Nama Baru',
                'email' => $siswa->email,
                'role' => 'siswa',
                'ortu_id' => '',
                'nis' => '2026001', // NIS sama miliknya sendiri harus tetap lolos
            ])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'id' => $siswa->id,
            'name' => 'Nama Baru',
            'nis' => '2026001',
        ]);
    }

    public function test_admin_cannot_use_duplicate_nis(): void
    {
        $admin = $this->makeAdmin();
        $siswaA = $this->makeSiswa();
        $siswaB = User::create([
            'name' => 'Siswa Kedua',
            'email' => 'kedua@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'kelas' => 'X PPLG',
        ]);
        $siswaA->update(['nis' => '2026001']);

        $this->actingAs($admin)
            ->put('/admin/users/'.$siswaB->id, [
                'name' => $siswaB->name,
                'email' => $siswaB->email,
                'role' => 'siswa',
                'ortu_id' => '',
                'nis' => '2026001', // sudah dipakai siswa lain
            ])
            ->assertSessionHasErrors('nis');

        $this->assertNull($siswaB->fresh()->nis);
    }

    public function test_admin_can_clear_nis_of_student(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();
        $siswa->update(['nis' => '2026001']);

        $this->actingAs($admin)
            ->put('/admin/users/'.$siswa->id, [
                'name' => $siswa->name,
                'email' => $siswa->email,
                'role' => 'siswa',
                'ortu_id' => '',
                'nis' => '', // dikosongkan
            ])
            ->assertRedirect('/admin/users');

        $this->assertNull($siswa->fresh()->nis);
    }

    public function test_admin_register_siswa_with_optional_nis(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post('/admin/register', [
                'name' => 'Siswa Dengan NIS',
                'email' => 'dengan-nis@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'siswa',
                'kelas' => 'X PPLG',
                'nis' => '2026777',
            ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'dengan-nis@test.com',
            'nis' => '2026777',
        ]);
    }

    public function test_admin_can_link_student_to_parent_via_edit(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();
        [$ortuUser, $ortu] = $this->makeOrtu();

        $this->actingAs($admin)
            ->put('/admin/users/'.$siswa->id, [
                'name' => $siswa->name,
                'email' => $siswa->email,
                'role' => 'siswa',
                'ortu_id' => $ortu->id,
            ])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'id' => $siswa->id,
            'ortu_id' => $ortu->id,
        ]);

        $this->actingAs($ortuUser)
            ->get('/ortu/dashboard')
            ->assertOk()
            ->assertSee($siswa->name);
    }

    public function test_admin_can_unlink_student_from_parent_via_edit(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();
        [, $ortu] = $this->makeOrtu();
        $siswa->update(['ortu_id' => $ortu->id]);

        $this->actingAs($admin)
            ->put('/admin/users/'.$siswa->id, [
                'name' => $siswa->name,
                'email' => $siswa->email,
                'role' => 'siswa',
                'ortu_id' => '',
            ])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'id' => $siswa->id,
            'ortu_id' => null,
        ]);
    }

    public function test_user_import_links_student_to_parent_via_email_ortu(): void
    {
        $admin = $this->makeAdmin();
        [, $ortu] = $this->makeOrtu();

        $csv = "nis,nama,email_ortu\n"
            ."2026001,Budi Santoso,ortu@test.com\n"
            ."2026002,Ani Rahayu,\n";

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $this->actingAs($admin)
            ->post('/admin/users/import', ['file' => $file, 'kelas' => 'X PPLG'])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'nis' => '2026001',
            'ortu_id' => $ortu->id,
        ]);
        $this->assertDatabaseHas('users', [
            'nis' => '2026002',
            'ortu_id' => null,
        ]);
    }

    public function test_user_import_skips_row_with_invalid_email_ortu(): void
    {
        $admin = $this->makeAdmin();

        $csv = "nis,nama,email_ortu\n2026001,Budi Santoso,ortu@tidak-ada.com\n";

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $this->actingAs($admin)
            ->post('/admin/users/import', ['file' => $file, 'kelas' => 'X PPLG'])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseMissing('users', ['nis' => '2026001']);
    }

    public function test_ortu_cannot_use_absensi_cepat(): void
    {
        [$ortuUser] = $this->makeOrtu();

        $this->actingAs($ortuUser)
            ->post('/absensi-cepat', [
                'kelas' => 'X PPLG',
                'tanggal' => now()->format('Y-m-d'),
                'absensi' => [],
            ])
            ->assertForbidden();

        $this->actingAs($ortuUser)->get('/absensi-cepat')->assertForbidden();
    }

    public function test_ortu_cannot_modify_gallery(): void
    {
        [$ortuUser] = $this->makeOrtu();
        $gallery = Gallery::create([
            'title' => 'Foto Kegiatan',
            'type' => 'kegiatan',
            'image' => 'foto.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($ortuUser)
            ->post('/gallery', [
                'title' => 'Hack',
                'type' => 'kegiatan',
                'image' => UploadedFile::fake()->image('hack.jpg'),
            ])
            ->assertForbidden();

        $this->actingAs($ortuUser)
            ->delete("/gallery/{$gallery->id}")
            ->assertForbidden();
    }

    public function test_gallery_manage_uses_correct_edit_url(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get('/gallery/manage')
            ->assertOk()
            ->assertSee('fetch(`/gallery/${id}/edit`)')
            ->assertSee('`/gallery/${id}`');
    }

    public function test_ortu_dashboard_shows_notifications(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtu();
        $siswa = User::create([
            'name' => 'Anak Budi',
            'email' => 'anak@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'kelas' => 'X PPLG',
            'ortu_id' => $ortu->id,
        ]);

        Notifikasi::create([
            'ortu_id' => $ortu->id,
            'siswa_id' => $siswa->id,
            'judul' => 'Siswa Tidak Masuk',
            'pesan' => 'Anak Anda tidak hadir hari ini.',
            'status' => 'pending',
            'jenis' => 'kehadiran',
            'dikirim_at' => now(),
        ]);

        $this->actingAs($ortuUser)
            ->get('/ortu/dashboard')
            ->assertOk()
            ->assertSee('Siswa Tidak Masuk')
            ->assertSee('Anak Anda tidak hadir hari ini.');
    }

    public function test_register_page_lists_parents_from_orang_tua_table(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtu();

        $this->actingAs($this->makeAdmin())
            ->get('/admin/register')
            ->assertOk()
            ->assertSee($ortuUser->name, false)
            ->assertSee('value="'.$ortu->id.'"', false);
    }

    public function test_siswa_cannot_access_guru_input_pages(): void
    {
        $siswa = $this->makeSiswa();

        $this->actingAs($siswa)->get('/guru/absensi/create')->assertForbidden();
        $this->actingAs($siswa)->get('/guru/absensi/template')->assertForbidden();
        $this->actingAs($siswa)->get('/guru/nilai/setengah-semester')->assertForbidden();
        $this->actingAs($siswa)->get('/guru/nilai/akhir-semester')->assertForbidden();
        $this->actingAs($siswa)->get('/gallery/manage')->assertForbidden();

        $this->actingAs($siswa)->post('/guru/absensi', [
            'tanggal' => now()->format('Y-m-d'),
            'kelas' => 'X PPLG',
            'absensi' => [],
        ])->assertForbidden();
    }

    public function test_guru_can_access_input_pages(): void
    {
        $guru = $this->makeGuru();
        $this->makeSiswa();

        $this->actingAs($guru)->get('/guru/absensi/create')->assertOk();
        $this->actingAs($guru)->get('/guru/absensi/template')->assertOk();
        $this->actingAs($guru)->get('/guru/nilai/setengah-semester')->assertOk();
        $this->actingAs($guru)->get('/guru/nilai/akhir-semester')->assertOk();
        $this->actingAs($guru)->get('/gallery/manage')->assertOk();
    }

    public function test_absensi_import_updates_instead_of_duplicating(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();

        $csv = "nama,keterangan\n{$siswa->name},hadir\n";
        $file = UploadedFile::fake()->createWithContent('absensi.csv', $csv);

        $payload = ['file' => $file, 'kelas' => 'X PPLG', 'tanggal' => now()->format('Y-m-d')];

        $this->actingAs($admin)->post('/admin/absensi/import', $payload)->assertRedirect()->assertSessionMissing('error');
        $this->actingAs($admin)->post('/admin/absensi/import', $payload)->assertRedirect()->assertSessionMissing('error');

        $this->assertDatabaseCount('absensis', 1);
    }

    public function test_absensi_cepat_notifies_parent_only_when_status_changes(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtu();
        $siswa = $this->makeSiswa();
        $siswa->update(['ortu_id' => $ortu->id]);
        $guru = $this->makeGuru();

        $payload = [
            'kelas' => 'X PPLG',
            'tanggal' => now()->format('Y-m-d'),
            'absensi' => [$siswa->id => 'hadir'],
        ];

        $this->actingAs($guru)->post('/absensi-cepat', $payload)->assertRedirect();
        $this->assertDatabaseCount('notifikasi', 1);

        // Simpan status yang sama → tidak boleh ada notifikasi duplikat
        $this->actingAs($guru)->post('/absensi-cepat', $payload)->assertRedirect();
        $this->assertDatabaseCount('notifikasi', 1);

        // Ganti status → notifikasi baru
        $payload['absensi'][$siswa->id] = 'sakit';
        $this->actingAs($guru)->post('/absensi-cepat', $payload)->assertRedirect();
        $this->assertDatabaseCount('notifikasi', 2);
    }

    public function test_absensi_cepat_rejects_student_from_other_kelas(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();
        $other = User::create([
            'name' => 'Siswa Lain',
            'email' => 'lain@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'kelas' => 'XI TJKT',
        ]);

        $this->actingAs($guru)->post('/absensi-cepat', [
            'kelas' => 'X PPLG',
            'tanggal' => now()->format('Y-m-d'),
            'absensi' => [$siswa->id => 'hadir', $other->id => 'hadir'],
        ])->assertForbidden();
    }

    public function test_leaving_ortu_role_removes_orang_tua_row(): void
    {
        $admin = $this->makeAdmin();
        [$ortuUser, $ortu] = $this->makeOrtu();

        $this->actingAs($admin)
            ->put('/admin/users/'.$ortuUser->id.'/role', ['role' => 'guru'])
            ->assertRedirect();

        $this->assertDatabaseMissing('orang_tua', ['user_id' => $ortuUser->id]);
    }

    public function test_absensi_show_rejects_unknown_kelas(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get('/admin/absensi/import')->assertNotFound();
        $this->actingAs($admin)->get('/admin/absensi/KELAS-HACK')->assertNotFound();
        $this->actingAs($admin)->get('/admin/absensi/X%20PPLG')->assertOk();
    }
}
