<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ImportAbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $kelasList = [
            'X PPLG' => 'X PPLG',
            'X TJKT' => 'X TJKT',
            'X ACP' => 'X ACP',
            'X AKL' => 'X AKL',
            'XI PPLG' => 'XI PPLG',
            'XI TJKT' => 'XI TJKT',
            'XI ACP' => 'XI ACP',
            'XI AKL' => 'XI AKL',
            'XII PPLG' => 'XII PPLG',
            'XII TJKT' => 'XII TJKT',
            'XII ACP' => 'XII ACP',
            'XII AKL' => 'XII AKL',
        ];

        return view('admin.import-absensi', compact('kelasList'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        try {
            $import = new AbsensiImport($request->kelas, $request->tanggal);
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();
            
            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                }
                return redirect()->back()
                    ->with('warning', 'Data berhasil diimport sebagian. Ada beberapa data yang gagal:')
                    ->with('errors', $errorMessages);
            }

            return redirect()->route('import.absensi')
                ->with('success', 'Data absensi berhasil diimport!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_absensi.csv"',
        ];

        $columns = ['nama', 'keterangan'];
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Contoh data
            fputcsv($file, ['Budi Santoso', 'hadir']);
            fputcsv($file, ['Ani Rahayu', 'ijin']);
            fputcsv($file, ['Citra Dewi', 'sakit']);
            fputcsv($file, ['Dedi Firmansyah', 'tidak_masuk']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}