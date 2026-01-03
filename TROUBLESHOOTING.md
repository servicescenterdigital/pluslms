# SchoolDream+ Troubleshooting Guide

## Common Issues and Solutions

### Database Connection Issues

#### Problem: "Database connection failed"

**Symptoms:**
- Error message: "Database connection failed: SQLSTATE[HY000] [2002]..."
- Cannot access the application
- Setup script fails

**Solutions:**

1. **Check if MySQL is running:**
   ```bash
   # Ubuntu/Debian
   sudo systemctl status mysql
   # or
   sudo systemctl status mariadb
   
   # Start if not running
   sudo systemctl start mysql
   ```

2. **Verify credentials in .env:**
   ```bash
   cat .env | grep DB_
   ```
   Make sure:
   - DB_HOST is correct (usually 127.0.0.1)
   - DB_PORT is correct (usually 3306)
   - DB_USERNAME and DB_PASSWORD are correct

3. **Test connection manually:**
   ```bash
   mysql -u root -p
   # Enter your password
   ```
   
4. **Run the connection test:**
   ```bash
   php test-mysql.php
   ```

5. **Reset MySQL password if forgotten:**
   ```bash
   sudo mysql
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_password';
   FLUSH PRIVILEGES;
   exit;
   ```

---

### PHP Issues

#### Problem: "PHP version not supported"

**Solution:**
```bash
# Check PHP version
php -v

# Ubuntu/Debian - Install PHP 7.4+
sudo apt-get install php php-cli php-mysql php-mbstring php-json
```

#### Problem: "Missing PHP extensions"

**Error:** "Call to undefined function mb_strlen" or similar

**Solution:**
```bash
# Install missing extensions
sudo apt-get install php-mbstring php-json php-xml

# Restart web server if using Apache/Nginx
sudo systemctl restart apache2
```

---

### Composer Issues

#### Problem: "Composer not found"

**Solution:**
```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verify
composer --version
```

#### Problem: "Composer install fails"

**Solution:**
```bash
# Update Composer
composer self-update

# Clear cache and try again
composer clear-cache
composer install --no-scripts

# If still fails, try
composer install --ignore-platform-reqs
```

---

### Permission Issues

#### Problem: "Permission denied" errors

**Error:** Cannot write to storage/ or public/uploads/

**Solution:**
```bash
# Linux/macOS
chmod -R 755 storage
chmod -R 755 public/uploads
chown -R $USER:$USER storage public/uploads

# Make sure directories exist
mkdir -p storage/{logs,cache,sessions}
mkdir -p public/uploads/{courses,certificates,profile_pictures}
```

---

### Port Already in Use

#### Problem: "Address already in use"

**Error:** "Failed to listen on localhost:3000"

**Solution:**

1. **Use a different port:**
   ```bash
   php -S localhost:8080 -t public
   ```
   
2. **Update .env:**
   ```env
   APP_URL=http://localhost:8080
   ```

3. **Or kill the process using port 3000:**
   ```bash
   # Find process
   lsof -i :3000
   # or
   netstat -tulpn | grep 3000
   
   # Kill process
   kill -9 <PID>
   ```

---

### Session Issues

#### Problem: "Session not working" / "Logged out immediately"

**Solution:**

1. **Check session directory permissions:**
   ```bash
   chmod 755 storage/sessions
   ```

2. **Clear existing sessions:**
   ```bash
   rm -rf storage/sessions/*
   ```

3. **Check PHP session settings:**
   ```bash
   php -i | grep session.save_path
   ```

---

### Database Setup Issues

#### Problem: "Database already exists" error

**Solution:**

**Option 1: Drop and recreate (CAUTION: This deletes all data)**
```bash
mysql -u root -p
DROP DATABASE IF EXISTS schooldream_lms;
exit
php database/setup.php
```

**Option 2: Just run seeds without recreating:**
```bash
mysql -u root -p schooldream_lms < database/seeds/seed.sql
```

#### Problem: "Table already exists" error during setup

**Solution:**
The setup script should handle this, but if not:
```bash
mysql -u root -p
USE schooldream_lms;
SHOW TABLES;  # See what exists
DROP TABLE table_name;  # Drop specific tables if needed
exit
```

---

### Login Issues

#### Problem: "Invalid email or password"

**Solutions:**

1. **Make sure you're using the correct credentials:**
   - Admin: admin@schooldream.com / admin123
   - Instructor: jpmunyaneza@schooldream.com / instructor123
   - Student: alice@example.com / student123

2. **Check if seed data was loaded:**
   ```bash
   mysql -u root -p schooldream_lms -e "SELECT email, role FROM users;"
   ```

3. **Re-run the seed:**
   ```bash
   mysql -u root -p schooldream_lms < database/seeds/seed.sql
   ```

#### Problem: "Instructor can't access dashboard"

**Cause:** Instructor account not approved

**Solution:**
1. Login as admin (admin@schooldream.com / admin123)
2. Go to Admin Dashboard → Instructors
3. Approve the instructor account

---

### File Upload Issues

#### Problem: "Failed to upload file"

**Solutions:**

1. **Check PHP upload settings:**
   ```bash
   php -i | grep upload_max_filesize
   php -i | grep post_max_size
   ```

2. **Increase limits in php.ini:**
   ```ini
   upload_max_filesize = 20M
   post_max_size = 25M
   ```

