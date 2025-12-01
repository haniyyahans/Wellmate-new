<?php

class Saran extends Controller {
    private $saranModel;
    private $berandaModel;
    private $notificationModel;
    
    public function __construct() {
        $this->saranModel = $this->model('SaranModel');
        $this->berandaModel = $this->model('BerandaModel');
        $this->notificationModel = $this->model('NotificationModel');
        $this->startSession();

        if (!$this->getSession('id_akun')) {
            $this->setSession('id_akun', 1);
        }
    }
    
    // Method default untuk menampilkan halaman saran aktivitas
    public function index() {
        try {
            $idAkun = $this->getSession('id_akun');
            
            $dataPengguna = $this->berandaModel->getDataPengguna($idAkun);
            $namaPengguna = $dataPengguna ? $dataPengguna['nama'] : 'Pengguna';

            // Ambil id_pengguna dari data pengguna
            $idPengguna = $dataPengguna ? $dataPengguna['id_pengguna'] : null;

            // Get data yang diperlukan
            $aktivitas = $this->saranModel->getAllAktivitas();
            $targetHarian = $this->saranModel->getTargetHarian($idAkun);
            $statistik = $this->saranModel->getStatistikHariIni($idAkun);
            $unreadCount = $this->notificationModel->countUnread($idPengguna);
            
            $data = [
                'aktivitas' => $aktivitas,
                'targetHarian' => $targetHarian,
                'statistik' => $statistik,
                'namaPengguna' => $namaPengguna,
                'dataPengguna' => $dataPengguna,
                'unreadCount' => $unreadCount
            ];
            
            $this->view('saran', $data);

        } catch (Exception $e) {
            $this->view('saran', [
                'aktivitas' => [],
                'targetHarian' => 0,
                'statistik' => ['total_diminum' => 0, 'jumlah_catatan' => 0],
                'namaPengguna' => 'Pengguna',
                'dataPengguna' => null,
                'unreadCount' => 0
            ]);
        }
    }
    
    // Method untuk mendapatkan semua aktivitas via AJAX
    public function getAllAktivitas() {
        try {
            $aktivitas = $this->saranModel->getAllAktivitas();
            
            $this->jsonResponse([
                'success' => true,
                'data' => $aktivitas
            ]);

        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Gagal mengambil data aktivitas'
            ]);
        }
    }
    
    // Method untuk mendapatkan detail aktivitas by ID via AJAX
    public function getAktivitasDetail() {
        try {
            $id = $_GET['id'] ?? '';
            
            if (empty($id)) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'ID tidak valid'
                ]);
                return;
            }
            
            $aktivitas = $this->saranModel->getAktivitasById($id);
            
            if ($aktivitas) {
                $this->jsonResponse([
                    'success' => true,
                    'data' => $aktivitas
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
                ]);
            }

        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Gagal mengambil detail aktivitas'
            ]);
        }
    }
}