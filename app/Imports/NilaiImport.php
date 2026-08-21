<?php

namespace App\Imports;

use App\Models\Nilai;
use App\Models\User;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NilaiImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    protected $kelas;

    protected $tahunAjaran;

    protected $periode;

    public function __construct($kelas, $tahunAjaran, $periode)
    {
        $this->kelas = $kelas;
        $this->tahunAjaran = $tahunAjaran;
        $this->periode = $periode;
    }

    public function model(array $row)
    {
        $user = User::where('name', $row['nama'])
            ->where('kelas', $this->kelas)
            ->where('role', 'siswa')
            ->first();

        if (! $user) {
            return null;
        }

        Nilai::updateOrCreate(
            [
                'user_id' => $user->id,
                'kelas' => $this->kelas,
                'tahun_ajaran' => $this->tahunAjaran,
                'periode' => $this->periode,
                'mata_pelajaran' => $row['mata_pelajaran'],
            ],
            ['nilai' => $row['nilai']]
        );

        return null;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'mata_pelajaran' => 'required|string',
            'nilai' => 'required|numeric|between:0,100',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Kolom Nama wajib diisi',
            'mata_pelajaran.required' => 'Kolom Mata Pelajaran wajib diisi',
            'nilai.required' => 'Kolom Nilai wajib diisi',
            'nilai.numeric' => 'Nilai harus berupa angka',
            'nilai.between' => 'Nilai harus antara 0 - 100',
        ];
    }
}
