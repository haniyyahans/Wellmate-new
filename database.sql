-- ============================================
-- DATABASE WELLMATE
-- ============================================

-- Buat Database
CREATE DATABASE IF NOT EXISTS wellmate;
USE wellmate;

-- ============================================
-- TABEL AKUN
-- ============================================
CREATE TABLE IF NOT EXISTS akun (
    id_akun INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(20) NOT NULL
);

-- ============================================
-- TABEL PENGGUNA
-- ============================================
CREATE TABLE IF NOT EXISTS pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    id_akun INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    berat_badan DECIMAL(5,2),
    usia INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_id_akun (id_akun),
    FOREIGN KEY (id_akun) REFERENCES akun (id_akun) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ============================================
-- TABEL JENIS MINUMAN
-- ============================================
CREATE TABLE IF NOT EXISTS jenis_minuman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(50) NOT NULL,
    ikon VARCHAR(10) NOT NULL,
    warna VARCHAR(7) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABEL CATATAN MINUM HARIAN
-- ============================================
CREATE TABLE IF NOT EXISTS catatan_minum (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_akun INT NOT NULL,
    jenis VARCHAR(50) NOT NULL,
    jumlah INT NOT NULL,
    waktu VARCHAR(10) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_akun_tanggal (id_akun, tanggal),
    FOREIGN KEY (id_akun) REFERENCES akun (id_akun)
);

-- ============================================
-- TABEL TARGET HARIAN USER
-- ============================================
CREATE TABLE IF NOT EXISTS user_target (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_akun INT NOT NULL,
    target_harian INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user (id_akun),
    FOREIGN KEY (id_akun) REFERENCES akun (id_akun)
);

-- ============================================
-- TABEL AKTIVITAS FISIK
-- ============================================
CREATE TABLE IF NOT EXISTS aktivitas_fisik (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    ikon VARCHAR(10) NOT NULL,
    cairan_tambahan VARCHAR(20) NOT NULL,
    deskripsi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABEL NOTIFIKASI
-- ============================================
CREATE TABLE IF NOT EXISTS notifikasi (
    id_notif INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    pesan TEXT NOT NULL,
    waktu_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(30) DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pengguna_status (id_pengguna, status),
    INDEX idx_waktu_kirim (waktu_kirim)
);

-- ============================================
-- TABEL TEMAN
-- ============================================
CREATE TABLE IF NOT EXISTS teman (
    id_teman INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_user_teman INT NOT NULL,
    status VARCHAR(30) DEFAULT 'pending',
    tanggal DATE DEFAULT (CURRENT_DATE),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_user_teman) REFERENCES pengguna(id_pengguna) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_friendship (id_pengguna, id_user_teman),
    INDEX idx_status (status),
    INDEX idx_user_teman (id_user_teman),
    CONSTRAINT chk_different_users CHECK (id_pengguna != id_user_teman),
    CONSTRAINT chk_status CHECK (status IN ('pending', 'accepted', 'declined'))
);

-- ============================================
-- TABEL BERITA_EDUKASI
-- ============================================
CREATE TABLE berita_edukasi (
    id_berita INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    isi TEXT NOT NULL,
    kategori VARCHAR(50),
    tanggal_publish TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sumber VARCHAR(50),
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
);

-- ============================================
-- TABEL RIWAYAT_MINUM
-- ============================================
CREATE TABLE riwayat_minum (
    id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    total_harian DECIMAL(10,2) NULL,
    tanggal TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    persentase_target DECIMAL(5,2) NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
);

-- ============================================
-- TABEL LAPORAN
-- ============================================
CREATE TABLE laporan (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    jenis_laporan VARCHAR(100) NULL,
    periode INT NULL,
    jumlah_konsumsi INT NULL,
    persentase DECIMAL(5,2) NULL,
    kategori_pencapaian VARCHAR(50) NULL,
    analisis_pencapaian VARCHAR(1000) NULL,
    rekomendasi VARCHAR(500) NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
);

-- ============================================
-- INSERT DATA DUMMY - JENIS MINUMAN
-- ============================================
INSERT INTO jenis_minuman (nama, ikon, warna) VALUES
('Air Putih', '💧', '#3478F5'),
('Teh Hijau', '🍵', '#34C759'),
('Jus Buah', '🍊', '#FFB300'),
('Kopi', '☕', '#8B4513'),
('Susu', '🥛', '#F0E68C'),
('Soda', '🥤', '#87CEFA'),
('Jamu', '🌿', '#A0522D'),
('Yogurt', '🥄', '#FFDEAD');

