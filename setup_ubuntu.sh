#!/usr/bin/env bash
# ==============================================================================
# Script Instalasi & Setup Otomatis Server Ubuntu
# Ekosistem EAI: SIMAK UNIVERSE & TOTAL BILLING SYSTEM
# Sistem Operasi Target: Ubuntu 20.04, 22.04, 24.04, & 26.04 (Resolute) Minimal
# ==============================================================================

set -e

# Warna Terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

# Pastikan script dijalankan sebagai root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}[ERROR] Script ini harus dijalankan sebagai root (gunakan sudo bash setup_ubuntu.sh).${NC}"
    exit 1
fi

clear
echo -e "${CYAN}${BOLD}"
echo "====================================================================="
echo "   AUTOMASI DEPLOYMENT EAI (SIMAK & TOTAL BILLING SYSTEM) - UBUNTU   "
echo "====================================================================="
echo -e "${NC}"
echo -e "Script ini kompatibel dengan Ubuntu 20.04, 22.04, 24.04, dan 26 Minimal."
echo -e "Tahapan eksekusi:"
echo -e "  1. Pembersihan repo broken & instalasi dependensi OS (Nginx, PHP, MariaDB, Certbot)"
echo -e "  2. Pembuatan basis data 'db_simak' dan 'db_billing'"
echo -e "  3. Konfigurasi Domain & VirtualHost Nginx untuk SIMAK dan Billing"
echo -e "  4. Setup SSL / HTTPS otomatis via Let's Encrypt Certbot"
echo -e "  5. Instalasi website, migrasi database & seeding data awal"
echo -e "  6. Pemasangan custom CLI command 'eai' di sistem (/usr/local/bin/eai)\n"

# ------------------------------------------------------------------------------
# 1. FORMULIR INPUT KONFIGURASI
# ------------------------------------------------------------------------------
echo -e "${BOLD}${YELLOW}--- TAHAP 1: KONFIGURASI DOMAIN & IDENTITAS SERVER ---${NC}"

read -p "Masukkan Domain untuk SIMAK Portal [contoh: simak.kampus.ac.id]: " DOMAIN_SIMAK
while [ -z "$DOMAIN_SIMAK" ]; do
    echo -e "${RED}Domain SIMAK tidak boleh kosong!${NC}"
    read -p "Masukkan Domain untuk SIMAK Portal: " DOMAIN_SIMAK
done

read -p "Masukkan Domain untuk Total Billing System [contoh: billing.kampus.ac.id]: " DOMAIN_BILLING
while [ -z "$DOMAIN_BILLING" ]; do
    echo -e "${RED}Domain Total Billing tidak boleh kosong!${NC}"
    read -p "Masukkan Domain untuk Total Billing System: " DOMAIN_BILLING
done

read -p "Masukkan Email Administrator untuk Sertifikat SSL Let's Encrypt: " ADMIN_EMAIL
while [ -z "$ADMIN_EMAIL" ]; do
    echo -e "${RED}Email wajib diisi untuk pendaftaran SSL!${NC}"
    read -p "Masukkan Email Administrator: " ADMIN_EMAIL
done

# Password Database
DEFAULT_DB_PASS=$(openssl rand -base64 12 | tr -dc 'a-zA-Z0-9' | head -c 16)
read -p "Masukkan Password Database untuk user 'eai_user' [Tekan ENTER untuk random: $DEFAULT_DB_PASS]: " INPUT_DB_PASS
DB_PASS="${INPUT_DB_PASS:-$DEFAULT_DB_PASS}"

echo ""
echo -e "${GREEN}Konfigurasi yang akan diproses:${NC}"
echo -e "  - Domain SIMAK   : ${BOLD}http(s)://${DOMAIN_SIMAK}${NC}"
echo -e "  - Domain Billing : ${BOLD}http(s)://${DOMAIN_BILLING}${NC}"
echo -e "  - Email SSL      : ${BOLD}${ADMIN_EMAIL}${NC}"
echo -e "  - User Database  : ${BOLD}eai_user${NC} (Pass: ${BOLD}${DB_PASS}${NC})"
echo ""
read -p "Lanjutkan proses instalasi sekarang? (y/n): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Instalasi dibatalkan oleh pengguna.${NC}"
    exit 0
fi

# ------------------------------------------------------------------------------
# 2. DETEKSI OS, PEMBERSIHAN PPA & INSTALASI DEPENDENSI
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 2: DETEKSI OS & MENGINSTAL DEPENDENSI ---${NC}"
export DEBIAN_FRONTEND=noninteractive

# Deteksi codename Ubuntu
UBUNTU_CODENAME=$(lsb_release -sc 2>/dev/null || grep -oP '(?<=VERSION_CODENAME=).+' /etc/os-release || echo "unknown")
echo -e "${CYAN}Terdeteksi versi Ubuntu: ${BOLD}${UBUNTU_CODENAME}${NC}"

