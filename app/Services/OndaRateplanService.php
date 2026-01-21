<?php

namespace App\Services;


use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Partner;
use App\Models\Room;
use App\Models\Rateplan;
use App\Models\RoomRateplan;

class OndaRateplanService
{
	private Client $client;
	private $ondaDomain;
	private $ondaKey;

	public function __construct()
	{
		if ($_ENV['ONDA_ENV'] == 'production') {
			$this->ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
			$this->ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
		} else {
			$this->ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
			$this->ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
		}

		$this->client = new Client([
			'headers' => [
				'Authorization' => $this->ondaKey,
				'accept' => 'application/json',
			],
		]);
	}

	// public function fetchRateplans(int $partnerIdx, int $roomIdx): array
	// {
	// 	$response = $this->client->request('GET', $this->ondaDomain."/properties/{$partnerIdx}/roomtypes/{$roomIdx}/rateplans");
	// 	return json_decode($response->getBody(), true)['rateplans'];
	// }

	public function fetchRateplans(int $partnerIdx, int $roomIdx): array
	{
		try {
			// 요청 실행
			$response = $this->client->request('GET', $this->ondaDomain . "/properties/{$partnerIdx}/roomtypes/{$roomIdx}/rateplans");
			return json_decode($response->getBody(), true)['rateplans'];
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			// 404나 기타 클라이언트 오류 처리
			error_log("Client error while fetching rateplans for partner {$partnerIdx} and room {$roomIdx}: " . $e->getMessage());
			return []; // 기본 값 반환
		} catch (\Exception $e) {
			// 기타 예외 처리
			error_log("Unexpected error while fetching rateplans for partner {$partnerIdx} and room {$roomIdx}: " . $e->getMessage());
			return []; // 기본 값 반환
		}
	}

	public function fetchRateplanDetails(int $partnerIdx, int $roomIdx, int $rateplanIdx): array
	{
		$response = $this->client->request('GET', $this->ondaDomain . "/properties/{$partnerIdx}/roomtypes/{$roomIdx}/rateplans/{$rateplanIdx}");
		return json_decode($response->getBody(), true)['rateplan'];
	}

	public function saveRateplans(Room $room): void
	{
		$partner = Partner::find($room->partner_idx);
		$rateplans = $this->fetchRateplans($partner->partner_onda_idx, $room->room_onda_idx);

		foreach ($rateplans as $ondaRateplan) {
			$rateplan = Rateplan::firstOrNew([
				'rateplan_onda_idx' => intval($ondaRateplan['id']),
				'rateplan_thirdparty' => 'onda',
			]);

			if (strpos($ondaRateplan['name'], '숙박세일페스타') !== false) {
				$ondaRateplan['name'] = str_replace('숙박세일페스타', '깜짝할인', $ondaRateplan['name']);
			}

			$rateplan->fill([
				'partner_idx' => $room->partner_idx,
				'rateplan_name' => $ondaRateplan['name'],
				'rateplan_onda_idx' => intval($ondaRateplan['id']),
				'rateplan_thirdparty' => 'onda',
				'rateplan_status' => $ondaRateplan['status'],
				'rateplan_type' => $ondaRateplan['type'],
				'rateplan_updated_at' => Carbon::now(),
			])->save();

			$roomRateplan = RoomRateplan::firstOrNew([
				'rateplan_onda_idx' => intval($ondaRateplan['id']),
				'rateplan_thirdparty' => 'onda',
			]);

			$roomRateplan->fill([
				'partner_idx' => $room->partner_idx,
				'room_idx' => $room->room_idx,
				'rateplan_idx' => $rateplan->rateplan_idx,
				'rateplan_onda_idx' => $rateplan->rateplan_onda_idx,
				'rateplan_thirdparty' => $rateplan->rateplan_thirdparty,
				'room_rateplan_status' => $ondaRateplan['status'],
				'updated_at' => Carbon::now(),
			])->save();
		}
	}

