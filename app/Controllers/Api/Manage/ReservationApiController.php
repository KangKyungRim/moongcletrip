<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\RoomRateplan;
use App\Models\RoomInventory;
use App\Models\RoomPrice;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Voucher;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use App\Services\SanhaIntegrationService;

use GuzzleHttp\Client;

use Carbon\Carbon;
use Database;

class ReservationApiController
{
	public static function changeReservationCode()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['paymentItemIdx']) || empty($input['code'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		$paymentItem = PaymentItem::find($input['paymentItemIdx']);
		$paymentItem->reservation_confirmed_code = $input['code'];
		$paymentItem->save();

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '코드를 수정했습니다.',
			'data' => [
				'paymentItemIdx' => $paymentItem->payment_item_idx
			]
		], 200);
	}

	public static function resendReservation()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['paymentIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		$payment = Payment::where('payment_idx', $input['paymentIdx'])
			->first();

		$paymentItems = $payment->items;

		foreach ($paymentItems as $paymentItem) {
			if ($paymentItem->thirdparty_type === 'sanha') {
				$partner = Partner::find($paymentItem->partner_idx);

				$result = SanhaIntegrationService::postReservation($paymentItem, 'Book');

				if ($result['status'] != 1 && $result['status'] != 'W301') {
					return ResponseHelper::jsonResponse([
						'result' => $result['status'],
						'success' => false,
						'message' => '예약 재전송에 실패했습니다.',
						'data' => [
							'paymentItemIdx' => $paymentItem->payment_item_idx
						]
					], 200);
				}

				$totalPrice = 0;

				$roomRateplan = RoomRateplan::find($paymentItem->product_idx);

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

						// if ($paymentItem->quantity > $roomInventory->inventory_quantity) {
						// 	return ResponseHelper::jsonResponse([
						// 		'result' => $result['status'],
						// 		'success' => false,
						// 		'message' => '예약 재전송에 실패했습니다.',
						// 		'data' => [
						// 			'error' => 'quantity',
						// 			'paymentItemIdx' => $paymentItem->payment_item_idx
						// 		]
						// 	], 200);
						// }
					}
				}

				if ($paymentItem->product_type == 'room_rateplan') {
					// if ($totalPrice != $paymentItem->item_sale_price) {
					// 	return ResponseHelper::jsonResponse([
					// 		'result' => $result['status'],
					// 		'success' => false,
					// 		'message' => '예약 재전송에 실패했습니다.',
					// 		'data' => [
					// 			'error' => 'price',
					// 			'paymentItemIdx' => $paymentItem->payment_item_idx
					// 		]
					// 	], 200);
					// }
				}

				for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
					if ($paymentItem->product_type == 'room_rateplan' || $paymentItem->product_type == 'moongcledeal') {
						$inventoryQuery = RoomInventory::where('room_idx', $roomRateplan->room_idx)
							->where('inventory_date', $date->format('Y-m-d'));
						error_log('[재고]reservationApi sanha:'.$paymentItem->product_type);
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

				$payment->payment_status = 'paid';
				$payment->save();

				foreach ($paymentItems as $paymentItem) {
					$paymentItem->payment_status = 'paid';
					$paymentItem->save();
				}
			}
		}

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '예약을 재전송했습니다.',
			'data' => [
				'paymentItemIdx' => $paymentItem->payment_item_idx
			]
		], 200);
	}

	public static function resendReservationCancel()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['paymentIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		$payment = Payment::where('payment_idx', $input['paymentIdx'])
			->first();

		$paymentItems = $payment->items;

		foreach ($paymentItems as $item) {
			if ($item->thirdparty_type === 'sanha') {
				$partner = Partner::find($item->partner_idx);

				$today = new \DateTime();
				$nextPercent = 100;

				$salePrice = $item->item_sale_price;
				$refundPrice = $salePrice * ($nextPercent / 100);

				$result = SanhaIntegrationService::postReservation($item, 'Cancel');

				if ($result['status'] != 1 && $result['status'] != 'W302') {
					return ResponseHelper::jsonResponse([
						'result' => $result['status'],
						'success' => false,
						'message' => '예약 재전송에 실패했습니다.',
						'data' => [
							'paymentItemIdx' => $paymentItem->payment_item_idx
						]
					], 200);
				}

				$roomRateplan = RoomRateplan::find($item->product_idx);

				$startDate = new \DateTime($item->start_date);
				$endDate = new \DateTime($item->end_date);
				$endDate->modify('-1 day');

				for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
					if ($item->product_type == 'room_rateplan' || $item->product_type == 'moongcledeal') {
						$inventoryQuery = RoomInventory::where('room_idx', $roomRateplan->room_idx)
							->where('inventory_date', $date->format('Y-m-d'));
						error_log('[재고]reservationApi sanha:'.$item->product_type);
						$inventoryQuery->increment('inventory_quantity', $item->quantity);
						$inventoryQuery->decrement('inventory_sold_quantity', $item->quantity);
					}
				}

				$item->reservation_status = 'canceled';
				$item->payment_status = 'canceled';
				$item->canceled_quantity = $item->quantity;
				$item->canceled_amount = $refundPrice;
				$item->canceled_reason = 'other';
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
					return ResponseHelper::jsonResponse([
						'result' => $result['status'],
						'success' => false,
						'message' => '예약 재전송에 실패했습니다.',
						'data' => [
							'error' => 'paymentCancel',
							'paymentItemIdx' => $paymentItem->payment_item_idx
						]
					], 200);
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
					} else {
						return ResponseHelper::jsonResponse([
							'result' => $result['status'],
							'success' => false,
							'message' => '예약 재전송에 실패했습니다.',
							'data' => [
								'error' => 'cancelSave',
								'paymentItemIdx' => $paymentItem->payment_item_idx
							]
						], 200);
					}
				}
			} else if ($item->thirdparty_type === 'onda') {
				if ($_ENV['ONDA_ENV'] == 'production') {
					$ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
					$ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
				} else {
					$ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
					$ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
				}

				$totalRefundPrice = 0;

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

				$totalRefundPrice = $payment->payment_sale_amount * ($nextPercent / 100);

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
					return ResponseHelper::jsonResponse([
						'success' => true,
						'message' => '예약 재전송에 문제가 발생했습니다.',
						'data' => [
							'paymentItemIdx' => $paymentItem->payment_item_idx
						]
					], 200);
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
					} else {
						return ResponseHelper::jsonResponse([
							'success' => true,
							'message' => '예약 재전송에 문제가 발생했습니다.',
							'data' => [
								'paymentItemIdx' => $paymentItem->payment_item_idx
							]
						], 200);
					}
				}
			} else {
				$totalRefundPrice = 0;

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
				$refundPrice = $salePrice * ($nextPercent / 100);

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

				$voucher = new Voucher();
				$voucher->user_idx = $payment->user_idx;
				$voucher->item_idx = $item->payment_item_idx;
				$voucher->item_type = 'paymentItem';
				$voucher->item_status = 'cancel';
				$voucher->send_type = 'partnerEmail';
				$voucher->status = 'pending';
				$voucher->save();

				$totalRefundPrice = $payment->payment_sale_amount * ($nextPercent / 100);

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
					return ResponseHelper::jsonResponse([
						'success' => true,
						'message' => '예약 재전송에 문제가 발생했습니다.',
						'data' => [
							'paymentItemIdx' => $paymentItem->payment_item_idx
						]
					], 200);
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
					} else {
						return ResponseHelper::jsonResponse([
							'success' => true,
							'message' => '예약 재전송에 문제가 발생했습니다.',
							'data' => [
								'paymentItemIdx' => $paymentItem->payment_item_idx
							]
						], 200);
					}
				}
			}
		}

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '예약을 재전송했습니다.',
			'data' => [
				'paymentItemIdx' => $paymentItem->payment_item_idx
			]
		], 200);
	}
}
