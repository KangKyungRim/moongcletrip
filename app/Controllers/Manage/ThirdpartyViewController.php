<?php

namespace App\Controllers\Manage;

use App\Models\Partner;

use App\Helpers\PartnerHelper;

class ThirdpartyViewController
{
    public static function config()
    {
        $basicData = PartnerHelper::adminDefaultProcess();

        $appendData = [];
        $appendData['partner'] = Partner::find($basicData['selectedPartnerIdx']);

        $data = array_merge($basicData, $appendData);
        self::render('manage/thirdparty-config', ['data' => $data]);
    }

    public static function onda()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/thirdparty-onda', ['data' => $data]);
    }

    public static function safeCancel()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-safe', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
