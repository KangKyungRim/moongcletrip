<?php

namespace App\Controllers\View;

class ArticleViewController
{
    public static function articleCity()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('/article-city', ['data' => $data]);
    }

    public static function articleStay()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('/article-stay', ['data' => $data]);
    }

    public static function articleExhibitions()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('/article-exhibitions', ['data' => $data]);
    }

    public static function articleFreeform()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType,
        );

        self::render('/article-freeform', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
