# SchoolDream+ Default Credentials

## ⚠️ Important Security Notice

**These are default credentials for development and testing only.**

**For production deployment:**
1. Change all default passwords immediately
2. Create new admin accounts
3. Delete or disable default accounts
4. Use strong, unique passwords
5. Enable two-factor authentication (when implemented)

---

## 🔑 User Accounts

### Admin Account
**Email:** `admin@schooldream.com`  
**Password:** `admin123`  
**Role:** Administrator  
**Status:** Active  

**Access:**
- Full system administration
- User management (view, delete)
- Instructor approval/rejection
- Course approval/rejection
- Reports and analytics
- Certificate management
- System settings

**Test Actions:**
- Approve pending instructors
- Approve pending courses
- View system statistics
- Generate reports
- Manage users

---

### Instructor Accounts

#### Instructor 1 (Approved)
**Email:** `jpmunyaneza@schooldream.com`  
**Password:** `instructor123`  
**Name:** Dr. Jean-Paul Munyaneza  
**Role:** Instructor  
**Status:** Approved  
**Courses:** 4 published courses  

**Access:**
- Create and manage courses
- Add lessons and quizzes
- View student progress
- Send announcements
- Course statistics

**Test Actions:**
- Create a new course
- Add lessons to existing courses
- Create quizzes
- View enrolled students

---

#### Instructor 2 (Approved)
**Email:** `guwase@schooldream.com`  
**Password:** `instructor123`  
**Name:** Prof. Grace Uwase  
**Role:** Instructor  
**Status:** Approved  
**Courses:** 1 published course  

---

#### Instructor 3 (Approved)
**Email:** `pnkubito@schooldream.com`  
**Password:** `instructor123`  
**Name:** Eng. Patrick Nkubito  
**Role:** Instructor  
**Status:** Approved  
**Courses:** 2 published courses  

---

#### Instructor 4 (Pending Approval)
**Email:** `smukamana@schooldream.com`  
**Password:** `instructor123`  
**Name:** Sarah Mukamana  
**Role:** Instructor  
**Status:** Pending  

**Note:** This account cannot access instructor features until approved by admin.

**Test Actions:**
- Login to see "Pending Approval" message
- Admin can approve this account

---

### Student Accounts

#### Student 1
**Email:** `alice@example.com`  
**Password:** `student123`  
**Name:** Alice Kabera  
**Role:** Student  
**Enrollments:** 2 courses  
**Progress:** In progress on Course 1  

**Test Actions:**
- Enroll in courses
- Complete lessons
- Take quizzes
- View progress
- Earn certificates

---

#### Student 2
**Email:** `bob@example.com`  
**Password:** `student123`  
**Name:** Bob Mugisha  
**Role:** Student  
**Enrollments:** 2 courses  

---

#### Student 3
**Email:** `claire@example.com`  
**Password:** `student123`  
**Name:** Claire Uwimana  
**Role:** Student  
**Enrollments:** 2 courses (1 completed)  
**Certificates:** 1  

**Test Actions:**
- View completed course
- Download certificate
- View certificate history

---

#### Student 4
**Email:** `david@example.com`  
**Password:** `student123`  
**Name:** David Habimana  
**Role:** Student  
**Enrollments:** 2 courses  

---

#### Student 5
**Email:** `emma@example.com`  
**Password:** `student123`  
**Name:** Emma Ingabire  
**Role:** Student  
**Enrollments:** 2 courses  

---

## 📊 Sample Data Summary

### Users by Role
- **Admins:** 1
- **Instructors (Approved):** 3
- **Instructors (Pending):** 1
- **Students:** 5
- **Total:** 10 users

### Courses
- **Published:** 6 courses
- **Pending Approval:** 1 course
- **Total:** 7 courses

### Enrollments
- **Total:** 10 enrollments
- **Active:** 9
- **Completed:** 1

### Certificates
- **Issued:** 1 certificate
- **Number:** SD-2024-A1B2C3D4

---

## 🔐 Database Access

If you need direct database access:

**Database:** `schooldream_lms`  
**Username:** `root` (or as configured in .env)  
**Password:** (as configured in .env)  

**Connection:**
```bash
mysql -u root -p schooldream_lms
```

---

## 🧪 Testing Scenarios

### Scenario 1: Student Journey
**Login as:** alice@example.com / student123

1. Browse courses
2. Enroll in "Introduction to Programming" (FREE)
3. Complete first lesson
4. Take quiz
5. Complete all lessons
6. Download certificate

### Scenario 2: Instructor Journey
**Login as:** jpmunyaneza@schooldream.com / instructor123

1. View dashboard
2. Create new course
3. Add lessons with different content types
4. Create quiz with questions
5. Submit for approval
6. View enrolled students

### Scenario 3: Admin Journey
**Login as:** admin@schooldream.com / admin123

1. View dashboard statistics
2. Approve pending instructor (smukamana@schooldream.com)
3. Approve pending course
4. View reports
5. Manage users

### Scenario 4: New User Registration
1. Click "Register"
2. Fill in details
3. Choose role (Student or Instructor)
4. Submit
5. Login with new credentials

---

## 📝 Password Requirements

**Current:** No strict requirements (for development)

**Recommended for Production:**
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character
- Cannot be same as email

---

## 🔄 Changing Passwords

### For Logged-in Users
1. Go to Profile
2. Update password
3. Save changes

### For Admins (Reset User Password)
Direct database update:
```sql
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'user@example.com';
```
(This sets password to 'password')

### Generate New Password Hash
```php
<?php
echo password_hash('new_password', PASSWORD_DEFAULT);
?>
```

---

## 🛡️ Security Best Practices

**Before going to production:**

1. **Change all default passwords:**
   ```sql
   UPDATE users SET password = ? WHERE email = ?;
   ```

2. **Update .env secrets:**
   ```env
   JWT_SECRET=your-unique-secret-key-here
   ```

3. **Remove or disable test accounts:**
   ```sql
   DELETE FROM users WHERE email LIKE '%@example.com';
   ```

4. **Enable HTTPS**
5. **Set up firewall rules**
6. **Regular security audits**
7. **Database backups**

---

## 📧 Email Configuration

**Note:** Email functionality is configured but not active in development.

**To enable (in .env):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@schooldream.com
```

---

## 🆘 Forgot Password?

**Current Status:** No password reset feature in v1.0

**Workaround for development:**
1. Use one of the default accounts listed above
2. Or reset via database (see "Changing Passwords" above)

**Future Enhancement:** Password reset via email

---

## 📌 Quick Reference Card

```
┌─────────────────────────────────────────────┐
│         SCHOOLDREAM+ CREDENTIALS            │
├─────────────────────────────────────────────┤
│ ADMIN                                       │
│ Email: admin@schooldream.com                │
│ Pass:  admin123                             │
├─────────────────────────────────────────────┤
│ INSTRUCTOR (Approved)                       │
│ Email: jpmunyaneza@schooldream.com          │
│ Pass:  instructor123                        │
├─────────────────────────────────────────────┤
│ STUDENT                                     │
│ Email: alice@example.com                    │
│ Pass:  student123                           │
├─────────────────────────────────────────────┤
│ All passwords are "password123" format      │
│ except admin which is "admin123"            │
└─────────────────────────────────────────────┘
```

---

**Remember:** These credentials are for **development and testing only**. Always change them in production!

For more information, see:
- [QUICKSTART.md](QUICKSTART.md) - Getting started
- [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Common issues
- [README.md](README.md) - Full documentation
