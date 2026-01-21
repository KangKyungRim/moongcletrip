<?php

namespace App\Controllers\Partner;

use App\Helpers\MiddleHelper;

use App\Models\Partner;

class CommonViewController
{
    public static function page500()
    {
        $user = MiddleHelper::checkPartnerLoginCookie();

        if (!$user) {
            header('Location: /partner/login');
            exit;
        } else {
            if ($user->partner_user_level < 4) {
                header('Location: /partner/login');
                exit;
            }
        }

        self::render('partner/500', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
