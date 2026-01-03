<?php

use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\InstructorMiddleware;

$router = new Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/courses', 'HomeController@courses');
$router->get('/courses/{id}', 'HomeController@courseDetails');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Student routes
$router->get('/student/dashboard', 'StudentController@dashboard', [AuthMiddleware::class]);
$router->get('/student/courses', 'StudentController@myCourses', [AuthMiddleware::class]);
$router->get('/student/course/{id}', 'StudentController@viewCourse', [AuthMiddleware::class]);
$router->post('/student/enroll/{id}', 'StudentController@enroll', [AuthMiddleware::class]);
$router->get('/student/lesson/{id}', 'StudentController@viewLesson', [AuthMiddleware::class]);
$router->post('/student/lesson/{id}/complete', 'StudentController@completeLesson', [AuthMiddleware::class]);
$router->get('/student/quiz/{id}', 'StudentController@takeQuiz', [AuthMiddleware::class]);
$router->post('/student/quiz/{id}/submit', 'StudentController@submitQuiz', [AuthMiddleware::class]);
$router->get('/student/certificates', 'StudentController@certificates', [AuthMiddleware::class]);
$router->get('/student/certificate/{id}/download', 'StudentController@downloadCertificate', [AuthMiddleware::class]);
$router->get('/student/profile', 'StudentController@profile', [AuthMiddleware::class]);
$router->post('/student/profile', 'StudentController@updateProfile', [AuthMiddleware::class]);

// Instructor routes
$router->get('/instructor/dashboard', 'InstructorController@dashboard', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->get('/instructor/courses', 'InstructorController@courses', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->get('/instructor/courses/create', 'InstructorController@createCourse', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/courses', 'InstructorController@storeCourse', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->get('/instructor/courses/{id}/edit', 'InstructorController@editCourse', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/courses/{id}', 'InstructorController@updateCourse', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/courses/{id}/lessons', 'InstructorController@addLesson', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/lessons/{id}', 'InstructorController@updateLesson', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/lessons/{id}/delete', 'InstructorController@deleteLesson', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/courses/{id}/quiz', 'InstructorController@createQuiz', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->get('/instructor/quiz/{id}/edit', 'InstructorController@editQuiz', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/quiz/{id}/questions', 'InstructorController@addQuestion', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->get('/instructor/courses/{id}/students', 'InstructorController@students', [AuthMiddleware::class, InstructorMiddleware::class]);
$router->post('/instructor/courses/{id}/announcements', 'InstructorController@createAnnouncement', [AuthMiddleware::class, InstructorMiddleware::class]);

// Admin routes
$router->get('/admin/dashboard', 'AdminController@dashboard', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/users', 'AdminController@users', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/instructors', 'AdminController@instructors', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/instructors/{id}/approve', 'AdminController@approveInstructor', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/instructors/{id}/reject', 'AdminController@rejectInstructor', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/courses', 'AdminController@courses', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/courses/{id}/approve', 'AdminController@approveCourse', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/courses/{id}/reject', 'AdminController@rejectCourse', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/enrollments', 'AdminController@enrollments', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/certificates', 'AdminController@certificates', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/reports', 'AdminController@reports', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/users/{id}/delete', 'AdminController@deleteUser', [AuthMiddleware::class, AdminMiddleware::class]);

// API routes
$router->get('/api/courses', 'ApiController@courses');
$router->get('/api/courses/{id}', 'ApiController@courseDetails');
$router->post('/api/enroll', 'ApiController@enroll');
$router->get('/api/my-enrollments', 'ApiController@myEnrollments');
$router->post('/api/lesson-progress', 'ApiController@lessonProgress');
$router->post('/api/quiz/{id}/submit', 'ApiController@submitQuiz');
$router->get('/api/certificate/verify/{number}', 'ApiController@verifyCertificate');
$router->get('/api/recommendations', 'ApiController@recommendations');

return $router;
