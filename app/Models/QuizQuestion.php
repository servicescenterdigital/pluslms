<?php

namespace App\Models;

use App\Core\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    
    public function getByQuiz($quizId)
    {
        return $this->where(['quiz_id' => $quizId], 'order_index ASC');
    }
    
    public function createQuestion($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    public function updateQuestion($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }
}
