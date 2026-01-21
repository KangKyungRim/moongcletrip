<?php

namespace App\Controllers\Api\Manage;

use App\Models\PartnerUser;
use App\Models\PartnerUserAssignment;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class PartnerUserApiController
{
    public static function createPartnerUser()
    {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input['partnerIdx'])) {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '파트너 ID가 유효하지 않습니다.'
            ], 400);
        }

        if (
            empty($input['partnerUserName']) ||
            empty($input['partnerUserEmail']) ||
            empty($input['partnerUserPhoneNumber']) ||
            empty($input['partnerUserPassword']) ||
            empty($input['partnerUserPasswordRepeat'])
        ) {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '신규 유저 정보가 유효하지 않습니다.'
            ], 400);
        }

        if ($input['partnerUserPassword'] !== $input['partnerUserPasswordRepeat']) {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '비밀번호가 동일하지 않습니다.'
            ], 400);
        }

        $email = $input['partnerUserEmail'];
        $password = $input['partnerUserPassword'];

        // 이메일 중복 체크
        $existingUser = PartnerUser::where('partner_user_email', $email)->first();
        if ($existingUser) {
            return ResponseHelper::jsonResponse(['error' => '이미 등록된 이메일입니다.'], 409);
        }

        // 비밀번호 해싱
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 유저 생성 및 저장
        $partnerUser = new PartnerUser();
        $partnerUser->partner_user_level = 5;
        $partnerUser->partner_user_nickname = $input['partnerUserName'];
        $partnerUser->partner_user_password = $hashedPassword;
        $partnerUser->partner_user_name = $input['partnerUserName'];
        $partnerUser->partner_user_email = $email;
        $partnerUser->partner_user_phone_number = $input['partnerUserPhoneNumber'];
        $partnerUser->partner_user_login_type = 'email';
        $partnerUser->partner_user_status = 'normal';
        $partnerUser->partner_user_created_at = Carbon::now();
        $partnerUser->partner_user_updated_at = Carbon::now();
        $partnerUser->save();

        $partnerUserAssginment = new PartnerUserAssignment();
        $partnerUserAssginment->partner_idx = $input['partnerIdx'];
        $partnerUserAssginment->partner_user_idx = $partnerUser->partner_user_idx;
        $partnerUserAssginment->is_manager = true;
        $partnerUserAssginment->save();

        return ResponseHelper::jsonResponse([
            'success' => true,
            'message' => '신규 파트너 유저를 생성했습니다.'
        ], 200);
    }

    public static function editPartnerUser()
    {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}
        
        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input['partnerIdx'])) {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '파트너 ID가 유효하지 않습니다.'
            ], 400);
        }

        if (
            empty($input['partnerUserName']) ||
            empty($input['partnerUserEmail']) ||
            empty($input['partnerUserPhoneNumber'])
        ) {
            return ResponseHelper::jsonResponse([
                'success' => false,
                'message' => '유저 정보가 유효하지 않습니다.'
            ], 400);
        }

        $email = $input['partnerUserEmail'];
        $partnerUser = PartnerUser::where('partner_user_email', $email)->first();
        if (empty($partnerUser)) {
            return ResponseHelper::jsonResponse(['error' => '존재하지 않는 이메일입니다.'], 409);
        }

        if(!empty($input['partnerUserPassword'])) {
            if ($input['partnerUserPassword'] !== $input['partnerUserPasswordRepeat']) {
                return ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => '비밀번호가 동일하지 않습니다.'
                ], 400);
            }

            $password = $input['partnerUserPrevPassword'];

            if (password_verify($password, $partnerUser->partner_user_password)) {
                $hashedPassword = password_hash($input['partnerUserPassword'], PASSWORD_DEFAULT);
                $partnerUser->partner_user_password = $hashedPassword;
            } else {
                return ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => '비밀번호가 올바르지 않습니다.'
                ], 400);
            }
        }
        
        $partnerUser->partner_user_nickname = $input['partnerUserName'];
        $partnerUser->partner_user_name = $input['partnerUserName'];
        $partnerUser->partner_user_phone_number = $input['partnerUserPhoneNumber'];
        $partnerUser->partner_user_updated_at = Carbon::now();
        $partnerUser->save();

        return ResponseHelper::jsonResponse([
            'success' => true,
            'message' => '파트너 유저를 수정했습니다.'
        ], 200);
    }
}
