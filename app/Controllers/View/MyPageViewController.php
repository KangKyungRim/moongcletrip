<?php

namespace App\Controllers\View;

use App\Models\Notification;

use App\Helpers\MiddleHelper;

use Database;

use Carbon\Carbon;

class MyPageViewController
{
    public static function main()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->distinct()
                ->count(['base_idx', 'target_idx']);
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
			";

            $bindings = [
                'userIdx' => $user->user_idx,
                'now1' => $now,
                'now2' => $now,
            ];

            $myCoupons = Database::getInstance()->getConnection()->select($sql, $bindings);
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'myCoupons' => $myCoupons,
            'unreadMoocledealCount' => $unreadMoocledealCount
        );

        if ($isGuest) {
            self::render('mypage-anonymous', ['data' => $data]);
        } else {
            self::render('mypage', ['data' => $data]);
        }
    }

    public static function favorites()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $myFavorites = null;

        if ($user && !$isGuest) {
            $bindings = [];

            $sql = "
                SELECT
                    pf.*,
                    p.partner_idx,
                    p.partner_name,
                    p.partner_address1,
                    (
                        SELECT GROUP_CONCAT(image_normal_path ORDER BY image_order SEPARATOR ':-:')
                        FROM moongcletrip.images img
                        WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
                    ) AS image_paths,
                    GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                    GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types
                FROM partner_favorites pf
                LEFT JOIN partners p ON p.partner_idx = pf.partner_idx
                LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
                LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
                WHERE pf.user_idx = :userIdx
                    AND p.partner_category = 'stay'
                GROUP BY pf.favorite_idx
                ORDER BY pf.created_at DESC
            ";

            $bindings = [
                'userIdx' => $user->user_idx
            ];

            $myFavorites = Database::getInstance()->getConnection()->select($sql, $bindings);
        }

        foreach ($myFavorites as &$myFavorite) {
            $price = null;

            if ($myFavorite->target == 'partner') {
                $sql = "
                    SELECT
                        MIN(rp.room_price_sale) AS lowest_price,
                        SUBSTRING_INDEX(
                            GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
                        ) AS basic_price
                    FROM rooms r
                    LEFT JOIN room_rateplan rr ON r.room_idx = rr.room_idx
                    LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                    LEFT JOIN room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                    WHERE r.partner_idx = :partnerIdx
                        AND i.inventory_quantity > 0
                        AND rp.room_price_sale > 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                    LIMIT 1;
                ";

                $bindings = [
                    'partnerIdx' => $myFavorite->partner_idx
                ];

                $price = Database::getInstance()->getConnection()->select($sql, $bindings);

                if (!empty($price[0])) {
                    $myFavorite->basic_price = intval($price[0]->basic_price);
                    $myFavorite->lowest_price = intval($price[0]->lowest_price);
                } else {
                    $myFavorite->basic_price = 0;
                    $myFavorite->lowest_price = 0;
                }
            } else {
                $sql = "
                    SELECT
                        MIN(mp.moongcleoffer_price_sale) AS lowest_price,
                        SUBSTRING_INDEX(
                            GROUP_CONCAT(mp.moongcleoffer_price_basic ORDER BY mp.moongcleoffer_price_sale ASC), ',', 1
                        ) AS basic_price,
                        mp.moongcleoffer_price_date,
                        mo.base_product_idx 
                    FROM moongcletrip.moongcleoffers mo
                    LEFT JOIN moongcletrip.partners p ON mo.partner_idx = p.partner_idx
                    LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
                    LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
                    LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                    LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                    LEFT JOIN moongcletrip.moongcleoffer_prices mp ON mp.base_idx = rp.room_price_idx AND mo.moongcleoffer_idx = mp.moongcleoffer_idx 
                    WHERE mo.moongcleoffer_idx = :moongcleofferIdx
                        AND mp.moongcleoffer_price_sale > 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                        AND i.inventory_quantity > 0
                        AND mp.moongcleoffer_price_date >= CURDATE()
                    LIMIT 1;
                ";

                $bindings = [
                    'moongcleofferIdx' => $myFavorite->moongcleoffer_idx
                ];

                $price = Database::getInstance()->getConnection()->select($sql, $bindings);

                if (!empty($price[0])) {
                    $myFavorite->basic_price = intval($price[0]->basic_price);
                    $myFavorite->lowest_price = intval($price[0]->lowest_price);
                } else {
                    $myFavorite->basic_price = 0;
                    $myFavorite->lowest_price = 0;
                }
            }
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'myFavorites' => $myFavorites
        );

        self::render('favorites', ['data' => $data]);
    }

    public static function faq()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
        );

        self::render('faq', ['data' => $data]);
    }

    public static function notices()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
        );

        self::render('notices', ['data' => $data]);
    }

    public static function notice($noticeIdx)
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'noticeIdx' => $noticeIdx,
        );

        self::render('notice', ['data' => $data]);
    }

    public static function termsOfService()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms1.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '서비스 이용약관'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function youthProtectionPolicy()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms6.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '청소년 보호정책'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function privacyPolicy()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms2.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '개인정보 처리방침'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function locationBasedService()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms5.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '위치기반 이용약관'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function financialTransaction()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms3.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '전자금융거래 이용약관'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function reviewPolocy()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms4.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '리뷰 운영 정책'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function consumerDisputeResolutionStandards()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $term = file_get_contents(__DIR__ . '/../../Views/app/terms/terms7.html');

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'term' => $term,
            'title' => '소비자 분쟁 해결 기준'
        );

        self::render('terms', ['data' => $data]);
    }

    public static function profile()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            } else {
                header('Location: /mypage');
            }
        } else {
            header('Location: /mypage');
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
        );

        self::render('profile', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
