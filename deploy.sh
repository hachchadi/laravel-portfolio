#!/bin/bash

# Laravel Portfolio Deployment Script
# This script handles the deployment of the Laravel Portfolio application

set -e

echo "🚀 Starting Laravel Portfolio Deployment..."

# Configuration
APP_DIR="/var/www/laravel-portfolio"
BACKUP_DIR="/var/backups/laravel-portfolio"
REPO_URL="https://github.com/your-username/laravel-portfolio.git"
BRANCH="main"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root for security reasons"
   exit 1
fi

# Create backup directory if it doesn't exist
if [ ! -d "$BACKUP_DIR" ]; then
    print_status "Creating backup directory..."
    sudo mkdir -p "$BACKUP_DIR"
    sudo chown $USER:$USER "$BACKUP_DIR"
fi

# Backup current deployment if it exists
if [ -d "$APP_DIR" ]; then
    print_status "Creating backup of current deployment..."
    BACKUP_NAME="backup-$(date +%Y%m%d-%H%M%S)"
    sudo cp -r "$APP_DIR" "$BACKUP_DIR/$BACKUP_NAME"
    print_status "Backup created: $BACKUP_DIR/$BACKUP_NAME"
fi

# Clone or update repository
if [ ! -d "$APP_DIR" ]; then
    print_status "Cloning repository..."
    sudo git clone "$REPO_URL" "$APP_DIR"
else
    print_status "Updating repository..."
    cd "$APP_DIR"
    sudo git fetch origin
    sudo git reset --hard origin/$BRANCH
fi

cd "$APP_DIR"

# Set proper ownership
print_status "Setting file permissions..."
sudo chown -R $USER:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage"
sudo chmod -R 775 "$APP_DIR/bootstrap/cache"

# Install/Update Composer dependencies
print_status "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install/Update NPM dependencies
print_status "Installing NPM dependencies..."
npm ci --production

# Build assets
print_status "Building production assets..."
npm run build

# Copy environment file if it doesn't exist
if [ ! -f ".env" ]; then
    print_status "Creating environment file..."
    cp .env.example .env
    print_warning "Please update the .env file with your production settings"
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    print_status "Generating application key..."
    php artisan key:generate
fi

# Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
print_status "Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create symbolic link for storage
print_status "Creating storage symbolic link..."
php artisan storage:link

# Restart services
print_status "Restarting services..."
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

# Run queue workers if configured
if grep -q "QUEUE_CONNECTION=redis\|QUEUE_CONNECTION=database" .env; then
    print_status "Restarting queue workers..."
    sudo supervisorctl restart laravel-worker:*
fi

print_status "✅ Deployment completed successfully!"
print_warning "Don't forget to:"
print_warning "1. Update your .env file with production settings"
print_warning "2. Configure your web server to point to the public directory"
print_warning "3. Set up SSL certificate"
print_warning "4. Configure backup strategy"
print_warning "5. Set up monitoring and logging"

echo ""
echo "🎉 Laravel Portfolio is now deployed!"
echo "📁 Application directory: $APP_DIR"
echo "💾 Backup directory: $BACKUP_DIR"