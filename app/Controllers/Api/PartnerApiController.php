<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\PartnerDraft;
use App\Models\PartnerUser;
use App\Models\UserVerification;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\EmailService;

use Carbon\Carbon;

class PartnerApiController
{
	public static function partnerRegister()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->user_level >= 5) {
				// 이미 로그인한 유저라면 대시보드로 이동
				header('Location: /dashboard');
				exit;
			}
		}

		$data = json_decode(file_get_contents("php://input"), true);

		// 입력 검증
		if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
			return ResponseHelper::jsonResponse(['error' => 'Name and Email and password are required.'], 400);
		}

		$name = $data['name'];
		$email = $data['email'];
		$mobileNumber = $data['mobileNumber'];
		$password = $data['password'];

		// 이메일 중복 체크
		$existingUser = PartnerUser::where('partner_user_email', $email)->first();
		if ($existingUser) {
			return ResponseHelper::jsonResponse(['error' => 'Email already exists.'], 409);
		}

		// 비밀번호 해싱
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// 유저 생성 및 저장
		$user = new PartnerUser();
		$user->partner_user_email = $email;
		$user->partner_user_password = $hashedPassword;
		$user->partner_user_nickname = $name;
		$user->partner_user_name = $name;
		$user->partner_user_phone_number = $mobileNumber;
		$user->partner_user_level = 5;
		$user->partner_user_login_type = 'email';
		$user->partner_user_status = 'prepare';
		$user->partner_user_created_at = Carbon::now();
		$user->partner_user_updated_at = Carbon::now();
		$user->save();

		$emailService = new EmailService();

		$token = bin2hex(random_bytes(16));
		$userVerification = new UserVerification();
		$userVerification->user_idx = $user->partner_user_idx;
		$userVerification->token = $token;
		$userVerification->created_at = Carbon::now();
		$userVerification->save();

		// 사용자 정보 및 이메일 템플릿 로드
		$variables = [
			'verification_url' => 'https://' . $_ENV['WWW_HOST_NAME'] . '/partner-user/verify?token=' . $token,
		];
		$emailService->loadTemplate('Templates/email_register_verification.html', $variables);

		// 이메일 전송
		$emailService->sendMail($email, $email, '뭉클트립 메일 인증 안내');

		return ResponseHelper::jsonResponse(['message' => 'User registered successfully.'], 200);
	}

	// 이메일 로그인 (POST)
	public static function partnerLogin()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$email = $data['email'];
		$password = $data['password'];

		// 이메일로 유저 검색
		$user = PartnerUser::where('partner_user_email', $email)->where('partner_user_status', 'normal')->first();

		if (!$user || !password_verify($password, $user->partner_user_password)) {
			return ResponseHelper::jsonResponse(['error' => 'Invalid credentials'], 401);
		}

		// 액세스 토큰 및 리프레시 토큰 생성
		$accessToken = MiddleHelper::createPartnerAccessToken($user);
		$refreshToken = MiddleHelper::createPartnerRefreshToken($user);

		return ResponseHelper::jsonResponse([
			'access_token' => $accessToken,
			'refresh_token' => $refreshToken,
			'user_id' => $user->partner_user_idx
		], 200);
	}

	// 파트너 생성 및 업데이트 API
	public static function storeDraft()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		// 입력된 데이터 가져오기
		$input = json_decode(file_get_contents("php://input"), true);

		// 필수 데이터 확인
		if (empty($input['partnerName']) || empty($input['partnerCategory']) || empty($input['partnerEmail']) || empty($input['partnerPhonenumber'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		// 유효성 검증
		if (!filter_var($input['partnerEmail'], FILTER_VALIDATE_EMAIL)) {
			return ResponseHelper::jsonResponse(['error' => '유효하지 않은 이메일 형식입니다.'], 400);
		}

		if (!is_numeric($input['partnerPhonenumber']) || strlen($input['partnerPhonenumber']) > 11) {
			return ResponseHelper::jsonResponse(['error' => '유효하지 않은 전화번호 형식입니다.'], 400);
		}

		try {
			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			$partnerIsNew = true;

			if (!empty($input['partnerIdx'])) {
				$partner = Partner::find($input['partnerIdx']);
				$partnerDraft = $partner->draft;
				$partnerIsNew = false;
			} else {
				$partner = new Partner();
				$partnerDraft = new PartnerDraft();
			}

			// 파트너 데이터 저장
			$partner->partner_name = $input['partnerName'];
			$partner->partner_category = $input['partnerCategory'];
			$partner->partner_status = 'disabled';
			$partner->save();

			// 파트너 데이터 저장
			$partnerDraft->partner_idx = $partner->partner_idx;
			$partnerDraft->partner_name = $input['partnerName'];
			$partnerDraft->partner_category = $input['partnerCategory'];

			if ($input['partnerCategory'] != 'stay') {
				$input['partnerType'] = null;
				$input['partnerGrade'] = null;
			}

			$partnerDraft->partner_type = $input['partnerType'] ?? null;

			if ($input['partnerType'] != 'hotel') {
				$input['partnerGrade'] = null;
			}

			$partnerDraft->partner_grade = $input['partnerGrade'] ?? null;
			$partnerDraft->partner_zip = $input['zipcode'] ?? null;
			$partnerDraft->partner_address1 = $input['address1'] ?? null;
			$partnerDraft->partner_address2 = $input['address2'] ?? null;
			$partnerDraft->partner_address3 = $input['address3'] ?? null;
			$partnerDraft->partner_city = $input['mapCity'] ?? null;
			$partnerDraft->partner_region = $input['mapRegion'] ?? null;
			$partnerDraft->partner_region_detail = $input['mapRegionDetail'] ?? null;
			$partnerDraft->partner_latitude = $input['latitude'] ?? null;
			$partnerDraft->partner_longitude = $input['longitude'] ?? null;
			$partnerDraft->partner_phonenumber = $input['partnerPhonenumber'];
			$partnerDraft->partner_email = $input['partnerEmail'];
			$partnerDraft->partner_reservation_phonenumber = $input['partnerReservationPhonenumber'];
			$partnerDraft->partner_reservation_email = $input['partnerReservationEmail'];
			$partnerDraft->partner_manager_phonenumber = $input['partnerManagerPhonenumber'];
			$partnerDraft->partner_manager_email = $input['partnerManagerEmail'];
			$partnerDraft->partner_search_badge = $input['searchBadge'] ?? null;
			$partnerDraft->is_approved = false;
			$partnerDraft->save();

			if ($partnerIsNew) {
				$partner->partnerUsers()->attach($checkUser->partner_user_idx, ['is_manager' => true]);
			}

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '파트너가 성공적으로 저장되었습니다.',
				'partner_id' => $partner->partner_idx,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function approve()
	{
		// 파트너 관리자 로그인 확인
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		// 입력된 데이터 가져오기
		$input = json_decode(file_get_contents("php://input"), true);

		// 필수 데이터 확인 (partnerIdx 확인)
		if (empty($input['partnerIdx'])) {
			return ResponseHelper::jsonResponse(['error' => '파트너 ID가 필요합니다.'], 400);
		}

		try {
			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			// 파트너 초안 가져오기
			$partnerDraft = PartnerDraft::where('partner_idx', $input['partnerIdx'])
				->where('is_approved', false)
				->first();

			if (!$partnerDraft) {
				return ResponseHelper::jsonResponse(['error' => '승인할 초안을 찾을 수 없습니다.'], 404);
			}

			// 파트너 본 데이터 가져오기
			$partner = Partner::find($input['partnerIdx']);

			if (!$partner) {
				return ResponseHelper::jsonResponse(['error' => '파트너 정보를 찾을 수 없습니다.'], 404);
			}

			// 파트너 본 데이터 업데이트
			$partner->partner_name = $partnerDraft->partner_name;
			$partner->partner_category = $partnerDraft->partner_category;
			$partner->partner_type = $partnerDraft->partner_type ?? null;
			$partner->partner_grade = $partnerDraft->partner_grade ?? null;
			$partner->partner_zip = $partnerDraft->partner_zip ?? null;
			$partner->partner_address1 = $partnerDraft->partner_address1 ?? null;
			$partner->partner_address2 = $partnerDraft->partner_address2 ?? null;
			$partner->partner_address3 = $partnerDraft->partner_address3 ?? null;
			$partner->partner_city = $partnerDraft->partner_city ?? null;
			$partner->partner_region = $partnerDraft->partner_region ?? null;
			$partner->partner_region_detail = $partnerDraft->partner_region_detail ?? null;
			$partner->partner_latitude = $partnerDraft->partner_latitude ?? null;
			$partner->partner_longitude = $partnerDraft->partner_longitude ?? null;
			$partner->partner_phonenumber = $partnerDraft->partner_phonenumber;
			$partner->partner_email = $partnerDraft->partner_email;
			$partner->partner_reservation_phonenumber = $partnerDraft->partner_reservation_phonenumber ?? null;
			$partner->partner_reservation_email = $partnerDraft->partner_reservation_email ?? null;
			$partner->partner_manager_phonenumber = $partnerDraft->partner_manager_phonenumber ?? null;
			$partner->partner_manager_email = $partnerDraft->partner_manager_email ?? null;
			$partner->partner_search_badge = $partnerDraft->partner_search_badge ?? null;
			$partner->partner_status = 'disabled';  // 승인 시 활성화

			$partner->save();

			// 초안 승인 상태 업데이트
			$partnerDraft->is_approved = true;
			$partnerDraft->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse(['message' => '파트너가 성공적으로 승인되었습니다.'], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
