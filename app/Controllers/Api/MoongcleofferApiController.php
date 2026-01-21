<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\MoongcleOffer;
use App\Models\RoomRateplan;
use App\Models\Partner;
use App\Models\Room;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Database;

use Carbon\Carbon;

class MoongcleofferApiController
{
	public function moongcleofferDetailInfo($moongcleofferIdx)
	{
		$input = json_decode(file_get_contents("php://input"), true);

		$startDate = $input['startDate'];
		$endDate = $input['endDate'];

		$bindings = [];
		$roomTagList = [];

		$sql = "
            SELECT 
				rp.*,
				rt.*,
				(
					SELECT cr.cancel_rules_day
					FROM cancel_rules cr
					WHERE cr.partner_idx = r.partner_idx
						AND cr.cancel_rules_percent = 100
					ORDER BY cr.cancel_rules_day ASC
					LIMIT 1
				) AS min_cancel_rule_day,
				(SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                FROM benefit_item bi 
                WHERE (bi.item_idx = r.room_idx AND bi.item_type = 'room')
                    OR (bi.item_idx = rt.rateplan_idx AND bi.item_type = 'rateplan')
                    OR (bi.item_idx = mp.moongcleoffer_idx AND bi.item_type = 'moongcleoffer')) AS benefits
			FROM moongcleoffer_prices mp 
			LEFT JOIN room_prices rp ON rp.room_rateplan_idx = mp.room_rateplan_idx AND rp.room_price_date = mp.moongcleoffer_price_date
			LEFT JOIN rooms r ON r.room_idx = rp.room_idx 
			LEFT JOIN rateplans rt ON rt.rateplan_idx = rp.rateplan_idx 
			WHERE mp.moongcleoffer_idx = :roomRateplanIdx
				AND mp.moongcleoffer_price_date >= :startDate
				AND mp.moongcleoffer_price_date < :endDate
            ORDER BY mp.moongcleoffer_price_date ASC;
        ";

        $bindings = [
            'roomRateplanIdx' => $moongcleofferIdx,
			'startDate' => $startDate,
			'endDate' => $endDate,
        ];

        // 쿼리 실행
        $roomRateplan = Database::getInstance()->getConnection()->select($sql, $bindings);

		$partner = Partner::find($roomRateplan[0]->partner_idx);
		$room = Room::find($roomRateplan[0]->room_idx);
		$stay = $partner->partnerDetail();

		// 템플릿 파일 불러오기
		ob_start();
		include $_ENV['ROOT_DIRECTORY'] . '/public' . "/../app/Views/app/blocks/room-rateplan-detail-full-modal.php";
		$html = ob_get_clean();

		// JSON으로 HTML 전송
		echo json_encode(['html' => $html]);
	}
}
