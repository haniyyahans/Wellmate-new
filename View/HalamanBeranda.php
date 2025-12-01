<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'wellmate-blue': '#3B82F6',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
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
                <a href="index.php?c=Beranda&m=index" class="flex items-center space-x-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
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

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-blue-100 p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Progress Hidrasi -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Progress Hidrasi Hari Ini</h2>
                        
                        <div class="flex justify-center items-center mb-6">
                            <div class="relative w-64 h-64" id="main-chart-visual">
                                <!-- SVG Circle Progress -->
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="128" cy="128" r="100" stroke="#E5E7EB" stroke-width="20" fill="none"/>
                                    <circle 
                                        id="progress-circle"
                                        cx="128" cy="128" r="100" 
                                        stroke="#3B82F6" 
                                        stroke-width="20" 
                                        fill="none"
                                        stroke-dasharray="628"
                                        stroke-dashoffset="<?php 
                                            $persentase = isset($progressData['persentase']) ? $progressData['persentase'] : 0;
                                            echo 628 - (628 * $persentase / 100); 
                                        ?>"
                                        stroke-linecap="round"
                                    />
                                </svg>
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center">
                                    <span id="progress-percentage" class="text-2xl font-bold text-gray-800">
                                        <?php echo isset($progressData['persentase']) ? $progressData['persentase'] : 0; ?>%
                                    </span>
                                    <small class="text-sm font-normal block text-gray-500">Tercapai</small>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Diminum Hari Ini</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    <?php 
                                        $diminum = isset($progressData['total_diminum']) ? $progressData['total_diminum'] : 0;
                                        echo number_format($diminum / 1000, 1) . 'L'; 
                                    ?>
                                </p>
                            </div>
                            <div class="text-center bg-red-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Masih Perlu</p>
                                <p class="text-2xl font-bold text-orange-500">
                                    <?php 
                                        $sisa = isset($progressData['sisa_kebutuhan']) ? $progressData['sisa_kebutuhan'] : 0;
                                        echo number_format($sisa / 1000, 1) . 'L'; 
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Berita Terkini -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Berita terkini</h2>
                        <div class="space-y-4">
                            <?php if (isset($beritaTerkini) && count($beritaTerkini) > 0): ?>
                                <?php foreach ($beritaTerkini as $berita): ?>
                                    <a href="index.php?c=Berita&m=detail&id=<?php echo $berita['id_berita']; ?>" 
                                    class="block border-l-4 border-blue-500 pl-4 py-2 hover:bg-gray-50 transition duration-150">
                                        <p class="font-semibold text-gray-800 mb-1">
                                            <?php echo htmlspecialchars($berita['judul']); ?>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Kategori: <?php echo htmlspecialchars($berita['kategori']); ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-500 text-center py-4">Belum ada berita tersedia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Motivational Message -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
                    <p class="text-lg text-gray-700">
                        <?php 
                            $persentase = isset($progressData['persentase']) ? $progressData['persentase'] : 0;
                            if ($persentase >= 100) {
                                echo '<span class="font-semibold">🎉 Selamat! Kamu sudah mencapai target hidrasi hari ini!</span>';
                            } elseif ($persentase >= 75) {
                                echo '<span class="font-semibold">Wow, target pencapaianmu tinggal ' . (100 - $persentase) . '% lagi, ayo tingkatkan agar sempurna dengan target!</span>';
                            } elseif ($persentase >= 50) {
                                echo '<span class="font-semibold">Bagus! Kamu sudah di tengah perjalanan, lanjutkan minum air secara teratur!</span>';
                            } elseif ($persentase > 0) {
                                echo '<span class="font-semibold">Yuk mulai minum lebih banyak! Tubuhmu butuh hidrasi yang cukup.</span>';
                            } else {
                                echo '<span class="font-semibold">Jangan lupa minum air hari ini! Mulai catat konsumsi airmu sekarang.</span>';
                            }
                        ?>
                    </p>
                </div>

                <!-- Biodata Pengguna -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Biodata Pengguna</h2>
                        <button onclick="openModal()" class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Edit</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama</p>
                            <p class="font-semibold text-gray-800 biodata-nama">
                                <?php echo isset($dataPengguna['nama']) ? htmlspecialchars($dataPengguna['nama']) : '-'; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Usia</p>
                            <p class="font-semibold text-gray-800 biodata-usia">
                                <?php echo isset($dataPengguna['usia']) && $dataPengguna['usia'] ? htmlspecialchars($dataPengguna['usia']) : '-'; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Berat Badan</p>
                            <p class="font-semibold text-gray-800 biodata-berat">
                                <?php echo isset($dataPengguna['berat_badan']) && $dataPengguna['berat_badan'] ? htmlspecialchars($dataPengguna['berat_badan']) . ' kg' : '-'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div id="modalOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>

    <!-- Modal Pop-up Biodata -->
    <div id="biodataModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">Edit Biodata Pengguna</h2>
            </div>

            <form id="biodataForm" class="p-6 space-y-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama"
                        value="<?php echo isset($dataPengguna['nama']) ? htmlspecialchars($dataPengguna['nama']) : ''; ?>"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-150"
                    >
                </div>

                <div>
                    <label for="usia" class="block text-sm font-medium text-gray-700 mb-2">Usia</label>
                    <input 
                        type="number" 
                        id="usia" 
                        name="usia"
                        value="<?php echo isset($dataPengguna['usia']) ? htmlspecialchars($dataPengguna['usia']) : ''; ?>"
                        min="1"
                        max="150"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-150"
                    >
                </div>

                <div>
                    <label for="beratBadan" class="block text-sm font-medium text-gray-700 mb-2">Berat Badan (kg)</label>
                    <input 
                        type="number" 
                        id="beratBadan" 
                        name="berat_badan"
                        value="<?php echo isset($dataPengguna['berat_badan']) ? htmlspecialchars($dataPengguna['berat_badan']) : ''; ?>"
                        step="0.1"
                        min="1"
                        max="300"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-150"
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-150 flex items-center justify-center space-x-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Success -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="successModalContent">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Berhasil!</h3>
                <p class="text-gray-600 mb-6" id="successMessage">Biodata berhasil diperbarui!</p>
                <button onclick="closeSuccessModal()" class="w-full py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-150">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('biodataModal');
            const overlay = document.getElementById('modalOverlay');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            overlay.classList.remove('hidden');
            
            setTimeout(() => {
                overlay.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('biodataModal');
            const overlay = document.getElementById('modalOverlay');
            const content = document.getElementById('modalContent');
            
            overlay.classList.remove('opacity-100');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }, 300);
        }

        document.getElementById('biodataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('index.php?c=Beranda&m=updateBiodata', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update tampilan
                    if (data.data) {
                        document.querySelector('.biodata-nama').textContent = data.data.nama || '-';
                        document.querySelector('.biodata-usia').textContent = data.data.usia || '-';
                        document.querySelector('.biodata-berat').textContent = data.data.berat_badan ? data.data.berat_badan + ' kg' : '-';
                    }

                    closeModal();
                    showSuccessModal('Biodata berhasil diupdate!');
                    
                    // setTimeout(() => {
                    //     location.reload();
                    // }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            });
        });

        function showSuccessModal(message) {
            const modal = document.getElementById('successModal');
            const content = document.getElementById('successModalContent');
            
            document.getElementById('successMessage').textContent = message;
            
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            const content = document.getElementById('successModalContent');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                // Reload setelah modal ditutup
                location.reload();
            }, 300);
        }

        document.getElementById('modalOverlay').addEventListener('click', closeModal);

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
