# SchoolDream+ Installation Guide

This guide will help you install and set up SchoolDream+ LMS on your system.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 7.4 or higher** with extensions:
  - pdo
  - pdo_mysql
  - mbstring
  - json
- **MySQL 5.7+ or MariaDB 10.3+**
- **Composer** (PHP dependency manager)
- **Web server** (Apache, Nginx, or PHP built-in server for development)

## Step-by-Step Installation

### 1. Install PHP and Extensions

**Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install -y php php-cli php-mysql php-mbstring php-json php-xml
```

**macOS (using Homebrew):**
```bash
brew install php
```

**Windows:**
Download and install PHP from [php.net](https://www.php.net/downloads)

### 2. Install MySQL/MariaDB

**Ubuntu/Debian:**
```bash
sudo apt-get install -y mariadb-server mariadb-client
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

**macOS:**
```bash
brew install mysql
brew services start mysql
```

**Windows:**
Download and install from [MySQL.com](https://dev.mysql.com/downloads/installer/) or [MariaDB.org](https://mariadb.org/download/)

### 3. Secure MySQL Installation (Recommended)

```bash
sudo mysql_secure_installation
```

Follow the prompts to:
- Set root password
- Remove anonymous users
- Disallow remote root login
- Remove test database

### 4. Install Composer

**Linux/macOS:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Windows:**
Download from [getcomposer.org](https://getcomposer.org/download/)

### 5. Clone or Download the Project

```bash
cd /path/to/your/projects
git clone <repository-url> schooldream-lms
cd schooldream-lms
```

Or extract the ZIP file if downloaded.

### 6. Install PHP Dependencies

```bash
composer install
```

This will download all required PHP packages.

### 7. Configure Environment

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
# Application
APP_NAME="SchoolDream+"
APP_ENV=development
APP_URL=http://localhost:3000
APP_DEBUG=true

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schooldream_lms
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

# Session
SESSION_LIFETIME=120
SESSION_COOKIE_NAME=schooldream_session

# Security
JWT_SECRET=your-secret-key-change-this-in-production
```

**Important:** Change `DB_PASSWORD` to your MySQL root password or create a dedicated database user.

### 8. Test MySQL Connection

```bash
php test-mysql.php
```

This script will verify that:
- PHP can connect to MySQL
- Your credentials are correct
- The database exists (or needs to be created)

### 9. Set Up the Database

```bash
php database/setup.php
```

This will:
- Create the database (if it doesn't exist)
- Create all required tables
- Seed sample data (courses, users, etc.)

You should see output like:
```
✓ Connected to MySQL server
✓ Database 'schooldream_lms' created
✓ Tables created successfully
✓ Database seeded successfully
```

### 10. Set Permissions (Linux/macOS)

```bash
chmod +x start.sh
chmod -R 755 storage
chmod -R 755 public/uploads
```

Make sure these directories are writable:
- `storage/logs`
- `storage/cache`
- `storage/sessions`
- `public/uploads/*`

### 11. Start the Application

**Using the included script:**
```bash
./start.sh
```

**Or manually:**
```bash
php -S localhost:3000 -t public
```

The application will be available at: **http://localhost:3000**

## Default Login Credentials

After setup, you can login with these accounts:

### Admin Account
- **Email:** admin@schooldream.com
- **Password:** admin123
- **Access:** Full system administration

### Instructor Account
- **Email:** jpmunyaneza@schooldream.com
- **Password:** instructor123
- **Access:** Create and manage courses

### Student Account
- **Email:** alice@example.com
- **Password:** student123
- **Access:** Enroll in and take courses

## Troubleshooting

### MySQL Connection Issues

**Error: "Can't connect to local server"**
```bash
# Check if MySQL is running
sudo systemctl status mysql
# or
sudo systemctl status mariadb

# Start MySQL if needed
sudo systemctl start mysql
```

**Error: "Access denied for user 'root'"**
- Verify your password in `.env` file
- Try connecting manually:
  ```bash
  mysql -u root -p
  ```
- If you can't remember the root password, reset it:
  ```bash
  sudo mysql
  ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_password';
  FLUSH PRIVILEGES;
  ```

### Permission Errors

If you get file permission errors:
```bash
sudo chown -R $USER:$USER storage public/uploads
chmod -R 755 storage public/uploads
```

### Port Already in Use

If port 3000 is already in use:
```bash
php -S localhost:8080 -t public
```

Then update `APP_URL` in `.env`:
```env
APP_URL=http://localhost:8080
```

### Composer Installation Fails

If composer install fails, try:
```bash
composer install --no-scripts
composer dump-autoload
```

## Production Deployment

For production environments:

### 1. Update Environment Settings

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Use a Real Web Server

Configure Apache or Nginx to serve the `public/` directory.

**Apache Example (.htaccess already included):**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/schooldream-lms/public
    
    <Directory /path/to/schooldream-lms/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx Example:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/schooldream-lms/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 3. Secure File Permissions

```bash
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 storage public/uploads
```

### 4. Enable HTTPS

Use Let's Encrypt (free) or another SSL certificate:
```bash
sudo apt-get install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

### 5. Set Up Cron Jobs (Optional)

For automated tasks:
```bash
crontab -e
```

Add:
```
# Clear expired sessions daily
0 0 * * * cd /path/to/schooldream-lms && php artisan sessions:clear
```

### 6. Database Backup

Set up regular database backups:
```bash
#!/bin/bash
mysqldump -u root -p schooldream_lms > backup_$(date +%Y%m%d).sql
```

## Updating the Application

To update to a newer version:

```bash
# Backup database first!
mysqldump -u root -p schooldream_lms > backup.sql

# Pull latest code
git pull origin main

# Update dependencies
composer install

# Run any new migrations
php database/migrate.php

# Clear cache
rm -rf storage/cache/*
```

## Getting Help

- Check the README.md for feature documentation
- Review the code comments for implementation details
- Test with `php test-mysql.php` to diagnose connection issues
- Check `storage/logs/` for error logs

## Next Steps

After installation:

1. **Login as admin** and review system settings
2. **Approve pending instructors** if any
3. **Browse sample courses** to understand the system
4. **Create your own course** as an instructor
5. **Enroll as a student** to test the learning experience
6. **Review and customize** the code for your specific needs

Enjoy using SchoolDream+! 🎓
