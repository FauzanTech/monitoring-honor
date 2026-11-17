# Monitoring Honor Mitra — BPS Kabupaten Pangkajene dan Kepulauan (7309)

Aplikasi Laravel + Livewire untuk memantau total honor mitra per bulan terhadap limit SBKS,
membantu pegawai memilih petugas yang masih longgar limitnya untuk kegiatan berikutnya.

## Fitur

| Menu | Isi |
|---|---|
| **Monitoring** | Matriks 12 bulan per mitra: total honor, % dari limit, warna status. Kartu ringkasan (klik = filter), filter bulan/posisi, cari, urutkan "paling longgar". |
| **Kegiatan** | Tambah/ubah/hapus kegiatan: nama, tim, jenis (Survei/Sensus), tanggal mulai–selesai, honor PPL & PML per bulan. Atribut POK (aktivitas, KRO, RO, komponen, akun) belum dipakai. |
| **Kelola kegiatan** | • Unggah Excel petugas → **pratinjau kalkulasi** (honor kegiatan lain + honor kegiatan ini vs limit tiap bulan) sebelum disimpan.<br>• Buang petugas tertentu / semua yang merah dari pratinjau.<br>• **Lepas petugas dari kegiatan sebelumnya** langsung dari pratinjau atau daftar petugas, status dihitung ulang seketika.<br>• Ubah jabatan & honor per bulan, keluarkan petugas.<br>• **★ Rekomendasi petugas**: mitra diurutkan dari yang paling longgar bila ditambahkan dengan honor default. |
| **Mitra** | Master mitra (ID SOBAT), impor dari Excel, halaman detail: kartu 12 bulan, riwayat penugasan + tombol Lepas, simulasi "Tugaskan ke kegiatan". |
| **Pengaturan Limit** | Limit per jenis kegiatan × jabatan, ambang warna oranye (default 75 %). |

### Rumus kalkulasi

```
total honor petugas bulan X = honor berjalan (dari Excel) + honor kegiatan ini (honor PPL/PML di form kegiatan)
% limit                     = total ÷ limit
```

* **Honor berjalan** = honor yang sedang diterima petugas dari kegiatan lain, diunggah pegawai lewat Excel.
  Rekap Excel adalah acuan utama; unggahan terbaru menimpa rekap lama untuk bulan yang sama.
* **Honor kegiatan ini** = honor PPL/PML per bulan yang diisi di form kegiatan (bisa diubah per petugas di panel *Atur*).
* Kegiatan yang ditambahkan lewat aplikasi **setelah** rekap diunggah ikut dijumlahkan (belum tercakup rekap);
  kegiatan yang lebih lama dari rekap dianggap sudah termasuk, sehingga tidak dihitung dobel.

### Aturan status

* **Hijau** ≤ 75 % · **Oranye** 75–100 % (mendekati) · **Merah** > 100 % (melebihi) · abu-abu = belum ada honor.
* Status petugas di kegiatan = bulan **terburuk** di periode kegiatan.
* Limit awal (sheet LIMIT, nonorganik): Survei PPL/PML Rp3.910.000; Sensus PPL Rp4.418.000, PML Rp4.891.000.

### Format Excel upload

```
sobat_id | nama (opsional) | jabatan | Jan 2026 | Feb 2026 | … | Des 2026
```

* Kolom bulan berisi **honor berjalan**. Hanya bulan dalam periode kegiatan yang diambil; sel kosong = 0.
  Judul bulan boleh `Jan`, `Januari 2026`, `2026-01`, `01/2026`, atau tanggal Excel.
* `jabatan`: `ppl` / `pml` — jabatan petugas di kegiatan ini (menentukan honor PPL atau PML yang ditambahkan).
* Alternatif: satu kolom `jumlah` → honor berjalan yang sama untuk setiap bulan periode.
* ID SOBAT yang belum terdaftar dibuat otomatis sebagai mitra baru (pakai kolom `nama`).
* `contoh-upload-penugasan.xlsx` berisi honor berjalan Jan–Des 2026 dari matriks untuk 15 mitra.

## Instalasi

Butuh PHP ≥ 8.2 (ekstensi zip, gd, xml, mbstring, pdo_sqlite/pdo_mysql) dan Composer.

```bash
composer create-project laravel/laravel monitoring-honor
cd monitoring-honor
composer require livewire/livewire phpoffice/phpspreadsheet

# salin folder app/, database/, resources/, routes/ dari paket ini ke proyek (timpa routes/web.php)

# .env → atur DB (SQLite bawaan Laravel juga bisa), dan:
#   APP_LOCALE=id
#   APP_TIMEZONE=Asia/Makassar

php artisan migrate
php artisan db:seed --class=MonitoringHonorSeeder

# (opsional, disarankan) impor master mitra + rekap honor berjalan 2026 dari matriks Excel
php -d memory_limit=1G artisan honor:import-matriks /path/Matrix_Honor_Mitra_BPS_7309_2026.xlsx --tahun=2026 --fresh

php artisan serve
```

Impor matriks membaca sheet **Petugas** (master mitra) dan sheet **JAN–DES** (jumlah *Honor yang Dibayarkan* per ID per bulan
→ rekap honor berjalan). `--fresh` menghapus kegiatan hasil impor versi lama aplikasi ini; kegiatan buatan manual tidak tersentuh.

Tailwind dimuat lewat CDN sehingga tidak perlu `npm`. Belum ada login; bila perlu, tambahkan Laravel Breeze/Fortify
dan bungkus rute di `routes/web.php` dengan middleware `auth`.

## Struktur

```
app/Services/HonorService.php          kalkulasi beban, limit, status, rekomendasi
app/Services/PenugasanExcelReader.php  membaca & menyesuaikan Excel ke periode kegiatan
app/Services/PenugasanService.php      simpan/lepas penugasan, simpan honor berjalan, sinkron periode
app/Console/Commands/ImportMatriksHonor.php
app/Livewire/{Monitoring, PengaturanLimit, Kegiatan/Index, Kegiatan/Show, Mitra/Index, Mitra/Show}.php
database/migrations/…_create_monitoring_honor_tables.php
  mitras · kegiatans · penugasans · honor_bulanans (honor kegiatan, bulan 'YYYY-MM')
  honor_berjalans (rekap Excel) · limit_honors · pengaturans
```
