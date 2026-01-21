<?php

namespace App\Services;

use GuzzleHttp\Client;

use App\Models\User;

use App\Helpers\MiddleHelper;
use Carbon\Carbon;

class KakaoAuthService
{
	private $clientId;
	private $callbackUri;

	public function __construct()
	{
		$this->clientId = $_ENV['KAKAO_RESTAPI_KEY'];
		$this->callbackUri = $_ENV['APP_HTTP'] . $_ENV['KAKAO_CALLBACK_URL'];
	}

	// 카카오 인증 URL 생성
	public function getAuthUrl($state)
	{
		return "https://kauth.kakao.com/oauth/authorize?" . http_build_query([
			'client_id' => $this->clientId,
			'redirect_uri' => $this->callbackUri,
			'response_type' => 'code',
			'state' => $state,
			// 'prompt' => 'select_account',
		]);
	}

	// 카카오 API 호출 (토큰 요청)
	public function getAccessToken($code)
	{
		$client = new Client();

		$response = $client->post('https://kauth.kakao.com/oauth/token', [
			'form_params' => [
				'grant_type' => 'authorization_code',
				'client_id' => $this->clientId,
				'redirect_uri' => $this->callbackUri,
				'code' => $code,
			],
		]);

		return json_decode($response->getBody(), true);
	}

	// 사용자 정보 가져오기
	public function getUserInfo($accessToken)
	{
		$client = new Client();

		$response = $client->get('https://kapi.kakao.com/v2/user/me', [
			'headers' => [
				'Authorization' => "Bearer {$accessToken}",
			],
		]);

		return json_decode($response->getBody(), true);
	}

	// 사용자 데이터 처리
	public function handleUser($kakaoUser)
	{
		$kakaoId = "kakao_{$kakaoUser['id']}";

		// 기존 사용자 조회
		$user = User::where('user_id', $kakaoId)->first();

		if ($user) {
			if ($user->user_status == 'deleted') {
				$user->user_level = 2;
				$user->user_status = 'prepare';
			}

			// 기존 사용자 업데이트
			$user->update([
				'user_last_login_date' => Carbon::now(),
			]);
		} else {
			$nickname = MiddleHelper::createNickname();
			$email = $kakaoUser['kakao_account']['email'] ?? null;

			// 새로운 사용자 생성
			$user = User::create([
				'user_is_guest' => false,
				'user_id' => $kakaoId,
				'user_nickname' => $nickname,
				'user_email' => $email,
				'user_login_type' => 'kakao',
				'user_status' => 'prepare',
				'user_customer_key' => generateRandomKey(50),
				'user_created_at' => Carbon::now(),
			]);
		}

		return $user;
	}
}
