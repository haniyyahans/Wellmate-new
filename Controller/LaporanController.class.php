<?php // ada 10 fungsi
require_once 'Controller/Controller.class.php';
require_once 'Model/LaporanModel.class.php';
require_once 'Model/UserModel.class.php';
require_once 'Model/BerandaModel.class.php';
require_once 'Model/NotificationModel.class.php';

class Laporan extends Controller
{
    private $laporanModel;
    private $targetMingguan;
    private $targetBulanan;
    // bagian notifikasi
    private $berandaModel;
    private $notificationModel;

    public function __construct() {
        // bagian notifikasi dan beranda
        $this->berandaModel = new BerandaModel();
        $this->notificationModel = new NotificationModel();
        // bagian laporan
        $this->laporanModel = new LaporanModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $targetHarian = $this->laporanModel->getTargetUser($_SESSION['id_akun']);
        $this->targetMingguan = $targetHarian * 7;   // target harian x 7 hari
        $this->targetBulanan = $targetHarian * 28;   // target harian x 28 hari (4 minggu)
    }

    public function index()
    {   
        // bagian notifikasi
        $idAkun = $this->getSession('id_akun');  // Ambil id_akun dari session
        $dataPengguna = $this->berandaModel->getDataPengguna($idAkun);  // Ambil data pengguna untuk mendapatkan id_pengguna
        $idPengguna = $dataPengguna ? $dataPengguna['id_pengguna'] : null;
        $unreadCount = $this->notificationModel->countUnread($idPengguna); // hitung notifikasi belum dibaca

        // menampilkan halaman utama laporan
        $id_pengguna = $_SESSION['id_akun'];
        if (!$this->laporanModel->cekDataRiwayat($id_pengguna)) {       // cek apakah user suudah ada data riwayat minum, kalau tidak tampilkan pemberitahuan dan hentikan
            echo "Tidak ada data riwayat minum. Silakan tambahkan data terlebih dahulu.";
            return;
        }
        $this->perbaruiLaporanOtomatis($id_pengguna);                   // selalu perbarui laporan terbaru
        $data = [                                                       // ambil data yang mau ditampilkan ke halaman
            'title' => 'Laporan dan Analisis',
            'laporan_mingguan' => $this->getDataLaporanMingguan($id_pengguna),
            'laporan_bulanan' => $this->getDataLaporanBulanan($id_pengguna),
            'unreadCount' => $unreadCount
        ];
        $this->view('HalamanLaporan', $data);
    }

    private function perbaruiLaporanOtomatis($id_pengguna)// agar laporannya selalu update
    { 
        $this->buatLaporanMingguan($id_pengguna); // selalu perbarui laporan mingguan dan bulanan dengan data terbaru
        $this->buatLaporanBulanan($id_pengguna);
    }

    public function buatLaporanMingguan($id_pengguna)
    { // buat/perbarui laporan mingguan
        $riwayatMingguan = $this->laporanModel->getRiwayatMingguan($id_pengguna); // ambil data riwayat minggu terakhir 
        if (empty($riwayatMingguan)) {
            return false;
        }
        $totalKonsumsi = 0;                                                       // hitung total konsumsi dengan menjumlah total_harian selama 1 minggu
        foreach ($riwayatMingguan as $riwayat) {
            $totalKonsumsi += $riwayat['total_harian'];
        }
        $targetMingguan = $this->targetMingguan;                                   // target mingguan uda ditentukan diatas, tpi kalau digabung diambil dari tabel lain
        $persentase = ($targetMingguan > 0) ? ($totalKonsumsi / $targetMingguan) * 100 : 0;   // hitung persentase
        $kategori = $this->tentukanKategori($persentase);                         // tentukan kategori pencapaian
        $analisis = $this->generateAnalisis($persentase, $kategori, 'minggu');    // tentukan analisis
        $rekomendasi = $this->generateRekomendasi($kategori, $persentase);        // tentukan rekomendasi rekomendasi
        $dataLaporan = [                                                          // Insert data laporan ke database
            'id_pengguna' => $id_pengguna,
            'jenis_laporan' => 'Mingguan',
            'periode' => 1,
            'jumlah_konsumsi' => $totalKonsumsi,
            'persentase' => round($persentase, 1),
            'kategori_pencapaian' => $kategori,
            'analisis_pencapaian' => $analisis,
            'rekomendasi' => $rekomendasi
        ];
        $laporanLama = $this->laporanModel->getLaporanTerakhir($id_pengguna, 'Mingguan'); // cek apakah sudah ada laporan mingguan, jika ada maka update, jika tidak insert
        if ($laporanLama) {
            return $this->laporanModel->updateLaporan($laporanLama['id_laporan'], $dataLaporan);
        } else {
            return $this->laporanModel->insertLaporan($dataLaporan);
        }
    }

