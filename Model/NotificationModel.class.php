<?php

require_once 'Model.class.php';

class NotificationModel extends Model
{
    // Fungsi bantu untuk mengubah timestamp menjadi waktu relatif (misalnya: '5 menit yang lalu')
    private function time_ago($timestamp) {
        $current_time = time();
        $time_difference = $current_time - strtotime($timestamp);
        $seconds = $time_difference;
        
        // Logika perhitungan waktu relatif
        $minutes = round($seconds / 60);
        $hours = round($seconds / 3600);
        $days = round($seconds / 86400);
        $weeks = round($seconds / 604800);
        $months = round($seconds / 2629440);
        $years = round($seconds / 31553280);

        if ($seconds <= 60) {
            return "Baru saja";
        } else if ($minutes <= 60) {
            return "$minutes menit yang lalu";
        } else if ($hours <= 24) {
            return "$hours jam yang lalu";
        } else if ($days <= 7) {
            return "$days hari yang lalu";
        } else if ($weeks <= 4) {
            return "$weeks minggu yang lalu";
        } else if ($months <= 12) {
            return "$months bulan yang lalu";
        } else {
            return "$years tahun yang lalu";
        }
    }

    // Mengambil semua notifikasi berdasarkan ID User
    function getNotificationsByUserId($userId) {
        // Query ke database
        $sql = "SELECT * FROM notifikasi WHERE id_pengguna = ? ORDER BY waktu_kirim DESC"; // Pastikan diurutkan
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Tambahkan kolom 'waktu_relatif' ke setiap notifikasi
        foreach ($notifications as &$notif) {
            // Asumsi kolom waktu di database bernama 'waktu_kirim'
            $notif['waktu_relatif'] = $this->time_ago($notif['waktu_kirim']); 
        }
        
        return $notifications;
    }

    // Menghitung jumlah notifikasi yang belum dibaca
    public function countUnread($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM notifikasi 
                WHERE id_pengguna = ? AND status = 'unread'";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return 0;
        }
        
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data['count'] ?? 0;
    }

    // Membuat notifikasi baru
    public function createNotification($userId, $message)
    {
        $sql = "INSERT INTO notifikasi (id_pengguna, pesan, waktu_kirim, status) 
                VALUES (?, ?, NOW(), 'unread')";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("is", $userId, $message);
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return $stmt->affected_rows > 0;
    }

    // Mengupdate status notifikasi (read/unread)
    public function updateStatus($notifId, $status)
    {
        $sql = "UPDATE notifikasi SET status = ? WHERE id_notif = ?";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("si", $status, $notifId);
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return true; // Berhasil meskipun tidak ada perubahan
    }

    // Menandai semua notifikasi sebagai sudah dibaca
    public function markAllAsRead($userId)
    {
        $sql = "UPDATE notifikasi SET status = 'read' 
                WHERE id_pengguna = ? AND status = 'unread'";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("i", $userId);
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return true;
    }

    // Menghapus notifikasi
    public function deleteNotification($notifId, $userId)
    {
        $sql = "DELETE FROM notifikasi WHERE id_notif = ? AND id_pengguna = ?";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("ii", $notifId, $userId);
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        return $stmt->affected_rows > 0;
    }

    // Mengambil semua pengguna aktif (untuk cron job)
    public function getAllActiveUsers()
    {
        $sql = "SELECT id_pengguna, nama FROM pengguna ORDER BY id_pengguna";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            error_log("Query failed: " . $this->db->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Cek apakah notifikasi milik user tertentu
    public function isNotificationOwnedByUser($notifId, $userId)
    {
        $sql = "SELECT id_notif FROM notifikasi WHERE id_notif = ? AND id_pengguna = ?";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("ii", $notifId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
}