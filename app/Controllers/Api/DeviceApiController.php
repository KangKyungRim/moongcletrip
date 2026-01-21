<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Token;
use App\Models\User;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class DeviceApiController
{
	public static function registerDevice()
	{
		try {
			$input = json_decode(file_get_contents("php://input"), true);

			if(empty($input['deviceToken'])) {
				return ResponseHelper::jsonResponse([
					'message' => '디바이스 정보가 없습니다.',
					'success' => false,
				], 400);
			}

			$token = Token::where('device_token', $input['deviceToken'])->first();

			if (!empty($token)) {
				$token->token = $input['token'];
				$token->token_device_type = $input['platform'];
				$token->token_is_active = true;
				$token->save();

				return ResponseHelper::jsonResponse([
					'message' => '디바이스 정보를 저장했습니다.',
					'success' => true,
				], 200);
			} else {
				$guestId = 'Guest_' . uniqid();

				$user = User::create([
					'user_is_guest' => true,
					'user_id' => $guestId,
					'user_nickname' => $guestId,
					'user_login_type' => 'device',
					'user_status' => 'guest',
					'user_customer_key' => generateRandomKey(50),
					'user_created_at' => Carbon::now(),
				]);

				$_SESSION['user_idx'] = $user->user_idx;
				$_SESSION['user_customer_key'] = $user->user_customer_key;
				$_SESSION['user_is_guest'] = true;

				$token = new Token();
				$token->token = $input['token'];
				$token->device_token = $input['deviceToken'];
				$token->guest_idx = $user->user_idx;
				$token->token_device_type = $input['platform'];
				$token->token_is_active = true;
				$token->token_created_at = Carbon::now();
				$token->token_last_used_at = Carbon::now();
				$token->save();

				return ResponseHelper::jsonResponse([
					'message' => '디바이스 정보가 존재하지 않아 새로 생성했습니다.',
					'success' => true,
				], 200);
			}
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse([
				'message' => '디바이스 정보를 저장 중 에러가 발생했습니다.',
				'success' => false,
			], 400);
		}
	}
}
