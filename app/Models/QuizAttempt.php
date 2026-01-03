<?php

namespace App\Models;

use App\Core\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';
    
    public function startAttempt($userId, $quizId)
    {
        return $this->create([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'started_at' => date('Y-m-d H:i:s'),
            'status' => 'in_progress'
        ]);
    }
    
    public function completeAttempt($attemptId, $score, $totalQuestions, $answers)
    {
        return $this->update($attemptId, [
            'score' => $score,
            'total_questions' => $totalQuestions,
            'answers' => json_encode($answers),
            'completed_at' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ]);
    }
    
    public function getUserAttempts($userId, $quizId = null)
    {
        $sql = "SELECT qa.*, q.title as quiz_title, q.passing_score,
                       c.title as course_title, l.title as lesson_title
                FROM {$this->table} qa
                INNER JOIN quizzes q ON qa.quiz_id = q.id
                LEFT JOIN courses c ON q.course_id = c.id
                LEFT JOIN lessons l ON q.lesson_id = l.id
                WHERE qa.user_id = ?";
        
        $params = [$userId];
        
        if ($quizId) {
            $sql .= " AND qa.quiz_id = ?";
            $params[] = $quizId;
        }
        
        $sql .= " ORDER BY qa.started_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    public function getQuizAttempts($quizId)
    {
        $sql = "SELECT qa.*, u.name as user_name, u.email as user_email
                FROM {$this->table} qa
                INNER JOIN users u ON qa.user_id = u.id
                WHERE qa.quiz_id = ?
                ORDER BY qa.started_at DESC";
        
        return $this->fetchAll($sql, [$quizId]);
    }
    
    public function getBestScore($userId, $quizId)
    {
        $sql = "SELECT MAX(score) as best_score
                FROM {$this->table}
                WHERE user_id = ? AND quiz_id = ? AND status = 'completed'";
        
        $result = $this->fetchOne($sql, [$userId, $quizId]);
        return $result['best_score'] ?? 0;
    }
    
    public function getAttemptCount($userId, $quizId)
    {
        return $this->count([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'status' => 'completed'
        ]);
    }
}
