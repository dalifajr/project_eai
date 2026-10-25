# Panduan Lengkap Instalasi & Setup Server Ubuntu: SIMAK & Total Billing System

Panduan ini disusun untuk memandu Helm dalam melakukan instalasi, konfigurasi domain, pengaktifan SSL (HTTPS), migrasi basis data, serta manajemen kedua aplikasi di server **Ubuntu (20.04, 22.04, 24.04, hingga 26.04 Resolute Minimal)** menggunakan automated installer dan custom CLI command **`eai`**.

---

## 1. Prasyarat Server & Domain

Sebelum memulai proses instalasi di VPS/Server:
1. **Sistem Operasi**: Ubuntu 20.04 LTS, 22.04 LTS, 24.04 LTS, atau **Ubuntu 26 Minimal (Resolute)** (Akses `root` atau `sudo`).
2. **Koneksi Port**: Pastikan port `80` (HTTP), `443` (HTTPS), dan `22` (SSH) terbuka pada firewall penyedia VPS (Security Group / UFW).
3. **DNS A Record (Domain / Subdomain)**:
   Arahkan DNS Record pada penyedia domain Anda ke IP Publik server:
   - `simak.domainanda.com` &rarr; `[IP Publik Server]`
   - `billing.domainanda.com` &rarr; `[IP Publik Server]`

> [!NOTE]
> **Khusus Ubuntu 26 (Resolute) Minimal**:
> PPA lama `ppa:ondrej/php` di Launchpad tidak mendukung Ubuntu 26 dan akan menghasilkan error 404. Script `setup_ubuntu.sh` yang telah diperbarui secara cerdas mendeteksi Ubuntu 26 dan otomatis menggunakan paket PHP resmi bawaan sistem tanpa memerlukan PPA pihak ketiga. Jika sebelumnya Anda sempat mengalami error 404, script akan otomatis membersihkan file repo yang rusak tersebut.

---

## 2. Cara Instalasi Otomatis (Sangat Direkomendasikan)

