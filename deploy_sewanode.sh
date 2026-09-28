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

# 3. Setup Repository PHP Ondrej (untuk PHP 8.4) tanpa ketergantungan Python add-apt-repository
echo -e "${YELLOW}[2/8] Memeriksa & Menginstal PHP 8.4 beserta ekstensi...${NC}"
apt-get install -y ca-certificates gnupg curl

mkdir -p /etc/apt/keyrings
cat << 'EOF' > /tmp/ondrej-php.asc
-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: Hockeypuck 2.2

xo0ESX35nAEEALKDCUDVXvmW9n+T/+3G1DnTpoWh9/1xNaz/RrUH6fQKhHr568F8
hfnZP/2CGYVYkW9hxP9LVW9IDvzcmnhgIwK+ddeaPZqh3T/FM4OTA7Q78HSvR81m
Jpf2iMLm/Zvh89ZsmP2sIgZuARiaHo8lxoTSLtmKXsM3FsJVlusyewHfABEBAAHN
H0xhdW5jaHBhZCBQUEEgZm9yIE9uZMWZZWogU3Vyw73CtgQTAQIAIAUCSX35nAIb
AwYLCQgHAwIEFQIIAwQWAgMBAh4BAheAAAoJEE9OoKrlJnpsQjYD/jW1NlIFAlT6
EvF2xfVbkhERii9MapjaUsSso4XLCEmZdEGX54GQ01svXnrivwnd/kmhKvyxCqiN
LDY/dOaK8MK//bDI6mqdKmG8XbP2vsdsxhifNC+GH/OwaDPvn1TyYB653kwyruCG
FjEnCreZTcRUu2oBQyolORDl+BmF4DjLwsBzBBABCgAdFiEECvaBvTqO/UqmWMI/
thEcm0xImQEFAmXTV0AACgkQthEcm0xImQGTTggAhuMHGeBZlRUAsZE7jJM7Mf06
/WIhcgUfBfSFnJFlFH+xdEe/GFYyVk9kingDsPh90Ecnt4n8DJHTlsuUV1+SPBIO
JfbQTUjx1n/+Ck+TVKzRByvrpRXtiZQ214m3zbhZpme2eBBMItZByjG7g925NUIq
rL+R5ZoEcZvVlYscfsA0Sr8yJTsGJPefuLYI6eJkNDa1QkzBkSSW4XaCfNIxNBRs
zN/qGe3xy0bibOaC4T2TcbZPSAVP855ahNbLAdqkyfAutiEWcKZmQpR9qNh4482k
0pXVlQJ8UB860gVFHjwjFm/MsCeX8yfeAi38ZyInWL2OSG2pDx5ZzNESwnCPIg==
=3DzI
-----END PGP PUBLIC KEY BLOCK-----
EOF
gpg --dearmor --yes -o /etc/apt/keyrings/ondrej-php.gpg /tmp/ondrej-php.asc
rm -f /tmp/ondrej-php.asc

CODENAME=$(lsb_release -sc 2>/dev/null || grep VERSION_CODENAME /etc/os-release | cut -d= -f2 || echo "jammy")
echo "deb [signed-by=/etc/apt/keyrings/ondrej-php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu ${CODENAME} main" > /etc/apt/sources.list.d/ondrej-php.list

apt-get update -y
apt-get install -y php8.4-fpm php8.4-cli php8.4-mysql php8.4-xml php8.4-mbstring \
                   php8.4-curl php8.4-zip php8.4-gd php8.4-intl php8.4-bcmath

update-alternatives --set php /usr/bin/php8.4 2>/dev/null || true

# 4. Install Composer jika belum ada
echo -e "${YELLOW}[3/8] Memeriksa Composer...${NC}"
if ! command -v composer &> /dev/null; then
    echo -e "${CYAN}Menginstal Composer...${NC}"
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm /tmp/composer-setup.php
fi

# 5. Setup Database MariaDB & User Aplikasi Khusus
echo -e "${YELLOW}[4/8] Mengatur Database MariaDB (sipena_genbi) & User Aplikasi...${NC}"
systemctl start mariadb || systemctl start mysql
systemctl enable mariadb || systemctl enable mysql

DB_NAME="sipena_genbi"
DB_USER="sipena_user"
DB_PASS="SipenaGenbi2026!"

mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "ALTER USER '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';"
mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'127.0.0.1';"
mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo -e "${GREEN}Database '${DB_NAME}' dan user '${DB_USER}' berhasil disiapkan!${NC}"

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

# Bersihkan konfigurasi lama agar tidak bentrok dengan comment (#)
sed -i '/^#* *APP_ENV=/d' "$APP_DIR/.env"
sed -i '/^#* *APP_DEBUG=/d' "$APP_DIR/.env"
sed -i '/^#* *APP_URL=/d' "$APP_DIR/.env"
sed -i '/^#* *DB_/d' "$APP_DIR/.env"

# Tulis konfigurasi baru yang valid
cat <<EOF >> "$APP_DIR/.env"

APP_ENV=production
APP_DEBUG=false
APP_URL=http://${PUBLIC_IP}:${WEB_PORT}

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASS}
EOF

# Install Dependencies PHP
echo -e "${CYAN}Menjalankan composer install...${NC}"
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=php+

# Bersihkan config cache agar Laravel membaca konfigurasi .env yang baru
php artisan config:clear || true

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

# Mulai dan aktifkan PHP-FPM
systemctl restart php8.4-fpm 2>/dev/null || systemctl restart php8.2-fpm 2>/dev/null || true
systemctl enable php8.4-fpm 2>/dev/null || systemctl enable php8.2-fpm 2>/dev/null || true

# Temukan PHP-FPM socket yang aktif
if [ -e /var/run/php/php8.4-fpm.sock ]; then
    PHP_FPM_SOCK="/var/run/php/php8.4-fpm.sock"
elif [ -e /var/run/php/php8.3-fpm.sock ]; then
    PHP_FPM_SOCK="/var/run/php/php8.3-fpm.sock"
elif [ -e /var/run/php/php8.2-fpm.sock ]; then
    PHP_FPM_SOCK="/var/run/php/php8.2-fpm.sock"
else
    PHP_FPM_SOCK=$(ls /var/run/php/php*-fpm.sock 2>/dev/null | head -n 1)
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
