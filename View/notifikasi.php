<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Notifikasi</title>
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

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex justify-between items-center bg-white px-8 py-4 shadow-sm">
                <div></div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <a href="index.php?c=Notification&m=listNotifications" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
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
                    <div class="max-w-6xl">
                        <!-- Header dengan tombol -->
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="text-4xl font-bold text-gray-600">Notifikasi</h1>
                        </div>

                        <!-- Status Message -->
                        <?php if (isset($statusMessage) && $statusMessage): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                                <span class="block sm:inline"><?= htmlspecialchars($statusMessage) ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Daftar Notifikasi -->
                        <div class="space-y-4">
                            <?php if (!isset($notifications) || empty($notifications)): ?>
                                <div class="bg-white rounded-2xl p-8 shadow-md text-center">
                                    <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg">Belum ada notifikasi</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <div class="notif-item bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition flex items-start justify-between <?= $notif['status'] == 'read' ? 'opacity-60' : '' ?>" 
                                         data-id="<?= $notif['id_notif'] ?>" 
                                         data-status="<?= $notif['status'] ?>">
                                        <div class="flex items-start space-x-4 flex-1">
                                            <!-- Icon -->
                                            <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <?php if (strpos($notif['pesan'], 'teman') !== false || strpos($notif['pesan'], 'pertemanan') !== false): ?>
                                                    <i class="fas fa-user-plus text-white text-xl"></i>
                                                <?php elseif (strpos($notif['pesan'], 'minum') !== false): ?>
                                                    <i class="fas fa-glass-water text-white text-xl"></i>
                                                <?php elseif (strpos($notif['pesan'], 'target') !== false || strpos($notif['pesan'], 'tercapai') !== false): ?>
                                                    <i class="fas fa-trophy text-white text-xl"></i>
                                                <?php elseif (strpos($notif['pesan'], 'kafein') !== false || strpos($notif['pesan'], 'manis') !== false): ?>
                                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-bell text-white text-xl"></i>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-1 cursor-pointer" onclick="markAsRead(<?= $notif['id_notif'] ?>)">
                                                <div class="relative">
                                                    
                                                    <div class="flex items-start justify-between mb-1"> 
                                                        <h3 class="text-xl font-bold text-gray-600">Ada info terbaru nih!</h3>
                                                        
                                                        <?php if ($notif['status'] == 'unread'): ?>
                                                            <span class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full ml-4"></span> 
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <p class="text-gray-800 leading-relaxed pr-4"> 
                                                        <?= nl2br(htmlspecialchars($notif['pesan'])) ?>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Time and Action -->
                                        <div class="flex flex-col items-end space-y-2 ml-4">
                                            <small class="text-xs text-gray-400 mt-1 block">
                                                <?= $notif['waktu_relatif'] ?? '' ?>
                                            </small>
                                            <button class="flex-shrink-0 ml-4 text-gray-400 hover:text-red-500 transition duration-150"
                                                    onclick="event.stopPropagation(); deleteNotification(<?= $notif['id_notif'] ?>)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div> 
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash-alt text-red-500 text-3xl"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h3 class="text-2xl font-bold text-gray-800 text-center mb-3">
                Hapus Notifikasi?
            </h3>
            
            <!-- Message -->
            <p class="text-gray-600 text-center mb-8">
                Apakah Anda yakin ingin menghapus notifikasi ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <!-- Buttons -->
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition duration-200">
                    Batal
                </button>
                <button onclick="confirmDelete()" 
                        class="flex-1 px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition duration-200">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="successToast" class="hidden fixed top-6 right-6 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg z-50 flex items-center gap-3">
        <i class="fas fa-check-circle text-2xl"></i>
        <span class="font-semibold">Notifikasi berhasil dihapus!</span>
    </div>

    <!-- Error Toast -->
    <div id="errorToast" class="hidden fixed top-6 right-6 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg z-50 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-2xl"></i>
        <span class="font-semibold" id="errorMessage">Terjadi kesalahan!</span>
    </div>

    <script>
        let currentDeleteId = null;

        // Show delete modal
        function showDeleteModal(notifId) {
            currentDeleteId = notifId;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            // Add animation
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.add('scale-100');
            }, 10);
        }

        // Close delete modal
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.querySelector('.bg-white').classList.remove('scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                currentDeleteId = null;
            }, 200);
        }

        // Confirm delete
        async function confirmDelete() {
            if (!currentDeleteId) return;
            
            closeDeleteModal();
            await deleteNotification(currentDeleteId);
        }

        // Show toast notification
        function showToast(type, message = '') {
            const toast = document.getElementById(type === 'success' ? 'successToast' : 'errorToast');
            
            if (type === 'error' && message) {
                document.getElementById('errorMessage').textContent = message;
            }
            
            toast.classList.remove('hidden');
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Mark single notification as read
        async function markAsRead(notifId) {
            try {
                const response = await fetch('index.php?c=Notification&m=markAsRead', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_notif: notifId })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    const notifElement = document.querySelector(`[data-id="${notifId}"]`);
                    if (notifElement) {
                        notifElement.classList.add('opacity-60');
                        notifElement.setAttribute('data-status', 'read');
                        
                        // Remove blue dot
                        const blueDot = notifElement.querySelector('.bg-blue-500.rounded-full:not(.w-14)');
                        if (blueDot) {
                            blueDot.remove();
                        }
                    }

                    // Update badge
                    updateBadge(result.unreadCount);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Delete notification
        async function deleteNotification(notifId) {
            try {
                const response = await fetch('index.php?c=Notification&m=deleteNotification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_notif: notifId })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Show success toast
                    showToast('success');
                    
                    // Remove from UI with animation
                    const notifElement = document.querySelector(`[data-id="${notifId}"]`);
                    if (notifElement) {
                        notifElement.style.transition = 'opacity 0.3s, transform 0.3s';
                        notifElement.style.opacity = '0';
                        notifElement.style.transform = 'translateX(100%)';
                        
                        setTimeout(() => {
                            notifElement.remove();
                            
                            // Check if no notifications left
                            const remainingNotifs = document.querySelectorAll('.notif-item');
                            if (remainingNotifs.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }

                    // Update badge
                    updateBadge(result.unreadCount);
                } else {
                    showToast('error', result.message || 'Gagal menghapus notifikasi');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan saat menghapus notifikasi');
            }
        }

        // Update notification badge
        function updateBadge(count) {
            const badge = document.getElementById('notif-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        // Auto-refresh notification count every 30 seconds
        setInterval(async () => {
            try {
                const response = await fetch('index.php?c=Notification&m=getUnreadCount');
                const result = await response.json();
                
                if (result.success) {
                    updateBadge(result.count);
                }
            } catch (error) {
                console.error('Error refreshing notification count:', error);
            }
        }, 30000);

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>