    public function buatLaporanBulanan($id_pengguna)
    { // buat/perbarui laporan bulanan
        $riwayatBulanan = $this->laporanModel->getRiwayatBulanan($id_pengguna);   // ambil data riwayat bulan terakhir  
        if (empty($riwayatBulanan)) {
            return false;
        }
        $totalKonsumsi = 0;                                                       // hitung total konsumsi (hanya 4 minggu pertama), menjumlah total_mingguan selama 1 bulan
        $jumlahMinggu = min(4, count($riwayatBulanan));                           // maksimal 4 minggu
        for ($i = 0; $i < $jumlahMinggu; $i++) {
            $totalKonsumsi += $riwayatBulanan[$i]['total_mingguan'];
        }
        $targetBulanan = $this->targetBulanan;
        $persentase = ($targetBulanan > 0) ? ($totalKonsumsi / $targetBulanan) * 100 : 0; // hitung persentase
        $kategori = $this->tentukanKategori($persentase);                          // tentukan kategori pencapaian
        $analisis = $this->generateAnalisis($persentase, $kategori, 'bulan');      // tentukan analisis
        $rekomendasi = $this->generateRekomendasi($kategori, $persentase);         // tentukan rekomendasi
        $dataLaporan = [                                                           // Insert data laporan ke database
            'id_pengguna' => $id_pengguna,
            'jenis_laporan' => 'Bulanan',
            'periode' => 1,
            'jumlah_konsumsi' => $totalKonsumsi,
            'persentase' => round($persentase, 1),
            'kategori_pencapaian' => $kategori,
            'analisis_pencapaian' => $analisis,
            'rekomendasi' => $rekomendasi
        ];
        $laporanLama = $this->laporanModel->getLaporanTerakhir($id_pengguna, 'Bulanan'); // cek apakah sudah ada laporan bulanan, jika ada maka update, jika tidak insert
        if ($laporanLama) {
            return $this->laporanModel->updateLaporan($laporanLama['id_laporan'], $dataLaporan);
        } else {
            return $this->laporanModel->insertLaporan($dataLaporan);
        }
    }

    private function tentukanKategori($persentase)
    { // tentukan kategori pencapaian berdasarkan persentase
        if ($persentase >= 90) {
            return "Sangat Baik";
        } elseif ($persentase >= 75) {
            return "Baik";
        } elseif ($persentase >= 50) {
            return "Cukup";
        } else {
            return "Kurang";
        }
    }

    private function generateAnalisis($persentase, $kategori, $periode)
    { // tentukan analisis pencapaian berdasarkan kategori
        $persentaseFormat = number_format($persentase, 1);
        if ($kategori == "Sangat Baik") {
            return "Kamu masuk kategori \"Sangat Baik\", target hampir sepenuhnya tercapai dan tubuhmu kemungkinan terhidrasi dengan baik.";
        } elseif ($kategori == "Baik") {
            return "Pencapaian kamu termasuk \"Baik\" dengan persentase {$persentaseFormat}%. Tubuhmu sudah cukup terhidrasi.";
        } elseif ($kategori == "Cukup") {
            return "Pencapaian kamu \"Cukup\" dengan persentase {$persentaseFormat}%. Perlu sedikit peningkatan untuk hidrasi optimal.";
        } else {
            return "Pencapaian kamu masih \"Kurang\" dengan persentase {$persentaseFormat}%. Tubuhmu mungkin kurang terhidrasi.";
        }
    }

