# SchoolDream+ LMS

SchoolDream+ is a comprehensive, professional online Learning Management System (LMS) built with PHP, MySQL, and modern web technologies. It provides a complete platform for students, instructors, and administrators to manage online courses, track progress, issue certificates, and much more.

## Features

### Student Features
- ✅ Registration and authentication
- ✅ Browse and enroll in courses (free and paid)
- ✅ Access course content in multiple formats (videos, PDFs, text, images, exercises)
- ✅ Track progress for each lesson and course
- ✅ Take quizzes and view scores
- ✅ Earn and download certificates upon course completion
- ✅ Receive AI-powered course recommendations
- ✅ View course history and achievements

### Instructor Features
- ✅ Register as instructor (requires admin approval)
- ✅ Create and manage courses
- ✅ Upload course content in multiple formats
- ✅ Create quizzes with multiple question types
- ✅ Track student progress and performance
- ✅ Send announcements to enrolled students
- ✅ View enrollment statistics

### Admin Features
- ✅ Manage all users (students, instructors, admins)
- ✅ Approve/reject instructor applications
- ✅ Approve/reject courses before publishing
- ✅ View comprehensive reports (enrollments, revenue, performance)
- ✅ Manage certificates
- ✅ Access system-wide analytics

### Technical Features
- ✅ **Backend**: PHP 7.4+ with MVC architecture
- ✅ **Database**: MySQL with comprehensive schema
- ✅ **Authentication**: Session-based with role-based access control (RBAC)
- ✅ **API**: RESTful endpoints for all major operations
- ✅ **Dependencies**: Managed with Composer
- ✅ **Security**: Password hashing, CSRF protection, input sanitization
- ✅ **Responsive Design**: Mobile-friendly interface
- ✅ **AI Features**: Course recommendations based on enrollment history

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx) or PHP built-in server

### Setup Instructions

1. **Clone the repository**
   ```bash
   cd /home/engine/project
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and update your database credentials:
   ```
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=schooldream_lms
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Set up the database**
   ```bash
   php database/setup.php
   ```
   This will:
   - Create the database
   - Run all migrations
   - Seed sample data

5. **Start the application**
   ```bash
   php -S localhost:3000 -t public
   ```
   Or use the provided startup script:
   ```bash
   chmod +x start.sh
   ./start.sh
   ```

6. **Access the application**
   Open your browser and navigate to: `http://localhost:3000`

## Default Credentials

After running the setup script, you can login with these accounts:

### Admin
- Email: `admin@schooldream.com`
- Password: `admin123`

### Instructor (Approved)
- Email: `jpmunyaneza@schooldream.com`
- Password: `instructor123`

### Student
- Email: `alice@example.com`
- Password: `student123`

## Project Structure

```
schooldream-lms/
├── app/
│   ├── Controllers/       # Application controllers
│   ├── Models/           # Database models
│   ├── Views/            # View templates
│   ├── Core/             # Core framework classes
│   ├── Middleware/       # Authentication & authorization
│   └── Helpers/          # Helper functions
├── config/               # Configuration files
├── database/
│   ├── migrations/       # Database schema
│   └── seeds/           # Sample data
├── public/
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   ├── images/          # Image assets
│   └── uploads/         # User uploads
├── routes/              # Application routes
├── storage/             # Logs, cache, sessions
├── tests/               # Test files
├── vendor/              # Composer dependencies
├── .env                 # Environment configuration
├── composer.json        # PHP dependencies
└── README.md           # This file
```

## API Endpoints

### Public Endpoints
- `GET /api/courses` - List all published courses
- `GET /api/courses/{id}` - Get course details
- `GET /api/certificate/verify/{number}` - Verify certificate

### Authenticated Endpoints
- `POST /api/enroll` - Enroll in a course
- `GET /api/my-enrollments` - Get user's enrollments
- `POST /api/lesson-progress` - Update lesson progress
- `POST /api/quiz/{id}/submit` - Submit quiz answers
- `GET /api/recommendations` - Get personalized course recommendations

### Example API Usage

**Get all courses:**
```bash
curl http://localhost:3000/api/courses
```

**Enroll in a course:**
```bash
curl -X POST http://localhost:3000/api/enroll \
  -H "Content-Type: application/json" \
  -d '{"course_id": 1}'
```

**Verify a certificate:**
```bash
curl http://localhost:3000/api/certificate/verify/SD-2024-A1B2C3D4
```

## Database Schema

### Core Tables
- **users** - User accounts (students, instructors, admins)
- **courses** - Course information
- **lessons** - Course lessons and content
- **enrollments** - Student course enrollments
- **lesson_progress** - Student progress tracking
- **quizzes** - Course quizzes
- **quiz_questions** - Quiz questions and answers
- **quiz_attempts** - Student quiz submissions
- **certificates** - Issued certificates
- **reviews** - Course reviews and ratings
- **announcements** - Course announcements
- **activity_logs** - System activity tracking

## Development

### Adding a New Course
1. Login as an approved instructor
2. Navigate to Instructor Dashboard
3. Click "Create Course"
4. Fill in course details
5. Add lessons and quizzes
6. Submit for admin approval

### Running Tests
```bash
composer test
```

### Code Style
The project follows PSR-12 coding standards. Run code checks:
```bash
composer check-style
```

## Features in Detail

### Progress Tracking
- Real-time progress updates as students complete lessons
- Visual progress bars showing course completion
- Time tracking for each lesson
- Automatic certificate generation at 100% completion

### Quiz System
- Multiple question types: Multiple choice, True/False, Short answer
- Time limits and attempt limits
- Automatic grading
- Score history and best score tracking
- Passing score requirements

### Certificate System
- Automatic generation upon course completion
- Unique certificate numbers (format: SD-YEAR-XXXXXXXX)
- Downloadable and printable
- Verification system for authenticity

### Recommendation Engine
- Analyzes user's enrollment history
- Suggests courses based on:
  - Similar categories
  - Popular courses in user's interest areas
  - Completion rates and ratings
  - Enrollment trends

### Content Types Supported
- 📹 Videos (MP4, AVI, MOV)
- 📄 PDFs and Documents
- 📝 Rich text content
- 🖼️ Images (JPG, PNG, GIF)
- 💻 Code exercises

## Security Features

- Password hashing with bcrypt
- Session-based authentication
- Role-based access control (RBAC)
- CSRF protection
- Input sanitization
- SQL injection prevention with prepared statements
- XSS protection

## Scalability

The system is designed to scale:
- Efficient database queries with indexes
- Paginated results
- Modular architecture for easy feature addition
- Separate API layer for mobile/SPA integration
- Caching support (can be enabled)

## Future Enhancements

Potential features for future versions:
- [ ] Real-time chat and forums
- [ ] Live video streaming
- [ ] Assignment submissions with file uploads
- [ ] Gamification (badges, points, leaderboards)
- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] Mobile applications
- [ ] Advanced analytics dashboard
- [ ] Course bundles and subscriptions
- [ ] Multi-language support

## Support

For issues, questions, or contributions, please contact the development team.

## License

MIT License - See LICENSE file for details

## Credits

**SchoolDream+** - Professional Online Learning Management System
Developed for Rwanda's digital education ecosystem.

---

**Version:** 1.0.0  
**Last Updated:** January 2024
