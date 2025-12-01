<?php

require_once 'Model.class.php';

class FriendModel extends Model
{
    // Mengambil semua teman yang sudah diterima (status: accepted)
    public function getFriendsByUserId($userId)
    {
        $sql = "SELECT 
                    t.id_teman,
                    t.tanggal,
                    CASE 
                        WHEN t.id_pengguna = ? THEN p2.id_pengguna
                        ELSE p1.id_pengguna
                    END as id_teman_user,
                    CASE 
                        WHEN t.id_pengguna = ? THEN p2.nama
                        ELSE p1.nama
                    END as nama_teman,
                    CASE 
                        WHEN t.id_pengguna = ? THEN a2.username  -- PERUBAHAN UTAMA DI SINI
                        ELSE a1.username  -- PERUBAHAN UTAMA DI SINI
                    END as username_teman
                FROM teman t
                JOIN pengguna p1 ON t.id_pengguna = p1.id_pengguna
                JOIN pengguna p2 ON t.id_user_teman = p2.id_pengguna
                -- JOIN ke tabel AKUN untuk mendapatkan username
                JOIN akun a1 ON p1.id_akun = a1.id_akun  -- JOIN untuk pengguna t.id_pengguna
                JOIN akun a2 ON p2.id_akun = a2.id_akun  -- JOIN untuk pengguna t.id_user_teman
                WHERE (t.id_pengguna = ? OR t.id_user_teman = ?) 
                AND t.status = 'accepted'
                ORDER BY t.tanggal DESC";
        
        // Jumlah parameter bind_param tetap sama: 5 'i' untuk 5 placeholder '?'
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiiii", $userId, $userId, $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFriendshipStatus($userId, $friendId)
    {
        // Cek apakah sudah berteman (accepted)
        if ($this->areFriends($userId, $friendId)) {
            return 'accepted';
        }
        
        // Cek apakah ada permintaan pending
        $sql = "SELECT id_pengguna, id_user_teman FROM teman 
                WHERE ((id_pengguna = ? AND id_user_teman = ?) 
                OR (id_pengguna = ? AND id_user_teman = ?))
                AND status = 'pending'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Jika current user yang mengirim
            if ($row['id_pengguna'] == $userId) {
                return 'pending_sent';
            }
            // Jika current user yang menerima
            return 'pending_received';
        }
        
        return 'none';
    }

    // Mengambil permintaan pertemanan yang masuk (status: pending)
    public function getPendingRequests($userId)
    {
        $sql = "SELECT 
                    t.id_teman,
                    t.tanggal,
                    p.id_pengguna as id_pengirim,
                    p.nama as nama_pengirim,
                    a.username as username_pengirim
                FROM teman t
                JOIN pengguna p ON t.id_pengguna = p.id_pengguna
                JOIN akun a ON p.id_akun = a.id_akun  -- JOIN KE TABEL AKUN
                WHERE t.id_user_teman = ? AND t.status = 'pending'
                ORDER BY t.tanggal DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Menambahkan permintaan pertemanan baru
    public function addFriendRequest($userId, $friendId)
    {
        $sql = "INSERT INTO teman (id_pengguna, id_user_teman, status, tanggal) 
                VALUES (?, ?, 'pending', CURDATE())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $friendId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Mengupdate status pertemanan (accepted/declined)
    public function updateFriendshipStatus($friendshipId, $status)
    {
        $sql = "UPDATE teman SET status = ?, tanggal = CURDATE() WHERE id_teman = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $friendshipId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Menghapus pertemanan
    public function deleteFriendship($friendshipId, $userId)
    {
        $sql = "DELETE FROM teman 
                WHERE id_teman = ? 
                AND (id_pengguna = ? OR id_user_teman = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $friendshipId, $userId, $userId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Cek apakah hubungan pertemanan sudah ada (pending/accepted)
    public function isFriendshipActive($userId, $friendId)
    {
        // Cek jika statusnya 'accepted' atau 'pending'
        $sql = "SELECT id_teman FROM teman 
                WHERE ((id_pengguna = ? AND id_user_teman = ?) 
                OR (id_pengguna = ? AND id_user_teman = ?))
                AND status IN ('accepted', 'pending')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Cek apakah dua user adalah teman (status: accepted)
    public function areFriends($userId, $friendId)
    {
        $sql = "SELECT id_teman FROM teman 
                WHERE ((id_pengguna = ? AND id_user_teman = ?) 
                OR (id_pengguna = ? AND id_user_teman = ?))
                AND status = 'accepted'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Mengambil data pertemanan berdasarkan ID
    public function getFriendshipById($friendshipId)
    {
        $sql = "SELECT * FROM teman WHERE id_teman = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $friendshipId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Menghitung jumlah permintaan pertemanan
    public function countFriendRequests($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM teman 
                WHERE id_user_teman = ? 
                AND status = 'pending'";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return 0;
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] ?? 0;
    }
}