<?php

namespace App\Controllers\Manage;

use App\Models\PartnerUser;
use App\Models\PartnerUserAssignment;

use App\Helpers\PartnerHelper;

use Database;

class UserViewController
{
    public static function userList()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 20;
        $offset = ($page - 1) * $perPage;

        $searchField = $_GET['field'] ?? null; // user_id, user_email 등
        $searchValue = $_GET['keyword'] ?? null;

        $allowedFields = [
            'user_id',
            'user_nickname',
            'user_email',
            'reservation_name',
            'reservation_email',
            'reservation_phone',
            'user_agree_marketing'
        ];

        $where = " WHERE u.user_is_guest = 0 ";
        $bindings = [];

        if (in_array($searchField, $allowedFields) && !empty($searchValue)) {
            $where .= "AND u.{$searchField} LIKE ?";
            $bindings[] = "%{$searchValue}%";
        }

        $countSql = "SELECT COUNT(*) as total FROM moongcletrip.users u $where";
        $total = Database::getInstance()->getConnection()->selectOne($countSql, $bindings);
        $data['total'] = $total->total;

        $sql = "
            SELECT
                u.user_idx,
                u.user_is_guest,
                u.user_id,
                u.user_nickname,
                u.user_email,
                u.reservation_name,
                u.reservation_email,
                u.reservation_phone,
                u.user_agree_marketing,
                u.user_created_at
            FROM moongcletrip.users u
            $where
            ORDER BY u.user_created_at DESC
            LIMIT ? OFFSET ?
        ";

        $bindings[] = $perPage;
        $bindings[] = $offset;

        $users = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data['users'] = $users;
        $data['page'] = $page;
        $data['perPage'] = $perPage;
        $data['allowedFields'] = $allowedFields;

        self::render('manage/users', ['data' => $data]);
    }

    public static function partnerUserList()
    {
        $data = PartnerHelper::adminDefaultProcess();

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

        self::render('manage/partner-user-list', ['data' => $data]);
    }

    public static function partnerUserCreate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-user-create', ['data' => $data]);
    }

    public static function partnerUserEdit()
    {
        $basicData = PartnerHelper::adminDefaultProcess();

        if (empty($_GET['userIdx'])) {
            header('Location: /manage/dashboard');
            exit;
        }

        $partnerUser = PartnerUser::find($_GET['userIdx']);
        $partnerUserAssignment = PartnerUserAssignment::where('partner_user_idx', $partnerUser->partner_user_idx)->first();

        if ($basicData['selectedPartnerIdx'] != $partnerUserAssignment->partner_idx) {
            header('Location: /manage/dashboard');
            exit;
        }

        if ($basicData['user']->partner_user_level < 4) {
            header('Location: /manage/dashboard');
            exit;
        }

        $appendData = [];
        $appendData['editUser'] = $partnerUser;

        $data = array_merge($basicData, $appendData);
        self::render('manage/partner-user-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