3. **Check directory permissions:**
   ```bash
   chmod -R 755 public/uploads
   ```

---

### Routing Issues

#### Problem: "404 Not Found" on all pages

**Cause:** Web server not configured correctly

**Solutions:**

**For Apache:**
Make sure `.htaccess` exists in `public/` directory:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
```

**For Nginx:**
Add to your site configuration:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**For PHP Built-in Server:**
Always use:
```bash
php -S localhost:3000 -t public
```
(Note the `-t public` flag)

---

### CSS/JS Not Loading

#### Problem: "Styles not applied" / "JavaScript not working"

**Solutions:**

1. **Check if files exist:**
   ```bash
   ls -la public/css/style.css
   ls -la public/js/main.js
   ```

2. **Check APP_URL in .env:**
   ```env
   APP_URL=http://localhost:3000
   ```
   Make sure it matches your actual URL

3. **Clear browser cache:**
   - Hard refresh: Ctrl+Shift+R (Windows/Linux) or Cmd+Shift+R (Mac)

---

### Performance Issues

#### Problem: "Slow page loads"

**Solutions:**

1. **Enable caching in production:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Add indexes to frequently queried columns** (already done in migrations)

3. **Use pagination for large datasets**

4. **Enable MySQL query cache:**
   ```sql
   SET GLOBAL query_cache_type = ON;
   SET GLOBAL query_cache_size = 1048576;
   ```

---

### API Issues

#### Problem: "API returns HTML instead of JSON"

**Cause:** PHP errors being displayed

**Solution:**
1. Check `storage/logs/` for errors
2. Fix the underlying PHP error
3. In production, ensure `APP_DEBUG=false`

#### Problem: "CORS errors when calling API"

**Solution:**
Add CORS headers in `public/index.php`:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
```

---

### Certificate Generation Issues

#### Problem: "Certificate not generated after completing course"

**Check:**
1. All lessons are marked as complete:
   ```sql
   SELECT l.id, l.title, lp.status 
   FROM lessons l 
   LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id 
   WHERE l.course_id = 1 AND lp.user_id = 1;
   ```

2. Manually generate certificate:
   ```php
   $certificate = new App\Models\Certificate();
   $certificate->generate(user_id, course_id);
   ```

---

### Production Deployment Issues

#### Problem: "Works locally but not in production"

**Checklist:**

1. **Update .env for production:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Set correct file permissions:**
   ```bash
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod -R 777 storage public/uploads
   ```

3. **Configure web server properly** (see Apache/Nginx examples in INSTALL.md)

4. **Enable HTTPS:**
   ```bash
   sudo certbot --apache -d yourdomain.com
   ```

5. **Set up database backup:**
   ```bash
   mysqldump -u root -p schooldream_lms > backup.sql
   ```

---

## Debugging Tips

### Enable Debug Mode
In `.env`:
```env
APP_DEBUG=true
```

### Check PHP Errors
```bash
tail -f storage/logs/error.log
```

### Check MySQL Logs
```bash
# Ubuntu/Debian
sudo tail -f /var/log/mysql/error.log
```

### Test Individual Components

**Test Database:**
```bash
php test-mysql.php
```

**Test Routes:**
```bash
php -r "require 'routes/web.php'; echo 'Routes loaded successfully';"
```

**Test Autoloading:**
```bash
composer dump-autoload
php -r "require 'vendor/autoload.php'; echo 'Autoload works';"
```

---

## Getting Help

If you're still stuck:

1. **Check the documentation:**
   - README.md - Features overview
   - INSTALL.md - Installation guide
   - API.md - API documentation

2. **Review error logs:**
   - `storage/logs/`
   - PHP error log
   - MySQL error log
   - Web server error log

3. **Verify environment:**
   - PHP version: `php -v`
   - MySQL version: `mysql --version`
   - Composer version: `composer --version`
   - PHP extensions: `php -m`

4. **Test with minimal setup:**
   ```bash
   # Fresh install
   rm -rf vendor
   composer install
   php database/setup.php
   php -S localhost:3000 -t public
   ```

---

## Common Error Messages and Solutions

| Error Message | Solution |
|--------------|----------|
| "Call to undefined function env()" | Run `composer install` |
| "Class not found" | Run `composer dump-autoload` |
| "SQLSTATE[HY000] [2002]" | MySQL not running - start it |
| "SQLSTATE[42S02]: Base table or view not found" | Run `php database/setup.php` |
| "Cannot modify header information" | Check for whitespace before `<?php` |
| "Undefined index: user" | User not logged in - check session |
| "Permission denied" | Fix file permissions with chmod |
| "Address already in use" | Port in use - use different port |

---

## Quick Fixes

```bash
# Full reset (CAUTION: Deletes all data)
mysql -u root -p -e "DROP DATABASE IF EXISTS schooldream_lms;"
rm -rf vendor composer.lock
rm -rf storage/sessions/* storage/cache/*
composer install
php database/setup.php
php -S localhost:3000 -t public

# Partial reset (keeps database)
rm -rf storage/sessions/* storage/cache/*
composer dump-autoload
php -S localhost:3000 -t public

# Permissions fix
chmod -R 755 storage public/uploads
chown -R $USER:$USER storage public/uploads
```

---

**Still having issues?** Double-check that MySQL is running and your .env credentials are correct. 99% of issues are related to database connection or file permissions.
