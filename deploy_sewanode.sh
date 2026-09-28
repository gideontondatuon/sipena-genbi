#!/usr/bin/env bash
# ==============================================================================
# SCRIPT DEPLOY OTOMATIS SIPENA GENBI KE SEWANODE NAT VPS
# OS Target : Ubuntu 22.04 / 24.04 LTS
# Target Web: Port 2282 (SewaNode NAT Public Port)
# ==============================================================================

set -e

# Warna Terminal
GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${CYAN}=====================================================${NC}"
echo -e "${CYAN}   MEMULAI DEPLOYMENT SIPENA GENBI KE SEWANODE VPS   ${NC}"
echo -e "${CYAN}=====================================================${NC}"

# 1. Pastikan dijalankan sebagai root
if [ "$EUID" -ne 0 ]; then
  echo -e "${RED}[ERROR] Harap jalankan script ini sebagai root (sudo bash deploy_sewanode.sh)${NC}"
  exit 1
fi

APP_DIR="/var/www/sipena-genbi"
WEB_PORT=2282
PUBLIC_IP="103.190.0.8"

# 2. Update Sistem & Install Prasyarat
echo -e "${YELLOW}[1/8] Memperbarui paket sistem Ubuntu...${NC}"
apt-get update -y
apt-get install -y software-properties-common curl git unzip ufw nginx mariadb-server

# 3. Setup Repository PHP Ondrej (untuk PHP 8.2)
echo -e "${YELLOW}[2/8] Memeriksa & Menginstal PHP 8.2 beserta ekstensi...${NC}"
if ! dpkg -s php8.2-fpm >/dev/null 2>&1; then
    add-apt-repository -y ppa:ondrej/php
    apt-get update -y
    apt-get install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring \
                       php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath
fi

# 4. Install Composer jika belum ada
echo -e "${YELLOW}[3/8] Memeriksa Composer...${NC}"
if ! command -v composer &> /dev/null; then
    echo -e "${CYAN}Menginstal Composer...${NC}"
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm /tmp/composer-setup.php
fi

# 5. Setup Database MariaDB
echo -e "${YELLOW}[4/8] Mengatur Database MariaDB (sipena_genbi)...${NC}"
systemctl start mariadb || systemctl start mysql
systemctl enable mariadb || systemctl enable mysql

mysql -e "CREATE DATABASE IF NOT EXISTS \`sipena_genbi\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo -e "${GREEN}Database 'sipena_genbi' siap!${NC}"

# 6. Setup Direktori Aplikasi
echo -e "${YELLOW}[5/8] Mengonfigurasi Aplikasi Laravel di ${APP_DIR}...${NC}"
if [ ! -d "$APP_DIR" ]; then
    echo -e "${CYAN}Menyalin file project ke ${APP_DIR}...${NC}"
    mkdir -p "$APP_DIR"
    cp -r . "$APP_DIR"
fi

cd "$APP_DIR"

# Buat file .env jika belum ada
if [ ! -f "$APP_DIR/.env" ]; then
    echo -e "${CYAN}Membuat file .env dari template...${NC}"
    cp "$APP_DIR/.env.example" "$APP_DIR/.env"
fi

# Sesuaikan konfigurasi .env untuk SewaNode NAT VPS
sed -i 's/^APP_ENV=.*/APP_ENV=production/' "$APP_DIR/.env"
sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' "$APP_DIR/.env"
sed -i "s|^APP_URL=.*|APP_URL=http://${PUBLIC_IP}:${WEB_PORT}|" "$APP_DIR/.env"

sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' "$APP_DIR/.env"
sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' "$APP_DIR/.env"
sed -i 's/^DB_PORT=.*/DB_PORT=3306/' "$APP_DIR/.env"
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=sipena_genbi/' "$APP_DIR/.env"
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=root/' "$APP_DIR/.env"
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=/' "$APP_DIR/.env"

# Install Dependencies PHP
echo -e "${CYAN}Menjalankan composer install...${NC}"
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction

# Generate App Key jika belum ada
php artisan key:generate --force

# Jalankan Database Migration & Seed
echo -e "${CYAN}Menjalankan migrasi database & seeding...${NC}"
php artisan migrate --force
php artisan db:seed --force

# Storage link
php artisan storage:link || true

# Build frontend jika ada Node.js & npm
if command -v npm &> /dev/null; then
    echo -e "${CYAN}Membangun aset frontend (Vite)...${NC}"
    npm install --production=false
    npm run build
fi

# Optimasi Cache Laravel
echo -e "${CYAN}Membersihkan & Mengoptimalkan Cache Laravel...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Konfigurasi Nginx Web Server pada Port 2282
echo -e "${YELLOW}[6/8] Mengonfigurasi Nginx untuk Port ${WEB_PORT}...${NC}"

# Temukan PHP-FPM socket yang aktif
PHP_FPM_SOCK=$(ls /var/run/php/php*-fpm.sock 2>/dev/null | head -n 1)
if [ -z "$PHP_FPM_SOCK" ]; then
    PHP_FPM_SOCK="/var/run/php/php8.2-fpm.sock"
fi

cat <<EOF > /etc/nginx/sites-available/sipena-genbi
server {
    listen ${WEB_PORT};
    listen [::]:${WEB_PORT};
    server_name _;
    root ${APP_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    client_max_body_size 50M;

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
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Aktifkan konfigurasi Nginx
ln -sf /etc/nginx/sites-available/sipena-genbi /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx

# 8. Set Izin Folder (Permissions)
echo -e "${YELLOW}[7/8] Mengatur kepemilikan dan izin folder...${NC}"
chown -R www-data:www-data "${APP_DIR}"
chmod -R 775 "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"

# 9. Buka Port di Firewall (jika UFW aktif)
echo -e "${YELLOW}[8/8] Memeriksa firewall...${NC}"
if command -v ufw &> /dev/null; then
    ufw allow ${WEB_PORT}/tcp || true
fi

echo -e "${GREEN}=====================================================${NC}"
echo -e "${GREEN}   DEPLOYMENT SIPENA GENBI BERHASIL DILAKUKAN!       ${NC}"
echo -e "${GREEN}=====================================================${NC}"
echo -e "Aplikasi sekarang dapat diakses langsung melalui browser di:"
echo -e "${CYAN}👉 http://${PUBLIC_IP}:${WEB_PORT}${NC}"
echo -e ""
echo -e "Akun login bawaan hasil seeding:"
echo -e "- Admin   : Username = ${YELLOW}admin${NC} | Password = ${YELLOW}password${NC}"
echo -e "- Anggota : Username = ${YELLOW}gideon${NC} | Password = ${YELLOW}password${NC}"
echo -e "${GREEN}=====================================================${NC}"
