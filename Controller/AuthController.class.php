<?php

require_once 'Controller.class.php'; 
// Catatan: Karena AuthController menggunakan $this->model('UserModel'), 
// require_once UserModel.class.php tidak diperlukan di sini.

class Auth extends Controller {
    
    private $userModel;
    
    public function __construct() {
        // Menginstansiasi UserModel melalui method model() untuk memastikan Model.class.php dimuat
        $this->userModel = $this->model('UserModel'); 
        
        // Start session jika belum
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Memproses pendaftaran akun baru (Mirip Users::register)
     * URL: index.php?c=Auth&m=register
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nama = $_POST['nama'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($nama) || empty($username) || empty($password)) {
                // Tampilkan kembali form dengan pesan error
                $message = ['message' => 'Semua field harus diisi'];
                $this->view('signuppage', $message); 
                return;
            }
            
            // Panggil UserModel::register dengan parameter eksplisit
            $result = $this->userModel->register($nama, $username, $password);
            
            if ($result['success']) {
                // Redirect jika berhasil, mirip Users::register
                header('Location: index.php?c=Auth&m=login');
                exit();
            } else {
                // Tampilkan kembali form dengan pesan error
                $message = ['message' => $result['message']];
                $this->view('signuppage', $message);
            }
            
        } else {
            // Tampilkan form registrasi (signuppage.php)
            $this->view('signuppage'); 
        }
    }
    
    /**
     * Memverifikasi data login dan membuat sesi (Mirip Users::login)
     * URL: index.php?c=Auth&m=login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']) ? true : false;
            
            if (empty($username) || empty($password)) {
                // Tampilkan kembali form dengan pesan error
                $message = ['message' => 'Username dan password harus diisi'];
                $this->view('signinpage', $message);
                return;
            }
            
            $result = $this->userModel->login($username, $password);
            
            // Hapus semua session dan redirect logic, ganti dengan JSON Response
            if ($result['success']) {
                // Buat sesi
                $_SESSION['id_pengguna'] = $result['user']['id_pengguna'];
                $_SESSION['id_akun'] = $result['user']['id_akun'];
                $_SESSION['username'] = $result['user']['username'];
                $_SESSION['nama'] = $result['user']['nama'];
                
                // Jika login berhasil, kembalikan JSON sukses
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => 'index.php?c=Beranda&m=index' // Memberi tahu klien ke mana harus redirect
                ]);
                
            } else {
                // Jika login gagal, kembalikan JSON error
                $this->jsonResponse([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
            
        } else {
            // Tampilkan form login
            $this->view('signinpage'); 
        }
    }
    
    /**
     * Logout (Mirip Users::logout)
     */
    public function logout() {
        session_destroy();
        setcookie('id_pengguna', '', time() - 3600, '/');
        // Redirect ke halaman login
        header('Location: index.php?c=Auth&m=login');
        exit;
    }
    
    // ... method lain (isLoggedIn)
    public function isLoggedIn() {
        return isset($_SESSION['id_pengguna']);
    }
}