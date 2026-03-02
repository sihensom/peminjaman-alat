-- ============================================================
-- DATABASE: APLIKASI PEMINJAMAN ALAT
-- UKK Paket 1 - Pengembangan Aplikasi Peminjaman Alat
-- ============================================================

-- Buat Database
CREATE DATABASE IF NOT EXISTS db_peminjaman_alat;
USE db_peminjaman_alat;

-- ============================================================
-- TABEL 1: USERS
-- 3 Role: admin, petugas, peminjam
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'petugas', 'peminjam') NOT NULL DEFAULT 'peminjam',
    no_telepon VARCHAR(20) NULL,
    alamat TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 2: KATEGORI
-- Kategori untuk mengelompokkan alat
-- ============================================================
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 3: ALAT
-- Data alat yang bisa dipinjam
-- ============================================================
CREATE TABLE IF NOT EXISTS alat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    kode_alat VARCHAR(50) NOT NULL UNIQUE,
    nama_alat VARCHAR(150) NOT NULL,
    deskripsi TEXT NULL,
    jumlah_total INT NOT NULL DEFAULT 0,
    jumlah_tersedia INT NOT NULL DEFAULT 0,
    kondisi ENUM('baik', 'rusak', 'maintenance') NOT NULL DEFAULT 'baik',
    gambar VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 4: PEMINJAMAN
