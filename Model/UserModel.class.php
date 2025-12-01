<?php

class UserModel extends Model{
    public function __construct() {
        parent::__construct(); // Panggil konstruktor Model untuk membuat koneksi $this->db
    }
    
    /**
     * Register user baru
     */
    // Menggunakan parameter eksplisit (seperti UsersModel)
    public function register($nama, $username, $password) {
        
        // Cek username sudah ada
        $stmt_check = $this->db->prepare("SELECT id_akun FROM akun WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            return [
                'success' => false,
                'message' => 'Username sudah terdaftar'
            ];
        }
        $stmt_check->close();
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Mulai transaction (manual)
        $this->db->begin_transaction();
        
        try {
            // 1. Insert ke tabel akun
            // Perbaikan: Menambahkan placeholder bind_param
            $stmt_akun = $this->db->prepare("
                INSERT INTO akun (username, password) 
                VALUES (?, ?)
            ");
            $stmt_akun->bind_param("ss", $username, $hashedPassword);
            $stmt_akun->execute();
            $stmt_akun->close();
            
            $idAkun = $this->db->insert_id;
            
            // 2. Insert ke tabel pengguna
            $stmt_pengguna = $this->db->prepare("
                INSERT INTO pengguna (id_akun, nama, berat_badan, usia) 
                VALUES (?, ?, NULL, NULL)
            ");
            $stmt_pengguna->bind_param("is", $idAkun, $nama);
            $stmt_pengguna->execute();
            $stmt_pengguna->close();
            
            // Commit transaction
            $this->db->commit();
            
            return [
                'success' => true,
                'message' => 'Registrasi berhasil',
                'id_akun' => $idAkun
            ];
            
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            // Catat pesan error yang lebih jelas dari database
            return [
                'success' => false,
                'message' => 'Registrasi gagal: ' . $this->db->error . ' (' . $e->getMessage() . ')'
            ];
        }
    }
    
    /**
     * Login user
     */
    // Menggunakan parameter eksplisit (seperti UsersModel)
    public function login($username, $password) {
        $akun = null;
        
        try {
            // 1. Ambil data akun
            $stmt_akun = $this->db->prepare("SELECT id_akun, username, password FROM akun WHERE username = ?");
            $stmt_akun->bind_param("s", $username);
            $stmt_akun->execute();
            $result_akun = $stmt_akun->get_result();
            
            if ($result_akun->num_rows === 1) {
                $akun = $result_akun->fetch_assoc();
            }
            $stmt_akun->close();
            
            if (!$akun) {
                return [
                    'success' => false,
                    'message' => 'Username tidak ditemukan'
                ];
            }

            // --- START DEBUGGING ---
            error_log("Input Password: " . $password);
            error_log("Database Hash: " . $akun['password']);
            $is_verified = password_verify($password, $akun['password']);
            error_log("Verification Result: " . ($is_verified ? 'TRUE' : 'FALSE'));

            if (!$is_verified) { // Ganti if (!password_verify(...)) dengan $is_verified
                return [
                    'success' => false,
                    'message' => 'Password salah'
                ];
            }
            // --- END DEBUGGING ---
            
            // 2. Ambil data pengguna
            $stmt_pengguna = $this->db->prepare("
                SELECT p.*, a.username
                FROM pengguna p 
                JOIN akun a ON p.id_akun = a.id_akun 
                WHERE p.id_akun = ?
            ");
            $stmt_pengguna->bind_param("i", $akun['id_akun']);
            $stmt_pengguna->execute();
            $result_pengguna = $stmt_pengguna->get_result();
            $pengguna = $result_pengguna->fetch_assoc();
            $stmt_pengguna->close();
            
            return [
                'success' => true,
                'message' => 'Login berhasil',
                'user' => $pengguna
            ];
            
        } catch(\Exception $e) {
            return [
                'success' => false,
                'message' => 'Login gagal: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update data pengguna (nama, usia, berat_badan)
     */
    public function updatePengguna($idAkun, $nama, $usia, $beratBadan) {
        try {
            // Validasi sederhana
            if (empty($idAkun) || empty($nama)) {
                return false;
            }
            
            // Konversi ke tipe yang benar
            $usia = $usia === null ? null : (int)$usia;
            $beratBadan = $beratBadan === null ? null : (int)$beratBadan;
            $idAkun = (int)$idAkun;
            
            // Query menggunakan prepared statement untuk keamanan
            $stmt = $this->db->prepare("
                UPDATE pengguna 
                SET nama = ?, usia = ?, berat_badan = ? 
                WHERE id_akun = ?
            ");
            
            // Binding parameters: s=string, i=integer
            // Menggunakan 's' untuk nama, 'i' untuk usia dan beratBadan, 'i' untuk id_akun
            // Catatan: Jika usia atau berat_badan bisa NULL, Anda mungkin perlu perlakuan khusus
            // Di sini kita asumsikan jika NULL, ia akan di-bind sebagai integer 0 atau string kosong (bisa error)
            // Untuk NULL, solusi yang lebih robust adalah menanganinya di logic PHP.
            // Untuk kesederhanaan, kita asumsikan input valid.
            
            $stmt->bind_param("siii", $nama, $usia, $beratBadan, $idAkun);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result; // Mengembalikan true jika berhasil, false jika gagal

        } catch (\Exception $e) {
            // Log error atau kembalikan false
            return false;
        }
    }

    // Mencari user berdasarkan username atau nama (untuk fitur cari teman)
    public function searchUsers($searchTerm, $currentUserId)
    {
        // Tambahkan wildcard % untuk LIKE pattern
        $searchPattern = "%{$searchTerm}%";
        
        $sql = "SELECT 
                    p.id_pengguna, 
                    p.nama,
                    a.username
                FROM pengguna p
                JOIN akun a ON p.id_akun = a.id_akun
                WHERE (p.nama LIKE ? OR a.username LIKE ?)
                AND p.id_pengguna != ?
                LIMIT 10";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $searchPattern, $searchPattern, $currentUserId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Mengambil data user berdasarkan ID
    public function getUserById($userId)
    {
        $sql = "SELECT id_pengguna, nama, username, berat_badan, usia 
                FROM pengguna p JOIN akun a ON p.id_akun = a.id_akun
                WHERE p.id_pengguna = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Cek apakah username sudah ada
    public function isUsernameExists($username, $excludeUserId = null)
    {
        if ($excludeUserId) {
            $sql = "SELECT id_pengguna FROM pengguna WHERE username = ? AND id_pengguna != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $username, $excludeUserId);
        } else {
            $sql = "SELECT id_pengguna FROM pengguna WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $username);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
}