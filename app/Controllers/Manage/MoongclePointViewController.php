<?php

namespace App\Controllers\Manage;

use App\Helpers\PartnerHelper;

use App\Models\Partner;

use Database;

class MoongclePointViewController
{
    public static function moongclepoint()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $sql = "
            SELECT
                mp.*,
                (
                    SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'image_origin_name', img.image_origin_name,
                            'image_path', img.image_big_path,
                            'image_origin_size', img.image_origin_size
                        ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
                    FROM moongcletrip.moongcle_point_images img
                    WHERE img.moongcle_point_idx = mp.moongcle_point_idx
                ) AS images
            FROM moongcle_points mp
            WHERE mp.partner_idx = :partnerIdx
        ";

        $bindings = [
            'partnerIdx' => $data['selectedPartnerIdx']
        ];

        $moongclePoint = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data['moongclePoint'] = $moongclePoint[0];

        self::render('manage/moongcle-point', ['data' => $data]);
    }

    public static function moongclepointUpdate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $sql = "
            SELECT
                mp.*,
                (
                    SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'image_origin_name', img.image_origin_name,
                            'image_origin_path', img.image_origin_path,
                            'image_origin_size', img.image_origin_size
                        ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
                    FROM moongcletrip.moongcle_point_images img
                    WHERE img.moongcle_point_idx = mp.moongcle_point_idx
                ) AS images
            FROM moongcle_points mp
            WHERE mp.partner_idx = :partnerIdx
        ";

        $bindings = [
            'partnerIdx' => $data['selectedPartnerIdx']
        ];

        $moongclePoint = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data['moongclePoint'] = $moongclePoint[0];

        self::render('manage/moongcle-point-form', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
