-- ====================================================================================================================================
--						  SCRIPTS: BOOKCOLLECT DB
-- ====================================================================================================================================

DROP DATABASE IF EXISTS BookCollect;
CREATE DATABASE BookCollect;
USE BookCollect;

-- ==================================================  MASTER TABLES  ==================================================================

CREATE TABLE Pengguna (
  id_pengguna   INT AUTO_INCREMENT PRIMARY KEY,
  nama          VARCHAR(50) NOT NULL,
  email         VARCHAR(50) NOT NULL UNIQUE,
  kata_sandi    VARCHAR(50) NOT NULL,
  tgl_daftar    DATE DEFAULT CURRENT_DATE,
  tgl_lahir     DATE,
  bio           TEXT,
  domisili      VARCHAR(50)
);

CREATE TABLE Buku (
  kode_buku      INT AUTO_INCREMENT PRIMARY KEY,
  judul          VARCHAR(100) NOT NULL,
  penulis        VARCHAR(50)  NOT NULL,
  genre          VARCHAR(50),
  tahun_terbit   INT,
  bahasa         VARCHAR(30),
  penerbit       VARCHAR(100),
  jumlah_halaman INT CHECK (jumlah_halaman > 0),
  cover VARCHAR (255)
);

-- =================================================  RELASI TABLES  ===================================================================

CREATE TABLE Buku_pengguna (
  id_bukupengguna INT AUTO_INCREMENT PRIMARY KEY,
  id_pengguna     INT NOT NULL,
  kode_buku       INT NOT NULL,
  status_baca     ENUM('Belum Dibaca','Sedang Dibaca','Selesai') NOT NULL,
  progres         INT DEFAULT 0 CHECK (progres BETWEEN 0 AND 100),
  tanggal_mulai   DATE,
  tanggal_selesai DATE,
  catatan         TEXT,
  FOREIGN KEY (id_pengguna) REFERENCES Pengguna(id_pengguna) ON DELETE CASCADE,
  FOREIGN KEY (kode_buku)   REFERENCES Buku(kode_buku) ON DELETE CASCADE
);

CREATE TABLE Wishlist (
  id_wishlist         INT AUTO_INCREMENT PRIMARY KEY,
  id_pengguna         INT NOT NULL,
  kode_buku           INT NOT NULL,
  tanggal_ditambahkan DATE DEFAULT CURRENT_DATE,
  prioritas           ENUM('Rendah','Sedang','Tinggi'),
  harga_perkiraan     DECIMAL(10,2),
  catatan             TEXT,
  FOREIGN KEY (id_pengguna) REFERENCES Pengguna(id_pengguna) ON DELETE CASCADE,
  FOREIGN KEY (kode_buku)   REFERENCES Buku(kode_buku)       ON DELETE CASCADE
);

CREATE TABLE Ulasan (
  id_ulasan        INT AUTO_INCREMENT PRIMARY KEY,
  id_pengguna      INT NOT NULL,
  kode_buku        INT NOT NULL,
  rating           INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  isi_ulasan       TEXT,
  tanggal_ulasan   DATE DEFAULT CURRENT_DATE,
  jumlah_like      INT DEFAULT 0,
  skor_keterbacaan INT CHECK (skor_keterbacaan BETWEEN 1 AND 10),
  FOREIGN KEY (id_pengguna) REFERENCES Pengguna(id_pengguna) ON DELETE CASCADE,
  FOREIGN KEY (kode_buku)   REFERENCES Buku(kode_buku) ON DELETE CASCADE
);

CREATE TABLE Transaksi (
  id_transaksi      INT AUTO_INCREMENT PRIMARY KEY,
  id_pengguna       INT NOT NULL,
  kode_buku         INT NOT NULL,
  tanggal_transaksi DATE DEFAULT CURRENT_DATE,
  status_transaksi  ENUM('berhasil','belum lunas','gagal') NOT NULL,
  jumlah_transaksi  DECIMAL(10,2) DEFAULT 0.00,
  FOREIGN KEY (id_pengguna) REFERENCES Pengguna(id_pengguna) ON DELETE CASCADE,
  FOREIGN KEY (kode_buku)   REFERENCES Buku(kode_buku)       ON DELETE CASCADE
);

-- =====================================================  DUMMY DATA  ==================================================================

