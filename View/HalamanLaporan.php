<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WellMate - Laporan dan Analisis' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
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
                <a href="index.php?c=Saran&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-running"></i>
                    <span>Saran Aktivitas</span>
                </a>
                <a href="index.php?c=Laporan&m=index" class="flex items-center space-x-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
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
<!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-blue-100 p-6"> 
<!-- LAPORAN MINGGUAN -->
                <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                    <div class="bg-blue-500 text-white p-4 flex justify-between items-center cursor-pointer" onclick="toggleSection('weekly')">
                        <h2 class="text-xl font-semibold">Laporan Mingguan</h2>
                        <svg id="weekly-icon" class="w-6 h-6 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div id="weekly-content" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> 
                            <!-- Diagram Mingguan -->
                            <div class="lg:col-span-1 w-full lg:w-[400px]">
                                <h3 class="text-gray-700 font-medium mb-4 text-center">Progres Jumlah Minum per Hari (1 Minggu)</h3>
                                <div class="w-full h-80">
                                    <canvas id="weeklyChart"></canvas>
                                </div>
                            </div>
                            <!-- Jumlah Minum per Hari -->
                            <div class="lg:col-span-1 space-y-4 text-[#4B5563] font-bold ml-0 lg:ml-28">
                                <div class="bg-blue-50 p-4 rounded-lg shadow-md">
                                    <h5 class="mb-3 text-sm">Jumlah minum/hari:</h5>
                                    <div class="grid grid-cols-[max-content_auto] text-xs gap-x-[2px]">
                                        <?php if (!empty($laporan_mingguan['data_per_hari'])): ?>
                                            <?php foreach ($laporan_mingguan['data_per_hari'] as $hari): ?> <!--loop perhari lalu tampilkan nama hari dan jumlah minumnya-->
                                                <div><?= $hari['hari'] ?></div>
                                                <div>: <?= number_format($hari['jumlah'], 0, ',', '.') ?> ml</div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="col-span-2 text-center">Tidak ada data</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Analisis dan Rekomendasi Mingguan -->
                            <div class="lg:col-span-1 space-y-4"> <!--data dan logicnya diatur di mvc di getDataLaporanMingguan-->
                                <div class="bg-blue-50 p-4 rounded-lg text-[#4B5563] font-bold shadow-md">
                                    <div class="grid grid-cols-[max-content_auto] gap-x-2 text-sm">
                                        <div>Jumlah target minum</div>
                                        <div>: <?= number_format($laporan_mingguan['target_konsumsi'], 0, ',', '.') ?> ml</div>
                                        
                                        <div>Jumlah ketercapaian</div>
                                        <div>: <?= number_format($laporan_mingguan['total_konsumsi'], 0, ',', '.') ?> ml</div>
                                        
                                        <div>Persentase ketercapaian</div>
                                        <div>: <?= number_format($laporan_mingguan['persentase'], 1, ',', '.') ?>%</div>
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-4 rounded shadow-md font-bold">
                                    <h5 class="mb-2 text-[#3478F5]">Analisis:</h5>
                                    <p class="text-sm text-[#4B5563]"><?= $laporan_mingguan['analisis'] ?></p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded shadow-md font-bold">
                                    <h5 class="mb-2 text-[#3478F5]">Rekomendasi:</h5>
                                    <p class="text-sm text-[#4B5563]"><?= $laporan_mingguan['rekomendasi'] ?></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