Telah disediakan script automasi [setup_ubuntu.sh](file:///g:/My%20Drive/KULIAH/SEMESTER%207/EAI/PROJECT%20EAI/setup_ubuntu.sh) yang secara otomatis menginstal seluruh dependensi, membuat basis data, mengonfigurasi Nginx, memasang SSL Let's Encrypt, dan mendaftarkan custom command `eai`.

### Langkah 1: Upload Folder Proyek ke Server
Upload seluruh folder proyek ini ke server Ubuntu Anda (misal ke direktori `/root/eai_project` atau home direktori).

### Langkah 2: Berikan Izin Eksekusi & Jalankan Script
Jalankan perintah berikut di terminal server:
```bash
cd /path/ke/folder/eai_project
chmod +x setup_ubuntu.sh bin/eai
sudo bash setup_ubuntu.sh
```

### Langkah 3: Ikuti Panduan Interaktif
Script akan menanyakan 4 hal sederhana:
1. **Domain SIMAK**: Masukkan subdomain untuk SIMAK (contoh: `simak.kampus.ac.id`).
2. **Domain Billing**: Masukkan subdomain untuk Billing (contoh: `billing.kampus.ac.id`).
3. **Email Administrator**: Untuk notifikasi pembaruan SSL Let's Encrypt.
4. **Password Database**: Anda dapat mengetik password sendiri atau langsung tekan `ENTER` untuk menggunakan password acak yang aman.

Script akan menyelesaikan seluruh proses instalasi, menjalankan migrasi database, menerbitkan SSL Certbot, dan mengaktifkan custom CLI command `eai`.

---

## 3. Custom Command `eai` (Server Management Tool)

Setelah instalasi selesai, custom command `eai` telah terpasang secara global di `/usr/local/bin/eai`. Anda dapat mengetikkan perintah `eai` kapan saja dari direktori mana pun di terminal server.

### Daftar Perintah yang Tersedia:

| Perintah | Deskripsi Fungsi |
| :--- | :--- |
| `eai status` | Memeriksa status kesehatan layanan Nginx, PHP-FPM, MySQL, ketersediaan direktori, dan ruang disk. |
| `eai restart` | Merestart seluruh layanan (Nginx, PHP-FPM, Database) secara berurutan. |
| `eai restart nginx` | Hanya merestart web server Nginx. |
| `eai restart php` | Hanya merestart service PHP-FPM. |
| `eai restart mysql` | Hanya merestart basis data MySQL / MariaDB. |
| `eai reload` | Melakukan reload konfigurasi Nginx dan PHP-FPM tanpa downtime (*zero downtime*). |
| `eai update` | Menjalankan git pull, instalasi composer produksi, migrasi database, dan pembuatan cache produksi. |
| `eai migrate` | Menjalankan migrasi database `simak` dan `billing` (`php artisan migrate --force`). |
| `eai seed` | Menjalankan database seeder untuk mengisi data awal mahasiswa dan invoice tagihan. |
| `eai cache:clear` | Membersihkan seluruh cache aplikasi, konfigurasi, route, dan blade view. |
| `eai cache:optimize` | Melakukan caching konfigurasi, route, dan view untuk performa maksimal di produksi. |
| `eai logs simak` | Memantau log error/aktivitas SIMAK secara live (*tail -f*). Tekan `Ctrl+C` untuk keluar. |
| `eai logs billing` | Memantau log error/aktivitas Total Billing System secara live. |
| `eai logs nginx` | Memantau log error Nginx secara live. |
| `eai ssl:renew` | Menguji dan memperbarui sertifikat SSL Let's Encrypt Certbot. |
| `eai backup` | Membuat dump SQL terkompresi dari basis data `db_simak` dan `db_billing` ke `/var/backups/eai/`. |
| `eai help` | Menampilkan panduan lengkap penggunaan perintah `eai`. |

---

## 4. Konfigurasi Lingkungan Produksi (.env)

Automated script telah mengatur komunikasi antar-sistem secara tepat pada masing-masing file `.env`:

### SIMAK Portal (`/var/www/simak/.env`):
- `APP_URL=https://simak.domainanda.com`
- `DB_DATABASE=db_simak`
- `BILLING_API_URL=https://billing.domainanda.com/api` *(Endpoint REST API untuk verifikasi UKT & cek denda)*
- `BILLING_WEBHOOK_SECRET=eai_secret_webhook_token_2026`

### Total Billing System (`/var/www/billing/.env`):
- `APP_URL=https://billing.domainanda.com`
- `DB_DATABASE=db_billing`
- `SIMAK_WEBHOOK_URL=https://simak.domainanda.com/api/webhook/billing-payment` *(Endpoint Webhook asinkron pelunasan wisuda)*
- `SIMAK_WEBHOOK_SECRET=eai_secret_webhook_token_2026`

---

## 5. Troubleshooting & Tips Pemeliharaan

1. **Memperbarui Sertifikat SSL Jika Tertunda**:
   Jika pada saat awal menjalankan script domain Anda belum selesai masa propagasi DNS, jalankan perintah Certbot manual berikut setelah DNS aktif:
   ```bash
   certbot --nginx -d simak.domainanda.com -d billing.domainanda.com
   ```
   Atau gunakan perintah:
   ```bash
   eai ssl:renew
   ```

2. **Memperbaiki Izin Folder (*Permissions*)**:
   Jika muncul pesan error *Permission Denied* pada folder log atau storage:
   ```bash
   sudo chown -R www-data:www-data /var/www/simak /var/www/billing
   sudo chmod -R 775 /var/www/simak/storage /var/www/simak/bootstrap/cache
   sudo chmod -R 775 /var/www/billing/storage /var/www/billing/bootstrap/cache
   ```

3. **Melihat Log Error Secara Cepat**:
   ```bash
   eai logs simak
   eai logs billing
   ```
