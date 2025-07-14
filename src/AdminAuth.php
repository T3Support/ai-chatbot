<?php
namespace AIChatBot;

class AdminAuth
{
    public static function check()
    {
        session_start();
        if (empty($_SESSION['is_admin'])) {
            header('Location: login.php');
            exit;
        }
    }

    public static function login($password)
    {
        $env = parse_ini_file(dirname(__DIR__).'/.env');
        $admin_pw = $env['ADMIN_PASSWORD'] ?? '';
        if ($password && $password === $admin_pw) {
            $_SESSION['is_admin'] = true;
            return true;
        }
        return false;
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
    }
}
