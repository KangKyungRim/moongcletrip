<?php

namespace App\Controllers\View;

use App\Helpers\MiddleHelper;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Room;
use App\Models\RoomRateplan;
use App\Models\MoongcleOffer;
use Database;
use App\Models\CancelRule;

class MyReservationViewController
{
    public static function list()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            parentGotoNewUrl('/mypage');
            // header('Location: /mypage');
            exit;
        }

        $sql = "
            SELECT
                payi.*,
                p.*,
                (
					SELECT GROUP_CONCAT(image_normal_path ORDER BY image_order SEPARATOR ':-:')
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
				) AS image_paths,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                IF(rv.review_idx IS NULL, 0, 1) AS has_review
            FROM
                payment_items payi
            LEFT JOIN partners p ON p.partner_idx = payi.partner_idx
            LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
            LEFT JOIN reviews rv ON rv.payment_item_idx = payi.payment_item_idx
            WHERE
                payi.user_idx = :userIdx
                AND payi.payment_status != 'pending'
                AND payi.product_category = 'stay'
            GROUP BY payi.payment_item_idx
            ORDER BY created_at DESC
        ";

        $bindings = [
            'userIdx' => $user->user_idx
        ];

        $reservations = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT
                (
                    SELECT COUNT(*) 
                    FROM payment_items 
                    WHERE user_idx = :userIdx1 
                        AND payment_status != 'pending' 
                        AND product_category = 'stay' 
                        AND reservation_status = 'confirmed'
                ) AS confirmed,
                (
                    SELECT COUNT(*) 
                    FROM payment_items 
                    WHERE user_idx = :userIdx2
                        AND payment_status != 'pending' 
                        AND product_category = 'stay' 
                        AND reservation_status = 'completed'
                ) AS completed,
                (
                    SELECT COUNT(*) 
                    FROM payment_items 
                    WHERE user_idx = :userIdx3
                        AND payment_status != 'pending' 
                        AND product_category = 'stay' 
                        AND reservation_status = 'canceled'
                ) AS canceled
            FROM
                payment_items p
            WHERE
                p.user_idx = :userIdx4
                AND p.payment_status != 'pending'
                AND p.product_category = 'stay'
        ";

        $bindings = [
            'userIdx1' => $user->user_idx,
            'userIdx2' => $user->user_idx,
            'userIdx3' => $user->user_idx,
            'userIdx4' => $user->user_idx,
        ];

        $statusCount = Database::getInstance()->getConnection()->select($sql, $bindings);
        $statusCount = $statusCount[0] ?? [];

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'reservations' => $reservations,
            'statusCount' => $statusCount
        );

        self::render('reservation-list', ['data' => $data]);
    }

    public static function detail($paymentIdx)
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            parentGotoNewUrl('/mypage');
            // header('Location: /mypage');
            exit;
        }

        //본인 예약 건만 조회 가능
        $payment = Payment::find($paymentIdx);

        if ($payment->user_idx != $user->user_idx) {
            parentGotoNewUrl('/my/reservations');
            exit;
        }

        $sql = "
            SELECT
                payi.*,
                p.*,
                s.*,
                (
					SELECT image_normal_path
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
					ORDER BY image_order ASC
					LIMIT 1
				) AS image_path,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags
            FROM
                payment_items payi
            LEFT JOIN partners p ON p.partner_idx = payi.partner_idx
            LEFT JOIN stays s ON s.stay_idx = p.partner_detail_idx
            LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
            WHERE
                payi.user_idx = :userIdx
                AND payi.payment_idx = :paymentIdx
        ";

        $partnerIdx = $reservations[0]->partner_idx ?? null; // Assign a value to $partnerIdx

        $bindings = [
            'userIdx' => $user->user_idx,
            'paymentIdx' => $paymentIdx,
        ];

        $reservations = Database::getInstance()->getConnection()->select($sql, $bindings);
        $reservation = $reservations[0];

        if ($reservation->product_type == 'moongcledeal') {
            $product = MoongcleOffer::find($reservation->product_idx);
        } else {
            $product = RoomRateplan::find($reservation->product_idx);
        }

        $sql = "
            SELECT
                r.*,
                (
                    SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                    FROM moongcletrip.tag_connections t
                    WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                ) AS views
            FROM
                rooms r
            WHERE
                r.room_idx = :roomIdx
        ";

        $bindings = [
            'roomIdx' => $product->room_idx
        ];

        $room = Database::getInstance()->getConnection()->select($sql, $bindings);
        $room = $room[0];

        $sql = "
            SELECT
                pay.*
            FROM
                payments pay
            WHERE
                pay.payment_idx = :paymentIdx
        ";

        $bindings = [
            'paymentIdx' => $paymentIdx
        ];

        $payment = Database::getInstance()->getConnection()->select($sql, $bindings);
        $payment = $payment[0];

        $partnerIdx = $reservations[0]->partner_idx ?? null;

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
            'partnerIdx' => $partnerIdx,
        ];

        $cancelRules = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'reservation' => $reservation,
            'payment' => $payment,
            'room' => $room,
            'cancelRules' => $cancelRules,
        );

        self::render('reservation-detail', ['data' => $data]);
    }

    public static function cancel($paymentIdx)
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            parentGotoNewUrl('/mypage');
            // header('Location: /mypage');
            exit;
        }

        $sql = "
            SELECT
                payi.*,
                p.*
            FROM
                payment_items payi
            LEFT JOIN partners p ON p.partner_idx = payi.partner_idx
            WHERE
                payi.user_idx = :userIdx
                AND payi.payment_idx = :paymentIdx
        ";

        $bindings = [
            'userIdx' => $user->user_idx,
            'paymentIdx' => $paymentIdx
        ];

        $reservations = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT
                pay.*
            FROM
                payments pay
            WHERE
                pay.payment_idx = :paymentIdx
        ";

        $bindings = [
            'paymentIdx' => $paymentIdx
        ];

        $payment = Database::getInstance()->getConnection()->select($sql, $bindings);
        $payment = $payment[0];

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'reservations' => $reservations,
            'payment' => $payment
        );

        self::render('reservation-cancel', ['data' => $data]);
    }

    /**
     * 예약내역 > 취소 상세
     */
    public static function canceled($paymentIdx)
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            parentGotoNewUrl('/mypage');
            // header('Location: /mypage');
            exit;
        }

        //본인 취소 건만 조회 가능
        $payment = Payment::find($paymentIdx);

        if ($payment->user_idx != $user->user_idx || $payment->payment_status != 'canceled') {
            parentGotoNewUrl('/my/reservations');
            exit;
        }

        $sql = "
            SELECT
                payi.*,
                p.*
            FROM
                payment_items payi
            LEFT JOIN partners p ON p.partner_idx = payi.partner_idx
            WHERE
                payi.user_idx = :userIdx
                AND payi.payment_idx = :paymentIdx
        ";

        $bindings = [
            'userIdx' => $user->user_idx,
            'paymentIdx' => $paymentIdx
        ];

        $reservations = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT
                pay.*
            FROM
                payments pay
            WHERE
                pay.payment_idx = :paymentIdx
        ";

        $bindings = [
            'paymentIdx' => $paymentIdx
        ];

        $payment = Database::getInstance()->getConnection()->select($sql, $bindings);
        $payment = $payment[0];

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'reservations' => $reservations,
            'payment' => $payment
        );

        self::render('reservation-canceled', ['data' => $data]);
    }

    public static function cancelFail()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            parentGotoNewUrl('/mypage');
            // header('Location: /mypage');
            exit;
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
        );

        self::render('reservation-cancel-fail', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
