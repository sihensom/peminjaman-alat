-- Hapus kategori duplikat terlebih dahulu (jika ada)
DELETE FROM kategori WHERE id IN (6, 8, 9);

-- Data Alat Sekolah Baru (dengan kode unik, tidak duplikat dengan yang sudah ada)
-- Kode yang sudah ada: ELK-001, ELK-002, PRK-001, PRK-002, LAB-001, LAB-002, OLR-001, OLR-002, MLT-001, MLT-002

INSERT INTO alat (kode_alat, nama_alat, kategori_id, jumlah_total, jumlah_tersedia, kondisi, deskripsi) VALUES
-- Elektronik / Multimedia (kategori 1 & 5)
('PRJ-001', 'Proyektor BenQ MX550', 1, 8, 8, 'baik', 'Proyektor untuk presentasi kelas'),
('PRJ-002', 'Proyektor Portabel Mini', 5, 4, 4, 'baik', 'Proyektor portable untuk ruang kecil'),
('SPK-001', 'Speaker Active 100W', 5, 6, 6, 'baik', 'Speaker untuk acara sekolah'),
('MIC-001', 'Microphone Wireless Set', 5, 8, 8, 'baik', 'Mic wireless untuk presentasi'),
('CAM-001', 'Kamera DSLR Nikon D3500', 5, 3, 3, 'baik', 'Kamera untuk dokumentasi sekolah'),
('VID-001', 'Handycam Sony 4K', 5, 2, 2, 'baik', 'Video recorder untuk pembelajaran'),
('TRP-001', 'Tripod Standing 1.5m', 5, 6, 6, 'baik', 'Tripod untuk kamera'),
('PTR-001', 'Pointer Laser Wireless', 1, 12, 12, 'baik', 'Pointer untuk presentasi'),
('WBD-001', 'Whiteboard Portable 90x60', 1, 8, 8, 'baik', 'Papan tulis portable'),
('EXT-001', 'Extension Cable 10M', 1, 20, 20, 'baik', 'Kabel ekstensi listrik'),

-- Komputer (kategori 7, gunakan ID yang sudah ada)
('LPT-002', 'Laptop Asus VivoBook A412', 7, 15, 15, 'baik', 'Laptop untuk guru dan siswa'),
('LPT-003', 'Laptop HP Pavilion 14', 7, 10, 10, 'baik', 'Laptop untuk lab komputer'),
('LCD-001', 'Monitor LED LG 24 inch', 7, 12, 12, 'baik', 'Monitor untuk komputer'),
('PRN-001', 'Printer HP LaserJet P1102', 7, 4, 4, 'baik', 'Printer untuk admin'),
('SCA-001', 'Scanner Epson Flatbed', 7, 2, 2, 'baik', 'Scanner dokumen'),
('TAB-001', 'Tablet Samsung Tab A 10', 7, 20, 20, 'baik', 'Tablet untuk pembelajaran digital'),
('PWB-001', 'Power Bank 30000mAh', 7, 15, 15, 'baik', 'Power bank untuk perangkat'),
('ADT-001', 'Adaptor Universal Set', 7, 12, 12, 'baik', 'Adaptor serbaguna'),

-- Laboratorium (kategori 3)
('MIC-002', 'Mikroskop Binokuler Olympus', 3, 20, 20, 'baik', 'Mikroskop untuk praktikum biologi'),
('OSC-001', 'Osiloskop Digital Tektronix', 3, 5, 5, 'baik', 'Alat ukur untuk lab fisika'),
('MLT-003', 'Multimeter Digital Fluke', 3, 15, 15, 'baik', 'Alat ukur arus dan tegangan'),
('BKR-001', 'Beaker Set 50-500ml Pyrex', 3, 25, 25, 'baik', 'Gelas ukur untuk lab kimia'),
('TUB-001', 'Test Tube Rack Set 50pcs', 3, 30, 30, 'baik', 'Tabung reaksi dengan rak'),
('BRN-001', 'Bunsen Burner Gas', 3, 10, 10, 'baik', 'Pembakar untuk praktikum kimia'),
('TST-001', 'Thermometer Digital -50 to 300C', 3, 12, 12, 'baik', 'Termometer untuk lab'),
('PIP-001', 'Pipet Tetes Set 100pcs', 3, 40, 40, 'baik', 'Pipet untuk lab kimia'),
('ERN-001', 'Erlenmeyer Flask Set', 3, 30, 30, 'baik', 'Labu Erlenmeyer berbagai ukuran'),

