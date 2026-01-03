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

class ApiController extends Controller
{
    private $courseModel;
    private $enrollmentModel;
    private $lessonModel;
    private $progressModel;
    private $quizModel;
    private $attemptModel;
    private $certificateModel;
    
    public function __construct()
    {
        header('Content-Type: application/json');
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->lessonModel = new Lesson();
        $this->progressModel = new LessonProgress();
        $this->quizModel = new Quiz();
        $this->attemptModel = new QuizAttempt();
        $this->certificateModel = new Certificate();
    }
    
    public function courses()
    {
        $courses = $this->courseModel->getPublishedCourses();
        $this->json(['success' => true, 'data' => $courses]);
    }
    
    public function courseDetails($id)
    {
        $course = $this->courseModel->getById($id);
        
        if (!$course || $course['status'] !== 'published') {
            $this->json(['success' => false, 'message' => 'Course not found'], 404);
        }
        
        $lessons = $this->lessonModel->getByCourse($id);
        $quizzes = $this->quizModel->getByCourse($id);
        
        $course['lessons'] = $lessons;
        $course['quizzes'] = $quizzes;
        
        $this->json(['success' => true, 'data' => $course]);
    }
    
    public function enroll()
    {
        if (!isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Authentication required'], 401);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $courseId = $input['course_id'] ?? null;
        
        if (!$courseId) {
            $this->json(['success' => false, 'message' => 'Course ID required'], 400);
        }
        
        $user = auth();
        $course = $this->courseModel->find($courseId);
        
        if (!$course || $course['status'] !== 'published') {
            $this->json(['success' => false, 'message' => 'Course not found'], 404);
        }
        
        if ($this->enrollmentModel->isEnrolled($user['id'], $courseId)) {
            $this->json(['success' => false, 'message' => 'Already enrolled'], 400);
        }
        
        $paymentStatus = $course['price'] > 0 ? 'paid' : 'free';
        
        if ($this->enrollmentModel->enroll($user['id'], $courseId, $paymentStatus)) {
            $this->json(['success' => true, 'message' => 'Enrollment successful']);
        } else {
            $this->json(['success' => false, 'message' => 'Enrollment failed'], 500);
        }
    }
    
    public function myEnrollments()
    {
        if (!isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Authentication required'], 401);
        }
        
        $user = auth();
        $enrollments = $this->enrollmentModel->getUserEnrollments($user['id']);
        
        $this->json(['success' => true, 'data' => $enrollments]);
    }
    
    public function lessonProgress()
    {
        if (!isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Authentication required'], 401);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $lessonId = $input['lesson_id'] ?? null;
        $action = $input['action'] ?? 'start';
        
        if (!$lessonId) {
            $this->json(['success' => false, 'message' => 'Lesson ID required'], 400);
        }
        
        $user = auth();
        
        if ($action === 'complete') {
            $this->progressModel->markAsCompleted($user['id'], $lessonId);
            
            $lesson = $this->lessonModel->find($lessonId);
            if ($this->certificateModel->shouldIssueCertificate($user['id'], $lesson['course_id'])) {
                $certificate = $this->certificateModel->generate($user['id'], $lesson['course_id']);
                $this->json(['success' => true, 'message' => 'Lesson completed', 'certificate' => $certificate]);
            }
        } else {
            $this->progressModel->markAsStarted($user['id'], $lessonId);
        }
        
        $this->json(['success' => true, 'message' => 'Progress updated']);
    }
    
    public function submitQuiz($quizId)
    {
        if (!isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Authentication required'], 401);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $answers = $input['answers'] ?? [];
        
        $user = auth();
        $quiz = $this->quizModel->getQuizWithQuestions($quizId);
        
        if (!$quiz) {
            $this->json(['success' => false, 'message' => 'Quiz not found'], 404);
        }
        
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
        
        $passed = $scorePercentage >= $quiz['passing_score'];
        
        $this->json([
            'success' => true,
            'score' => round($scorePercentage, 2),
            'passed' => $passed,
            'passing_score' => $quiz['passing_score']
        ]);
    }
    
    public function verifyCertificate($certificateNumber)
    {
        $certificate = $this->certificateModel->verify($certificateNumber);
        
        if ($certificate) {
            $this->json(['success' => true, 'data' => $certificate, 'valid' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Certificate not found', 'valid' => false], 404);
        }
    }
    
    public function recommendations()
    {
        if (!isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Authentication required'], 401);
        }
        
        $user = auth();
        $recommendations = $this->courseModel->getRecommendations($user['id']);
        
        $this->json(['success' => true, 'data' => $recommendations]);
    }
}
