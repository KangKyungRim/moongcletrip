<?php

namespace App\Services;

use App\Models\MoongcleDeal;
use App\Models\MoongcleDealTag;
use App\Models\MoongcleMatch;
use App\Models\MoongcleOffer;
use Carbon\Carbon;

use Database;
use RedisManager;

class RecommendOffers
{
	public static function recommendOffers($moongcledeal)
	{
		$userTags = MoongcleDealTag::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)
			->orderBy('tag_order', 'asc')
			->get(['tag_idx', 'tag_order']);

		// if ($userTags->isEmpty()) {
		// 	return;
		// }

		$bindings = [];

		$redis = RedisManager::getInstance();

		// Redis 키 설정
		$redisKey = "moongcledeal:recommend:all";
		$cacheTTL = 86400; // 하루 (초 단위)

		// Redis에서 데이터 조회
		$cachedData = $redis->get($redisKey);

		if ($cachedData) {
			$offers = json_decode($cachedData);
		} else {
			$sql = "
				WITH offer_data AS (
					SELECT 
						mo.moongcleoffer_idx AS item_idx,
						mo.partner_idx,
						mo.moongcleoffer_attractive,
						p.partner_city,
						p.partner_address1,
						p.partner_status
					FROM moongcletrip.moongcleoffers mo
					LEFT JOIN moongcletrip.partners p 
						ON mo.partner_idx = p.partner_idx
					WHERE mo.moongcleoffer_status = 'enabled' AND p.partner_status = 'enabled'
				),
				inventory_data AS (
					SELECT 
						mo.moongcleoffer_idx AS item_idx,
						MIN(mp.moongcleoffer_price_date) AS inventory_start_date,
						MAX(mp.moongcleoffer_price_date) AS inventory_end_date,
						MIN(mp.moongcleoffer_price_sale) AS moongcleoffer_price_sale,
						r.room_max_person,
						JSON_ARRAYAGG(DISTINCT mp.moongcleoffer_price_date ORDER BY mp.moongcleoffer_price_date ASC) AS available_dates
					FROM moongcletrip.moongcleoffers mo
					LEFT JOIN moongcletrip.room_rateplan rr 
						ON mo.base_product_idx = rr.room_rateplan_idx
						AND rr.room_rateplan_status = 'enabled'
					LEFT JOIN moongcletrip.room_inventories i 
						ON rr.rateplan_idx = i.rateplan_idx
						AND i.inventory_quantity > 0
						AND i.inventory_date > CURDATE()
					LEFT JOIN moongcletrip.moongcleoffer_prices mp 
						ON mo.moongcleoffer_idx = mp.moongcleoffer_idx
						AND i.inventory_date = mp.moongcleoffer_price_date
						AND mp.moongcleoffer_price_sale > 0
						AND mp.is_closed = 0
					LEFT JOIN moongcletrip.rooms r
						ON r.room_idx = rr.room_idx
						AND r.room_status = 'enabled'
					WHERE mo.moongcleoffer_status = 'enabled'
					GROUP BY mo.moongcleoffer_idx
				),
				rateplan_benefits_data AS (
					SELECT 
						mo.moongcleoffer_idx AS item_idx,
						JSON_ARRAYAGG(JSON_OBJECT('tag_idx', ct.tag_idx, 'tag_machine_name', ct.tag_machine_name)) AS rateplan_benefits
					FROM moongcletrip.moongcleoffers mo
					LEFT JOIN moongcletrip.curated_tags ct
						ON ct.item_type = 'moongcleoffer' 
						AND ct.item_idx = mo.moongcleoffer_idx
					WHERE mo.moongcleoffer_status = 'enabled'
					GROUP BY mo.moongcleoffer_idx
				)
				SELECT 
					od.item_idx,
					od.partner_idx,
					od.moongcleoffer_attractive,
					od.partner_city,
					od.partner_address1,
					od.partner_status,
					id.room_max_person,
					id.moongcleoffer_price_sale,
					id.inventory_start_date,
					id.inventory_end_date,
					id.available_dates,
					rbd.rateplan_benefits
				FROM offer_data od
				LEFT JOIN inventory_data id ON od.item_idx = id.item_idx
				LEFT JOIN rateplan_benefits_data rbd ON od.item_idx = rbd.item_idx;
			";

			$offers = Database::getInstance()->getConnection()->select($sql, $bindings);

			$redis->setex($redisKey, $cacheTTL, json_encode($offers));
		}

