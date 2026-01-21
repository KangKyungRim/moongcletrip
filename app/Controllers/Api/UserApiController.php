<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Models\Token;
use App\Models\UserVerification;

use App\Helpers\MiddleHelper;
use App\Helpers\EmailService;
use App\Helpers\ResponseHelper;

use App\Services\GuestMigrationService;

use Carbon\Carbon;

class UserApiController
{
    public static function signupEmail()
    {
        $checkUser = MiddleHelper::checkLoginCookie();

        if ($checkUser) {
            if ($checkUser->user_is_guest == false) {
                // 이미 로그인한 유저라면 마이페이지로 이동
                parentGotoNewUrl('/mypage');
                // header('Location: /mypage');
                exit;
            }
        }

        $input = json_decode(file_get_contents("php://input"), true);

        // 입력 검증
        if (empty($input['email']) || empty($input['password'])) {
            return ResponseHelper::jsonResponse(['error' => 'Email and password are required.'], 400);
        }

        $email = $input['email'];
        $password = $input['password'];

        // 이메일 중복 체크
        $existingUser = User::where('user_id', $email)->first();
        if ($existingUser) {
            return ResponseHelper::jsonResponse(['error' => 'Email already exists.'], 409);
        }

        // 비밀번호 해싱
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 유저 생성 및 저장
        $user = new User();
        $user->user_id = $email;
        $user->user_email = $email;
        $user->user_password = $hashedPassword;
        $user->user_name = $input['name'];
        $user->user_nickname = MiddleHelper::createNickname();
        $user->user_is_guest = false;
        $user->user_login_type = 'email';
        $user->user_status = 'normal';
        $user->initializeUserPoints(); // 기본 포인트 0으로 설정
        $user->user_customer_key = generateRandomKey(50);
        $user->user_agree_age = $input['agreeAge'];
        $user->user_agree_terms = $input['agreeTerms'];
        $user->user_agree_privacy = $input['agreePrivacy'];
        $user->user_agree_location = $input['agreeLocation'];
        $user->user_agree_marketing = $input['agreeMarketing'];
        $user->user_created_at = Carbon::now();
        $user->user_updated_at = Carbon::now();
        $user->user_last_login_date = Carbon::now();
        $user->save();

        if (!empty($_SESSION['user_is_guest'])) {
            $token = Token::where('device_token', $_SESSION['device_token'])->first();
            $token->user_idx = $user->user_idx;
            $token->save();

            $guestService = new GuestMigrationService();
            $guestService->guestDataToNewUser($_SESSION['user_idx'], $user->user_idx);
        }

        $_SESSION['user_idx'] = $user->user_idx;
        $_SESSION['user_customer_key'] = $user->user_customer_key;
        $_SESSION['user_is_guest'] = false;

        // $accessToken = MiddleHelper::createAccessToken($user);
        // $refreshToken = MiddleHelper::createRefreshToken($user);

        return ResponseHelper::jsonResponse([
            'success' => true
            // 'access_token' => $accessToken,
            // 'refresh_token' => $refreshToken,
            // 'user_id' => $user->user_idx
        ], 200);
    }

    // 이메일 로그인 (POST)
    public static function loginEmail()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'];
        $password = $data['password'];

        // 이메일로 유저 검색
        $user = User::where('user_id', $email)->where('user_status', 'normal')->first();

        if (!$user || !password_verify($password, $user->user_password)) {
            return ResponseHelper::jsonResponse(['error' => 'Invalid credentials'], 401);
        }

        // 액세스 토큰 및 리프레시 토큰 생성
        // $accessToken = MiddleHelper::createAccessToken($user);
        // $refreshToken = MiddleHelper::createRefreshToken($user);

        $user->user_last_login_date = Carbon::now();
        $user->save();

        if (!empty($_SESSION['user_is_guest'])) {
            $token = Token::where('device_token', $_SESSION['device_token'])->first();
            $token->user_idx = $user->user_idx;
            $token->save();

            $guestService = new GuestMigrationService();
            $guestService->guestDataToNewUser($_SESSION['user_idx'], $user->user_idx);
        }

        $_SESSION['user_idx'] = $user->user_idx;
        $_SESSION['user_customer_key'] = $user->user_customer_key;
        $_SESSION['user_is_guest'] = false;

        return ResponseHelper::jsonResponse([
            'success' => true
            // 'access_token' => $accessToken,
            // 'refresh_token' => $refreshToken,
            // 'user_id' => $user->user_idx
        ], 200);
    }

    // 로그아웃 (POST)
    public static function logout()
    {
        // 클라이언트에서 토큰을 만료시키면 됨 (서버에서 별도의 처리 필요 없음)
        // 클라이언트가 로컬 스토리지나 세션에서 토큰을 삭제
        return ResponseHelper::jsonResponse(['message' => 'Logged out successfully'], 200);
    }

    // 리프레시 토큰을 통한 액세스 토큰 재발급 (POST)
    public static function refreshToken()
    {
        $refreshToken = $_POST['refresh_token'];

        // 리프레시 토큰 검증
        $decoded = MiddleHelper::validateRefreshToken($refreshToken);

        if (!$decoded) {
            return ResponseHelper::jsonResponse(['error' => 'Invalid refresh token'], 401);
        }

        // 유저 검색
        $user = User::find($decoded->sub);

        // 새로운 액세스 토큰 발급
        $newAccessToken = MiddleHelper::createAccessToken($user);

        return ResponseHelper::jsonResponse(['access_token' => $newAccessToken], 200);
    }

    public static function updateAgrees()
    {
        $user = MiddleHelper::getOnlyUserData();

        if (!$user) {
            return ResponseHelper::jsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $user->user_status = 'normal';
        $user->user_agree_age = $input['agreeAge'];
        $user->user_agree_terms = $input['agreeTerms'];
        $user->user_agree_privacy = $input['agreePrivacy'];
        $user->user_agree_location = $input['agreeLocation'];
        $user->user_agree_marketing = $input['agreeMarketing'];
        $user->user_updated_at = Carbon::now();
        $user->save();

        return ResponseHelper::jsonResponse(['success' => true], 200);
    }

    public static function updateProfile()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            } else {
                return ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => '올바르지 않은 접근입니다.'
                ], 400);
            }
        } else {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '올바르지 않은 접근입니다.'
            ], 400);
        }

        if (!empty($input['user_nickname'])) {
            $user->user_nickname = $input['user_nickname'];
            $user->save();
        }

        return ResponseHelper::jsonResponse([
            'success' => true,
            'message' => '유저 정보를 업데이트했습니다.'
        ], 200);
    }

    public static function deleteAccount()
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            } else {
                return ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => '올바르지 않은 접근입니다.'
                ], 400);
            }
        } else {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '올바르지 않은 접근입니다.'
            ], 400);
        }

        $user->user_status = 'deleted';
        $user->user_level = 1;
        $user->save();

        return ResponseHelper::jsonResponse([
            'success' => true,
            'message' => '탈퇴했습니다.'
        ], 200);
    }
}
