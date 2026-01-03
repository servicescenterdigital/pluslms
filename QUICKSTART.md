# SchoolDream+ Quick Start Guide

Get up and running with SchoolDream+ in just 5 minutes!

## Prerequisites Check

Before you start, verify you have:
- [x] PHP 7.4 or higher installed
- [x] MySQL or MariaDB installed
- [x] Composer installed
- [x] Terminal/Command line access

**Quick Check:**
```bash
php -v        # Should show 7.4 or higher
mysql --version  # Should show MySQL/MariaDB
composer --version  # Should show Composer
```

If any of these fail, see [INSTALL.md](INSTALL.md) for installation instructions.

## Installation in 5 Steps

### Step 1: Navigate to Project Directory
```bash
cd /home/engine/project
# Or wherever you cloned/extracted the project
```

### Step 2: Install Dependencies
```bash
composer install
```
This downloads all required PHP packages (~30 seconds).

### Step 3: Configure Database
```bash
cp .env.example .env
nano .env  # or use your favorite text editor
```

Edit these lines in `.env`:
```env
DB_DATABASE=schooldream_lms
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here
```

Save and exit.

### Step 4: Set Up Database
```bash
php database/setup.php
```

You should see:
```
✓ Connected to MySQL server
✓ Database 'schooldream_lms' created
✓ Tables created successfully
✓ Database seeded successfully
```

### Step 5: Start the Server
```bash
./start.sh
# or manually:
php -S localhost:3000 -t public
```

## Access the Application

Open your browser and go to: **http://localhost:3000**

### Default Login Credentials

Try logging in with these accounts:

#### **Admin Account** (Full System Access)
- Email: `admin@schooldream.com`
- Password: `admin123`
- Access: User management, course approval, reports

#### **Instructor Account** (Create Courses)
- Email: `jpmunyaneza@schooldream.com`
- Password: `instructor123`
- Access: Create courses, add lessons, manage students

#### **Student Account** (Take Courses)
- Email: `alice@example.com`
- Password: `student123`
- Access: Enroll in courses, complete lessons, earn certificates

## What to Do Next

### As Admin
1. Login with admin credentials
2. Review the dashboard
3. Check pending instructor applications (Admin → Instructors)
4. Review pending courses (Admin → Courses)
5. View system reports (Admin → Reports)

### As Instructor
1. Login with instructor credentials
2. Go to Instructor Dashboard
3. Click "Create Course"
4. Add course details
5. Add lessons to your course
6. Create quizzes
7. Submit for admin approval

### As Student
1. Login with student credentials
2. Browse courses (Courses menu)
3. Enroll in a course
4. Complete lessons
5. Take quizzes
6. Earn certificates

## Explore Sample Data

The system comes with:
- 7 sample courses
- 8+ lessons with content
- Sample quizzes
- Enrolled students
- Course reviews
- Certificates

## Quick Tour

### 1. Homepage
- View featured courses
- See platform statistics
- Access login/register

### 2. Browse Courses
- Search courses
- Filter by category
- View course details
- Check instructor info

### 3. Student Dashboard
- See your enrolled courses
- Track your progress
- View recommendations
- Access certificates

