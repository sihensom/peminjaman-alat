# 🛠️ Sistem Peminjaman Alat (Edisi Premium)

Aplikasi berbasis web modern untuk manajemen peminjaman alat sekolah/kantor. Dibuat menggunakan **Laravel 11** dengan desain UI/UX yang premium, dinamis, dan responsif.

---

## 📂 Struktur Folder Penting

Untuk memudahkan pemahaman alur aplikasi, berikut adalah folder-folder penting yang digunakan dalam sistem ini:

- `app/Http/Controllers/` : Berisi logika utama aplikasi (pengolahan request dan koneksi ke view).
  - `Admin/` : Controller khusus untuk level Administrator (CRUD alat, kategori, user, dll).
  - `Petugas/` : Controller khusus untuk level Petugas (persetujuan peminjaman, monitor pengembalian, laporan).
  - `Peminjam/` : Controller khusus untuk level Peminjam (katalog alat, pengajuan pinjam).
- `app/Models/` : Berisi struktur Model database, misalnya `User.php`, `Alat.php`, `Peminjaman.php`, `Pengembalian.php`. Di sinilah relasi database didefinisikan (One-to-Many, dll).
- `resources/views/` : Berisi semua file tampilan **Blade Template (Frontend)**. Folder ini juga dipisahkan sesuai role:
  - `admin/` : Halaman dashboard dan manajemen untuk Admin.
  - `petugas/` : Halaman dashboard, persetujuan, dan monitor untuk Petugas.
  - `peminjam/` : Halaman dashboard dan proses pinjam untuk Siswa/Guru.
  - `auth/` : Halaman Login.
  - `layouts/` : Kerangka antarmuka utama seperti _Header_ dan _Sidebar_.
- `routes/web.php` : Tempat seluruh konfigurasi URL (Routing) berada, yang dikelompokkan dengan Middleware berdasarkan tipe Role pengguna.
- `database/` : Berisi file SQL dump (`database.sql`) yang berisi tabel, stored procedure, dan trigger siap pakai.

---

## 🗄️ Struktur dan Dokumentasi Tabel Database

Aplikasi ini menggunakan database **MySQL (`db_peminjaman_alat`)** dengan 6 tabel utama:

### 1. Tabel `users`
Menyimpan kredensial semua pengguna di sistem.
- **Kolom Penting:** `id`, `nama`, `email`, `password`, `role` (admin/petugas/peminjam).
- **Relasi:** Dipakai oleh tabel `peminjaman`, `pengembalian`, dan `log_aktivitas` untuk mengetahui siapa pelaku tindakan.

### 2. Tabel `kategori`
Pengelompokan jenis-jenis alat (misal: Elektronik, Laboratorium).
- **Kolom Penting:** `id`, `nama_kategori`.
- **Relasi:** Induk dari tabel `alat`.

### 3. Tabel `alat`
Pusat data alat yang akan dipinjamkan ke siswa/guru.
- **Kolom Penting:** `id`, `kategori_id` (FK), `nama_alat`, `jumlah_total`, `jumlah_tersedia` (update otomatis), `kondisi`.
- **Relasi:** Menyimpan `id` kategori, digunakan dalam detail pinjam di tabel `peminjaman`.

### 4. Tabel `peminjaman`
Mencatat siapa meminjam alat apa, kapan, dan status terkininya.
- **Kolom Penting:** `id`, `user_id` (Aktor Peminjam), `alat_id` (Alat yang Dipinjam), `jumlah_pinjam`, `status` (pending → disetujui → dipinjam → dikembalikan).
- **Relasi:** Menyambungkan `users` dan `alat`.

### 5. Tabel `pengembalian`
Mencatat informasi spesifik ketika alat dikembalikan (misal: kondisinya baik/rusak saat dikembalikan).
- **Kolom Penting:** `id`, `peminjaman_id` (Transaksinya), `kondisi_alat`, `tanggal_kembali_aktual`.
- **Relasi:** Bersambung dengan rekam `peminjaman` yang telah sukses.

### 6. Tabel `log_aktivitas` (Audit Trail)
Pencatatan aktivitas _(History)_ apa pun (Tambah/Ubah/Hapus/Proses) yang dilakukan semua user untuk kebutuhan transparansi Admin.
- **Kolom Penting:** `id`, `user_id`, `aksi`, `tabel`, `detail`.

---

