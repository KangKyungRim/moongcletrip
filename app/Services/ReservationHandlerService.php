<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\PaymentItem;
use App\Models\Voucher;

use Carbon\Carbon;

class ReservationHandlerService
{
	public function processReservationStatus()
	{
		$confirmedReservations = PaymentItem::where('reservation_status', 'confirmed')->where('payment_status', 'paid')->get();

		foreach ($confirmedReservations as $confirmedReservation) {
			$currentDateTime = new \DateTime('now', new \DateTimeZone('Asia/Seoul'));

			$startDateTime = new \DateTime($confirmedReservation->start_date, new \DateTimeZone('Asia/Seoul'));
			$startDateTime->setTime(10, 00);

			if ($currentDateTime > $startDateTime) {
				$voucherExist = Voucher::where('item_idx', $confirmedReservation->payment_item_idx)
					->where('user_idx', $confirmedReservation->user_idx)
					->where('item_type', 'paymentItem')
					->where('item_status', 'checkin')
					->first();

				if (empty($voucherExist)) {
					$voucher = new Voucher();
					$voucher->user_idx = $confirmedReservation->user_idx;
					$voucher->item_idx = $confirmedReservation->payment_item_idx;
					$voucher->item_type = 'paymentItem';
					$voucher->item_status = 'checkin';
					$voucher->send_type = 'kakao';
					$voucher->status = 'pending';
					$voucher->save();
				}
			}

			$endDateTime = new \DateTime($confirmedReservation->end_date, new \DateTimeZone('Asia/Seoul'));
			$endDateTime->setTime(11, 30);

			if ($currentDateTime > $endDateTime) {
				$confirmedReservation->reservation_status = 'completed';
				$confirmedReservation->save();

				$voucher = new Voucher();
				$voucher->user_idx = $confirmedReservation->user_idx;
				$voucher->item_idx = $confirmedReservation->payment_item_idx;
				$voucher->item_type = 'paymentItem';
				$voucher->item_status = 'checkout';
				$voucher->send_type = 'kakao';
				$voucher->status = 'pending';
				$voucher->save();
			}
		}
	}

	public function processCouponStatus()
	{
		$coupons = Coupon::where('end_date', '<', Carbon::today())
			->where('is_active', true)->get();

		foreach ($coupons as $coupon) {
			$coupon->is_active = false;
			$coupon->show_in_coupon_wallet = false;
			$coupon->save();
		}

		$userCoupons = CouponUser::where('end_date', '<', Carbon::today())
			->where('is_active', true)->get();

		foreach ($userCoupons as $userCoupon) {
			$userCoupon->is_active = false;
			$userCoupon->save();
		}
	}
}
