<?php

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $default;
        
        if ($value === 'true') return true;
        if ($value === 'false') return false;
        if ($value === 'null') return null;
        
        return $value;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null)
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);
        
        $config = require __DIR__ . '/../../config/' . $file . '.php';
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }
        
        return $config;
    }
}

if (!function_exists('url')) {
    function url($path = '')
    {
        $baseUrl = rtrim(config('app.url', 'http://localhost:3000'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path)
    {
        return url('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect($path)
    {
        header("Location: " . url($path));
        exit;
    }
}

if (!function_exists('session')) {
    function session($key = null, $value = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($key === null) {
            return $_SESSION;
        }
        
        if ($value === null) {
            return $_SESSION[$key] ?? null;
        }
        
        $_SESSION[$key] = $value;
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return session('user');
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        return session('user') !== null;
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role)
    {
        $user = auth();
        return $user && $user['role'] === $role;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (!session('csrf_token')) {
            session('csrf_token', bin2hex(random_bytes(32)));
        }
        return session('csrf_token');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('method_field')) {
    function method_field($method)
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

if (!function_exists('old')) {
    function old($key, $default = '')
    {
        return session('old')[$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    function flash($key, $message = null)
    {
        if ($message === null) {
            $value = session('flash')[$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        
        $_SESSION['flash'][$key] = $message;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, $directory = 'uploads')
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        $allowedTypes = config('app.upload.allowed_types');
        $maxSize = config('app.upload.max_size');
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = __DIR__ . '/../../public/' . $directory . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $directory . '/' . $filename;
        }
        
        return false;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $timestamp);
        }
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data)
    {
        if (is_array($data)) {
            return array_map('sanitize', $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}
