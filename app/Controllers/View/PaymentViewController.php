<?php

namespace App\Controllers\View;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\Helpers\MiddleHelper;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\RoomInventory;
use App\Models\Partner;
use App\Models\Room;
use App\Models\Rateplan;
use App\Models\MoongcleOffer;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\RoomPrice;
use App\Models\RoomRateplan;
use App\Models\Voucher;

use App\Services\SanhaIntegrationService;

use Database;

use Carbon\Carbon;

class PaymentViewController
{
	public static function stay($partnerIdx, $roomIdx, $rateplanIdx)
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /stay/detail/' . $partnerIdx . '?' . $_SERVER['QUERY_STRING']);
		}

		if ($_ENV['ONDA_ENV'] == 'production') {
			$ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
			$ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
		} else {
			$ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
			$ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
		}

		$partner = null;

		$deviceType = getDeviceType();

		$sql = "
			SELECT
				p.*,
				s.*,
				(
					SELECT image_normal_path
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
					ORDER BY image_order ASC
					LIMIT 1
				) AS image_path,
				(
					SELECT GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:')
					FROM moongcletrip.tag_connections tc
					WHERE tc.item_idx = p.partner_detail_idx 
					AND tc.item_type = 'stay' 
					AND tc.connection_type = 'stay_type_detail'
				) AS tags,
				r.room_name,
				r.room_standard_person,
				r.room_max_person,
				r.room_bed_type,
				(
                    SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                    FROM moongcletrip.tag_connections t
                    WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                ) AS views,
				rt.rateplan_name,
				rt.rateplan_type,
				rt.rateplan_is_refundable,
				rt.rateplan_stay_min,
				rt.rateplan_stay_max,
				rr.*,
				CONCAT('[', GROUP_CONCAT(
					CONCAT('{\"date\":\"', rp.room_price_date, '\",\"sub_product_id\":', rp.room_price_idx, ',\"sale_price\":', rp.room_price_sale, ',\"basic_price\":', rp.room_price_basic, '}')
					ORDER BY rp.room_price_date ASC
					SEPARATOR ','
				), ']') AS datewise_product_data,
				SUM(rp.room_price_sale) AS total_sale_price,
				SUM(rp.room_price_basic) AS total_basic_price,
				i.*
			FROM partners p
			LEFT JOIN stays s ON s.stay_idx = p.partner_detail_idx
			LEFT JOIN rooms r ON r.partner_idx = p.partner_idx
			LEFT JOIN rateplans rt ON p.partner_idx = rt.partner_idx
			LEFT JOIN room_rateplan rr ON rr.rateplan_idx = rt.rateplan_idx AND r.room_idx = rr.room_idx
			LEFT JOIN room_prices rp ON rp.room_rateplan_idx = rr.room_rateplan_idx
			LEFT JOIN room_inventories i ON i.room_rateplan_idx = rr.room_rateplan_idx AND i.inventory_date = rp.room_price_date 
			WHERE 
				p.partner_idx = :partnerId
				AND r.room_idx = :roomId
				AND rr.rateplan_idx = :rateplanId
				AND rp.room_price_date >= :startDate
				AND rp.room_price_date < :endDate
				AND rp.is_closed = 0
				AND i.inventory_quantity >= :roomQuantity
			GROUP BY 
				rr.room_rateplan_idx;
		";

		$bindings = [
			'partnerId' => $partnerIdx,
			'roomId' => $roomIdx,
			'rateplanId' => $rateplanIdx,
			'startDate' => $_GET['startDate'],
			'endDate' => $_GET['endDate'],
			'roomQuantity' => $_GET['roomQuantity'],
		];

		$partner = Database::getInstance()->getConnection()->select($sql, $bindings);

		if (empty($partner[0])) {
			header('Location: /stay/detail/' . $partnerIdx);
			exit;
		}

		$partner = $partner[0];
		$partner->datewise_product_data = json_decode($partner->datewise_product_data, true);

		if ($_GET['roomQuantity'] > 1) {
			$partner->total_sale_price = $partner->total_sale_price * $_GET['roomQuantity'];
			$partner->total_basic_price = $partner->total_basic_price * $_GET['roomQuantity'];

			foreach ($partner->datewise_product_data as $key => $productData) {
				$partner->datewise_product_data[$key]['sale_price'] = $productData['sale_price'] * $_GET['roomQuantity'];
				$partner->datewise_product_data[$key]['basic_price'] = $productData['basic_price'] * $_GET['roomQuantity'];
			}
		}

		$startDateTime = new \DateTime($_GET['startDate']);
		$endDateTime = new \DateTime($_GET['endDate']);

		$interval = $startDateTime->diff($endDateTime);

		if ($interval->days < $partner->rateplan_stay_min || ($partner->rateplan_stay_max != 0 && $interval->days > $partner->rateplan_stay_max)) {
			header('Location: /');
			exit;
		}

		$refundPolicy = null;

		if ($partner->rateplan_thirdparty === 'onda') {
			$client = new Client();

			$property = Partner::find($partnerIdx);
			$room = Room::find($roomIdx);
			$rateplan = Rateplan::find($rateplanIdx);

			$response = $client->request('GET', $ondaDomain . '/properties/' . $property->partner_onda_idx . '/roomtypes/' . $room->room_onda_idx . '/rateplans/' . $rateplan->rateplan_onda_idx . '/refund_policy?checkin=' . $_GET['startDate'] . '&checkout=' . $_GET['endDate'], [
				'headers' => [
					'Authorization' => $ondaKey,
					'accept' => 'application/json',
				],
			]);

			$refundPolicy = json_decode($response->getBody());
			$refundPolicy = $refundPolicy->refund_policy;
		}

		if (empty($refundPolicy)) {
			$sql = "
				SELECT
					c.*
				FROM cancel_rules c
				WHERE 
					c.partner_idx = :partnerId
				ORDER BY 
					c.cancel_rules_day DESC;
			";

			$bindings = [
				'partnerId' => $partnerIdx,
			];

			$cancelRules = Database::getInstance()->getConnection()->select($sql, $bindings);

			$refundPolicy = [];
			foreach ($cancelRules as $rule) {
				// 환불 규칙의 적용 날짜 계산
				$untilDate = new \DateTime($_GET['startDate']);
				if (empty($rule->cancel_rules_time)) {
					$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime(23, 59, 59);
				} else {
					list($hours, $minutes, $seconds) = explode(':', $rule->cancel_rules_time);
					$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime($hours, $minutes, $seconds);
				}

				// 환불 정책 데이터 형식화
				$refundPolicy[] = (object)[
					'until' => $untilDate->format(\DateTime::RFC3339),
					'percent' => $rule->cancel_rules_percent,
				];
			}
		}

		$refundDate = null;
		$maxRefundDay = null;

		$fullRefundRules = array_filter($refundPolicy, function ($rule) {
			return $rule->percent === 100;
		});

		if (!empty($fullRefundRules)) {
			$maxRefundDay = min(array_column($fullRefundRules, 'until'));
			$refundDate = $maxRefundDay;
		}

		$myCoupons = [];

		if ($user) {
			$now = Carbon::now()->toDateTimeString();

			$sql = "
				SELECT *
				FROM coupon_user
				WHERE user_idx = :userIdx
					AND is_active = 1
					AND is_used = 0
					AND (start_date IS NULL OR start_date <= :now1)
					AND (end_date IS NULL OR end_date >= :now2)
					AND minimum_order_price <= :price
			";

			$bindings = [
				'userIdx' => $user->user_idx,
				'now1' => $now,
				'now2' => $now,
				'price' => $partner->total_sale_price,
			];

			$myCoupons = Database::getInstance()->getConnection()->select($sql, $bindings);
		}

		$terms2 = file_get_contents(__DIR__ . '/../../Views/app/terms/terms2.html');

		$now = new \DateTime();
		$startDateTime = new \DateTime($_GET['startDate']);
		$startDateTime->setTime(23, 50, 0);

		$passToday = false;
		if ($now > $startDateTime) {
			$passToday = true;
		}

		$data = array(
			'deviceType' => $deviceType,
			'partner' => $partner,
			'refundDate' => $refundDate,
			'refundPolicy' => $refundPolicy,
			'user' => $user,
			'myCoupons' => $myCoupons,
			'terms2' => $terms2,
			'passToday' => $passToday,
			'interval' => $interval->days
		);

		self::render('payment-stay', ['data' => $data]);
	}

	public static function moongcleoffer($partnerIdx, $roomIdx, $moongcleofferIdx)
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /moongcleoffer/product/' . $partnerIdx . '?' . $_SERVER['QUERY_STRING']);
		}

		if ($_ENV['ONDA_ENV'] == 'production') {
			$ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
			$ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
		} else {
			$ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
			$ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
		}

		$partner = null;

		$deviceType = getDeviceType();

		$sql = "
			SELECT
				p.*,
				s.*,
				(
					SELECT image_normal_path
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
					ORDER BY image_order ASC
					LIMIT 1
				) AS image_path,
				(
					SELECT GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:')
					FROM moongcletrip.tag_connections tc
					WHERE tc.item_idx = p.partner_detail_idx 
					AND tc.item_type = 'stay' 
					AND tc.connection_type = 'stay_type_detail'
				) AS tags,
				r.room_name,
				r.room_standard_person,
				r.room_max_person,
				r.room_bed_type,
				(
                    SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                    FROM moongcletrip.tag_connections t
                    WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                ) AS views,
				rt.rateplan_idx,
				rt.rateplan_name,
				rt.rateplan_type,
				rt.rateplan_is_refundable,
				mo.moongcleoffer_idx,
				rt.rateplan_stay_min,
				rt.rateplan_stay_max,
				rr.*,
				(SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                FROM benefit_item bi 
                WHERE (bi.item_idx = r.room_idx AND bi.item_type = 'room')
                    OR (bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan')
                    OR (bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer')) AS benefits,
				CONCAT('[', GROUP_CONCAT(
					CONCAT('{\"date\":\"', rp.room_price_date, '\",\"sub_product_id\":', rp.room_price_idx, ',\"sale_price\":', rp.room_price_sale, ',\"basic_price\":', rp.room_price_basic, ',\"moongcledeal_price\":', mp.moongcleoffer_price_sale, '}')
					ORDER BY rp.room_price_date ASC
					SEPARATOR ','
				), ']') AS datewise_product_data,
				SUM(mp.moongcleoffer_price_sale) AS total_sale_price,
				SUM(rp.room_price_sale) AS total_origin_sale_price,
				SUM(mp.moongcleoffer_price_basic) AS total_basic_price,
				i.*
			FROM partners p
			LEFT JOIN stays s ON s.stay_idx = p.partner_detail_idx
			LEFT JOIN rooms r ON r.partner_idx = p.partner_idx
			LEFT JOIN moongcleoffers mo ON mo.moongcleoffer_idx = :moongcleofferIdx
			LEFT JOIN room_rateplan rr ON rr.room_rateplan_idx = mo.base_product_idx
			LEFT JOIN rateplans rt ON rt.rateplan_idx = rr.rateplan_idx
			LEFT JOIN room_prices rp ON rp.room_rateplan_idx = rr.room_rateplan_idx
			LEFT JOIN room_inventories i ON i.room_rateplan_idx = rr.room_rateplan_idx AND i.inventory_date = rp.room_price_date 
			LEFT JOIN moongcleoffer_prices mp ON mp.moongcleoffer_idx = mo.moongcleoffer_idx AND mp.moongcleoffer_price_date = rp.room_price_date 
			WHERE 
				p.partner_idx = :partnerId
				AND r.room_idx = :roomId
				AND rp.room_price_date >= :startDate
				AND rp.room_price_date < :endDate
				AND i.inventory_quantity >= :roomQuantity
			GROUP BY 
				rr.room_rateplan_idx;
		";

		$bindings = [
			'partnerId' => $partnerIdx,
			'roomId' => $roomIdx,
			'moongcleofferIdx' => $moongcleofferIdx,
			'startDate' => $_GET['startDate'],
			'endDate' => $_GET['endDate'],
			'roomQuantity' => $_GET['roomQuantity'],
		];

		$partner = Database::getInstance()->getConnection()->select($sql, $bindings);

		if (empty($partner[0])) {
			header('Location: /moongcleoffer/product/' . $partnerIdx);
			exit;
		}

		$partner = $partner[0];
		$partner->datewise_product_data = json_decode($partner->datewise_product_data, true);

		if ($_GET['roomQuantity'] > 1) {
			$partner->total_sale_price = $partner->total_sale_price * $_GET['roomQuantity'];
			$partner->total_basic_price = $partner->total_basic_price * $_GET['roomQuantity'];
			$partner->total_origin_sale_price = $partner->total_origin_sale_price * $_GET['roomQuantity'];

			foreach ($partner->datewise_product_data as $key => $productData) {
				$partner->datewise_product_data[$key]['sale_price'] = $productData['sale_price'] * $_GET['roomQuantity'];
				$partner->datewise_product_data[$key]['basic_price'] = $productData['basic_price'] * $_GET['roomQuantity'];
				$partner->datewise_product_data[$key]['moongcledeal_price'] = $productData['moongcledeal_price'] * $_GET['roomQuantity'];
			}
		}

		$startDateTime = new \DateTime($_GET['startDate']);
		$endDateTime = new \DateTime($_GET['endDate']);

		$interval = $startDateTime->diff($endDateTime);

		if ($interval->days < $partner->rateplan_stay_min || ($partner->rateplan_stay_max != 0 && $interval->days > $partner->rateplan_stay_max)) {
			header('Location: /');
			exit;
		}

		$refundPolicy = null;

		if ($partner->rateplan_thirdparty === 'onda') {
			$client = new Client();

			$property = Partner::find($partnerIdx);
			$room = Room::find($roomIdx);
			$rateplan = Rateplan::find($partner->rateplan_idx);

			$response = $client->request('GET', $ondaDomain . '/properties/' . $property->partner_onda_idx . '/roomtypes/' . $room->room_onda_idx . '/rateplans/' . $rateplan->rateplan_onda_idx . '/refund_policy?checkin=' . $_GET['startDate'] . '&checkout=' . $_GET['endDate'], [
				'headers' => [
					'Authorization' => $ondaKey,
					'accept' => 'application/json',
				],
			]);

			$refundPolicy = json_decode($response->getBody());
			$refundPolicy = $refundPolicy->refund_policy;
		}

		if (empty($refundPolicy)) {
			$sql = "
				SELECT
					c.*
				FROM cancel_rules c
				WHERE 
					c.partner_idx = :partnerId
				ORDER BY 
					c.cancel_rules_day DESC;
			";

			$bindings = [
				'partnerId' => $partnerIdx,
			];

			$cancelRules = Database::getInstance()->getConnection()->select($sql, $bindings);

			$refundPolicy = [];
			foreach ($cancelRules as $rule) {
				// 환불 규칙의 적용 날짜 계산
				$untilDate = new \DateTime($_GET['startDate']);
				if (empty($rule->cancel_rules_time)) {
					$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime(23, 59, 59);
				} else {
					list($hours, $minutes, $seconds) = explode(':', $rule->cancel_rules_time);
					$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime($hours, $minutes, $seconds);
				}

				// 환불 정책 데이터 형식화
				$refundPolicy[] = (object)[
					'until' => $untilDate->format(\DateTime::RFC3339),
					'percent' => $rule->cancel_rules_percent,
				];
			}
		}

		$refundDate = null;
		$maxRefundDay = null;

		$fullRefundRules = array_filter($refundPolicy, function ($rule) {
			return $rule->percent === 100;
		});

		if (!empty($fullRefundRules)) {
			$maxRefundDay = min(array_column($fullRefundRules, 'until'));
			$refundDate = $maxRefundDay;
		}

		$myCoupons = [];

		if ($user) {
			$now = Carbon::now()->toDateTimeString();

			$sql = "
				SELECT *
				FROM coupon_user
				WHERE user_idx = :userIdx
					AND is_active = 1
					AND is_used = 0
					AND (start_date IS NULL OR start_date <= :now1)
					AND (end_date IS NULL OR end_date >= :now2)
					AND minimum_order_price <= :price
			";

			$bindings = [
				'userIdx' => $user->user_idx,
				'now1' => $now,
				'now2' => $now,
				'price' => $partner->total_sale_price,
			];

			$myCoupons = Database::getInstance()->getConnection()->select($sql, $bindings);
		}

		$terms2 = file_get_contents(__DIR__ . '/../../Views/app/terms/terms2.html');

		$data = array(
			'deviceType' => $deviceType,
			'partner' => $partner,
			'refundDate' => $refundDate,
			'refundPolicy' => $refundPolicy,
			'user' => $user,
			'myCoupons' => $myCoupons,
			'terms2' => $terms2,
			'interval' => $interval->days
		);

		self::render('payment-moongcleoffer', ['data' => $data]);
	}

	public static function success()
	{
		$payment = Payment::where('payment_type', $_GET['paymentType'])
			->where('payment_order_id', $_GET['orderId'])
			->first();

		if (empty($payment)) {
			parentGotoNewUrl('/payment/fail?message=PaymentIsNotExist&orderId=' . $payment->payment_order_id);
			// header('Location: /payment/fail?message=PaymentIsNotExist&orderId=' . $payment->payment_order_id);
			exit;
		}

		if ($_ENV['ONDA_ENV'] == 'production') {
			$ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
			$ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
		} else {
			$ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
			$ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
		}

		$payment->payment_key = $_GET['paymentKey'];
		$payment->payment_amount = $_GET['amount'];
		$payment->updated_at = Carbon::now();
		$payment->save();

		$paymentItems = $payment->items;

		if (empty($paymentItems)) {
			// parentGotoNewUrl('/payment/fail?message=PaymentItemIsNotExist&orderId=' . $payment->payment_order_id);
			header('Location: /payment/fail?message=PaymentItemIsNotExist&orderId=' . $payment->payment_order_id);
			exit;
		}

		// 토스페이먼츠 API는 시크릿 키를 사용자 ID로 사용하고, 비밀번호는 사용하지 않습니다.
		// 비밀번호가 없다는 것을 알리기 위해 시크릿 키 뒤에 콜론을 추가합니다.

		if ($_ENV['APP_ENV'] == 'production') {
			$widgetSecretKey = $_ENV['TOSS_LIVE_SECRET'];
			$credential = base64_encode($widgetSecretKey . ':');
		} else {
			$widgetSecretKey = $_ENV['TOSS_TEST_SECRET'];
			$credential = base64_encode($widgetSecretKey . ':');
		}

		$paymentBody = [
			'paymentKey' => $_GET['paymentKey'] ?? '',
			'orderId' => $_GET['orderId'] ?? '',
			'amount' => $_GET['amount'] ?? ''
		];

		// 결제를 승인하면 결제수단에서 금액이 차감돼요.
		$url = 'https://api.tosspayments.com/v1/payments/confirm';
		$curlHandle = curl_init($url);
		curl_setopt_array($curlHandle, [
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => [
				'Authorization: Basic ' . $credential,
				'Content-Type: application/json'
			],
			CURLOPT_POSTFIELDS => json_encode($paymentBody)
		]);

		curl_exec($curlHandle);
		$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);

		if ($httpCode != 200) {
			// parentGotoNewUrl('/payment/fail?message=PaymentFailed&orderId=' . $payment->payment_order_id);
			header('Location: /payment/fail?message=PaymentFailed&orderId=' . $payment->payment_order_id);
			exit;
		}

		if (!empty($payment->coupon_user_idx)) {
			$couponUser = CouponUser::find($payment->coupon_user_idx);
			$couponUser->used_at = Carbon::now();
			$couponUser->is_used = true;
			$couponUser->is_active = false;
			$couponUser->save();

			$coupon = Coupon::find($couponUser->coupon_idx);
			$coupon->used_count = $coupon->used_count + 1;
			$coupon->save();
		}

		try {
			foreach ($paymentItems as $paymentItem) {
				$partner = Partner::find($paymentItem->partner_idx);

				if ($paymentItem->thirdparty_type === 'onda') {
					$sql = "
						SELECT
							p.partner_onda_idx,
							r.room_onda_idx,
							r.room_standard_person,
							rr.rateplan_onda_idx
						FROM
							room_rateplan rr
						LEFT JOIN partners p ON p.partner_idx = rr.partner_idx
						LEFT JOIN rooms r ON r.room_idx = rr.room_idx
						WHERE
							rr.room_rateplan_idx = :productIdx
					";

					$productIdx = $paymentItem->product_idx;

					if ($paymentItem->product_type == 'moongcledeal') {
						$moongcleoffer = MoongcleOffer::find($paymentItem->product_idx);

						if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
							$productIdx = $moongcleoffer->base_product_idx;
						}
					}

					$bindings = [
						'productIdx' => $productIdx
					];

					$ondaIds = Database::getInstance()->getConnection()->select($sql, $bindings);
					$ondaIds = $ondaIds[0];

					try {
						$client = new Client();

						$response = $client->request('GET', $ondaDomain . '/properties/' . $ondaIds->partner_onda_idx . '/roomtypes/' . $ondaIds->room_onda_idx . '/rateplans/' . $ondaIds->rateplan_onda_idx . '/checkavail?checkin=' . $paymentItem->start_date . '&checkout=' . $paymentItem->end_date, [
							'headers' => [
								'Authorization' => $ondaKey,
								'accept' => 'application/json',
							],
						]);

						$availResponse = json_decode($response->getBody(), true);
						$availability = $availResponse['availability'];
					} catch (RequestException $e) {
						// parentGotoNewUrl('/payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
						header('Location: /payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
						exit;
					}

					if (!$availability) {
						// parentGotoNewUrl('/payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
						header('Location: /payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
						exit;
					}

					$guests = $paymentItem->reservation_personnel;

					$totalGuestNumber = $guests['adult'];

					if (isset($guests['child']) && $guests['child'] > 0) {
						$totalGuestNumber += $guests['child'];
					}
					if (isset($guests['infant']) && $guests['infant'] > 0) {
						$totalGuestNumber += $guests['infant'];
					}

					$numberOfGuest = [
						"adult" => $guests['adult'],
						"child_age" => []
					];

					if ($totalGuestNumber <= $ondaIds->room_standard_person) {
						if (isset($guests['child']) && $guests['child'] > 0) {
							$numberOfGuest['child_age'] = array_values($guests['childAge']);
						}

						if (isset($guests['infant']) && $guests['infant'] > 0) {
							$infantAges = array_fill(0, $guests['infant'], 1); // 유아 수만큼 1살 추가
							$numberOfGuest['child_age'] = array_merge($numberOfGuest['child_age'], $infantAges);
						}
					} else {
						$numberOfGuest = [
							"adult" => $ondaIds->room_standard_person,
							"child_age" => []
						];
					}

					$startDate = new \DateTime($paymentItem->start_date);
					$startDate = $startDate->format("Y-m-d");

					$endDate = new \DateTime($paymentItem->end_date);
					$endDate = $endDate->format("Y-m-d");

					$data = [
						"currency" => "KRW",
						"channel_booking_number" => $paymentItem->reservation_pending_code,
						"checkin" => $startDate,
						"checkout" => $endDate,
						"booker" => [
							"name" => $payment->reservation_name,
							"email" => $payment->reservation_email,
							"phone" => $payment->reservation_phone,
							"nationality" => "KR",
							"timezone" => "Asia/Seoul"
						],
						"guests" => [
							[
								"name" => $payment->visit_name,
								"email" => $payment->visit_email,
								"phone" => $payment->visit_phone,
								"nationality" => "KR"
							]
						],
						"rateplans" => [
							[
								"rateplan_id" => $ondaIds->rateplan_onda_idx,
								"amount" => intval($paymentItem->item_origin_sale_price),
								"number_of_guest" => $numberOfGuest,
								"guests" => [
									[
										"name" => $payment->visit_name,
										"email" => $payment->visit_email,
										"phone" => $payment->visit_phone,
										"nationality" => "KR"
									]
								]
							]
						]
					];

					$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

					try {
						$response = $client->request('POST', $ondaDomain . '/properties/' . $ondaIds->partner_onda_idx . '/bookings', [
							'body' => $jsonData,
							'headers' => [
								'Authorization' => $ondaKey,
								'accept' => 'application/json',
								'content-type' => 'application/json',
							],
						]);

						$reservationResult = json_decode($response->getBody(), true);
					} catch (RequestException $e) {
						// parentGotoNewUrl('/payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
						header('Location: /payment/fail?message=RoomPriceFalse&orderId=' . $payment->payment_order_id);
						exit;
					}

					if ($reservationResult['status'] !== 'confirmed') {
						// parentGotoNewUrl('/payment/fail?message=ReservationConfirmFailed&orderId=' . $payment->payment_order_id);
						header('Location: /payment/fail?message=ReservationConfirmFailed&orderId=' . $payment->payment_order_id);
						exit;
					}

					$paymentItem->reservation_confirmed_code = $reservationResult['booking_number'];
					$paymentItem->reservation_status = $reservationResult['status'];
					$paymentItem->refund_policy = $reservationResult['rateplans'][0]['refund_policy'];
					$paymentItem->updated_at = Carbon::now();
					$paymentItem->save();

					// $dateArray = [];
					// $currentDate = new \DateTime($startDate);
					// $endBoundary = new \DateTime($endDate);

					// while ($currentDate < $endBoundary) {
					// 	$dateArray[] = $currentDate->format("Y-m-d");
					// 	$currentDate->modify("+1 day");
					// }

					// if ($paymentItem->product_type == 'room_rateplan') {
					// 	$deduction = $paymentItem->quantity;

					// 	RoomInventory::where('room_rateplan_idx', $paymentItem->product_idx)
					// 		->whereIn('inventory_date', $dateArray)
					// 		->get()
					// 		->each(function ($inventory) use ($deduction) {
					// 			$inventory->inventory_quantity -= $deduction;
					// 			$inventory->save();
					// 		});
					// }
				} else if ($paymentItem->thirdparty_type === 'sanha') {
					$result = SanhaIntegrationService::postReservation($paymentItem, 'Book');

					if ($result['status'] !== 1) {
						header('Location: /payment/fail?message=BookingFailed&orderId=' . $payment->payment_order_id);
						exit;
					}

					$totalPrice = 0;

					$productIdx = $paymentItem->product_idx;
					if ($paymentItem->product_type == 'moongcledeal') {
						$moongcleoffer = MoongcleOffer::find($paymentItem->product_idx);
						if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
							$productIdx = $moongcleoffer->base_product_idx;
						}
					}
					$roomRateplan = RoomRateplan::find($productIdx);

					$startDate = new \DateTime($paymentItem->start_date);
					$endDate = new \DateTime($paymentItem->end_date);
					$endDate->modify('-1 day');

					for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
						if ($paymentItem->product_type == 'room_rateplan') {
							$roomPrice = RoomPrice::where('room_rateplan_idx', $paymentItem->product_idx)
								->where('room_price_date', $date->format('Y-m-d'))
								->first();

							$totalPrice += $roomPrice->room_price_sale * $paymentItem->quantity;

							$roomInventory = RoomInventory::where('room_idx', $roomRateplan->room_idx)
								->where('inventory_date', $date->format('Y-m-d'))
								->first();

							if ($paymentItem->quantity > $roomInventory->inventory_quantity) {
								header('Location: /payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
								exit;
							}
						}
					}

					if ($paymentItem->product_type == 'room_rateplan') {
						if ($totalPrice != $paymentItem->item_sale_price) {
							header('Location: /payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
							exit;
						}
					}

					for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
						if ($paymentItem->product_type == 'room_rateplan' || $paymentItem->product_type == 'moongcledeal') {
							$inventoryQuery = RoomInventory::where('room_idx', $roomRateplan->room_idx)
								->where('inventory_date', $date->format('Y-m-d'));
							//재고 줄이는 로직
							$inventoryQuery->decrement('inventory_quantity', $paymentItem->quantity);
							$inventoryQuery->increment('inventory_sold_quantity', $paymentItem->quantity);
						}
					}

					$sql = "
						SELECT
							c.*
						FROM cancel_rules c
						WHERE 
							c.partner_idx = :partnerIdx
						ORDER BY 
							c.cancel_rules_day DESC;
					";

					$bindings = [
						'partnerIdx' => $partner->partner_idx,
					];

					$cancelRules = Database::getInstance()->getConnection()->select($sql, $bindings);

					$refundPolicy = [];
					foreach ($cancelRules as $rule) {
						// 환불 규칙의 적용 날짜 계산
						$untilDate = new \DateTime($paymentItem->start_date);
						if (empty($rule->cancel_rules_time)) {
							$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime(23, 59, 59);
						} else {
							list($hours, $minutes, $seconds) = explode(':', $rule->cancel_rules_time);
							$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime($hours, $minutes, $seconds);
						}

						$refundAmount = $paymentItem->item_sale_price * ($rule->cancel_rules_percent / 100);

						// 환불 정책 데이터 형식화
						$refundPolicy[] = (object)[
							'until' => $untilDate->format(\DateTime::RFC3339),
							'percent' => $rule->cancel_rules_percent,
							'refund_amount' => $refundAmount,
							'charge_amount' => $paymentItem->item_sale_price - $refundAmount,
						];
					}

					$paymentItem->reservation_status = 'confirmed';
					$paymentItem->refund_policy = $refundPolicy;
					$paymentItem->updated_at = Carbon::now();
					$paymentItem->save();
				} else if ($paymentItem->thirdparty_type === 'tl') {
				} else {
					$totalPrice = 0;

					$productIdx = $paymentItem->product_idx;
					if ($paymentItem->product_type == 'moongcledeal') {
						$moongcleoffer = MoongcleOffer::find($paymentItem->product_idx);
						if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
							$productIdx = $moongcleoffer->base_product_idx;
						}
					}
					$roomRateplan = RoomRateplan::find($productIdx);

					$startDate = new \DateTime($paymentItem->start_date);
					$endDate = new \DateTime($paymentItem->end_date);
					$endDate->modify('-1 day');

					for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
						if ($paymentItem->product_type == 'room_rateplan') {
							$roomPrice = RoomPrice::where('room_rateplan_idx', $paymentItem->product_idx)
								->where('room_price_date', $date->format('Y-m-d'))
								->first();

							$totalPrice += $roomPrice->room_price_sale * $paymentItem->quantity;

							$roomInventory = RoomInventory::where('room_idx', $roomRateplan->room_idx)
								->where('inventory_date', $date->format('Y-m-d'))
								->first();

							if ($paymentItem->quantity > $roomInventory->inventory_quantity) {
								header('Location: /payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
								exit;
							}
						}
					}

					if ($paymentItem->product_type == 'room_rateplan') {
						if ($totalPrice != $paymentItem->item_sale_price) {
							header('Location: /payment/fail?message=RoomAvailabilityFalse&orderId=' . $payment->payment_order_id);
							exit;
						}
					}

					for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
						if ($paymentItem->product_type == 'room_rateplan' || $paymentItem->product_type == 'moongcledeal') {
							$inventoryQuery = RoomInventory::where('room_idx', $roomRateplan->room_idx)
								->where('inventory_date', $date->format('Y-m-d'));
							//재고 줄이는 로직
							$inventoryQuery->decrement('inventory_quantity', $paymentItem->quantity);
							$inventoryQuery->increment('inventory_sold_quantity', $paymentItem->quantity);
						}
					}

					$sql = "
						SELECT
							c.*
						FROM cancel_rules c
						WHERE 
							c.partner_idx = :partnerIdx
						ORDER BY 
							c.cancel_rules_day DESC;
					";

					$bindings = [
						'partnerIdx' => $partner->partner_idx,
					];

					$cancelRules = Database::getInstance()->getConnection()->select($sql, $bindings);

					$refundPolicy = [];
					foreach ($cancelRules as $rule) {
						// 환불 규칙의 적용 날짜 계산
						$untilDate = new \DateTime($paymentItem->start_date);
						if (empty($rule->cancel_rules_time)) {
							$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime(23, 59, 59);
						} else {
							list($hours, $minutes, $seconds) = explode(':', $rule->cancel_rules_time);
							$untilDate->modify('-' . $rule->cancel_rules_day . ' days')->setTime($hours, $minutes, $seconds);
						}

						$refundAmount = $paymentItem->item_sale_price * ($rule->cancel_rules_percent / 100);

						// 환불 정책 데이터 형식화
						$refundPolicy[] = (object)[
							'until' => $untilDate->format(\DateTime::RFC3339),
							'percent' => $rule->cancel_rules_percent,
							'refund_amount' => $refundAmount,
							'charge_amount' => $paymentItem->item_sale_price - $refundAmount,
						];
					}

					$paymentItem->reservation_confirmed_code = $paymentItem->reservation_pending_code;
					$paymentItem->reservation_status = 'confirmed';
					$paymentItem->refund_policy = $refundPolicy;
					$paymentItem->updated_at = Carbon::now();
					$paymentItem->save();
				}

				$voucher = new Voucher();
				$voucher->user_idx = $payment->user_idx;
				$voucher->item_idx = $paymentItem->payment_item_idx;
				$voucher->item_type = 'paymentItem';
				$voucher->item_status = 'booking';
				$voucher->send_type = 'email';
				$voucher->status = 'pending';
				$voucher->save();

				$voucher = new Voucher();
				$voucher->user_idx = $payment->user_idx;
				$voucher->item_idx = $paymentItem->payment_item_idx;
				$voucher->item_type = 'paymentItem';
				$voucher->item_status = 'booking';
				$voucher->send_type = 'kakao';
				$voucher->status = 'pending';
				$voucher->save();

				if ($partner->partner_thirdparty == 'custom' || $partner->partner_thirdparty == 'sanha') {
					$voucher = new Voucher();
					$voucher->user_idx = $payment->user_idx;
					$voucher->item_idx = $paymentItem->payment_item_idx;
					$voucher->item_type = 'paymentItem';
					$voucher->item_status = 'booking';
					$voucher->send_type = 'partnerEmail';
					$voucher->status = 'pending';
					$voucher->save();
				}
			}
		} catch (\Exception $e) {
            header('Location: /payment/fail?message=ReservationFailUnkownIssue&orderId=' . $payment->payment_order_id);
            exit;
		}

		$payment->payment_status = 'paid';
		$payment->save();

		foreach ($paymentItems as $paymentItem) {
			$paymentItem->payment_status = 'paid';
			$paymentItem->save();
		}

		// parentGotoNewUrl('/my/reservations');
		header('Location: /my/reservations');
		exit;
	}

	public static function fail()
	{
		$deviceType = getDeviceType();

		$data = array(
			'deviceType' => $deviceType
		);

		if ($_GET['message'] == 'RoomPriceFalse') {
			$data['message'] = '요금이 업데이트되어 수정 반영 중으로 잠시 예약이 어렵습니다. 잠시 후 예약을 시도해주시거나 고객센터로 문의 부탁드립니다.';
		} else {
			$data['message'] = '객실 마감 또는 알 수 없는 문제로 인하여 예약이 완료되지 않았습니다. 잠시 후 예약을 시도해주시거나 고객센터로 문의 부탁 드립니다.';
		}

		if (!empty($_GET['orderId'])) {
			$payment = Payment::where('payment_order_id', $_GET['orderId'])
				->first();

			if (!empty($payment)) {
				$payment->payment_error_code = !empty($_GET['code']) ? $_GET['code'] : '';
				$payment->payment_error_message = !empty($_GET['message']) ? $_GET['message'] : '';
				$payment->updated_at = Carbon::now();
				$payment->save();

				if (
					$_GET['message'] == 'RoomAvailabilityFalse' ||
					$_GET['message'] == 'RoomPriceFalse' ||
					$_GET['message'] == 'ReservationConfirmFailed' ||
					$_GET['message'] == 'ReservationFailUnkownIssue' ||
					$_GET['message'] == 'BookingFailed'
				) {
					$curl = curl_init();

					if ($_ENV['APP_ENV'] == 'production') {
						$widgetSecretKey = $_ENV['TOSS_LIVE_SECRET'];
						$credential = base64_encode($widgetSecretKey . ':');
					} else {
						$widgetSecretKey = $_ENV['TOSS_TEST_SECRET'];
						$credential = base64_encode($widgetSecretKey . ':');
					}

					$postData = json_encode([
						'cancelReason' => '예약 불가로 인한 취소'
					]);

					curl_setopt_array($curl, [
						CURLOPT_URL => "https://api.tosspayments.com/v1/payments/" . $payment->payment_key . "/cancel",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => $postData,
						CURLOPT_HTTPHEADER => [
							"Authorization: Basic " . $credential,
							"Content-Type: application/json"
						],
					]);

					$response = curl_exec($curl);
					$err = curl_error($curl);

					curl_close($curl);

					$paymentStatus = 'canceled';
					$totalRefundPrice = $payment->payment_sale_amount;

					if ($err) {
						$paymentStatus = 'error_canceled';
						$totalRefundPrice = 0;
					}

					$payment->payment_status = $paymentStatus;
					$payment->refund_amount = $totalRefundPrice;
					$payment->updated_at = Carbon::now();
					$payment->save();

					if (!empty($payment->coupon_user_idx)) {
						$couponUser = CouponUser::find($payment->coupon_user_idx);
						$couponUser->used_at = null;
						$couponUser->is_used = false;
						$couponUser->is_active = true;
						$couponUser->save();

						$coupon = Coupon::find($couponUser->coupon_idx);
						$coupon->used_count = $coupon->used_count - 1;
						$coupon->save();
					}
				}
			}
		}

		self::render('payment-stay-fail', ['data' => $data]);
	}

	private static function render($view, $data = [])
	{
		extract($data);
		require "../app/Views/app/{$view}.php";
	}
}
