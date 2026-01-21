<?php

namespace App\Helpers;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Firebase\JWT\ExpiredException;

use \App\Models\User;
use \App\Models\PartnerUser;
use \App\Models\Token;
use \App\Models\AccessLog;

use Carbon\Carbon;

class MiddleHelper
{
	public static function checkUserAction()
	{
		$userIdx = null;

		if (!empty($_SESSION['user_idx'])) {
			$userIdx = $_SESSION['user_idx'];
		}

		$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		$userIP = $_SERVER['REMOTE_ADDR'];
		$isNaverAd = false;

		if (strpos($referrer, 'naver.com') !== false) {
			// 네이버에서 유입된 경우
			$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
			parse_str($queryString, $queryParams);

			if (isset($queryParams['utm_source']) && $queryParams['utm_source'] === 'naver') {
				$isNaverAd = true;
			}
		} else {
			// 네이버가 아닌 경우
		}

		if ($isNaverAd) {
			$accessLog = new AccessLog();
			$accessLog->user_idx = $userIdx;
			$accessLog->access_ip = $userIP;
			$accessLog->access_full_path = $_SERVER['REQUEST_URI'];
			$accessLog->save();
		} else {
		}
	}

	// JWT 로그인 상태 체크
	public static function checkPartnerLoginCookie()
	{
		// 쿠키에서 JWT 토큰 읽기
		if (isset($_COOKIE['accessTokenPartner'])) {
			$token = $_COOKIE['accessTokenPartner'];

			try {
				$secretKey = $_ENV['JWT_SECRET'];

				// JWT 검증
				$decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

				if ($decoded->sub && $decoded->isPartner) {
					$user = PartnerUser::find($decoded->sub);
				} else {
					return false;
				}

				return $user;  // 로그인된 유저의 정보 반환
			} catch (ExpiredException $e) {
				return false;  // 토큰 만료
			} catch (\Exception $e) {
				echo $e;
				return false;  // 유효하지 않은 토큰
			}
		}

		return false;  // 쿠키에 토큰이 없는 경우
	}

	public static function checkPartnerLoginHeader()
	{
		$headers = getallheaders();

		if (isset($headers['Authorization'])) {
			$token = str_replace('Bearer ', '', $headers['Authorization']);

			try {
				$secretKey = $_ENV['JWT_SECRET'];

				// JWT 검증
				$decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
				return $decoded;  // 로그인된 유저의 정보 반환
			} catch (ExpiredException $e) {
				return false;  // 토큰 만료
			} catch (\Exception $e) {
				return false;  // 유효하지 않은 토큰
			}
		}

		return false;  // Authorization 헤더가 없는 경우
	}

	// JWT 액세스 토큰 생성
	public static function createPartnerAccessToken($user = null)
	{
		$secretKey = $_ENV['JWT_SECRET'];

		$payload = [
			'iss' => $_ENV['HOST_NAME'],  // 발급자
			'sub' => $user ? $user->partner_user_idx : null,   // 유저 ID
			'iat' => time(),            // 발급 시간
			'exp' => time() + (60 * 60 * 24),       // 만료 시간 (1일)
			'isPartner' => true
		];

		return JWT::encode($payload, $secretKey, 'HS256');
	}

	// 리프레시 토큰 생성
	public static function createPartnerRefreshToken($user = null)
	{
		$refreshSecretKey = $_ENV['JWT_REFRESH_SECRET'];

		$payload = [
			'iss' => $_ENV['HOST_NAME'],
			'sub' => $user ? $user->partner_user_idx : null,
			'iat' => time(),
			'exp' => time() + (60 * 60 * 24 * 30),  // 30일
		];

		return JWT::encode($payload, $refreshSecretKey, 'HS256');
	}

	public static function checkLoginHeader()
	{
		$headers = getallheaders();

		if (isset($headers['Authorization'])) {
			$token = str_replace('Bearer ', '', $headers['Authorization']);

			try {
				$secretKey = $_ENV['JWT_SECRET'];

				// JWT 검증
				$decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
				return $decoded;  // 로그인된 유저의 정보 반환
			} catch (ExpiredException $e) {
				return false;  // 토큰 만료
			} catch (\Exception $e) {
				return false;  // 유효하지 않은 토큰
			}
		}

		return false;  // Authorization 헤더가 없는 경우
	}