## ⚡ Store Procedure & Trigger (Otomatisasi Database)

Sistem ini didukung logika tingkat database (Stored Procedure dan Triggers), memastikan konsistensi dan integritas di level *backend terendah*.

**Daftar Penjelasan 8 Trigger Lengkap (Pencatat Log Aktivitas):**

Sistem ini memiliki **8 Trigger** yang menempel pada 4 tabel (*users, alat, peminjaman, pengembalian*). Semua trigger ini bermuara pada satu tujuan: **mencatat otomatis ke tabel `log_aktivitas`**.

---

### 1. `tr_after_insert_user` (Trigger Tambah User)
- **Kondisi (When):** Aktif secara otomatis setiap kali ada data **baru** yang berhasil ditambahkan (INSERT) ke dalam tabel `users`.
- **Aksi (What):** Menambahkan baris baru ke tabel `log_aktivitas` dengan histori tindakan `"CREATE"`.
- **Aktor (Who):** Biasanya dilakukan oleh **Admin** (karena hanya Admin yang memiliki hak akses CRUD User).
- **Tujuan (Why):** Memastikan setiap pembuatan akun baru terlacak dengan jelas, meliputi nama dan role user baru tersebut.

### 2. `tr_after_update_user` (Trigger Edit User)
- **Kondisi (When):** Aktif secara otomatis setiap kali ada data yang sudah ada diubah/diedit (UPDATE) pada tabel `users`.
- **Aksi (What):** Menambahkan baris baru ke tabel `log_aktivitas` dengan histori tindakan `"UPDATE"`.
- **Aktor (Who):** Dilakukan oleh **Admin**.
- **Tujuan (Why):** Memantau aktivitas perubahan profil atau _role_ akun (misal jika Admin sengaja/tidak sengaja menaikkan _role_ peminjam menjadi petugas).

### 3. `tr_after_delete_user` (Trigger Hapus User)
- **Kondisi (When):** Aktif secara otomatis setiap kali ada data yang dihapus (DELETE) dari tabel `users`.
- **Aksi (What):** Menambahkan baris baru ke tabel `log_aktivitas` dengan histori tindakan `"DELETE"`.
- **Aktor (Who):** Dilakukan oleh **Admin**.
- **Tujuan (Why):** Rekam jejak permanen bahwa suatu akun pernah ada dan telah dihapus. Ini sangat krusial jika akun dihapus untuk menghilangkan jejak peminjaman fiktif.

---

### 4. `tr_after_insert_alat` (Trigger Tambah Alat)
- **Kondisi (When):** Aktif secara otomatis setiap kali ada barang/alat **baru** yang ditambahkan (INSERT) ke tabel `alat`.
- **Aksi (What):** Menambahkan baris baru ke tabel `log_aktivitas` dengan histori tindakan `"CREATE"` beserta detail kode alat.
- **Aktor (Who):** Dilakukan oleh **Admin**.
- **Tujuan (Why):** Mencatat aset masuk (inventarisasi awal) sebagai bukti bahwa stok aset telah divalidasi ke dalam sistem.

### 5. `tr_after_update_alat` (Trigger Edit Alat)
- **Kondisi (When):** Aktif secara otomatis setiap kali informasi barang/alat (nama, jumlah, kondisi) diperbarui (UPDATE) di tabel `alat`.
- **Aksi (What):** Menambahkan baris baru ke tabel `log_aktivitas` dengan histori tindakan `"UPDATE"`.
- **Aktor (Who):** Dipicu secara langsung oleh **Admin** yang mengedit manual, ATAU dipicu oleh sistem saat **Petugas/Peminjam** memproses peminjaman/pengembalian (otomatis mengurangi/menambah `jumlah_tersedia`).
- **Tujuan (Why):** Melacak jejak modifikasi inventaris, entah itu update stok dari alat yang dipinjam, atau perubahan kondisi fisik alat (misal _baik_ menjadi _rusak_).

---

### 6. `tr_after_insert_peminjaman` (Trigger Request Pinjam Baru)
- **Kondisi (When):** Aktif saat tabel `peminjaman` mendapatkan baris data baru (INSERT), dengan status awal `"pending"`.
- **Aksi (What):** Menambahkan baris ke `log_aktivitas` dengan histori `"CREATE"` yang memuat ID transaksi pengajuan.
- **Aktor (Who):** Memiliki _user_id_ dari sisi **Peminjam** (Siswa/Guru).
- **Tujuan (Why):** Mencatat momen persis secara *real-time* kapan seorang peminjam pertama kali mengetuk tombol *"Ajukan Peminjaman"*.