-- Olahraga (kategori 4)
('BLA-002', 'Bola Voli Mikasa MVA200', 4, 12, 12, 'baik', 'Bola voli standar pertandingan'),
('BLA-003', 'Bola Sepak Nike Premier', 4, 15, 15, 'baik', 'Bola sepak size 5'),
('BLA-004', 'Bola Futsal Specs', 4, 10, 10, 'baik', 'Bola futsal size 4'),
('RKT-001', 'Raket Badminton Li-Ning', 4, 20, 20, 'baik', 'Raket badminton profesional'),
('MAT-001', 'Matras Senam Tebal 5cm', 4, 18, 18, 'baik', 'Matras untuk senam lantai'),
('NET-001', 'Net Voli + Tiang Portable', 4, 3, 3, 'baik', 'Net voli dengan tiang'),
('NET-002', 'Net Badminton Set', 4, 6, 6, 'baik', 'Net untuk lapangan badminton'),
('CON-001', 'Cone Training Set 50pcs', 4, 50, 50, 'baik', 'Cone untuk latihan'),
('STW-001', 'Stopwatch Digital Casio', 4, 10, 10, 'baik', 'Stopwatch untuk olahraga'),
('PMP-001', 'Pompa Bola Manual', 4, 8, 8, 'baik', 'Pompa untuk bola'),

-- Seni & Musik (kategori 10)
('GTR-001', 'Gitar Akustik Yamaha F310', 10, 10, 10, 'baik', 'Gitar untuk ekstrakurikuler'),
('GTR-002', 'Gitar Elektrik Fender', 10, 5, 5, 'baik', 'Gitar elektrik untuk band'),
('KBD-001', 'Keyboard Casio CTK-3500', 10, 5, 5, 'baik', 'Keyboard pembelajaran musik'),
('DRM-001', 'Drum Set 5 Piece Tama', 10, 2, 2, 'baik', 'Drum set untuk band sekolah'),
('BAS-001', 'Bass Elektrik Ibanez', 10, 3, 3, 'baik', 'Bass untuk band'),
('AMP-001', 'Amplifier Gitar 50W', 10, 4, 4, 'baik', 'Amplifier untuk gitar'),
('EZL-001', 'Easel Lukis Portable', 10, 15, 15, 'baik', 'Kuda-kuda untuk melukis'),
('CNV-001', 'Canvas Board 30x40cm 50pcs', 10, 30, 30, 'baik', 'Kanvas lukis'),
('PNT-001', 'Cat Akrilik Set 24 Warna', 10, 20, 20, 'baik', 'Cat untuk melukis'),
('BRS-001', 'Brush Set Lukis 12pcs', 10, 25, 25, 'baik', 'Kuas lukis berbagai ukuran'),
('PLT-001', 'Palet Lukis Plastik', 10, 30, 30, 'baik', 'Palet untuk mencampur cat'),

-- Perkakas (kategori 2)
('PRK-003', 'Obeng Set Precision 32 in 1', 2, 15, 15, 'baik', 'Set obeng untuk elektronik'),
('PRK-004', 'Tool Box Kenmaster Lengkap', 2, 8, 8, 'baik', 'Kotak perkakas lengkap'),
('PRK-005', 'Solder Listrik 40W Goot', 2, 10, 10, 'baik', 'Solder untuk elektronik'),
('PRK-006', 'Glue Gun Set + Lem', 2, 12, 12, 'baik', 'Lem tembak untuk prakarya'),
('PRK-007', 'Tang Kombinasi Set 3pcs', 2, 10, 10, 'baik', 'Tang potong, buaya, kombinasi'),
('PRK-008', 'Meteran 5M Stanley', 2, 8, 8, 'baik', 'Meteran untuk ukur');

-- Note: Total alat baru = ~58 item
-- Total alat di database setelah import = 10 (existing) + 58 (new) = 68 alat
