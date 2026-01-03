<?php

namespace App\Core;

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: {$view}");
        }
    }
    
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($path)
    {
        header("Location: " . url($path));
        exit;
    }
    
    protected function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header("Location: {$referer}");
        exit;
    }
    
    protected function error($code = 404)
    {
        http_response_code($code);
        $this->view("shared.error", ['code' => $code]);
    }
}
