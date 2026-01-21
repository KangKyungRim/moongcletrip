<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\Room;
use App\Models\CancelRule;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Database;

use Carbon\Carbon;

class RoomRateplanApiController
{
	public function roomRateplanDetailInfo($roomRateplanIdx)
	{
		$input = json_decode(file_get_contents("php://input"), true);

		$startDate = $input['startDate'];
		$endDate = $input['endDate'];

		$bindings = [];
		$roomTagList = [];

		$sql = "
            SELECT 
				rp.*,
				r.*,
				(
					SELECT cr.cancel_rule_idx
					FROM cancel_rules cr
					WHERE cr.partner_idx = r.partner_idx
						AND cr.cancel_rules_percent = 100
					ORDER BY cr.cancel_rules_day ASC
					LIMIT 1
				) AS cancel_rule_idx
			FROM room_prices rp 
			LEFT JOIN rateplans r ON r.rateplan_idx = rp.rateplan_idx 
			WHERE rp.room_rateplan_idx = :roomRateplanIdx
				AND rp.room_price_date >= :startDate
				AND rp.room_price_date < :endDate
            ORDER BY rp.room_price_date ASC;
        ";

		$bindings = [
			'roomRateplanIdx' => $roomRateplanIdx,
			'startDate' => $startDate,
			'endDate' => $endDate,
		];

		// 쿼리 실행
		$roomRateplan = Database::getInstance()->getConnection()->select($sql, $bindings);

		$partner = Partner::find($roomRateplan[0]->partner_idx);
		$room = Room::find($roomRateplan[0]->room_idx);
		$stay = $partner->partnerDetail();

		$cancelRule = null;
		if (!empty($roomRateplan[0]->cancel_rule_idx)) {
			$cancelRule = CancelRule::find($roomRateplan[0]->cancel_rule_idx);
		}

		// 템플릿 파일 불러오기
		ob_start();
		include $_ENV['ROOT_DIRECTORY'] . '/public' . "/../app/Views/app/blocks/room-rateplan-detail-full-modal.php";
		$html = ob_get_clean();

		// JSON으로 HTML 전송
		echo json_encode(['html' => $html]);
	}
}
