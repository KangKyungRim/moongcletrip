<?php

namespace App\Controllers\View;

use App\Models\Notification;
use App\Models\Article;

use App\Helpers\MiddleHelper;

use Database;

class CommunityViewController
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

        $sql = "
            SELECT 
                r.*,
                u.user_nickname,
                p.partner_address1,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('origin_path', ri.review_image_origin_path, 'path', ri.review_image_normal_path, 'extension', ri.review_image_extension, 'order', ri.review_image_order))
                FROM moongcletrip.review_images ri 
                WHERE ri.review_idx = r.review_idx) AS image_list,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('tag_name', rt.tag_name, 'tag_machine_name', rt.tag_machine_name, 'order', rt.tag_order))
                FROM moongcletrip.review_tags rt 
                WHERE rt.review_idx = r.review_idx) AS tag_list,
                (
                    SELECT img.image_small_path
                    FROM moongcletrip.images img
                    WHERE img.image_entity_id = p.partner_detail_idx
                    AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
                    ORDER BY img.image_order ASC
                    LIMIT 1
                ) AS partner_image_path,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS partner_types
            FROM moongcletrip.reviews r 
            LEFT JOIN moongcletrip.users u ON u.user_idx = r.user_idx 
            LEFT JOIN moongcletrip.partners p ON p.partner_idx = r.partner_idx 
            LEFT JOIN moongcletrip.tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type'
            WHERE r.is_active = true AND r.rating >= 4
            GROUP BY r.review_idx
            ORDER BY r.created_at DESC
            LIMIT 10;
        ";

        // $bindings = [
        //     'moongcleofferIdx' => $moongcleofferIdx
        // ];

        $reviews = Database::getInstance()->getConnection()->select($sql);

        foreach ($reviews as &$review) {
            if (!empty($review->tag_list)) {
                $tag = json_decode($review->tag_list, true);
                $review->encoded_tags = base64_encode(json_encode($tag));
            } else {
                $review->encoded_tags = null;
            }
        }

        $articles = Article::all();

        $data = array(
            'deviceType' => $deviceType,
            'reviews' => $reviews,
            'unreadMoocledealCount' => $unreadMoocledealCount,
            'articles' => $articles
        );

        self::render('community', ['data' => $data]);
    }
    public static function article($articleIdx)
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $article = Article::find($articleIdx);

        $data = array(
            'deviceType' => $deviceType,
            'article' => $article
        );

        self::render('article', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
