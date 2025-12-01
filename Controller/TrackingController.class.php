<?php
require_once 'Controller/Controller.class.php';
require_once 'Model/Model.class.php';
class Tracking extends Controller
{
    private $trackingModel;
    private $berandaModel;
    private $notificationModel;

    public function __construct()
    {
        $this->trackingModel = $this->model('TrackingModel');
        $this->berandaModel = $this->model('BerandaModel');
        $this->notificationModel = $this->model('NotificationModel');
        $this->startSession(); // spy bisa baca user id

        if (!$this->getSession('id_akun')) {
            $this->setSession('id_akun', 1); // Ganti dengan ID yang ada di database
        }
    }

    // Method default untuk menampilkan halaman tracking
    public function index()
    {
        try {
            $idAkun = $this->getSession('id_akun');

            // if (!$idAkun) {
            //     // Redirect ke login jika belum login
            //     $this->redirect('index.php?c=Auth&m=login');
            //     return;
            // }

            $today = date('Y-m-d');

            $dataPengguna = $this->berandaModel->getDataPengguna($idAkun);
            $namaPengguna = $dataPengguna ? $dataPengguna['nama'] : 'Pengguna';
            // minta data yg diperlukan ke model
            $jenisMinuman = $this->trackingModel->getJenisMinuman();
            $targetHarian = $this->trackingModel->getTargetHarian($idAkun);
            $catatanMinum = $this->trackingModel->getCatatanMinumByDate($today, $idAkun);
            $statistik = $this->trackingModel->getStatistikHariIni($idAkun);
            
            $idPengguna = $dataPengguna ? $dataPengguna['id_pengguna'] : null;
            $unreadCount = $this->notificationModel->countUnread($idPengguna);

            $data = [
                'jenisMinuman' => $jenisMinuman,
                'targetHarian' => $targetHarian,
                'catatanMinum' => $catatanMinum,
                'statistik' => $statistik,
                'namaPengguna' => $namaPengguna,
                'dataPengguna' => $dataPengguna,
                'unreadCount' => $unreadCount 
            ];

            $this->view('tracking', $data);

            // klo error/blm ada datanya, ditampilkan default ini:
        } catch (Exception $e) {
            $this->view('tracking', [
                'jenisMinuman' => [],
                'targetHarian' => 0,
                'catatanMinum' => [],
                'statistik' => ['total_diminum' => 0, 'jumlah_catatan' => 0],
                'namaPengguna' => 'Pengguna',
                'dataPengguna' => null,
                'unreadCount' => 0
            ]);
        }
    }

    // Method untuk mendapatkan data via AJAX
    public function getData()
    {
        try {
            $idAkun = $this->getSession('id_akun');

            // if (!$idAkun) {
            //     $this->jsonResponse([
            //         'success' => false,
            //         'message' => 'Anda belum login'
            //     ]);
            //     return;
            // }

            $today = date('Y-m-d');

            $jenisMinuman = $this->trackingModel->getJenisMinuman();
            $targetHarian = $this->trackingModel->getTargetHarian($idAkun);
            $catatanMinum = $this->trackingModel->getCatatanMinumByDate($today, $idAkun);

            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'jenisMinuman' => $jenisMinuman,
                    'targetHarian' => $targetHarian,
                    'catatanMinum' => $catatanMinum
                ]
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Gagal mengambil data'
            ]);
        }
    }

    // Method untuk tambah catatan minum
    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $idAkun = $this->getSession('id_akun');

                $jenis = $_POST['jenis'] ?? '';
                $jumlah = $_POST['jumlah'] ?? 0;
                $waktu = $_POST['waktu'] ?? '';
                $tanggal = date('Y-m-d');

                // Validasi lebih longgar
                if (empty($jenis) || $jumlah <= 0 || empty($waktu)) {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'Data tidak lengkap. Jenis: ' . $jenis . ', Jumlah: ' . $jumlah . ', Waktu: ' . $waktu
                    ]);
                    return;
                }

                $result = $this->trackingModel->tambahCatatanMinum($jenis, $jumlah, $waktu, $tanggal, $idAkun);

                // Perbaiki pengecekan result
                if ($result !== false) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Catatan berhasil ditambahkan',
                        'id' => $result
                    ]);
                } else {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'Gagal menambahkan catatan - database error'
                    ]);
                }
            } catch (Exception $e) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }
    }

    // Method untuk update catatan minum
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $idAkun = $this->getSession('id_akun');

                // if (!$idAkun) {
                //     $this->jsonResponse([
                //         'success' => false,
                //         'message' => 'Anda belum login'
                //     ]);
                //     return;
                // }

                $id = $_POST['id'] ?? '';
                $jenis = $_POST['jenis'] ?? '';
                $jumlah = $_POST['jumlah'] ?? 0;
                $waktu = $_POST['waktu'] ?? '';

                if (empty($id) || empty($jenis) || empty($jumlah) || empty($waktu)) {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'Data tidak lengkap'
                    ]);
                    return;
                }

                $result = $this->trackingModel->updateCatatanMinum($id, $jenis, $jumlah, $waktu);

                if ($result) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Catatan berhasil diperbarui'
                    ]);
                } else {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'Gagal memperbarui catatan'
                    ]);
                }
            } catch (Exception $e) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ]);
        }
    }

    // Method untuk hapus catatan minum
    public function hapus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $idAkun = $this->getSession('id_akun');

                // if (!$idAkun) {
                //     $this->jsonResponse([
                //         'success' => false,
                //         'message' => 'Anda belum login'
                //     ]);
                //     return;
                // }

                $id = $_POST['id'] ?? '';

                if (empty($id)) {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'ID tidak valid'
                    ]);
                    return;
                }

                $result = $this->trackingModel->hapusCatatanMinum($id);

                if ($result) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Catatan berhasil dihapus'
                    ]);
                } else {
                    $this->jsonResponse([
                        'success' => false,
                        'message' => 'Gagal menghapus catatan'
                    ]);
                }
            } catch (Exception $e) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ]);
        }
    }
}