<!-- LAPORAN BULANAN -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-blue-500 text-white p-4 flex justify-between items-center cursor-pointer" onclick="toggleSection('monthly')">
                        <h2 class="text-xl font-semibold">Laporan Bulanan</h2>
                        <svg id="monthly-icon" class="w-6 h-6 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div id="monthly-content" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Diagram Bulanan -->
                            <div class="lg:col-span-1 w-full lg:w-[390px]">
                                <h3 class="text-gray-700 font-medium mb-4 text-center">Progres Jumlah Minum per Minggu (1 Bulan)</h3>
                                <div class="w-full h-80">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                            <!-- Jumlah Minum per Minggu -->
                            <div class="lg:col-span-1 space-y-4 text-[#4B5563] font-bold ml-0 lg:ml-28">
                                <div class="bg-blue-50 p-4 rounded-lg shadow-md">
                                    <h5 class="mb-3 text-sm">Jumlah minum/minggu:</h5>
                                    <div class="space-y-1 text-[11px]">
                                        <?php if (!empty($laporan_bulanan['data_per_minggu'])): ?>
                                            <?php foreach ($laporan_bulanan['data_per_minggu'] as $minggu): ?> <!--loop perminggu lalu tampilkan nama minggu dan jumlah minumnya-->
                                                <div><?= $minggu['minggu'] ?> : <?= number_format($minggu['jumlah'], 0, ',', '.') ?> ml</div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-center">Tidak ada data</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Analisis dan Rekomendasi Bulanan -->
                            <div class="lg:col-span-1 space-y-4"> <!--data dan logicnya diatur di mvc di getDataLaporanBulanan-->
                                <div class="bg-blue-50 p-4 rounded-lg text-[#4B5563] font-bold shadow-md">
                                    <div class="grid grid-cols-[max-content_auto] gap-x-2 text-sm">
                                        <div>Jumlah target minum</div>
                                        <div>: <?= number_format($laporan_bulanan['target_konsumsi'], 0, ',', '.') ?> ml</div>
                                        
                                        <div>Jumlah ketercapaian</div>
                                        <div>: <?= number_format($laporan_bulanan['total_konsumsi'], 0, ',', '.') ?> ml</div>
                                        
                                        <div>Persentase ketercapaian</div>
                                        <div>: <?= number_format($laporan_bulanan['persentase'], 1, ',', '.') ?>%</div>
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-4 rounded shadow-md font-bold">
                                    <h5 class="mb-2 text-[#3478F5]">Analisis:</h5>
                                    <p class="text-sm text-[#4B5563]"><?= $laporan_bulanan['analisis'] ?></p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded shadow-md font-bold">
                                    <h5 class="mb-2 text-[#3478F5]">Rekomendasi:</h5>
                                    <p class="text-sm text-[#4B5563]"><?= $laporan_bulanan['rekomendasi'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<!-- MODAL ERROR -->
    <div id="errorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[1000]">
        <div class="bg-white rounded-lg p-8 max-w-sm mx-4 text-center relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div class="w-16 h-16 bg-red-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Data tidak tersedia atau terjadi kesalahan</h3>
        </div>
    </div>
<!-- JAVASCRIPT -->
    <script>
        // ambil data dari PHP
        const weeklyData = <?= json_encode($laporan_mingguan['data_per_hari']) ?>;
        const monthlyData = <?= json_encode($laporan_bulanan['data_per_minggu']) ?>;
        // buat chart mingguan, datanya dari mvc
        const weeklyLabels = weeklyData.map(d => d.hari);
        const weeklyValues = weeklyData.map(d => parseInt(d.jumlah));
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Jumlah Minum (ml)',
                    data: weeklyValues,
                    backgroundColor: 'rgba(96, 165, 250, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...weeklyValues) + 500,
                        ticks: {
                            stepSize: 500
                        }
                    }
                }
            }
        });
        // buat chart bulanan, datanya dari mvc
        const monthlyLabels = monthlyData.map(d => d.minggu);
        const monthlyValues = monthlyData.map(d => parseInt(d.jumlah));
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Jumlah Minum (ml)',
                    data: monthlyValues,
                    backgroundColor: 'rgba(125, 211, 252, 0.8)',
                    borderColor: 'rgba(56, 189, 248, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...monthlyValues) + 2000,
                        ticks: {
                            stepSize: 2000
                        }
                    }
                }
            }
        });
        // untuk drop-down buka/tutup bagian mingguan dan bulanan
        function toggleSection(section) {
            const content = document.getElementById(section + '-content');
            const icon = document.getElementById(section + '-icon');
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(-90deg)';
            }
        }
        // untuk buka dan tutup modal error
        function showErrorModal() {
            document.getElementById('errorModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('errorModal').classList.add('hidden');
        }
        // atur tampilan awal (mingguan terbuka, bulanan tertutup)
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('weekly-content').style.display = 'block';
            document.getElementById('monthly-content').style.display = 'none';
            document.getElementById('monthly-icon').style.transform = 'rotate(-90deg)';
        });
    </script>
</body>
</html>