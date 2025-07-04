#!/bin/bash

# Vega Asist Laravel Backup Script
# Otomatik backup almak için kullanılır

# Konfigürasyon
PROJECT_PATH="/var/www/html/vegaasist"
BACKUP_DIR="/var/backups/vegaasist"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="vegaasist_backup_$DATE"

# Database bilgileri (.env dosyasından okunacak)
DB_HOST=$(grep DB_HOST $PROJECT_PATH/.env | cut -d '=' -f2)
DB_DATABASE=$(grep DB_DATABASE $PROJECT_PATH/.env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME $PROJECT_PATH/.env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD $PROJECT_PATH/.env | cut -d '=' -f2)

# Backup klasörü oluştur
mkdir -p $BACKUP_DIR/$BACKUP_NAME

echo "=== Vega Asist Backup Başlıyor - $DATE ==="

# 1. Database Backup
echo "📁 Database backup alınıyor..."
mysqldump -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE > $BACKUP_DIR/$BACKUP_NAME/database.sql

if [ $? -eq 0 ]; then
    echo "✅ Database backup başarılı"
else
    echo "❌ Database backup başarısız!"
    exit 1
fi

# 2. Files Backup (storage klasörü)
echo "📁 Storage dosyaları backup alınıyor..."
cp -r $PROJECT_PATH/storage $BACKUP_DIR/$BACKUP_NAME/

# 3. Environment dosyası backup
echo "📁 Environment dosyası backup alınıyor..."
cp $PROJECT_PATH/.env $BACKUP_DIR/$BACKUP_NAME/

# 4. Public uploads backup (eğer varsa)
if [ -d "$PROJECT_PATH/public/uploads" ]; then
    echo "📁 Public uploads backup alınıyor..."
    cp -r $PROJECT_PATH/public/uploads $BACKUP_DIR/$BACKUP_NAME/
fi

# 5. Log dosyaları backup
echo "📁 Log dosyaları backup alınıyor..."
cp -r $PROJECT_PATH/storage/logs $BACKUP_DIR/$BACKUP_NAME/

# 6. Backup'ı sıkıştır
echo "📦 Backup sıkıştırılıyor..."
cd $BACKUP_DIR
tar -czf $BACKUP_NAME.tar.gz $BACKUP_NAME
rm -rf $BACKUP_NAME

# 7. Eski backup'ları temizle (30 günden eski)
echo "🗑️  Eski backup'lar temizleniyor..."
find $BACKUP_DIR -name "vegaasist_backup_*.tar.gz" -mtime +30 -delete

# 8. Backup boyutu
BACKUP_SIZE=$(du -h $BACKUP_DIR/$BACKUP_NAME.tar.gz | cut -f1)
echo "✅ Backup tamamlandı!"
echo "📊 Backup boyutu: $BACKUP_SIZE"
echo "📍 Backup konumu: $BACKUP_DIR/$BACKUP_NAME.tar.gz"

# 9. Backup log'u
echo "$(date): Backup tamamlandı - $BACKUP_NAME.tar.gz ($BACKUP_SIZE)" >> $BACKUP_DIR/backup.log

# 10. Email bildirimi (isteğe bağlı)
# mail -s "Vega Asist Backup Tamamlandı" admin@vegaasist.com.tr < /dev/null

echo "🎉 Backup işlemi başarıyla tamamlandı!" 