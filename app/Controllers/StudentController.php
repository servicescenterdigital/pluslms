<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use App\Models\Review;

class StudentController extends Controller
{
    private $courseModel;
    private $enrollmentModel;
    private $lessonModel;
    private $progressModel;
    private $quizModel;
    private $attemptModel;
    private $certificateModel;
    private $reviewModel;
    
    public function __construct()
    {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->lessonModel = new Lesson();
        $this->progressModel = new LessonProgress();
        $this->quizModel = new Quiz();
        $this->attemptModel = new QuizAttempt();
        $this->certificateModel = new Certificate();
        $this->reviewModel = new Review();
    }
    
    public function dashboard()
    {
        $user = auth();
        $enrollments = $this->enrollmentModel->getUserEnrollments($user['id']);
        $recommendations = $this->courseModel->getRecommendations($user['id']);
        $certificates = $this->certificateModel->getUserCertificates($user['id']);
        
        $this->view('student.dashboard', [
            'enrollments' => $enrollments,
            'recommendations' => $recommendations,
            'certificates' => $certificates
        ]);
    }
    
    public function myCourses()
    {
        $user = auth();
        $enrollments = $this->enrollmentModel->getUserEnrollments($user['id']);
        
        $this->view('student.courses', [
            'enrollments' => $enrollments
        ]);
    }
    
    public function enroll($courseId)
    {
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['status'] !== 'published') {
            flash('error', 'Course not found');
            $this->redirect('/courses');
        }
        
        if ($this->enrollmentModel->isEnrolled($user['id'], $courseId)) {
            flash('info', 'You are already enrolled in this course');
            $this->redirect('/student/course/' . $courseId);
        }
        
        $paymentStatus = $course['price'] > 0 ? 'paid' : 'free';
        
