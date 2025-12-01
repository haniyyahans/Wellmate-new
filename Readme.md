# Wellmate - Kelompok 3 RSI-F

Sebuah sistem berbasis website yang dibuat untuk membantu pengguna menjaga kebutuhan cairan tubuh secara optimal serta mendorong terciptanya pola hidup sehat di kalangan remaja maupun masyarakat secara umum.. Sistem ini dibangun menggunakan pendekatan arsitektur **MVC (Model-View-Controller)**.

---

## 📁 Struktur Folder

/Wellmate-new/
│
├── /Controller/ # File controller untuk mengatur alur aplikasi
│ ├── AuthController.class.php
│ ├── BerandaController.class.php
│ ├── BeritaController.class.php
│ ├── Controller.class.php
│ ├── FriendController.class.php
│ ├── LaporanController.class.php
│ ├── NotificationController.class.php
│ ├── SaranController.class.php
│ └── TrackingController.class.php
│
├── /Kerangka/ # Tampilan user (HTML)
│ ├── berandapage.html
│ ├── beritaEdukasi.html
│ ├── laporandanAnalisis.html
│ ├── lihatTeman.html
│ ├── notifikasi.html
│ ├── saran.html
│ ├── signinpage.html
│ ├── signuppage.html
│ ├── tambahTeman.html
│ ├── tracking.html
│ └── updateTeman.html
│
├── /Model/ # File model untuk menangani data dan database
│ ├── BerandaModel.class.php
│ ├── BeritaModel.class.php
│ ├── FriendModel.class.php
│ ├── LaporanModel.class.php
│ ├── Model.class.php
│ ├── NotificationModel.class.php
│ ├── SaranModel.class.php
│ ├── TrackingModel.class.php
│ └── UserModel.class.php
│
├── /View/ # Tampilan user (PHP)
│ ├── HalamanBeranda.php
│ ├── HalamanBerita.php
│ ├── HalamanLaporan.php
│ ├── Saran.php
│ ├── Tracking.php
│ ├── lihatTeman.php
│ ├── notifikasi.php
│ ├── permintaanPertemanan.php
│ ├── signinpage.php
│ ├── signuppage.php
│ └── tambahTeman.php
│
├── index.php # Entry point aplikasi
├── database.sql # Struktur database
└── Readme.md # Dokumentasi proyek ini



---

## Fitur Utama
  *Pinartika Nasya Meilanty*
- **Register & Login – Membuat akun dan mengakses semua fitur.**
- **Hitung Kebutuhan Cairan – Menentukan target hidrasi harian berdasarkan berat badan.**
    *Shabreena Sugestiani*
- **Tracking Konsumsi Minum – Mencatat minuman harian dan menampilkan progres dalam grafik.**
- **Saran Cairan Setelah Aktivitas – Rekomendasi cairan berdasarkan aktivitas (misal: olahraga).**
    *Sabrina Zahra*
- **Laporan & Analisis – Grafik perkembangan mingguan/bulanan.**
- **Berita & Edukasi – Artikel dan informasi seputar hidrasi dan kesehatan.**
    *Zahra Nurul Haniyyah Anas*
- **Notifikasi Pengingat – Pengingat otomatis untuk jadwal minum.**
- **Berbagi dengan Teman – Terhubung, memantau progres, dan saling memberi dukungan.**


---

## Cara Menjalankan

1. Import file `database.sql` ke phpMyAdmin atau aplikasi manajemen database lainnya.
2. Letakkan seluruh file dan folder ke dalam direktori `htdocs` (jika menggunakan XAMPP).
3. Akses melalui browser menggunakan URL:  
   `http://localhost/Wellmate-new/`

---

## Catatan

- Semua logic utama berada di dalam folder `/Controller/`.
- Semua interaksi dengan database berada di `/Model/`.
- File tampilan yang dilihat user berada di `/View/`.

---

> Proyek ini telah mengalami banyak perubahan sejak laporan awal. Hal ini disesuaikan dengan proses belajar anggota kelompok serta kebutuhan teknis agar proyek dapat dikerjakan dengan baik oleh kelompok.
