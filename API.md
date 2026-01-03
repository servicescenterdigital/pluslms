# SchoolDream+ API Documentation

## Base URL
```
http://localhost:3000/api
```

## Authentication
Most endpoints require the user to be logged in via session-based authentication. The API uses the same session as the web application.

## Response Format
All responses are in JSON format:

### Success Response
```json
{
    "success": true,
    "data": { ... }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description"
}
```

---

## Endpoints

### 1. Get All Courses
Retrieve all published courses.

**Endpoint:** `GET /api/courses`

**Authentication:** Not required

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Introduction to ICT",
            "description": "...",
            "category": "Information Technology",
            "price": 12000.00,
            "duration": "4 weeks",
            "level": "beginner",
            "instructor_name": "Dr. Jean-Paul Munyaneza",
            "enrollment_count": 75,
            "average_rating": 4.5
        },
        ...
    ]
}
```

**Example:**
```bash
curl http://localhost:3000/api/courses
```

---

### 2. Get Course Details
Retrieve detailed information about a specific course, including lessons and quizzes.

**Endpoint:** `GET /api/courses/{id}`

**Authentication:** Not required

**Parameters:**
- `id` (path parameter) - Course ID

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Introduction to ICT",
        "description": "...",
        "instructor_name": "Dr. Jean-Paul Munyaneza",
        "lessons": [
            {
                "id": 1,
                "title": "Welcome to ICT",
                "content_type": "text",
                "duration": 15
            },
            ...
        ],
        "quizzes": [
            {
                "id": 1,
                "title": "ICT Fundamentals Quiz",
                "passing_score": 70
            },
            ...
        ]
    }
}
```

**Example:**
```bash
curl http://localhost:3000/api/courses/1
```

---

### 3. Enroll in Course
Enroll the authenticated user in a course.

**Endpoint:** `POST /api/enroll`

**Authentication:** Required

**Request Body:**
```json
{
    "course_id": 1
}
```

**Response:**
```json
{
    "success": true,
    "message": "Enrollment successful"
}
```

**Error Responses:**
- `401`: Not authenticated
- `400`: Already enrolled or invalid course ID
- `404`: Course not found

**Example:**
```bash
curl -X POST http://localhost:3000/api/enroll \
  -H "Content-Type: application/json" \
  -d '{"course_id": 1}' \
  --cookie "schooldream_session=..."
```

---

### 4. Get My Enrollments
Retrieve all courses the authenticated user is enrolled in.

**Endpoint:** `GET /api/my-enrollments`

**Authentication:** Required

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "course_id": 1,
            "title": "Introduction to ICT",
            "enrolled_at": "2024-01-15 10:30:00",
            "progress_percentage": 45.5,
            "completed_lessons": 5,
            "total_lessons": 11
        },
        ...
    ]
}
```

**Example:**
```bash
curl http://localhost:3000/api/my-enrollments \
  --cookie "schooldream_session=..."
```

---

### 5. Update Lesson Progress
Mark a lesson as started or completed.

**Endpoint:** `POST /api/lesson-progress`

**Authentication:** Required

**Request Body:**
```json
{
    "lesson_id": 1,
    "action": "complete"
}
```

**Actions:**
- `start` - Mark lesson as started
- `complete` - Mark lesson as completed

**Response:**
```json
{
    "success": true,
    "message": "Progress updated"
}
```

**If course completed:**
```json
{
    "success": true,
    "message": "Lesson completed",
    "certificate": {
        "id": 1,
        "certificate_number": "SD-2024-A1B2C3D4",
        "issued_at": "2024-01-20 15:45:00"
    }
}
```

**Example:**
```bash
curl -X POST http://localhost:3000/api/lesson-progress \
  -H "Content-Type: application/json" \
  -d '{"lesson_id": 1, "action": "complete"}' \
  --cookie "schooldream_session=..."
```

---

### 6. Submit Quiz
Submit answers for a quiz and receive score.

**Endpoint:** `POST /api/quiz/{id}/submit`

**Authentication:** Required

**Parameters:**
- `id` (path parameter) - Quiz ID

**Request Body:**
```json
{
    "answers": {
        "1": "Central Processing Unit",
        "2": "Keyboard",
        "3": "False",
        "4": "World Wide Web",
        "5": "HTTPS"
    }
}
```

**Response:**
```json
{
    "success": true,
    "score": 80.0,
    "passed": true,
    "passing_score": 70
}
```

**Example:**
```bash
curl -X POST http://localhost:3000/api/quiz/1/submit \
  -H "Content-Type: application/json" \
  -d '{"answers": {"1": "Answer 1", "2": "Answer 2"}}' \
  --cookie "schooldream_session=..."
