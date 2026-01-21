<?php

namespace App\Services;

use App\Models\User;

use App\Helpers\MiddleHelper;

use Carbon\Carbon;

class AppleAuthService
{
	public function getAuthUrl($state)
	{
		$clientId = $_ENV['APPLE_CLIENTID'];
		$redirectUri = urlencode($_ENV['APP_HTTP'] . $_ENV['APPLE_CALLBACK_URL']);

		return "https://appleid.apple.com/auth/authorize?client_id={$clientId}&redirect_uri={$redirectUri}&response_type=code&scope=name email&state={$state}&response_mode=form_post";
	}

	public function decodeIdToken($idToken)
	{
		$idTokenParts = explode('.', $idToken);
		if (count($idTokenParts) !== 3) {
			return null;
		}

		// JWT의 Payload를 Base64 Decode
		$payload = json_decode(base64_decode($idTokenParts[1]), true);

		return $payload;
	}

	public function fetchTokens($code)
	{
		$clientId = $_ENV['APPLE_CLIENTID'];
		$clientSecret = $this->generateClientSecret(); // Apple JWT 생성 메서드
		$redirectUri = $_ENV['APP_HTTP'] . $_ENV['APPLE_CALLBACK_URL'];

		$url = 'https://appleid.apple.com/auth/token';

		$postData = [
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $redirectUri,
			'client_id' => $clientId,
			'client_secret' => $clientSecret,
		];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/x-www-form-urlencoded',
		]);

		$response = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($statusCode !== 200) {
			throw new \Exception('Failed to fetch tokens from Apple');
		}

		return json_decode($response, true);
	}

	public function generateClientSecret()
	{
		$privateKey = file_get_contents($_ENV['ROOT_DIRECTORY'] . $_ENV['APPLE_PRIVATE_KEY_PATH']);
		$teamId = $_ENV['APPLE_TEAM_ID'];
		$clientId = $_ENV['APPLE_CLIENTID'];
		$keyId = $_ENV['APPLE_KEY_ID'];

		$payload = [
			'iss' => $teamId,
			'iat' => time(),
			'exp' => time() + (60 * 60 * 24), // 1일 유효
			'aud' => 'https://appleid.apple.com',
			'sub' => $clientId,
		];

		return \Firebase\JWT\JWT::encode($payload, $privateKey, 'ES256', $keyId);
	}

	public function handleUser($appleUser)
	{
		$appleId = "apple_{$appleUser['sub']}";

		// 기존 사용자 조회
		$user = User::where('user_id', $appleId)->first();

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

			// 새로운 사용자 생성
			$user = User::create([
				'user_is_guest' => false,
				'user_id' => $appleId,
				'user_nickname' => $nickname,
				'user_email' => $appleUser['email'],
				'user_login_type' => 'apple',
				'user_status' => 'prepare',
				'user_customer_key' => generateRandomKey(50),
				'user_created_at' => Carbon::now(),
			]);
		}

		return $user;
	}
}
