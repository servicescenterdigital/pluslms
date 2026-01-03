<?php

namespace App\Models;

use App\Core\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';
    
    public function getByLesson($lessonId)
    {
        return $this->where(['lesson_id' => $lessonId]);
    }
    
    public function getByCourse($courseId)
    {
        return $this->where(['course_id' => $courseId]);
    }
    
    public function createQuiz($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    public function getQuizWithQuestions($quizId)
    {
        $quiz = $this->find($quizId);
        if ($quiz) {
            $questionModel = new QuizQuestion();
            $quiz['questions'] = $questionModel->getByQuiz($quizId);
        }
        return $quiz;
    }
}
