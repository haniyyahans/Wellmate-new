<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Minum</title>
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
    <style>
        .donut-chart {
            transform: rotate(-90deg);
        }
    </style>
</head>
<body class="bg-abu-terang text-hitam-gelap font-sans m-0 p-0">
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
                <a href="index.php?c=Tracking&m=index" class="flex items-center space-x-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
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
            
            <main class="flex-1 overflow-y-auto bg-blue-100 p-6">
                <!-- Banner & Summary Wrapper -->
                <div class="mb-5 rounded-xl overflow-hidden bg-transparent">
                    <div class="bg-biru-primary text-white p-5 rounded-xl mb-0">
                        <h1 class="m-0 mb-[5px] text-[1.8em] font-bold">
                            Selamat Datang, <?php echo htmlspecialchars($namaPengguna ?? 'Pengguna'); ?>!
                        </h1>
                        <p class="m-0">Mari jaga hidrasi tubuh Anda hari ini</p>
                    </div>
                    
                    <div class="bg-transparent py-5 px-0 mb-0">
                        <div class="flex gap-[15px] justify-between" id="summary-cards">
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="bg-white p-5 rounded-xl mb-5 shadow-sm">
                    <div class="flex justify-between items-center mb-[15px]">
                        <h3 class="m-0 font-bold">Progress Hidrasi Hari Ini</h3>
                        <a href="#" id="show-detail-btn" class="py-2 px-[15px] border border-biru-primary rounded-lg cursor-pointer font-bold text-sm bg-transparent text-biru-primary no-underline">Lihat Detail</a>
                    </div>
                    
                    <div class="flex items-center justify-around text-center">
                        <div class="relative w-[150px] h-[150px] mr-10" id="main-chart-visual">
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-hitam-gelap">
                                <span id="progress-percentage">0%</span>
                                <small class="text-[0.5em] font-normal block -mt-[5px] text-abu-medium">Tercapai</small>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-5 mt-5">
                        <div class="p-[15px] rounded-lg text-center flex-1 font-bold bg-biru-muda text-biru-primary">
                            <span id="progress-consumed">0ml</span>
                            <span class="font-normal text-sm block mt-[5px]">Diminum Hari Ini</span>
                        </div>
                        <div class="p-[15px] rounded-lg text-center flex-1 font-bold bg-[#FFEEEE] text-[#FF4500]">
                            <span id="progress-remaining">0ml</span>
                            <span class="font-normal text-sm block mt-[5px]">Masih Perlu</span>
                        </div>
                    </div>
                </div>

                <!-- Tracking List -->
                <div class="bg-white p-5 rounded-xl mb-5 shadow-sm">
                    <div class="flex justify-between items-center mb-[15px]">
                        <h3 class="m-0 font-bold">Tracking Konsumsi Hari Ini</h3>
                        <button id="add-drink-btn" class="py-2 px-[15px] border-none rounded-lg cursor-pointer font-bold text-sm bg-biru-primary text-white w-auto">+ Tambah Minum</button>
                    </div>

                    <div class="bg-white p-2.5 rounded-xl shadow-sm">
                        <div id="drink-list-container">
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Modal Form -->
    <div id="drink-form-modal" class="hidden fixed top-0 left-0 w-full h-full bg-black/50 justify-center items-center z-[1000]">
        <div class="bg-white p-[30px] rounded-xl w-[90%] max-w-[450px] shadow-xl">
            <h3 id="form-title" class="mt-0 text-biru-primary border-b-2 border-biru-muda pb-2.5 mb-5">Tambah Catatan Minum</h3>
            <form id="drink-form">
                <input type="hidden" id="edit-id">
                <div class="mb-[15px]">
                    <label for="drink-type" class="block mb-[5px] font-bold">Jenis Minuman</label>
                    <select id="drink-type" required class="w-full p-2.5 border border-abu-medium rounded-lg box-border text-base">
                    </select>
                </div>
                <div class="mb-[15px]">
                    <label for="drink-amount" class="block mb-[5px] font-bold">Jumlah (ml)</label>
                    <input type="number" id="drink-amount" placeholder="250" required min="1" class="w-full p-2.5 border border-abu-medium rounded-lg box-border text-base">
                </div>
                <div class="mb-[15px]">
                    <label for="drink-time" class="block mb-[5px] font-bold">Waktu</label>
                    <input type="text" id="drink-time" placeholder="15:30" required class="w-full p-2.5 border border-abu-medium rounded-lg box-border text-base">
                </div>
                <button type="submit" class="py-2.5 px-5 border-none rounded-lg cursor-pointer font-bold text-base bg-biru-primary text-white w-full mt-[15px]">Simpan</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Status -->
    <div id="status-modal" class="hidden fixed top-0 left-0 w-full h-full bg-black/50 justify-center items-center z-[1000]">
        <div class="bg-white p-5 rounded-xl w-[90%] max-w-[450px] shadow-xl text-center">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4" id="status-icon">
                <i class="fas fa-check text-white text-3xl"></i>
            </div>
            <p class="mb-5 text-lg" id="status-message">Catatan Berhasil Disimpan!</p>
            <button class="py-2.5 px-5 border-none rounded-lg cursor-pointer font-bold text-base bg-biru-primary text-white" id="status-ok">OK</button>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div id="confirm-delete-modal" class="hidden fixed top-0 left-0 w-full h-full bg-black/50 justify-center items-center z-[1000]">
        <div class="bg-white p-5 rounded-xl w-[90%] max-w-[400px] shadow-xl text-center">
            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Konfirmasi Hapus</h3>
            <p class="mb-5 text-gray-600">Apakah Anda yakin ingin menghapus catatan ini?</p>
            <div class="flex gap-3">
                <button id="confirm-delete-cancel" class="flex-1 py-2.5 px-5 border border-gray-300 rounded-lg cursor-pointer font-bold text-base bg-white text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button id="confirm-delete-ok" class="flex-1 py-2.5 px-5 border-none rounded-lg cursor-pointer font-bold text-base bg-red-500 text-white hover:bg-red-600">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail -->
    <div id="detail-modal" class="hidden fixed top-0 left-0 w-full h-full bg-black/50 justify-center items-center z-[1000]">
        <div class="bg-white p-[30px] rounded-xl w-[90%] max-w-[450px] shadow-xl">
            <h3 class="mt-0 border-none">Detail Konsumsi Hari Ini</h3>
            <div class="flex items-center justify-around text-center flex-col">
                <div class="relative w-[200px] h-[200px] my-0 mx-auto" id="detail-chart-visual">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[2.2em] font-bold text-hitam-gelap">
                        <span id="detail-progress-percentage">0%</span>
                        <small class="text-[0.5em] font-normal block -mt-[5px] text-abu-medium">Tercapai</small>
                    </div>
                </div>
                <ul class="list-none p-0 mt-5 text-[0.95em]" id="detail-list-breakdown">
                </ul>
                <p class="text-center mt-5 font-bold leading-relaxed">Kamu perlu minum <span class="text-biru-primary" id="detail-remaining-amount">0ml</span> lagi untuk mencapai target hidrasi hari ini. Semangat!</p>
            </div>
            <button class="py-2.5 px-5 border-none rounded-lg cursor-pointer font-bold text-base bg-biru-primary text-white w-full mt-[15px]" id="detail-back-btn">Kembali</button>
        </div>
    </div>

    <script>
        // Data dari PHP
        const TARGET_HARIAN = <?php echo $targetHarian; ?>;
        const JENIS_MINUMAN = <?php echo json_encode($jenisMinuman); ?>;
        let catatanMinuman = <?php echo json_encode($catatanMinum); ?>;
        const DATA_PENGGUNA = <?php echo json_encode($dataPengguna); ?>;
        
        function formatVolume(ml) {
            if (ml >= 1000) {
                return (ml / 1000).toFixed(2).replace(/\.?0+$/, '') + 'L';
            }
            return ml + 'ml';
        }

        function buatDonutChart(statistikMinuman, totalDiminum, ukuran = 150) {
            const radius = ukuran / 2.5;
            const keliling = 2 * Math.PI * radius;
            let offsetSaatIni = 0;
            let total = 0;
            
            for (const key in statistikMinuman) {
                total += statistikMinuman[key].jumlah;
            }
            
            const sisa = TARGET_HARIAN - total;
            const dataChart = [];

            for (const key in statistikMinuman) {
                const persentase = (statistikMinuman[key].jumlah / TARGET_HARIAN) * 100;
                dataChart.push({ persentase: persentase, warna: statistikMinuman[key].warna });
            }

            if (sisa > 0) {
                const persentaseSisa = (sisa / TARGET_HARIAN) * 100;
                dataChart.push({ persentase: persentaseSisa, warna: '#D3D3D3' });
            }
            
            const lingkaran = dataChart.map(data => {
                const dashArray = `${(data.persentase / 100) * keliling} ${keliling}`;
                const dashOffset = offsetSaatIni;
                offsetSaatIni -= ((data.persentase / 100) * keliling);
                
                return `<circle class="donut-segment" cx="${ukuran / 2}" cy="${ukuran / 2}" r="${radius}" 
                            fill="transparent" 
                            stroke="${data.warna}" 
                            stroke-width="25" 
                            stroke-dasharray="${dashArray}" 
                            stroke-dashoffset="${dashOffset}" />`;
            }).join('');

            return `<svg class="donut-chart" width="${ukuran}" height="${ukuran}" viewBox="0 0 ${ukuran} ${ukuran}">${lingkaran}</svg>`;
        }
        
        function perbaruiSemuaUI() {
            const totalDiminum = catatanMinuman.reduce((sum, record) => sum + parseInt(record.jumlah), 0);
            const sisa = Math.max(0, TARGET_HARIAN - totalDiminum);
            const sisaFormattedL = formatVolume(sisa);
            const persentaseProgress = Math.min(100, (totalDiminum / TARGET_HARIAN) * 100);
            
            const statistikMinuman = {};
            JENIS_MINUMAN.forEach(d => statistikMinuman[d.nama] = { jumlah: 0, warna: d.warna, persentase: 0 });
            
            catatanMinuman.forEach(record => {
                if (statistikMinuman[record.jenis]) {
                    statistikMinuman[record.jenis].jumlah += parseInt(record.jumlah);
                }
            });
            
            let statistikUntukChart = {};
            for (const jenis in statistikMinuman) {
                if (statistikMinuman[jenis].jumlah > 0) {
                    statistikMinuman[jenis].persentase = Math.round((statistikMinuman[jenis].jumlah / TARGET_HARIAN) * 100);
                    statistikUntukChart[jenis] = statistikMinuman[jenis];
                }
            }

            // Update Summary Cards
            document.getElementById('summary-cards').innerHTML = `
                <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                    <div class="text-[0.85em] text-abu-medium leading-tight">Target Harian</div>
                    <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                        <span>${formatVolume(TARGET_HARIAN)}</span>
                        <div class="w-[30px] h-[30px] bg-biru-muda rounded-full flex items-center justify-center text-biru-primary text-base">🎯</div>
                    </div>
                    <div class="text-[0.85em] text-abu-medium leading-tight">Capai target hidrasi Anda!</div>
                </div>
                <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                    <div class="text-[0.85em] text-abu-medium leading-tight">Sudah diminum</div>
                    <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                        <span>${formatVolume(totalDiminum)}</span>
                        <div class="w-[30px] h-[30px] bg-biru-muda rounded-full flex items-center justify-center text-biru-primary text-base">💧</div>
                    </div>
                    <div class="text-[0.85em] text-abu-medium leading-tight">dari ${formatVolume(TARGET_HARIAN)}, target</div>
                </div>
                <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                    <div class="text-[0.85em] text-abu-medium leading-tight">Sisa Target</div>
                    <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                        <span>${sisaFormattedL}</span>
                        <div class="w-[30px] h-[30px] bg-biru-muda rounded-full flex items-center justify-center text-biru-primary text-base">⏳</div>
                    </div>
                    <div class="text-[0.85em] text-abu-medium leading-tight">dari ${formatVolume(TARGET_HARIAN)}, target</div>
                </div>
                <div class="bg-white p-[15px] rounded-xl flex-1 shadow-sm text-sm">
                    <div class="text-[0.85em] text-abu-medium leading-tight">Progres</div>
                    <div class="text-[1.8em] font-bold text-biru-primary my-[5px] mx-0 flex items-center justify-between leading-none">
                        <span>${Math.round(persentaseProgress)}%</span>
                        <div class="w-[30px] h-[30px] bg-biru-muda rounded-full flex items-center justify-center text-biru-primary text-base">📈</div>
                    </div>
                    <div class="text-[0.85em] text-abu-medium leading-tight">dari ${formatVolume(TARGET_HARIAN)}, target</div>
                </div>
            `;
            
            document.getElementById('progress-consumed').textContent = formatVolume(totalDiminum);
            document.getElementById('progress-remaining').textContent = sisaFormattedL;
            document.getElementById('progress-percentage').textContent = `${Math.round(persentaseProgress)}%`;
            
            document.getElementById('main-chart-visual').innerHTML = buatDonutChart(statistikUntukChart, totalDiminum) 
                + `<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-hitam-gelap"><span id="progress-percentage">${Math.round(persentaseProgress)}%</span><small class="text-[0.5em] font-normal block -mt-[5px] text-abu-medium">Tercapai</small></div>`;

            document.getElementById('detail-progress-percentage').textContent = `${Math.round(persentaseProgress)}%`;
            document.getElementById('detail-remaining-amount').textContent = sisaFormattedL;
            document.getElementById('detail-chart-visual').innerHTML = buatDonutChart(statistikUntukChart, totalDiminum, 200) 
                + `<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[2.2em] font-bold text-hitam-gelap"><span id="detail-progress-percentage">${Math.round(persentaseProgress)}%</span><small class="text-[0.5em] font-normal block -mt-[5px] text-abu-medium">Tercapai</small></div>`;

            const breakdownHtml = Object.entries(statistikUntukChart).map(([jenis, data]) => `
                <li class="flex justify-between py-[5px] border-b border-dashed border-gray-200">
                    <div><span class="inline-block w-2.5 h-2.5 mr-2 rounded" style="background-color: ${data.warna};"></span>${jenis}</div>
                    <strong>${data.persentase}% (${formatVolume(data.jumlah)})</strong>
                </li>
            `).join('');
            document.getElementById('detail-list-breakdown').innerHTML = breakdownHtml;

            const listKontainer = document.getElementById('drink-list-container');
            const listHtml = catatanMinuman.map(record => {
                const infoMinuman = JENIS_MINUMAN.find(d => d.nama === record.jenis) || { ikon: '❓', warna: '#151515' };
                return `
                    <div class="flex items-center p-2.5 border-b border-gray-200 last:border-b-0" data-id="${record.id}">
                        <div class="text-2xl mr-[15px] w-[30px] text-center" style="color: ${infoMinuman.warna};">${infoMinuman.ikon}</div>
                        <div class="flex-grow">
                            <strong>${record.jenis}</strong>
                            <div class="text-xs text-abu-medium">${record.waktu}  - ${formatVolume(record.jumlah)}</div>
                        </div>
                        <div>
                            <button class="edit-btn bg-transparent border-none cursor-pointer text-base text-biru-primary ml-2.5 p-[5px]" data-id="${record.id}"><span>✎</span></button>
                            <button class="delete-btn bg-transparent border-none cursor-pointer text-base ml-2.5 p-[5px]" data-id="${record.id}"><span style="color: #FF4500;"><i class="fas fa-trash text-xl"></i></span></button>
                        </div>
                    </div>
                `;
            }).join('');
            
            listKontainer.innerHTML = listHtml;
            pasangListenerList();
        }
        
        function tampilkanModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function sembunyikanModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function resetForm() {
            document.getElementById('drink-form').reset();
            document.getElementById('edit-id').value = '';
            document.getElementById('form-title').textContent = 'Tambah Catatan Minum';
        }
        
        document.getElementById('drink-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit-id').value;
            const jenis = document.getElementById('drink-type').value;
            const jumlah = parseInt(document.getElementById('drink-amount').value);
            const waktu = document.getElementById('drink-time').value;
            
            const formData = new FormData();
            formData.append('jenis', jenis);
            formData.append('jumlah', jumlah);
            formData.append('waktu', waktu);
            
            let url = 'index.php?c=Tracking&m=tambah';
            if (id) {
                formData.append('id', id);
                url = 'index.php?c=Tracking&m=update';
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    sembunyikanModal('drink-form-modal');
                    document.getElementById('status-message').textContent = result.message;
                    tampilkanModal('status-modal');
                    resetForm();
                    
                    // Reload data
                    location.reload();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menyimpan data');
            }
        });

        document.getElementById('add-drink-btn').addEventListener('click', function() {
            resetForm();
            tampilkanModal('drink-form-modal');
        });
        
        document.getElementById('show-detail-btn').addEventListener('click', function(e) {
            e.preventDefault();
            tampilkanModal('detail-modal');
        });
        
        document.getElementById('detail-back-btn').addEventListener('click', function() {
            sembunyikanModal('detail-modal');
        });
        
        document.getElementById('status-ok').addEventListener('click', function() {
            sembunyikanModal('status-modal');
        });
        
        function pasangListenerList() {
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    const record = catatanMinuman.find(r => r.id == id);
                    
                    if (record) {
                        document.getElementById('form-title').textContent = 'Perbarui Catatan Minum';
                        document.getElementById('edit-id').value = record.id;
                        document.getElementById('drink-type').value = record.jenis;
                        document.getElementById('drink-amount').value = record.jumlah;
                        document.getElementById('drink-time').value = record.waktu;
                        tampilkanModal('drink-form-modal');
                    }
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = parseInt(this.dataset.id);
            
                    // Simpan ID yang akan dihapus
                    document.getElementById('confirm-delete-ok').dataset.deleteId = id;
                    
                    // Tampilkan modal konfirmasi
                    tampilkanModal('confirm-delete-modal');
                });
            });
        }

        // Handler untuk tombol Hapus di modal konfirmasi delete
        document.getElementById('confirm-delete-ok').addEventListener('click', async function() {
            const id = parseInt(this.dataset.deleteId);
            
            // Tutup modal konfirmasi
            sembunyikanModal('confirm-delete-modal');
            
            const formData = new FormData();
            formData.append('id', id);
            
            try {
                const response = await fetch('index.php?c=Tracking&m=hapus', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Tampilkan modal sukses
                    document.getElementById('status-message').textContent = result.message || 'Catatan berhasil dihapus!';
                    tampilkanModal('status-modal');
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menghapus data');
            }
        });

        // Update handler status-ok untuk reload setelah sukses hapus/tambah/edit
        document.getElementById('status-ok').addEventListener('click', function() {
            sembunyikanModal('status-modal');
            location.reload(); // Reload setelah user klik OK
        });

        // Handler untuk tombol Batal di modal konfirmasi delete
        document.getElementById('confirm-delete-cancel').addEventListener('click', function() {
            sembunyikanModal('confirm-delete-modal');
        });
        
        function isiDropdownMinuman() {
            const select = document.getElementById('drink-type');
            select.innerHTML = JENIS_MINUMAN.map(d => `<option value="${d.nama}">${d.nama}</option>`).join('');
        }
        
        function init() {
            isiDropdownMinuman();
            perbaruiSemuaUI();
        }
        
        init();
    </script>
</body>
</html>