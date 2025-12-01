<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Saran Aktivitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'biru-primary': '#3478F5',
                        'biru-muda': '#C6E2FF',
                        'hitam-gelap': '#151515',
                        'abu-terang': '#F8F8F8',
                        'abu-medium': '#AAAAAA',
                        'sukses': '#34C759',
                        'warning': '#FF9500',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-abu-terang text-[#333] font-sans m-0 p-0">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <div class="py-5">
                <a href="index.php" class="-mt-[50px] -mb-[25px] flex items-center no-underline">
                    <img src="assets/logoWellmate.jpg" alt="WellMate Logo" class="h-[140px] -mr-10 -ml-8">
                    <span class="text-[1.4em] text-gray-700 font-bold pb-[15px]">WellMate</span>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="index.php?c=Beranda&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-line"></i>
                    <span class="font-medium">Beranda</span>
                </a>
                <a href="index.php?c=Tracking&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-glass-water"></i>
                    <span>Tracking Minum</span>
                </a>
                <a href="index.php?c=Saran&m=index" class="flex items-center space-x-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
                    <i class="fas fa-running"></i>
                    <span>Saran Aktivitas</span>
                </a>
                <a href="index.php?c=Laporan&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan dan Analisis</span>
                </a>
                <a href="index.php?c=Berita&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-newspaper"></i>
                    <span>Berita dan Edukasi</span>
                </a>

                <a href="index.php?c=Friend&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-users"></i>
                    <span>Teman</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <div class="p-3 no-underline">
                <a href="index.php?c=Auth&m=logout" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex justify-between items-center bg-white px-8 py-4 shadow-sm">
                <div></div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <a href="index.php?c=Notification&m=index" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                            <i class="fas fa-bell text-xl"></i>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <span id="notif-badge" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    <?= $unreadCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="w-10 h-10 bg-blue-400 rounded-full overflow-hidden flex items-center justify-center">
                        <img src="assets/fotoProfil.jpg" alt="Profile Picture" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content Area -->
            <main class="flex-1 overflow-y-auto bg-blue-100 p-6">
                <!-- Welcome Banner -->
                <div class="bg-biru-primary text-white p-5 rounded-xl mb-0">
                    <h1 class="m-0 mb-[5px] text-[1.8em] font-bold">Saran Aktivitas dan Cairan</h1>
                    <p class="m-0">Lihat kebutuhan hidrasi ekstra Anda berdasarkan aktivitas fisik.</p>
                </div>
                
                <!-- Summary Section -->
                <div class="bg-transparent py-5 px-0 mb-0">
                    <div class="flex gap-[15px] justify-between">
                        <?php
                        $totalDiminum = $statistik['total_diminum'];
                        $sisa = max(0, $targetHarian - $totalDiminum);
                        $persentase = min(100, ($totalDiminum / $targetHarian) * 100);
                        
                        function formatVolume($ml) {
                            if ($ml >= 1000) {
                                return number_format($ml / 1000, 1) . 'L';
                            }
                            return $ml . 'ml';
                        }
                        ?>
                        <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                            <div class="text-[0.85em] text-abu-medium leading-tight">Target Harian</div>
                            <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                                <span><?php echo formatVolume($targetHarian); ?></span>
                                <div class="w-[30px] h-[30px] bg-biru-muda border-none rounded-full flex items-center justify-center text-biru-primary text-base">🎯</div>
                            </div>
                            <div class="text-[0.85em] text-abu-medium leading-tight">Capai target hidrasi Anda!</div>
                        </div>
                        <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                            <div class="text-[0.85em] text-abu-medium leading-tight">Sudah diminum</div>
                            <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                                <span><?php echo formatVolume($totalDiminum); ?></span>
                                <div class="w-[30px] h-[30px] bg-biru-muda border-none rounded-full flex items-center justify-center text-biru-primary text-base">💧</div>
                            </div>
                            <div class="text-[0.85em] text-abu-medium leading-tight">dari <?php echo formatVolume($targetHarian); ?>, target</div>
                        </div>
                        <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                            <div class="text-[0.85em] text-abu-medium leading-tight">Sisa Target</div>
                            <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                                <span><?php echo formatVolume($sisa); ?></span>
                                <div class="w-[30px] h-[30px] bg-biru-muda border-none rounded-full flex items-center justify-center text-biru-primary text-base">⏳</div>
                            </div>
                            <div class="text-[0.85em] text-abu-medium leading-tight">dari <?php echo formatVolume($targetHarian); ?>, target</div>
                        </div>
                        <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                            <div class="text-[0.85em] text-abu-medium leading-tight">Progres</div>
                            <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                                <span><?php echo round($persentase); ?>%</span>
                                <div class="w-[30px] h-[30px] bg-biru-muda border-none rounded-full flex items-center justify-center text-biru-primary text-base">📈</div>
                            </div>
                            <div class="text-[0.85em] text-abu-medium leading-tight">dari <?php echo formatVolume($targetHarian); ?>, target</div>
                        </div>
                    </div>
                </div>

                <!-- Recommendation Area -->
                <div class="bg-transparent p-0 pb-[30px] mb-0">
                    <div class="bg-white p-5 rounded-xl shadow-sm m-0">
                        <h3 class="mt-0 font-bold text-hitam-gelap border-b border-abu-terang pb-2.5 mb-5">Rekomendasi Cairan Tambahan untuk Aktivitas Fisik</h3>
                        
                        <div class="grid grid-cols-3 gap-[15px]" id="recommendation-grid">
                            <?php foreach ($aktivitas as $act): ?>
                            <div class="bg-white border border-gray-200 rounded-xl p-[15px] shadow-sm transition-shadow cursor-pointer hover:shadow-md" data-id="<?php echo $act['id']; ?>">
                                <strong class="block text-base mb-[5px] font-medium text-[#333]">
                                    <span class="mr-2 text-biru-primary"><?php echo $act['ikon']; ?></span><?php echo htmlspecialchars($act['nama']); ?>
                                </strong>
                                <a href="#" class="show-detail-saran text-[0.85em] text-biru-primary no-underline block mt-2.5 visible">Lihat Selengkapnya...</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Modal -->
    <div id="detail-saran-modal" class="hidden fixed top-0 left-0 w-full h-full bg-black/60 justify-center items-center z-[1000]">
        <div class="bg-white p-[30px] rounded-xl w-[90%] max-w-[400px] shadow-xl text-center">
            <h3 class="mt-0 text-[#333] text-[1.4em] mb-[5px]">Rekomendasi Cairan Tambahan</h3>
            <p class="text-biru-primary mb-[15px] font-medium" id="modal-activity-title">Aktivitas: -</p>
            <p class="text-sm text-[#555] leading-relaxed mb-[25px]" id="modal-description">-</p>
            <p class="text-[2em] font-bold text-biru-primary mb-[25px]" id="modal-amount">-</p>
            <button class="py-2.5 px-5 border-none rounded-lg cursor-pointer font-bold text-base bg-biru-primary text-white w-full" id="modal-ok-btn">OK</button>
        </div>
    </div>

    <script>
        // Data aktivitas dari PHP
        const DATA_AKTIVITAS = <?php echo json_encode($aktivitas); ?>;

        function tampilkanModal(data) {
            document.getElementById('modal-activity-title').textContent = `Aktivitas: ${data.nama}`;
            document.getElementById('modal-description').textContent = data.deskripsi;
            document.getElementById('modal-amount').textContent = data.cairan_tambahan;
            document.getElementById('detail-saran-modal').classList.remove('hidden');
            document.getElementById('detail-saran-modal').classList.add('flex');
        }

        function sembunyikanModal() {
            document.getElementById('detail-saran-modal').classList.add('hidden');
            document.getElementById('detail-saran-modal').classList.remove('flex');
        }

        // Event listeners untuk card
        document.querySelectorAll('[data-id]').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName !== 'A') {
                    const id = parseInt(this.dataset.id);
                    const aktivitas = DATA_AKTIVITAS.find(a => a.id == id);
                    if (aktivitas) {
                        tampilkanModal(aktivitas);
                    }
                }
            });
            
            const link = card.querySelector('.show-detail-saran');
            if (link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = parseInt(this.closest('[data-id]').dataset.id);
                    const aktivitas = DATA_AKTIVITAS.find(a => a.id == id);
                    if (aktivitas) {
                        tampilkanModal(aktivitas);
                    }
                });
            }
        });
        
        document.getElementById('modal-ok-btn').addEventListener('click', sembunyikanModal);

        document.getElementById('detail-saran-modal').addEventListener('click', function(e) {
            if (e.target.id === 'detail-saran-modal') {
                sembunyikanModal();
            }
        });
    </script>
</body>
</html>