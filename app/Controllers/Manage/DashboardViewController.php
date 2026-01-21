<?php

namespace App\Controllers\Manage;

use App\Helpers\PartnerHelper;

class DashboardViewController
{
    public static function dashboard()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/dashboard', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
