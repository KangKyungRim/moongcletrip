<?php

namespace App\Services;

use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\RoomInventory;
use App\Models\RoomPrice;

class OndaRoomInventoryService
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

	/**
	 * [온다(Onda)] 특정 요금제의 기간별 재고 정보를 API를 통해 조회한다.
	 *
	 * API 요청 중 오류가 발생하면 빈 배열을 반환한다.
	 *
	 * @param  int    $rateplanId 요금제의 ID.
	 * @param  string $fromDate   조회 시작일 (YYYY-MM-DD 형식).
	 * @param  string $toDate     조회 종료일 (YYYY-MM-DD 형식).
	 * @return array{
	 * 		rateplan_id	: string,
	 * 		date		: string,
	 * 		vacancy		: int,
	 * 		basic_price	: int,
	 * 		sale_price	: int,
	 * 		promotion_type : ?string,
	 * 		extra_adult	: int,
	 * 		extra_child	: int,
	 * 		extra_infant: int,
	 * 		currency	: string,
	 * 		checkin		: string,
	 * 		checkout	: string,
	 * 		length_of_stay : array{min: int, max: int}
	 * }[] 조회된 재고 목록 배열. 실패 시 빈 배열.
	 */
	public function fetchInventories(int $rateplanId, string $fromDate, string $toDate): array
	{
		try {
			$response = $this->client->request(
				'GET',
				$this->ondaDomain . "/inventories?rateplan_id={$rateplanId}&from={$fromDate}&to={$toDate}"
			);

			return json_decode($response->getBody(), true)['inventories'];
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

	/**
     * [온다(Onda) API]를 통해 가져온 재고 및 가격 정보 배열을 DB에 저장
     *
     * 전달받은 재고 배열을 순회하며 각 날짜의 재고와 가격 정보를 개별적으로 저장/업데이트
     *
     * @param array{
	 * 		rateplan_id	: string,
	 * 		date		: string,
	 * 		vacancy		: int,
	 * 		basic_price	: int,
	 * 		sale_price	: int,
	 * 		promotion_type : ?string,
	 * 		extra_adult	: int,
	 * 		extra_child	: int,
	 * 		extra_infant: int,
	 * 		currency	: string,
	 * 		checkin		: string,
	 * 		checkout	: string,
	 * 		length_of_stay : array{min: int, max: int}
	 * }[] fetchInventories API로부터 받은 재고/가격 정보 배열
     * @param RoomRateplan $roomRateplan 재고 정보가 연결될 객실-요금제 정보 객체
     * @return void
     */
	public function saveInventories(array $inventories, RoomRateplan $roomRateplan): void
	{
		foreach ($inventories as $inventoryData) {
			$this->saveRoomInventory($inventoryData, $roomRateplan);
			$this->saveRoomPrice($inventoryData, $roomRateplan);
		}
	}

	/**
     * 개별 객실 재고(잔여 객실 수) 정보를 저장하거나 업데이트
     *
     * 'room_rateplan_idx'와 'inventory_date'를 기준으로 데이터를 찾아 업데이트하거나, 없는 경우 새로 생성
     *
     * @internal
     * @param array{date: string, vacancy: int} $data 단일 날짜의 재고 정보
     * @param RoomRateplan $roomRateplan 관련 객실-요금제 정보
     * @return void
     */
	private function saveRoomInventory(array $data, RoomRateplan $roomRateplan): void
	{
		$inventory = RoomInventory::firstOrNew([
			'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
			'inventory_date' => $data['date'],
		]);

		$inventory->fill([
			'room_idx' => $roomRateplan->room_idx,
			'rateplan_idx' => $roomRateplan->rateplan_idx,
			'inventory_quantity' => $data['vacancy'],
		])->save();
	}

	/**
     * 개별 객실 가격 정보를 저장하거나 업데이트
     *
     * 'room_rateplan_idx'와 'room_price_date'를 기준으로 데이터를 찾아 업데이트하거나, 없는 경우 새로 생성한다.
     *
     * @internal
     * @param array{
	 * 		rateplan_id	: string,
	 * 		date		: string,
	 * 		vacancy		: int,
	 * 		basic_price	: int,
	 * 		sale_price	: int,
	 * 		promotion_type : ?string,
	 * 		extra_adult	: int,
	 * 		extra_child	: int,
	 * 		extra_infant: int,
	 * 		currency	: string,
	 * 		checkin		: string,
	 * 		checkout	: string,
	 * 		length_of_stay : array{min: int, max: int}
	 * } $data 단일 날짜의 재고/가격 정보 배열
     * @param RoomRateplan $roomRateplan 관련 객실-요금제 정보.
     * @return void
     */
	private function saveRoomPrice(array $data, RoomRateplan $roomRateplan): void
	{
		$roomPrice = RoomPrice::firstOrNew([
			'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
			'room_price_date' => $data['date'],
		]);

		$roomPrice->fill([
			'room_idx' => $roomRateplan->room_idx,
			'rateplan_idx' => $roomRateplan->rateplan_idx,
			'room_price_basic' => $data['basic_price'],
			'room_price_sale' => $data['sale_price'],
			'room_price_currency' => $data['currency'],
			'room_price_promotion_type' => $data['promotion_type'],
			'room_price_additional_adult' => $data['extra_adult'],
			'room_price_additional_child' => $data['extra_child'],
			'room_price_additional_tiny' => $data['extra_infant'],
			'room_price_checkin' => $data['checkin'],
			'room_price_checkout' => $data['checkout'],
			'room_price_stay_min' => $data['length_of_stay']['min'],
			'room_price_stay_max' => $data['length_of_stay']['max'],
		])->save();
	}
}
