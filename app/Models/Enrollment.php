<?php

namespace App\Models;

use App\Core\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';
    
    public function enroll($userId, $courseId, $paymentStatus = 'free')
    {
        $existing = $this->first([
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
        
        if ($existing) {
            return false;
        }
        
        return $this->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrolled_at' => date('Y-m-d H:i:s'),
            'payment_status' => $paymentStatus,
            'status' => 'active'
        ]);
    }
    
    public function isEnrolled($userId, $courseId)
    {
        return $this->first([
            'user_id' => $userId,
            'course_id' => $courseId
        ]) !== false;
    }
    
    public function getUserEnrollments($userId)
    {
        $sql = "SELECT e.*, c.title, c.description, c.thumbnail, c.duration,
                       u.name as instructor_name,
                       COUNT(DISTINCT l.id) as total_lessons,
                       COUNT(DISTINCT lp.id) as completed_lessons,
                       ROUND((COUNT(DISTINCT lp.id) / COUNT(DISTINCT l.id)) * 100, 2) as progress_percentage
                FROM {$this->table} e
                INNER JOIN courses c ON e.course_id = c.id
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN lessons l ON c.id = l.course_id
                LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = e.user_id AND lp.status = 'completed'
                WHERE e.user_id = ?
                GROUP BY e.id, c.id
                ORDER BY e.enrolled_at DESC";
        
        return $this->fetchAll($sql, [$userId]);
    }
    
    public function getCourseEnrollments($courseId)
    {
        $sql = "SELECT e.*, u.name, u.email, u.profile_picture,
                       COUNT(DISTINCT lp.id) as completed_lessons,
                       COUNT(DISTINCT l.id) as total_lessons
                FROM {$this->table} e
                INNER JOIN users u ON e.user_id = u.id
                LEFT JOIN lessons l ON e.course_id = l.course_id
                LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = e.user_id AND lp.status = 'completed'
                WHERE e.course_id = ?
                GROUP BY e.id
                ORDER BY e.enrolled_at DESC";
        
        return $this->fetchAll($sql, [$courseId]);
    }
    
    public function getEnrollmentStats($courseId = null)
    {
        if ($courseId) {
            $sql = "SELECT COUNT(*) as total_enrollments,
                           SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_enrollments
                    FROM {$this->table}
                    WHERE course_id = ?";
            return $this->fetchOne($sql, [$courseId]);
        }
        
        $sql = "SELECT COUNT(*) as total_enrollments,
                       SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_enrollments
                FROM {$this->table}";
        return $this->fetchOne($sql);
    }
    
    public function getRevenueStats()
    {
        $sql = "SELECT SUM(c.price) as total_revenue,
                       COUNT(e.id) as paid_enrollments,
                       AVG(c.price) as average_price
                FROM {$this->table} e
                INNER JOIN courses c ON e.course_id = c.id
                WHERE e.payment_status = 'paid'";
        
        return $this->fetchOne($sql);
    }
}
