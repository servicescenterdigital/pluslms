<?php

namespace App\Models;

use App\Core\Model;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';
    
    public function markAsStarted($userId, $lessonId)
    {
        $existing = $this->first([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ]);
        
        if ($existing) {
            return $existing['id'];
        }
        
        return $this->create([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
            'status' => 'in_progress',
            'started_at' => date('Y-m-d H:i:s'),
            'time_spent' => 0
        ]);
    }
    
    public function markAsCompleted($userId, $lessonId)
    {
        $progress = $this->first([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ]);
        
        if ($progress) {
            return $this->update($progress['id'], [
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        return $this->create([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
            'status' => 'completed',
            'started_at' => date('Y-m-d H:i:s'),
            'completed_at' => date('Y-m-d H:i:s'),
            'time_spent' => 0
        ]);
    }
    
    public function updateTimeSpent($userId, $lessonId, $timeSpent)
    {
        $progress = $this->first([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ]);
        
        if ($progress) {
            return $this->update($progress['id'], [
                'time_spent' => $progress['time_spent'] + $timeSpent
            ]);
        }
        
        return false;
    }
    
    public function getCourseProgress($userId, $courseId)
    {
        $sql = "SELECT 
                    COUNT(DISTINCT l.id) as total_lessons,
                    COUNT(DISTINCT lp.id) as completed_lessons,
                    SUM(lp.time_spent) as total_time_spent
                FROM lessons l
                LEFT JOIN {$this->table} lp ON l.id = lp.lesson_id AND lp.user_id = ? AND lp.status = 'completed'
                WHERE l.course_id = ?";
        
        return $this->fetchOne($sql, [$userId, $courseId]);
    }
    
    public function getUserProgress($userId)
    {
        $sql = "SELECT c.id as course_id, c.title,
                       COUNT(DISTINCT l.id) as total_lessons,
                       COUNT(DISTINCT lp.id) as completed_lessons,
                       ROUND((COUNT(DISTINCT lp.id) / COUNT(DISTINCT l.id)) * 100, 2) as progress_percentage
                FROM enrollments e
                INNER JOIN courses c ON e.course_id = c.id
                LEFT JOIN lessons l ON c.id = l.course_id
                LEFT JOIN {$this->table} lp ON l.id = lp.lesson_id AND lp.user_id = e.user_id AND lp.status = 'completed'
                WHERE e.user_id = ?
                GROUP BY c.id
                ORDER BY progress_percentage DESC";
        
        return $this->fetchAll($sql, [$userId]);
    }
}
