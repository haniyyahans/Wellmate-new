<?php // ada 7 fungsi
require_once "Model.class.php";
class LaporanModel extends Model
{
    public function getRiwayatMingguan($id_pengguna)
    { // mengambil 7 data konsumsi harian terakhir untuk 1 pengguna
        $query = "SELECT 
                    DATE(tanggal) as tanggal,      
                    SUM(total_harian) as total_harian,
                    DAYNAME(tanggal) as hari
                  FROM riwayat_minum 
                  WHERE id_pengguna = ? 
                  GROUP BY DATE(tanggal), DAYNAME(tanggal)
                  ORDER BY tanggal DESC
                  LIMIT 7";                           // ambil data di kolom tanggal, jumlahkan konsumsi minum user, ambil nama hari di kolom tanggal (inggris)
        $stmt = $this->db->prepare($query);           // siapin query di database
        $stmt->bind_param('i', $id_pengguna);         // parameternya bertipe integer untuk id_pengguna
        $stmt->execute();                             // jalankan query yang uda disiapkan beserta parameternya
        $result = $stmt->get_result();                // ambil dan simpan hasilnya
        $data = [];
        while ($row = $result->fetch_assoc()) {       // loop dan baca semua baris hasil lalu disimpan ke $data
            $data[] = $row;
        }
        return array_reverse($data);                  // agar urutannya dari hari lama ke baru
    }

    public function getRiwayatBulanan($id_pengguna)
    { // mengambil riwayat minum per minggu selama satu bulan (maksimal 4 minggu).
        $query = "SELECT 
                    FLOOR(DATEDIFF(tanggal, (SELECT MIN(tanggal) FROM riwayat_minum WHERE id_pengguna = ?)) / 7) + 1 as minggu, 
                    SUM(total_harian) as total_mingguan,
                    MIN(tanggal) as tanggal_mulai,
                    MAX(tanggal) as tanggal_akhir
                  FROM riwayat_minum 
                  WHERE id_pengguna = ? 
                  GROUP BY FLOOR(DATEDIFF(tanggal, (SELECT MIN(tanggal) FROM riwayat_minum WHERE id_pengguna = ?)) / 7)
                  ORDER BY minggu ASC
                  LIMIT 4";                               //kelompokkan data berdasarkan minggu, jumlahkan konsumsi dalam mingguan, ambil tanggal awal dan akhir, kelompokkan hari agar masuk di minggu yg sesuai
        $stmt = $this->db->prepare($query);               // penjelasan bagian ini sama dengan getRiwayatMingguan
        $stmt->bind_param('iii', $id_pengguna, $id_pengguna, $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function insertLaporan($data)
    { // insert data laporan lengkap ke database
        $query = "INSERT INTO laporan 
                  (id_pengguna, jenis_laporan, periode, jumlah_konsumsi, 
                   persentase, kategori_pencapaian, 
                   analisis_pencapaian, rekomendasi)
                  VALUES 
                  (?, ?, ?, ?, ?, ?, ?, ?)";                // perintah insert di tabel laporan
        $stmt = $this->db->prepare($query);                 // menyiapkan query insert
        $stmt->bind_param(
            'isiidsss',
            $data['id_pengguna'],
            $data['jenis_laporan'],
            $data['periode'],
            $data['jumlah_konsumsi'],
            $data['persentase'],
            $data['kategori_pencapaian'],
            $data['analisis_pencapaian'],
            $data['rekomendasi']
        );                                                    // isi parameternya
        return $stmt->execute();                              // eksekusi query
    }

    public function updateLaporan($id_laporan, $dataLaporan)
    { // update laporan yang sudah ada
        $query = "UPDATE laporan SET 
                  jumlah_konsumsi = ?,
                  persentase = ?,
                  kategori_pencapaian = ?,
                  analisis_pencapaian = ?,
                  rekomendasi = ?
                  WHERE id_laporan = ?";                    // perintah update di tabel laporan
        $stmt = $this->db->prepare($query);                 // menyiapkan query update
        $stmt->bind_param(
            'idsssi',
            $dataLaporan['jumlah_konsumsi'],
            $dataLaporan['persentase'],
            $dataLaporan['kategori_pencapaian'],
            $dataLaporan['analisis_pencapaian'],
            $dataLaporan['rekomendasi'],
            $id_laporan
        );                                                  // isi parameternya (i=integer, d=double, s=string)
        return $stmt->execute();                            // eksekusi query
    }

    public function getLaporanTerakhir($id_pengguna, $jenis_laporan)
    { // ambil laporan terakhir berdasarkan jenis (Mingguan/Bulanan)
        $query = "SELECT * FROM laporan 
                  WHERE id_pengguna = ? 
                  AND jenis_laporan = ?
                  ORDER BY id_laporan DESC 
                  LIMIT 1";                                    // perintah untuk menampilkan laporan terbaru, mingguan ataupun bulanan
        $stmt = $this->db->prepare($query);                    // siapkan query
        $stmt->bind_param('is', $id_pengguna, $jenis_laporan); // isi parameter
        $stmt->execute();                                      // eksekusi
        $result = $stmt->get_result();                         // simpan hasil dan mengembalikan array 1 baris laporan
        return $result->fetch_assoc();
    }

    public function cekDataRiwayat($id_pengguna)
    { // cek apakah ada data riwayat untuk user
        $query = "SELECT COUNT(*) as jumlah FROM riwayat_minum 
                  WHERE id_pengguna = ?";                      // penjelasannya sama seperti sebelumnya
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['jumlah'] > 0;
    }

    public function getTargetUser($id_pengguna)
    {
        $query = "SELECT target_harian FROM user_target 
              WHERE id_akun = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['target_harian'] : null;
    }
}