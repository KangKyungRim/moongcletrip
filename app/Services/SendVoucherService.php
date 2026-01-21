<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Partner;
use App\Models\Stay;
use App\Models\RoomPrice;
use App\Models\RoomRateplan;
use App\Services\EmailTemplateService;
use App\Services\SendMailService;
use App\Services\AligoAPIService;

class SendVoucherService
{
    public function processSendEmailVouchers()
    {
        $pendingVouchers = Voucher::where('status', 'pending')
            ->where('send_type', 'email')
            ->get();

        foreach ($pendingVouchers as $pendingVoucher) {
            if ($pendingVoucher->item_type == 'paymentItem') {
                $paymentItem = PaymentItem::find($pendingVoucher->item_idx);
                $payment = Payment::find($paymentItem->payment_idx);

                if ($paymentItem->product_category == 'stay') {
                    $partner = Partner::find($paymentItem->partner_idx);

                    $mailData = [
                        'reservation_email' => $payment->reservation_email,
                        'reservation_name' => $payment->reservation_name,
                        'start_date' => $paymentItem->start_date,
                        'end_date' => $paymentItem->end_date,
                        'partner_idx' => $paymentItem->partner_idx,
                        'partner_phonenumber' => $partner->partner_phonenumber,
                        'product_partner_name' => $paymentItem->product_partner_name,
                        'product_detail_name' => $paymentItem->product_detail_name,
                        'quantity' => $paymentItem->quantity,
                        'reservation_number' => $paymentItem->reservation_number,
                        'payment_sale_amount' => $payment->payment_sale_amount,
                        'refund_amount' => $payment->refund_amount,
                        'used_coupon' => !empty($payment->coupon_user_idx) ? 'Y' : 'N',
                        'coupon_name' => !empty($payment->coupon_name) ? $payment->coupon_name : '',
                        'product_type' => $paymentItem->product_type,
                        'product_idx' => $paymentItem->product_idx,
                    ];

                    if (!empty($paymentItem->product_benefits)) {
                        $benefitNames = array_column($paymentItem->product_benefits, 'benefit_name');
                        $mailData['product_detail_name'] .= ' - ' . implode(', ', $benefitNames);
                    }

                    if ($pendingVoucher->item_status == 'booking') {
                        $email = EmailTemplateService::reservationBookingTemplate($mailData);
                    } else {
                        $email = EmailTemplateService::reservationCancelTemplate($mailData);
                    }

                    if ($email) {
                        $result = SendMailService::sendMail($email);

                        if ($result) {
                            $pendingVoucher->status = 'send';
                        } else {
                            $pendingVoucher->status = 'fail';
                        }

                        $pendingVoucher->title = $email['subject'];
                        $pendingVoucher->message = $email['contents'];
                        $pendingVoucher->save();
                    }
                }
            }
        }
    }

    public function processSendKakaoVouchers()
    {
        $pendingVouchers = Voucher::where('status', 'pending')
            ->where('send_type', 'kakao')
            ->get();

        foreach ($pendingVouchers as $pendingVoucher) {
            if ($pendingVoucher->item_type == 'paymentItem') {
                $paymentItem = PaymentItem::find($pendingVoucher->item_idx);
                $payment = Payment::find($paymentItem->payment_idx);
                $partner = Partner::find($paymentItem->partner_idx);
                $stay = $partner->partnerDetail();

                if ($paymentItem->product_category == 'stay') {
                    $aligo = new AligoAPIService();
                    $token = $aligo->createToken();

                    $checkin = $stay->stay_checkin_rule;
                    $checkout = $stay->stay_checkout_rule;

                    // if (count($paymentItem->datewise_product_data) > 1) {
                    //     $roomPrice = RoomPrice::find($paymentItem->datewise_product_data[0]['sub_product_id']);
                    //     $checkin = $roomPrice->room_price_checkin;

                    //     $lastIndex = count($paymentItem->datewise_product_data) - 1;
                    //     $lastElement = $paymentItem->datewise_product_data[$lastIndex];
                    //     $roomPrice = RoomPrice::find($lastElement['sub_product_id']);
                    //     $checkout = $roomPrice->room_price_checkout;
                    // } else {
                    //     $roomPrice = RoomPrice::find($paymentItem->datewise_product_data[0]['sub_product_id']);

                    //     $checkin = $roomPrice->room_price_checkin;
                    //     $checkout = $roomPrice->room_price_checkout;
                    // }

                    $templateData = [
                        'type' => 'booking',
                        'reservation_phone' => $payment->reservation_phone,
                        'reservation_name' => $payment->reservation_name,
                        'product_partner_name' => $paymentItem->product_partner_name,
                        'product_detail_name' => $paymentItem->product_detail_name,
                        'partner_idx' => $paymentItem->partner_idx,
                        'partner_phonenumber' => $partner->partner_phonenumber,
                        'partner_address1' => $partner->partner_address1,
                        'partner_address2' => $partner->partner_address2,
                        'partner_address3' => $partner->partner_address3,
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        'quantity' => $paymentItem->quantity,
                        'start_date' => $paymentItem->start_date,
                        'end_date' => $paymentItem->end_date,
                        'reservation_number' => $paymentItem->reservation_number,
                        'payment_sale_amount' => $payment->payment_sale_amount,
                        'refund_amount' => $payment->refund_amount,
                        'used_coupon' => !empty($payment->coupon_user_idx) ? '사용함' : '사용안함',
                        'coupon_name' => !empty($payment->coupon_name) ? $payment->coupon_name : '없음',
                    ];

                    if (!empty($paymentItem->product_benefits)) {
                        $benefitNames = array_column($paymentItem->product_benefits, 'benefit_name');
                        $templateData['product_detail_name'] .= ' - ' . implode(', ', $benefitNames);
                    }

                    if ($pendingVoucher->item_status == 'cancel') {
                        $templateData['type'] = 'cancel';
                    } else if ($pendingVoucher->item_status == 'checkin') {
                        $templateData['type'] = 'checkin';
                    } else if ($pendingVoucher->item_status == 'checkout') {
                        $templateData['type'] = 'checkout';
                    }

                    $response = $aligo->sendTemplateMessage($token, $templateData);

                    if ($response['result']) {
                        $pendingVoucher->status = 'send';
                    } else {
                        $pendingVoucher->status = 'fail';
                    }

                    $pendingVoucher->title = '';
                    $pendingVoucher->message = $response['message'];

                    $pendingVoucher->save();
                }
            }
        }
    }

