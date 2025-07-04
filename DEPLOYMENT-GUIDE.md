# Vega Asist Laravel Deployment Rehberi 🚀

## 📋 Deployment Öncesi Hazırlık

### 1. Sistem Gereksinimleri
- **PHP**: 8.0 veya üzeri
- **MySQL**: 5.7 veya üzeri
- **Composer**: 2.x
- **Node.js**: 16.x veya üzeri
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **SSL Sertifikası**: HTTPS zorunlu

### 2. Sunucu Hazırlığı

#### Ubuntu/Debian için:
```bash
# PHP ve gerekli extensionlar
sudo apt update
sudo apt install php8.0 php8.0-cli php8.0-fpm php8.0-mysql php8.0-xml php8.0-mbstring php8.0-curl php8.0-zip php8.0-gd php8.0-intl

# Composer kurulumu
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js kurulumu
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt-get install -y nodejs

# MySQL kurulumu
sudo apt install mysql-server
```

#### CentOS/RHEL için:
```bash
# PHP ve gerekli extensionlar
sudo yum install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl php-zip php-gd php-intl

# Diğer kurulumlar yukarıdaki gibi...
```

## 🗄️ Database Kurulumu

### 1. MySQL Database Oluşturma
```sql
CREATE DATABASE vegaasist_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'vegaasist_user'@'localhost' IDENTIFIED BY 'GÜÇLÜ_ŞİFRE_BURAYA';
GRANT ALL PRIVILEGES ON vegaasist_production.* TO 'vegaasist_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Database Import
```bash
# Backup'tan import (eğer varsa)
mysql -u vegaasist_user -p vegaasist_production < database_backup.sql
```

## 📁 Dosya Yapısı ve Upload

### 1. Dosyaları Sunucuya Upload
```bash
# Git ile (Önerilen)
cd /var/www/html
git clone https://github.com/username/vegaasist.git
cd vegaasist

# Veya manuel upload
# FTP/SFTP ile dosyaları /var/www/html/vegaasist/ klasörüne upload edin
```

### 2. Environment Dosyası
```bash
# .env.example.production dosyasını .env olarak kopyalayın
cp .env.example.production .env

# .env dosyasını düzenleyin:
nano .env
```

#### Kritik .env Ayarları:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://panel.vegaasist.com.tr

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=vegaasist_production
DB_USERNAME=vegaasist_user
DB_PASSWORD=GÜÇLÜ_ŞİFRE_BURAYA

# SSL zorunlu ayarlar
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=panel.vegaasist.com.tr

# Mail ayarları
MAIL_HOST=mail.vegaasist.com.tr
MAIL_USERNAME=noreply@vegaasist.com.tr
MAIL_FROM_ADDRESS=noreply@vegaasist.com.tr

# POSNET ödeme ayarları
POSNET_MERCHANT_ID=GERÇEK_MERCHANT_ID
POSNET_TERMINAL_ID=GERÇEK_TERMINAL_ID
```

### 3. Deployment Script Çalıştırma
```bash
# Deployment script'ini çalıştırılabilir yapın
chmod +x deploy.sh

# Deployment'ı çalıştırın
./deploy.sh
```

### 4. Laravel Key Generate
```bash
php artisan key:generate --force
```

## 🔧 Web Server Konfigürasyonu

### Apache Konfigürasyonu
```bash
# Apache config dosyasını kopyalayın
sudo cp apache-config.conf /etc/apache2/sites-available/vegaasist.conf

# Site'ı aktifleştirin
sudo a2ensite vegaasist.conf
sudo a2enmod rewrite ssl headers deflate
sudo systemctl reload apache2
```

### Nginx Konfigürasyonu
```bash
# Nginx config dosyasını kopyalayın
sudo cp nginx-config.conf /etc/nginx/sites-available/vegaasist

# Site'ı aktifleştirin
sudo ln -s /etc/nginx/sites-available/vegaasist /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔐 SSL Sertifikası Kurulumu

### Let's Encrypt ile Ücretsiz SSL:
```bash
# Certbot kurulumu
sudo abmbnApache için
sudo apt install certbot python3-certbot-nginx   # Nginx için

