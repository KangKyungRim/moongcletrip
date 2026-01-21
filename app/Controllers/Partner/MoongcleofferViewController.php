<?php

namespace App\Controllers\Partner;

use App\Models\Room;
use App\Models\Rateplan;
use App\Models\StayMoongcleOffer;
use App\Models\MoongcleTag;
use App\Models\CuratedTag;

use App\Services\TagService;
use App\Helpers\PartnerHelper;

use Database;

class MoongcleofferViewController
{
    public static function moongcleoffers()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $sortColumn = $_GET['sortColumn'] ?? 'createdAt';
        $sortOrder = $_GET['sortOrder'] ?? 'DESC';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 20;
        $offset = ($page - 1) * $perPage;

        $bindings = [];

        $sql = "
            SELECT SQL_CALC_FOUND_ROWS
                sm.*,
                rt.*
            FROM moongcletrip.stay_moongcleoffers sm
            LEFT JOIN moongcletrip.rateplans rt ON rt.rateplan_idx = sm.rateplan_idx 
            WHERE sm.partner_idx = :partnerIdx
        ";

        $bindings['partnerIdx'] = $data['selectedPartnerIdx'];

        $allowedSortColumns = [
            'createdAt' => 'sm.created_at',
            'saleStartDate' => 'sm.sale_start_date',
            'stayStartDate' => 'sm.stay_start_date'
        ];

        $orderByColumn = $allowedSortColumns[$sortColumn] ?? 'sm.created_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY {$orderByColumn} {$sortOrder}";

        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $moongcleoffers = Database::getInstance()->getConnection()->select($sql, $bindings);

        $totalCountResult = Database::getInstance()->getConnection()->select("SELECT FOUND_ROWS() AS total_count");
        $totalCount = $totalCountResult[0]->total_count ?? 0;
        $totalPages = ceil($totalCount / $perPage);

        foreach ($moongcleoffers as &$moongcleoffer) {
            $moongcleoffer->benefits = json_decode($moongcleoffer->benefits, true);
            $moongcleoffer->rooms = json_decode($moongcleoffer->rooms, true);
            $moongcleoffer->tags = json_decode($moongcleoffer->tags, true);
            $moongcleoffer->curated_tags = json_decode($moongcleoffer->curated_tags, true);

            $moongcleoffer->tag_list = MoongcleTag::whereIn('tag_machine_name', $moongcleoffer->tags)->get();
        }

        $data['moongcleoffers'] = $moongcleoffers;
        $data['pagination'] = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages
        ];

        self::render('partner/moongcleoffers', ['data' => $data]);
    }

    public static function createMoongcleoffer()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] === -1) {
            echo "<script>alert('파트너를 먼저 선택해 주세요.'); history.back();</script>";
            exit;
        }

        $appendData = [];

        $bindings = [];
        $bindings['partnerIdx'] = $basicData['selectedPartnerIdx'];

        $sql = "
            SELECT
                p.*,
                s.*,
                (
					SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
				) AS image_paths,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types
            FROM partners p
            LEFT JOIN stays s ON p.partner_detail_idx = s.stay_idx
            LEFT JOIN review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
            LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
            LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
            WHERE p.partner_category = 'stay'
                AND p.partner_idx = :partnerIdx
        ";

        $partner = Database::getInstance()->getConnection()->select($sql, $bindings);

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

        $rateplans = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT 
                b.*
            FROM benefits b
            WHERE b.benefit_recommend = 1
        ";

        $benefitData = Database::getInstance()->getConnection()->select($sql);

        $benefits = [];
        foreach ($benefitData as $benefit) {
            if (empty($benefits[$benefit->benefit_category])) {
                $benefits[$benefit->benefit_category] = [];
            }

            $benefits[$benefit->benefit_category][] = $benefit;
        }

        $tagService = new TagService();

        $appendData['partner'] = $partner[0];
        $appendData['rateplans'] = $rateplans;
        $appendData['benefits'] = $benefits;
        $appendData['tags'] = $tagService->getMoongcledealTags();

        $data = array_merge($basicData, $appendData);
        self::render('partner/moongcleoffers-create', ['data' => $data]);
    }

    public static function editMoongcleoffer()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] === -1) {
            header('Location: /partner/dashboard');
        }

        $stayMoongcleoffer = StayMoongcleOffer::find($_GET['stayMoongcleofferIdx']);

        if ($stayMoongcleoffer->partner_idx != $basicData['selectedPartnerIdx']) {
            header('Location: /partner/dashboard');
        }

        $appendData = [];

        $bindings = [];
        $bindings['partnerIdx'] = $basicData['selectedPartnerIdx'];

        $sql = "
            SELECT
                p.*,
                s.*,
                (
					SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
				) AS image_paths,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types
            FROM partners p
            LEFT JOIN stays s ON p.partner_detail_idx = s.stay_idx
            LEFT JOIN review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
            LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
            LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
            WHERE p.partner_category = 'stay'
                AND p.partner_idx = :partnerIdx
        ";

        $partner = Database::getInstance()->getConnection()->select($sql, $bindings);

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

        $rateplans = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT 
                b.*
            FROM benefits b
            WHERE b.benefit_recommend = 1
        ";

        $benefitData = Database::getInstance()->getConnection()->select($sql);

        $benefits = [];
        foreach ($benefitData as $benefit) {
            if (empty($benefits[$benefit->benefit_category])) {
                $benefits[$benefit->benefit_category] = [];
            }

            $benefits[$benefit->benefit_category][] = $benefit;
        }

        $tagService = new TagService();

        $appendData['partner'] = $partner[0];
        $appendData['rateplans'] = $rateplans;
        $appendData['benefits'] = $benefits;
        $appendData['tags'] = $tagService->getMoongcledealTags();
        $appendData['stayMoongcleoffer'] = $stayMoongcleoffer;

        $data = array_merge($basicData, $appendData);
        self::render('partner/moongcleoffers-edit', ['data' => $data]);
    }

    public static function moongcleoffersOnda()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/moongcleoffers-onda', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
