<?php

namespace App\Controllers\Api;

use App\Helpers\ResponseHelper;

use App\Services\FCMNotificationService;

use App\Models\Token;
use App\Models\User;

/**
 * 트레블테크팀 내부 테스트용 API
 */
class TravelTechTestController
{
    /**
     * FCM 발송 테스트
     */
    public static function sendFcm()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        // 입력 검증
        if (empty($input['email'])) {
            return ResponseHelper::jsonResponse(['error' => 'Email are required.'], 400);
        }

        if (empty($input['device_type'])) {
            return ResponseHelper::jsonResponse(['error' => 'Device Type are required.'], 400);
        }

        $email      = $input['email'];
        $deviceType = $input['device_type'];

        $title = $input['title'] ? $input['title'] : "테스트";
        $message = $input['message'] ? $input['message'] : "테스트 입니다.";

        //이메일로 사용자 조회
        $user = User::where('user_email', $email)->first();
        if (!$user) {
            return ResponseHelper::jsonResponse(['error' => '사용자 없음'], 409);
        }

        $tokens = Token::where('user_idx', $user->user_idx)
                    ->where('token_device_type',  $deviceType)
					->where('token_is_active', true)
					->get();

        if ($tokens->isEmpty()) {
            return ResponseHelper::jsonResponse(['error' => 'token 없음'], 409);
        }
    
        
        $fcmService = new FCMNotificationService($_ENV['FCM_KEY']);
        $result = $fcmService->sendNotification($tokens, $title, $message, '', 0, 0);

        return ResponseHelper::jsonResponse([
            'success'   => true,
            'user'      => $user->user_idx,
            'token'     => $tokens,
            'fcmResult' => $result
        ], 200);
    }
}