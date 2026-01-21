<?php

namespace App\Controllers\Partner;

use App\Models\Partner;

use App\Helpers\PartnerHelper;

class ThirdpartyViewController
{
    public static function config()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        $appendData = [];
        $appendData['partner'] = Partner::find($basicData['selectedPartnerIdx']);

        $data = array_merge($basicData, $appendData);
        self::render('partner/thirdparty-config', ['data' => $data]);
    }

    public static function onda()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/thirdparty-onda', ['data' => $data]);
    }

    public static function safeCancel()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-safe', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
