# Laravel Portfolio - Installation Guide

This guide will help you install and set up the Laravel Portfolio application on your local development environment or production server.

## Table of Contents

- [System Requirements](#system-requirements)
- [Local Development Setup](#local-development-setup)
- [Production Deployment](#production-deployment)
- [Docker Setup](#docker-setup)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [File Permissions](#file-permissions)
- [Web Server Configuration](#web-server-configuration)
- [SSL Configuration](#ssl-configuration)
- [Troubleshooting](#troubleshooting)

## System Requirements

### Minimum Requirements

- **PHP**: 8.1 or higher
- **Composer**: 2.0 or higher
- **Node.js**: 16.0 or higher
- **NPM**: 8.0 or higher
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Nginx 1.18+ or Apache 2.4+
- **Redis**: 6.0+ (recommended for caching and sessions)

### PHP Extensions Required

- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- ZIP PHP Extension

## Local Development Setup

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/laravel-portfolio.git
cd laravel-portfolio
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Environment Variables

Edit the `.env` file and update the following variables:

```env
APP_NAME="Laravel Portfolio"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_portfolio
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

CONTACT_EMAIL=your-email@gmail.com
```

### 6. Database Setup

```bash
# Create the database
mysql -u root -p -e "CREATE DATABASE laravel_portfolio;"

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Build Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

Your application will be available at `http://localhost:8000`.

## Production Deployment

### Automated Deployment

Use the provided deployment script:

```bash
# Make the script executable
chmod +x deploy.sh

# Run the deployment
./deploy.sh
```

### Manual Deployment

#### 1. Server Preparation

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server redis-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-gd php8.1-curl php8.1-mbstring php8.1-zip php8.1-bcmath php8.1-redis

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

#### 2. Application Deployment

```bash
# Clone repository
sudo git clone https://github.com/your-username/laravel-portfolio.git /var/www/laravel-portfolio
cd /var/www/laravel-portfolio

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/laravel-portfolio
sudo chmod -R 755 /var/www/laravel-portfolio
sudo chmod -R 775 /var/www/laravel-portfolio/storage
sudo chmod -R 775 /var/www/laravel-portfolio/bootstrap/cache
```

#### 3. Environment Configuration

```bash
# Copy production environment file
cp .env.production .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link
```

## Docker Setup

### Development with Docker

```bash
# Build and start containers
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run dev

# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed
```

### Production with Docker

```bash
# Build production image
docker-compose -f docker-compose.prod.yml build

# Start production containers
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

## Configuration

### Mail Configuration

For Gmail SMTP:

1. Enable 2-factor authentication on your Google account
2. Generate an App Password
3. Use the App Password in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
```

### Redis Configuration

```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### File Storage Configuration

For local storage:
```env
FILESYSTEM_DISK=public
```

For AWS S3:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

## Database Setup

### MySQL

```sql
-- Create database
CREATE DATABASE laravel_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON laravel_portfolio.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
```

### PostgreSQL

```sql
-- Create database
CREATE DATABASE laravel_portfolio;

-- Create user
CREATE USER laravel_user WITH PASSWORD 'secure_password';
GRANT ALL PRIVILEGES ON DATABASE laravel_portfolio TO laravel_user;
```

## File Permissions

Set proper file permissions for security:

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/laravel-portfolio

# Set directory permissions
sudo find /var/www/laravel-portfolio -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/laravel-portfolio -type f -exec chmod 644 {} \;

# Set writable directories
sudo chmod -R 775 /var/www/laravel-portfolio/storage
sudo chmod -R 775 /var/www/laravel-portfolio/bootstrap/cache
```

## Web Server Configuration

### Nginx Configuration

Create `/etc/nginx/sites-available/laravel-portfolio`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/laravel-portfolio/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/laravel-portfolio /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache Configuration

Create `/etc/apache2/sites-available/laravel-portfolio.conf`:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/laravel-portfolio/public

    <Directory /var/www/laravel-portfolio/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/laravel-portfolio_error.log
    CustomLog ${APACHE_LOG_DIR}/laravel-portfolio_access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite laravel-portfolio.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

## SSL Configuration

### Using Certbot (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test automatic renewal
sudo certbot renew --dry-run
```

## Queue Workers (Production)

### Using Supervisor

Install Supervisor:

```bash
sudo apt install supervisor
```

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel-portfolio/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/laravel-portfolio/storage/logs/worker.log
stopwaitsecs=3600
```

Start the worker:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## Troubleshooting

### Common Issues

#### Permission Denied Errors

```bash
sudo chown -R www-data:www-data /var/www/laravel-portfolio
sudo chmod -R 775 /var/www/laravel-portfolio/storage
sudo chmod -R 775 /var/www/laravel-portfolio/bootstrap/cache
```

#### 500 Internal Server Error

1. Check error logs: `tail -f /var/log/nginx/error.log`
2. Ensure `.env` file exists and is properly configured
3. Run `php artisan config:clear`
4. Check file permissions

#### Database Connection Issues

1. Verify database credentials in `.env`
2. Ensure database server is running
3. Test connection: `php artisan tinker` then `DB::connection()->getPdo()`

#### Mail Not Sending

1. Verify SMTP credentials
2. Check firewall settings for SMTP ports
3. Test with: `php artisan tinker` then `Mail::raw('Test', function($msg) { $msg->to('test@example.com'); })`

#### Assets Not Loading

1. Run `npm run build`
2. Check `public/build` directory exists
3. Verify web server can serve static files

### Performance Optimization

```bash
# Enable OPcache
sudo nano /etc/php/8.1/fpm/php.ini
# Set: opcache.enable=1

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enable Redis for sessions and cache
# Update .env:
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

### Monitoring and Logging

```bash
# Monitor error logs
tail -f /var/www/laravel-portfolio/storage/logs/laravel.log

# Monitor web server logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# Monitor system resources
htop
df -h
free -m
```

## Support

If you encounter any issues during installation:

1. Check the [troubleshooting section](#troubleshooting)
2. Review the Laravel documentation: https://laravel.com/docs
3. Check the project's GitHub issues
4. Contact the developer at your-email@example.com

## Security Considerations

1. Keep all software updated
2. Use strong passwords
3. Enable firewall
4. Regular backups
5. Monitor logs for suspicious activity
6. Use HTTPS in production
7. Implement rate limiting
8. Regular security audits