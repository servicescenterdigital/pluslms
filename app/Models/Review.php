<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected $table = 'reviews';
    
    public function getCourseReviews($courseId)
    {
        $sql = "SELECT r.*, u.name as user_name, u.profile_picture
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.id
                WHERE r.course_id = ?
                ORDER BY r.created_at DESC";
        
        return $this->fetchAll($sql, [$courseId]);
    }
    
    public function addReview($data)
    {
        $existing = $this->first([
            'user_id' => $data['user_id'],
            'course_id' => $data['course_id']
        ]);
        
        if ($existing) {
            return $this->update($existing['id'], [
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    public function getAverageRating($courseId)
    {
        $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews
                FROM {$this->table}
                WHERE course_id = ?";
        
        return $this->fetchOne($sql, [$courseId]);
    }
}