		$matchingScores = [];
		$requiredTags = [60, 84, 85, 117, 119, 103, 183, 185, 259, 134, 135, 136];
		//글램핑, 카라반, 캠핑 추가

		foreach ($offers as $key => $offer) {
			$requiredBreak = false;
			$availableDates = json_decode($offer->available_dates);

			if (empty($offer->inventory_start_date)) {
				continue;
			}
			if (empty($offer->inventory_end_date)) {
				continue;
			}

			if (empty($offer->moongcleoffer_price_sale)) {
				continue;
			}

			if (empty($offer->moongcleoffer_attractive)) {
				continue;
			}

			$itemId = $offer->item_idx;
			$partnerCity = str_replace(' ', '', $offer->partner_address1); // partner_address1 가져오기
			$maxPerson = $offer->room_max_person; // room_max_person 가져오기
			$startDate = $offer->inventory_start_date; // 시작 날짜
			$endDate = $offer->inventory_end_date;     // 끝 날짜
			$tagData = json_decode($offer->rateplan_benefits, true);
			$attractiveScore = $offer->moongcleoffer_attractive;

			if (!is_array($tagData)) {
				continue;
			}

			$offerTags = array_column($tagData, 'tag_idx');
			$offerMachineTags = array_column($tagData, 'tag_machine_name');

			// $userTags에서 필수 태그가 있는지 확인
			$userRequiredTags = [];

			if (!$userTags->isEmpty()) {
				foreach ($userTags as $userTag) {
					if (in_array($userTag->tag_idx, $requiredTags)) {
						$userRequiredTags[] = $userTag;
					}
				}
			}

			// 필수 태그가 $userTags에 존재하면서 $offerTags에는 없는 경우 continue
			foreach ($userRequiredTags as $requiredTag) {
				if (!in_array($requiredTag->tag_idx, $offerTags)) {
					$requiredBreak = true;
					break;
				}
			}

			// 반려동물과 선택 시 뭉클오퍼에 반려동물 동반가능 태그가 없으면 추천하지 않음
			if (!empty($moongcledeal->selected['companion']['tag_machine_name'])) {
				if ($moongcledeal->selected['companion']['tag_machine_name'] == 'pet_friendly') {
					// if (!in_array(117, $offerTags)) {
					// 	$requiredBreak = true;
					// }

					if (!empty($moongcledeal->selected['pet'])) {
						if (!empty($moongcledeal->selected['pet']['size']['tag_machine_name'])) {
							if (!in_array($moongcledeal->selected['pet']['size']['tag_machine_name'], $offerMachineTags)) {
								$requiredBreak = true;
							}
						}
						if (!empty($moongcledeal->selected['pet']['weight']['tag_machine_name'])) {
							if (!in_array($moongcledeal->selected['pet']['weight']['tag_machine_name'], $offerMachineTags)) {
								$requiredBreak = true;
							}
						}
						if (!empty($moongcledeal->selected['pet']['counts']['tag_machine_name'])) {
							if (!in_array($moongcledeal->selected['pet']['counts']['tag_machine_name'], $offerMachineTags)) {
								$requiredBreak = true;
							}
						}
					}
				}
			}

			if ($requiredBreak) {
				continue;
			}

			// 추가 조건 점수 계산
			$additionalScore = $attractiveScore;

			// 기간 조건
			if (!empty($moongcledeal->selected['days'])) {
				$dayCondition = $moongcledeal->selected['days'][0]['type'] ?? null;
				if ($dayCondition === 'period') {
					$dates = explode('~', $moongcledeal->selected['days'][0]['dates'] ?? '');
					if (count($dates) === 2 && in_array($dates[1], $availableDates) && in_array($dates[0], $availableDates)) {
						$additionalScore += 100;

						$checkin = trim($dates[0]);
						$checkout = trim($dates[1]);

						//error_log('checkin :: '.$checkin.'checkout :: '.$checkout);
						//선택한 투숙 날짜 조건에 잔여객실 없는 경우 스킵
						$searchRemainRoomsSql = "
							SELECT
								DATEDIFF(:checkout1, :checkin1) AS desired_nights,
								COUNT(DISTINCT i.inventory_date) AS available_nights -- 조회된 숙박 가능일수
							FROM moongcletrip.moongcleoffers mo
							LEFT JOIN moongcletrip.stay_moongcleoffers smo	-- '온다'처럼 부모가 없을 수 있으니 LEFT JOIN
								ON mo.stay_moongcleoffer_idx = smo.stay_moongcleoffer_idx
							JOIN moongcletrip.room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
							JOIN moongcletrip.rooms r ON rr.room_idx = r.room_idx
							JOIN moongcletrip.room_inventories i 
								ON rr.room_rateplan_idx = i.room_rateplan_idx
									AND i.inventory_date >= :checkin2
									AND i.inventory_date < :checkout2 -- 체크아웃 날짜는 숙박일에 포함되지 않으므로 '<' 사용
									AND i.inventory_quantity > 0 -- 재고가 1개 이상 있어야 함
							WHERE
								mo.moongcleoffer_idx = :moongcleoffer_idx
								-- 활성화된 뭉클딜인지 체크
								AND (
									-- 조건 1: 온다 딜일 경우 (부모가 없고, 내 상태가 enabled)
									(mo.stay_moongcleoffer_idx IS NULL AND mo.moongcleoffer_status = 'enabled')
									OR
									-- 조건 2: 그 외 딜일 경우 (부모가 있고, 부모와 내 상태가 모두 enabled)
									(mo.stay_moongcleoffer_idx IS NOT NULL AND smo.stay_moongcleoffer_status = 'enabled' AND mo.moongcleoffer_status = 'enabled')
								)
								AND r.room_max_person >= :person_cnt	-- 인원수 조건
							GROUP BY
								mo.moongcleoffer_idx
							HAVING
								desired_nights = available_nights
						";

						$bindings = [
							'checkout1'	=> $checkout,
							'checkin1'	=> $checkin,
							'checkin2'	=> $checkin,
							'checkout2'	=> $checkout,
							'moongcleoffer_idx'	=> $itemId,
							'person_cnt' => (!empty($moongcledeal->selected['personnel'])) ? $moongcledeal->selected['personnel'] : 0  
						];

						$existsRemainRooms = Database::getInstance()->getConnection()->select($searchRemainRoomsSql, $bindings);
						//투숙일수(desired_nights) != 객실있는 숙박가능일수(available_nights)
						if ( empty($existsRemainRooms)) {
							continue;
						} else if(!empty($existsRemainRooms[0]) && property_exists($existsRemainRooms[0], 'desired_nights') && $existsRemainRooms[0]->desired_nights != $existsRemainRooms[0]->available_nights) {
							continue;
						}

					} else {
						continue;
					}
				} elseif ($dayCondition === 'month') {
					$month = preg_replace('/[^0-9]/', '', $moongcledeal->selected['days'][0]['dates'] ?? '');
					if (!empty($month) && date('m', strtotime($startDate)) <= $month && date('m', strtotime($endDate)) >= $month) {
						$additionalScore += 100;
					} else {
						continue;
					}
				}
			}

			// 인원 조건
			if (!empty($moongcledeal->selected['personnel']) && $maxPerson >= $moongcledeal->selected['personnel']) {
				$additionalScore += 50;
			}

			// 도시 조건
			if (!empty($moongcledeal->selected['city']['tag_name'])) {
				$cityName = str_replace(' ', '', $moongcledeal->selected['city']['tag_name']);
				if (strpos($partnerCity, $cityName) !== false) {
					$additionalScore += 100;
				} else {
					continue;
				}
			}

			// 기본 매칭 점수 계산
			if (!$userTags->isEmpty()) {
				foreach ($userTags as $userTag) {
					if (in_array($userTag->tag_idx, $offerTags)) {
						$matchingScores[$itemId] = ($matchingScores[$itemId] ?? 0)
							+ 100 + max(0, (10 - $userTag->tag_order) * 20);
					}
				}
			} else {
				foreach ($moongcledeal->selected['taste'] as $userTag) {
					if (in_array($userTag->tag_machine_name, $offerMachineTags)) {
						$matchingScores[$itemId] = ($matchingScores[$itemId] ?? 0)
							+ 100;
					}
				}
			}

			// 최종 점수 반영
			$matchingScores[$itemId] = ($matchingScores[$itemId] ?? 0) + $additionalScore;
		}