-- Data peminjaman alat oleh peminjam
-- ============================================================
CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    alat_id INT NOT NULL,
    jumlah_pinjam INT NOT NULL DEFAULT 1,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali_rencana DATE NOT NULL,
    status ENUM('pending', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'diajukan_kembali', 'dibatalkan') NOT NULL DEFAULT 'pending',
    approved_by INT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (alat_id) REFERENCES alat(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 5: PENGEMBALIAN
-- Data pengembalian alat
-- ============================================================
CREATE TABLE IF NOT EXISTS pengembalian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    peminjaman_id INT NOT NULL,
    tanggal_kembali_aktual DATE NOT NULL,
    kondisi_alat ENUM('baik', 'rusak', 'hilang') NOT NULL DEFAULT 'baik',
    solusi VARCHAR(100) NULL COMMENT 'Solusi untuk alat rusak/hilang (Memperbaiki, Ganti Rugi, dll)',
    keterangan TEXT NULL,
    received_by INT NULL,
    denda BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Denda dalam Rupiah',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (received_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 6: LOG AKTIVITAS
-- Mencatat semua aktivitas pengguna
-- ============================================================
CREATE TABLE IF NOT EXISTS log_aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    aksi VARCHAR(50) NOT NULL,
    tabel VARCHAR(50) NOT NULL,
    record_id INT NULL,
    detail TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ============================================================
-- STORED PROCEDURES
-- ============================================================

-- --------------------------------
-- SP: Tambah User Baru
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_create_user(
    IN p_nama VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_role ENUM('admin', 'petugas', 'peminjam'),
    IN p_no_telepon VARCHAR(20),
    IN p_alamat TEXT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal menambahkan user';
    END;
    
    START TRANSACTION;
        INSERT INTO users (nama, email, password, role, no_telepon, alamat)
        VALUES (p_nama, p_email, p_password, p_role, p_no_telepon, p_alamat);
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Update User
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_update_user(
    IN p_id INT,
    IN p_nama VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_role ENUM('admin', 'petugas', 'peminjam'),
    IN p_no_telepon VARCHAR(20),
    IN p_alamat TEXT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal mengupdate user';
    END;
    
    START TRANSACTION;
        UPDATE users 
        SET nama = p_nama, email = p_email, role = p_role, 
            no_telepon = p_no_telepon, alamat = p_alamat
        WHERE id = p_id;
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Hapus User
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_delete_user(IN p_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal menghapus user';
    END;
    
    START TRANSACTION;
        DELETE FROM users WHERE id = p_id;
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Tambah Alat Baru
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_create_alat(
    IN p_kategori_id INT,
    IN p_kode_alat VARCHAR(50),
    IN p_nama_alat VARCHAR(150),
    IN p_deskripsi TEXT,
    IN p_jumlah_total INT,
    IN p_kondisi ENUM('baik', 'rusak', 'maintenance'),
    IN p_gambar VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal menambahkan alat';
    END;
    
    START TRANSACTION;
        INSERT INTO alat (kategori_id, kode_alat, nama_alat, deskripsi, jumlah_total, jumlah_tersedia, kondisi, gambar)
        VALUES (p_kategori_id, p_kode_alat, p_nama_alat, p_deskripsi, p_jumlah_total, p_jumlah_total, p_kondisi, p_gambar);
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Update Alat
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_update_alat(
    IN p_id INT,
    IN p_kategori_id INT,
    IN p_kode_alat VARCHAR(50),
    IN p_nama_alat VARCHAR(150),
    IN p_deskripsi TEXT,
    IN p_jumlah_total INT,
    IN p_kondisi ENUM('baik', 'rusak', 'maintenance'),
    IN p_gambar VARCHAR(255)
)
BEGIN
    DECLARE v_jumlah_dipinjam INT DEFAULT 0;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal mengupdate alat';
    END;
    
    START TRANSACTION;
        -- Hitung jumlah yang sedang dipinjam
        SELECT COALESCE(SUM(jumlah_pinjam), 0) INTO v_jumlah_dipinjam
        FROM peminjaman 
        WHERE alat_id = p_id AND status IN ('disetujui', 'dipinjam');
        
        UPDATE alat 
        SET kategori_id = p_kategori_id, kode_alat = p_kode_alat, nama_alat = p_nama_alat,
            deskripsi = p_deskripsi, jumlah_total = p_jumlah_total, 
            jumlah_tersedia = p_jumlah_total - v_jumlah_dipinjam,
            kondisi = p_kondisi, gambar = p_gambar
        WHERE id = p_id;
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Hapus Alat
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_delete_alat(IN p_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal menghapus alat';
    END;
    
    START TRANSACTION;
        DELETE FROM alat WHERE id = p_id;
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Buat Peminjaman (oleh Peminjam)
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_create_peminjaman(
    IN p_user_id INT,
    IN p_alat_id INT,
    IN p_jumlah_pinjam INT,
    IN p_tanggal_pinjam DATE,
    IN p_tanggal_kembali_rencana DATE,
    IN p_keterangan TEXT
)
BEGIN
    DECLARE v_tersedia INT DEFAULT 0;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal membuat peminjaman';
    END;
    
    START TRANSACTION;
        -- Cek ketersediaan stok
        SELECT jumlah_tersedia INTO v_tersedia FROM alat WHERE id = p_alat_id FOR UPDATE;
        
        IF v_tersedia < p_jumlah_pinjam THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok alat tidak mencukupi';
        END IF;
        
        INSERT INTO peminjaman (user_id, alat_id, jumlah_pinjam, tanggal_pinjam, tanggal_kembali_rencana, status, keterangan)
        VALUES (p_user_id, p_alat_id, p_jumlah_pinjam, p_tanggal_pinjam, p_tanggal_kembali_rencana, 'pending', p_keterangan);
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Approve/Reject Peminjaman (oleh Petugas)
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_approve_peminjaman(
    IN p_peminjaman_id INT,
    IN p_approved_by INT,
    IN p_status ENUM('disetujui', 'ditolak'),
    IN p_keterangan TEXT
)
BEGIN
    DECLARE v_jumlah INT DEFAULT 0;
    DECLARE v_alat_id INT DEFAULT 0;
    DECLARE v\_tersedia INT DEFAULT 0;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal memproses peminjaman';
    END;
    
    START TRANSACTION;
        -- Ambil data peminjaman
        SELECT jumlah_pinjam, alat_id INTO v_jumlah, v_alat_id
        FROM peminjaman WHERE id = p_peminjaman_id AND status = 'pending' FOR UPDATE;
        
        IF v_alat_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Peminjaman tidak ditemukan atau sudah diproses';
        END IF;
        
        -- Jika disetujui, cek dan kurangi stok
        IF p_status = 'disetujui' THEN
            SELECT jumlah_tersedia INTO v_tersedia FROM alat WHERE id = v_alat_id FOR UPDATE;
            
            IF v_tersedia < v_jumlah THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok alat tidak mencukupi';
            END IF;
            \
            UPDATE alat SET jumlah_tersedia = jumlah_tersedia - v_jumlah WHERE id = v_alat_id;
        END IF;
        
        -- Update status peminjaman
        UPDATE peminjaman 
        SET status = p_status, approved_by = p_approved_by, keterangan = p_keterangan
        WHERE id = p_peminjaman_id;
    COMMIT;
END //
DELIMITER ;

-- --------------------------------
-- SP: Proses Pengembalian
-- --------------------------------
DELIMITER //
CREATE PROCEDURE sp_create_pengembalian(
    IN p_peminjaman_id INT,
    IN p_tanggal_kembali_aktual DATE,
    IN p_kondisi_alat ENUM('baik', 'rusak'),
    IN p_keterangan TEXT,
    IN p_received_by INT
)
BEGIN
    DECLARE v_jumlah INT DEFAULT 0;
    DECLARE v_alat_id INT DEFAULT 0;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal memproses pengembalian';
    END;
    
    START TRANSACTION;
        -- Ambil data peminjaman
        SELECT jumlah_pinjam, alat_id INTO v_jumlah, v_alat_id
        FROM peminjaman WHERE id = p_peminjaman_id AND status IN ('disetujui', 'dipinjam') FOR UPDATE;
        
        IF v_alat_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Peminjaman tidak ditemukan atau sudah dikembalikan';
        END IF;
        
        -- Tambah record pengembalian
        INSERT INTO pengembalian (peminjaman_id, tanggal_kembali_aktual, kondisi_alat, keterangan, received_by)
        VALUES (p_peminjaman_id, p_tanggal_kembali_aktual, p_kondisi_alat, p_keterangan, p_received_by);
        
        -- Update status peminjaman
        UPDATE peminjaman SET status = 'dikembalikan' WHERE id = p_peminjaman_id;
        
        -- Kembalikan stok alat
        UPDATE alat SET jumlah_tersedia = jumlah_tersedia + v_jumlah WHERE id = v_alat_id;
        
        -- Jika kondisi rusak, update kondisi alat
        IF p_kondisi_alat = 'rusak' THEN
            UPDATE alat SET kondisi = 'rusak' WHERE id = v_alat_id;
        END IF;
    COMMIT;
END //
DELIMITER ;


-- ============================================================
-- FUNCTIONS
-- ============================================================

-- --------------------------------
-- Function: Cek Ketersediaan Alat
-- --------------------------------
DELIMITER //
CREATE FUNCTION fn_cek_ketersediaan(p_alat_id INT, p_jumlah INT) 
RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_tersedia INT DEFAULT 0;
    
    SELECT jumlah_tersedia INTO v_tersedia FROM alat WHERE id = p_alat_id;
    
    RETURN v_tersedia >= p_jumlah;
END //
DELIMITER ;

-- --------------------------------
-- Function: Hitung Total Peminjaman User
-- --------------------------------
DELIMITER //
CREATE FUNCTION fn_total_peminjaman_user(p_user_id INT) 
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_total INT DEFAULT 0;
    
    SELECT COUNT(*) INTO v_total FROM peminjaman WHERE user_id = p_user_id;
    
    RETURN v_total;
END //
DELIMITER ;

-- --------------------------------
-- Function: Hitung Alat yang Sedang Dipinjam
-- --------------------------------
DELIMITER //
CREATE FUNCTION fn_total_alat_dipinjam(p_alat_id INT) 
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_total INT DEFAULT 0;
    
    SELECT COALESCE(SUM(jumlah_pinjam), 0) INTO v_total 
    FROM peminjaman 
    WHERE alat_id = p_alat_id AND status IN ('disetujui', 'dipinjam');
    
    RETURN v_total;
END //
DELIMITER ;


-- ============================================================
-- TRIGGERS
-- ============================================================

-- --------------------------------
-- Trigger: Log setelah INSERT user
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_insert_user
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NEW.id, 'CREATE', 'users', NEW.id, CONCAT('User baru ditambahkan: ', NEW.nama, ' (', NEW.role, ')'));
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah UPDATE user
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_update_user
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NEW.id, 'UPDATE', 'users', NEW.id, CONCAT('User diupdate: ', NEW.nama));
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah DELETE user
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_delete_user
AFTER DELETE ON users
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NULL, 'DELETE', 'users', OLD.id, CONCAT('User dihapus: ', OLD.nama, ' (', OLD.email, ')'));
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah INSERT alat
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_insert_alat
AFTER INSERT ON alat
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NULL, 'CREATE', 'alat', NEW.id, CONCAT('Alat baru: ', NEW.nama_alat, ' (', NEW.kode_alat, ')'));
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah UPDATE alat
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_update_alat
AFTER UPDATE ON alat
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NULL, 'UPDATE', 'alat', NEW.id, CONCAT('Alat diupdate: ', NEW.nama_alat));
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah INSERT peminjaman
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_insert_peminjaman
AFTER INSERT ON peminjaman
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NEW.user_id, 'CREATE', 'peminjaman', NEW.id, CONCAT('Peminjaman baru diajukan: ID #', NEW.id));
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah UPDATE peminjaman (approval/status change)
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_update_peminjaman
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
        VALUES (NEW.approved_by, 'UPDATE', 'peminjaman', NEW.id, 
            CONCAT('Status peminjaman #', NEW.id, ' berubah: ', OLD.status, ' -> ', NEW.status));
    END IF;
