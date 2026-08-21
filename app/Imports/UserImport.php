<?php

namespace App\Imports;

use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    protected $kelas;

    public function __construct($kelas)
    {
        $this->kelas = $kelas;
    }

    public function model(array $row)
    {
        $nis = (string) $row['nis'];

        $data = [
            'name' => $row['nama'],
            'email' => strtolower($nis).'@siswa.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'kelas' => $this->kelas,
        ];

        // Email sudah dipakai user lain (NIS beda) — lewati baris ini agar import tidak error
        if (User::where('email', $data['email'])->where('nis', '!=', $nis)->exists()) {
            return null;
        }

        $emailOrtu = trim((string) ($row['email_ortu'] ?? ''));
        if ($emailOrtu !== '') {
            $ortuUser = User::where('role', 'ortu')->where('email', strtolower($emailOrtu))->first();
            $ortu = $ortuUser ? OrangTua::where('user_id', $ortuUser->id)->first() : null;

            if ($ortu) {
                $data['ortu_id'] = $ortu->id;
            }
        }

        User::updateOrCreate(
            ['nis' => $nis],
            $data
        );

        return null;
    }

    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama' => 'required|string',
            'email_ortu' => [
                'nullable',
                'email',
                Rule::exists('users', 'email')->where(fn ($query) => $query->where('role', 'ortu')),
            ],
        ];
    }

    public function prepareForValidation(array $row)
    {
        if (isset($row['nis'])) {
            $row['nis'] = (string) $row['nis'];
        }

        if (isset($row['email_ortu']) && $row['email_ortu'] !== null) {
            $row['email_ortu'] = strtolower(trim((string) $row['email_ortu']));
        }

        return $row;
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'Kolom NIS wajib diisi',
            'nama.required' => 'Kolom Nama wajib diisi',
            'email_ortu.exists' => 'Email orang tua tidak terdaftar',
        ];
    }
}
