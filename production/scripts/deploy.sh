#!/bin/bash

#################################################
# Situation Room - Deployment Script
# Run on server: bash /var/www/situation-room/scripts/deploy.sh
#################################################

set -e  # Exit on error

cd /var/www/situation-room

echo "🚀 Starting deployment..."

# Pull latest code
echo "📥 Pulling latest code from Git..."
git pull origin main

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📦 Installing Node dependencies..."
npm install

echo "🏗️  Building assets..."
npm run build

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and rebuild caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/situation-room/storage
chown -R www-data:www-data /var/www/situation-room/bootstrap/cache
chmod -R 755 /var/www/situation-room/storage
chmod -R 755 /var/www/situation-room/bootstrap/cache

# Restart services
echo "🔄 Restarting services..."
systemctl restart php8.3-fpm
systemctl reload caddy

echo "✅ Deployment complete!"
echo "🌐 Check your site: https://situationroom.eu"
