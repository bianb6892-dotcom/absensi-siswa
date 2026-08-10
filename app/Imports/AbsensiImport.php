<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;

class AbsensiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $kelas;
    protected $tanggal;

    public function __construct($kelas, $tanggal)
    {
        $this->kelas = $kelas;
        $this->tanggal = $tanggal;
    }

    public function model(array $row)
    {
        // Cari user berdasarkan nama atau NIS
        $user = User::where('name', $row['nama'])
            ->where('kelas', $this->kelas)
            ->first();

        if (!$user) {
            return null;
        }

        return new Absensi([
            'user_id' => $user->id,
            'kelas' => $this->kelas,
            'tanggal' => $this->tanggal,
            'keterangan' => $row['keterangan'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'keterangan' => 'required|in:hadir,ijin,sakit,tidak_masuk',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Kolom Nama wajib diisi',
            'keterangan.required' => 'Kolom Keterangan wajib diisi',
            'keterangan.in' => 'Keterangan harus: hadir, ijin, sakit, atau tidak_masuk',
        ];
    }
}