		$highestScoresByPartner = [];
		$selectedOffers = [];

		// 파트너별 최고 점수 오퍼 필터링
		foreach ($matchingScores as $itemId => $score) {
			$partnerId = MoongcleOffer::find($itemId)->partner_idx;

			if (!isset($highestScoresByPartner[$partnerId]) || $score > $highestScoresByPartner[$partnerId]['score']) {
				$highestScoresByPartner[$partnerId] = ['itemId' => $itemId, 'score' => $score];
			}
		}

		// 점수 순위에 따라 정렬
		usort($highestScoresByPartner, function ($a, $b) {
			return $b['score'] <=> $a['score']; // 점수 내림차순 정렬
		});

		// 최대 12개 선택
		$selectedOffers = array_slice(array_column($highestScoresByPartner, 'itemId'), 0, 12);

		$baseTime = Carbon::now(); // 기준 시간

		// 선택된 오퍼 저장
		foreach ($selectedOffers as $index => $itemId) {
			// 중복 확인 로직
			$existingMatch = MoongcleMatch::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)
				->where('product_idx', $itemId)
				->where('moongcle_match_category', 'moongcleoffer')
				->exists();

			if ($existingMatch) {
				continue;
			}

			if ($index === 0) {
				// 첫 번째 알림은 현재 시간
				$notificationTime = Carbon::now();

				// if ($notificationTime->hour >= 22 || $notificationTime->hour < 8) {
				// 	if ($notificationTime->hour >= 22) {
				// 		$notificationTime->addDay()->hour(8)->minute(mt_rand(0, 59));
				// 	} elseif ($notificationTime->hour < 8) {
				// 		$notificationTime->hour(8)->minute(mt_rand(0, 59));
				// 	}
				// }
			} else {
				if ($index == 1 || $index == 2) {
					$interval = mt_rand(2, 5);
				} else {
					$interval = mt_rand(2, 10);
				}

				$notificationTime = (clone $baseTime)->addMinutes($interval);

				// 밤 22시 이후와 오전 7시 이전을 피하는 로직
				while ($notificationTime->hour >= 22 || $notificationTime->hour < 8) {
					// 밤 22시 이후 또는 오전 8시 이전이면 8시로 이동
					if ($notificationTime->hour >= 22) {
						$notificationTime->addDay()->hour(8)->minute(mt_rand(0, 59));
					} elseif ($notificationTime->hour < 8) {
						$notificationTime->hour(8)->minute(mt_rand(0, 59));
					}
				}
			}

			if (!$existingMatch) {
				MoongcleMatch::create([
					'moongcledeal_idx' => $moongcledeal->moongcledeal_idx,
					'product_idx' => $itemId,
					'moongcle_match_category' => 'moongcleoffer',
					'moongcle_match_status' => 'enabled',
					'notification_status' => 'pending',
					'notification_time' => $notificationTime,
					'match_score' => $matchingScores[$itemId],
				]);
			}

			$baseTime = clone $notificationTime;
		}
	}
}