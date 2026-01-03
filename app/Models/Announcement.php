<?php

namespace App\Models;

use App\Core\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    
    public function createAnnouncement($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    public function getCourseAnnouncements($courseId)
    {
        $sql = "SELECT a.*, u.name as instructor_name
                FROM {$this->table} a
                INNER JOIN users u ON a.instructor_id = u.id
                WHERE a.course_id = ?
                ORDER BY a.created_at DESC";
        
        return $this->fetchAll($sql, [$courseId]);
    }
    
    public function getInstructorAnnouncements($instructorId)
    {
        $sql = "SELECT a.*, c.title as course_title
                FROM {$this->table} a
                INNER JOIN courses c ON a.course_id = c.id
                WHERE a.instructor_id = ?
                ORDER BY a.created_at DESC";
        
        return $this->fetchAll($sql, [$instructorId]);
    }
}