END //
DELIMITER ;

-- --------------------------------
-- Trigger: Log setelah INSERT pengembalian
-- --------------------------------
DELIMITER //
CREATE TRIGGER tr_after_insert_pengembalian
AFTER INSERT ON pengembalian
FOR EACH ROW
BEGIN
    INSERT INTO log_aktivitas (user_id, aksi, tabel, record_id, detail)
    VALUES (NEW.received_by, 'CREATE', 'pengembalian', NEW.id, 
        CONCAT('Pengembalian dicatat: ID #', NEW.id, ', Kondisi: ', NEW.kondisi_alat));
END //
DELIMITER ;


-- ============================================================
-- SEED DATA (Data Awal)
-- ============================================================

-- Password default: bcrypt hash dari 'password123'
-- $2y$12$YQdP4UDynHQ1GKIASqF0xOkRBMPl38k3M9nFr.PZ7XzFj8RMTHPHu

-- Admin default
INSERT INTO users (nama, email, password, role, no_telepon, alamat) VALUES
('Administrator', 'admin@peminjaman.test', '$2y$12$YQdP4UDynHQ1GKIASqF0xOkRBMPl38k3M9nFr.PZ7XzFj8RMTHPHu', 'admin', '081234567890', 'Jl. Admin No. 1'),
('Petugas Satu', 'petugas@peminjaman.test', '$2y$12$YQdP4UDynHQ1GKIASqF0xOkRBMPl38k3M9nFr.PZ7XzFj8RMTHPHu', 'petugas', '081234567891', 'Jl. Petugas No. 1'),
('Budi Peminjam', 'peminjam@peminjaman.test', '$2y$12$YQdP4UDynHQ1GKIASqF0xOkRBMPl38k3M9nFr.PZ7XzFj8RMTHPHu', 'peminjam', '081234567892', 'Jl. Peminjam No. 1');

