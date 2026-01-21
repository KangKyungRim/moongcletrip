<?php

namespace App\Controllers\View;

class TravelCartViewController
{
    public static function main()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('travel-cart', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