        if ($this->enrollmentModel->enroll($user['id'], $courseId, $paymentStatus)) {
            flash('success', 'Successfully enrolled in ' . $course['title']);
            $this->redirect('/student/course/' . $courseId);
        } else {
            flash('error', 'Enrollment failed. Please try again.');
            $this->redirect('/courses/' . $courseId);
        }
    }
    
    public function viewCourse($courseId)
    {
        $user = auth();
        
        if (!$this->enrollmentModel->isEnrolled($user['id'], $courseId)) {
            flash('error', 'Please enroll in this course first');
            $this->redirect('/courses/' . $courseId);
        }
        
        $course = $this->courseModel->getById($courseId);
        $lessons = $this->lessonModel->getCourseLessonsWithProgress($courseId, $user['id']);
        $progress = $this->progressModel->getCourseProgress($user['id'], $courseId);
        $quizzes = $this->quizModel->getByCourse($courseId);
        $announcements = (new \App\Models\Announcement())->getCourseAnnouncements($courseId);
        
        $progressPercentage = $progress['total_lessons'] > 0 
            ? round(($progress['completed_lessons'] / $progress['total_lessons']) * 100, 2)
            : 0;
        
        $canGetCertificate = $progressPercentage >= 100;
        $certificate = $this->certificateModel->first(['user_id' => $user['id'], 'course_id' => $courseId]);
        
        $this->view('student.course-view', [
            'course' => $course,
            'lessons' => $lessons,
            'progress' => $progress,
            'progressPercentage' => $progressPercentage,
            'quizzes' => $quizzes,
            'announcements' => $announcements,
            'canGetCertificate' => $canGetCertificate,
            'certificate' => $certificate
        ]);
    }
    
    public function viewLesson($lessonId)
    {
        $user = auth();
        $lesson = $this->lessonModel->getLessonWithProgress($lessonId, $user['id']);
        
        if (!$lesson) {
            flash('error', 'Lesson not found');
            $this->redirect('/student/dashboard');
        }
        
        if (!$this->enrollmentModel->isEnrolled($user['id'], $lesson['course_id'])) {
            flash('error', 'Please enroll in this course first');
            $this->redirect('/courses/' . $lesson['course_id']);
        }
        
        $this->progressModel->markAsStarted($user['id'], $lessonId);
        
        $allLessons = $this->lessonModel->getByCourse($lesson['course_id']);
        $currentIndex = array_search($lessonId, array_column($allLessons, 'id'));
        
        $nextLesson = $currentIndex !== false && isset($allLessons[$currentIndex + 1]) 
            ? $allLessons[$currentIndex + 1] 
            : null;
        $prevLesson = $currentIndex !== false && $currentIndex > 0 
            ? $allLessons[$currentIndex - 1] 
            : null;
        
        $this->view('student.lesson-view', [
            'lesson' => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson
        ]);
    }
    
    public function completeLesson($lessonId)
    {
        $user = auth();
        $this->progressModel->markAsCompleted($user['id'], $lessonId);
        
        $lesson = $this->lessonModel->find($lessonId);
        
        if ($this->certificateModel->shouldIssueCertificate($user['id'], $lesson['course_id'])) {
            $this->certificateModel->generate($user['id'], $lesson['course_id']);
            flash('success', 'Congratulations! You have completed the course and earned a certificate!');
        } else {
            flash('success', 'Lesson marked as complete!');
        }
        
        $this->redirect('/student/course/' . $lesson['course_id']);
    }
    
    public function takeQuiz($quizId)
    {
        $user = auth();
        $quiz = $this->quizModel->getQuizWithQuestions($quizId);
        
        if (!$quiz) {
            flash('error', 'Quiz not found');
            $this->redirect('/student/dashboard');
        }
        
        $courseId = $quiz['course_id'] ?? $quiz['lesson_id'];
        if (!$this->enrollmentModel->isEnrolled($user['id'], $quiz['course_id'])) {
            flash('error', 'Please enroll in this course first');
            $this->redirect('/courses/' . $quiz['course_id']);
        }
        
        $attemptCount = $this->attemptModel->getAttemptCount($user['id'], $quizId);
        if ($quiz['max_attempts'] > 0 && $attemptCount >= $quiz['max_attempts']) {
            flash('error', 'You have reached the maximum number of attempts for this quiz');
            $this->redirect('/student/course/' . $quiz['course_id']);
        }
        
        $this->view('student.quiz-take', [
            'quiz' => $quiz,
            'attemptCount' => $attemptCount
        ]);
    }
    
    public function submitQuiz($quizId)
    {
        $user = auth();
        $quiz = $this->quizModel->getQuizWithQuestions($quizId);
        
        if (!$quiz) {
            $this->json(['error' => 'Quiz not found'], 404);
        }
        
        $answers = $_POST['answers'] ?? [];
        $score = 0;
        $totalPoints = 0;
        
        foreach ($quiz['questions'] as $question) {
            $totalPoints += $question['points'];
            $userAnswer = $answers[$question['id']] ?? '';
            
            if (strtolower(trim($userAnswer)) === strtolower(trim($question['correct_answer']))) {
                $score += $question['points'];
            }
        }
        
        $scorePercentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        
        $attemptId = $this->attemptModel->startAttempt($user['id'], $quizId);
        $this->attemptModel->completeAttempt($attemptId, $scorePercentage, count($quiz['questions']), $answers);
        
        flash('success', 'Quiz submitted! Your score: ' . round($scorePercentage, 2) . '%');
        $this->redirect('/student/course/' . $quiz['course_id']);
    }
    
    public function certificates()
    {
        $user = auth();
        $certificates = $this->certificateModel->getUserCertificates($user['id']);
        
        $this->view('student.certificates', [
            'certificates' => $certificates
        ]);
    }
    
    public function downloadCertificate($certificateId)
    {
        $user = auth();
        $certificate = $this->certificateModel->find($certificateId);
        
        if (!$certificate || $certificate['user_id'] != $user['id']) {
            flash('error', 'Certificate not found');
            $this->redirect('/student/certificates');
        }
        
        $course = $this->courseModel->find($certificate['course_id']);
        
        $this->view('student.certificate-print', [
            'certificate' => $certificate,
            'course' => $course,
            'user' => $user
        ]);
    }
    
    public function profile()
    {
        $user = auth();
        $this->view('student.profile', ['user' => $user]);
    }
    
    public function updateProfile()
    {
        $user = auth();
        $userModel = new \App\Models\User();
        
        $data = [
            'name' => sanitize($_POST['name'] ?? $user['name']),
            'bio' => sanitize($_POST['bio'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? '')
        ];
        
        if ($userModel->updateProfile($user['id'], $data)) {
            $updatedUser = $userModel->find($user['id']);
            session('user', $updatedUser);
            flash('success', 'Profile updated successfully');
        } else {
            flash('error', 'Failed to update profile');
        }
        
        $this->redirect('/student/profile');
    }
}
