<?php

namespace App\Middleware;

class InstructorMiddleware
{
    public function handle()
    {
        if (!isLoggedIn() || !in_array(auth()['role'], ['instructor', 'admin'])) {
            flash('error', 'Access denied. Instructor privileges required.');
            redirect('/');
        }
    }
}