    private function generateRekomendasi($kategori, $persentase)
    { // tentukan rekomendasi pola minum berdasarkan kategori
        if ($kategori == "Sangat Baik") {
            return "Selalu semangat dalam mempertahankan pola ini. Boleh tambah variasi (air infused, jus tanpa gula) untuk menjaga motivasi.";
        } elseif ($kategori == "Baik") {
            return "Pertahankan kebiasaan baik ini! Coba tingkatkan sedikit lagi untuk mencapai target optimal.";
        } elseif ($kategori == "Cukup") {
            return "Tingkatkan konsumsi air secara bertahap. Set reminder di ponsel agar tidak lupa minum secara teratur.";
        } else {
            return "Kamu perlu meningkatkan konsumsi air secara signifikan. Mulai dengan target kecil dan tingkatkan perlahan. Konsultasi dengan dokter jika diperlukan.";
        }
    }

    private function getDataLaporanMingguan($id_pengguna)
    { // mengambil dan menyiapkan data laporan mingguan yang akan dikirim ke view
        $riwayat = $this->laporanModel->getRiwayatMingguan($id_pengguna);             // data konsumsi harian selama 7 hari terakhir.
        $laporan = $this->laporanModel->getLaporanTerakhir($id_pengguna, 'Mingguan'); // laporan mingguan terakhir yang sudah tersimpan (jika ada)
        $hariIndo = [                                                                 // konversi inggris ke indo, format data untuk chart
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        $dataPerHari = [];
        $totalKonsumsi = 0;
        foreach ($riwayat as $data) {
            $hari = $hariIndo[$data['hari']] ?? $data['hari'];
            $dataPerHari[] = [
                'hari' => $hari,
                'jumlah' => $data['total_harian']
            ];
            $totalKonsumsi += $data['total_harian'];                                   // hitung total konsumsi dalam 1 minggu
        }
        $targetMingguan = $this->targetMingguan;
        $persentase = ($targetMingguan > 0) ? ($totalKonsumsi / $targetMingguan) * 100 : 0; // hitung persentase pencapaian target
        return [                                                                        // return data lengkap untuk view
            'data_per_hari' => $dataPerHari,
            'total_konsumsi' => $laporan['jumlah_konsumsi'] ?? $totalKonsumsi,
            'target_konsumsi' => $targetMingguan,
            'persentase' => $laporan['persentase'] ?? round($persentase, 1),
            'kategori' => $laporan['kategori_pencapaian'] ?? '-',
            'analisis' => $laporan['analisis_pencapaian'] ?? '-',
            'rekomendasi' => $laporan['rekomendasi'] ?? '-'
        ];
    }

    private function getDataLaporanBulanan($id_pengguna)
    { // mengambil dan menyiapkan data laporan bulanan yang akan dikirim ke view
        $riwayat = $this->laporanModel->getRiwayatBulanan($id_pengguna);               // logicnya sama seperti mingguan
        $laporan = $this->laporanModel->getLaporanTerakhir($id_pengguna, 'Bulanan');
        $dataPerMinggu = [];                                                           // Format data untuk chart - HANYA 4 MINGGU
        $totalKonsumsi = 0;
        $jumlahMinggu = min(4, count($riwayat));                                       // maksimal 4 minggu
        for ($i = 0; $i < $jumlahMinggu; $i++) {
            $dataPerMinggu[] = [
                'minggu' => 'Minggu ke-' . ($i + 1),
                'jumlah' => $riwayat[$i]['total_mingguan']
            ];
            $totalKonsumsi += $riwayat[$i]['total_mingguan'];
        }
        $targetBulanan = $this->targetBulanan;
        $persentase = ($targetBulanan > 0) ? ($totalKonsumsi / $targetBulanan) * 100 : 0;
        return [
            'data_per_minggu' => $dataPerMinggu,
            'total_konsumsi' => $laporan['jumlah_konsumsi'] ?? $totalKonsumsi,
            'target_konsumsi' => $targetBulanan,
            'persentase' => $laporan['persentase'] ?? round($persentase, 1),
            'kategori' => $laporan['kategori_pencapaian'] ?? '-',
            'analisis' => $laporan['analisis_pencapaian'] ?? '-',
            'rekomendasi' => $laporan['rekomendasi'] ?? '-'
        ];
    }
}