INSERT INTO Pengguna (nama,email,kata_sandi,tgl_lahir,bio,domisili) VALUES
('Malik Hariyanto','malik@gmail.com','123','2004-08-13','Mahasiswa yang suka membaca','Bangkalan'),
('Al Khawarizmi','khawariz@gmail.com','321','1000-01-01','Menulis adalah hobiku','Andalusia'),
('Mahmoud Darwish','mdarwish@gmail.com','111','1941-03-13','Bersyair adalah hobiku','Al-Birwa'),
('Tan Malaka','bapakrepublik1@gmail.com','732','1897-06-02','Tuan rumah tak akan berunding dengan maling yang menjarah rumahnya','Sumatera Barat'),
('Rocky Gerung','dosenkedunguanindo@gmail.com','1092','1959-01-12','Salam akal sehat','Manado'),
('Fahruddin Faiz','dosenfilsufemosionalitas@gmail.com','284','1975-08-16','Berfikir itu adalah bukti kita sebagai manusia','Mojokerto'),
('Zorcasmira','zorcasmiaja@gmail.com','1233','2008-11-03','Gausah dipikirin and let it flow biar ga stress','Bangkalan');

INSERT INTO Buku (judul,penulis,genre,tahun_terbit,bahasa,penerbit,jumlah_halaman) VALUES
('Nicomachean Ethics','Aristoteles','Filsafat',350,'Yunani','Penguin Classics',400),
('Bumi Manusia','Pramoedya Ananta Toer','Sastra',1980,'Indonesia','Lentera Dipantara',535),
('Anak Semua Bangsa','Pramoedya Ananta Toer','Sastra',1981,'Indonesia','Lentera Dipantara',500),
('Jejak Langkah','Pramoedya Ananta Toer','Sastra',1985,'Indonesia','Lentera Dipantara',530),
('Rumah Kaca','Pramoedya Ananta Toer','Sastra',1988,'Indonesia','Lentera Dipantara',460),
('Manifesto Komunis','Karl Marx & Friedrich Engels','Filsafat Politik',1848,'Indonesia','Progressive Press',90),
('Laut Bercerita','Leila S. Chudori','Fiksi Politik',2017,'Indonesia','Kepustakaan Populer Gramedia',379),
('Sejarah Filsafat Barat','Bertrand Russell','Filsafat',1945,'Indonesia','Pustaka Pelajar',850),
('Madilog','Tan Malaka','Filsafat',1943,'Indonesia','Narasi',375),
('Dari Penjara ke Penjara Jilid 1','Tan Malaka','Otomobiografi Politik',1948,'Indonesia','Narasi',300),
('Semangat Muda','Tan Malaka','Politik & Pendidikan',1946,'Indonesia','Narasi',210),
('The Philosophy Book','DK','Filsafat',2011,'Indonesia','Gramedia',320),
('Thinking, Fast and Slow','Daniel Kahneman','Psikologi',2011,'Indonesia','Penerbit Mizan',512),
('Atomic Habits','James Clear','Self-Improvement',2018,'Indonesia','Gramedia',320),
('The Power of Habit','Charles Duhigg','Self-Improvement',2012,'Indonesia','Penerbit Mizan',400),
('Meditations','Marcus Aurelius','Filsafat',180,'Indonesia','Gramedia',170),
('Man’s Search for Meaning','Viktor Frankl','Filsafat',1946,'Indonesia','Penerbit Mizan',200),
('The Art of Thinking Clearly','Rolf Dobelli','Logika',2013,'Indonesia','Gramedia',384),
('The Subtle Art of Not Giving a F*ck','Mark Manson','Self-Improvement',2016,'Indonesia','Gramedia',224),
('Grit','Angela Duckworth','Self-Improvement',2016,'Indonesia','Penerbit Mizan',400),
('The Obstacle Is the Way','Ryan Holiday','Self-Improvement',2014,'Indonesia','Penerbit Mizan',220),
('Laskar Pelangi','Andrea Hirata','Sastra',2005,'Indonesia','Bentang Pustaka',529),
('Perahu Kertas','Dewi Lestari','Sastra',2009,'Indonesia','Bentang Pustaka',444),
('Ayat-Ayat Cinta','Habiburrahman El Shirazy','Sastra',2004,'Indonesia','Republika',412),
('Harry Potter and the Sorcerers Stone','J.K. Rowling','Novel & fiksi',1997,'Inggris','Bloomsbury',320),
('To Kill a Mockingbird','Harper Lee','Sastra',1960,'Inggris','J.B. Lippincott & Co.',336);