# SSL sertifikası alma
sudo certbot --apache -d panel.vegaasist.com.tr  # Apache için
sudo certbot --nginx -d panel.vegaasist.com.tr   # Nginx için
```

### Manuel SSL Sertifikası:
```bash
# Sertifikalarınızı doğru konumlara kopyalayın
sudo cp your-certificate.crt /etc/ssl/certs/vegaasist.crt
sudo cp your-private-key.key /etc/ssl/private/vegaasist.key
sudo cp your-chain.crt /etc/ssl/certs/vegaasist-chain.crt
```

## 🔒 Güvenlik Ayarları

### 1. File Permissions
```bash
# Doğru izinleri ayarlayın
sudo chown -R www-data:www-data /var/www/html/vegaasist
sudo chmod -R 755 /var/www/html/vegaasist
sudo chmod -R 775 /var/www/html/vegaasist/storage
sudo chmod -R 775 /var/www/html/vegaasist/bootstrap/cache
```

### 2. Firewall Ayarları
```bash
# UFW Firewall (Ubuntu)
sudo ufw allow 22    # SSH
sudo ufw allow 80    # HTTP
sudo ufw allow 443   # HTTPS
sudo ufw enable
```

### 3. PHP Güvenlik Ayarları (php.ini)
```ini
expose_php = Off
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
max_execution_time = 30
max_input_time = 30
memory_limit = 128M
post_max_size = 20M
upload_max_filesize = 20M
```

## 📊 Monitoring ve Logging

### 1. Log Dosyaları
```bash
# Laravel logs
tail -f /var/www/html/vegaasist/storage/logs/laravel.log

# Web server logs
tail -f /var/log/apache2/vegaasist_error.log    # Apache
tail -f /var/log/nginx/vegaasist_error.log      # Nginx
```

### 2. Cron Jobs (Eğer gerekirse)
```bash
# Laravel scheduler için
crontab -e

# Aşağıdaki satırı ekleyin:
* * * * * cd /var/www/html/vegaasist && php artisan schedule:run >> /dev/null 2>&1
```

## 🔄 Backup ve Güncelleme

### 1. Otomatik Backup Script
```bash
# backup.sh dosyasını kullanın
chmod +x backup.sh
./backup.sh

# Cron ile otomatik backup (günlük)
0 2 * * * /var/www/html/vegaasist/backup.sh
```

### 2. Güncelleme İşlemi
```bash
# Git ile güncelleme
git pull origin main

# Deployment script'ini tekrar çalıştırın
./deploy.sh
```

## ✅ Deployment Checklist

### Deployment Öncesi:
- [ ] Backup alındı mı?
- [ ] .env dosyası hazırlandı mı?
- [ ] Database oluşturuldu mu?
- [ ] SSL sertifikası hazır mı?
- [ ] DNS ayarları yapıldı mı?

### Deployment Sırasında:
- [ ] Dosyalar upload edildi mi?
- [ ] Composer install çalıştırıldı mı?
- [ ] NPM build yapıldı mı?
- [ ] Migration çalıştırıldı mı?
- [ ] Cache'ler oluşturuldu mu?
- [ ] File permissions ayarlandı mı?

### Deployment Sonrası:
- [ ] Site açılıyor mu?
- [ ] HTTPS çalışıyor mu?
- [ ] Login işlemi çalışıyor mu?
- [ ] Database bağlantısı var mı?
- [ ] Email gönderimi çalışıyor mu?
- [ ] Ödeme sistemi test edildi mi?
- [ ] Error logları kontrol edildi mi?

## 🆘 Troubleshooting

### Yaygın Sorunlar:

#### 1. 500 Internal Server Error
```bash
# Log dosyalarını kontrol edin
tail -f /var/www/html/vegaasist/storage/logs/laravel.log

# Permissions kontrol edin
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Database Connection Error
```bash
# .env dosyasındaki database ayarlarını kontrol edin
# MySQL servisinin çalıştığını kontrol edin
sudo systemctl status mysql
```

#### 3. CSS/JS Dosyaları Yüklenmiyor
```bash
# NPM build yapıldı mı kontrol edin
npm run build

# Static files permissions
sudo chmod -R 755 public/
```

#### 4. SSL Sertifika Hatası
```bash
# Sertifika dosyalarının varlığını kontrol edin
ls -la /etc/ssl/certs/vegaasist.crt
ls -la /etc/ssl/private/vegaasist.key

# Sertifika geçerliliğini kontrol edin
openssl x509 -in /etc/ssl/certs/vegaasist.crt -text -noout
```

## 📞 Destek

Deployment sırasında sorun yaşarsanız:
1. Log dosyalarını kontrol edin
2. Error mesajlarını not alın
3. Bu rehberdeki troubleshooting bölümünü inceleyin
4. Gerekirse teknik destek ekibiyle iletişime geçin

## 🔄 Güncelleme Süreci

### Minor Güncellemeler (Bug fix, küçük özellikler):
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci --production && npm run build
php artisan migrate --force
php artisan cache:clear && php artisan config:cache
```

### Major Güncellemeler (Büyük değişiklikler):
1. Tam backup alın
2. Test ortamında deneyin
3. Maintenance mode'a alın: `php artisan down`
4. Güncellemeyi yapın
5. Test edin
6. Maintenance mode'dan çıkın: `php artisan up`

---

**📌 Not**: Bu rehber Vega Asist Laravel uygulaması için özelleştirilmiştir. Sunucu özelliklerinize göre ayarlamalar gerekebilir. 