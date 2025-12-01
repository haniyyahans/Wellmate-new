<?php // ada 2 fungsi untuk menampilkan daftar berita dan menampilkan isi berita
require_once "Model.class.php";                                                // memanggil class Model, supaya koneksi database siap
class BeritaModel extends Model                                                // merupakan anak model
{
    public function getAllBerita() // untuk menampilkan semua daftar berita
    {
        $query = "SELECT * FROM berita_edukasi ORDER BY tanggal_publish DESC";  // simpan query, menampilkan data dari tabel berita_edukasi dan diurutkan
        $result = $this->db->query($query);                                     // jalanin query di database
        $data = [];                                                             // buat array data
        while ($row = $result->fetch_assoc()) {                                 // loop hasil query, ambil semua row dalam bentuk array asosiatif
            $data[] = $row;                                                     // masukkan hasilnya ke array $data
        }
        return $data;                                                           // kembaliin hasil array ke controller
    }

    public function getBeritaById($id) // untuk menampilkan detail berita
    {
        $stmt = $this->db->prepare("SELECT * FROM berita_edukasi WHERE id_berita = ?"); // menampilkan detail berita sesuai id nya
        $stmt->bind_param("i", $id);                                                    // id berita diisi dengan integer, kan sebelumnya kosong id nya
        $stmt->execute();                                                               // eksekusi perintah select dengan id yang uda terisi
        return $stmt->get_result()->fetch_assoc();                                      // hasilnya dikembalikan ke controller, fetch_assoc() ini mengubah hasil query jadi array 
    }
}