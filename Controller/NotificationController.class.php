<?php

require_once 'Controller.class.php';
require_once 'Model/NotificationModel.class.php';
require_once 'Model/TrackingModel.class.php';

class Notification extends Controller
{
    private $notificationModel;
    private $trackingModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->trackingModel = new TrackingModel();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Menampilkan daftar notifikasi pengguna
    public function index()
    {
        $this->listNotifications();
    }

    // Menampilkan daftar notifikasi pengguna
    public function listNotifications() {
    // 1. Get data dari Model
    $userId = $_SESSION['id_pengguna'] ?? null;
    if (!$userId) {
        // Handle the case when user is not logged in
        header('Location: index.php?c=Auth&m=login');
        exit;
    }
    $notifications = $this->notificationModel->getNotificationsByUserId($userId);
    $unreadCount = $this->notificationModel->countUnread($userId);
    
    // 2. Pass data ke View
    $this->view('notifikasi', [
        'notifications' => $notifications,
        'unreadCount' => $unreadCount
    ]);
}

    // Menandai notifikasi sebagai sudah dibaca (AJAX)
    public function markAsRead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['id_pengguna'])) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $userId = $_SESSION['id_pengguna'];
        
        // Ambil data dari request body
        $data = json_decode(file_get_contents('php://input'), true);
        $notifId = $data['id_notif'] ?? null;

        if (!$notifId) {
            echo json_encode(['success' => false, 'message' => 'ID notifikasi tidak ditemukan']);
            exit;
        }

        // Validasi kepemilikan notifikasi
        if (!$this->notificationModel->isNotificationOwnedByUser($notifId, $userId)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $success = $this->notificationModel->updateStatus($notifId, 'read');
        
        if ($success) {
            $newCount = $this->notificationModel->countUnread($userId);
            echo json_encode([
                'success' => true, 
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca',
                'unreadCount' => $newCount
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupdate notifikasi']);
        }
        exit;
    }

    // Menandai semua notifikasi sebagai sudah dibaca
    public function markAllAsRead()
    {
        if (!isset($_SESSION['id_pengguna'])) {
            header('Location: index.php?c=User&m=login');
            exit;
        }

        $userId = $_SESSION['id_pengguna'];
        $success = $this->notificationModel->markAllAsRead($userId);
        
        if ($success) {
            header("Location: index.php?c=Notification&m=listNotifications&status=all_read");
        } else {
            header("Location: index.php?c=Notification&m=listNotifications&status=fail");
        }
        exit;
    }

    // Cek aktivitas konsumsi dan kirim notifikasi jika perlu
    public function checkAndSendReminder()
    {
        // Ambil semua pengguna aktif
        $activeUsers = $this->notificationModel->getAllActiveUsers();
        $notifSent = 0;
        $debugInfo = [];

        foreach ($activeUsers as $user) {
            $userId = $user['id_pengguna'];
            
            // PERBAIKAN: Pastikan menggunakan id yang benar
            // Jika id_pengguna == id_akun, gunakan langsung
            // Jika berbeda, sesuaikan dengan struktur database Anda
            $lastConsumption = $this->trackingModel->getLastConsumption($userId);
            
            // Debug: Simpan info untuk tiap user
            $debugInfo[$userId] = [
                'nama' => $user['nama'],
                'last_consumption' => $lastConsumption,
                'is_more_than_2h' => $this->isMoreThan2Hours($lastConsumption)
            ];
            
            // Jika sudah lebih dari 2 jam tidak mencatat konsumsi
            if ($this->isMoreThan2Hours($lastConsumption)) {
                $message = "Sudah lebih dari 2 jam sejak terakhir kali kamu mencatat konsumsi air, yuk minum dulu!";
                $created = $this->notificationModel->createNotification($userId, $message);
                
                if ($created) {
                    $notifSent++;
                    $debugInfo[$userId]['notif_sent'] = true;
                }
            }
        }

        // Return dengan info debug
        echo json_encode([
            'success' => true,
            'message' => "Notifikasi pengingat berhasil dikirim ke $notifSent pengguna",
            'details' => $debugInfo
        ]);
    }

    // Helper: Cek apakah sudah lebih dari 2 jam (IMPROVED)
    private function isMoreThan2Hours($lastConsumption)
    {
        if (!$lastConsumption || !isset($lastConsumption['waktu'])) {
            // Belum ada konsumsi sama sekali
            error_log("No consumption found");
            return true;
        }

        // Validasi format waktu
        $waktu = $lastConsumption['waktu'];
        
        if (empty($waktu)) {
            error_log("Empty waktu value");
            return true;
        }

        $lastTime = strtotime($waktu);
        
        // Cek jika strtotime gagal
        if ($lastTime === false) {
            error_log("Invalid datetime format: " . $waktu);
            return true; // Anggap perlu reminder jika format tidak valid
        }

        $currentTime = time();
        $diff = ($currentTime - $lastTime) / 3600; // Konversi ke jam

        // Debug log
        error_log("Last time: " . date('Y-m-d H:i:s', $lastTime));
        error_log("Current time: " . date('Y-m-d H:i:s', $currentTime));
        error_log("Difference in hours: " . $diff);

        return $diff >= 2;
    }

    // Menghapus notifikasi
    public function deleteNotification()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['id_pengguna'])) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $userId = $_SESSION['id_pengguna'];
        
        $data = json_decode(file_get_contents('php://input'), true);
        $notifId = $data['id_notif'] ?? null;

        if (!$notifId) {
            echo json_encode(['success' => false, 'message' => 'ID notifikasi tidak ditemukan']);
            exit;
        }

        $success = $this->notificationModel->deleteNotification($notifId, $userId);
        
        if ($success) {
            $newCount = $this->notificationModel->countUnread($userId);
            echo json_encode([
                'success' => true, 
                'message' => 'Notifikasi berhasil dihapus',
                'unreadCount' => $newCount
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus notifikasi']);
        }
        exit;
    }

    // Get unread count (untuk AJAX)
    public function getUnreadCount()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['id_pengguna'])) {
            echo json_encode(['success' => false, 'count' => 0]);
            exit;
        }

        $userId = $_SESSION['id_pengguna'];
        $count = $this->notificationModel->countUnread($userId);
        
        echo json_encode(['success' => true, 'count' => $count]);
        exit;
    }
}