<?php

namespace App\Controllers\Api;

use App\Models\Partner;
use App\Models\ServiceDetail;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Illuminate\Database\Capsule\Manager as Capsule;

use Database;

class ServiceDetailApiController
{
	public static function create()
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

			// 파트너 데이터 가져오기
			$partner = Partner::find($input['partnerIdx']);

			if (!$partner) {
				return ResponseHelper::jsonResponse(['error' => '파트너 정보를 찾을 수 없습니다.'], 404);
			}

			$serviceDetail = new ServiceDetail();
            $serviceDetail->partner_idx = $partner->partner_idx;
			$serviceDetail->service_name = $input['serviceName'];
			$serviceDetail->service_description = $input['serviceDescription'];
			$serviceDetail->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse(['message' => '서비스 정보를 저장했습니다.'], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

    public static function update()
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

			// 파트너 데이터 가져오기
			$partner = Partner::find($input['partnerIdx']);

			if (!$partner) {
				return ResponseHelper::jsonResponse(['error' => '파트너 정보를 찾을 수 없습니다.'], 404);
			}

			$serviceDetail = ServiceDetail::where('service_detail_idx', $input['serviceDetailIdx'])->first();

			if(empty($serviceDetail)) {
				header('Location: /manage/login');
			}

			$serviceDetail->service_name = $input['serviceName'];
			$serviceDetail->service_description = $input['serviceDescription'];
			$serviceDetail->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse(['message' => '서비스 정보를 저장했습니다.'], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function delete()
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

			// 파트너 데이터 가져오기
			$partner = Partner::find($input['partnerIdx']);

			if (!$partner) {
				return ResponseHelper::jsonResponse(['error' => '파트너 정보를 찾을 수 없습니다.'], 404);
			}

			$serviceDetail = ServiceDetail::where('service_detail_idx', $input['serviceDetailIdx'])->first();
			$serviceDetail->delete();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse(['message' => '서비스 정보를 저장했습니다.'], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
