<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Models\Token;

use App\Services\AppleAuthService;
use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use App\Services\GuestMigrationService;

use Carbon\Carbon;

class AppleAuthController
{
	// 카카오 로그인 URL 생성
	public static function redirect()
	{
		$headers = getallheaders();
		$service = new AppleAuthService();

		$data = array(
			'headers' => $headers,
			'service' => $service
		);

		self::render('apple-redirect', ['data' => $data]);
	}

	public function callbackPost()
	{
		$state = $_POST['state'] ?? null;
		$code = $_POST['code'] ?? null;
		$idToken = $_POST['id_token'] ?? null;

		$stateFile = '/tmp/apple_state_' . $state . '.json';

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

		$service = new AppleAuthService();
		$tokenData = $service->fetchTokens($code);

		// ID 토큰 디코딩
		$appleUser = $service->decodeIdToken($tokenData['id_token']);

		if (!$appleUser) {
			return ResponseHelper::jsonResponse(['error' => 'Failed to retrieve Apple user data'], 400);
		}

		// 사용자 생성 및 업데이트
		$user = $service->handleUser($appleUser);

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

		header('Location: /mypage');
		exit;
	}

	public function callbackGet()
	{
		$token = $_GET['identityToken'];

		// ID 토큰 디코딩
		$service = new AppleAuthService();
		$appleUser = $service->decodeIdToken($token);

		if (!$appleUser) {
			return ResponseHelper::jsonResponse(['error' => 'Failed to retrieve Apple user data'], 400);
		}

		// 사용자 생성 및 업데이트
		$user = $service->handleUser($appleUser);

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

		parentGotoNewUrl('/mypage');
	}

	private static function render($view, $data = [])
	{
		extract($data);
		require "../app/Views/app/{$view}.php";
	}
}
