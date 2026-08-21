<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function show()
    {
        $ortuProfile = null;
        if (auth()->user()->role === 'ortu') {
            $ortuProfile = OrangTua::where('user_id', auth()->id())->first();
        }

        return view('pengaturan', compact('ortuProfile'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function updateWhatsapp(Request $request)
    {
        if (auth()->user()->role !== 'ortu') {
            abort(403, 'Fitur ini hanya untuk orang tua.');
        }

        $request->validate([
            'no_wa' => 'nullable|string|max:20',
        ]);

        $ortu = OrangTua::where('user_id', auth()->id())->first();

        if ($ortu) {
            $ortu->update(['no_wa' => $request->no_wa]);
        } else {
            OrangTua::create(['user_id' => auth()->id(), 'no_wa' => $request->no_wa]);
        }

        return back()->with('success', 'Nomor WhatsApp berhasil disimpan!');
    }
}
