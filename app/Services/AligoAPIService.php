<?php

namespace App\Services;

class AligoAPIService
{
	private $apiKey;
	private $userId;
	private $apiBaseURL = 'https://kakaoapi.aligo.in/akv10/';

	public function __construct()
	{
		$this->apiKey = $_ENV['ALIGO_API_KEY'];
		$this->userId = 'honolulucompany';
	}

	// 토큰 생성
	public function createToken()
	{
		$url = $this->apiBaseURL . 'token/create/30/s/';
		$postData = [
			'apikey' => $this->apiKey,
			'userid' => $this->userId
		];

		$response = $this->sendRequest($url, $postData);
		if ($response && $response->code === 0) {
			return $response->token;
		} else {
			throw new \Exception('Token generation failed: ' . ($response->message ?? 'Unknown error'));
		}
	}

	// 템플릿 메시지 전송
	public function sendTemplateMessage($token, $templateData)
	{
		$url = $this->apiBaseURL . 'alimtalk/send/';

		$template = $this->getTemplateConfig($templateData['type']);
		$message = $this->generateMessage($templateData);
		$emtitle = '';

		if ($templateData['type'] === 'booking') {
			$emtitle = $templateData["product_partner_name"] . "-예약 완료";
		} else if ($templateData['type'] === 'cancel') {
			$emtitle = $templateData["product_partner_name"] . "-예약 취소 완료";
		} else if ($templateData['type'] === 'checkin') {
			$emtitle = "체크인 안내";
		}
		else if ($templateData['type'] === 'checkout') {
			$emtitle = "고객님의 여행 후기를 공유해주세요!";
		}

		$postData = $this->prepareMessageData($token, $templateData, $template, $message, $emtitle, $templateData['type']);

		$response = $this->sendRequest($url, $postData);
		if ($response && $response->code === 0) {
			return [
				'result' => true,
				'message' => $message
			];
		} else {
			return [
				'result' => false,
				'message' => $message
			];
		}
	}

