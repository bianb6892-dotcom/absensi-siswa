@extends('layouts.app')

@section('title', 'Tunggakan SPP')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl flex items-center gap-3">
            <i class="ph-fill ph-money text-blue-700"></i> Tunggakan SPP
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Catat siswa yang belum membayar SPP. Orang tua akan melihatnya di dashboard mereka.
        </p>
    </div>
    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <i class="ph ph-coins text-lg"></i>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Total tunggakan</p>
            <p class="text-sm font-bold text-slate-900">Rp {{ number_format((float) $totalTunggakan, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<!-- ===================== INPUT TUNGGAKAN ===================== -->
<div class="card overflow-hidden mb-6">
    <form method="POST" action="{{ route('guru.tunggakan.store') }}" class="bg-white px-6 py-5">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelasTerpilih }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="user_id" class="label">Siswa</label>
                <select id="user_id" name="user_id" required class="input cursor-pointer">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" @selected(old('user_id') == $s->id)>{{ $s->name }} ({{ $s->nis ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="bulan" class="label">Bulan Tunggakan</label>
                <input type="month" id="bulan" name="bulan" required value="{{ old('bulan', now()->format('Y-m')) }}" class="input">
            </div>
            <div>
                <label for="jumlah" class="label">Jumlah (Rp)</label>
                <input type="text" inputmode="numeric" id="jumlah" name="jumlah" required placeholder="contoh: 230.000"
                    value="{{ old('jumlah') }}" class="input" autocomplete="off">
            </div>
            <div>
                <label for="keterangan" class="label">Keterangan <span class="font-normal text-slate-400">(opsional)</span></label>
                <input type="text" id="keterangan" name="keterangan" placeholder="contoh: belum bayar sejak pindahan"
                    value="{{ old('keterangan') }}" class="input">
            </div>
        </div>

        <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:justify-end">
            <button type="submit" class="btn btn-primary">
                <i class="ph ph-floppy-disk"></i> Simpan Tunggakan
            </button>
        </div>

        @error('user_id')
            <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </form>
    @if($siswa->isEmpty())
        <div class="border-t border-slate-100 bg-slate-50/60 px-6 py-3">
            <p class="text-xs text-slate-400"><i class="ph ph-info mr-1"></i> Belum ada siswa di kelas ini.</p>
        </div>
    @endif
</div>

<!-- ===================== DAFTAR TUNGGAKAN ===================== -->
<div class="card overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph ph-receipt"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Tunggakan</h2>
                <p class="text-xs text-slate-400">{{ $kelasTerpilih }}</p>
            </div>
        </div>
        <div class="sm:ml-auto">
            <select onchange="window.location.href='{{ url('/tunggakan-spp') }}?kelas='+this.value" class="input cursor-pointer">
                @foreach($daftarKelas as $k)
                    <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="p-6">
        <div class="table-wrapper">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">NIS</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Bulan</th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Jumlah</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Keterangan</th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tunggakanList as $key => $t)
                        <tr class="transition-colors hover:bg-slate-50/70">
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $key + 1 }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-700 text-xs font-bold text-white">
                                        {{ strtoupper(substr($t->user->name ?? '-', 0, 2)) }}
                                    </div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $t->user->name ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $t->user->nis ?? '-' }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold border border-amber-200 bg-amber-50 text-amber-700">
                                    {{ $t->bulan_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-right text-sm font-extrabold text-slate-900">{{ $t->jumlah_rupiah }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $t->keterangan ?? '-' }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="openEdit({{ $t->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 transition-colors hover:bg-blue-50">
                                        <i class="ph ph-pencil-simple"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('guru.tunggakan.destroy', $t->id) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus tunggakan SPP {{ $t->bulan_label }} milik {{ $t->user->name ?? '-' }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 border border-rose-200 transition-colors hover:bg-rose-50">
                                            <i class="ph ph-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <i class="ph ph-check-circle text-5xl block mb-3 text-green-200"></i>
                                Tidak ada tunggakan SPP di kelas ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===================== MODAL EDIT ===================== -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-modal max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="ph ph-pencil-simple"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Edit Tunggakan SPP</h2>
                    <p id="edit_nama" class="text-xs text-slate-400">-</p>
                </div>
            </div>
            <button type="button" onclick="closeEdit()" class="text-slate-400 hover:text-slate-600">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <form id="edit_form" method="POST" action="" class="px-6 py-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="kelas" value="{{ $kelasTerpilih }}">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="space-y-4">
                <div>
                    <label for="edit_bulan" class="label">Bulan Tunggakan</label>
                    <input type="month" id="edit_bulan" name="bulan" required class="input">
                    @error('bulan')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="edit_jumlah" class="label">Jumlah (Rp)</label>
                    <input type="text" inputmode="numeric" id="edit_jumlah" name="jumlah" required
                        placeholder="contoh: 230.000" class="input" autocomplete="off">
                    @error('jumlah')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="edit_keterangan" class="label">Keterangan <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input type="text" id="edit_keterangan" name="keterangan" class="input">
                </div>
            </div>

            <div class="mt-5 flex justify-end gap-3">
                <button type="button" onclick="closeEdit()" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function formatRibuan(el) {
    const digits = el.value.replace(/[^\d]/g, '');
    el.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

async function openEdit(id) {
    try {
        const res = await fetch(`/tunggakan-spp/${id}/edit`);
        if (!res.ok) throw new Error('Gagal memuat data');
        const d = await res.json();

        document.getElementById('edit_nama').textContent = `${d.nama} · NIS ${d.nis}`;
        document.getElementById('edit_user_id').value = d.user_id;
        document.getElementById('edit_bulan').value = d.bulan;
        document.getElementById('edit_jumlah').value = d.jumlah;
        formatRibuan(document.getElementById('edit_jumlah'));
        document.getElementById('edit_keterangan').value = d.keterangan ?? '';
        document.getElementById('edit_form').action = `/tunggakan-spp/${d.id}`;

        const modal = document.getElementById('modal-edit');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } catch (e) {
        alert('Terjadi kesalahan saat membuka form edit.');
    }
}

function closeEdit() {
    const modal = document.getElementById('modal-edit');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function () {
    ['jumlah', 'edit_jumlah'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', function () { formatRibuan(this); });
    });
});
</script>
@endpush
