<?php

namespace App\Controllers\View;

use App\Models\PaymentItem;
use App\Models\Partner;
use App\Models\Image;

use App\Helpers\MiddleHelper;

use Database;

class ReviewViewController
{
    public static function reviews()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $sql = "
            SELECT
                r.*,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('origin_path', ri.review_image_origin_path, 'path', ri.review_image_small_path, 'extension', ri.review_image_extension, 'order', ri.review_image_order))
                FROM moongcletrip.review_images ri 
                WHERE ri.review_idx = r.review_idx) AS image_list
            FROM reviews r
            WHERE r.user_idx = :userIdx
                AND r.is_active = 1
            LIMIT 10;
        ";

        $bindings = [
            'userIdx' => $user->user_idx,
        ];

        $myReviewsQuery = Database::getInstance()->getConnection()->select($sql, $bindings);

        $myReviews = [];

        foreach ($myReviewsQuery as $review) {
            $partner = Partner::find($review->partner_idx);
            $paymentItem = PaymentItem::find($review->payment_item_idx);

            $mainImage = null;
            if ($review->review_category == 'stay') {
                $mainImage = Image::where('image_entity_id', $partner->partner_detail_idx)
                    ->where('image_entity_type', 'stay')
                    ->orderBy('image_order', 'ASC')
                    ->first();
            }

            if (empty($mainImage)) {
                $mainImage = '/assets/app/images/demo/img_hotel_large.png';
            } else {
                $mainImage = $mainImage->image_small_path;
            }

            $review->product_detail_name = $paymentItem->product_detail_name;
            $review->main_image = $mainImage;
            $myReviews[] = $review;
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'myReviews' => $myReviews,
        );

        self::render('my-reviews', ['data' => $data]);
    }

    public static function createReview($paymentItemIdx)
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

        $paymentItem = PaymentItem::find($paymentItemIdx);
        $partner = Partner::find($paymentItem->partner_idx);
        $partnerDetail = $partner->partnerDetail();

        $mainImage = null;
        if ($paymentItem->product_category == 'stay') {
            $mainImage = Image::where('image_entity_id', $partnerDetail->stay_idx)
                ->where('image_entity_type', 'stay')
                ->orderBy('image_order', 'ASC')
                ->first();
        }

        if (empty($mainImage)) {
            $mainImage = new \stdClass();
            $mainImage->image_small_path = '/assets/app/images/demo/img_hotel_large.png';
        }

        $data = array(
            'user' => $user,
            'deviceType' => $deviceType,
            'paymentItem' => $paymentItem,
            'mainImage' => $mainImage
        );

        self::render('create-review', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
