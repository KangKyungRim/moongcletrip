<?php

namespace App\Controllers\Api;

use GuzzleHttp\Client;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\RoomRateplan;
use App\Models\RoomPrice;
use App\Models\RoomInventory;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\MoongcleOffer;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Voucher;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\EmailService;

use App\Services\SanhaIntegrationService;

use Carbon\Carbon;

class PaymentApiController
{
	public static function generateUniqueReservationNumber()
	{
		do {
			// 예약 번호 생성 (임의의 형식)
			$reservationNumber = generateRandomReservationNumber();

			// 중복 확인
			$numberExist = PaymentItem::where('reservation_number', $reservationNumber)->exists();
		} while ($numberExist);

		return $reservationNumber;
	}

	public static function prepare()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		try {
			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			$user->reservation_name = $_POST['payment']['reservation_name'];
			$user->reservation_phone = $_POST['payment']['reservation_phone'];
			$user->reservation_email = $_POST['payment']['reservation_email'];
			$user->save();

			if (!empty($_POST['selectedCoupon']['coupon_user_idx'])) {
				$couponUser = CouponUser::find($_POST['selectedCoupon']['coupon_user_idx']);
				if ($couponUser->discount_amount != $_POST['selectedCoupon']['discount_amount']) {
					return ResponseHelper::jsonResponse(['error' => '잘못된 접근입니다.'], 500);
				}
			}

			$payment = new Payment();
			$payment->travel_cart_idx = null;
			$payment->user_idx = $user->user_idx;
			$payment->payment_unique_code = generateRandomKey();
			$payment->payment_total_amount = $_POST['payment']['payment_total_amount'];
			$payment->payment_sale_amount = $_POST['payment']['payment_sale_amount'];
			$payment->used_point_amount = $_POST['payment']['used_point_amount'];
			$payment->payment_status = 'pending';
			$payment->payment_method = 'TOSS';
			$payment->payment_name = count($_POST['paymentItems']) > 1 ? $_POST['paymentItems'][0]['product_name'] . ' 외 ' . (count($_POST['paymentItems']) - 1) : $_POST['paymentItems'][0]['product_name'];
			$payment->payment_key = null;
			$payment->payment_type = $_POST['payment']['payment_type'];
			$payment->payment_order_id = generateRandomOrderId();
			$payment->payment_amount = null;
			$payment->payment_error_code = null;
			$payment->payment_error_message = null;
			$payment->reservation_name = $_POST['payment']['reservation_name'];
			$payment->reservation_phone = $_POST['payment']['reservation_phone'];
			$payment->reservation_email = $_POST['payment']['reservation_email'];
			$payment->visit_name = $_POST['payment']['visit_name'];
			$payment->visit_phone = $_POST['payment']['visit_phone'];
			$payment->visit_email = $_POST['payment']['visit_email'];
			$payment->visit_way = $_POST['payment']['visit_way'];
			$payment->coupon_user_idx = !empty($_POST['selectedCoupon']['coupon_user_idx']) ? $_POST['selectedCoupon']['coupon_user_idx'] : null;
			$payment->coupon_discount_amount = !empty($_POST['selectedCoupon']['discount_amount']) ? $_POST['selectedCoupon']['discount_amount'] : 0;
			$payment->coupon_name = !empty($_POST['selectedCoupon']['coupon_name']) ? $_POST['selectedCoupon']['coupon_name'] : '';
			$payment->refund_point_amount = 0;
			$payment->save();

			foreach ($_POST['paymentItems'] as $item) {
				$reservationNumber = self::generateUniqueReservationNumber();

				$paymentItem = new PaymentItem();
				$paymentItem->user_idx = $user->user_idx;
				$paymentItem->payment_idx = $payment->payment_idx;
				$paymentItem->cart_item_idx = null;
				$paymentItem->partner_idx = $item['partner_idx'];
				$paymentItem->product_idx = $item['product_idx'];
				$paymentItem->product_category = 'stay';
				$paymentItem->product_type = $item['product_type'];
				$paymentItem->product_name = $item['product_name'];
				$paymentItem->product_partner_name = $item['product_partner_name'];
				$paymentItem->product_detail_name = $item['product_detail_name'];
				$paymentItem->product_benefits = !empty($item['product_benefits']) ? json_decode($item['product_benefits']) : null;
				$paymentItem->datewise_product_data = json_decode($item['datewise_product_data']);
				$paymentItem->item_basic_price = $item['item_basic_price'];
				$paymentItem->item_sale_price = $item['item_sale_price'];
				$paymentItem->item_origin_sale_price = $item['item_origin_sale_price'];
				$paymentItem->quantity = $item['quantity'];
				$paymentItem->reservation_number = $reservationNumber;
				$paymentItem->reservation_pending_code = $reservationNumber;
				$paymentItem->reservation_confirmed_code = null;
				$paymentItem->start_date = $item['start_date'];
				$paymentItem->end_date = $item['end_date'];
				$paymentItem->free_cancel_date = $item['free_cancel_date'];
				$paymentItem->refundable = $item['refundable'];
				$paymentItem->reservation_personnel = json_decode($item['reservation_personnel']);
				$paymentItem->payment_status = 'pending';
				$paymentItem->reservation_status = 'pending';
				$paymentItem->thirdparty_type = $item['thirdparty_type'];
				$paymentItem->canceled_quantity = 0;
				$paymentItem->canceled_amount = 0;
				$paymentItem->save();
			}

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '결제 정보가 성공적으로 저장되었습니다.',
				'orderId' => $payment->payment_order_id,
				'orderName' => $payment->payment_name,
				'customerEmail' => $payment->reservation_email,
				'customerName' => $payment->reservation_name,
				'customerMobilePhone' => $payment->reservation_phone,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function cancel()
	{
		if ($_ENV['ONDA_ENV'] == 'production') {
			$ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
			$ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
		} else {
			$ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
			$ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
		}

		$checkUser = MiddleHelper::checkLoginCookie();

		$input = json_decode(file_get_contents("php://input"), true);

		$payment = Payment::find($input['cancelPaymentIdx']);

		if ($payment->user_idx != $checkUser->user_idx) {
			return ResponseHelper::jsonResponse(['error' => 'User does not match'], 500);
		}

		$totalRefundPrice = 0;

		foreach ($input['cancelItems'] as $itemIdx) {
			$item = PaymentItem::find($itemIdx);
			$partner = Partner::find($item->partner_idx);

			$today = new \DateTime();
			$nextPercent = null;

			foreach ($item->refund_policy as $refund) {
				$refundDate = new \DateTime($refund['until']);

				if ($refundDate > $today) {
					$nextPercent = $refund['percent'];
					break;
				}
			}

			$salePrice = $item->item_sale_price;

			if ($item->thirdparty_type == 'onda') {
				$salePrice = $item->item_origin_sale_price;
			}
			$refundPrice = $salePrice * ($nextPercent / 100);

			if ($item->thirdparty_type == 'onda') {
				$client = new Client();

				$data = [
					"canceled_by" => "user",
					"reason" => "고객 개인 사정으로 인한 여행 취소",
					"currency" => "KRW",
					"total_amount" => $salePrice,
					"refund_amount" => $refundPrice
				];

				$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

				$response = $client->request('PUT', $ondaDomain . '/properties/' . $partner->partner_onda_idx . '/bookings/' . $item->reservation_confirmed_code . '/cancel', [
					'body' => $jsonData,
					'headers' => [
						'Authorization' => $ondaKey,
						'accept' => 'application/json',
						'content-type' => 'application/json',
					],
				]);

				$cancelResult = json_decode($response->getBody(), true);

				if ($cancelResult['channel_booking_number'] != $item->reservation_pending_code) {
					return ResponseHelper::jsonResponse(['error' => 'Booking code does not match'], 500);
				}
			} else if ($item->thirdparty_type === 'sanha') {
				$result = SanhaIntegrationService::postReservation($item, 'Cancel');

				if ($result['status'] !== 1) {
					parentGotoNewUrl('/my/cancel-reservation/fail');
					exit;
				}

				$productIdx = $item->product_idx;
				if ($item->product_type == 'moongcledeal') {
					$moongcleoffer = MoongcleOffer::find($item->product_idx);
					if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
						$productIdx = $moongcleoffer->base_product_idx;
					}
				}
				$roomRateplan = RoomRateplan::find($productIdx);

				$startDate = new \DateTime($item->start_date);
				$endDate = new \DateTime($item->end_date);
				$endDate->modify('-1 day');

				for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
					if ($item->product_type == 'room_rateplan' || $item->product_type == 'moongcledeal') {
						$inventoryQuery = RoomInventory::where('room_idx', $roomRateplan->room_idx)
							->where('inventory_date', $date->format('Y-m-d'));
						//재고 늘리는 로직
						$inventoryQuery->increment('inventory_quantity', $item->quantity);
						$inventoryQuery->decrement('inventory_sold_quantity', $item->quantity);
					}
				}
			} else if ($item->thirdparty_type === 'tl') {
			} else {
				$productIdx = $item->product_idx;
				if ($item->product_type == 'moongcledeal') {
					$moongcleoffer = MoongcleOffer::find($item->product_idx);
					if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
						$productIdx = $moongcleoffer->base_product_idx;
					}
				}
				$roomRateplan = RoomRateplan::find($productIdx);

				$startDate = new \DateTime($item->start_date);
				$endDate = new \DateTime($item->end_date);
				$endDate->modify('-1 day');

				for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
					if ($item->product_type == 'room_rateplan' || $item->product_type == 'moongcledeal') {
						$inventoryQuery = RoomInventory::where('room_idx', $roomRateplan->room_idx)
							->where('inventory_date', $date->format('Y-m-d'));
						//재고 늘리는 로직
						error_log('[재고]CUSTOM:'.$item->product_type);
						$inventoryQuery->increment('inventory_quantity', $item->quantity);
						$inventoryQuery->decrement('inventory_sold_quantity', $item->quantity);
					}
				}
			}

			$item->reservation_status = 'canceled';
			$item->payment_status = 'canceled';
			$item->canceled_quantity = $item->quantity;
			$item->canceled_amount = $refundPrice;
			$item->canceled_reason = $input['reason'];
			$item->updated_at = Carbon::now();
			$item->save();

			$voucher = new Voucher();
			$voucher->user_idx = $payment->user_idx;
			$voucher->item_idx = $item->payment_item_idx;
			$voucher->item_type = 'paymentItem';
			$voucher->item_status = 'cancel';
			$voucher->send_type = 'email';
			$voucher->status = 'pending';
			$voucher->save();

			$voucher = new Voucher();
			$voucher->user_idx = $payment->user_idx;
			$voucher->item_idx = $item->payment_item_idx;
			$voucher->item_type = 'paymentItem';
			$voucher->item_status = 'cancel';
			$voucher->send_type = 'kakao';
			$voucher->status = 'pending';
			$voucher->save();

			if ($partner->partner_thirdparty == 'custom' || $partner->partner_thirdparty == 'sanha') {
				$voucher = new Voucher();
				$voucher->user_idx = $payment->user_idx;
				$voucher->item_idx = $item->payment_item_idx;
				$voucher->item_type = 'paymentItem';
				$voucher->item_status = 'cancel';
				$voucher->send_type = 'partnerEmail';
				$voucher->status = 'pending';
				$voucher->save();
			}

			$totalRefundPrice = $payment->payment_sale_amount * ($nextPercent / 100);
		}

		$curl = curl_init();

		if ($_ENV['APP_ENV'] == 'production') {
			$widgetSecretKey = $_ENV['TOSS_LIVE_SECRET'];
			$credential = base64_encode($widgetSecretKey . ':');
		} else {
			$widgetSecretKey = $_ENV['TOSS_TEST_SECRET'];
			$credential = base64_encode($widgetSecretKey . ':');
		}

		$paymentStatus = 'canceled';
		if ($totalRefundPrice == $payment->payment_sale_amount) {
			$postData = json_encode([
				'cancelReason' => '고객이 취소를 원함'
			]);
		} else {
			$postData = json_encode([
				'cancelReason' => '고객이 취소를 원함',
				'cancelAmount' => $totalRefundPrice
			]);
			$paymentStatus = 'partial_canceled';
		}

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

		if ($err) {
			parentGotoNewUrl('/my/cancel-reservation/fail');
			// header('Location: /my/cancel-reservation/fail');
		} else {
			$responseArray = json_decode($response, true);

			if ($responseArray['status'] == 'CANCELED' || $responseArray['status'] == 'PARTIAL_CANCELED') {
				$payment->payment_status = $paymentStatus;
				$payment->refund_amount = $responseArray['cancels'][0]['cancelAmount'];
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

				// 성공 응답
				return ResponseHelper::jsonResponse([
					'message' => '결제가 취소되었습니다.',
					'success' => true
				], 200);
			} else {
				header('Location: /my/cancel-reservation-fail?paymentIdx=' . $payment->payment_idx);
			}
		}
	}
}
