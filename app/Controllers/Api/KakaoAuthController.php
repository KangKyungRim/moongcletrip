<?php

namespace App\Controllers\Api;

use App\Models\Token;

use App\Services\KakaoAuthService;
use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use App\Services\GuestMigrationService;

class KakaoAuthController
{
	// 카카오 로그인 URL 생성
	public static function redirect()
	{
		$headers = getallheaders();
		$service = new KakaoAuthService();

		$data = array(
			'headers' => $headers,
			'service' => $service
		);

		self::render('kakao-redirect', ['data' => $data]);
	}

	// 카카오 로그인 처리
	public static function callback()
	{
		try {
			$code = $_GET['code'] ?? null;
			$state = $_GET['state'] ?? null;

			$stateFile = '/tmp/kakao_state_' . $state . '.json';

			if (!file_exists($stateFile)) {
				return ResponseHelper::jsonResponse(['error' => 'Invalid state'], 400); // state가 유효하지 않음
			}

			$stateData = json_decode(file_get_contents($stateFile), true);

			// 만료 시간 확인
			if (time() > $stateData['expires_at']) {
				unlink($stateFile); // 만료된 파일 삭제
				return ResponseHelper::jsonResponse(['error' => 'State expired'], 400);
			}

			unlink($stateFile); // 검증 후 파일 삭제

			$service = new KakaoAuthService();

			// 액세스 토큰 가져오기
			$tokenResponse = $service->getAccessToken($code);

			// 사용자 정보 가져오기
			$kakaoUser = $service->getUserInfo($tokenResponse['access_token']);

			// 사용자 처리
			$user = $service->handleUser($kakaoUser);

			if (!empty($_SESSION['user_is_guest'])) {

				$token = Token::where('device_token', $_SESSION['device_token'])->first();
				$token->user_idx = $user->user_idx;
				$token->save();

				$guestService = new GuestMigrationService();
				$guestService->guestDataToNewUser($_SESSION['user_idx'], $user->user_idx);
			}

			$_SESSION['user_idx'] = $user['user_idx'];
			$_SESSION['user_customer_key'] = $user['user_customer_key'];
			$_SESSION['user_is_guest'] = false;

			// JWT 생성
			// $accessToken = MiddleHelper::createAccessToken($user);
			// $refreshToken = MiddleHelper::createRefreshToken($user);

			// // 쿠키 저장
			// setcookie('accessToken', $accessToken, time() + (60 * 60 * 24 * 30), '/', $_ENV['HOST_NAME'], true, true);
			// setcookie('refreshToken', $refreshToken, time() + (60 * 60 * 24 * 30), '/', $_ENV['HOST_NAME'], true, true);

			if (!empty($stateData['return'])) {
				$returnUrl = urldecode($stateData['return']);

				parentGotoNewUrl($returnUrl);
			} else {
				parentGotoNewUrl('/mypage');
			}

			// header('Location: /mypage');
			exit;
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private static function render($view, $data = [])
	{
		extract($data);
		require "../app/Views/app/{$view}.php";
	}
}
