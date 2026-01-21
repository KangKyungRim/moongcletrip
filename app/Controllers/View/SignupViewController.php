<?php

namespace App\Controllers\View;

class SignupViewController
{
    public static function signupAgree()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('signup-agree', ['data' => $data]);
    }

    public static function signupEmail()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('signup-email', ['data' => $data]);
    }

    public static function signupComplete()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('signup-complete', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
