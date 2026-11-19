<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCareer — Tempat Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #faf7f2; }
        .sidebar { background-color: #0c251e; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen overflow-hidden antialiased">

    <!-- SIDEBAR (Tetap berada di atas backdrop agar tidak ikut redup) -->
    <aside class="sidebar relative z-50 w-[260px] h-full flex flex-col text-white flex-shrink-0 border-r border-white/10">
        <!-- Logo & Portal -->
        <div class="p-6 pb-4">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl bg-[#f18c54] flex items-center justify-center text-white shadow-sm">
                    <i class="ph-fill ph-paw-print text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-base tracking-tight leading-tight">PawCareer</h1>
                    <p class="text-[11px] text-gray-400">Klinik Hewan Sahabat Satwa</p>
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-white/10">
                <p class="text-[11px] text-gray-400">
                    Portal <span class="text-white font-semibold">Manajer Divisi</span> · Medis &amp; Bedah
                </p>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-3.5 py-2 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard.manager') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                <i class="ph ph-squares-four text-lg"></i>
                <span>Dasbor</span>
            </a>

            <a href="{{ route('requisitions.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                <i class="ph ph-file-text text-lg"></i>
                <span>Pengajuan Kebutuhan</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                <i class="ph ph-user-plus text-lg"></i>
                <span>Karyawan Baru</span>
            </a>

            <!-- Menu Aktif: Tempat Sampah -->
            <a href="{{ route('trash.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 bg-[#fdfbf6] text-[#0c251e] rounded-xl font-semibold text-sm shadow-xs">
                <i class="ph-fill ph-trash text-lg"></i>
                <span>Tempat Sampah</span>
            </a>

            <div class="pt-6 pb-2 px-3.5">
                <p class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Lainnya</p>
            </div>

            <!-- Tombol Notifikasi Interaktif -->
            <button type="button"
                    id="notifToggleBtn"
                    aria-expanded="false"
                    aria-controls="notifDrawer"
                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/10 border border-transparent transition-all text-sm font-medium cursor-pointer">
                <div class="flex items-center gap-3">
                    <i id="notifBellIcon" class="ph ph-bell text-lg"></i>
                    <span>Notifikasi</span>
                </div>
                <span id="notifBadge"
                      class="bg-[#f18c54] text-white text-[11px] font-bold min-w-[20px] h-5 px-1.5 inline-flex items-center justify-center rounded-full">
                    1
                </span>
            </button>
        </nav>

        <!-- Profil Bawah -->
        <div class="p-4 border-t border-white/10 bg-black/15 flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-[#f18c54] flex items-center justify-center font-bold text-xs text-white shrink-0">
                    BP
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">drh. Bima Prasetyo</p>
                    <p class="text-[11px] text-gray-400 truncate">bima@sahabatsatwa.id</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="m-0 shrink-0">
                @csrf
                <button type="submit"
                        title="Keluar"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
                    <i class="ph ph-sign-out text-lg"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="px-8 py-9 max-w-5xl mx-auto">

            <!-- Breadcrumb & Header Halaman -->
            <div class="mb-8">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                    <span>Portal Manajer Divisi</span>
                    <span>/</span>
                    <span class="font-medium text-gray-800">Tempat Sampah</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <span class="inline-block text-[11px] font-bold text-[#3f664c] tracking-wider uppercase mb-1">
                            Soft Delete · Retensi 30 Hari
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Tempat sampah</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Pengajuan milik Anda yang dihapus dapat dipulihkan kembali sebelum batas waktu 30 hari berakhir.
                        </p>
                    </div>

                    <div class="text-xs text-gray-500 bg-white border border-gray-200 px-3.5 py-2 rounded-lg shrink-0">
                        Total item tersimpan: <strong class="text-gray-900">1</strong>
                    </div>
                </div>
            </div>

            <!-- List Data yang Dihapus -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-5 mb-6 shadow-2xs hover:border-gray-300 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-5">

                <!-- Kiri: Badge & Info Data -->
                <div class="flex items-start sm:items-center gap-4">
                    <span class="bg-[#f3f4f1] text-[#0c251e] border border-gray-200 text-[10px] font-bold px-3 py-1.5 rounded-lg tracking-wider shrink-0">
                        PENGAJUAN
                    </span>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-[15px]">Asisten Bedah · 1 orang</h3>
                        <p class="text-[13px] text-gray-500 mt-0.5">
                            Alasan: Duplikat pengajuan · Dihapus pada 30 Okt 2026
                        </p>
                    </div>
                </div>

                <!-- Kanan: Progress Bar & Tombol -->
                <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                    <!-- Countdown bar -->
                    <div class="w-36">
                        <div class="flex items-center justify-between text-[11px] mb-1.5">
                            <span class="text-gray-400">Sisa waktu</span>
                            <span class="text-[#0c251e] font-semibold">24 hari</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[#0c251e] h-1.5 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>

                    <!-- Tombol Pulihkan -->
                    <button type="button"
                            onclick="return confirm('Pulihkan pengajuan Asisten Bedah ke daftar aktif?')"
                            class="inline-flex items-center gap-1.5 bg-[#e6f4ed] hover:bg-[#d2ecdf] text-[#0c251e] text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors border border-[#c6e5d5] cursor-pointer shrink-0">
                        <i class="ph ph-arrow-counter-clockwise text-sm font-bold"></i>
                        <span>Pulihkan</span>
                    </button>
                </div>
            </div>

            <!-- Banner Info Aturan -->
            <div class="bg-[#edf6f2] border border-[#d3e8de] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3">
                    <i class="ph-fill ph-info text-[#0c251e] text-lg shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#163a28] leading-relaxed">
                        Posisi tidak dapat dipulihkan bila divisinya juga terhapus. Nama data di tempat sampah tetap terpakai sampai dipulihkan atau dibuang permanen. Pembuangan otomatis harian setelah 30 hari berlaku pada Rilis 2.
                    </p>
                </div>
                <span class="text-[10px] font-bold text-[#4a6e55] bg-white/80 border border-[#d3e8de] px-2.5 py-1 rounded-md uppercase tracking-wider whitespace-nowrap self-start sm:self-center">
                    SDL · A-28
                </span>
            </div>

        </div>
    </main>

    <!-- OVERLAY BACKDROP (Hanya meredupkan area konten utama, bukan sidebar) -->
    <div id="notifBackdrop"
         class="fixed inset-0 left-[260px] bg-slate-900/25 backdrop-blur-[1px] z-30 opacity-0 pointer-events-none transition-opacity duration-200"></div>

    <!-- SLIDE-OVER DRAWER NOTIFIKASI (Meluncur halus dari balik sidebar) -->
    <aside id="notifDrawer"
           aria-label="Daftar Notifikasi"
           aria-hidden="true"
           class="fixed top-0 left-[260px] h-full w-full max-w-[400px] bg-white border-r border-gray-200 shadow-[12px_0_32px_rgba(12,37,30,0.12)] z-40 flex flex-col -translate-x-full pointer-events-none transition-transform duration-250 ease-out">

        <!-- Header Panel -->
        <div class="px-6 py-5 border-b border-gray-200 bg-white flex items-start justify-between gap-3">
            <div>
                <div class="flex items-center gap-2.5">
                    <h3 class="text-base font-bold text-gray-900">Notifikasi</h3>
                    <span id="drawerUnreadPill"
                          class="bg-[#f18c54]/15 text-[#d96b2d] text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                        1 Belum dibaca
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Pembaruan status pengajuan &amp; aktivitas tempat sampah</p>
            </div>

            <button type="button"
                    id="notifCloseBtn"
                    aria-label="Tutup panel notifikasi"
                    class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 hover:text-gray-900 hover:bg-gray-100 flex items-center justify-center transition-colors cursor-pointer shrink-0">
                <i class="ph ph-x text-base"></i>
            </button>
        </div>

        <!-- Filter Tabs & Tandai Dibaca -->
        <div class="px-6 py-2.5 border-b border-gray-100 bg-[#faf9f6] flex items-center justify-between gap-2">
            <div class="flex items-center gap-1.5">
                <button type="button"
                        data-filter="all"
                        class="notif-tab px-2.5 py-1 rounded-md text-xs font-semibold bg-[#0c251e] text-white transition-colors cursor-pointer">
                    Semua (2)
                </button>
                <button type="button"
                        data-filter="unread"
                        class="notif-tab px-2.5 py-1 rounded-md text-xs font-medium text-gray-600 hover:bg-gray-200/70 transition-colors cursor-pointer">
                    Belum dibaca
                </button>
            </div>

            <button type="button"
                    id="markAllReadBtn"
                    class="text-[11px] font-semibold text-[#0c251e] hover:text-[#f18c54] transition-colors cursor-pointer">
                Tandai dibaca
            </button>
        </div>

        <!-- Daftar Item Notifikasi -->
        <div id="notifList" class="flex-1 overflow-y-auto divide-y divide-gray-100">
            <!-- Item 1: Belum Dibaca -->
            <div id="unreadNotifItem"
                 data-unread="true"
                 class="notif-item p-5 bg-[#fdf9f3] hover:bg-[#fbf3e6] transition-colors flex items-start gap-3.5 cursor-pointer">
                <div class="w-9 h-9 rounded-xl bg-[#f18c54]/15 text-[#e07034] flex items-center justify-center shrink-0 mt-0.5">
                    <i class="ph-fill ph-clock-countdown text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-xs font-bold text-gray-900 leading-snug">
                            Pengingat Masa Simpan Tempat Sampah
                        </p>
                        <span id="unreadDot" class="w-2 h-2 rounded-full bg-[#f18c54] shrink-0 mt-1"></span>
                    </div>
                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                        Pengajuan <strong class="font-semibold text-gray-800">Asisten Bedah · 1 orang</strong> akan dihapus permanen secara otomatis dalam <strong class="font-semibold text-gray-800">24 hari</strong> jika tidak dipulihkan.
                    </p>
                    <div class="flex items-center gap-2 mt-2.5 text-[11px] text-gray-400">
                        <span>Hari ini</span>
                        <span>•</span>
                        <span>Sistem PawCareer</span>
                    </div>
                </div>
            </div>

            <!-- Item 2: Sudah Dibaca -->
            <div data-unread="false"
                 class="notif-item p-5 bg-white hover:bg-gray-50 transition-colors flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-[#e9f5f0] text-[#0c251e] flex items-center justify-center shrink-0 mt-0.5">
                    <i class="ph-fill ph-check-circle text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 leading-snug">
                        Pengajuan Dipindahkan ke Tempat Sampah
                    </p>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Pengajuan Asisten Bedah (Medis &amp; Bedah) dipindahkan ke tempat sampah dengan alasan duplikat pengajuan.
                    </p>
                    <div class="flex items-center gap-2 mt-2.5 text-[11px] text-gray-400">
                        <span>30 Okt 2026</span>
                        <span>•</span>
                        <span>Arsip Divisi</span>
                    </div>
                </div>
            </div>

            <!-- Empty State untuk Filter Belum Dibaca -->
            <div id="emptyUnreadState" class="hidden py-16 px-6 text-center">
                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                    <i class="ph ph-check text-lg"></i>
                </div>
                <p class="text-xs font-semibold text-gray-800">Semua notifikasi sudah dibaca</p>
                <p class="text-[11px] text-gray-400 mt-1">Tidak ada pemberitahuan baru yang tertunda.</p>
            </div>
        </div>

        <!-- Footer Panel -->
        <div class="px-6 py-4 border-t border-gray-200 bg-[#faf9f6] flex items-center justify-between gap-3">
            <a href="{{ route('requisitions.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0c251e] hover:text-[#f18c54] transition-colors">
                <span>Ke Pengajuan Kebutuhan</span>
                <i class="ph ph-arrow-right text-sm"></i>
            </a>

            <button type="button"
                    id="notifCloseFooterBtn"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-100 transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('notifToggleBtn');
            const bellIcon = document.getElementById('notifBellIcon');
            const drawer = document.getElementById('notifDrawer');
            const backdrop = document.getElementById('notifBackdrop');
            const closeBtn = document.getElementById('notifCloseBtn');
            const closeFooterBtn = document.getElementById('notifCloseFooterBtn');
            const markAllReadBtn = document.getElementById('markAllReadBtn');
            const unreadItem = document.getElementById('unreadNotifItem');
            const unreadDot = document.getElementById('unreadDot');
            const notifBadge = document.getElementById('notifBadge');
            const drawerUnreadPill = document.getElementById('drawerUnreadPill');
            const tabs = document.querySelectorAll('.notif-tab');
            const notifItems = document.querySelectorAll('.notif-item');
            const emptyUnreadState = document.getElementById('emptyUnreadState');

            let isOpen = false;
            let currentFilter = 'all';

            function openNotif() {
                isOpen = true;
                drawer.classList.remove('-translate-x-full', 'pointer-events-none');
                drawer.classList.add('translate-x-0', 'pointer-events-auto');
                drawer.setAttribute('aria-hidden', 'false');

                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');

                toggleBtn.setAttribute('aria-expanded', 'true');
                toggleBtn.classList.add('bg-white/12', 'text-white', 'border-white/15');
                if (bellIcon) {
                    bellIcon.classList.remove('ph');
                    bellIcon.classList.add('ph-fill', 'text-[#f18c54]');
                }
            }

            function closeNotif() {
                isOpen = false;
                drawer.classList.add('-translate-x-full', 'pointer-events-none');
                drawer.classList.remove('translate-x-0', 'pointer-events-auto');
                drawer.setAttribute('aria-hidden', 'true');

                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');

                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.classList.remove('bg-white/12', 'text-white', 'border-white/15');
                if (bellIcon) {
                    bellIcon.classList.add('ph');
                    bellIcon.classList.remove('ph-fill', 'text-[#f18c54]');
                }
            }

            function applyFilter(filter) {
                currentFilter = filter;
                let visibleCount = 0;

                tabs.forEach(function (tab) {
                    const active = tab.getAttribute('data-filter') === filter;
                    if (active) {
                        tab.classList.add('bg-[#0c251e]', 'text-white', 'font-semibold');
                        tab.classList.remove('text-gray-600', 'hover:bg-gray-200/70', 'font-medium');
                    } else {
                        tab.classList.remove('bg-[#0c251e]', 'text-white', 'font-semibold');
                        tab.classList.add('text-gray-600', 'hover:bg-gray-200/70', 'font-medium');
                    }
                });

                notifItems.forEach(function (item) {
                    const isUnread = item.getAttribute('data-unread') === 'true';
                    const show = filter === 'all' || (filter === 'unread' && isUnread);
                    item.style.display = show ? 'flex' : 'none';
                    if (show) visibleCount++;
                });

                if (emptyUnreadState) {
                    emptyUnreadState.classList.toggle('hidden', visibleCount > 0);
                }
            }

            function markAsRead() {
                if (notifBadge) notifBadge.classList.add('hidden');
                if (drawerUnreadPill) {
                    drawerUnreadPill.textContent = '0 Belum dibaca';
                    drawerUnreadPill.classList.remove('bg-[#f18c54]/15', 'text-[#d96b2d]');
                    drawerUnreadPill.classList.add('bg-gray-100', 'text-gray-500');
                }
                if (unreadDot) unreadDot.classList.add('hidden');
                if (unreadItem) {
                    unreadItem.setAttribute('data-unread', 'false');
                    unreadItem.classList.remove('bg-[#fdf9f3]', 'hover:bg-[#fbf3e6]');
                    unreadItem.classList.add('bg-white', 'hover:bg-gray-50');
                }
                applyFilter(currentFilter);
            }

            toggleBtn.addEventListener('click', function () {
                isOpen ? closeNotif() : openNotif();
            });

            closeBtn.addEventListener('click', closeNotif);
            closeFooterBtn.addEventListener('click', closeNotif);
            backdrop.addEventListener('click', closeNotif);

            markAllReadBtn.addEventListener('click', markAsRead);
            unreadItem.addEventListener('click', markAsRead);

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    applyFilter(this.getAttribute('data-filter'));
                });
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && isOpen) {
                    closeNotif();
                }
            });
        });
    </script>
</body>
</html>