<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Hapus constructor middleware, pindahkan ke route

    // Tampilkan halaman dashboard sekolah (public)
    public function index()
    {
        // Data Jurusan dengan foto
        $jurusan = Gallery::where('type', 'jurusan')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Data Eskul dengan foto
        $eskul = Gallery::where('type', 'eskul')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Data Gallery Kegiatan
        $galleries = Gallery::where('type', 'kegiatan')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $informasi = [
            'visi' => 'Membentuk generasi unggul, kreatif, dan berakhlak mulia melalui pendidikan yang berkualitas dan inovatif.',
            'misi' => [
                'Menyediakan pendidikan berkualitas yang berorientasi pada kebutuhan industri',
                'Mengembangkan potensi siswa secara holistik (akademik, karakter, dan keterampilan)',
                'Membangun kemitraan dengan dunia usaha dan industri',
                'Menciptakan lingkungan belajar yang inklusif dan menyenangkan'
            ],
            'fasilitas' => [
                'Laboratorium Komputer Modern',
                'Perpustakaan Digital',
                'Ruang Praktik Standar Industri',
                'Area Olahraga Terpadu',
                'Ruang Multimedia',
                'Wi-Fi Area'
            ],
            'prestasi' => [
                'Juara 1 Lomba Web Design Tingkat Nasional',
                'Sekolah Adiwiyata Mandiri',
                'Mitra 50+ Perusahaan Multinasional',
                'Juara Umum OSN 2025'
            ]
        ];

        return view('school.dashboard', compact('galleries', 'jurusan', 'eskul', 'informasi'));
    }

    // Admin: Kelola Gallery
    public function manage()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        return view('admin.gallery-manage', compact('galleries'));
    }

    // Admin: Store Gallery
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'category' => 'nullable|string|max:100',
            'type' => 'required|in:kegiatan,jurusan,eskul',
        ]);

        $imagePath = $request->file('image')->store('gallery', 'public');

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'category' => $request->category,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return redirect()->route('gallery.manage')
            ->with('success', 'Foto berhasil ditambahkan!');
    }

    // Admin: Update Gallery
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'type' => 'required|in:kegiatan,jurusan,eskul',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type,
        ];

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('gallery.manage')
            ->with('success', 'Foto berhasil diperbarui!');
    }

    // Admin: Toggle Active Status
    public function toggle($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->update([
            'is_active' => !$gallery->is_active
        ]);

        return redirect()->route('gallery.manage')
            ->with('success', 'Status foto berhasil diubah!');
    }

    // Admin: Delete Gallery
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }
        
        $gallery->delete();

        return redirect()->route('gallery.manage')
            ->with('success', 'Foto berhasil dihapus!');
    }

    // Admin: Get Gallery Data for Edit Modal
    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery);
    }
}