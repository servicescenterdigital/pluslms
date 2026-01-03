<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isLoggedIn()) {
            flash('error', 'Please login to continue');
            redirect('/login');
        }
    }
}