### 7. `tr_after_update_peminjaman` (Trigger Perubahan Status Peminjaman)
- **Kondisi (When):** Aktif setiap kali ada kolom dalam tabel `peminjaman` yang berubah (UPDATE), *KHUSUSNYA* jika kolom `status` berubah (misalnya dari *"pending"* ke *"disetujui"*, atau *"disetujui"* ke *"dikembalikan"*).
- **Aksi (What):** Membandingkan status `OLD` dan `NEW`. Jika beda, tambahkan baris Log dengan histori `"UPDATE"` beserta alur detil (contoh pesan: "_pending -> disetujui_").
- **Aktor (Who):** Biasanya dilakukan oleh **Petugas** (saat menyetujui, menolak, atau menerima pengembalian lewat Controller).
- **Tujuan (Why):** Ini merupakan salah satu trigger paling fundamental untuk transparansi alur birokrasi barang (kapan disetujui, dan kapan sah dikembalikan).

---

### 8. `tr_after_insert_pengembalian` (Trigger Terima Pengembalian)
- **Kondisi (When):** Aktif saat petugas mengeklik terima alat, yang menyebabkan terbentuknya baris detail baru (INSERT) di tabel `pengembalian`.
- **Aksi (What):** Menambahkan log aksi `"CREATE"` ke `log_aktivitas` dengan mencatat Kondisi barang tersebut saat dikembalikan.
- **Aktor (Who):** Dilakukan oleh **Petugas** (menggunakan referensi _received_by_).
- **Tujuan (Why):** Menjadi bukti valid serah terima barang secara fisik. Jika alat ternyata kondisinya `"rusak"`, ini adalah jejak *last touch* saksi matanya.

---

## 🌟 Fitur Utama (Matrix Role & Hak Akses)

Semua role beserta hak aksenys sudah di-implementasikan sesuai tabel referensi tugas. Berikut ringkasannya:

| Fitur | Admin | Petugas | Peminjam |
|-------|:-----:|:-------:|:--------:|
| **Login** | ✅ | ✅ | ✅ |
| **Logout** | ✅ | ✅ | ✅ |
| **CRUD User** | ✅ | | |
| **CRUD Alat** | ✅ | | |
| **CRUD Kategori** | ✅ | | |
| **CRUD Data Peminjaman** | ✅ | | |
| **CRUD Pengembalian** | ✅ | | |
| **Log Aktifitas** | ✅ | | |
| **Menyetujui Peminjaman** | | ✅ | |
| **Memantau Pengembalian** | | ✅ | |
| **Mencetak Laporan** | | ✅ | |
| **Melihat Daftar Alat** | | | ✅ |
| **Mengajukan Peminjaman** | | | ✅ |
| **Mengembalikan Alat** | | | ✅ |

---

## 📊 ERD (Entity Relationship Diagram)

```
┌──────────────┐       ┌──────────────────┐       ┌──────────────┐
│   KATEGORI   │       │      USERS       │       │ LOG_AKTIVITAS│
├──────────────┤       ├──────────────────┤       ├──────────────┤
│ PK id        │       │ PK id            │       │ PK id        │
│    nama_     │       │    nama          │◄──────│ FK user_id   │
│    kategori  │       │    email         │       │    aksi      │
│    deskripsi │       │    password      │       │    tabel     │
│    created_at│       │    role (enum)   │       │    record_id │
│    updated_at│       │    no_telepon    │       │    detail    │
└──────┬───────┘       │    alamat        │       │    created_at│
       │1              │    created_at    │       └──────────────┘
       │               │    updated_at    │
       │               └───────┬──────────┘
       │                       │1
       │N                      │N
┌──────▼───────┐       ┌───────▼──────────┐
│     ALAT     │       │   PEMINJAMAN     │
├──────────────┤       ├──────────────────┤
│ PK id        │◄──────│ PK id            │
│ FK kategori_ │   1:N │ FK user_id       │
│    id        │       │ FK alat_id       │
│    kode_alat │       │ FK approved_by   │──────► USERS
│    nama_alat │       │    jumlah_pinjam │
│    deskripsi │       │    tanggal_pinjam│
│    jumlah_   │       │    tanggal_      │
│    total     │       │    kembali_      │
│    jumlah_   │       │    rencana       │
│    tersedia  │       │    status (enum) │
│    kondisi   │       │    keterangan    │
│    gambar    │       │    created_at    │
│    created_at│       │    updated_at    │
│    updated_at│       └───────┬──────────┘
└──────────────┘               │1
                               │
                               │1
                       ┌───────▼──────────┐
                       │  PENGEMBALIAN    │
                       ├──────────────────┤
                       │ PK id            │
                       │ FK peminjaman_id │
                       │ FK received_by   │──────► USERS
                       │    tanggal_      │
                       │    kembali_aktual│
                       │    kondisi_alat  │
                       │    keterangan    │
                       │    created_at    │
                       └──────────────────┘
```