INSERT INTO Buku_pengguna (id_pengguna,kode_buku,status_baca,progres,tanggal_mulai,tanggal_selesai,catatan) VALUES
(1,1,'Sedang Dibaca',40,'2024-12-15',NULL,'Menarik, Aristoteles emang mantap'),
(2,2,'Selesai',100,'2024-11-01','2024-11-20','Kisah yang kuat dengan diksi yang membahana'),
(3,3,'Sedang Dibaca',60,'2024-12-10',NULL,'Lanjutan kisah dari Bumi Manusia yang makin dalam'),
(1,9,'Sedang Dibaca',30,'2025-03-29',NULL,'Jangan dibaca, bisa bikin pinter'),
(1,6,'Selesai',100,'2025-04-03','2025-04-12','Bagus dan nggak bikin saya jadi komunis'),
(4,22,'Belum Dibaca',0,NULL,NULL,'Masih antri'),
(5,24,'Belum Dibaca',0,NULL,NULL,'Penasaran sekali'),
(6,14,'Sedang Dibaca',20,'2025-05-10',NULL,'Praktik kebiasaan baru'),
(7,25,'Selesai',100,'2025-01-05','2025-01-25','Buku fantasi pertama yang diselesaikan');

INSERT INTO Transaksi (id_pengguna,kode_buku,tanggal_transaksi,status_transaksi,jumlah_transaksi) VALUES
(1,1,'2025-04-30','berhasil',10000.00),
(2,2,'2025-04-20','berhasil',15000.00),
(3,1,'2024-03-01','belum lunas',10000.00),
(1,3,'2025-04-25','berhasil',12000.00),
(2,1,'2025-03-07','berhasil',22000.00),
(1,8,'2023-03-28','berhasil',13000.00),
(6,1,'2021-12-18','berhasil',98000.00),
(1,10,'2023-10-29','berhasil',109000.00),
(6,6,'2021-11-01','berhasil',83000.00),
(4,7,'2023-01-17','berhasil',159000.00),
(5,6,'2024-06-03','belum lunas',89000.00),
(2,7,'2024-09-20','belum lunas',99000.00),
(6,15,'2023-07-01','belum lunas',130000.00),
(3,5,'2022-10-10','gagal',0.00);

INSERT INTO Wishlist (id_pengguna,kode_buku,prioritas,harga_perkiraan,catatan) VALUES
(1,4,'Tinggi',95000.00,'Lanjutkan Tetralogi Buru'),
(2,5,'Sedang',90000.00,'Ingin tahu akhir kisah Minke'),
(3,22,'Rendah',110000.00,'Klasik yang wajib coba'),
(4,23,'Tinggi',100000.00,'Novel yang populer'),
(5,19,'Sedang',88000.00,'Ingin meningkatkan kebiasaan baik'),
(6,20,'Tinggi',93000.00,'Direkomendasikan teman');

INSERT INTO Ulasan (id_pengguna,kode_buku,rating,isi_ulasan,jumlah_like,skor_keterbacaan) VALUES
(1,1,4,'Isi buku mendalam dan reflektif, tapi bahasa agak tinggi.',5,6),
(2,1,1,'Kurang suka, sulit dimengerti.',2,3),
(2,2,5,'Narasi kuat, sangat menyentuh.',3,9),
(3,3,5,'Bahasa indah, alur mengalir penuh makna.',5,8),
(1,8,5,'Wajib baca untuk mahasiswa.',2,7),
(4,24,5,'Ceritanya romantis dan ringan.',4,8),
(5,25,4,'Fantasi yang seru.',3,7),
(6,14,5,'Tips praktis dan aplikatif.',2,9);


SELECT * FROM buku;
-- =====================================================================================================================================
--            				                   VIEWS
-- =====================================================================================================================================


-- 			<<<<<<<<<<<<<<<<  BUKU POPULER  >>>>>>>>>>>>>>>>>
CREATE OR REPLACE VIEW view_buku_populer AS
SELECT
  b.kode_buku,
  b.judul,
  COUNT(t.id_transaksi) AS jumlah_transaksi
