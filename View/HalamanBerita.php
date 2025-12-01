<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Berita dan Edukasi</title>
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
                <a href="index.php?c=Berita&m=index" class="flex items-center space-x-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
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
<!--ISI-->
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
<!--KOLOM PENCARIAN-->
                <div class="mb-6">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Cari" 
                            class="w-full bg-blue-500 text-white placeholder-white px-6 py-4 rounded-full text-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            id="searchInput"
                            onkeyup="filterArticles()"
                        >
                    </div>
                </div>
<!--KATEGORI-->
               <div class="flex gap-3 mb-6 overflow-x-auto pb-2">
                <button onclick="filterCategory('all')" class="category-btn bg-white text-blue-600 px-6 py-2 rounded-full font-medium hover:bg-blue-100 transition whitespace-nowrap active">
                    Semua
                </button>
                <button onclick="filterCategory('kesehatan dan hidrasi')" class="category-btn bg-white text-blue-600 px-6 py-2 rounded-full font-medium hover:bg-blue-100 transition whitespace-nowrap">
                    Kesehatan dan Hidrasi
                </button>
                <button onclick="filterCategory('nutrisi dan gaya hidup sehat')" class="category-btn bg-white text-blue-600 px-6 py-2 rounded-full font-medium hover:bg-blue-100 transition whitespace-nowrap">
                    Nutrisi dan Gaya Hidup Sehat
                </button>
                <button onclick="filterCategory('teknologi & kesehatan digital')" class="category-btn bg-white text-blue-600 px-6 py-2 rounded-full font-medium hover:bg-blue-100 transition whitespace-nowrap">
                    Teknologi & Kesehatan Digital
                </button>
                <button onclick="filterCategory('edukasi dan fakta sains')" class="category-btn bg-white text-blue-600 px-6 py-2 rounded-full font-medium hover:bg-blue-100 transition whitespace-nowrap">
                    Edukasi dan Fakta Sains
                </button>
            </div>
<!--LIST ARTIKEL-->
                <div id="articlesList" class="space-y-5 <?= isset($detail) ? 'hidden' : '' ?>">
                <?php foreach ($berita as $b): ?> <!--$berita adalah array berisi daftar semua berita dari controller, foreach melakukan loop untuk tiap berita dan menampilkan card-nya-->
                    <a href="index.php?c=Berita&m=detail&id=<?= $b['id_berita'] ?>" class="block"> <!--setiap card adalah link, klo di klik akan diarahkan ke halaman dengan model dan controller tersebut-->
                        <div class="article-card bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition cursor-pointer"
                            data-category="<?= strtolower($b['kategori']) ?>"> <!--untuk kategorikan berita-->
                            <h3 class="text-lg text-[#4B5563] font-bold mb-3">
                                <?= $b['judul'] ?> <!--tampilkan judul berita-->
                            </h3>
                            <p class="text-xs text-[#4B5563] font-semibold mt-0">
                                Kategori : <?= $b['kategori'] ?> <!--tampilkan kategori berita-->
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
<!--ISI DETAIL ARTIKEL-->
               <?php if (isset($detail) && $detail['id_berita'] == 2): ?> <!--untuk menampilkan error saat berita kedua dipencet-->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showErrorModal();
                    });
                </script>
            <?php endif; ?>
            <div id="articleDetail" class="<?= isset($detail) ? '' : 'hidden' ?>"> <!--tampilkan detail artikel-->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <a href="index.php?c=Berita&m=index" 
                        class="mb-4 text-blue-600 hover:text-blue-800 flex items-center gap-2">
                        ← Kembali
                    </a> <!--balik ke halaman daftar berita-->
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-[#4B5563] mb-2">
                            <?= $detail['judul'] ?> <!--tampilkan judul artikel-->
                        </h2>
                        <p class="text-sm text-gray-600 mb-6 text-[#4B5563] font-bold">
                            Kategori : <?= $detail['kategori'] ?> <!--tampilkan kategori artikel-->
                        </p>
                    </div>
                    <div class="prose max-w-none space-y-4 text-[#4B5563] font-bold">
                        <?= nl2br($detail['isi']) ?> <!--tampilkan isi artikel-->
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>
<!--MODAL NOTIFIKASI (untuk buat bagian tampilan dan logika notifikasi)-->
    <div id="newsErrorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[1000]">
        <div class="bg-white rounded-lg max-w-md mx-4 text-center relative border-2 border-blue-500">
            <div class="p-6">
                <div class="w-16 h-16 bg-red-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Sistem gagal menampilkan berita dan edukasi silahkan coba lagi!</h3>
                <button onclick="closeModal()" class="bg-blue-500 text-white px-8 py-2 rounded-lg hover:bg-blue-600 transition">OK</button>
            </div>
        </div>
    </div>
<!--FUNGSI-->
    <script>
        function filterCategory(category) {  // untuk filter artikel berdasarkan kategori
            const articles = document.querySelectorAll('.article-card');
            const buttons = document.querySelectorAll('.category-btn');
            buttons.forEach(btn => btn.classList.remove('bg-blue-500'));
            event.target.classList.add('bg-white', 'text-blue-600');
            articles.forEach(article => {
                if (category === 'all' || article.dataset.category === category) {
                    article.style.display = 'block';
                } else {
                    article.style.display = 'none';
                }
            }); 
        }

        function filterArticles() { // untuk pencarian real time 
            const input = document.getElementById('searchInput').value.toLowerCase();
            const articles = document.querySelectorAll('.article-card');
            articles.forEach(article => {
                const title = article.querySelector('h3').textContent.toLowerCase();
                if (title.includes(input)) {
                    article.style.display = 'block';
                } else {
                    article.style.display = 'none';
                }
            });
        }

        function showErrorModal() { // tampilkan modal error
            document.getElementById('newsErrorModal').classList.remove('hidden');
        }

        function closeModal() { // tutup modal error
            document.getElementById('newsErrorModal').classList.add('hidden');
        }
    </script>
</body>
</html>