-- ============================================
-- INSERT DATA DUMMY - AKTIVITAS FISIK
-- ============================================
INSERT INTO aktivitas_fisik (nama, ikon, cairan_tambahan, deskripsi) VALUES
('Lari 30-60 menit', '🏃', '±400 ml', 'Aktivitas kardio yang meningkatkan detak jantung dan membuat tubuh cepat kehilangan cairan melalui keringat. Sangat disarankan untuk minum sebelum, selama, dan setelah lari.'),
('Gym 1 jam', '🏋️', '±500 ml', 'Latihan beban dan kardio intens dalam ruangan yang meningkatkan suhu tubuh. Kehilangan keringat tinggi. Pastikan asupan cairan untuk menjaga performa.'),
('Angkat Beban 45-60 menit', '💪', '±300 ml', 'Meskipun tidak seintensif kardio, angkat beban tetap menyebabkan kehilangan cairan. Minum secara teratur antar set untuk menjaga fokus dan energi.'),
('Bersepeda 45 menit', '🚴', '±450 ml', 'Bersepeda, terutama di luar ruangan, membutuhkan asupan cairan yang konsisten. Kebutuhan cairan bisa lebih tinggi jika cuaca panas.'),
('Kerja Fisik', '👷', '±600 ml', 'Bekerja di luar atau di lingkungan panas/lembab memerlukan perhatian ekstra pada hidrasi. Disarankan minum setiap 20-30 menit kerja.'),
('Aerobik atau Zumba 45 menit', '💃', '±350 ml', 'Aktivitas grup yang energik dan bergerak cepat. Kehilangan cairan terjadi secara cepat. Jaga botol air di dekat Anda.'),
('Mendaki 2-4 jam', '⛰️', '±1.5 L', 'Aktivitas yang berlangsung lama dan sering di lingkungan yang menantang (pulau/dataran tinggi). Wajib membawa cairan yang cukup dan elektrolit.'),
('Olahraga 1-2 jam', '⚽', '±750 ml', 'Aktivitas olahraga tim (sepak bola, basket) yang memerlukan gerakan sporadis intens. Konsumsi cairan di waktu istirahat sangat penting.'),
('Lainnya', '✨', '±250 ml', 'Untuk aktivitas ringan atau durasi yang lebih singkat, tambahan 250ml sudah cukup. Sesuaikan dengan rasa haus Anda.');

