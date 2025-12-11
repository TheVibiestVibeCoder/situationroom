#!/bin/bash

#################################################
# Situation Room - Server Setup Script
# Ubuntu 22.04 LTS
# Run as root: bash server-setup.sh
#################################################

set -e  # Exit on error

echo "🚀 Starting Situation Room server setup..."

# Update system
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# Install required packages
echo "📦 Installing PHP, PostgreSQL, Nginx..."
apt install -y \
  php8.3 php8.3-fpm php8.3-cli php8.3-common php8.3-mysql \
  php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd \
  php8.3-sqlite3 php8.3-pgsql php8.3-bcmath php8.3-redis \
  postgresql postgresql-contrib \
  nginx \
  git curl unzip \
  redis-server

# Install Composer
echo "📦 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Node.js (for npm)
echo "📦 Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Setup PostgreSQL
echo "🗄️  Setting up PostgreSQL database..."
read -p "Enter database password: " DB_PASSWORD
sudo -u postgres psql -c "CREATE USER situationroom WITH PASSWORD '${DB_PASSWORD}';"
sudo -u postgres psql -c "CREATE DATABASE situationroom OWNER situationroom;"

# Setup firewall
echo "🔥 Configuring firewall..."
ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

# Install Caddy for automatic SSL
echo "🔐 Installing Caddy web server..."
apt install -y debian-keyring debian-archive-keyring apt-transport-https
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | tee /etc/apt/sources.list.d/caddy-stable.list
apt update
apt install caddy

echo "✅ Server setup complete!"
echo ""
echo "Next steps:"
echo "1. Clone your repository to /var/www/situation-room"
echo "2. Copy .env.example to .env and configure"
echo "3. Run: composer install --no-dev"
echo "4. Run: php artisan key:generate"
echo "5. Run: php artisan migrate"
echo "6. Configure Caddy (see deployment instructions)"
echo ""
echo "Database credentials:"
echo "  Database: situationroom"
echo "  Username: situationroom"
echo "  Password: ${DB_PASSWORD}"
