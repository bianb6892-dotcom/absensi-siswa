<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Download Aplikasi | Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1D4ED8">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] } } } }
    </script>
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-blue-700 text-white">
            <div class="mx-auto max-w-5xl px-4 py-3 sm:py-4 flex items-center justify-between">
                <a href="/login" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white text-blue-700 flex items-center justify-center"><i class="ph-fill ph-student text-xl"></i></div>
                    <div><p class="font-extrabold leading-none">Absensi Siswa</p><p class="text-xs text-blue-200">SMK BPPI Baleendah</p></div>
                </a>
                <a href="/login" class="hidden sm:inline-flex items-center gap-2 rounded-lg bg-white/15 px-4 py-2 text-sm font-semibold hover:bg-white/25"><i class="ph ph-sign-in"></i> Login</a>
            </div>
        </header>

        <main class="flex-1 mx-auto max-w-5xl w-full px-4 py-6 sm:py-8">
            <!-- Hero -->
            <div class="grid lg:grid-cols-2 gap-6 sm:gap-8 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-bold text-blue-700"><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Aplikasi Resmi • Gratis</div>
                    <h1 class="mt-4 text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">Download Aplikasi<br><span class="text-blue-700">Absensi Siswa</span></h1>
                    <p class="mt-3 text-slate-500 leading-relaxed">Tidak perlu ke Play Store. Cukup 1 klik, langsung jadi aplikasi di HP kamu seperti WhatsApp. Data <b>otomatis sinkron</b> dengan website.</p>

                    <!-- Sinkron box -->
                    <div class="mt-6 rounded-2xl bg-green-50 border border-green-200 p-4 flex gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center shrink-0"><i class="ph-fill ph-arrows-clockwise"></i></div>
                        <div>
                            <p class="text-sm font-bold text-green-800">Data Pasti Sama & Sinkron Otomatis?</p>
                            <p class="text-sm text-green-700 mt-1">YA, 100% sama. Aplikasi ini <b>bukan aplikasi terpisah</b>. Isinya website kamu yang dibungkus jadi aplikasi. Jadi kalau guru input <b>1 tunggakan SPP di website</b>, langsung muncul di aplikasi orang tua tanpa perlu update. Begitu juga sebaliknya. Semua pakai database yang sama.</p>
                        </div>
                    </div>

                    <!-- Tombol Download -->
                    <div class="mt-6 space-y-3">
                        <button id="btn-install" onclick="installPWA()" class="w-full rounded-xl bg-blue-700 text-white px-6 py-4 font-bold text-base shadow-lg shadow-blue-700/20 flex items-center justify-center gap-2 hover:bg-blue-800">
                            <i class="ph-fill ph-download-simple text-xl"></i> Download & Install Aplikasi
                        </button>
                        <p id="install-hint" class="text-xs text-center text-slate-400">Tombol akan aktif otomatis jika HP kamu mendukung. Jika tidak muncul, ikuti panduan di bawah.</p>
                        <div id="http-hint" class="hidden rounded-xl bg-amber-50 border border-amber-300 p-3 text-xs text-amber-900">
                            <p class="font-bold flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Kamu buka pakai http (belum gembok)</p>
                            <p class="mt-1">Tombol biru tidak bisa otomatis di <b>http://192.168...</b> — ini aturan Google. Tapi tetap bisa jadi aplikasi:</p>
                            <p class="mt-2 font-bold">Cara manual 5 detik:</p>
                            <p>Chrome HP → <b>titik 3 kanan atas</b> → <b>Tambahkan ke Layar Utama / Add to Home screen</b> → <b>Tambahkan</b></p>
                            <p class="mt-1 text-[11px] text-amber-700">Hasilnya sama persis, icon muncul seperti WA.</p>
                        </div>
                        <div id="ios-hint" class="hidden rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-800">iPhone: tap tombol <b>Share</b> (kotak + panah) → <b>Add to Home Screen</b> → <b>Add</b></div>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                        <div class="rounded-xl bg-white border border-slate-200 p-3"><p class="text-lg">⚡</p><p class="text-xs font-bold text-slate-700">1 Klik</p><p class="text-[11px] text-slate-400">Langsung jadi app</p></div>
                        <div class="rounded-xl bg-white border border-slate-200 p-3"><p class="text-lg">🔄</p><p class="text-xs font-bold text-slate-700">Sinkron</p><p class="text-[11px] text-slate-400">Data sama 100%</p></div>
                        <div class="rounded-xl bg-white border border-slate-200 p-3"><p class="text-lg">📱</p><p class="text-xs font-bold text-slate-700">Ringan</p><p class="text-[11px] text-slate-400">< 1 MB</p></div>
                    </div>
                </div>

                <!-- Mockup HP -->
                <div class="flex justify-center">
                    <div class="relative w-[280px] h-[560px] rounded-[36px] border-[10px] border-slate-900 bg-white shadow-2xl overflow-hidden">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-6 bg-slate-900 rounded-b-2xl"></div>
                        <div class="h-14 bg-blue-700 flex items-center gap-3 px-4 pt-4 text-white">
                            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center"><i class="ph-fill ph-student"></i></div>
                            <div><p class="text-xs font-bold">Absensi Siswa</p><p class="text-[10px] text-blue-200">SMK BPPI Baleendah</p></div>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="rounded-xl bg-blue-600 text-white p-4">
                                <p class="text-xs text-blue-100">Halo, Orang Tua</p>
                                <p class="font-bold">Budi Santoso - X PPLG</p>
                                <p class="text-xs mt-2 bg-white/20 inline-flex px-2 py-1 rounded-full">✅ Hadir Hari Ini</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="rounded-xl bg-green-50 border border-green-200 p-3 text-center"><p class="text-lg font-extrabold text-green-700">12</p><p class="text-xs text-slate-500">Hadir</p></div>
                                <div class="rounded-xl bg-amber-50 border border-amber-200 p-3 text-center"><p class="text-lg font-extrabold text-amber-600">Rp 300.000</p><p class="text-xs text-slate-500">Tunggakan</p></div>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-3">
                                <p class="text-xs font-bold text-slate-700">Tunggakan SPP</p>
                                <div class="mt-2 flex justify-between text-xs"><span>Juni 2026</span><span class="font-bold">Rp 150.000</span></div>
                                <div class="mt-1 flex justify-between text-xs"><span>Juli 2026</span><span class="font-bold">Rp 150.000</span></div>
                                <p class="text-[11px] text-green-600 mt-2">↻ Sinkron otomatis dari website</p>
                            </div>
                            <div class="rounded-xl bg-slate-900 text-white p-3 text-center text-xs">Icon muncul di layar HP seperti WA</div>
                        </div>
                        <!-- Bottom nav mock -->
                        <div class="absolute bottom-2 left-2 right-2 bg-white border border-slate-200 rounded-2xl flex justify-around py-2">
                            <span class="text-blue-700 text-xs font-bold">● Dashboard</span><span class="text-slate-300 text-xs">Nilai</span><span class="text-slate-300 text-xs">Gallery</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panduan -->
            <div class="mt-10 grid md:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-white border border-slate-200 p-5">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ph-fill ph-android-logo text-green-600"></i> Cara Install di Android (Chrome)</h3>
                    <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                        <li>Buka website ini di <b>Google Chrome</b></li>
                        <li>Klik tombol biru <b>Download & Install</b> di atas</li>
                        <li>Klik <b>Install</b> saat muncul pop-up</li>
                        <li>Selesai! Icon <b>Absensi Siswa</b> muncul di HP</li>
                    </ol>
                    <p class="mt-3 text-xs text-slate-400">Jika tombol tidak muncul: klik titik 3 di kanan atas Chrome → <b>Install app / Add to Home screen</b></p>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-5">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ph-fill ph-apple-logo"></i> Cara Install di iPhone (Safari)</h3>
                    <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                        <li>Buka website di <b>Safari</b></li>
                        <li>Tap tombol <b>Share</b> (kotak + panah ke atas)</li>
                        <li>Pilih <b>Add to Home Screen</b></li>
                        <li>Tap <b>Add</b> → icon muncul di Home</li>
                    </ol>
                    <p class="mt-3 text-xs text-slate-400">iPhone tidak pakai tombol biru, langsung pakai Share.</p>
                </div>
            </div>

            <!-- FAQ Sinkron -->
            <div class="mt-6 rounded-2xl bg-white border border-slate-200 p-5">
                <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ph-fill ph-question"></i> Pertanyaan yang sering ditanya</h3>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="rounded-xl bg-slate-50 p-3"><p class="font-bold text-slate-800">Apakah data di website dan aplikasi beda?</p><p class="text-slate-600"><b>Tidak.</b> Sama persis. Karena aplikasinya cuma jalan pintas website kamu. Semua data (absensi, nilai, tunggakan SPP, gallery) ambil dari database yang sama. Contoh: guru input tunggakan Rp 150.000 di laptop (website), 1 detik kemudian orang tua lihat di HP (aplikasi) langsung muncul.</p></div>
                    <div class="rounded-xl bg-slate-50 p-3"><p class="font-bold text-slate-800">Perlu login lagi di aplikasi?</p><p class="text-slate-600">Ya, login sekali saja seperti di website, pakai NIS/email + password. Setelah itu tetap login.</p></div>
                    <div class="rounded-xl bg-slate-50 p-3"><p class="font-bold text-slate-800">Butuh internet?</p><p class="text-slate-600">Ya, untuk lihat data terbaru butuh internet, sama seperti website. Jika offline, aplikasi akan tampil halaman offline.</p></div>
                    <div class="rounded-xl bg-slate-50 p-3"><p class="font-bold text-slate-800">Berapa ukuran aplikasinya?</p><p class="text-slate-600">Sangat ringan, &lt; 1 MB, karena tidak download file besar. Cuma icon + jalan pintas.</p></div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="/login" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700">← Kembali ke Login</a>
            </div>
        </main>

        <footer class="py-6 text-center text-xs text-slate-400">© {{ date('Y') }} SMK BPPI Baleendah • PWA Installable</footer>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(()=>{});
        }
        let deferredPrompt = null;
        const btn = document.getElementById('btn-install');
        const hint = document.getElementById('install-hint');
        const iosHint = document.getElementById('ios-hint');
        const httpHint = document.getElementById('http-hint');
        const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
        const isHttp = location.protocol === 'http:';
        if(isIos){ iosHint.classList.remove('hidden'); hint.textContent='iPhone: ikuti panduan di atas (pakai Safari)'; }
        if(isHttp && !isIos){
            httpHint.classList.remove('hidden');
            hint.textContent='Kamu pakai http (192.168...), tombol biru tidak aktif — pakai cara manual di kotak kuning di atas.';
            hint.classList.add('text-amber-600');
        }

        window.addEventListener('beforeinstallprompt', (e)=>{
            e.preventDefault();
            deferredPrompt = e;
            btn.classList.remove('opacity-50');
            hint.textContent = 'HP kamu siap! Klik tombol di atas untuk install.';
            hint.classList.add('text-green-600');
        });
        window.addEventListener('appinstalled', ()=>{
            hint.textContent='✅ Berhasil terinstall! Cek icon di layar HP kamu.';
            hint.classList.add('text-green-600');
            btn.innerHTML='<i class=\"ph-fill ph-check-circle\"></i> Sudah Terinstall - Buka di HP';
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-blue-700');
        });
        async function installPWA(){
            if(deferredPrompt){
                deferredPrompt.prompt();
                const r = await deferredPrompt.userChoice;
                if(r.outcome==='dismissed'){ hint.textContent='Dibatalkan. Kamu bisa klik lagi kapan saja.'; }
                deferredPrompt=null;
            } else {
                if(isIos){
                    iosHint.scrollIntoView({behavior:'smooth', block:'center'});
                    iosHint.classList.add('ring-2','ring-amber-300');
                    setTimeout(()=>iosHint.classList.remove('ring-2','ring-amber-300'),2000);
                } else if(isHttp){
                    httpHint.scrollIntoView({behavior:'smooth', block:'center'});
                    httpHint.classList.add('ring-2','ring-amber-300');
                    setTimeout(()=>httpHint.classList.remove('ring-2','ring-amber-300'),2000);
                } else {
                    // Sudah https tapi Chrome belum kasih prompt -> kasih panduan manual
                    httpHint.classList.remove('hidden');
                    httpHint.innerHTML='<p class=\"font-bold\">Chrome belum kasih pop-up?</p><p class=\"mt-1\">Klik <b>titik 3 kanan atas Chrome</b> → <b>Install aplikasi</b> atau <b>Tambahkan ke Layar Utama</b></p>';
                    httpHint.scrollIntoView({behavior:'smooth', block:'center'});
                }
            }
        }
        // Deteksi sudah standalone (sudah jadi app)
        if(window.matchMedia('(display-mode: standalone)').matches){
            btn.innerHTML='<i class=\"ph-fill ph-check-circle\"></i> Kamu sudah buka via Aplikasi ✅';
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-blue-700');
            hint.textContent='Kamu sedang membuka versi aplikasi. Data tetap sinkron dengan website.';
        }
    </script>
</body>
</html>
