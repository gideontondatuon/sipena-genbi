#!/usr/bin/env bash
# ==============================================================================
# SCRIPT UPDATE CEPAT SIPENA GENBI DI SEWANODE VPS
# ==============================================================================

set -e

GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
NC='\033[0m'

APP_DIR="/var/www/sipena-genbi"

echo -e "${CYAN}Menarik pembaruan dari repository GitHub...${NC}"
cd "$APP_DIR"
git pull origin main

echo -e "${CYAN}Menginstal dependensi Composer terbaru...${NC}"
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${CYAN}Menjalankan migrasi database baru jika ada...${NC}"
php artisan migrate --force

if command -v npm &> /dev/null; then
    echo -e "${CYAN}Membangun ulang aset frontend (Vite)...${NC}"
    npm run build
fi

echo -e "${CYAN}Membersihkan dan mengoptimalkan cache Laravel...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${CYAN}Mengatur izin file...${NC}"
chown -R www-data:www-data "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

systemctl reload nginx
systemctl reload php8.2-fpm || true

echo -e "${GREEN}Update selesai dengan sukses!${NC}"
