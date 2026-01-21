<?php

namespace App\Controllers\Manage;

use App\Helpers\PartnerHelper;


/**
 * 파트너 > 앱 관리 > 배너 관리
 */
class BannerViewController
{

    /**
     * 배너 목록
     */
    public static function banners()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/banners', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}