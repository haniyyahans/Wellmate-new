<?php

require_once 'Model.class.php';

class TrackingModel extends Model
{

    // Get all jenis minuman
    public function getJenisMinuman()
    {
        try {
            $sql = "SELECT * FROM jenis_minuman ORDER BY id";
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

    // Get user target harian (UPDATED - menggunakan id_akun)
    public function getTargetHarian($idAkun)
    {
        try {
            $idAkun = $this->escape($idAkun);

            // Ambil target dari user_target
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

    // Method helper untuk menghitung target otomatis
    private function hitungTargetOtomatis($beratBadan, $usia)
    {
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

    // Method helper untuk update target harian
    private function updateTargetHarian($idAkun, $targetHarian)
    {
        try {
            $idAkun = $this->escape($idAkun);
            $targetHarian = $this->escape($targetHarian);

            $sql = "UPDATE user_target 
                    SET target_harian = '$targetHarian',
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id_akun = '$idAkun'";

            return $this->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Method helper untuk membuat target baru
    private function buatTargetBaru($idAkun, $targetHarian)
    {
        try {
            $idAkun = $this->escape($idAkun);
            $targetHarian = $this->escape($targetHarian);

            $sql = "INSERT INTO user_target (id_akun, user_id, target_harian) 
                    VALUES ('$idAkun', '$idAkun', '$targetHarian')";

            return $this->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Get catatan minum by date (UPDATED - menggunakan id_akun)
    public function getCatatanMinumByDate($tanggal, $idAkun)
    {
        try {
            $tanggal = $this->escape($tanggal);
            $idAkun = $this->escape($idAkun);

            $sql = "SELECT * FROM catatan_minum 
                    WHERE tanggal = '$tanggal' AND id_akun = '$idAkun'
                    ORDER BY waktu ASC";

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

    // Add new catatan minum (UPDATED - menggunakan id_akun)
    public function tambahCatatanMinum($jenis, $jumlah, $waktu, $tanggal, $idAkun)
    {
        try {
            if (!$this->isConnected()) {
                return false;
            }

            $jenis = $this->escape($jenis);
            $jumlah = $this->escape($jumlah);
            $waktu = $this->escape($waktu);
            $tanggal = $this->escape($tanggal);
            $idAkun = $this->escape($idAkun);

            $sql = "INSERT INTO catatan_minum (id_akun, jenis, jumlah, waktu, tanggal) 
                VALUES ('$idAkun', '$jenis', '$jumlah', '$waktu', '$tanggal')";

            $result = $this->query($sql);

            if ($result) {
                return $this->db->insert_id;
            }

            return false;
        } catch (Exception $e) {
            // Log error untuk debugging
            error_log("Error tambahCatatanMinum: " . $e->getMessage());
            return false;
        }
    }

    // Update catatan minum
    public function updateCatatanMinum($id, $jenis, $jumlah, $waktu)
    {
        try {
            $id = $this->escape($id);
            $jenis = $this->escape($jenis);
            $jumlah = $this->escape($jumlah);
            $waktu = $this->escape($waktu);

            $sql = "UPDATE catatan_minum 
                    SET jenis = '$jenis', jumlah = '$jumlah', waktu = '$waktu'
                    WHERE id = '$id'";

            return $this->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete catatan minum
    public function hapusCatatanMinum($id)
    {
        try {
            $id = $this->escape($id);
            $sql = "DELETE FROM catatan_minum WHERE id = '$id'";

            return $this->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Get statistics for today (UPDATED - menggunakan id_akun)
    public function getStatistikHariIni($idAkun)
    {
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

    // Get catatan minum terakhir berdasarkan ID Akun
    public function getLastConsumption($idAkun)
    {
        try {
            $idAkun = $this->escape($idAkun);

            // PERBAIKAN: Gabungkan tanggal dan waktu untuk mendapat timestamp lengkap
            $sql = "SELECT CONCAT(tanggal, ' ', waktu) as waktu_lengkap
                    FROM catatan_minum 
                    WHERE id_akun = '$idAkun'
                    ORDER BY tanggal DESC, waktu DESC
                    LIMIT 1";

            $result = $this->query($sql);

            if ($result && $row = $result->fetch_assoc()) {
                // Return waktu_lengkap sebagai 'waktu' agar kompatibel dengan controller
                return ['waktu' => $row['waktu_lengkap']];
            }

            return null; // Belum ada catatan konsumsi
        } catch (Exception $e) {
            error_log("Error getLastConsumption: " . $e->getMessage());
            return null;
        }
    }
}
