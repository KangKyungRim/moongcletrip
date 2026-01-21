<?php

namespace App\Controllers\Manage;

use App\Helpers\MiddleHelper;

class LoginViewController
{
    public static function login()
    {
        $user = MiddleHelper::checkPartnerLoginCookie();

        if ($user) {
            if ($user->partner_user_level >= 4) {
                header('Location: /manage/dashboard');
                exit;
            }
        }

        self::render('manage/login');
    }

    public static function register()
    {
        $user = MiddleHelper::checkPartnerLoginCookie();

        if ($user) {
            if ($user->partner_user_level >= 4) {
                header('Location: /manage/dashboard');
                exit;
            }
        }

        self::render('manage/register');
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
