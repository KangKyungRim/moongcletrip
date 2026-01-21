<?php

namespace App\Controllers\Partner;

use App\Models\PartnerUser;
use App\Models\PartnerUserAssignment;

use App\Helpers\PartnerHelper;

use Database;

class UserViewController
{
    public static function partnerUserList()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $sql = "
            SELECT
                p.partner_name,
                p.partner_category,
                pu.*
            FROM
                partner_user_assignment pua
            LEFT JOIN partners p ON pua.partner_idx = p.partner_idx
            LEFT JOIN partner_users pu ON pua.partner_user_idx = pu.partner_user_idx
        ";

        $bindings = [];

        if ($data['selectedPartnerIdx'] != -1) {
            $bindings = [
                'partnerIdx' => $data['selectedPartnerIdx']
            ];

            $sql .= "
                WHERE
                    pua.partner_idx = :partnerIdx
            ";
        }

        $partnerUsers = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data['partnerUsers'] = $partnerUsers;

        self::render('partner/partner-user-list', ['data' => $data]);
    }

    public static function partnerUserCreate()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-user-create', ['data' => $data]);
    }

    public static function partnerUserEdit()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if (empty($_GET['userIdx'])) {
            header('Location: /partner/dashboard');
            exit;
        }

        $partnerUser = PartnerUser::find($_GET['userIdx']);
        $partnerUserAssignment = PartnerUserAssignment::where('partner_user_idx', $partnerUser->partner_user_idx)->first();

        if ($basicData['selectedPartnerIdx'] != $partnerUserAssignment->partner_idx) {
            header('Location: /partner/dashboard');
            exit;
        }

        if ($basicData['user']->partner_user_level < 4) {
            header('Location: /partner/dashboard');
            exit;
        }

        $appendData = [];
        $appendData['editUser'] = $partnerUser;

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-user-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