FROM
  Buku b
JOIN
  Transaksi t ON b.kode_buku = t.kode_buku
GROUP BY
  b.kode_buku,
  b.judul
ORDER BY
  jumlah_transaksi DESC;




--			 <<<<<<<<<<<<<<<<<<<<<<<<<  BUKU rating tertinggi  >>>>>>>>>>>>>>>>>>>>>>>>>>
CREATE OR REPLACE VIEW view_buku_rating_tertinggi AS
SELECT
  b.kode_buku,
  b.judul,
  b.penulis,
  b.genre,
  COUNT(t.id_transaksi) AS total_transaksi_berhasil,
  ROUND(AVG(u.rating), 2) AS rata_rata_rating
FROM
  Buku b
LEFT JOIN
  Transaksi t ON b.kode_buku = t.kode_buku AND t.status_transaksi = 'berhasil'
LEFT JOIN
  Ulasan u ON b.kode_buku = u.kode_buku
GROUP BY
  b.kode_buku, b.judul, b.penulis, b.genre
ORDER BY
  rata_rata_rating DESC
LIMIT 5;



-- 			<<<<<<<<<<<<<<<<<<<<<<  URUT USER BERDASARKAN SERING BACA  >>>>>>>>>>>>>>>>>>>>>
CREATE OR REPLACE VIEW rangking_pengguna AS
SELECT
  p.id_pengguna,
  p.nama,
  p.email,
  COUNT(bp.id_bukupengguna) AS jumlah_buku_selesai
FROM 
  Pengguna p
LEFT JOIN 
  Buku_pengguna bp ON p.id_pengguna = bp.id_pengguna AND bp.status_baca = 'Selesai'
GROUP BY 
  p.id_pengguna, p.nama, p.email
ORDER BY 
  jumlah_buku_selesai DESC;


-- 			<<<<<<<<<<<<<<<<<<<<  URUT BUKU BERDASARKAN GENRE  >>>>>>>>>>>>>>>>>>>>
CREATE OR REPLACE VIEW buku_urut_genre AS
SELECT kode_buku, judul, penulis, genre, tahun_terbit
FROM Buku
ORDER BY genre, judul;



--			 <<<<<<<<<<<<<<<<<  DAFTAR BUKU BERDASARKAN PENULIS  >>>>>>>>>>>>>>>>>>
CREATE OR REPLACE VIEW buku_penulis AS
SELECT penulis, judul, genre, tahun_terbit
FROM Buku
ORDER BY penulis, tahun_terbit;



-- =====================================================================================================================================

-- <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  STORAGE PROCEDURE  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

-- =====================================================================================================================================

--  STORED PROCEDURE: UPDATE_PROGRESS_AUTOFINISH
--  Fungsi  : Mengecek setiap entri Buku_pengguna.
--            • Jika status_baca = 'Sedang Dibaca'
--              dan progres sudah 100 % → ubah status_baca = 'Selesai'
--              serta isi tanggal_selesai = CURRENT_DATE.
--            • Jika status_baca = 'Sedang Dibaca'
--              tapi progres masih 0 % → ubah status_baca = 'Belum Dibaca'.


DELIMITER //

CREATE PROCEDURE Update_Progress_WithLoop()
BEGIN
    DECLARE id_terkecil INT;
    DECLARE id_terbesar INT;
    DECLARE id_sekarang INT;

    -- Cari ID terkecil dan terbesar
    SELECT MIN(id_bukupengguna), MAX(id_bukupengguna) INTO id_terkecil, id_terbesar FROM Buku_pengguna;

    SET id_sekarang = id_terkecil;

    WHILE id_sekarang <= id_terbesar DO
        UPDATE Buku_pengguna
        SET status_baca = CASE
            WHEN status_baca = 'Sedang Dibaca' AND progres = 0 THEN 'Belum Dibaca'
            WHEN status_baca = 'Sedang Dibaca' AND progres = 100 THEN 'Selesai'
            ELSE status_baca
        END,
        tanggal_mulai = CASE
            WHEN status_baca = 'Sedang Dibaca' AND progres = 0 THEN NULL
            ELSE tanggal_mulai
        END,
        tanggal_selesai = CASE
            WHEN status_baca = 'Sedang Dibaca' AND progres = 100 THEN CURRENT_DATE
            ELSE tanggal_selesai
        END
        WHERE id_bukupengguna = id_sekarang;

        SET id_sekarang = id_sekarang + 1;
    END WHILE;
