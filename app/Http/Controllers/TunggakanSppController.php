<?php

namespace App\Http\Controllers;

use App\Models\TunggakanSpp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TunggakanSppController extends Controller
{
    private $daftarKelas = [
        'X PPLG', 'X TJKT', 'X ACP', 'X AKL',
        'XI PPLG', 'XI TJKT', 'XI ACP', 'XI AKL',
        'XII PPLG', 'XII TJKT', 'XII ACP', 'XII AKL',
    ];

    public function index(Request $request)
    {
        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        session(['kelas_aktif' => $kelasTerpilih]);

        $daftarKelas = $this->daftarKelas;

        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();

        $tunggakanList = TunggakanSpp::with('user')
            ->where('kelas', $kelasTerpilih)
            ->orderByDesc('bulan')
            ->get()
            ->sortBy(fn ($t) => $t->user->name)
            ->values();

        $totalTunggakan = $tunggakanList->sum('jumlah');

        return view('guru.tunggakan-index', compact(
            'kelasTerpilih',
            'daftarKelas',
            'siswa',
            'tunggakanList',
            'totalTunggakan'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Pastikan siswa valid dan benar-benar berada di kelas terpilih
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $data['kelas'])
            ->where('id', $data['user_id'])
            ->first();

        if (! $siswa) {
            abort(403, 'Siswa tidak valid untuk kelas ini.');
        }

        $bulan = Carbon::createFromFormat('Y-m', $data['bulan'])->startOfMonth();

        TunggakanSpp::updateOrCreate(
            [
                'user_id' => $siswa->id,
                'bulan' => $bulan->format('Y-m-d'),
            ],
            [
                'kelas' => $data['kelas'],
                'jumlah' => $data['jumlah'],
                'keterangan' => $data['keterangan'] ?? null,
            ]
        );

        session(['kelas_aktif' => $data['kelas']]);

        return redirect()->route('guru.tunggakan.index', ['kelas' => $data['kelas']])
            ->with('success', 'Tunggakan SPP '.$bulan->translatedFormat('F Y').' atas nama '.$siswa->name.' berhasil disimpan!');
    }

    public function edit($id)
    {
        $tunggakan = TunggakanSpp::with('user')->findOrFail($id);

        return response()->json([
            'id' => $tunggakan->id,
            'user_id' => $tunggakan->user_id,
            'nama' => $tunggakan->user->name ?? '-',
            'nis' => $tunggakan->user->nis ?? '-',
            'kelas' => $tunggakan->kelas,
            'bulan' => $tunggakan->bulan->format('Y-m'),
            'jumlah' => (string) (int) $tunggakan->jumlah,
            'keterangan' => $tunggakan->keterangan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tunggakan = TunggakanSpp::findOrFail($id);
        $data = $this->validateData($request);

        if ((int) $data['user_id'] !== (int) $tunggakan->user_id) {
            abort(403, 'Data siswa tidak sesuai.');
        }

        $bulan = Carbon::createFromFormat('Y-m', $data['bulan'])->startOfMonth();

        // Cegah duplikat: siswa yang sama sudah punya tunggakan di bulan lain record
        $konflik = TunggakanSpp::where('user_id', $tunggakan->user_id)
            ->where('bulan', $bulan->format('Y-m-d'))
            ->where('id', '!=', $tunggakan->id)
            ->exists();

        if ($konflik) {
            return back()
                ->withErrors(['bulan' => 'Siswa ini sudah memiliki tunggakan pada bulan tersebut.'])
                ->withInput();
        }

        $tunggakan->update([
            'bulan' => $bulan->format('Y-m-d'),
            'jumlah' => $data['jumlah'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        return redirect()->route('guru.tunggakan.index', ['kelas' => $tunggakan->kelas])
            ->with('success', 'Tunggakan SPP '.$bulan->translatedFormat('F Y').' atas nama '.$tunggakan->user->name.' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tunggakan = TunggakanSpp::findOrFail($id);
        $tunggakan->delete();

        return redirect()->back()->with('success', 'Data tunggakan SPP berhasil dihapus!');
    }

    private function validateData(Request $request): array
    {
        // Terima jumlah dalam format apa pun: "230.000", "230000", "230.000,50"
        $parsed = $this->parseJumlah($request->input('jumlah'));
        if ($parsed !== null) {
            $request->merge(['jumlah' => $parsed]);
        }

        return $request->validate([
            'kelas' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'bulan' => 'required|date_format:Y-m',
            'jumlah' => 'required|numeric|gt:0|max:99999999999',
            'keterangan' => 'nullable|string|max:255',
        ]);
    }

    private function parseJumlah(mixed $raw): ?float
    {
        if ($raw === null) {
            return null;
        }

        $nilai = trim((string) $raw);
        if ($nilai === '') {
            return null;
        }

        $nilai = str_replace(['.', ' '], '', $nilai); // hapus titik ribuan & spasi
        $nilai = str_replace(',', '.', $nilai);       // koma sebagai desimal

        return is_numeric($nilai) ? (float) $nilai : null;
    }
}