	// JWT 로그인 상태 체크 및 갱신
	public static function checkLoginCookie()
	{
		if (!empty($_SESSION['device_token']) && empty($_SESSION['user_idx'])) {
			$tokenExist = Token::where('device_token', $_SESSION['device_token'])->first();

			if (empty($tokenExist)) {
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
				$token->device_token = $_SESSION['device_token'];
				$token->guest_idx = $user->user_idx;
				$token->token_device_type = getPlatformType();
				$token->token_is_active = true;
				$token->token_created_at = Carbon::now();
				$token->token_last_used_at = Carbon::now();
				$token->save();
			} else {
				if (!empty($tokenExist->guest_idx)) {
					$user = User::find($tokenExist->guest_idx);
					$user->user_last_login_date = Carbon::now();
					$user->save();

					$tokenExist->token_last_used_at = Carbon::now();
					$tokenExist->save();
				}
			}

			$_SESSION['user_idx'] = $user->user_idx;
			$_SESSION['user_customer_key'] = $user->user_customer_key;
			$_SESSION['user_is_guest'] = true;

			return $user;
		}

		// 액세스 토큰 검증
		if (!empty($_SESSION['user_idx'])) {
			$user = User::find($_SESSION['user_idx']);

			if (empty($user)) {
				unset($_SESSION['user_idx']);
				unset($_SESSION['user_customer_key']);
				unset($_SESSION['user_is_guest']);
				parentGotoNewUrl('/mypage');
				exit;
			}
			if (empty($_SESSION['user_customer_key'])) {
				unset($_SESSION['user_idx']);
				unset($_SESSION['user_customer_key']);
				unset($_SESSION['user_is_guest']);
				parentGotoNewUrl('/mypage');
				exit;
			}
			if ($_SESSION['user_customer_key'] !== $user->user_customer_key) {
				unset($_SESSION['user_idx']);
				unset($_SESSION['user_customer_key']);
				unset($_SESSION['user_is_guest']);
				parentGotoNewUrl('/mypage');
				exit;
			}

			if ($user->user_status == 'prepare') {
				$currentUrl = urlencode($_SERVER['REQUEST_URI']);
				parentGotoNewUrl('/users/signup-agree?type=thirdparty&return=' . $currentUrl);
				// header('Location: /users/signup-agree?type=thirdparty');
				exit;
			}

			return $user;
		} else {
			return false;
		}

		// if (isset($_COOKIE['accessToken'])) {
		// 	$accessToken = $_COOKIE['accessToken'];
		// 	$refreshToken = $_COOKIE['refreshToken'] ?? null;

		// 	try {
		// 		$secretKey = $_ENV['JWT_SECRET'];
		// 		$decoded = JWT::decode($accessToken, new Key($secretKey, 'HS256'));

		// 		// 유저 확인
		// 		$user = User::find($decoded->sub);

		// 		if ($user->user_status == 'prepare') {
		// 			parentGotoNewUrl('/users/signup-agree?type=thirdparty');
		// 			// header('Location: /users/signup-agree?type=thirdparty');
		// 			exit;
		// 		}

		// 		return $user;
		// 	} catch (ExpiredException $e) {
		// 		// 액세스 토큰 만료 시, 리프레시 토큰 검증
		// 		if ($refreshToken && self::validateRefreshToken($refreshToken)) {
		// 			$user = self::getUserFromRefreshToken($refreshToken);

		// 			// 리프레시 토큰이 유효한 경우 새로운 액세스 토큰과 리프레시 토큰 생성
		// 			if ($user) {
		// 				$newAccessToken = self::createAccessToken($user);
		// 				$newRefreshToken = self::createRefreshToken($user);

		// 				setcookie('accessToken', $newAccessToken, time() + (60 * 60 * 24 * 30), '/', $_ENV['HOST_NAME'], true, true);
		// 				setcookie('refreshToken', $newRefreshToken, time() + (60 * 60 * 24 * 30), '/', $_ENV['HOST_NAME'], true, true);

		// 				return $user;
		// 			}
		// 		}
		// 	} catch (\Exception $e) {
		// 		// 유효하지 않은 토큰
		// 		return false;
		// 	}
		// }
		return false;
	}

	// JWT 로그인 상태 체크 및 갱신
	public static function getOnlyUserData()
	{
		// 액세스 토큰 검증
		if (isset($_SESSION['user_idx'])) {
			try {
				// 유저 확인
				$user = User::find($_SESSION['user_idx']);

				return $user;
			} catch (ExpiredException $e) {
				return false;
			} catch (\Exception $e) {
				// 유효하지 않은 토큰
				return false;
			}
		}

		// if (isset($_COOKIE['accessToken'])) {
		// 	$accessToken = $_COOKIE['accessToken'];
		// 	$refreshToken = $_COOKIE['refreshToken'] ?? null;

		// 	try {
		// 		$secretKey = $_ENV['JWT_SECRET'];
		// 		$decoded = JWT::decode($accessToken, new Key($secretKey, 'HS256'));

		// 		// 유저 확인
		// 		$user = User::find($decoded->sub);

		// 		return $user;
		// 	} catch (ExpiredException $e) {
		// 		return false;
		// 	} catch (\Exception $e) {
		// 		// 유효하지 않은 토큰
		// 		return false;
		// 	}
		// }
		return false;
	}

