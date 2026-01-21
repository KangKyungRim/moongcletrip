<?php

namespace App\Controllers\Partner;

use App\Helpers\MiddleHelper;

class LoginViewController
{
    public static function login()
    {
        $user = MiddleHelper::checkPartnerLoginCookie();

        if ($user) {
            if ($user->partner_user_level >= 4) {
                header('Location: /partner/dashboard');
                exit;
            }
        }

        self::render('partner/login');
    }

    public static function register()
    {
        $user = MiddleHelper::checkPartnerLoginCookie();

        if ($user) {
            if ($user->partner_user_level >= 4) {
                header('Location: /partner/dashboard');
                exit;
            }
        }

        self::render('partner/register');
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
