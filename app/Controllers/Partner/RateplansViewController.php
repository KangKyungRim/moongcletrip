<?php

namespace App\Controllers\Partner;

use App\Models\Rateplan;
use App\Models\Room;

use App\Helpers\PartnerHelper;

use Database;

class RateplansViewController
{
    public static function rateplanList()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        $sql = "
            SELECT 
                rp.*,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'room_idx', r.room_idx,
                        'room_name', r.room_name,
                        'room_status', r.room_status,
                        'room_rateplan_status', COALESCE(rr.room_rateplan_status, 'notExist')
                    )
                ) AS rooms
            FROM rateplans rp
            LEFT JOIN rooms r ON r.partner_idx = rp.partner_idx AND r.room_status = 'enabled'
            LEFT JOIN room_rateplan rr ON r.room_idx = rr.room_idx AND rp.rateplan_idx = rr.rateplan_idx
            WHERE rp.partner_idx = :partnerIdx
            GROUP BY rp.rateplan_idx, rp.rateplan_name
            ORDER BY rp.rateplan_idx
        ";

        $bindings = [
            'partnerIdx' => $basicData['selectedPartnerIdx']
        ];

        $rateplans = Database::getInstance()->getConnection()->select($sql, $bindings);

        foreach ($rateplans as &$rateplan) {
            $rateplan->rooms = json_decode($rateplan->rooms);
        }

        $appendData = array(
            'rateplans' => $rateplans
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-rateplan-list', ['data' => $data]);
    }

    public static function rateplanInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] === -1) {
            header('Location: /partner/dashboard');
        }

        $sql = "
            SELECT 
                rp.*,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'room_idx', r.room_idx,
                        'room_name', r.room_name,
                        'room_status', r.room_status,
                        'room_rateplan_status', COALESCE(rr.room_rateplan_status, 'notExist')
                    )
                ) AS rooms
            FROM rateplans rp
            LEFT JOIN rooms r ON r.partner_idx = rp.partner_idx AND r.room_status = 'enabled'
            LEFT JOIN room_rateplan rr ON r.room_idx = rr.room_idx AND rp.rateplan_idx = rr.rateplan_idx
            WHERE rp.rateplan_idx = :rateplanIdx
            GROUP BY rp.rateplan_idx, rp.rateplan_name
        ";

        $bindings = [
            'rateplanIdx' => $_GET['rateplanIdx']
        ];

        $rateplan = Database::getInstance()->getConnection()->select($sql, $bindings);

        $rateplan[0]->rooms = json_decode($rateplan[0]->rooms);

        $appendData = array(
            'rateplan' => $rateplan[0]
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-rateplan-info', ['data' => $data]);
    }

    public static function createRateplanInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] === -1) {
            echo "<script>
                alert('파트너를 먼저 선택해 주세요.');
                window.location.href = '/partner/partner-select';
            </script>";
            exit;
        }

        $standaloneExist = false;

        $rateplan = Rateplan::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->where('rateplan_type', 'standalone')
            ->first();

        if (!empty($rateplan)) {
            $standaloneExist = true;
        }

        $rooms = Room::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->where('room_status', 'enabled')
            ->get();

        $appendData = array(
            'rooms' => $rooms,
            'standaloneExist' => $standaloneExist
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-rateplan-info-create', ['data' => $data]);
    }

    public static function editRateplanInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] === -1) {
            header('Location: /partner/dashboard');
        }

        $sql = "
            SELECT 
                rp.*,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'room_idx', r.room_idx,
                        'room_name', r.room_name,
                        'room_status', r.room_status,
                        'room_rateplan_status', COALESCE(rr.room_rateplan_status, 'notExist')
                    )
                ) AS rooms
            FROM rateplans rp
            LEFT JOIN rooms r ON r.partner_idx = rp.partner_idx AND r.room_status = 'enabled'
            LEFT JOIN room_rateplan rr ON r.room_idx = rr.room_idx AND rp.rateplan_idx = rr.rateplan_idx
            WHERE rp.rateplan_idx = :rateplanIdx
            GROUP BY rp.rateplan_idx, rp.rateplan_name
        ";

        $bindings = [
            'rateplanIdx' => $_GET['rateplanIdx']
        ];

        $rateplan = Database::getInstance()->getConnection()->select($sql, $bindings);

        $rateplan[0]->rooms = json_decode($rateplan[0]->rooms);

        $appendData = array(
            'rateplan' => $rateplan[0]
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-rateplan-info-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
