<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\PartnerFaq;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class PartnerMiniHomeApiController
{
	public static function editPartnerFaq()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['faqs'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			PartnerFaq::where('partner_idx', $input['partnerIdx'])->delete();

			$faqsToInsert = [];
			foreach ($input['faqs'] as $i => $question) {
				$faqsToInsert[] = [
					'partner_idx' => $input['partnerIdx'],
					'question' => $question['question'],
					'answer' => $question['answer'],
					'faq_order' => $i,
				];
			}

			PartnerFaq::insert($faqsToInsert);

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '자주 묻는 질문을 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '자주 묻는 질문 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
