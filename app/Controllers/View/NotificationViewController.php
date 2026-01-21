<?php

namespace App\Controllers\View;

use App\Models\Notification;

use App\Helpers\MiddleHelper;

class NotificationViewController
{
    public static function main()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadNotifications = null;
        $moongcledealNotifications = null;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadNotifications = Notification::select('base_idx', 'target_idx', 'title', 'message', 'link', 'is_read', 'push_status', 'created_at')
                ->where('user_idx', $user->user_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->groupBy('base_idx', 'target_idx')
                ->get();

            $moongcledealNotifications = Notification::select('base_idx', 'target_idx', 'title', 'message', 'link', 'is_read', 'push_status', 'created_at')
                ->where('user_idx', $user->user_idx)
                ->where('is_read', true)
                ->where('notification_type', 'moongcledeal')
                ->groupBy('base_idx', 'target_idx')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $data = array(
            'deviceType' => $deviceType,
            'moongcledealNotifications' => $moongcledealNotifications,
            'unreadNotifications' => $unreadNotifications
        );

        self::render('notification', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
