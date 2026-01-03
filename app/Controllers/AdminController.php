<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;

class AdminController extends Controller
{
    private $userModel;
    private $courseModel;
    private $enrollmentModel;
    private $certificateModel;
    
    public function __construct()
    {
        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->certificateModel = new Certificate();
    }
    
    public function dashboard()
    {
        $userStats = $this->userModel->getStats();
        $courseStats = $this->courseModel->getStats();
        $enrollmentStats = $this->enrollmentModel->getEnrollmentStats();
        $revenueStats = $this->enrollmentModel->getRevenueStats();
        
        $pendingInstructors = $this->userModel->where(['role' => 'instructor', 'instructor_status' => 'pending']);
        $pendingCourses = $this->courseModel->where(['status' => 'pending']);
        
        $this->view('admin.dashboard', [
            'userStats' => $userStats,
            'courseStats' => $courseStats,
            'enrollmentStats' => $enrollmentStats,
            'revenueStats' => $revenueStats,
            'pendingInstructors' => $pendingInstructors,
            'pendingCourses' => $pendingCourses
        ]);
    }
    
    public function users()
    {
        $role = $_GET['role'] ?? 'all';
        
        if ($role === 'all') {
            $users = $this->userModel->all('created_at DESC');
        } else {
            $users = $this->userModel->where(['role' => $role], 'created_at DESC');
        }
        
        $this->view('admin.users', [
            'users' => $users,
            'selectedRole' => $role
        ]);
    }
    
    public function instructors()
    {
        $instructors = $this->userModel->where(['role' => 'instructor'], 'created_at DESC');
        $pendingInstructors = array_filter($instructors, fn($i) => $i['instructor_status'] === 'pending');
        
        $this->view('admin.instructors', [
            'instructors' => $instructors,
            'pendingInstructors' => $pendingInstructors
        ]);
    }
    
    public function approveInstructor($id)
    {
        if ($this->userModel->approveInstructor($id)) {
            flash('success', 'Instructor approved successfully');
        } else {
            flash('error', 'Failed to approve instructor');
        }
        
        $this->redirect('/admin/instructors');
    }
    
    public function rejectInstructor($id)
    {
        if ($this->userModel->rejectInstructor($id)) {
            flash('success', 'Instructor rejected');
        } else {
            flash('error', 'Failed to reject instructor');
        }
        
        $this->redirect('/admin/instructors');
    }
    
    public function courses()
    {
        $status = $_GET['status'] ?? 'all';
        
        if ($status === 'all') {
            $courses = $this->courseModel->getAll();
        } else {
            $courses = $this->courseModel->getAll($status);
        }
        
        $this->view('admin.courses', [
            'courses' => $courses,
            'selectedStatus' => $status
        ]);
    }
    
    public function approveCourse($id)
    {
        if ($this->courseModel->approveCourse($id)) {
            flash('success', 'Course approved and published');
        } else {
            flash('error', 'Failed to approve course');
        }
        
        $this->redirect('/admin/courses');
    }
    
    public function rejectCourse($id)
    {
        if ($this->courseModel->rejectCourse($id)) {
            flash('success', 'Course rejected');
        } else {
            flash('error', 'Failed to reject course');
        }
        
        $this->redirect('/admin/courses');
    }
    
    public function enrollments()
    {
        $sql = "SELECT e.*, u.name as student_name, u.email as student_email,
                       c.title as course_title, c.price
                FROM enrollments e
                INNER JOIN users u ON e.user_id = u.id
                INNER JOIN courses c ON e.course_id = c.id
                ORDER BY e.enrolled_at DESC
                LIMIT 100";
        
        $enrollments = $this->enrollmentModel->fetchAll($sql);
        
        $this->view('admin.enrollments', [
            'enrollments' => $enrollments
        ]);
    }
    
    public function certificates()
    {
        $sql = "SELECT cert.*, u.name as user_name, u.email as user_email,
                       c.title as course_title
                FROM certificates cert
                INNER JOIN users u ON cert.user_id = u.id
                INNER JOIN courses c ON cert.course_id = c.id
                ORDER BY cert.issued_at DESC";
        
        $certificates = $this->certificateModel->fetchAll($sql);
        
        $this->view('admin.certificates', [
            'certificates' => $certificates
        ]);
    }
    
    public function reports()
    {
        $userStats = $this->userModel->getStats();
        $courseStats = $this->courseModel->getStats();
        $enrollmentStats = $this->enrollmentModel->getEnrollmentStats();
        $revenueStats = $this->enrollmentModel->getRevenueStats();
        
        $topCoursesSql = "SELECT c.title, COUNT(e.id) as enrollment_count, c.price
                         FROM courses c
                         LEFT JOIN enrollments e ON c.id = e.course_id
                         WHERE c.status = 'published'
                         GROUP BY c.id
                         ORDER BY enrollment_count DESC
                         LIMIT 10";
        $topCourses = $this->courseModel->fetchAll($topCoursesSql);
        
        $this->view('admin.reports', [
            'userStats' => $userStats,
            'courseStats' => $courseStats,
            'enrollmentStats' => $enrollmentStats,
            'revenueStats' => $revenueStats,
            'topCourses' => $topCourses
        ]);
    }
    
    public function deleteUser($id)
    {
        if ($id == auth()['id']) {
            flash('error', 'You cannot delete your own account');
            $this->redirect('/admin/users');
        }
        
        if ($this->userModel->delete($id)) {
            flash('success', 'User deleted successfully');
        } else {
            flash('error', 'Failed to delete user');
        }
        
        $this->redirect('/admin/users');
    }
}
