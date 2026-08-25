<?php

namespace Tests\Feature;

use App\Models\OrangTua;
use App\Models\TunggakanSpp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TunggakanSppFeatureTest extends TestCase
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

    private function makeSiswa(string $email = 'budi@test.com', string $kelas = 'X PPLG', string $nis = '2026001'): User
    {
        return User::create([
            'name' => 'Budi Santoso',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'kelas' => $kelas,
            'nis' => $nis,
        ]);
    }

    private function payload(User $siswa, array $overrides = []): array
    {
        return array_merge([
            'kelas' => $siswa->kelas,
            'user_id' => $siswa->id,
            'bulan' => '2026-06',
            'jumlah' => 150000,
            'keterangan' => null,
        ], $overrides);
    }

    public function test_guru_can_open_tunggakan_page(): void
    {
        $guru = $this->makeGuru();

        $this->actingAs($guru)
            ->get('/tunggakan-spp?kelas=X%20PPLG')
            ->assertOk();
    }

    public function test_guru_can_store_tunggakan(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswa))
            ->assertRedirect('/tunggakan-spp?kelas=X%20PPLG');

        $this->assertDatabaseHas('tunggakan_spp', [
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-06-01',
            'jumlah' => '150000.00',
        ]);

        // Orang tua melihat bulan tunggakan terformat
        $this->assertSame('Juni 2026', TunggakanSpp::first()->bulan_label);
        $this->assertSame('Rp 150.000', TunggakanSpp::first()->jumlah_rupiah);
    }

    public function test_store_updates_existing_for_same_student_and_month(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        foreach ([150000, 200000] as $jumlah) {
            $this->actingAs($guru)
                ->post('/tunggakan-spp', $this->payload($siswa, ['jumlah' => $jumlah]))
                ->assertRedirect();
        }

        $this->assertSame(1, TunggakanSpp::where('user_id', $siswa->id)->count());
        $this->assertSame('200000.00', TunggakanSpp::first()->jumlah);
    }

    public function test_guru_can_delete_tunggakan(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();
        $tunggakan = TunggakanSpp::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-06-01',
            'jumlah' => 150000,
        ]);

        $this->actingAs($guru)
            ->delete('/tunggakan-spp/'.$tunggakan->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('tunggakan_spp', ['id' => $tunggakan->id]);
    }

    public function test_tunggakan_rejects_student_from_other_kelas(): void
    {
        $guru = $this->makeGuru();
        $siswaLainKelas = $this->makeSiswa('lain@test.com', 'XI TJKT', '2026002');

        // Guru mengirim kelas X PPLG, tapi user_id milik siswa XI TJKT
        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswaLainKelas, ['kelas' => 'X PPLG']))
            ->assertForbidden();

        $this->assertDatabaseCount('tunggakan_spp', 0);
    }

    public function test_tunggakan_validation_requires_valid_data(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        // Jumlah wajib diisi & > 0
        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswa, ['jumlah' => 0]))
            ->assertSessionHasErrors('jumlah');

        // Bulan harus format Y-m
        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswa, ['bulan' => 'Juni 2026']))
            ->assertSessionHasErrors('bulan');

        // user_id harus siswa yang ada
        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswa, ['user_id' => 99999]))
            ->assertSessionHasErrors('user_id');
    }

    public function test_ortu_sees_child_tunggakan_on_dashboard(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtuPair();
        $siswa = $this->makeSiswa();
        $siswa->update(['ortu_id' => $ortu->id]);

        TunggakanSpp::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-06-01',
            'jumlah' => 150000,
            'keterangan' => 'Belum bayar SPP',
        ]);

        $this->actingAs($ortuUser)
            ->get('/ortu/dashboard')
            ->assertOk()
            ->assertSee('Tunggakan SPP')
            ->assertSee('Juni 2026')
            ->assertSee('Rp 150.000');
    }

    public function test_ortu_does_not_see_other_childs_tunggakan(): void
    {
        [$ortuUser, $ortu] = $this->makeOrtuPair();

        // Ortu ini punya satu anak tanpa tunggakan
        $anakSendiri = $this->makeSiswa('anak-sendiri@test.com');
        $anakSendiri->update(['ortu_id' => $ortu->id]);

        // Tapi tunggakan hanya ada di anak orang lain
        $anakOrangLain = $this->makeSiswa('anak-lain@test.com', 'X PPLG', '2026002');
        TunggakanSpp::create([
            'user_id' => $anakOrangLain->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-06-01',
            'jumlah' => 150000,
        ]);

        $response = $this->actingAs($ortuUser)
            ->get('/ortu/dashboard')
            ->assertOk()
            ->assertSee($anakSendiri->name)
            ->assertDontSee('Rp 150.000');

        $this->assertStringNotContainsString('Tunggakan SPP', $response->getContent());
    }

    public function test_store_accepts_formatted_thousand_separator(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswa, ['jumlah' => '230.000']))
            ->assertRedirect();

        $this->assertDatabaseHas('tunggakan_spp', [
            'user_id' => $siswa->id,
            'jumlah' => '230000.00',
        ]);
    }

    public function test_store_rejects_non_numeric_jumlah(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $this->actingAs($guru)
            ->post('/tunggakan-spp', $this->payload($siswa, ['jumlah' => 'dua ratus ribu']))
            ->assertSessionHasErrors('jumlah');

        $this->assertDatabaseCount('tunggakan_spp', 0);
    }

    public function test_guru_can_open_edit_and_update_tunggakan(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();
        $tunggakan = TunggakanSpp::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-06-01',
            'jumlah' => 230000,
            'keterangan' => 'Belum bayar',
        ]);

        // Endpoint JSON untuk modal edit
        $this->actingAs($guru)
            ->get('/tunggakan-spp/'.$tunggakan->id.'/edit')
            ->assertOk()
            ->assertJsonPath('id', $tunggakan->id)
            ->assertJsonPath('bulan', '2026-06')
            ->assertJsonPath('jumlah', '230000');

        // Update bulan & jumlah (dengan format titik ribuan)
        $this->actingAs($guru)
            ->put('/tunggakan-spp/'.$tunggakan->id, $this->payload($siswa, [
                'bulan' => '2026-07',
                'jumlah' => '250.000',
                'keterangan' => 'Ditunda ke Juli',
            ]))
            ->assertRedirect('/tunggakan-spp?kelas=X%20PPLG');

        $this->assertDatabaseHas('tunggakan_spp', [
            'id' => $tunggakan->id,
            'user_id' => $siswa->id,
            'bulan' => '2026-07-01',
            'jumlah' => '250000.00',
            'keterangan' => 'Ditunda ke Juli',
        ]);

        $this->assertSame(1, TunggakanSpp::where('user_id', $siswa->id)->count());
    }

    public function test_update_prevents_duplicate_month_for_same_student(): void
    {
        $guru = $this->makeGuru();
        $siswa = $this->makeSiswa();

        $juni = TunggakanSpp::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-06-01',
            'jumlah' => 150000,
        ]);

        $juli = TunggakanSpp::create([
            'user_id' => $siswa->id,
            'kelas' => 'X PPLG',
            'bulan' => '2026-07-01',
            'jumlah' => 150000,
        ]);

        // Coba ubah Juli menjadi Juni padahal Juni sudah ada
        $this->actingAs($guru)
            ->put('/tunggakan-spp/'.$juli->id, $this->payload($siswa, ['bulan' => '2026-06']))
            ->assertSessionHasErrors('bulan');

        // Data tidak berubah
        $this->assertDatabaseHas('tunggakan_spp', [
            'id' => $juli->id,
            'bulan' => '2026-07-01',
        ]);
        $this->assertSame(2, TunggakanSpp::where('user_id', $siswa->id)->count());
        $this->assertNotNull(TunggakanSpp::find($juni->id));
    }

    public function test_non_guru_cannot_access_tunggakan_pages(): void
    {
        $admin = $this->makeAdmin();
        $siswa = $this->makeSiswa();

        // Guest diarahkan ke login
        $this->get('/tunggakan-spp')->assertRedirect('/login');

        // Admin pun bukan guru
        $this->actingAs($admin)->get('/tunggakan-spp')->assertForbidden();
        $this->actingAs($admin)
            ->post('/tunggakan-spp', $this->payload($siswa))
            ->assertForbidden();
    }

    private function makeOrtuPair(): array
    {
        $ortuUser = User::create([
            'name' => 'Bapak Ortu',
            'email' => 'ortu-pair@test.com',
            'password' => bcrypt('password'),
            'role' => 'ortu',
        ]);

        $ortu = OrangTua::create(['user_id' => $ortuUser->id]);

        return [$ortuUser, $ortu];
    }
}
