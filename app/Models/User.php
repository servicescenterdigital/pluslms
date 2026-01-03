<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    
    public function findByEmail($email)
    {
        return $this->first(['email' => $email]);
    }
    
    public function register($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function updateProfile($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }
    
    public function getInstructors($status = 'approved')
    {
        return $this->where(['role' => 'instructor', 'instructor_status' => $status]);
    }
    
    public function getStudents()
    {
        return $this->where(['role' => 'student']);
    }
    
    public function approveInstructor($id)
    {
        return $this->update($id, [
            'instructor_status' => 'approved',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function rejectInstructor($id)
    {
        return $this->update($id, [
            'instructor_status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function updateLastLogin($id)
    {
        return $this->update($id, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) as total_students,
                    SUM(CASE WHEN role = 'instructor' THEN 1 ELSE 0 END) as total_instructors,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as total_admins
                FROM {$this->table}";
        return $this->fetchOne($sql);
    }
}
