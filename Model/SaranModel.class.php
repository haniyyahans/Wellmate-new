<?php

class SaranModel extends Model {
    
    // Get all aktivitas fisik
    public function getAllAktivitas() {
        try {
            $sql = "SELECT * FROM aktivitas_fisik ORDER BY id";
            $result = $this->query($sql);
            $data = [];
            
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            
            return $data;

        } catch (Exception $e) {
            return [];
        }
    }
    
    // Get aktivitas by ID
    public function getAktivitasById($id) {
        try {
            $id = $this->escape($id);
            $sql = "SELECT * FROM aktivitas_fisik WHERE id = '$id'";
            $result = $this->query($sql);
            
            return $result ? $result->fetch_assoc() : null;

        } catch (Exception $e) {
            return null;
        }
    }
    
    // Get user target harian (untuk ditampilkan di summary)
    public function getTargetHarian($idAkun) {
        try {
            $idAkun = $this->escape($idAkun);
            $sql = "SELECT ut.target_harian, p.berat_badan, p.usia
                    FROM user_target ut
                    LEFT JOIN pengguna p ON ut.id_akun = p.id_akun
                    WHERE ut.id_akun = '$idAkun'";
            $result = $this->query($sql);
            
            if ($result && $row = $result->fetch_assoc()) {
                // Jika target_harian = 0 atau NULL, hitung ulang berdasarkan data pengguna
                if (empty($row['target_harian']) || $row['target_harian'] == 0) {
                    if (!empty($row['berat_badan']) && !empty($row['usia'])) {
                        // Hitung target baru
                        $targetBaru = $this->hitungTargetOtomatis($row['berat_badan'], $row['usia']);
                        
                        // Simpan target baru
                        $this->updateTargetHarian($idAkun, $targetBaru);
                        
                        return $targetBaru;
                    }
                    // Jika data pengguna belum lengkap, return default
                    return 0;
                }
                
                return $row['target_harian'];
            }
            
            // Jika belum ada record, cek data pengguna
            $sqlPengguna = "SELECT berat_badan, usia FROM pengguna WHERE id_akun = '$idAkun'";
            $resultPengguna = $this->query($sqlPengguna);
            
            if ($resultPengguna && $rowPengguna = $resultPengguna->fetch_assoc()) {
                if (!empty($rowPengguna['berat_badan']) && !empty($rowPengguna['usia'])) {
                    $targetBaru = $this->hitungTargetOtomatis($rowPengguna['berat_badan'], $rowPengguna['usia']);
                    
                    // Buat record baru di user_target
                    $this->buatTargetBaru($idAkun, $targetBaru);
                    
                    return $targetBaru;
                }
            }
            
            return 0; // Default target jika data tidak lengkap

        } catch (Exception $e) {
            return 0;
        }
    }
    
    // TAMBAHKAN: Method helper untuk menghitung target otomatis
    private function hitungTargetOtomatis($beratBadan, $usia) {
        if (empty($beratBadan) || empty($usia) || $beratBadan <= 0 || $usia <= 0) {
            return 0;
        }
        
        // Tentukan faktor pengali berdasarkan usia
        $faktorPengali = 35;
        
        if ($usia >= 31 && $usia <= 55) {
            $faktorPengali = 33;
        } elseif ($usia > 55) {
            $faktorPengali = 30;
        }
        
        // Hitung target
        $targetMinum = $beratBadan * $faktorPengali;
        $targetMinum = round($targetMinum / 100) * 100;
        
        // Batasan minimal dan maksimal
        if ($targetMinum < 1500) {
            $targetMinum = 1500;
        } elseif ($targetMinum > 5000) {
            $targetMinum = 5000;
        }
        
        return $targetMinum;
    }
    
    // TAMBAHKAN: Method helper untuk update target harian
    private function updateTargetHarian($idAkun, $targetHarian) {
        try {
            $idAkun = $this->escape($idAkun);
            $targetHarian = $this->escape($targetHarian);
            
            $sql = "UPDATE user_target 
                    SET target_harian = '$targetHarian',
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id_akun = '$idAkun'";
            
            return $this->query($sql);
        } catch (Exception $e) {
            error_log("Error updateTargetHarian: " . $e->getMessage());
            return false;
        }
    }
    
    // TAMBAHKAN: Method helper untuk membuat target baru
    private function buatTargetBaru($idAkun, $targetHarian) {
        try {
            $idAkun = $this->escape($idAkun);
            $targetHarian = $this->escape($targetHarian);
            
            $sql = "INSERT INTO user_target (id_akun, user_id, target_harian) 
                    VALUES ('$idAkun', '$idAkun', '$targetHarian')";
            
            return $this->query($sql);
        } catch (Exception $e) {
            error_log("Error buatTargetBaru: " . $e->getMessage());
            return false;
        }
    }

    // Get statistik konsumsi hari ini (untuk summary section)
    public function getStatistikHariIni($idAkun) {
        try {
            $idAkun = $this->escape($idAkun);
            $today = date('Y-m-d');
            
            $sql = "SELECT 
                        SUM(jumlah) as total_diminum,
                        COUNT(*) as jumlah_catatan
                    FROM catatan_minum 
                    WHERE id_akun = '$idAkun' AND tanggal = '$today'";
            
            $result = $this->query($sql);
            
            if ($result && $row = $result->fetch_assoc()) {
                return [
                    'total_diminum' => $row['total_diminum'] ?? 0,
                    'jumlah_catatan' => $row['jumlah_catatan'] ?? 0
                ];
            }
            
            return ['total_diminum' => 0, 'jumlah_catatan' => 0];

        } catch (Exception $e) {
            return ['total_diminum' => 0, 'jumlah_catatan' => 0];
        }
    }
}