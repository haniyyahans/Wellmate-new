<?php
require_once 'Controller.class.php'; 
require_once 'Model/BerandaModel.class.php';
require_once 'Model/TrackingModel.class.php';
require_once 'Model/BeritaModel.class.php';

class Beranda extends Controller { 
    private $model;
    private $trackingModel;
    private $beritaModel;
    
    public function __construct() {
        $this->model = new BerandaModel();
        $this->trackingModel = new TrackingModel();
        $this->beritaModel = new BeritaModel();
    }

    public function index() {
        session_start();
        $id_pengguna = $_SESSION['id_pengguna'] ?? 1;
        
        // Ambil data pengguna dari database
        $dataPengguna = $this->model->getDataPengguna($id_pengguna);
        
        // Ambil data progres hidrasi
        $progressData = $this->model->getProgressHidrasi($id_pengguna);
        
        // Ambil berita terkini (3 berita terbaru)
        $this->model('BeritaModel');
        $beritaModel = new BeritaModel();
        $beritaTerkini = array_slice($beritaModel->getAllBerita(), 0, 3);
        
        // Ambil jumlah notifikasi belum dibaca
        $this->model('NotificationModel');
        $notificationModel = new NotificationModel();
        $unreadCount = $notificationModel->countUnread($id_pengguna);
        
        // Kirim data ke view
        $this->view('HalamanBeranda', [
            'dataPengguna' => $dataPengguna,
            'progressData' => $progressData,
            'beritaTerkini' => $beritaTerkini,
            'unreadCount' => $unreadCount 
        ]);
    }

    public function tambahBiodata() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diizinkan. Gunakan POST.'
            ]);
            return;
        }
        
        session_start();
        $id_pengguna = $_SESSION['id_pengguna'] ?? 1;
        
        $data = [
            'id_pengguna' => $id_pengguna,
            'nama' => isset($_POST['nama']) ? $_POST['nama'] : null,
            'berat_badan' => isset($_POST['berat_badan']) ? $_POST['berat_badan'] : null,
            'usia' => isset($_POST['usia']) ? $_POST['usia'] : null
        ];
        
        if (!$data['nama'] || !$data['berat_badan']) {
            echo json_encode([
                'success' => false,
                'message' => 'Parameter nama dan berat_badan wajib diisi'
            ]);
            return;
        }
        
        $hasil = $this->model->tambahBiodata($data);
        echo json_encode($hasil);
    }

    public function updateBiodata() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diizinkan. Gunakan POST.'
            ]);
            return;
        }
        
        session_start();
        $id_pengguna = $_SESSION['id_pengguna'] ?? 1;
        
        $data = [
            'nama' => isset($_POST['nama']) ? $_POST['nama'] : null,
            'berat_badan' => isset($_POST['berat_badan']) ? $_POST['berat_badan'] : null,
            'usia' => isset($_POST['usia']) ? $_POST['usia'] : null
        ];
        
        $hasil = $this->model->updateBiodata($id_pengguna, $data);
        echo json_encode($hasil);
    }

    public function hitungKebutuhanCairan() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diizinkan. Gunakan POST.'
            ]);
            return;
        }
        
        $beratBadan = isset($_POST['berat_badan']) ? $_POST['berat_badan'] : null;
        $usia = isset($_POST['usia']) ? $_POST['usia'] : null;
        
        if ($beratBadan === null) {
            echo json_encode([
                'success' => false,
                'message' => 'Parameter berat_badan tidak ditemukan'
            ]);
            return;
        }
        
        $hasil = $this->model->hitungTargetMinumHarian($beratBadan, $usia);
        echo json_encode($hasil);
    }
}