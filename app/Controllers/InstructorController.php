<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Enrollment;
use App\Models\Announcement;

class InstructorController extends Controller
{
    private $courseModel;
    private $lessonModel;
    private $quizModel;
    private $questionModel;
    private $enrollmentModel;
    private $announcementModel;
    
    public function __construct()
    {
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->quizModel = new Quiz();
        $this->questionModel = new QuizQuestion();
        $this->enrollmentModel = new Enrollment();
        $this->announcementModel = new Announcement();
    }
    
    public function dashboard()
    {
        $user = auth();
        
        if ($user['instructor_status'] !== 'approved') {
            $this->view('instructor.pending-approval');
            return;
        }
        
        $courses = $this->courseModel->getByInstructor($user['id']);
        $stats = [
            'total_courses' => count($courses),
            'published_courses' => count(array_filter($courses, fn($c) => $c['status'] === 'published')),
            'pending_courses' => count(array_filter($courses, fn($c) => $c['status'] === 'pending')),
            'total_students' => 0
        ];
        
        foreach ($courses as $course) {
            $stats['total_students'] += $course['enrollment_count'] ?? 0;
        }
        
        $this->view('instructor.dashboard', [
            'courses' => $courses,
            'stats' => $stats
        ]);
    }
    
    public function courses()
    {
        $user = auth();
        $courses = $this->courseModel->getByInstructor($user['id']);
        
        $this->view('instructor.courses', [
            'courses' => $courses
        ]);
    }
    
    public function createCourse()
    {
        $this->view('instructor.course-create');
    }
    
    public function storeCourse()
    {
        $user = auth();
        
        $data = [
            'instructor_id' => $user['id'],
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'category' => sanitize($_POST['category'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'duration' => sanitize($_POST['duration'] ?? ''),
            'level' => sanitize($_POST['level'] ?? 'beginner'),
            'prerequisites' => sanitize($_POST['prerequisites'] ?? ''),
            'learning_outcomes' => sanitize($_POST['learning_outcomes'] ?? '')
        ];
        
        if (empty($data['title']) || empty($data['description'])) {
            flash('error', 'Title and description are required');
            $this->redirect('/instructor/courses/create');
        }
        
        $courseId = $this->courseModel->createCourse($data);
        
        if ($courseId) {
            flash('success', 'Course created successfully. Add lessons to your course.');
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
        } else {
            flash('error', 'Failed to create course');
            $this->redirect('/instructor/courses/create');
        }
    }
    
    public function editCourse($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            flash('error', 'Course not found');
            $this->redirect('/instructor/courses');
        }
        
        $lessons = $this->lessonModel->getByCourse($courseId);
        $quizzes = $this->quizModel->getByCourse($courseId);
        $enrollments = $this->enrollmentModel->getCourseEnrollments($courseId);
        
        $this->view('instructor.course-edit', [
            'course' => $course,
            'lessons' => $lessons,
            'quizzes' => $quizzes,
            'enrollments' => $enrollments
        ]);
    }
    
    public function updateCourse($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            flash('error', 'Course not found');
            $this->redirect('/instructor/courses');
        }
        
        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'category' => sanitize($_POST['category'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'duration' => sanitize($_POST['duration'] ?? ''),
            'level' => sanitize($_POST['level'] ?? 'beginner'),
            'prerequisites' => sanitize($_POST['prerequisites'] ?? ''),
            'learning_outcomes' => sanitize($_POST['learning_outcomes'] ?? '')
        ];
        
        if ($this->courseModel->updateCourse($courseId, $data)) {
            flash('success', 'Course updated successfully');
        } else {
            flash('error', 'Failed to update course');
        }
        
        $this->redirect('/instructor/courses/' . $courseId . '/edit');
    }
    
    public function addLesson($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            $this->json(['error' => 'Unauthorized'], 403);
        }
        
        $data = [
            'course_id' => $courseId,
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'content_type' => sanitize($_POST['content_type'] ?? 'text'),
            'content_text' => $_POST['content_text'] ?? '',
            'content_url' => sanitize($_POST['content_url'] ?? ''),
            'duration' => intval($_POST['duration'] ?? 0),
            'order_index' => intval($_POST['order_index'] ?? 0),
            'is_free' => isset($_POST['is_free']) ? 1 : 0
        ];
        
        if (empty($data['title'])) {
            flash('error', 'Lesson title is required');
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
        }
        
        $lessonId = $this->lessonModel->createLesson($data);
        
        if ($lessonId) {
            flash('success', 'Lesson added successfully');
        } else {
            flash('error', 'Failed to add lesson');
        }
        
        $this->redirect('/instructor/courses/' . $courseId . '/edit');
    }
    
    public function updateLesson($lessonId)
    {
        $user = auth();
        $lesson = $this->lessonModel->find($lessonId);
        
        if (!$lesson) {
            $this->json(['error' => 'Lesson not found'], 404);
        }
        
        $course = $this->courseModel->find($lesson['course_id']);
        if (!$course || $course['instructor_id'] != $user['id']) {
            $this->json(['error' => 'Unauthorized'], 403);
        }
        
        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'content_type' => sanitize($_POST['content_type'] ?? 'text'),
            'content_text' => $_POST['content_text'] ?? '',
            'content_url' => sanitize($_POST['content_url'] ?? ''),
            'duration' => intval($_POST['duration'] ?? 0),
            'is_free' => isset($_POST['is_free']) ? 1 : 0
        ];
        
