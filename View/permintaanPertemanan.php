<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Permintaan Pertemanan</title>
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

            <main class="flex-1 overflow-y-auto">
                <!-- Content Area -->
                <div class="p-8 bg-blue-100 min-h-full">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <!-- Page Title and Actions -->
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="text-4xl font-bold text-gray-600">Permintaan Pertemanan</h1>
                            <div class="flex space-x-4">
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

                        <!-- Friend Requests List -->
                        <div class="space-y-4" id="requestsList">
                            <?php if (!empty($requests)): ?>
                                <?php foreach ($requests as $request): 
                                    // $request['id_teman'] adalah ID baris pertemanan di tabel 'teman'
                                    // $request['nama_pengirim'] adalah nama pengguna yang mengirim permintaan
                                    // $request['username_pengirim'] adalah username pengirim
                                ?>
                                    <div class="request-item flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-pink-200 rounded-full flex items-center justify-center text-pink-800 font-bold">
                                                <?= strtoupper(substr($request['nama_pengirim'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($request['nama_pengirim']) ?></h3>
                                                <p class="text-sm text-gray-500">@<?= htmlspecialchars($request['username_pengirim']) ?></p>
                                                <p class="text-xs text-gray-400 mt-1">Mengirim permintaan pada: <?= date('d M Y', strtotime($request['tanggal'])) ?></p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <!-- <form action="index.php?c=Friend&m=acceptRequest" method="POST"> -->
                                                <button type="button" 
                                                        class="confirm-btn bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-150 text-sm"
                                                        data-id-teman="<?= $request['id_teman'] ?>">
                                                    <i class="fas fa-check"></i> Terima
                                                </button>
                                            <!-- </form> -->
                                            
                                            <!-- <form action="index.php?c=Friend&m=declineRequest" method="POST"> -->
                                                <button type="button" 
                                                        class="delete-btn bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150 text-sm"
                                                        data-id-teman="<?= $request['id_teman'] ?>">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            <!-- </form> -->
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div id="emptyState" class="text-center py-10 text-gray-500">
                                    <i class="fas fa-handshake-slash text-4xl mb-3"></i>
                                    <p class="text-lg">Tidak ada permintaan pertemanan yang masuk.</p>
                                </div>
                            <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Tolak Permintaan?</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menolak permintaan pertemanan dari <span id="friendNameToDelete" class="font-semibold"></span>?</p>
                <div class="flex space-x-3">
                    <button id="cancelDelete" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Batal
                    </button>
                    <button id="confirmDelete" class="flex-1 px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Confirm -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Terima Permintaan?</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menerima permintaan pertemanan dari <span id="friendNameToConfirm" class="font-semibold"></span>?</p>
                <div class="flex space-x-3">
                    <button id="cancelConfirm" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Batal
                    </button>
                    <button id="confirmAccept" class="flex-1 px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                        Terima
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let requestToDelete = null;
        let requestToConfirm = null;

        // Delete button functionality
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                requestToDelete = this.closest('.request-item');
                const friendName = requestToDelete.querySelector('h3').textContent;
                
                // Show modal
                document.getElementById('friendNameToDelete').textContent = friendName;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        // Confirm button functionality
        document.querySelectorAll('.confirm-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                requestToConfirm = this.closest('.request-item');
                const friendName = requestToConfirm.querySelector('h3').textContent;
                
                // Show modal
                document.getElementById('friendNameToConfirm').textContent = friendName;
                document.getElementById('confirmModal').classList.remove('hidden');
            });
        });

        // Cancel delete
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            requestToDelete = null;
        });

        // Cancel confirm
        document.getElementById('cancelConfirm').addEventListener('click', function() {
            document.getElementById('confirmModal').classList.add('hidden');
            requestToConfirm = null;
        });

        // Confirm delete (reject request)
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (requestToDelete) {
                const idTeman = requestToDelete.querySelector('.delete-btn').getAttribute('data-id-teman');
                const friendName = requestToDelete.querySelector('h3').textContent;
                
                // Kirim request untuk tolak
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?c=Friend&m=rejectRequest';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_teman';
                input.value = idTeman;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Confirm accept (accept request)
        document.getElementById('confirmAccept').addEventListener('click', function() {
            if (requestToConfirm) {
                const idTeman = requestToConfirm.querySelector('.confirm-btn').getAttribute('data-id-teman');
                const friendName = requestToConfirm.querySelector('h3').textContent;
                
                // Kirim request untuk terima
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?c=Friend&m=acceptRequest';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_teman';
                input.value = idTeman;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Close modals when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                requestToDelete = null;
            }
        });

        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                requestToConfirm = null;
            }
        });

        // Update badge count
        function updateBadgeCount() {
            const remainingRequests = document.querySelectorAll('.request-item').length;
            const badge = document.getElementById('requestBadge');
            
            if (remainingRequests > 0) {
                badge.textContent = remainingRequests;
            } else {
                badge.style.display = 'none';
            }
        }

        // Check if requests list is empty
        function checkEmptyState() {
            const requestsList = document.getElementById('requestsList');
            const emptyState = document.getElementById('emptyState');
            const requestItems = requestsList.querySelectorAll('.request-item');
            
            if (requestItems.length === 0) {
                requestsList.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
            
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white font-semibold z-50 transform transition-all duration-300 ${bgColor}`;
            notification.style.transform = 'translateY(-100px)';
            notification.style.opacity = '0';
            notification.innerHTML = `
                <i class="fas ${icon} mr-2"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateY(0)';
                notification.style.opacity = '1';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateY(-100px)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
