<?php

namespace App\Models;

use App\Core\Model;

class Lesson extends Model
{
    protected $table = 'lessons';
    
    public function getByCourse($courseId)
    {
        return $this->where(['course_id' => $courseId], 'order_index ASC, created_at ASC');
    }
    
    public function createLesson($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    public function updateLesson($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }
    
    public function getLessonWithProgress($lessonId, $userId)
    {
        $sql = "SELECT l.*, 
                       p.id as progress_id,
                       p.status as progress_status,
                       p.completed_at,
                       p.time_spent
                FROM {$this->table} l
                LEFT JOIN lesson_progress p ON l.id = p.lesson_id AND p.user_id = ?
                WHERE l.id = ?";
        
        return $this->fetchOne($sql, [$userId, $lessonId]);
    }
    
    public function getCourseLessonsWithProgress($courseId, $userId)
    {
        $sql = "SELECT l.*, 
                       p.id as progress_id,
                       p.status as progress_status,
                       p.completed_at,
                       p.time_spent
                FROM {$this->table} l
                LEFT JOIN lesson_progress p ON l.id = p.lesson_id AND p.user_id = ?
                WHERE l.course_id = ?
                ORDER BY l.order_index ASC, l.created_at ASC";
        
        return $this->fetchAll($sql, [$userId, $courseId]);
    }
    
    public function countByCourse($courseId)
    {
        return $this->count(['course_id' => $courseId]);
    }
}
