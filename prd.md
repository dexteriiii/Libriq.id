# Product Requirements Document (PRD)
## Sistem Manajemen Perpustakaan Digital

**Versi:** 2.0 (Revisi)
**Status:** Draft untuk Review
**Arsitektur:** Hybrid API–Local Database

---

## 1. Ringkasan Eksekutif

Sistem Manajemen Perpustakaan Digital adalah platform berbasis web yang mendigitalisasi seluruh alur sirkulasi buku fisik — mulai dari katalogisasi, peminjaman, pengembalian, hingga perhitungan denda — dengan menggabungkan sumber data eksternal (Google Books API) dan basis data lokal untuk memastikan keandalan operasional bahkan saat konektivitas API terganggu.

**Tujuan Utama:**
1. Mendigitalisasi sirkulasi buku fisik (peminjaman & pengembalian).
2. Mengotomasi perhitungan denda keterlambatan.
3. Menyederhanakan manajemen inventaris melalui arsitektur hybrid API–database lokal.
4. Menyediakan pengalaman pengguna yang cepat, konsisten, dan andal untuk admin maupun anggota.

**Target Pengguna:**
| Peran | Deskripsi |
| :--- | :--- |
| **Administrator (Pustakawan)** | Mengelola katalog, memvalidasi sirkulasi, memantau statistik, mengelola pengguna |
| **Student/Member** | Menjelajah katalog, mengajukan peminjaman, memantau riwayat & denda pribadi |

---

## 2. Tech Stack

| Layer | Teknologi | Catatan |
| :--- | :--- | :--- |
| Backend Framework | Laravel 12 | REST API + Blade/Livewire hybrid rendering |
| Frontend Styling | Tailwind CSS | Utility-first, konsisten dengan design token di Bagian 3 |
| Interaktivitas Frontend | Alpine.js | Untuk komponen ringan (modal, toggle, live search) tanpa overhead SPA penuh |
| Sumber Data Eksternal | Google Books API | Auto-fetch metadata buku (title, author, publisher, year, synopsis, thumbnail) |
| Database | MySQL / PostgreSQL | Sumber kebenaran (source of truth) untuk stok, transaksi, dan data lokal |
| Job Scheduling | Laravel Task Scheduling + Queue | Perhitungan denda harian, notifikasi jatuh tempo |
| Date/Time Handling | Carbon | Perhitungan hari keterlambatan |
| Autentikasi | Laravel Breeze/Fortify + Spatie Laravel-Permission | Role-based access control (RBAC) |
| Storage | Laravel Filesystem (local/public disk) | Override cover buku custom |

**Persyaratan Lingkungan:**
- PHP >= 8.2
- Composer 2.x
- Node.js >= 18 (build asset Tailwind)
- Redis (opsional, direkomendasikan untuk queue & cache statistik dashboard)

---

## 3. Spesifikasi UI/UX & Design System

### 3.1 Palet Warna

