#!/bin/bash

#################################################
# Situation Room - Backup Script
# Run daily via cron: 0 2 * * * /var/www/situation-room/scripts/backup.sh
#################################################

set -e  # Exit on error

DATE=$(date +%Y-%m-%d-%H%M)
BACKUP_DIR="/root/backups"

mkdir -p $BACKUP_DIR

echo "🗄️  Starting backup: $DATE"

# Backup database
echo "📦 Backing up PostgreSQL database..."
sudo -u postgres pg_dump situationroom > $BACKUP_DIR/db-$DATE.sql

# Backup uploaded files (if any)
echo "📦 Backing up storage files..."
tar -czf $BACKUP_DIR/storage-$DATE.tar.gz /var/www/situation-room/storage

# Backup .env file
echo "📦 Backing up configuration..."
cp /var/www/situation-room/.env $BACKUP_DIR/env-$DATE.backup

# Keep only last 7 days of backups
echo "🧹 Cleaning up old backups (keeping last 7 days)..."
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
find $BACKUP_DIR -name "*.backup" -mtime +7 -delete

echo "✅ Backup complete: $DATE"
echo "📊 Current backups:"
ls -lh $BACKUP_DIR
