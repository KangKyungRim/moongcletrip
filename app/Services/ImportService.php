<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\Rateplan;
use App\Models\Benefit;
use App\Models\BenefitItem;
use App\Models\MoongcleTag;
use App\Models\MoongcleTagConnection;
use App\Models\MoongcleTagConnectionDraft;
use App\Models\CuratedTag;
use App\Models\MoongcleOffer;
use App\Models\MoongcleOfferPrice;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

use Carbon\Carbon;

class ImportService
{
	public static function importTags($filePath)
	{
		$spreadsheet = IOFactory::load($filePath);
		$sheet = $spreadsheet->getActiveSheet();
		$rows = $sheet->toArray();

		// $headers = array_map('strtolower', $rows[0]); // 첫 번째 줄은 헤더로 처리
		// unset($rows[0]); // 헤더 제거

		$partnerPrevIdx = 0;
		$partnerChanged = false;

		foreach ($rows as $rowKey => $row) {
			if (empty($row[1])) {
				continue;
			}

			if ($partnerPrevIdx !== $row[1]) {
				$partnerChanged = true;
			} else {
				$partnerChanged = false;
			}

			$partnerIdx = $row[1];
			$partnerPrevIdx = $row[1];
			$roomIdx = $row[6];
			$rateplanIdx = $row[11];

			$partner = Partner::find($partnerIdx);
			$room = Room::find($roomIdx);
			$rateplan = Rateplan::find($rateplanIdx);

			// if ($partner->partner_status != 'enabled') {
			// 	continue;
			// }
			// if ($room->room_status != 'enabled') {
			// 	continue;
			// }
			// if ($rateplan->rateplan_status != 'enabled') {
			// 	continue;
			// }

			$stayTags = $row[18];
			// $roomBenefits = $row[14];
			// $roomTags = $row[19];
			$rateplanBenefits = $row[16];
			// $rateplanTags = $row[12];
			$curatedTags = $row[17];
			$discount = $row[26];
			$additionalTag = $row[27];
			$whomTag = $row[28];
			$attractive = $row[30];
			$averageDiscount = $row[31];
			$searchIndex = $row[32];

			if (!empty($averageDiscount) && $partnerChanged) {
				$averageDiscount = str_replace(',', '', $averageDiscount);
				$averageDiscount = intval($averageDiscount);
				$searchIndex = intval($searchIndex);

				$partner->average_discount = $averageDiscount;
				$partner->search_index = $searchIndex;

				$partner->save();
			}

			if ((!empty($curatedTags) || !empty($additionalTag) || !empty($whomTag) || !empty($stayTags)) && $partnerChanged) {
				// 세 태그 병합 후 중복 제거
				$tags = array_merge(
					!empty($curatedTags) ? explode(',', $curatedTags) : [],
					!empty($stayTags) ? explode(',', $stayTags) : [],
					!empty($additionalTag) ? explode(',', $additionalTag) : [],
					!empty($whomTag) ? explode(',', $whomTag) : []
				);
				$tags = array_map('trim', $tags); // 공백 제거
				$tags = array_unique($tags); // 중복 제거

				// 기존 태그 조회 (MoongcleTagConnection & MoongcleTagConnectionDraft)
				$existingTags = MoongcleTagConnection::where('item_idx', $partner->partner_detail_idx)
					->where('item_type', 'stay')
					->pluck('tag_name')
					->toArray();

				$existingDraftTags = MoongcleTagConnectionDraft::where('item_idx', $partner->partner_detail_idx)
					->where('item_type', 'stay')
					->pluck('tag_name')
					->toArray();

				// 삭제할 태그 찾기 & 삭제
				$tagsToRemove = array_diff($existingTags, $tags);
				if (!empty($tagsToRemove)) {
					MoongcleTagConnection::where('item_idx', $partner->partner_detail_idx)
						->where('item_type', 'stay')
						->whereIn('tag_name', $tagsToRemove)
						->delete();
				}

				$tagsToRemoveDraft = array_diff($existingDraftTags, $tags);
				if (!empty($tagsToRemoveDraft)) {
					MoongcleTagConnectionDraft::where('item_idx', $partner->partner_detail_idx)
						->where('item_type', 'stay')
						->whereIn('tag_name', $tagsToRemoveDraft)
						->delete();
				}

				// 추가할 태그 찾기 & 추가
				foreach ($tags as $tag) {
					$tagData = MoongcleTag::where('tag_name', $tag)->first();

					if (!empty($tagData) && !in_array($tagData->tag_name, $existingTags)) {
						MoongcleTagConnectionDraft::create([
							'tag_idx' => $tagData->tag_idx,
							'tag_name' => $tagData->tag_name,
							'tag_machine_name' => $tagData->tag_machine_name,
							'item_idx' => $partner->partner_detail_idx,
							'item_type' => 'stay',
							'is_approved' => true,
						]);

						MoongcleTagConnection::create([
							'tag_idx' => $tagData->tag_idx,
							'tag_name' => $tagData->tag_name,
							'tag_machine_name' => $tagData->tag_machine_name,
							'item_idx' => $partner->partner_detail_idx,
							'item_type' => 'stay',
						]);
					}
				}
			}

			// if (!empty($roomBenefits)) {
			// 	$benefits = explode(',', $roomBenefits);

			// 	foreach ($benefits as $benefit) {
			// 		$benefit = trim($benefit);
			// 		$benefitData = Benefit::where('benefit_name', $benefit)->first();

			// 		if (empty($benefitData)) {
			// 			$benefitData = new Benefit();
			// 			$benefitData->benefit_name = $benefit;
			// 			$benefitData->benefit_created_at = Carbon::now();
			// 			$benefitData->benefit_updated_at = Carbon::now();
			// 			$benefitData->save();
			// 		}

			// 		$existingBenefit = BenefitItem::where('benefit_idx', $benefitData->benefit_idx)
			// 			->where('item_idx', $room->room_idx)
			// 			->where('item_type', 'room')
			// 			->first();

			// 		if (empty($existingBenefit)) {
			// 			BenefitItem::create([
			// 				'benefit_idx' => $benefitData->benefit_idx,
			// 				'benefit_name' => $benefitData->benefit_name,
			// 				'item_idx' => $room->room_idx,
			// 				'item_type' => 'room',
			// 			]);
			// 		}
			// 	}
			// }

			// if (!empty($roomTags)) {
			// 	$tags = explode(',', $roomTags);

			// 	foreach ($tags as $tag) {
			// 		$tag = trim($tag);
			// 		$tagData = MoongcleTag::where('tag_name', $tag)->first();

			// 		if (!empty($tagData)) {
			// 			$existingTag = MoongcleTagConnection::where('item_idx', $room->room_idx)
			// 				->where('tag_idx', $tagData->tag_idx)
			// 				->where('item_type', 'room')
			// 				->first();

			// 			if (empty($existingTag)) {
			// 				MoongcleTagConnectionDraft::create([
			// 					'tag_idx' => $tagData->tag_idx,
			// 					'tag_name' => $tagData->tag_name,
			// 					'tag_machine_name' => $tagData->tag_machine_name,
			// 					'item_idx' => $room->room_idx,
			// 					'item_type' => 'room',
			// 					'is_approved' => true,
			// 				]);
			// 				MoongcleTagConnection::create([
			// 					'tag_idx' => $tagData->tag_idx,
			// 					'tag_name' => $tagData->tag_name,
			// 					'tag_machine_name' => $tagData->tag_machine_name,
			// 					'item_idx' => $room->room_idx,
			// 					'item_type' => 'room',
			// 				]);
			// 			}
			// 		}
			// 	}
			// }

			if (!empty($rateplanBenefits)) {
				$benefits = explode(',', $rateplanBenefits);
				$benefitNames = array_map('trim', $benefits);

				// 현재 데이터베이스에 있는 해당 rateplan의 베네핏 가져오기
				$existingBenefits = BenefitItem::where('item_idx', $rateplan->rateplan_idx)
					->where('item_type', 'rateplan')
					->get();

				// 기존 베네핏 중 새롭게 들어온 리스트에 없는 것 삭제
				foreach ($existingBenefits as $existingBenefit) {
					if (!in_array($existingBenefit->benefit_name, $benefitNames)) {
						$existingBenefit->delete(); // 삭제
					}
				}

				// 새롭게 들어온 베네핏 처리
				foreach ($benefitNames as $benefit) {
					$benefitData = Benefit::where('benefit_name', $benefit)->first();

					if (empty($benefitData)) {
						$benefitData = new Benefit();
						$benefitData->benefit_name = $benefit;
						$benefitData->benefit_created_at = Carbon::now();
						$benefitData->benefit_updated_at = Carbon::now();
						$benefitData->save();
					}

					$existingBenefit = BenefitItem::where('benefit_idx', $benefitData->benefit_idx)
						->where('item_idx', $rateplan->rateplan_idx)
						->where('item_type', 'rateplan')
						->first();

					if (empty($existingBenefit)) {
						BenefitItem::create([
							'benefit_idx' => $benefitData->benefit_idx,
							'benefit_name' => $benefitData->benefit_name,
							'item_idx' => $rateplan->rateplan_idx,
							'item_type' => 'rateplan',
						]);
					}
				}
			}

			// if (!empty($rateplanTags)) {
			// 	$tags = explode(',', $rateplanTags);

			// 	foreach ($tags as $tag) {
			// 		$tag = trim($tag);
			// 		$tagData = MoongcleTag::where('tag_name', $tag)->first();

			// 		if (!empty($tagData)) {
			// 			$existingTag = MoongcleTagConnection::where('item_idx', $rateplan->rateplan_idx)
			// 				->where('tag_idx', $tagData->tag_idx)
			// 				->where('item_type', 'rateplan')
			// 				->first();

			// 			if (empty($existingTag)) {
			// 				MoongcleTagConnectionDraft::create([
			// 					'tag_idx' => $tagData->tag_idx,
			// 					'tag_name' => $tagData->tag_name,
			// 					'tag_machine_name' => $tagData->tag_machine_name,
			// 					'item_idx' => $rateplan->rateplan_idx,
			// 					'item_type' => 'rateplan',
			// 					'is_approved' => true,
			// 				]);
			// 				MoongcleTagConnection::create([
			// 					'tag_idx' => $tagData->tag_idx,
			// 					'tag_name' => $tagData->tag_name,
			// 					'tag_machine_name' => $tagData->tag_machine_name,
			// 					'item_idx' => $rateplan->rateplan_idx,
			// 					'item_type' => 'rateplan',
			// 				]);
			// 			}
			// 		}
			// 	}
			// }


			// if (empty($discount)) {
			// 	continue;
			// }

			if (!is_numeric($discount)) {
				continue;
			}

			$moongcleoffer = null;

			$roomPrices = RoomPrice::where('room_idx', $room->room_idx)
				->where('rateplan_idx', $rateplan->rateplan_idx)
				->get();

			if (empty($roomPrices[0])) {
				continue;
			}

			$moongcleoffer = MoongcleOffer::where('partner_idx', $partner->partner_idx)
				->where('base_product_idx', $roomPrices[0]->room_rateplan_idx)
				->where('moongcleoffer_category', 'roomRateplan')
				->first();

			if (empty($moongcleoffer)) {
				$moongcleoffer = new MoongcleOffer();
				$moongcleoffer->partner_idx = $partner->partner_idx;
				$moongcleoffer->room_idx = $room->room_idx;
				$moongcleoffer->rateplan_idx = $rateplan->rateplan_idx;
				$moongcleoffer->base_product_idx = $roomPrices[0]->room_rateplan_idx;
				$moongcleoffer->moongcleoffer_category = 'roomRateplan';
				$moongcleoffer->moongcleoffer_status = 'enabled';
				$moongcleoffer->minimum_discount = $discount;
				$moongcleoffer->moongcleoffer_attractive = empty($attractive) ? 0 : $attractive;
				$moongcleoffer->save();
			} else {
				$moongcleoffer->room_idx = $room->room_idx;
				$moongcleoffer->rateplan_idx = $rateplan->rateplan_idx;
				$moongcleoffer->minimum_discount = $discount;
				$moongcleoffer->moongcleoffer_attractive = empty($attractive) ? 0 : $attractive;
				$moongcleoffer->save();
			}

			foreach ($roomPrices as $roomPrice) {
				$moongcleofferPrice = MoongcleOfferPrice::where('moongcleoffer_idx', $moongcleoffer->moongcleoffer_idx)
					->where('base_idx', $roomPrice->room_price_idx)
					->where('base_type', 'roomRateplan')
					->first();

				if (empty($moongcleofferPrice)) {
					$moongcleofferPrice = new MoongcleOfferPrice();
					$moongcleofferPrice->moongcleoffer_idx = $moongcleoffer->moongcleoffer_idx;
					$moongcleofferPrice->base_idx = $roomPrice->room_price_idx;
					$moongcleofferPrice->base_type = 'roomRateplan';
					$moongcleofferPrice->moongcleoffer_price_date = $roomPrice->room_price_date;
					$moongcleofferPrice->moongcleoffer_price_basic = $roomPrice->room_price_basic;
					$moongcleofferPrice->moongcleoffer_price_sale = ceil($roomPrice->room_price_sale - ($roomPrice->room_price_sale * ($discount / 100)));
					$moongcleofferPrice->moongcleoffer_discount_rate = $discount;
				} else {
					$moongcleofferPrice->moongcleoffer_price_basic = $roomPrice->room_price_basic;
					$moongcleofferPrice->moongcleoffer_price_sale = ceil($roomPrice->room_price_sale - ($roomPrice->room_price_sale * ($discount / 100)));
					$moongcleofferPrice->moongcleoffer_discount_rate = $discount;
				}

				$moongcleofferPrice->save();
			}

			if (!empty($curatedTags) && !empty($moongcleoffer)) {
				$tags = explode(',', $curatedTags);
				$tags = array_map('trim', $tags); // 모든 태그를 정리하여 배열로 저장

				// 현재 연결된 태그를 가져옴
				$existingCuratedTags = CuratedTag::where('item_idx', $moongcleoffer->moongcleoffer_idx)
					->where('item_type', 'moongcleoffer')
					->get();

				// 삭제 로직: 새로 넣는 데이터에 없는 태그를 삭제
				foreach ($existingCuratedTags as $existingTag) {
					if (!in_array($existingTag->tag_name, $tags)) {
						$existingTag->delete();
					}
				}

				// 새 태그를 추가하는 로직
				foreach ($tags as $tag) {
					$tagExist = MoongcleTag::where('tag_name', $tag)->first();

					if ($tagExist) {
						$curatedTag = CuratedTag::where('tag_idx', $tagExist->tag_idx)
							->where('item_idx', $moongcleoffer->moongcleoffer_idx)
							->where('item_type', 'moongcleoffer')
							->first();

						if (!empty($curatedTag)) {
							continue;
						} else {
							$curatedTag = new CuratedTag();
							$curatedTag->tag_idx = $tagExist->tag_idx;
							$curatedTag->tag_name = $tagExist->tag_name;
							$curatedTag->tag_machine_name = $tagExist->tag_machine_name;
							$curatedTag->item_idx = $moongcleoffer->moongcleoffer_idx;
							$curatedTag->item_type = 'moongcleoffer';
							$curatedTag->save();
						}
					}
				}
			}
		}
	}
}
