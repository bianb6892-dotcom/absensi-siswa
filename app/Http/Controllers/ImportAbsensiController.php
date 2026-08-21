<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportAbsensiController extends Controller
{
    public function index()
    {
        return view('admin.import-absensi');
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
                    $errorMessages[] = "Row {$failure->row()}: ".implode(', ', $failure->errors());
                }

                return redirect()->back()
                    ->with('warning', 'Data berhasil diimport sebagian. Ada beberapa data yang gagal:')
                    ->withErrors($errorMessages);
            }

            return redirect()->route('import.absensi')
                ->with('success', 'Data absensi berhasil diimport!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_absensi.csv"',
        ];

        $columns = ['nama', 'keterangan'];
        $callback = function () use ($columns) {
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