	// HTTP 요청 전송
	private function sendRequest($url, $postData)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);
		$error = curl_error($curl);
		curl_close($curl);

		if ($response === false) {
			throw new \Exception('cURL error: ' . $error);
		}

		return json_decode($response);
	}

	// 메시지 데이터 준비
	private function prepareMessageData($token, $templateData, $template, $message, $emtitle, $type)
	{
		$button = '{"button":[{"name":"예약 내역보기","linkType":"AL","linkI":"https://moongcle.page.link/reservation", "linkA": "https://moongcle.page.link/reservation"}]}';

		if ($type === 'checkin') {
			$button = '{"button":[{"name":"뭉클에서 예약내역 확인하기","linkType":"AL","linkI":"https://moongcle.page.link/reservation", "linkA": "https://moongcle.page.link/reservation"}]}';
		} else if ($type === 'checkout') {
			$button = '{"button":[{"name":"이용후기 작성하기","linkType":"AL","linkI":"https://moongcle.page.link/reservation", "linkA": "https://moongcle.page.link/reservation"}]}';
		}

		return [
			'apikey' => $this->apiKey,
			'userid' => $this->userId,
			'token' => $token,
			'senderkey' => $_ENV['ALIGO_SENDER_KEY'],
			'tpl_code' => $template['tpl_code'],
			'sender' => $_ENV['ALIGO_SENDER'],
			'receiver_1' => $templateData['reservation_phone'],
			'recvname_1' => $templateData['reservation_name'],
			'subject_1' => $template['subject'],
			'emtitle_1' => $emtitle,
			'message_1' => $message,
			'failover' => "Y",
			'fsubject_1' => $template['subject'],
			'fmessage_1' => $message,
			'button_1' => $button,
		];
	}

	// 템플릿 설정 가져오기
	private function getTemplateConfig($type)
	{
		$templates = [
			'booking' => ['tpl_code' => 'TY_6625', 'subject' => '예약 완료', 'title' => '예약 완료'],
			'cancel' => ['tpl_code' => 'TW_2525', 'subject' => '호텔예약취소완료', 'title' => '예약 취소 완료'],
			'checkin' => ['tpl_code' => 'TW_2526', 'subject' => '체크인 안내', 'title' => '체크인 안내'],
			'checkout' => ['tpl_code' => 'TW_2528', 'subject' => '여행 후기', 'title' => '고객님의 여행 후기를 공유해주세요!'],
		];

		if (!isset($templates[$type])) {
			throw new \Exception('Invalid template type: ' . $type);
		}

		return $templates[$type];
	}

	// 메시지 생성
	private function generateMessage($data)
	{
		$message = '';

		if ($data['type'] === 'booking') {
			$message = $data["product_partner_name"] . "-예약 완료" . "\n" .
				$data["reservation_name"] . "님 안녕하세요." . "\n" .
				AligoAPIService::formatStayDate($data["start_date"]) . "로 " . $data["product_partner_name"] . "에 예약이 완료 되었습니다." . "\n" .
				"\n" .
				"▶" . $data["product_partner_name"] . "\n" .
				"▶옵션명 : " . $data["product_detail_name"] . "\n" .
				"▶고객정보 : " . $data["reservation_name"] . "\n" .
				"▶입실/퇴실일자 : [" . AligoAPIService::formatStayDate($data["start_date"]) . "~" . AligoAPIService::formatStayDate($data["end_date"]) . "]" . "\n" .
				"▶예약번호 : " . $data["reservation_number"] . "\n" .
				"▶예약상태: " . '예약 확정' . "\n" .
				"▶수량: " . $data["quantity"] . "객실" . "\n" .
				"▶결제금액(vat포함) : [" . number_format($data['payment_sale_amount']) . "원]" . "\n" .
				"▶객실상세보기 : [" . $_ENV['APP_HTTP'] . '/stay/detail/' . $data['partner_idx'] . "]" . "\n" .
				"\n" .
				"※ 본 예약은 상품페이지에 기재된 '취소 및 환불 규정'에 동의 후 예약이 되었으며, 취소수수료 없이, 무료취소가 가능한 기한은 상품마다 상이하오니, 상품페이지에 기재된 '취소 및 환불규정'을 반드시 숙지해주시기 바랍니다." . "\n" .
				"※ 차량 이용시, 주차가능 여부를 반드시 확인해주세요." . "\n" .
				"※ 문의 : [" . $data['partner_phonenumber'] . "]" . "\n" .
				"\n" .
				"그럼 편안하고 즐거운 투숙이 되시길 바라겠습니다." . "\n" .
				"이용해주셔서 감사합니다.";
		} else if ($data['type'] === 'cancel') {
			if ($data['payment_sale_amount'] == $data['refund_amount']) {
				$freeCancel = "무료취소";
			} else {
				$freeCancel = "위약금 발생";
			}

			$message = $data["product_partner_name"] . "-예약 취소 완료" . "\n" .
				$data["reservation_name"] . "님 안녕하세요." . "\n" .
				"뭉클트립을 이용해주셔서 감사합니다." . "\n" .
				"예약 취소 안내입니다." . "\n" .
				"\n" .
				"(예약취소정보)" . "\n" .
				"▶호텔명 : " . $data["product_partner_name"] . "\n" .
				"▶옵션명 : " . $data["product_detail_name"] . "\n" .
				"▶수량: " . $data["quantity"] . "객실" . "\n" .
				"▶고객정보 : " . $data["reservation_name"] . "\n" .
				"▶결제금액(vat포함) : [" . number_format($data['payment_sale_amount']) . "원]" . "\n" .
				"▶입실/퇴실일자 : [" . AligoAPIService::formatStayDate($data["start_date"])  . "~" . AligoAPIService::formatStayDate($data["end_date"]) . "]" . "\n" .
				"▶예약번호 : " . $data["reservation_number"] . "\n" .
				"▶무료취소 여부 : " . $freeCancel . "\n" .
				"▶취소위약금: " . number_format($data['payment_sale_amount'] - $data['refund_amount']) . '원' . "\n" .
				"※ 취소 및 노쇼 위약금은 예약시 동의하셨던 상품 취소 및 환불 정책에 따라 책정됩니다." . "\n" .
				"\n" .
				"※ 부분 환불금액이 발생 시 취소완료 후 환불까지 영업일 3~7일 정도 소요될 수 있습니다." . "\n" .
				"\n" .
				"자세한 내용은 '예약내역'을 확인해주세요." . "\n" .
				"\n" .
				"감사합니다.";
		} else if ($data['type'] === 'checkin') {
			$startDateTime = new \DateTime($data["start_date"]);
			$endDateTime = new \DateTime($data["end_date"]);

			// 두 날짜의 차이 계산
			$interval = $startDateTime->diff($endDateTime);

			$message = $data["reservation_name"] . " 고객님, 예약하신 숙소로 곧 떠날 시간이에요!" . "\n" .
				"\n" .
				"▶예약번호: " . $data["reservation_number"] . "\n" .
				"▶숙소명: " . $data["product_partner_name"] . "\n" .
				"▶상품명: " . $data["product_detail_name"] . "\n" .
				"▶체크인 시간: " . AligoAPIService::timeType($data['checkin']) . "\n" .
				"▶체크아웃 시간: " . AligoAPIService::timeType($data['checkout']) . "\n" .
				"▶주소: " . $data['partner_address1'] . " " . $data['partner_address2'] . " " . $data['partner_address3'] . "\n" .
				"▶투숙일자: " . AligoAPIService::formatStayDate($data["start_date"]) . "~" . AligoAPIService::formatStayDate($data["end_date"]) . "(" . $interval->days . "박)" . "\n" .
				"\n" .
				"체크인은 프론트에 도착 후 뭉클 예약내역을 보여주시면 안내를 도와드릴 거예요." . "\n" .
				"\n" .
				"뭉클트립을 이용해주셔서 감사드리며, 안전하고 뭉클한 여행 되시길 바라겠습니다." . "\n" .
				"\n" .
				"감사합니다.";
		} else if ($data['type'] === 'checkout') {
			$message = $data["reservation_name"] . " 고객님, 오늘은 " . $data["product_partner_name"] . "에서 체크아웃하는 날이네요." . "\n" .
            "\n" .
            $data["product_partner_name"] . "에서의 투숙은 어떠셨나요?" . "\n" .
            "편안하고 즐거운 투숙이 되셨길 바랍니다!" . "\n" .
            "\n" .
            "잊으신 물건 없는지 다시 한 번 확인하시며, " . $data["product_partner_name"] . "에서의 후기도 잊지 말아주세요!" . "\n" .
            "\n" .
            "고객님의 후기는 다른 뭉클 회원분들의 여행에 큰 도움이 됩니다." . "\n" .
            "\n" .
            "뭉클트립을 이용해주셔서 다시 한 번 감사드리며, 이번 여행이 소중한 추억으로 간직되시길 바라겠습니다.";
		}

		return $message;
	}

	public static function formatStayDate($originDate)
	{
		// 요일 배열 설정
		$daysOfWeek = ['일', '월', '화', '수', '목', '금', '토'];

		// DateTime 객체 생성
		$date = new \DateTime($originDate);

		// DateTime 객체의 요일 구하기
		$dateDayOfWeek = $daysOfWeek[$date->format('w')];

		// 날짜 포맷 변경
		$formattedDate = $date->format('y.m.d');

		// 결과 문자열 생성
		return "{$formattedDate}({$dateDayOfWeek})";
	}

	public static function TimeType($time)
	{
		$timestamp = strtotime($time);
		$hour = (int)date("H", $timestamp);
		$min = date("i", $timestamp);

		// 오전/오후 구분 및 12시간제로 변환
		$period = $hour >= 12 ? "오후" : "오전";
		$hour = $hour % 12 === 0 ? 12 : $hour % 12; // 12시간제로 변환

		return "{$period} {$hour}:{$min}";
	}
}