        if ($this->lessonModel->updateLesson($lessonId, $data)) {
            flash('success', 'Lesson updated successfully');
        } else {
            flash('error', 'Failed to update lesson');
        }
        
        $this->redirect('/instructor/courses/' . $lesson['course_id'] . '/edit');
    }
    
    public function deleteLesson($lessonId)
    {
        $user = auth();
        $lesson = $this->lessonModel->find($lessonId);
        
        if (!$lesson) {
            $this->json(['error' => 'Lesson not found'], 404);
        }
        
        $course = $this->courseModel->find($lesson['course_id']);
        if (!$course || $course['instructor_id'] != $user['id']) {
            $this->json(['error' => 'Unauthorized'], 403);
        }
        
        if ($this->lessonModel->delete($lessonId)) {
            flash('success', 'Lesson deleted successfully');
        } else {
            flash('error', 'Failed to delete lesson');
        }
        
        $this->redirect('/instructor/courses/' . $lesson['course_id'] . '/edit');
    }
    
    public function createQuiz($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            flash('error', 'Unauthorized');
            $this->redirect('/instructor/courses');
        }
        
        $data = [
            'course_id' => $courseId,
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'passing_score' => intval($_POST['passing_score'] ?? 70),
            'time_limit' => intval($_POST['time_limit'] ?? 0),
            'max_attempts' => intval($_POST['max_attempts'] ?? 0)
        ];
        
        if (empty($data['title'])) {
            flash('error', 'Quiz title is required');
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
        }
        
        $quizId = $this->quizModel->createQuiz($data);
        
        if ($quizId) {
            flash('success', 'Quiz created successfully. Now add questions.');
            $this->redirect('/instructor/quiz/' . $quizId . '/edit');
        } else {
            flash('error', 'Failed to create quiz');
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
        }
    }
    
    public function editQuiz($quizId)
    {
        $user = auth();
        $quiz = $this->quizModel->find($quizId);
        
        if (!$quiz) {
            flash('error', 'Quiz not found');
            $this->redirect('/instructor/courses');
        }
        
        $course = $this->courseModel->find($quiz['course_id']);
        if (!$course || $course['instructor_id'] != $user['id']) {
            flash('error', 'Unauthorized');
            $this->redirect('/instructor/courses');
        }
        
        $questions = $this->questionModel->getByQuiz($quizId);
        
        $this->view('instructor.quiz-edit', [
            'quiz' => $quiz,
            'questions' => $questions,
            'course' => $course
        ]);
    }
    
    public function addQuestion($quizId)
    {
        $user = auth();
        $quiz = $this->quizModel->find($quizId);
        
        if (!$quiz) {
            $this->json(['error' => 'Quiz not found'], 404);
        }
        
        $course = $this->courseModel->find($quiz['course_id']);
        if (!$course || $course['instructor_id'] != $user['id']) {
            $this->json(['error' => 'Unauthorized'], 403);
        }
        
        $options = isset($_POST['options']) ? json_encode($_POST['options']) : null;
        
        $data = [
            'quiz_id' => $quizId,
            'question_text' => sanitize($_POST['question_text'] ?? ''),
            'question_type' => sanitize($_POST['question_type'] ?? 'multiple_choice'),
            'options' => $options,
            'correct_answer' => sanitize($_POST['correct_answer'] ?? ''),
            'points' => intval($_POST['points'] ?? 1),
            'order_index' => intval($_POST['order_index'] ?? 0)
        ];
        
        if ($this->questionModel->createQuestion($data)) {
            flash('success', 'Question added successfully');
        } else {
            flash('error', 'Failed to add question');
        }
        
        $this->redirect('/instructor/quiz/' . $quizId . '/edit');
    }
    
    public function students($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            flash('error', 'Unauthorized');
            $this->redirect('/instructor/courses');
        }
        
        $enrollments = $this->enrollmentModel->getCourseEnrollments($courseId);
        
        $this->view('instructor.students', [
            'course' => $course,
            'enrollments' => $enrollments
        ]);
    }
    
    public function createAnnouncement($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            flash('error', 'Unauthorized');
            $this->redirect('/instructor/courses');
        }
        
        $data = [
            'course_id' => $courseId,
            'instructor_id' => $user['id'],
            'title' => sanitize($_POST['title'] ?? ''),
            'content' => sanitize($_POST['content'] ?? '')
        ];
        
        if (empty($data['title']) || empty($data['content'])) {
            flash('error', 'Title and content are required');
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
        }
        
        if ($this->announcementModel->createAnnouncement($data)) {
            flash('success', 'Announcement created successfully');
        } else {
            flash('error', 'Failed to create announcement');
        }
        
        $this->redirect('/instructor/courses/' . $courseId . '/edit');
    }
}
