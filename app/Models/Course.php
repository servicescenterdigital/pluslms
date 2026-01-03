<?php

namespace App\Models;

use App\Core\Model;

class Course extends Model
{
    protected $table = 'courses';
    
    public function getAll($status = null)
    {
        $sql = "SELECT c.*, u.name as instructor_name, u.email as instructor_email,
                       COUNT(DISTINCT e.id) as enrollment_count,
                       AVG(r.rating) as average_rating
                FROM {$this->table} c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN reviews r ON c.id = r.course_id
                WHERE 1=1";
        
        $params = [];
        if ($status) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }
        
        $sql .= " GROUP BY c.id ORDER BY c.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    public function getById($id)
    {
        $sql = "SELECT c.*, u.name as instructor_name, u.email as instructor_email,
                       u.bio as instructor_bio,
                       COUNT(DISTINCT e.id) as enrollment_count,
                       AVG(r.rating) as average_rating,
                       COUNT(DISTINCT r.id) as review_count
                FROM {$this->table} c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN reviews r ON c.id = r.course_id
                WHERE c.id = ?
                GROUP BY c.id";
        
        return $this->fetchOne($sql, [$id]);
    }
    
    public function getByInstructor($instructorId, $status = null)
    {
        $sql = "SELECT c.*, COUNT(DISTINCT e.id) as enrollment_count,
                       AVG(r.rating) as average_rating
                FROM {$this->table} c
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN reviews r ON c.id = r.course_id
                WHERE c.instructor_id = ?";
        
        $params = [$instructorId];
        if ($status) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }
        
        $sql .= " GROUP BY c.id ORDER BY c.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    public function createCourse($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'pending';
        return $this->create($data);
    }
    
    public function updateCourse($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }
    
    public function approveCourse($id)
    {
        return $this->update($id, [
            'status' => 'published',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function rejectCourse($id)
    {
        return $this->update($id, [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getPublishedCourses($limit = null)
    {
        return $this->getAll('published');
    }
    
    public function search($query, $category = null)
    {
        $sql = "SELECT c.*, u.name as instructor_name,
                       COUNT(DISTINCT e.id) as enrollment_count,
                       AVG(r.rating) as average_rating
                FROM {$this->table} c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN reviews r ON c.id = r.course_id
                WHERE c.status = 'published'
                AND (c.title LIKE ? OR c.description LIKE ?)";
        
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($category) {
            $sql .= " AND c.category = ?";
            $params[] = $category;
        }
        
        $sql .= " GROUP BY c.id ORDER BY c.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    public function getCategories()
    {
        $sql = "SELECT DISTINCT category FROM {$this->table} 
                WHERE status = 'published' AND category IS NOT NULL AND category != ''
                ORDER BY category";
        return $this->fetchAll($sql);
    }
    
    public function getStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_courses,
                    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_courses,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_courses,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_courses
                FROM {$this->table}";
        return $this->fetchOne($sql);
    }
    
    public function getRecommendations($userId, $limit = 6)
    {
        $sql = "SELECT c.*, u.name as instructor_name,
                       COUNT(DISTINCT e.id) as enrollment_count,
                       AVG(r.rating) as average_rating
                FROM {$this->table} c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN reviews r ON c.id = r.course_id
                WHERE c.status = 'published'
                AND c.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?)
                AND c.category IN (
                    SELECT DISTINCT c2.category 
                    FROM courses c2
                    INNER JOIN enrollments e2 ON c2.id = e2.course_id
                    WHERE e2.user_id = ?
                    LIMIT 3
                )
                GROUP BY c.id
                ORDER BY enrollment_count DESC, average_rating DESC
                LIMIT ?";
        
        return $this->fetchAll($sql, [$userId, $userId, $limit]);
    }
}
