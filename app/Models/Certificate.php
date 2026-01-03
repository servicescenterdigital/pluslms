<?php

namespace App\Models;

use App\Core\Model;

class Certificate extends Model
{
    protected $table = 'certificates';
    
    public function generate($userId, $courseId)
    {
        $existing = $this->first([
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
        
        if ($existing) {
            return $existing;
        }
        
        $certificateNumber = $this->generateCertificateNumber();
        
        $id = $this->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'certificate_number' => $certificateNumber,
            'issued_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->find($id);
    }
    
    private function generateCertificateNumber()
    {
        return 'SD-' . date('Y') . '-' . strtoupper(substr(uniqid(), -8));
    }
    
    public function getUserCertificates($userId)
    {
        $sql = "SELECT cert.*, c.title as course_title, c.description,
                       u.name as instructor_name
                FROM {$this->table} cert
                INNER JOIN courses c ON cert.course_id = c.id
                LEFT JOIN users u ON c.instructor_id = u.id
                WHERE cert.user_id = ?
                ORDER BY cert.issued_at DESC";
        
        return $this->fetchAll($sql, [$userId]);
    }
    
    public function getCourseCertificates($courseId)
    {
        $sql = "SELECT cert.*, u.name as user_name, u.email as user_email
                FROM {$this->table} cert
                INNER JOIN users u ON cert.user_id = u.id
                WHERE cert.course_id = ?
                ORDER BY cert.issued_at DESC";
        
        return $this->fetchAll($sql, [$courseId]);
    }
    
    public function verify($certificateNumber)
    {
        $sql = "SELECT cert.*, u.name as user_name, u.email as user_email,
                       c.title as course_title, c.duration
                FROM {$this->table} cert
                INNER JOIN users u ON cert.user_id = u.id
                INNER JOIN courses c ON cert.course_id = c.id
                WHERE cert.certificate_number = ?";
        
        return $this->fetchOne($sql, [$certificateNumber]);
    }
    
    public function shouldIssueCertificate($userId, $courseId)
    {
        $progressModel = new LessonProgress();
        $progress = $progressModel->getCourseProgress($userId, $courseId);
        
        if ($progress['total_lessons'] == 0) {
            return false;
        }
        
        $completionRate = ($progress['completed_lessons'] / $progress['total_lessons']) * 100;
        
        return $completionRate >= 100;
    }
}