```

---

### 7. Verify Certificate
Verify the authenticity of a certificate.

**Endpoint:** `GET /api/certificate/verify/{number}`

**Authentication:** Not required

**Parameters:**
- `number` (path parameter) - Certificate number (e.g., SD-2024-A1B2C3D4)

**Response:**
```json
{
    "success": true,
    "valid": true,
    "data": {
        "certificate_number": "SD-2024-A1B2C3D4",
        "user_name": "Alice Kabera",
        "user_email": "alice@example.com",
        "course_title": "Introduction to ICT and Internet Technologies",
        "issued_at": "2024-01-20 15:45:00",
        "duration": "4 weeks"
    }
}
```

**Invalid Certificate:**
```json
{
    "success": false,
    "valid": false,
    "message": "Certificate not found"
}
```

**Example:**
```bash
curl http://localhost:3000/api/certificate/verify/SD-2024-A1B2C3D4
```

---

### 8. Get Course Recommendations
Get personalized course recommendations based on user's enrollment history.

**Endpoint:** `GET /api/recommendations`

**Authentication:** Required

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 2,
            "title": "Web Development Fundamentals",
            "category": "Web Development",
            "price": 25000.00,
            "enrollment_count": 45,
            "average_rating": 4.8,
            "instructor_name": "Dr. Jean-Paul Munyaneza"
        },
        ...
    ]
}
```

**Example:**
```bash
curl http://localhost:3000/api/recommendations \
  --cookie "schooldream_session=..."
```

---

## Error Codes

| Status Code | Meaning |
|-------------|---------|
| 200 | Success |
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 500 | Internal Server Error |

---

## Authentication Flow

### 1. Login via Web Interface
```
POST /login
Body: email=user@example.com&password=password123
```

This creates a session cookie that will be used for API requests.

### 2. Use API with Session Cookie
Include the session cookie in your API requests:

**JavaScript:**
```javascript
fetch('http://localhost:3000/api/my-enrollments', {
    credentials: 'include'
})
.then(response => response.json())
.then(data => console.log(data));
```

**cURL:**
```bash
curl http://localhost:3000/api/my-enrollments \
  --cookie "schooldream_session=your_session_id"
```

---

## Rate Limiting

Currently, there is no rate limiting implemented. For production use, consider implementing:
- Rate limiting per IP address
- Rate limiting per user account
- API keys for external integrations

---

## CORS

CORS is not configured by default. For external API access, add CORS headers in `public/index.php`:

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
```

---

## Example Usage Scenarios

### Scenario 1: Student Enrolling in a Course

```javascript
// 1. Get available courses
const courses = await fetch('/api/courses').then(r => r.json());

// 2. Enroll in a course
const enrollResult = await fetch('/api/enroll', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ course_id: 1 }),
    credentials: 'include'
}).then(r => r.json());

// 3. Check enrollment status
const myEnrollments = await fetch('/api/my-enrollments', {
    credentials: 'include'
}).then(r => r.json());
```

### Scenario 2: Taking a Lesson

```javascript
// 1. Mark lesson as started
await fetch('/api/lesson-progress', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ lesson_id: 1, action: 'start' }),
    credentials: 'include'
});

// 2. Mark lesson as completed
const result = await fetch('/api/lesson-progress', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ lesson_id: 1, action: 'complete' }),
    credentials: 'include'
}).then(r => r.json());

if (result.certificate) {
    console.log('Certificate earned:', result.certificate.certificate_number);
}
```

### Scenario 3: Certificate Verification

```javascript
const certificateNumber = 'SD-2024-A1B2C3D4';
const verification = await fetch(`/api/certificate/verify/${certificateNumber}`)
    .then(r => r.json());

if (verification.valid) {
    console.log('Valid certificate for:', verification.data.user_name);
} else {
    console.log('Invalid certificate');
}
```

---

## Integration Examples

### PHP Integration
```php
<?php
$ch = curl_init('http://localhost:3000/api/courses');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$courses = json_decode($response, true);
curl_close($ch);
```

### Python Integration
```python
import requests

response = requests.get('http://localhost:3000/api/courses')
courses = response.json()
```

### JavaScript/Node.js Integration
```javascript
const axios = require('axios');

async function getCourses() {
    const response = await axios.get('http://localhost:3000/api/courses');
    return response.data;
}
```

---

## Future API Enhancements

Planned enhancements:
- [ ] JWT token authentication
- [ ] Pagination support
- [ ] Filtering and sorting options
- [ ] Webhook support for events
- [ ] Batch operations
- [ ] File upload endpoints
- [ ] Real-time notifications via WebSocket
- [ ] GraphQL endpoint

---

For more information, see:
- [README.md](README.md) - General documentation
- [INSTALL.md](INSTALL.md) - Installation guide
- [SUMMARY.md](SUMMARY.md) - Project summary