### 4. Take a Course
1. Click "Courses" in menu
2. Choose a course (try "Introduction to Programming" - it's FREE)
3. Click "Enroll Now"
4. Go to "My Courses"
5. Start learning!

### 5. Complete a Lesson
1. Open an enrolled course
2. Click on a lesson
3. Read/watch the content
4. Click "Mark as Complete"
5. Move to next lesson

### 6. Take a Quiz
1. In your course page
2. Scroll to "Quizzes" section
3. Click "Take Quiz"
4. Answer questions
5. Submit to see your score

### 7. Earn a Certificate
1. Complete ALL lessons in a course
2. Certificate generated automatically
3. Go to "My Certificates"
4. Download your certificate

## Testing the API

### Get All Courses
```bash
curl http://localhost:3000/api/courses | jq
```

### Verify a Certificate
```bash
curl http://localhost:3000/api/certificate/verify/SD-2024-A1B2C3D4
```

See [API.md](API.md) for complete API documentation.

## Troubleshooting

### Problem: Database connection fails
**Solution:**
```bash
# Check if MySQL is running
sudo systemctl status mysql

# Start it if needed
sudo systemctl start mysql

# Test connection
php test-mysql.php
```

### Problem: Port 3000 already in use
**Solution:**
```bash
# Use a different port
php -S localhost:8080 -t public

# Update .env
APP_URL=http://localhost:8080
```

### Problem: Page looks broken (no CSS)
**Solution:**
1. Hard refresh: Ctrl+Shift+R
2. Check `APP_URL` in .env matches your actual URL
3. Restart PHP server

See [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for more solutions.

## File Structure Overview

```
schooldream-lms/
├── app/
│   ├── Controllers/      # Business logic
│   ├── Models/          # Database interactions
│   ├── Views/           # HTML templates
│   ├── Core/            # Framework core
│   ├── Middleware/      # Auth & access control
│   └── Helpers/         # Utility functions
├── config/              # Configuration
├── database/
│   ├── migrations/      # Database schema
│   └── seeds/          # Sample data
├── public/
│   ├── index.php       # Entry point
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript
│   └── uploads/        # User uploads
├── routes/
│   └── web.php         # Route definitions
├── storage/
│   ├── logs/           # Error logs
│   ├── cache/          # Cache files
│   └── sessions/       # Session data
├── .env                # Environment config
├── composer.json       # PHP dependencies
├── README.md          # Full documentation
└── start.sh           # Startup script
```

## Key Features to Try

### 1. Course Recommendations (AI)
- Enroll in a course
- Browse courses again
- See personalized recommendations

### 2. Progress Tracking
- Enroll in multiple courses
- Complete some lessons
- View your progress percentage
- See visual progress bars

### 3. Multi-Format Content
- Try different lesson types:
  - Text lessons
  - Video lessons (if available)
  - PDF documents
  - Exercises

### 4. Quiz System
- Take a quiz
- See instant results
- Check passing/failing status
- View score history

### 5. Certificate Generation
- Complete all lessons in a course
- Certificate auto-generated
- Download and print
- Verify certificate authenticity

## Next Steps

1. **Read the Documentation:**
   - [README.md](README.md) - Feature overview
   - [INSTALL.md](INSTALL.md) - Detailed installation
   - [API.md](API.md) - API reference
   - [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Common issues

2. **Explore the Code:**
   - Review controllers to understand logic
   - Check models for database queries
   - Examine views for UI structure
   - Study routes for URL patterns

3. **Customize:**
   - Modify CSS in `public/css/style.css`
   - Update branding/logo
   - Add new course categories
   - Customize email templates

4. **Extend:**
   - Add payment gateway
   - Integrate email notifications
   - Add social authentication
   - Implement real-time chat

## Tips & Best Practices

### For Admins
- Regularly review and approve instructor applications
- Monitor course quality before publishing
- Check reports for platform insights
- Manage user roles appropriately

### For Instructors
- Create clear, structured course content
- Write detailed lesson descriptions
- Include varied content types
- Create meaningful quizzes
- Engage with students via announcements

### For Students
- Complete lessons in order
- Take notes while learning
- Attempt quizzes after studying
- Review material if you don't pass
- Download certificates when earned

## Performance Tips

### Development
```env
APP_ENV=development
APP_DEBUG=true
```

### Production
```env
APP_ENV=production
APP_DEBUG=false
```

### Database Optimization
- Already has indexes on key columns
- Use pagination for large datasets
- Regular backups recommended

## Security Reminders

1. **Change default passwords** after first login
2. **Update JWT_SECRET** in .env
3. **Use HTTPS** in production
4. **Regular backups** of database
5. **Keep PHP and MySQL updated**
6. **Review user permissions** regularly

## Getting Help

1. Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
2. Review error logs in `storage/logs/`
3. Test with `php test-mysql.php`
4. Verify environment with `php -v`, `mysql --version`

## Congratulations! 🎉

You now have a fully functional LMS!

Explore, experiment, and enjoy SchoolDream+!

---

**Quick Links:**
- [Full Documentation](README.md)
- [Installation Guide](INSTALL.md)
- [API Reference](API.md)
- [Troubleshooting](TROUBLESHOOTING.md)
- [Project Summary](SUMMARY.md)