-- ============================================
-- INSERT DATA DUMMY - BERITA_EDUKASI
-- ============================================
INSERT INTO berita_edukasi (id_pengguna, judul, isi, kategori, sumber)
VALUES
(1,'"Mengapa Air Putih Menjadi Kunci Utama Kesehatan Tubuh?"',
'Dalam kehidupan sehari-hari, air putih sering kali dianggap sepele, padahal perannnya sangat penting bagi kesehatan tubuh manusia. Berdasarkan laporan dari Kementerian Kesehatan RI, lebih dari 60% komposisi tubuh manusia terdiri dari air. Hal ini menjadikan air sebagai komponen vital dalam menjaga fungsi organ, mengatur suhu tubuh, serta membantu proses metabolisme.
Para ahli gizi menekankan bahwa kekurangan cairan dapat menyebabkan dehidrasi, yang ditandai dengan gejala seperti kelelahan, sakit kepala, dan menurunnya konsentrasi. Dalam jangka panjang, dehidrasi kronis dapat meningkatkan risiko gangguan ginjal, infeksi saluran kemih, hingga masalah pada kulit.
Menurut penelitian yang diterbitkan oleh World Health Organization (WHO), kebutuhan air harian untuk orang dewasa rata-rata adalah 2-2,5 liter per hari, tergantung aktivitas fisik dan kondisi lingkungan. Minum air putih secara cukup terbukti membantu meningkatkan energi, menjaga sistem pencernaan, serta membuang racun dari dalam tubuh melalui urin dan keringat.
Selain itu, air putih juga berperan penting dalam menjaga keseimbangan elektrolit. Ketika tubuh kekurangan cairan, keseimbangan elektrolit terganggu dan dapat menyebabkan kram otot atau gangguan pada sistem saraf.
Untuk itu, para pakar kesehatan menyarankan agar masyarakat membiasakan minum air putih secara teratur, bahkan sebelum merasa haus. Kebiasaan sederhana ini dapat menjadi langkah awal menuju hidup yang lebih sehat.
Tips Edukasi:
- Bawalah botol air minum sendiri untuk memantau asupan harian.
- Minum segelas air setelah bangun tidur dan sebelum tidur malam.
- Kurangi minuman manis dan berkafein yang dapat menyebabkan dehidrasi.
- Gunakan aplikasi pengingat minum jika sering lupa.',
'Kesehatan dan hidrasi',
'World Health Organization (WHO)'),
(1,'"Berapa Banyak Air yang Ideal Diminum Setiap Hari?"','kosong','Edukasi dan fakta sains','kosong'),
(1,'"Dampak Dehidrasi Ringan terhadap Konsentrasi dan Produktivitas"',
'Penelitian menunjukkan bahwa dehidrasi ringan—bahkan hanya 1–2% kehilangan cairan tubuh—dapat menurunkan fokus, memperlambat kemampuan berpikir, dan membuat seseorang lebih cepat lelah saat bekerja. Kondisi ini sering tidak disadari karena gejalanya muncul perlahan, seperti sakit kepala ringan dan sulit berkonsentrasi. Tetap menjaga asupan air sepanjang hari menjadi langkah penting untuk mempertahankan produktivitas.',
'Kesehatan dan hidrasi',
'World Health Organization (WHO)'),
(1,'"Fakta Menarik: Minum Air Bisa Meningkatkan Fokus dan Suasana Hati"',
'Studi kesehatan menunjukkan bahwa hidrasi yang cukup membantu meningkatkan fungsi kognitif, termasuk fokus dan kewaspadaan. Selain itu, minum air dalam jumlah cukup juga berpengaruh pada suasana hati, membantu mengurangi rasa lelah dan iritabilitas yang sering muncul akibat kekurangan cairan.',
'Edukasi dan fakta sains',
'Centers for Disease Control and Prevention (CDC)'),
(1,'"Air vs Minuman Manis: Mana yang Lebih Baik untuk Tubuh?"',
'Minuman manis dapat memberikan energi cepat, tetapi konsumsi berlebih meningkatkan risiko obesitas dan gangguan metabolik. Sebaliknya, air putih menyediakan hidrasi optimal tanpa kalori maupun gula tambahan. Karena itu, para ahli kesehatan merekomendasikan air sebagai pilihan utama untuk memenuhi kebutuhan cairan harian.',
'Nutrisi dan gaya hidup sehat',
'World Health Organization (WHO)'),
(1,'"Tips Menjaga Asupan Cairan Selama Aktivitas Fisik"',
'Ketika berolahraga, tubuh kehilangan cairan lebih cepat melalui keringat. Untuk mencegah dehidrasi, disarankan minum air sebelum, selama, dan setelah aktivitas fisik. Jika olahraga dilakukan dalam durasi panjang atau cuaca panas, penambahan elektrolit mungkin diperlukan untuk menjaga keseimbangan tubuh.',
'Nutrisi dan gaya hidup sehat',
'American College of Sports Medicine (ACSM)'),
(1,'"Kebiasaan Sehat yang Bisa Membantu Kamu Lebih Rajin Minum Air"',
'Beberapa kebiasaan sederhana seperti membawa botol minum, mengatur pengingat di ponsel, atau memulai hari dengan segelas air dapat membantu meningkatkan konsumsi cairan harian. Mengganti minuman manis dengan air secara bertahap juga efektif membuat tubuh terbiasa dengan hidrasi yang sehat.',
'Nutrisi dan gaya hidup sehat',
'Centers for Disease Control and Prevention (CDC)'),
(1,'"Mitos dan Fakta Tentang Air: Tidak Semua Informasi di Internet Benar!"',
'Banyak informasi keliru tentang konsumsi air, seperti "semua orang harus minum 8 gelas per hari". Faktanya, kebutuhan cairan berbeda bagi setiap orang berdasarkan aktivitas, usia, dan kondisi kesehatan. Oleh karena itu, penting memeriksa informasi kesehatan dari sumber tepercaya sebelum mempercayainya.',
'Edukasi dan fakta sains',
'National Institutes of Health (NIH)');
