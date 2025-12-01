<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Tambah Teman</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="index.php?c=Laporan&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan dan Analisis</span>
                </a>
                <a href="index.php?c=Berita&m=index" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-newspaper"></i>
                    <span>Berita dan Edukasi</span>
                </a>

                <a href="index.php?c=Friend&m=index" class="flex items-center space-x-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
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

        <!-- Konten Utama -->
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

            <main class="flex-1 overflow-y-auto">
                <!-- Area Konten -->
                <div class="p-8 bg-blue-100 min-h-full">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <!-- Notifikasi Status -->
                        <?php if (isset($_GET['status'])): ?>
                            <?php if ($_GET['status'] === 'sent'): ?>
                                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
                                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                                    <span>Permintaan pertemanan berhasil dikirim!</span>
                                </div>
                            <?php elseif ($_GET['status'] === 'exists'): ?>
                                <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                                    <span>Permintaan pertemanan sudah ada atau kalian sudah berteman.</span>
                                </div>
                            <?php elseif ($_GET['status'] === 'fail'): ?>
                                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
                                    <i class="fas fa-times-circle mr-3 text-xl"></i>
                                    <span>Gagal mengirim permintaan pertemanan. Silakan coba lagi.</span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Search Bar and Friend Requests Button -->
                        <div class="flex items-center space-x-4 mb-8">
                            <!-- Search Bar -->
                            <form action="index.php?c=Friend&m=searchFriend" method="GET" class="flex-1 relative">
                                <input type="hidden" name="c" value="Friend">
                                <input type="hidden" name="m" value="searchFriend">
                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-500 text-xl"></i>
                                </div>
                                <input type="text" name="q" id="searchInput" placeholder="Cari teman berdasarkan nama atau username..."
                                    class="w-full pl-16 pr-6 py-4 bg-blue-100 rounded-full text-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition"
                                    value="<?= htmlspecialchars($query ?? '') ?>">
                            </form>
                            
                            <!-- Friend Requests Button -->
                            <a href="index.php?c=Friend&m=listRequests" class="relative p-3 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                <i class="fas fa-users text-xl"></i>
                                <?php if (isset($requestCount) && $requestCount > 0): // Tampilkan badge hanya jika ada permintaan ?>
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        <?= $requestCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>

                        <!-- Friend Suggestions List -->
                        <div class="space-y-4" id="searchResults">
                            <?php if (!empty($query) && empty($users)): ?>
                                <div class="text-center py-10 text-gray-500">
                                    <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                                    <p class="text-lg">Tidak ada pengguna yang ditemukan dengan kata kunci "<?= htmlspecialchars($query) ?>".</p>
                                </div>
                            <?php elseif (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <div class="friend-item flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm"
                                        data-name="<?= htmlspecialchars($user['nama']) ?>"
                                        data-username="<?= htmlspecialchars($user['username']) ?>">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center text-purple-800 font-bold">
                                                <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($user['nama']) ?></h3>
                                                <p class="text-sm text-gray-500">@<?= htmlspecialchars($user['username']) ?></p>
                                            </div>
                                        </div>
                                        <div>
                                            <?php 
                                                $status = $user['friendship_status']; // Diambil dari Controller
                                                $buttonText = '';
                                                $buttonClass = '';
                                                $disabled = false;
                                            ?>
                                            
                                            <?php if ($status === 'none'): ?>
                                                <form action="index.php?c=Friend&m=sendRequest" method="POST">
                                                    <input type="hidden" name="id_teman" value="<?= $user['id_pengguna'] ?>">
                                                    <input type="hidden" name="search_query" value="<?= htmlspecialchars($query ?? '') ?>">
                                                    <button type="submit" 
                                                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-150 text-sm send-request-btn">
                                                        <i class="fas fa-user-plus mr-1"></i> Tambah Teman
                                                    </button>
                                                </form>
                                            <?php elseif ($status === 'pending_sent'): ?>
                                                <button disabled 
                                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg opacity-80 cursor-not-allowed transition duration-150 text-sm">
                                                    <i class="fas fa-hourglass-half mr-1"></i> Permintaan Terkirim
                                                </button>
                                            <?php elseif ($status === 'pending_received'): ?>
                                                <span class="text-orange-500 font-semibold text-sm">
                                                    <i class="fas fa-bell mr-1"></i> Menunggu Konfirmasi Anda
                                                </span>
                                                <a href="index.php?c=Friend&m=listRequests" class="ml-2 text-blue-500 hover:text-blue-700 text-sm">Lihat</a>
                                            <?php elseif ($status === 'accepted'): ?>
                                                <button disabled 
                                                        class="bg-green-500 text-white px-4 py-2 rounded-lg opacity-80 cursor-not-allowed transition duration-150 text-sm">
                                                    <i class="fas fa-check mr-1"></i> Sudah Berteman
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-10 text-gray-500">
                                    <i class="fas fa-search text-4xl mb-3"></i>
                                    <p class="text-lg">Silakan masukkan nama atau username di kolom pencarian untuk menemukan teman baru.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const searchInput = document.querySelector('input[type="text"]');
        const friendItems = document.querySelectorAll('.friend-item');

        searchInput.addEventListener('input', function (e) {
            const searchValue = e.target.value.toLowerCase();

            friendItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                const username = item.getAttribute('data-username').toLowerCase();

                if (name.includes(searchValue) || username.includes(searchValue)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Add Friend button functionality
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Tambah Teman')) {
                button.addEventListener('click', function() {
                    this.innerHTML = '<span>Permintaan Pertemanan Terkirim</span><i class="fas fa-check ml-2"></i>';
                    this.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    this.classList.add('bg-green-500', 'hover:bg-green-600');
                });
            }
        });
    </script>
</body>
</html>