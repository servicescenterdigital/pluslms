<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\User;

class HomeController extends Controller
{
    private $courseModel;
    private $userModel;
    
    public function __construct()
    {
        $this->courseModel = new Course();
        $this->userModel = new User();
    }
    
    public function index()
    {
        $courses = $this->courseModel->getPublishedCourses();
        $stats = [
            'total_courses' => count($courses),
            'total_students' => $this->userModel->count(['role' => 'student']),
            'total_instructors' => $this->userModel->count(['role' => 'instructor', 'instructor_status' => 'approved'])
        ];
        
        $this->view('home', [
            'courses' => array_slice($courses, 0, 6),
            'stats' => $stats
        ]);
    }
    
    public function courses()
    {
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        
        if ($search) {
            $courses = $this->courseModel->search($search, $category);
        } elseif ($category) {
            $courses = $this->courseModel->where(['category' => $category, 'status' => 'published']);
        } else {
            $courses = $this->courseModel->getPublishedCourses();
        }
        
        $categories = $this->courseModel->getCategories();
        
        $this->view('courses.index', [
            'courses' => $courses,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $category
        ]);
    }
    
    public function courseDetails($id)
    {
        $course = $this->courseModel->getById($id);
        
        if (!$course || $course['status'] !== 'published') {
            flash('error', 'Course not found');
            $this->redirect('/courses');
        }
        
        $this->view('courses.details', ['course' => $course]);
    }
}