END //

DELIMITER ;

CALL Update_Progress_WithLoop();




-- TURUNIN PRIORITAS WISHLIST KALO UDAH LEBIH DARI 90 HARI
DELIMITER //
CREATE PROCEDURE Turunkan_Prioritas_Wishlist_Lama()
BEGIN
    DECLARE id_min INT;
    DECLARE id_max INT;
    DECLARE id_now INT;

    SELECT MIN(id_wishlist), MAX(id_wishlist) INTO id_min, id_max FROM Wishlist;
    SET id_now = id_min;

    WHILE id_now <= id_max DO
        UPDATE Wishlist
        SET prioritas = 'Sedang'
        WHERE id_wishlist = id_now
          AND prioritas = 'Tinggi'
          AND DATEDIFF(CURRENT_DATE, tanggal_ditambahkan) > 90;

        SET id_now = id_now + 1;
    END WHILE;
END //

DELIMITER ;



-- procedure hapus kalo transaksinya udah lebih dari 60 hari
DELIMITER //

CREATE PROCEDURE Hapus_Transaksi_Gagal_Lama()
BEGIN
    DECLARE id_min INT;
    DECLARE id_max INT;
    DECLARE id_now INT;

    SELECT MIN(id_transaksi), MAX(id_transaksi) INTO id_min, id_max
    FROM   Transaksi;

    SET id_now = id_min;

    WHILE id_now <= id_max DO
        DELETE FROM Transaksi
        WHERE id_transaksi = id_now
          AND status_transaksi = 'gagal'
          AND DATEDIFF(CURRENT_DATE, tanggal_transaksi) > 60;

        SET id_now = id_now + 1;
    END WHILE;
END //

DELIMITER ;

-- <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  TRIGGER  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

-- Meng-update total rating dan jumlah review di tabel buku setiap kali user memberi ulasan.
DELIMITER //
CREATE TRIGGER after_review_insert
AFTER INSERT ON review
FOR EACH ROW
BEGIN
    UPDATE buku
    SET jumlah_review = jumlah_review + 1,
        total_rating = total_rating + NEW.rating
    WHERE id_buku = NEW.id_buku;
END;
//
DELIMITER ;


-- Trigger kombinasi insert dan update untuk otomatis hapus wishlist jika buku sudah dibeli
-- DELIMITER //
CREATE TRIGGER after_insert_or_update_wishlist_check_transaksi
AFTER INSERT ON wishlist
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM transaksi
        WHERE id_user = NEW.id_user AND id_buku = NEW.id_buku AND STATUS = 'berhasil'
    ) THEN
        DELETE FROM wishlist
        WHERE id_user = NEW.id_user AND id_buku = NEW.id_buku;
    END IF;
END;
//
DELIMITER ;




-- Saat user membeli buku (transaksi sukses), otomatis dimasukkan ke tabel riwayat_baca
DELIMITER //
CREATE TRIGGER after_insert_transaksi
AFTER INSERT ON transaksi
FOR EACH ROW
BEGIN
    IF NEW.status = 'berhasil' THEN
        INSERT INTO riwayat_baca (id_user, id_buku, tanggal_mulai)
        VALUES (NEW.id_user, NEW.id_buku, NOW());
    END IF;
END;
//
DELIMITER ;



-- Kalau user menghapus review-nya, maka rating dan jumlah review di tabel buku harus ikut dikurangi.
DELIMITER //
CREATE TRIGGER after_delete_review
AFTER DELETE ON review
FOR EACH ROW
BEGIN
    UPDATE buku
    SET jumlah_review = jumlah_review - 1,
        total_rating = total_rating - OLD.rating
    WHERE id_buku = OLD.id_buku;
END;
//
DELIMITER ;

<?php
$sql = "SELECT b.*, ROUND(AVG(u.rating),2) as rata_rating
        FROM Buku b
        LEFT JOIN Ulasan u ON b.kode_buku = u.kode_buku
        GROUP BY b.kode_buku
        ORDER BY rata_rating DESC
        LIMIT 5";