# Bersihkan PPA ondrej broken jika sebelumnya sempat gagal di Ubuntu resolute / versi baru
echo -e "${CYAN}Membersihkan list repository lama yang tidak valid...${NC}"
rm -f /etc/apt/sources.list.d/*ondrej* 2>/dev/null || true
apt-get clean

apt-get update -y
apt-get install -y software-properties-common curl git unzip ufw ca-certificates lsb-release

# Kebijakan PHP Repository:
# Pada Ubuntu 26 (resolute), repositori resmi Ubuntu telah menyediakan PHP terbaru langsung.
# Hanya pasang ppa:ondrej/php pada Ubuntu jammy (22.04), noble (24.04), atau focal (20.04).
if [ "$UBUNTU_CODENAME" = "jammy" ] || [ "$UBUNTU_CODENAME" = "noble" ] || [ "$UBUNTU_CODENAME" = "focal" ]; then
    echo -e "${CYAN}Menambahkan PPA PHP untuk Ubuntu ${UBUNTU_CODENAME}...${NC}"
    add-apt-repository -y ppa:ondrej/php || true
    apt-get update -y
else
    echo -e "${GREEN}Ubuntu ${UBUNTU_CODENAME} menggunakan repositori PHP resmi bawaan sistem.${NC}"
fi

# Instalasi Nginx, MariaDB, Certbot & Paket PHP Generik
echo -e "${CYAN}Menginstal Nginx, MariaDB, Certbot & PHP Modules...${NC}"
apt-get install -y nginx mariadb-server certbot python3-certbot-nginx \
    php-fpm php-cli php-common php-mysql php-zip php-gd \
    php-mbstring php-curl php-xml php-bcmath php-intl || \
apt-get install -y nginx mariadb-server certbot python3-certbot-nginx \
    php8.3-fpm php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd \
    php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-intl || \
apt-get install -y nginx mariadb-server certbot python3-certbot-nginx \
    php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd \
    php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-intl

# Instalasi Composer Global
if ! command -v composer &> /dev/null; then
    echo -e "${CYAN}Menginstal Composer versi terbaru...${NC}"
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm -f /tmp/composer-setup.php
fi

# Deteksi Service PHP-FPM Aktif
PHP_FPM_SOCK=$(ls -1 /var/run/php/php*-fpm.sock 2>/dev/null | head -n 1 || true)
PHP_SERVICE=$(systemctl list-unit-files | grep -oE "php[0-9.]+-fpm\.service" | head -n 1 | sed 's/\.service//' || true)
if [ -z "$PHP_SERVICE" ]; then
    PHP_SERVICE="php-fpm"
fi

echo -e "${GREEN}Deteksi PHP-FPM Service: ${PHP_SERVICE}${NC}"
echo -e "${GREEN}Deteksi FastCGI Socket : ${PHP_FPM_SOCK}${NC}"

# Aktifkan dan jalankan services
systemctl enable nginx
systemctl start nginx
systemctl enable mariadb
systemctl start mariadb
if systemctl list-unit-files | grep -q "$PHP_SERVICE"; then
    systemctl enable "$PHP_SERVICE"
    systemctl restart "$PHP_SERVICE"
fi

echo -e "${GREEN}✓ Dependensi dasar berhasil diinstal.${NC}"

# ------------------------------------------------------------------------------
# 3. KONFIGURASI BASIS DATA MARIADB / MYSQL
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 3: MENYIAPKAN BASIS DATA MYSQL ---${NC}"

mysql -e "CREATE DATABASE IF NOT EXISTS db_simak CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE DATABASE IF NOT EXISTS db_billing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'eai_user'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON db_simak.* TO 'eai_user'@'localhost';"
mysql -e "GRANT ALL PRIVILEGES ON db_billing.* TO 'eai_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

echo -e "${GREEN}✓ Basis data 'db_simak' dan 'db_billing' telah siap.${NC}"

# ------------------------------------------------------------------------------
# 4. DEPLOY KODE SUMBER KE /var/www/
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 4: MENYIAPKAN DIREKTORI & SUMBER APLIKASI ---${NC}"

mkdir -p /var/www/simak
mkdir -p /var/www/billing

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Salin dari direktori script jika folder simak dan billing ada di samping script
if [ -d "$SCRIPT_DIR/simak" ] && [ -d "$SCRIPT_DIR/billing" ]; then
    echo -e "${CYAN}Menyalin file aplikasi dari direktori lokal installer...${NC}"
    cp -r "$SCRIPT_DIR/simak/." /var/www/simak/
    cp -r "$SCRIPT_DIR/billing/." /var/www/billing/
fi

# Generate File .env untuk SIMAK
echo -e "${CYAN}Mengonfigurasi file .env untuk SIMAK Portal...${NC}"
cat > /var/www/simak/.env <<EOL
APP_NAME="SIMAK UNIVERSE"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://${DOMAIN_SIMAK}

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_simak
DB_USERNAME=eai_user
DB_PASSWORD=${DB_PASS}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=simak_cache_

# Konfigurasi EAI Integration Endpoint ke Total Billing
BILLING_API_URL=https://${DOMAIN_BILLING}/api
BILLING_WEBHOOK_SECRET=eai_secret_webhook_token_2026
EOL

# Generate File .env untuk Total Billing
echo -e "${CYAN}Mengonfigurasi file .env untuk Total Billing System...${NC}"
cat > /var/www/billing/.env <<EOL
APP_NAME="Total Billing System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://${DOMAIN_BILLING}

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_billing
DB_USERNAME=eai_user
DB_PASSWORD=${DB_PASS}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=billing_cache_

# Konfigurasi EAI Integration Webhook ke SIMAK Portal
SIMAK_WEBHOOK_URL=https://${DOMAIN_SIMAK}/api/webhook/billing-payment
SIMAK_WEBHOOK_SECRET=eai_secret_webhook_token_2026
EOL

# ------------------------------------------------------------------------------
# 5. MENJALANKAN COMPOSER, APP KEY & MIGRASI DATABASE
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 5: RUNNING COMPOSER, APP KEY & DATABASE MIGRATE ---${NC}"

# Setup SIMAK
if [ -f "/var/www/simak/composer.json" ]; then
    echo -e "${CYAN}Menginstal paket Composer pada SIMAK...${NC}"
    cd /var/www/simak
    composer install --no-dev --optimize-autoloader --no-interaction
    php artisan key:generate --force
    php artisan migrate:fresh --seed --force
fi

# Setup Billing
if [ -f "/var/www/billing/composer.json" ]; then
    echo -e "${CYAN}Menginstal paket Composer pada Total Billing...${NC}"
    cd /var/www/billing
    composer install --no-dev --optimize-autoloader --no-interaction
    php artisan key:generate --force
    php artisan migrate:fresh --seed --force
fi

# Permissions
chown -R www-data:www-data /var/www/simak /var/www/billing
find /var/www/simak -type f -exec chmod 664 {} \;
find /var/www/simak -type d -exec chmod 775 {} \;
find /var/www/billing -type f -exec chmod 664 {} \;
find /var/www/billing -type d -exec chmod 775 {} \;
chmod -R 775 /var/www/simak/storage /var/www/simak/bootstrap/cache
chmod -R 775 /var/www/billing/storage /var/www/billing/bootstrap/cache

echo -e "${GREEN}✓ Sumber kode, dependensi, dan migrasi basis data selesai.${NC}"

# ------------------------------------------------------------------------------
# 6. KONFIGURASI NGINX VIRTUAL HOST
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 6: MENYIAPKAN KONFIGURASI NGINX ---${NC}"

# Fallback socket jika belum terdeteksi
if [ -z "$PHP_FPM_SOCK" ]; then
    PHP_FPM_SOCK="/var/run/php/php-fpm.sock"
fi

# VirtualHost SIMAK
cat > /etc/nginx/sites-available/simak.conf <<EOL
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN_SIMAK};
    root /var/www/simak/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;
    charset utf-8;

    client_max_body_size 64M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:${PHP_FPM_SOCK};
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOL

# VirtualHost Total Billing System
cat > /etc/nginx/sites-available/billing.conf <<EOL
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN_BILLING};
    root /var/www/billing/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;
    charset utf-8;

    client_max_body_size 64M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:${PHP_FPM_SOCK};
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOL

# Aktifkan konfigurasi di Nginx
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/simak.conf /etc/nginx/sites-enabled/
ln -sf /etc/nginx/sites-available/billing.conf /etc/nginx/sites-enabled/

# Uji konfigurasi Nginx
nginx -t
systemctl reload nginx

echo -e "${GREEN}✓ Konfigurasi VirtualHost Nginx berhasil dipasang.${NC}"

# ------------------------------------------------------------------------------
# 7. KONFIGURASI SSL / HTTPS (LET'S ENCRYPT CERTBOT)
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 7: MENGONFIGURASI SERTIFIKAT SSL (HTTPS) ---${NC}"
echo -e "Mencoba menginstal sertifikat SSL otomatis melalui Let's Encrypt Certbot..."

if certbot --nginx -d "${DOMAIN_SIMAK}" -d "${DOMAIN_BILLING}" --non-interactive --agree-tos -m "${ADMIN_EMAIL}" --redirect; then
    echo -e "${GREEN}✓ Sertifikat SSL HTTPS berhasil diaktifkan untuk kedua domain!${NC}"
else
    echo -e "${YELLOW}[CATATAN PENTING SSL]${NC}"
    echo -e "Certbot belum dapat menerbitkan sertifikat otomatis saat ini."
    echo -e "Pastikan DNS A Record untuk kedua domain telah diarahkan ke IP server ini:"
    echo -e "  - ${DOMAIN_SIMAK}   -> [IP Server Anda]"
    echo -e "  - ${DOMAIN_BILLING} -> [IP Server Anda]"
    echo -e "Setelah DNS aktif (propagsi selesai), jalankan perintah berikut kapan saja:"
    echo -e "  ${BOLD}certbot --nginx -d ${DOMAIN_SIMAK} -d ${DOMAIN_BILLING}${NC}"
fi

# ------------------------------------------------------------------------------
# 8. MEMASANG CUSTOM COMMAND 'eai'
# ------------------------------------------------------------------------------
echo -e "\n${BOLD}${YELLOW}--- TAHAP 8: MEMASANG CUSTOM COMMAND 'eai' ---${NC}"

if [ -f "$SCRIPT_DIR/bin/eai" ]; then
    cp "$SCRIPT_DIR/bin/eai" /usr/local/bin/eai
elif [ -f "/var/www/simak/../bin/eai" ]; then
    cp "/var/www/simak/../bin/eai" /usr/local/bin/eai
fi

if [ -f "/usr/local/bin/eai" ]; then
    chmod +x /usr/local/bin/eai
    echo -e "${GREEN}✓ Custom command 'eai' berhasil terpasang di /usr/local/bin/eai.${NC}"
else
    echo -e "${YELLOW}Perhatian: Script bin/eai belum dapat disalin otomatis.${NC}"
fi

# Konfigurasi UFW Firewall
if command -v ufw &> /dev/null; then
    ufw allow 'Nginx Full' >/dev/null 2>&1 || true
    ufw allow OpenSSH >/dev/null 2>&1 || true
fi

# ------------------------------------------------------------------------------
# 9. SELESAI & RINGKASAN
# ------------------------------------------------------------------------------
clear
echo -e "${GREEN}${BOLD}"
echo "====================================================================="
echo "        INSTALASI EKOSISTEM EAI BERHASIL DISELESAIKAN!               "
echo "====================================================================="
echo -e "${NC}"
echo -e "Kedua aplikasi telah aktif dan siap diakses melalui peramban:"
echo -e "  1. ${BOLD}SIMAK UNIVERSE${NC}        : ${CYAN}https://${DOMAIN_SIMAK}${NC}"
echo -e "  2. ${BOLD}Total Billing System${NC}  : ${CYAN}https://${DOMAIN_BILLING}${NC}"
echo ""
echo -e "${BOLD}KREDENSIAL LOGIN DEFAULT:${NC}"
echo -e "  - SIMAK Mahasiswa (Dzulfikri) : NIM 210101001 / Pass: password123"
echo -e "  - SIMAK Mahasiswa (Budi)      : NIM 210101002 / Pass: password123"
echo -e "  - SIMAK Mahasiswa (Rina)      : NIM 200101005 / Pass: password123"
echo -e "  - SIMAK Administrator         : User admin / Pass: password123"
echo -e "  - Total Billing System        : admin@billing.kampus.id / Pass: password123"
echo ""
echo -e "${BOLD}MANAJEMEN SERVER DENGAN CUSTOM COMMAND 'eai':${NC}"
echo -e "  Anda dapat mengelola server kapan saja cukup dengan mengetik ${BOLD}${GREEN}eai${NC}:"
echo -e "  - ${CYAN}eai status${NC}         : Cek status Nginx, PHP-FPM, MySQL & disk"
echo -e "  - ${CYAN}eai restart${NC}        : Merestart seluruh service server"
echo -e "  - ${CYAN}eai reload${NC}         : Reload Nginx dan PHP tanpa downtime"
echo -e "  - ${CYAN}eai update${NC}         : Git pull, composer install, migrate & cache"
echo -e "  - ${CYAN}eai migrate${NC}        : Menjalankan migrasi database"
echo -e "  - ${CYAN}eai logs simak${NC}     : Memantau live error log SIMAK"
echo -e "  - ${CYAN}eai logs billing${NC}   : Memantau live error log Total Billing"
echo -e "  - ${CYAN}eai backup${NC}         : Membuat backup dump SQL basis data"
echo -e "  - ${CYAN}eai ssl:renew${NC}      : Perpanjang sertifikat SSL Let's Encrypt"
echo -e "  - ${CYAN}eai help${NC}           : Panduan lengkap perintah"
echo ""
echo -e "${GREEN}Sistem telah siap digunakan untuk keperluan produksi.${NC}"
echo "====================================================================="
