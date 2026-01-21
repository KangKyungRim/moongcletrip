<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\PartnerFavorite;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Database;

use Carbon\Carbon;

class PartnerFavoriteApiController
{
	public function toggleFavorite()
	{
		$input = json_decode(file_get_contents("php://input"), true);

		$partnerIdx = intval($input['partnerIdx']);
		$userIdx = intval($input['userIdx']);

		if (empty($userIdx) || empty($partnerIdx)) {
			return ResponseHelper::jsonResponse(['message' => '로그인 유저가 아닙니다.'], 200);
		}

		$favoriteExist = PartnerFavorite::where('partner_idx', $partnerIdx)
			->where('user_idx', $userIdx)
			->where('target', 'partner')
			->first();

		if (empty($favoriteExist)) {
			PartnerFavorite::create([
				'user_idx' => $userIdx,
				'partner_idx' => $partnerIdx,
			]);

			return ResponseHelper::jsonResponse(['message' => '찜에 추가했습니다.'], 200);
		} else {
			$favoriteExist->delete();

			return ResponseHelper::jsonResponse(['message' => '찜에서 삭제했습니다.'], 200);
		}
	}

	public function toggleFavoriteMoongcleoffer()
	{
		$input = json_decode(file_get_contents("php://input"), true);

		$partnerIdx = intval($input['partnerIdx']);
		$userIdx = intval($input['userIdx']);
		$moongcleofferIdx = intval($input['moongcleofferIdx']);
		$target = $input['target'];

		if (empty($userIdx) || empty($partnerIdx) || empty($target)) {
			return ResponseHelper::jsonResponse(['message' => '로그인 유저가 아닙니다.'], 200);
		}

		$favoriteExist = PartnerFavorite::where('partner_idx', $partnerIdx)
			->where('user_idx', $userIdx)
			->where('target', $target)
			->first();

		if (empty($favoriteExist)) {
			PartnerFavorite::create([
				'user_idx' => $userIdx,
				'partner_idx' => $partnerIdx,
				'moongcleoffer_idx' => $moongcleofferIdx,
				'target' => $target,
			]);

			return ResponseHelper::jsonResponse(['message' => '찜에 추가했습니다.'], 200);
		} else {
			$favoriteExist->delete();

			return ResponseHelper::jsonResponse(['message' => '찜에서 삭제했습니다.'], 200);
		}
	}
}