| Token | Nilai | Penggunaan |
| :--- | :--- | :--- |
| Primary | `orange-600` (#ea580c) | Tombol aksi utama, active nav state, highlight metrik dashboard |
| Primary Hover | `orange-700` (#c2410c) | State hover/active tombol |
| Surface/Canvas | `stone-50` (#fafaf9) | Background panel, mengurangi kelelahan mata saat scanning data intensif |
| Surface Elevated | `white` (#ffffff) | Card, modal, table container |
| Border | `stone-200` (#e7e5e4) | Divider, table border |
| Success (Emerald) | `emerald-600` (#059669) | Stok tersedia, pengembalian selesai |
| Warning (Amber) | `amber-500` (#f59e0b) | Permintaan pending, mendekati jatuh tempo (H-3) |
| Danger (Red) | `red-600` (#dc2626) | Overdue, denda aktif, stok habis |
| Text Primary | `stone-900` | Judul, teks utama |
| Text Secondary | `stone-500` | Metadata, label sekunder |

### 3.2 Tipografi

| Font | Penggunaan | Fallback |
| :--- | :--- | :--- |
| Plus Jakarta Sans | Heading struktural (H1–H4), judul kartu | sans-serif |
| Inter | Body text, tabel data, form label | system-ui |
| JetBrains Mono | ISBN, kode transaksi, nomor rak/katalog | monospace |

### 3.3 Prinsip Layout
- **Admin Dashboard:** Layout sidebar tetap (fixed sidebar) + top bar berisi notifikasi & profil. Konten utama menggunakan grid card untuk metrik (total buku, dipinjam, overdue, pendapatan denda).
- **Member Portal:** Layout top-nav dengan katalog bergaya grid card (cover-first), mendukung infinite scroll/pagination.
- **Responsivitas:** Mobile-first; sidebar admin collapse menjadi bottom-nav atau drawer pada layar < 768px.
- **Aksesibilitas:** Kontras warna minimal WCAG AA, status warna selalu disertai label teks/ikon (tidak mengandalkan warna saja) untuk pengguna buta warna.

---

## 4. Fitur Inti & Fungsionalitas

### 4.1 Hybrid Catalog Management (CRUD + API)

**Alur Input Buku Baru:**
1. Admin memasukkan ISBN atau judul pada form pencarian.
2. Sistem memanggil Google Books API secara asynchronous (indikator loading ditampilkan).
3. Hasil API mengisi **staging form** (belum tersimpan ke DB) berisi: judul, penulis, penerbit, tahun terbit, sinopsis, kategori, jumlah halaman, dan URL thumbnail.
4. Admin **wajib meninjau** dan dapat mengedit/menambah field sebelum submit final (mis. lokasi rak fisik, kode klasifikasi Dewey/UDC, jumlah eksemplar awal).
5. Data final disimpan ke tabel `books` lokal — sistem tidak lagi bergantung pada API setelah tahap ini.

**Penanganan Edge Case:**
- Jika API tidak menemukan hasil atau timeout (>5 detik), sistem menampilkan form manual kosong dengan notifikasi "Data tidak ditemukan, silakan input manual."
- Jika ISBN sudah terdaftar di database lokal, sistem memperingatkan admin dan menawarkan opsi "Tambah Stok" alih-alih membuat entri duplikat.
- Rate limit Google Books API ditangani dengan caching hasil pencarian (per ISBN) selama 24 jam untuk mengurangi panggilan API berulang.

**Manajemen Cover Buku:**
- Default: menyimpan URL thumbnail dari API (tanpa mengunduh file).
- Opsional: admin dapat mengunggah file gambar (jpg/png, maks 2MB) yang disimpan di `storage/app/public/covers`, menggantikan URL API pada tampilan.
- Validasi dimensi minimum untuk menjaga konsistensi tampilan grid katalog.

### 4.2 Real-Time Inventory Tracking

- Tabel `books` menyimpan `total_stock` dan `available_stock` sebagai kolom independen (bukan hasil kalkulasi query) untuk performa baca yang cepat pada halaman katalog dengan banyak pengunjung.
- Setiap perubahan stok dicatat melalui **database transaction** (locking row) untuk mencegah race condition saat dua admin memproses peminjaman bersamaan.
- `available_stock` berkurang otomatis saat status transaksi berubah menjadi `borrowed`, dan bertambah saat status menjadi `returned`.
- Indikator stok pada UI: Tersedia (emerald), Terbatas (amber, jika `available_stock` ≤ 2), Habis (red, jika `available_stock` = 0 — tombol "Borrow" otomatis nonaktif/berubah menjadi "Ajukan Antrian").

### 4.3 Autentikasi & Multi-Role Access

| Kemampuan | Admin | Member |
| :--- | :---: | :---: |
| Kelola katalog (CRUD) | ✅ | ❌ |
| Validasi peminjaman/pengembalian | ✅ | ❌ |
| Kelola data pengguna | ✅ | ❌ |
| Lihat dashboard statistik | ✅ | ❌ |
| Jelajah katalog & cari buku | ✅ | ✅ |
| Ajukan peminjaman (hold request) | ❌ | ✅ |
| Lihat riwayat & denda pribadi | ❌ | ✅ |
| Edit profil sendiri | ✅ | ✅ |

- Implementasi menggunakan **role & permission granular** (bukan hanya boolean is_admin) agar mendukung penambahan peran di masa depan (mis. Staff Sirkulasi dengan akses terbatas).
- Middleware route-level dan policy-level (Laravel Gate/Policy) untuk mencegah akses langsung via URL manipulation.

### 4.4 Circulation & Fine Engine

**Struktur Data Transaksi (`loans`):**
- `borrower_id`, `book_id`, `borrow_date`, `due_date`, `return_date` (nullable), `status` (`pending`, `borrowed`, `returned`, `overdue`), `fine_amount`, `fine_paid_at` (nullable).

**Aturan Bisnis:**
- Durasi peminjaman default: 7 hari (dikonfigurasi via settings, dapat berbeda per kategori buku).
- Kuota maksimum peminjaman aktif per member: dikonfigurasi (default 3 buku).
- Member dengan denda belum lunas di atas ambang tertentu tidak dapat mengajukan peminjaman baru.

**Perhitungan Denda Otomatis:**
- Laravel Scheduler menjalankan job harian (mis. pukul 00:05) menggunakan Carbon untuk membandingkan `due_date` dengan tanggal saat ini.
- Transaksi yang melewati `due_date` dan belum `returned` ditandai `overdue`, dan `fine_amount` dihitung: `hari_terlambat × tarif_denda_per_hari` (tarif dikonfigurasi di tabel `settings`).
- Denda terus terakumulasi setiap hari hingga buku dikembalikan; nominal final dikunci saat status berubah menjadi `returned`.
- Notifikasi (in-app, dan opsional email) dikirim pada H-3 sebelum jatuh tempo dan saat status berubah menjadi overdue.

---

## 5. Alur Aplikasi (Detail)

| Aktor | Fase | Alur Proses |
| :--- | :--- | :--- |
| Member | Discovery | Login → Dashboard Katalog Utama → Cari/Filter Buku (judul, penulis, kategori, ketersediaan) → Lihat Detail & Ketersediaan Stok |
| Member | Request | Klik "Borrow" → Validasi sistem (cek kuota maksimum & denda aktif) → Status berubah menjadi *Pending Request* → Notifikasi terkirim ke admin |
| Admin | Fulfillment | Tinjau antrean pending → Serahkan buku fisik ke peminjam → Update status ke *Borrowed* → Stok lokal berkurang → `due_date` ditetapkan |
| Admin | Return | Terima buku fisik → Cari via ID Transaksi/Nama Peminjam → Klik "Return" → Sistem menghitung denda (jika ada) → Stok bertambah kembali → Status menjadi *Returned* |
| System | Background | Scheduler Laravel berjalan harian → Tandai transaksi overdue yang lewat jatuh tempo → Terapkan pengali denda → Kirim notifikasi |

**Diagram Status Transaksi:**
`Pending → Borrowed → (Overdue jika lewat due_date) → Returned`

---

## 6. Skema Basis Data (Ringkas)

**`books`**: id, isbn, title, author, publisher, publish_year, synopsis, category, cover_url, cover_path (nullable), rack_location, total_stock, available_stock, created_at, updated_at

**`users`**: id, name, email, role, member_id (nullable, khusus student), created_at

**`loans`**: id, borrower_id (FK users), book_id (FK books), borrow_date, due_date, return_date (nullable), status, fine_amount, fine_paid_at (nullable)

**`settings`**: key, value (menyimpan konfigurasi seperti durasi pinjam default, tarif denda per hari, kuota maksimum pinjam)

**`notifications`**: id, user_id, type, message, read_at (nullable), created_at

---

## 7. Persyaratan Non-Fungsional

- **Performa:** Halaman katalog harus merender < 1 detik untuk hingga 10.000 entri buku (menggunakan pagination/indexing pada kolom pencarian).
- **Keandalan:** Fungsi sirkulasi inti (peminjaman, pengembalian, cek stok) tidak boleh bergantung pada ketersediaan Google Books API.
- **Keamanan:** Validasi input server-side penuh, proteksi CSRF bawaan Laravel, rate limiting pada endpoint pencarian API eksternal.
- **Audit Trail:** Semua perubahan status transaksi dicatat dengan timestamp dan user_id pelaku untuk keperluan audit.
- **Skalabilitas:** Struktur query dan indexing database dirancang mendukung pertumbuhan data tanpa migrasi arsitektur besar dalam 2–3 tahun ke depan.

---

## 8. Kriteria Penerimaan (Acceptance Criteria) — Contoh

- [ ] Admin dapat menambahkan buku baru via pencarian ISBN dan hasil API tersimpan hanya setelah admin menekan tombol "Simpan" pada staging form.
- [ ] Stok `available_stock` tidak pernah bernilai negatif meski terjadi permintaan peminjaman bersamaan (concurrent request).
- [ ] Denda terhitung otomatis dan akurat sesuai jumlah hari keterlambatan tanpa intervensi manual.
- [ ] Member dengan denda aktif di atas ambang batas tidak dapat mengajukan peminjaman baru, dan pesan error ditampilkan dengan jelas.
- [ ] Semua indikator status (stok, jatuh tempo, overdue) menggunakan kombinasi warna + label teks/ikon.

---

## 9. Potensi Pengembangan Lanjutan (Out of Scope v1)

- Integrasi barcode/QR scanner untuk mempercepat proses sirkulasi fisik.
- Modul reservasi antrean otomatis saat stok kosong.
- Laporan analitik lanjutan (buku terpopuler, tren peminjaman per periode).
- Integrasi pembayaran denda online.