-- Kategori alat
INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Elektronik', 'Alat-alat elektronik seperti laptop, proyektor, dll'),
('Perkakas', 'Alat perkakas seperti bor, gergaji, dll'),
('Laboratorium', 'Alat-alat laboratorium seperti mikroskop, tabung reaksi, dll'),
('Olahraga', 'Peralatan olahraga seperti bola, raket, dll'),
('Multimedia', 'Peralatan multimedia seperti kamera, speaker, dll');

-- Alat
INSERT INTO alat (kategori_id, kode_alat, nama_alat, deskripsi, jumlah_total, jumlah_tersedia, kondisi) VALUES
(1, 'ELK-001', 'Laptop Lenovo ThinkPad', 'Laptop untuk presentasi dan kerja', 5, 5, 'baik'),
(1, 'ELK-002', 'Proyektor Epson', 'Proyektor untuk presentasi ruang meeting', 3, 3, 'baik'),
(2, 'PRK-001', 'Bor Listrik Bosch', 'Bor listrik untuk keperluan maintenance', 4, 4, 'baik'),
(2, 'PRK-002', 'Gergaji Mesin', 'Gergaji mesin untuk pemotongan', 2, 2, 'baik'),
(3, 'LAB-001', 'Mikroskop Digital', 'Mikroskop digital untuk penelitian', 6, 6, 'baik'),
(3, 'LAB-002', 'Tabung Erlenmeyer Set', 'Set tabung erlenmeyer 250ml', 10, 10, 'baik'),
(4, 'OLR-001', 'Bola Basket Molten', 'Bola basket official size', 8, 8, 'baik'),
(4, 'OLR-002', 'Raket Badminton Yonex', 'Raket badminton untuk latihan', 10, 10, 'baik'),
(5, 'MLT-001', 'Kamera DSLR Canon', 'Kamera DSLR untuk dokumentasi', 3, 3, 'baik'),
(5, 'MLT-002', 'Speaker Portable JBL', 'Speaker portable untuk acara', 5, 5, 'baik');

-- ============================================================
-- SELESAI - Database siap digunakan
-- ============================================================
