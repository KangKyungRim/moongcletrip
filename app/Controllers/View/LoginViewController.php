<?php

namespace App\Controllers\View;

use App\Models\Token;
use App\Models\User;

class LoginViewController
{
    public static function loginEmail()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('login-email', ['data' => $data]);
    }

    public static function logout()
    {
        $deviceType = getDeviceType();

        if (empty($_SESSION['user_is_guest']) && !empty($_SESSION['device_token'])) {
            $token = Token::where('device_token', $_SESSION['device_token'])->first();
            $token->user_idx = null;
            $token->save();

            $guestUser = User::find($token->guest_idx);

            $_SESSION['user_idx'] = $guestUser->user_idx;
            $_SESSION['user_customer_key'] = $guestUser->user_customer_key;
            $_SESSION['user_is_guest'] = true;
        }

        if (empty($_SESSION['user_is_guest'])) {
            session_unset();
            session_destroy();
        }

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('logout', ['data' => $data]);
    }

    public static function loginFail()
	{
		$deviceType = getDeviceType();

        if (empty($_SESSION['user_is_guest']) && !empty($_SESSION['device_token'])) {
            $token = Token::where('device_token', $_SESSION['device_token'])->first();
            $token->user_idx = null;
            $token->save();

            $guestUser = User::find($token->guest_idx);

            $_SESSION['user_idx'] = $guestUser->user_idx;
            $_SESSION['user_customer_key'] = $guestUser->user_customer_key;
            $_SESSION['user_is_guest'] = true;
        }

        if (empty($_SESSION['user_is_guest'])) {
            session_unset();
            session_destroy();
        }

        $data = array(
            'deviceType' => $deviceType,
        );

		self::render('login-fail', ['data' => $data]);
	}

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
