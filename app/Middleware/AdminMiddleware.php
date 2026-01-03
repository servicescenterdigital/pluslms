<?php

namespace App\Middleware;

class AdminMiddleware
{
    public function handle()
    {
        if (!isLoggedIn() || !hasRole('admin')) {
            flash('error', 'Access denied. Admin privileges required.');
            redirect('/');
        }
    }
}
