<?php

namespace App\Services;

use App\Models\Token;
use App\Models\Notification;

use Google\Client as Google_Client;

class FCMNotificationService
{
	private $fcmKey;
	private $fcmUrl;
	private $accessTokenFile;

	public function __construct($fcmKey, $accessTokenFile = '/data/wwwroot/moongcletrip/config/mungkeul-trip-access.json', $fcmUrl = 'https://fcm.googleapis.com/fcm/send')
	{
		$this->fcmKey = $fcmKey;
		$this->fcmUrl = $fcmUrl;
		$this->accessTokenFile = $accessTokenFile;
	}

	/**
	 * FCM 알림 전송
	 *
	 */
	public function sendNotification($tokenList, string $title, string $message, string $link = '', int $baseIdx = 0, int $targetIdx = 0, array $data = [])
	{
		$result = [];

		$getAccessToken = FCMNotificationService::getAccessToken('/data/wwwroot/moongcletrip/config/mungkeul-trip-firebase-adminsdk-y2h8f-6a42c1aa2d.json');

		// 실패처리 
		if (empty($getAccessToken['rst']) || $getAccessToken['rst'] != 'success') {
			$errMsg = empty($getAccessToken['msg']) ? '엑세스토큰 생성 실패' : $getAccessToken['msg'];
			die($errMsg);
		}

		// 최종 access_token 을 가져온다.
		$accessToken = $getAccessToken['access_token'];

		// 헤더정의
		$headers = array(
			'Content-Type:application/json',
			'Authorization: Bearer ' . $accessToken
		);

		// 전송 데이터
		$requestData = array(
			'message' => array(
				'token' => '', // 멀티발송시 토큰은 하단에서 재설정
				'notification' => array(
					'title' => $title,
					'body' => $message,
				),
				'data' => array(
					'title' => $title,
					'message' => $message,
					'intent' => $link
				),
				'android' => array(
					'notification' => array(
						'sound' => 'default'
					)
				),
				'apns' => array(
					'payload' => array(
						'aps' => array(
							'sound' => 'default'
						)
					)
				)
			)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/mungkeul-trip/messages:send');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// 생성한 사용자 고유토큰 만큼 전송
		$responseDatas = array();
		foreach ($tokenList as $token) {
			$requestData['message']['token'] = $token->token;
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
			$res = curl_exec($ch);

			// 최종 JSON 결과 데이터를 배열로 받는다. 
			$responseDatas[$token->token_idx] = json_decode($res, true);
		}
		curl_close($ch);

		// 최종 결과를 저장 -> 어떤 토큰을 가진 사용자가 실패했는지
		foreach ($responseDatas as $tokenIdx => $responseData) {
			if (!empty($responseData['name'])) {
				// Notification::create([
				// 	'user_idx' => $token->user_idx ?: $token->guest_idx,
				// 	'base_idx' => $baseIdx,
				// 	'target_idx' => $targetIdx,
				// 	'notification_type' => 'moongcledeal',
				// 	'title' => $title,
				// 	'message' => $message,
				// 	'link' => $link,
				// 	'is_read' => false,
				// 	'push_status' => 'success',
				// ]);

				$result[] = $tokenIdx;
				// Array
				// (
				// 	[name] => projects/redinfo-c05f9/messages/0:1715959401356544%529b9228529b9228
				// )
			} else {
				// 전송 실패처리
				// Notification::create([
				// 	'user_idx' => $token->user_idx ?: $token->guest_idx,
				// 	'base_idx' => $baseIdx,
				// 	'target_idx' => $targetIdx,
				// 	'notification_type' => 'moongcledeal',
				// 	'title' => $title,
				// 	'message' => $message,
				// 	'link' => $link,
				// 	'is_read' => false,
				// 	'push_status' => 'fail',
				// ]);

				$token = Token::find($tokenIdx);
				$token->token_is_active = false;
				$token->save();
			}
		}

		return $result;
	}

	private function getAccessToken($serviceAccountFile = '')
	{
		try {
			// 파일 체크
			if (empty($serviceAccountFile) || !is_file($serviceAccountFile)) {
				throw new \Exception('비공개 키파일 비었음');
			}

			// 액세스 토큰 캐싱 여부 확인
			if (is_file($this->accessTokenFile)) {
				$tokenData = json_decode(file_get_contents($this->accessTokenFile), true);
				if (!empty($tokenData['access_token']) && time() < $tokenData['expires_at']) {
					// 유효한 토큰 반환
					return [
						'rst' => 'success',
						'access_token' => $tokenData['access_token']
					];
				}
			}

			// Google 클라이언트 초기화
			$client = new Google_Client();
			$client->setAuthConfig($serviceAccountFile); // 파일로 보내도 되고 비공개 파일 json 데이터를 배열화 해서 보내도 된다.
			$client->setScopes('https://www.googleapis.com/auth/firebase.messaging');

			$client->fetchAccessTokenWithAssertion();
			$result = $client->getAccessToken();

			if (empty($result['access_token'])) {
				throw new \Exception("엑세스토큰 생성 실패");
			}

			// 액세스 토큰 캐싱
			$tokenData = [
				'access_token' => $result['access_token'],
				'expires_at' => time() + $result['expires_in'] - 60 // 1분 여유 추가
			];
			file_put_contents($this->accessTokenFile, json_encode($tokenData));

			return [
				'rst' => 'success',
				'access_token' => $result['access_token']
			];
		} catch (\Exception $e) {
			return [
				'rst' => 'fail',
				'msg' => $e->getMessage()
			];
		}
	}
}
