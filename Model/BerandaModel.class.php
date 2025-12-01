<?php
require_once __DIR__ . '/Model.class.php';

class BerandaModel extends Model { 
    
    /**
     * Mengambil biodata berdasarkan id_pengguna
     */
    // public function getBiodataByIdPengguna($id_pengguna) {
    //     try {
    //         $id_pengguna = intval($id_pengguna);
    //         $sql = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna";
    //         $result = $this->db->query($sql);
            
    //         if ($result && $result->num_rows > 0) {
    //             return $result->fetch_assoc();
    //         }
            
    //         return null;
    //     } catch (Exception $e) {
    //         return null;
    //     }
    // }
    public function getDataPengguna($idAkun) {
        try {
            $idAkun = $this->escape($idAkun);
            $sql = "SELECT p.*, a.username 
                    FROM pengguna p
                    INNER JOIN akun a ON p.id_akun = a.id_akun
                    WHERE p.id_akun = '$idAkun'";
            
            $result = $this->query($sql);
            
            if ($result && $row = $result->fetch_assoc()) {
                return $row;
            }
            
            return null;

        } catch (Exception $e) {
            error_log("Error getDataPengguna: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Menambah atau update biodata user
     */
    public function tambahBiodata($data) {
        try {
            if (empty($data['id_pengguna']) || empty($data['nama']) || empty($data['berat_badan'])) {
                return [
                    'success' => false,
                    'message' => 'Data id_pengguna, nama, dan berat_badan wajib diisi'
                ];
            }
            
            $id_pengguna = intval($data['id_pengguna']);
            $nama = $this->escape($data['nama']);
            $berat_badan = floatval($data['berat_badan']);
            $usia = isset($data['usia']) ? intval($data['usia']) : null;
            $idAkunUntukTarget = $this->getDataPengguna($id_pengguna); // Cari idAkun

            if ($berat_badan <= 0 || $berat_badan > 300) {
                return [
                    'success' => false,
                    'message' => 'Berat badan tidak valid (harus antara 0-300 kg)'
                ];
            }
            
            // Cek apakah data sudah ada
            $checkSql = "SELECT id_pengguna FROM pengguna WHERE id_pengguna = $id_pengguna";
            $checkResult = $this->db->query($checkSql);
            
            if ($checkResult->num_rows > 0) {
                // Update jika sudah ada
                return $this->updateBiodata($id_pengguna, $data);
            }
            
            // Insert jika belum ada
            // Catatan: Anda menggunakan UPDATE di sini, yang tidak sesuai dengan nama fungsi 'tambahBiodata'. 
            // Saya mengasumsikan Anda ingin UPDATE jika ditemukan, dan INSERT jika tidak ada, tetapi kode Anda hanya menangani UPDATE.
            // Sesuai kode asli Anda, ini adalah blok UPDATE:
            $sql = "UPDATE pengguna SET 
                    nama = '$nama', 
                    berat_badan = $berat_badan, 
                    usia = " . ($usia ? $usia : "NULL") . ",
                    updated_at = NOW()
                    WHERE id_pengguna = $id_pengguna";
            
            $result = $this->db->query($sql);
            
            if ($result) {
                // *** PERBAIKAN: Logika perhitungan target dipindahkan ke sini ***
                // Asumsi: id_pengguna sama dengan id_akun untuk simpanTargetHarian
                $targetBaru = $this->hitungTargetMinumHarian($berat_badan, $usia);
                $this->simpanTargetHarian($id_pengguna, $targetBaru); 
                // *** END PERBAIKAN ***

                return [
                    'success' => true,
                    'message' => 'Biodata berhasil ditambahkan',
                    'data' => $this->getDataPengguna($id_pengguna) 
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menambahkan biodata'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update biodata user yang sudah ada
     */
    public function updateBiodata($id_pengguna, $data) {
        try {
            if (empty($id_pengguna)) {
                return [
                    'success' => false,
                    'message' => 'ID pengguna tidak ditemukan'
                ];
            }
            
            $id_pengguna = intval($id_pengguna);
            
            // Cek apakah pengguna ada
            $checkSql = "SELECT id_pengguna FROM pengguna WHERE id_pengguna = $id_pengguna";
            $checkResult = $this->db->query($checkSql);
            
            if ($checkResult->num_rows == 0) {
                return [
                    'success' => false,
                    'message' => 'Pengguna dengan ID tersebut tidak ditemukan'
                ];
            }
            
            // Build update query dinamis
            $updateFields = [];
            
            if (isset($data['nama']) && !empty($data['nama'])) {
                $nama = $this->escape($data['nama']);
                $updateFields[] = "nama = '$nama'";
            }
            
            $berat_badan_update = null;
            if (isset($data['berat_badan']) && !empty($data['berat_badan'])) {
                $berat_badan_update = floatval($data['berat_badan']);
                if ($berat_badan_update > 0 && $berat_badan_update <= 300) {
                    $updateFields[] = "berat_badan = $berat_badan_update";
                }
            }
            
            $usia_update = null;
            if (isset($data['usia'])) {
                $usia_update = intval($data['usia']);
                $updateFields[] = "usia = " . ($usia_update > 0 ? $usia_update : "NULL");
            }
            
            if (empty($updateFields)) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada data yang diupdate'
                ];
            }
            
            $updateFields[] = "updated_at = NOW()";
            
            $sql = "UPDATE pengguna SET " . implode(', ', $updateFields) . " WHERE id_pengguna = $id_pengguna";
            $result = $this->db->query($sql);
            
            if ($result) {
                // *** PERBAIKAN: Logika perhitungan target dipindahkan ke sini ***

                // 1. Ambil data terbaru (termasuk id_akun, berat_badan, dan usia) dari database setelah update.
                $currentDataSql = "SELECT id_akun, berat_badan, usia FROM pengguna WHERE id_pengguna = $id_pengguna";
                $currentDataResult = $this->query($currentDataSql);

                if ($currentDataResult && $dataPengguna = $currentDataResult->fetch_assoc()) {
                    $idAkun = $dataPengguna['id_akun'];
                    $beratBadan = $dataPengguna['berat_badan'];
                    $usia = $dataPengguna['usia'];
                    
                    // 2. Hitung dan simpan target baru menggunakan idAkun yang benar
                    $targetBaru = $this->hitungTargetMinumHarian($beratBadan, $usia);
                    $this->simpanTargetHarian($idAkun, $targetBaru);
                
                    // Kembalikan respons sukses dengan data terbaru
                    return [
                        'success' => true,
                        'message' => 'Biodata berhasil diupdate',
                        'data' => $dataPengguna 
                    ];
                }

                // Jika update pengguna berhasil, tapi logic target gagal 
                return [
                    'success' => true,
                    'message' => 'Biodata berhasil diupdate, namun gagal menghitung ulang target cairan.'
                ];
                // *** END PERBAIKAN ***
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal mengupdate biodata'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Menghitung target kebutuhan cairan harian berdasarkan berat badan
     */
    public function hitungTargetMinumHarian($beratBadan, $usia) {
        try {
            if (empty($beratBadan) || empty($usia) || $beratBadan <= 0 || $usia <= 0) {
                return 0; // Default jika data tidak lengkap
            }
            
            // Tentukan faktor pengali berdasarkan usia
            $faktorPengali = 35; // Default untuk usia 18-30
            
            if ($usia >= 31 && $usia <= 55) {
                $faktorPengali = 33;
            } elseif ($usia > 55) {
                $faktorPengali = 30;
            }
            
            // Hitung target (dalam ml)
            $targetMinum = $beratBadan * $faktorPengali;
            
            // Bulatkan ke kelipatan 100ml terdekat
            $targetMinum = round($targetMinum / 100) * 100;
            
            // Pastikan minimal 1500ml dan maksimal 5000ml
            if ($targetMinum < 1500) {
                $targetMinum = 1500;
            } elseif ($targetMinum > 5000) {
                $targetMinum = 5000;
            }
            
            return $targetMinum;

        } catch (Exception $e) {
            error_log("Error hitungTargetMinumHarian: " . $e->getMessage());
            return 0; // Default jika error
        }
    }

    /**
     * Simpan atau update target harian user
     */
    public function simpanTargetHarian($idAkun, $targetHarian) {
        try {
            $idAkun = $this->escape($idAkun);
            $targetHarian = $this->escape($targetHarian);
            
            // Cek apakah sudah ada data target untuk user ini
            $sqlCheck = "SELECT id FROM user_target WHERE id_akun = '$idAkun'";
            $resultCheck = $this->query($sqlCheck);
            
            if ($resultCheck && $resultCheck->num_rows > 0) {
                // Update jika sudah ada
                $sql = "UPDATE user_target 
                        SET target_harian = '$targetHarian',
                            updated_at = CURRENT_TIMESTAMP
                        WHERE id_akun = '$idAkun'";
            } else {
                // Insert jika belum ada
                $sql = "INSERT INTO user_target (id_akun, target_harian) 
                        VALUES ('$idAkun', '$targetHarian')";
            }
            
            return $this->query($sql);

        } catch (Exception $e) {
            error_log("Error simpanTargetHarian: " . $e->getMessage());
            return false;
        }
    }

    public function getProgressHidrasi($idAkun) {
        try {
            $idAkun = $this->escape($idAkun);
            $today = date('Y-m-d');
            
            // Ambil target harian
            $sqlTarget = "SELECT target_harian FROM user_target WHERE id_akun = '$idAkun'";
            $resultTarget = $this->query($sqlTarget);
            $targetHarian = 0;
            
            if ($resultTarget && $rowTarget = $resultTarget->fetch_assoc()) {
                $targetHarian = $rowTarget['target_harian'];
            }
            
            // Jika target masih 0, hitung dari data pengguna
            if ($targetHarian == 0) {
                $dataPengguna = $this->getDataPengguna($idAkun);
                if ($dataPengguna && isset($dataPengguna['berat_badan']) && isset($dataPengguna['usia'])) {
                    $targetHarian = $this->hitungTargetMinumHarian(
                        $dataPengguna['berat_badan'], 
                        $dataPengguna['usia']
                    );
                } else {
                    $targetHarian = 2000; // Default 2L
                }
            }
            
            // Ambil total konsumsi hari ini
            $sqlKonsumsi = "SELECT SUM(jumlah) as total_diminum 
                            FROM catatan_minum 
                            WHERE id_akun = '$idAkun' AND tanggal = '$today'";
            $resultKonsumsi = $this->query($sqlKonsumsi);
            $totalDiminum = 0;
            
            if ($resultKonsumsi && $rowKonsumsi = $resultKonsumsi->fetch_assoc()) {
                $totalDiminum = $rowKonsumsi['total_diminum'] ?? 0;
            }
            
            // Hitung persentase dan sisa
            $persentase = $targetHarian > 0 ? round(($totalDiminum / $targetHarian) * 100) : 0;
            $sisaKebutuhan = max(0, $targetHarian - $totalDiminum);
            
            return [
                'target_harian' => $targetHarian,
                'total_diminum' => $totalDiminum,
                'persentase' => $persentase,
                'sisa_kebutuhan' => $sisaKebutuhan
            ];
            
        } catch (Exception $e) {
            error_log("Error getProgressHidrasi: " . $e->getMessage());
            return [
                'target_harian' => 2000,
                'total_diminum' => 0,
                'persentase' => 0,
                'sisa_kebutuhan' => 2000
            ];
        }
    }
}