	public function saveRateplanDetails(RoomRateplan $roomRateplan): void
	{
		$partner = Partner::find($roomRateplan->partner_idx);
		$room = Room::find($roomRateplan->room_idx);
		$ondaRateplan = $this->fetchRateplanDetails($partner->partner_onda_idx, $room->room_onda_idx, $roomRateplan->rateplan_onda_idx);

		$maxTimestamp = '2038-01-19 03:14:07';

		$salesTo = isset($ondaRateplan['sales_terms']['to']) && $ondaRateplan['sales_terms']['to'] > $maxTimestamp
			? $maxTimestamp
			: $ondaRateplan['sales_terms']['to'];

		$roomRateplan->update([
			'room_rateplan_status' => $ondaRateplan['status'],
			'updated_at' => Carbon::now(),
		]);

		$rateplan = Rateplan::firstOrNew([
			'rateplan_onda_idx' => intval($ondaRateplan['id']),
			'rateplan_thirdparty' => 'onda',
		]);

		if (strpos($ondaRateplan['name'], '숙박세일페스타') !== false) {
			$ondaRateplan['name'] = str_replace('숙박세일페스타', '깜짝할인', $ondaRateplan['name']);
		}

		$rateplan->fill([
			'rateplan_name' => $ondaRateplan['name'],
			'rateplan_status' => $ondaRateplan['status'],
			'rateplan_type' => $ondaRateplan['type'],
			'rateplan_description' => $ondaRateplan['description'],
			'rateplan_stay_min' => $ondaRateplan['length_of_stay']['min'],
			'rateplan_stay_max' => $ondaRateplan['length_of_stay']['max'],
			'rateplan_sales_from' => $ondaRateplan['sales_terms']['from'],
			'rateplan_sales_to' => $salesTo,
			'rateplan_is_refundable' => $ondaRateplan['refundable'],
			'rateplan_has_breakfast' => $ondaRateplan['meal']['breakfast'],
			'rateplan_has_lunch' => $ondaRateplan['meal']['lunch'],
			'rateplan_has_dinner' => $ondaRateplan['meal']['dinner'],
			'rateplan_meal_count' => $ondaRateplan['meal']['meal_count'],
			'rateplan_updated_at' => Carbon::now(),
		])->save();
	}

	public function saveRateplansAll(Room $room): void
	{
		$partner = Partner::find($room->partner_idx);
		$rateplans = $this->fetchRateplans($partner->partner_onda_idx, $room->room_onda_idx);

		foreach ($rateplans as $ondaRateplan) {
			$rateplan = Rateplan::firstOrNew([
				'rateplan_onda_idx' => intval($ondaRateplan['id']),
				'rateplan_thirdparty' => 'onda',
			]);

			if (strpos($ondaRateplan['name'], '숙박세일페스타') !== false) {
				$ondaRateplan['name'] = str_replace('숙박세일페스타', '깜짝할인', $ondaRateplan['name']);
			}

			$rateplan->fill([
				'partner_idx' => $room->partner_idx,
				'rateplan_name' => $ondaRateplan['name'],
				'rateplan_onda_idx' => intval($ondaRateplan['id']),
				'rateplan_thirdparty' => 'onda',
				'rateplan_status' => $ondaRateplan['status'],
				'rateplan_type' => $ondaRateplan['type'],
				'rateplan_updated_at' => Carbon::now(),
			])->save();

			$roomRateplan = RoomRateplan::firstOrNew([
				'rateplan_onda_idx' => intval($ondaRateplan['id']),
				'rateplan_thirdparty' => 'onda',
			]);

			$roomRateplan->fill([
				'partner_idx' => $room->partner_idx,
				'room_idx' => $room->room_idx,
				'rateplan_idx' => $rateplan->rateplan_idx,
				'rateplan_onda_idx' => $rateplan->rateplan_onda_idx,
				'rateplan_thirdparty' => $rateplan->rateplan_thirdparty,
				'room_rateplan_status' => $ondaRateplan['status'],
				'updated_at' => Carbon::now(),
			])->save();

			$ondaRateplanDetail = $this->fetchRateplanDetails($partner->partner_onda_idx, $room->room_onda_idx, $roomRateplan->rateplan_onda_idx);

			$maxTimestamp = '2038-01-19 03:14:07';

			$salesTo = isset($ondaRateplanDetail['sales_terms']['to']) && $ondaRateplanDetail['sales_terms']['to'] > $maxTimestamp
				? $maxTimestamp
				: $ondaRateplanDetail['sales_terms']['to'];

			$roomRateplan->update([
				'room_rateplan_status' => $ondaRateplanDetail['status'],
				'updated_at' => Carbon::now(),
			]);

			if (strpos($ondaRateplanDetail['name'], '숙박세일페스타') !== false) {
				$ondaRateplanDetail['name'] = str_replace('숙박세일페스타', '깜짝할인', $ondaRateplanDetail['name']);
			}

			$rateplan->fill([
				'rateplan_name' => $ondaRateplanDetail['name'],
				'rateplan_status' => $ondaRateplanDetail['status'],
				'rateplan_type' => $ondaRateplanDetail['type'],
				'rateplan_description' => $ondaRateplanDetail['description'],
				'rateplan_stay_min' => $ondaRateplanDetail['length_of_stay']['min'],
				'rateplan_stay_max' => $ondaRateplanDetail['length_of_stay']['max'],
				'rateplan_sales_from' => $ondaRateplanDetail['sales_terms']['from'],
				'rateplan_sales_to' => $salesTo,
				'rateplan_is_refundable' => $ondaRateplanDetail['refundable'],
				'rateplan_has_breakfast' => $ondaRateplanDetail['meal']['breakfast'],
				'rateplan_has_lunch' => $ondaRateplanDetail['meal']['lunch'],
				'rateplan_has_dinner' => $ondaRateplanDetail['meal']['dinner'],
				'rateplan_meal_count' => $ondaRateplanDetail['meal']['meal_count'],
				'rateplan_updated_at' => Carbon::now(),
			])->save();
		}
	}
}
