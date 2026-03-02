-- Hapus kategori duplikat terlebih dahulu
DELETE FROM kategori WHERE id IN (6, 8, 9);

-- Tambahan Data Users untuk Semua Role
INSERT INTO users (nama, email, password, role, no_telepon, alamat) VALUES
-- Admin tambahan
('Dewi Admin', 'admin2@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'admin', '081234567892', 'Jl. Admin No. 2'),
('Eko Admin', 'admin3@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'admin', '081234567893', 'Jl. Admin No. 3'),

-- Petugas tambahan
('Siti Petugas', 'petugas2@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'petugas', '081234567894', 'Jl. Petugas No. 2'),
('Andi Petugas', 'petugas3@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'petugas', '081234567895', 'Jl. Petugas No. 3'),
('Rina Petugas', 'petugas4@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'petugas', '081234567896', 'Jl. Petugas No. 4'),

-- Peminjam tambahan (siswa/guru)
('Ayu Siswa', 'siswa1@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567897', 'Jl. Siswa No. 1'),
('Budi Siswa', 'siswa2@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567898', 'Jl. Siswa No. 2'),
('Citra Siswa', 'siswa3@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567899', 'Jl. Siswa No. 3'),
('Doni Siswa', 'siswa4@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567800', 'Jl. Siswa No. 4'),
('Eka Guru', 'guru1@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567801', 'Jl. Guru No. 1'),
('Fajar Guru', 'guru2@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567802', 'Jl. Guru No. 2'),
('Gita Siswa', 'siswa5@peminjaman.test', '$2y$12$LQv3c1yYqBTCRbPl7l4FbuZ3TH/Tg5kJO2K5mPVFZQXdZ3Z9Z9Z9Z', 'peminjam', '081234567803', 'Jl. Siswa No. 5');

-- Update kategori yang sudah ada untuk sesuai kebutuhan sekolah
UPDATE kategori SET nama_kategori = 'Elektronik', deskripsi = 'Peralatan elektronik seperti laptop, proyektor' WHERE id = 1;
UPDATE kategori SET nama_kategori = 'Komputer', deskripsi = 'Perangkat komputer dan aksesoris' WHERE id = 7;
UPDATE kategori SET nama_kategori = 'Seni & Musik', deskripsi = 'Peralatan seni, musik, dan ekstrakurikuler' WHERE id = 10;

-- Data Alat Sekolah Tambahan (kode unik)
INSERT INTO alat (kode_alat, nama_alat, kategori_id, jumlah_total, jumlah_tersedia, kondisi, deskripsi) VALUES
-- Elektronik / Multimedia (kategori 1 & 5)
('PRJ-001', 'Proyektor BenQ MX550', 1, 8, 8, 'baik', 'Proyektor untuk presentasi kelas'),
('PRJ-002', 'Proyektor Portabel Mini', 5, 4, 4, 'baik', 'Proyektor portable untuk ruang kecil'),
('SPK-001', 'Speaker Active 100W', 5, 6, 6, 'baik', 'Speaker untuk acara sekolah'),
('MIC-002', 'Microphone Wireless Set', 5, 8, 8, 'baik', 'Mic wireless untuk presentasi'),
('CAM-001', 'Kamera DSLR Nikon', 5, 3, 3, 'baik', 'Kamera untuk dokumentasi sekolah'),
('VID-001', 'Handycam Sony 4K', 5, 2, 2, 'baik', 'Video recorder untuk pembelajaran'),
('TRP-001', 'Tripod Standing', 5, 6, 6, 'baik', 'Tripod untuk kamera'),
('PTR-001', 'Pointer Laser Wireless', 1, 12, 12, 'baik', 'Pointer untuk presentasi'),

-- Komputer & Aksesoris (kategori 7)
('LPT-002', 'Laptop Asus VivoBook', 7, 15, 15, 'baik', 'Laptop untuk guru dan siswa'),
('LPT-003', 'Laptop HP Pavilion', 7, 10, 10, 'baik', 'Laptop untuk lab komputer'),
('LCD-001', 'Monitor LED 24 inch', 7, 12, 12, 'baik', 'Monitor untuk komputer'),
('PRN-001', 'Printer HP LaserJet', 7, 4, 4, 'baik', 'Printer untuk admin'),
('SCA-001', 'Scanner Flatbed', 7, 2, 2, 'baik', 'Scanner dokumen'),
('TAB-001', 'Tablet Android 10 inch', 7, 20, 20, 'baik', 'Tablet untuk pembelajaran digital'),
('PWB-001', 'Power Bank 30000mAh', 7, 15, 15, 'baik', 'Power bank untuk perangkat'),

-- Laboratorium (kategori 3)
('MIC-003', 'Mikroskop Binokuler', 3, 20, 20, 'baik', 'Mikroskop untuk praktikum biologi'),
('OSC-001', 'Osiloskop Digital', 3, 5, 5, 'baik', 'Alat ukur untuk lab fisika'),
('MLT-003', 'Multimeter Digital', 3, 15, 15, 'baik', 'Alat ukur arus dan tegangan'),
('BKR-001', 'Beaker Set 50-500ml', 3, 25, 25, 'baik', 'Gelas ukur untuk lab kimia'),
('TUB-001', 'Test Tube Rack Set', 3, 30, 30, 'baik', 'Tabung reaksi dengan rak'),
('BRN-001', 'Bunsen Burner', 3, 10, 10, 'baik', 'Pembakar untuk praktikum kimia'),
('TST-001', 'Thermometer Digital', 3, 12, 12, 'baik', 'Termometer untuk lab'),

-- Olahraga (kategori 4)
('BLA-002', 'Bola Voli Mikasa', 4, 12, 12, 'baik', 'Bola voli standar'),
('BLA-003', 'Bola Sepak Nike Size 5', 4, 15, 15, 'baik', 'Bola sepak untuk lapangan'),
('RKT-001', 'Raket Badminton Set', 4, 20, 20, 'baik', 'Raket badminton dengan cover'),
('MAT-001', 'Matras Senam Tebal', 4, 18, 18, 'baik', 'Matras untuk senam lantai'),
('NET-001', 'Net Voli + Tiang', 4, 3, 3, 'baik', 'Net voli dengan tiang portable'),
('NET-002', 'Net Badminton', 4, 6, 6, 'baik', 'Net untuk lapangan badminton'),
('CON-001', 'Cone Training Set', 4, 50, 50, 'baik', 'Cone untuk latihan olahraga'),
('STW-001', 'Stopwatch Digital', 4, 10, 10, 'baik', 'Stopwatch untuk olahraga'),

-- Seni & Musik (kategori 10)
('GTR-001', 'Gitar Akustik Yamaha', 10, 10, 10, 'baik', 'Gitar untuk ekstrakurikuler'),
('KBD-001', 'Keyboard Casio CTK', 10, 5, 5, 'baik', 'Keyboard untuk pembelajaran musik'),
('DRM-001', 'Drum Set 5 Piece', 10, 2, 2, 'baik', 'Drum set untuk band'),
('EZL-001', 'Easel Lukis Portable', 10, 15, 15, 'baik', 'Kuda-kuda untuk melukis'),
('CNV-001', 'Canvas Board 30x40cm', 10, 30, 30, 'baik', 'Kanvas lukis berbagai ukuran'),
('PNT-001', 'Cat Akrilik Set 24 Warna', 10, 20, 20, 'baik', 'Cat untuk melukis'),
('BRS-001', 'Brush Set Lukis', 10, 25, 25, 'baik', 'Kuas lukis berbagai ukuran');

-- Perkakas (kategori 2) - untuk bengkel/TIK
UPDATE alat SET nama_alat = 'Tang Set Lengkap', deskripsi = 'Set tang untuk perbaikan' WHERE kode_alat = 'PRK-001';
INSERT INTO alat (kode_alat, nama_alat, kategori_id, jumlah_total, jumlah_tersedia, kondisi, deskripsi) VALUES
('PRK-003', 'Obeng Set 20 in 1', 2, 15, 15, 'baik', 'Set obeng serbaguna'),
('PRK-004', 'Tool Box Lengkap', 2, 8, 8, 'baik', 'Kotak perkakas lengkap'),
('PRK-005', 'Solder Listrik 40W', 2, 10, 10, 'baik', 'Solder untuk elektronik'),
('PRK-006', 'Glue Gun Set', 2, 12, 12, 'baik', 'Lem tembak untuk prakarya');

-- Tambahan umum
INSERT INTO alat (kode_alat, nama_alat, kategori_id, jumlah_total, jumlah_tersedia, kondisi, deskripsi) VALUES
('WBD-001', 'Whiteboard Portable 90x60', 1, 8, 8, 'baik', 'Papan tulis portable'),
('EXT-001', 'Extension Cable 10M', 1, 20, 20, 'baik', 'Kabel ekstensi listrik'),
('ADT-001', 'Adaptor Universal Set', 1, 12, 12, 'baik', 'Adaptor serbaguna');