**Penjelasan Relasi:**
- `kategori` **1:N** `alat` — Satu kategori memiliki banyak alat.
- `users` **1:N** `peminjaman` — Satu user dapat membuat banyak peminjaman.
- `alat` **1:N** `peminjaman` — Satu alat dapat dipinjam berkali-kali.
- `peminjaman` **1:1** `pengembalian` — Satu peminjaman punya satu laporan pengembalian.
- `users` **1:N** `log_aktivitas` — Sistem mencatat semua perubahan dari setiap aktivitas user.

---

## 📈 Flowchart / Diagram Alur

### a. Proses Login
```
[Start] → [Buka halaman login] → [Input email & password]
   ↓
[Validasi form] --Kosong?--→ [Tampilkan error]
   ↓ Valid
[Cek DB] --Tidak Cocok?--→ [Gagal login]
   ↓ Cocok
[Regenerate session] → [Log aktivitas: Login]
   ↓
[Cek role: Admin / Petugas / Peminjam] --→ [Redirect sesuai Dashboard role] → [End]
```

### b. Proses Peminjaman Alat
```
[Start] → [Peminjam Login & Buka menu Alat] → [Pilih Alat]
   ↓
[Isi form: jumlah, tgl pinjam, tgl kembali]
   ↓
[Validasi Stok] --Kurang?--→ [Error: Stok tidak cukup]
   ↓ Cukup
[INSERT ke peminjaman (status: pending)] → [Log aktivitas]
   ↓
[Petugas buka menu Persetujuan] → [Klik Setujui/Tolak]
   ↓
[Jika Setujui] → [UPDATE status='disetujui'] → [UPDATE alat: jumlah_tersedia -= jumlah_pinjam] → [End]
```

### c. Proses Pengembalian Alat
```
[Start] → [Petugas buka menu Monitor] → [Pilih peminjaman berjalan]
   ↓
[Klik "Terima Pengembalian" & Isi Kondisi]
   ↓
[INSERT ke pengembalian] → [UPDATE peminjaman status='dikembalikan']
   ↓
[UPDATE alat: jumlah_tersedia += jumlah_pinjam]
   ↓
[Kondisi rusak?] --Ya--→ [UPDATE alat kondisi='rusak']
   ↓
[Log aktivitas] → [End]
```

---

## ✅ Skenario Uji Coba (Test Cases)

| No | Kasus Uji | Input | Hasil yang Diharapkan | Status |
|----|-----------|-------|----------------------|--------|
| **1** | **Login Normal** | User: admin, Pass: 123456 | Berhasil masuk ke dashboard | ✅ Lulus |
| **2** | **Tambah Alat** | Input lengkap data alat | Data tersimpan, log terekam | ✅ Lulus |
| **3** | **Validasi Stok** | Pinjam 999 (stok hanya 5) | Ditolak, notifikasi error stok tidak cukup | ✅ Lulus |
| **4** | **Persetujuan** | Petugas klik `Setujui` | Status berubah, stok alat berkurang | ✅ Lulus |
| **5** | **Pengembalian Rusak** | Petugas set kondisi `Rusak` | Stok kembali bertambah, kondisi alat jadi rusak | ✅ Lulus |
| **6** | **Akses Ilegal** | Peminjam akses URL `/admin` | Redirect `403 Access Denied` / kembali | ✅ Lulus |

---

## 💻 Contoh Query Penting Berdasarkan Fungsi Frontend

Berikut adalah cuplikan _Business Logic_ Query melalui pemanfaatan **Eloquent (ORM Laravel)** yang bekerja pada berbagai fungsi krusial:

### 1. Fungsi Peminjam: Mengajukan Peminjaman
*(View: `resources/views/peminjam/pinjam/create.blade.php`)*
```php
// Mengecek apakah alat yang diminta valid dan tersedia di DB
$alat = Alat::findOrFail($request->alat_id);
if ($alat->jumlah_tersedia < $request->jumlah) {
    // Validasi stok ditolak
}

// Laravel Eloquent mengeksekusi INSERT ke tabel `peminjaman`
Peminjaman::create([
    'user_id' => auth()->id(),
    'alat_id' => $alat->id,
    'jumlah_pinjam' => $request->jumlah_pinjam,
    'tanggal_pinjam' => $request->tanggal_pinjam,
    'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
    'status' => 'pending'
]);
```

### 2. Fungsi Petugas: Menyetujui Peminjaman
*(View: `resources/views/petugas/approval/index.blade.php`)*
```php
// Query untuk mengambil List peminjaman yang Butuh Persetujuan Petugas
$pendingList = Peminjaman::with(['user', 'alat'])
                  ->where('status', 'pending')
                  ->latest()
                  ->get();

// Saat Disetujui (Query UPDATE Multi-Tabel)
DB::transaction(function () use ($peminjaman) {
    $peminjaman->update([
        'status' => 'disetujui',
        'approved_by' => auth()->id()
    ]);
    // Potong ketersediaan alat (- minus)
    $peminjaman->alat->decrement('jumlah_tersedia', $peminjaman->jumlah_pinjam); 
});
```

### 3. Fungsi Petugas: Memantau dan Terima Pengembalian
*(View: `resources/views/petugas/monitor/index.blade.php`)*
```php
// Saat Barang Dikembalikan ke tangan Petugas
DB::transaction(function () use ($peminjaman, $request) {
    // INSERT laporan ke pengembalian
    Pengembalian::create([
        'peminjaman_id' => $peminjaman->id,
        'tanggal_kembali_aktual' => now(),
        'kondisi_alat' => $request->kondisi_alat,
    ]);
    // UPDATE peminjaman selesai
    $peminjaman->update(['status' => 'dikembalikan']);
    // Tambah ketersediaan alat komoditas (+ plus)
    $peminjaman->alat->increment('jumlah_tersedia', $peminjaman->jumlah_pinjam);
});
```

### 4. Fungsi Petugas: Mencetak Laporan
*(View: `resources/views/petugas/laporan/index.blade.php`)*
```php
// Filter List Data Laporan Peminjaman Berdasarkan Waktu
$peminjaman = Peminjaman::with(['user', 'alat', 'pengembalian'])
    ->whereBetween('tanggal_pinjam', [$request->dari, $request->sampai])
    ->latest()
    ->get();
```

### 5. Fungsi Admin: Dashboard Analytics Data (READ Aggregation)
*(View: `resources/views/admin/dashboard.blade.php`)*
```php
// Query Agregasi hitung total di dashboard
$stats = [
    'total_users' => User::count(),
    'total_alat' => Alat::count(),
    'peminjaman_aktif' => Peminjaman::whereIn('status', ['pending', 'disetujui', 'dipinjam'])->count(),
    'alat_dipinjam' => Peminjaman::whereIn('status', ['disetujui', 'dipinjam'])->sum('jumlah_pinjam'),
];
```

---

## 🚀 Panduan Setup & Instalasi Terbaru

### 1. Kloning & Install Dependencies
```bash
# Install library PHP
composer install
# Install Node Library (Bila diperlukan)
npm install && npm run build
```

### 2. Konfigurasi Database Environment
Salin file `.env.example` ke `.env` lalu aktifkan konfigurasi koneksi serta optimasi caching:
```env
DB_CONNECTION=mysql
DB_DATABASE=db_peminjaman_alat
DB_USERNAME=root
DB_PASSWORD=

# Pastikan settingan caching ke file agar tidak ada isu jika tabel cache minus
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 3. Setup Database & Hak Akses Login
Jalankan migrasi atau import `database.sql` ke database `db_peminjaman_alat`.

**Akun Administrator (Default Baru):**
- **Email:** `admin@gmail.com`
- **Password:** `123456`

### 4. Jalankan Aplikasi
```bash
php artisan serve
```
Buka browser dan buka: `http://localhost:8000`

---
*Dibuat untuk tugas UKK Paket 1 - SMK Pusat Keunggulan.*