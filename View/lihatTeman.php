<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Aktivitas Teman</title>
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

            <main class="flex-1 overflow-y-auto bg-blue-100 p-6"> 
                <!-- Area Konten -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <!-- Judul Halaman dan Aksi -->
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-4xl font-bold text-gray-600">Aktivitas Teman</h1>
                        <div class="flex space-x-3">
                            <a href="index.php?c=Friend&m=searchFriend" class="flex items-center space-x-2 px-6 py-3 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                <i class="fas fa-user-plus"></i>
                                <span class="font-semibold">Tambah Teman</span>
                            </a>
                            
                            <a href="index.php?c=Friend&m=listRequests" class="relative p-3 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                <i class="fas fa-users text-xl"></i>
                                <?php if (isset($requestCount) && $requestCount > 0): // Tampilkan badge hanya jika ada permintaan ?>
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        <?= $requestCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Daftar Aktivitas Teman -->
                    <div  iv class="space-y-4" id="friendsList">
                        <?php if (!empty($friends)): ?>
                            <?php foreach ($friends as $friend): 
                                // $friend['id_teman_user'] adalah ID teman yang sesungguhnya (bukan ID pertemanan)
                                // $friend['nama_teman'] adalah nama teman
                                // $friend['username_teman'] adalah username teman
                                // $friend['id_teman'] adalah ID baris pertemanan di tabel 'teman'
                            ?>
                                <div class="friend-item flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm"
                                    data-name="<?= htmlspecialchars($friend['nama_teman']) ?>"
                                    data-username="<?= htmlspecialchars($friend['username_teman']) ?>">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center text-blue-800 font-bold">
                                            <?= strtoupper(substr($friend['nama_teman'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($friend['nama_teman']) ?></h3>
                                            <p class="text-sm text-gray-500">@<?= htmlspecialchars($friend['username_teman']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="index.php?c=User&m=viewProfile&id=<?= $friend['id_teman_user'] ?>" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-150 text-sm">
                                            Lihat Profil
                                        </a>
                                        <form action="index.php?c=Friend&m=removeFriend" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus <?= htmlspecialchars($friend['nama_teman']) ?> dari daftar teman?');">
                                            <input type="hidden" name="id_teman" value="<?= $friend['id_teman'] ?>">
                                            <button type="button" 
                                                    class="delete-friend-btn bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150 text-sm"
                                                    data-id-teman="<?= $friend['id_teman'] ?>">
                                                Hapus Teman
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-10 text-gray-500">
                                <i class="fas fa-users-slash text-4xl mb-3"></i>
                                <p class="text-lg">Anda belum memiliki teman. Mari cari teman baru!</p>
                                <a href="index.php?c=Friend&m=searchFriend" class="mt-4 inline-block text-blue-500 hover:text-blue-700">
                                    Cari Teman Sekarang
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
            
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Hapus Teman?</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus <span id="friendNameToDelete" class="font-semibold"></span> dari daftar teman?</p>
                <div class="flex space-x-3">
                    <button id="cancelDelete" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Batal
                    </button>
                    <button id="confirmDelete" class="flex-1 px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let friendToDelete = null;

        // Delete friend functionality
        document.querySelectorAll('.delete-friend-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                friendToDelete = this.closest('.friend-item');
                const friendName = friendToDelete.querySelector('h3').textContent;
                
                // Show modal
                document.getElementById('friendNameToDelete').textContent = friendName;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        // Batal hapus
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            friendToDelete = null;
        });

        // Konfirmasi hapus
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (friendToDelete) {
                const idTeman = friendToDelete.querySelector('.delete-friend-btn').getAttribute('data-id-teman');
                const friendName = friendToDelete.querySelector('h3').textContent;
                
                // Kirim request ke server untuk hapus teman
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?c=Friend&m=removeFriend';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_teman';
                input.value = idTeman;
                
                form.appendChild(input);
                document.body.appendChild(form);
                
                // Submit form
                form.submit();
            }
        });

        // Tutup modal saat mengklik di luar modal
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                friendToDelete = null;
            }
        });

        // Cek jika daftar teman kosong
        function checkEmptyState() {
            const friendsList = document.getElementById('friendsList');
            const emptyState = document.getElementById('emptyState');
            const friendItems = friendsList.querySelectorAll('.friend-item');
            
            if (friendItems.length === 0) {
                friendsList.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        }

        // Tampilkan notifikasi
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white font-semibold z-50 transform transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.innerHTML = `
                <i class="fas fa-check-circle mr-2"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            // Animasi masuk
            setTimeout(() => {
                notification.style.transform = 'translateY(0)';
            }, 10);
            
            // Hapus setelah 3 detik
            setTimeout(() => {
                notification.style.transform = 'translateY(-100px)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Tombol Lihat Profil
        document.querySelectorAll('.bg-blue-500.text-white').forEach(btn => {
            if (btn.textContent.includes('Lihat Profil')) {
                btn.addEventListener('click', function() {
                    const friendName = this.closest('.friend-item').querySelector('h3').textContent;
                    showNotification(`Membuka profil ${friendName}`, 'success');
                });
            }
        });
    </script>
</body>
</html>