	// JWT 액세스 토큰 생성
	public static function createAccessToken($user)
	{
		$secretKey = $_ENV['JWT_SECRET'];

		$payload = [
			'iss' => $_ENV['HOST_NAME'],
			'sub' => $user->user_idx,
			'iat' => time(),
			'exp' => time() + (60 * 60 * 24),  // 만료 시간 1일
		];

		return JWT::encode($payload, $secretKey, 'HS256');
	}

	// 리프레시 토큰 생성
	public static function createRefreshToken($user)
	{
		$refreshSecretKey = $_ENV['JWT_REFRESH_SECRET'];

		$payload = [
			'iss' => $_ENV['HOST_NAME'],
			'sub' => $user->user_idx,
			'iat' => time(),
			'exp' => time() + (60 * 60 * 24 * 30),  // 만료 시간 30일
		];

		return JWT::encode($payload, $refreshSecretKey, 'HS256');
	}

	// 리프레시 토큰 검증
	public static function validateRefreshToken($refreshToken)
	{
		try {
			$refreshSecretKey = $_ENV['JWT_REFRESH_SECRET'];
			$decoded = JWT::decode($refreshToken, new Key($refreshSecretKey, 'HS256'));

			return $decoded;
		} catch (\Exception $e) {
			return null;
		}
	}

	// 리프레시 토큰에서 유저 정보 가져오기
	public static function getUserFromRefreshToken($refreshToken)
	{
		$decoded = self::validateRefreshToken($refreshToken);

		if ($decoded && $decoded->sub) {
			return User::find($decoded->sub);
		}
		return null;
	}

	// 유저 닉네임 랜덤 생성
	public static function createNickname()
	{
		$cities = [
			'서울', '부산', '대구', '인천', '광주', '대전', '울산', '제주', '수원', '성남',
			'도쿄', '베이징', '상하이', '홍콩', '방콕', '싱가포르', '쿠알라룸푸르', '하노이', '호치민', '자카르타',
			'파리', '런던', '로마', '베를린', '암스테르담', '바르셀로나', '마드리드', '리스본', '부다페스트', '프라하',
			'뉴욕', '로스앤젤레스', '시카고', '샌프란시스코', '토론토', '밴쿠버', '멕시코시티', '상파울루', '리우데자네이루', '부에노스아이레스',
			'카이로', '이스탄불', '두바이', '리야드', '테헤란', '카불', '바그다드', '다마스쿠스', '베이루트',
			'시드니', '멜버른', '브리즈번', '퍼스', '오클랜드', '웰링턴', '마닐라', '다카', '콜롬보', '카트만두',
			'모스크바', '상트페테르부르크', '키예프', '민스크', '바쿠', '트빌리시', '아스타나', '타슈켄트', '알마티', '비슈케크',
			'취리히', '제네바', '빈', '헬싱키', '스톡홀름', '코펜하겐', '오슬로', '레이캬비크', '브뤼셀', '루체른',
			'나이로비', '케이프타운', '요하네스버그', '라고스', '카사블랑카', '알제', '아디스아바바', '아크라', '다카르', '트리폴리'
		];

		// 재미있는 형용사 배열 (한글)
		$adjectives = [
			'용감한', '멋진', '재빠른', '귀여운', '느긋한', '행복한', '고요한',
			'똑똑한', '비밀스러운', '밝은', '쾌활한', '조용한', '화려한', '따뜻한', '신비로운'
		];

		// 재미있는 명사 배열 (한글)
		$nouns = [
			'호랑이', '사자', '여우', '곰', '늑대', '펭귄', '독수리', '용', '상어', '판다',
			'부엉이', '코끼리', '돌고래', '고양이', '개구리', '나비', '올빼미', '햄스터', '하마', '고래'
		];

		// 랜덤으로 도시, 형용사, 명사 선택
		$randomCity = $cities[array_rand($cities)];
		$randomAdjective = $adjectives[array_rand($adjectives)];
		$randomNoun = $nouns[array_rand($nouns)];

		// 재미있는 닉네임 생성
		$nickname = $randomAdjective . ' ' . $randomCity . '의 ' . $randomNoun;

		return $nickname;
	}
}
