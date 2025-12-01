<?php // ada 2 fungsi untuk menampilkan semua berita dan menampilkan detail berita
require_once 'Controller.class.php';
class Berita extends Controller {
    //bagian Hani, untuk fitur notifikasi
    private $beritaModel;
    private $notificationModel;
    private $berandaModel;

    public function __construct() { //untuk fitur notifikasi
        $this->beritaModel = $this->model('BeritaModel');  // Load semua model yang dibutuhkan
        $this->notificationModel = $this->model('NotificationModel');
        $this->berandaModel = $this->model('BerandaModel');
        $this->startSession(); // Start session
        if (!$this->getSession('id_akun')) { // Set default id_akun jika belum ada (untuk testing)
            $this->setSession('id_akun', 1);
        }
    }

    public function index() // menampilkan semua berita (halaman utama berita)
    {
        // bagian notifikasi
        $idAkun = $this->getSession('id_akun'); // Ambil id_akun dari session
        $dataPengguna = $this->berandaModel->getDataPengguna($idAkun);  // Ambil data pengguna untuk mendapatkan id_pengguna
        $idPengguna = $dataPengguna ? $dataPengguna['id_pengguna'] : null;
        $unreadCount = $this->notificationModel->countUnread($idPengguna); // hitung notifikasi belum dibaca
        // bagian berita
        $berita = $this->beritaModel->getAllBerita();       // mengambil semua berita dari database, pake fungsi di model berita
        $this->view('HalamanBerita', [                      // kirim ke view di HalamanBerita.php
            'berita' => $berita,
            'unreadCount' => $unreadCount                   // untuk notifikasi                    
        ]);
    }

    public function detail() // menampilkan detail berita
    {
        // bagian notifikasi
        $idAkun = $this->getSession('id_akun'); // Ambil id_akun dari session
        $dataPengguna = $this->berandaModel->getDataPengguna($idAkun); // Ambil data pengguna untuk mendapatkan id_pengguna
        $idPengguna = $dataPengguna ? $dataPengguna['id_pengguna'] : null;
        $unreadCount = $this->notificationModel->countUnread($idPengguna); // hitung notifikasi belum dibaca
        // bagian berita
        if (!isset($_GET['id'])) {                     // kalau list beritanya dipencet kan muncul id, nah ini u/ cek idnya ada apa engga
            echo "ID berita tidak ditemukan!";
            return;
        }
        $id = $_GET['id'];                            // ini untuk ambil id dari url terus disimpan di $id
        $beritaModel = $this->model('BeritaModel');        // panggil model
        $detail = $beritaModel->getBeritaById($id);   // ambil detail berita, pake fungsi model berita
        if (!$detail) {                               // ini output kalau berita tidak ditemukan
            echo "Sistem gagal menampilkan berita dan edukasi silahkan coba lagi!";
            return;
        }
        $berita = $beritaModel->getAllBerita();       // menampilkan daftar berita
        $this->view('HalamanBerita', [                // kirim dua data ke view, daftarnya dan detailnya
            'detail' => $detail,
            'berita' => $berita,
            'unreadCount' => $unreadCount            // untuk notifikasi
        ]);
    }  
}