    public function processSendPartnerEmailVouchers()
    {
        $pendingVouchers = Voucher::where('status', 'pending')
            ->where('send_type', 'partnerEmail')
            ->get();

        foreach ($pendingVouchers as $pendingVoucher) {
            if ($pendingVoucher->item_type == 'paymentItem') {
                $paymentItem = PaymentItem::find($pendingVoucher->item_idx);
                $payment = Payment::find($paymentItem->payment_idx);

                if ($paymentItem->product_category == 'stay') {
                    $partner = Partner::find($paymentItem->partner_idx);
                    $stay = Stay::find($partner->partner_detail_idx);

                    $mailData = [
                        'reservation_email' => $payment->reservation_email,
                        'reservation_phone' => $payment->reservation_phone,
                        'reservation_name' => $payment->reservation_name,
                        'start_date' => $paymentItem->start_date,
                        'end_date' => $paymentItem->end_date,
                        'partner_idx' => $paymentItem->partner_idx,
                        'partner_phonenumber' => $partner->partner_phonenumber,
                        'product_partner_name' => $paymentItem->product_partner_name,
                        'product_detail_name' => $paymentItem->product_detail_name,
                        'quantity' => $paymentItem->quantity,
                        'reservation_number' => $paymentItem->reservation_number,
                        'reservation_pending_code' => $paymentItem->reservation_pending_code,
                        'reservation_confirmed_code' => $paymentItem->reservation_confirmed_code,
                        'payment_sale_amount' => $payment->payment_sale_amount,
                        'refund_amount' => $payment->refund_amount,
                        'used_coupon' => !empty($payment->coupon_user_idx) ? 'Y' : 'N',
                        'coupon_name' => !empty($payment->coupon_name) ? $payment->coupon_name : '',
                        'product_type' => $paymentItem->product_type,
                        'product_idx' => $paymentItem->product_idx,
                        'item_origin_sale_price' => $paymentItem->item_sale_price,
                        'partner_thirdparty' => $partner->partner_thirdparty,
                        'partner_charge' => $partner->partner_charge,
                        'partner_reservation_email' => $partner->partner_reservation_email,
                        'stay_cancel_info' => $stay->stay_cancel_info,
                        'reservation_datetime' => $payment->created_at
                    ];

                    if (!empty($paymentItem->product_benefits)) {
                        $benefitNames = array_column($paymentItem->product_benefits, 'benefit_name');
                        $mailData['product_detail_name'] .= ' - ' . implode(', ', $benefitNames);
                    }

                    //객실투숙인원 정보
                    $mailData['reservation_personnel'] = "";
                    if (!empty($paymentItem->reservation_personnel)) {
                        if (!empty($paymentItem->reservation_personnel['adult'])) {
                            $mailData['reservation_personnel'] .= '성인 '.$paymentItem->reservation_personnel['adult'].'인';
                        }

                        if (!empty($paymentItem->reservation_personnel['child'])) {
                            $mailData['reservation_personnel'] .= '아동 '.$paymentItem->reservation_personnel['child'].'인';
                        }

                        if (!empty($paymentItem->reservation_personnel['infant'])) {
                            $mailData['reservation_personnel'] .= '유아 '.$paymentItem->reservation_personnel['infant'].'인';
                        }
                    }

                    if ($pendingVoucher->item_status == 'booking') {
                        $email = EmailTemplateService::reservationBookingToHotelTemplate($mailData);
                    } else {
                        $email = EmailTemplateService::reservationCancelToHotelTemplate($mailData);
                    }

                    if ($email) {
                        $result = SendMailService::sendMail($email);

                        if ($result) {
                            $pendingVoucher->status = 'send';
                        } else {
                            $pendingVoucher->status = 'fail';
                        }

                        $pendingVoucher->title = $email['subject'];
                        $pendingVoucher->message = $email['contents'];
                        $pendingVoucher->save();
                    }
                }
            }
        }
    }
}
