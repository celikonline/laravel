#!/bin/bash

# Laravel Production Deployment Script
# Vega Asist Project Deployment

echo "=== Laravel Production Deployment Başlıyor ==="

# 1. Environment kontrol
if [ ! -f .env ]; then
    echo "HATA: .env dosyası bulunamadı!"
    echo ".env.production dosyasını .env olarak kopyalayın"
    exit 1
fi

# 2. Composer dependencies (production mode)
echo "📦 Composer dependencies yükleniyor..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. NPM dependencies ve build
echo "🎨 Frontend assets build ediliyor..."
npm ci --production
npm run build

# 4. Laravel optimizasyonları
echo "⚡ Laravel optimizasyonları yapılıyor..."

# Cache temizleme
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache oluşturma
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Database migration
echo "🗄️  Database migration çalıştırılıyor..."
php artisan migrate --force

# 6. Storage link oluşturma
echo "🔗 Storage link oluşturuluyor..."
php artisan storage:link

# 7. File permissions ayarlama
echo "🔐 File permissions ayarlanıyor..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 8. Queue restart (eğer kullanılıyorsa)
echo "🔄 Queue restart..."
php artisan queue:restart

echo "✅ Deployment başarıyla tamamlandı!"
echo "🌐 Uygulama production'a hazır!" 