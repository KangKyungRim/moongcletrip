<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Models\PartnerUser;
use App\Models\UserVerification;

use Carbon\Carbon;

class VerificationApiController
{
    public static function verifyPartnerEmail()
    {
        // GET 요청에서 토큰 가져오기
        if (!isset($_GET['token'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Token is required.']);
            exit;
        }

        $token = $_GET['token'];

        // 토큰으로 사용자 인증 정보 찾기
        $userVerification = UserVerification::where('token', $token)->first();

        // 토큰이 없거나 유효하지 않은 경우
        if (!$userVerification) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Invalid token.']);
            exit;
        }

        // 토큰이 만료된 경우
        if ($userVerification->isExpired()) {
            header('HTTP/1.1 410 Gone');
            echo json_encode(['error' => 'Token has expired.']);
            exit;
        }

        // 이메일 인증 성공 처리 (유저 상태 업데이트)
        $user = PartnerUser::find($userVerification->user_idx);
        $user->partner_user_status = 'normal';
		$user->partner_user_updated_at = Carbon::now();
        $user->save();

        // 인증 토큰 삭제
        $userVerification->delete();

        echo json_encode(['message' => 'Email verified successfully.']);
    }

    public static function verifyEmail()
    {
        // GET 요청에서 토큰 가져오기
        if (!isset($_GET['token'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Token is required.']);
            exit;
        }

        $token = $_GET['token'];

        // 토큰으로 사용자 인증 정보 찾기
        $userVerification = UserVerification::where('token', $token)->first();

        // 토큰이 없거나 유효하지 않은 경우
        if (!$userVerification) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Invalid token.']);
            exit;
        }

        // 토큰이 만료된 경우
        if ($userVerification->isExpired()) {
            header('HTTP/1.1 410 Gone');
            echo json_encode(['error' => 'Token has expired.']);
            exit;
        }

        // 이메일 인증 성공 처리 (유저 상태 업데이트)
        $user = User::find($userVerification->user_idx);
        $user->user_status = 'normal';
		$user->user_updated_at = Carbon::now();
        $user->save();

        // 인증 토큰 삭제
        $userVerification->delete();

        echo json_encode(['message' => 'Email verified successfully.']);
    }
}