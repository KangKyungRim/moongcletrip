<?php

namespace App\Controllers\Api;

use App\Models\MoongcleDeal;
use App\Models\MoongcleDealTag;
use App\Models\MoongcleTag;

use Carbon\Carbon;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Models\MoongcleMatch;

class MoongcledealApiController
{
	public static function storeMain()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		MoongcleDeal::where('user_idx', $user->user_idx)
			->where('moongcledeal_create_complete', false)
			->delete();

		$moongcledeal = new MoongcleDeal();
		$moongcledeal->user_idx = $user->user_idx;
		$moongcledeal->priority = $input['priority'];
		$moongcledeal->selected = $input['selected'];
		$moongcledeal->status = 'in_progress';
		$moongcledeal->status_view = 'in_progress';
		$moongcledeal->moongcledeal_create_complete = true;
		$moongcledeal->save();

		if (!empty($input['priority'])) {
			MoongcleDealTag::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)->delete();

			foreach ($input['priority'] as $order => $tagName) {
				$tag = MoongcleTag::where('tag_machine_name', $tagName['tag_machine_name'])->first();

				if ($tag) {
					MoongcleDealTag::create([
						'moongcledeal_idx' => $moongcledeal->moongcledeal_idx,
						'tag_idx' => $tag->tag_idx,
						'tag_order' => $order + 1,
						'status' => 'active'
					]);
				}
			}
		}

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'message' => '뭉클딜을 저장했습니다.',
			'moongcledealIdx' => $moongcledeal->moongcledeal_idx,
		], 200);
	}

	public static function store()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
        }
		// } else {
		// 	header('Location: /mypage');
		// }

		$input = json_decode(file_get_contents("php://input"), true);

		MoongcleDeal::where('user_idx', $user->user_idx)
			->where('moongcledeal_create_complete', false)
			->delete();

		$moongcledeal = new MoongcleDeal();
		$moongcledeal->user_idx = $user->user_idx;
		$moongcledeal->priority = $input['priority'];
		$moongcledeal->selected = $input['selected'];
		$moongcledeal->status = 'pending';
		$moongcledeal->status_view = 'pending';
		$moongcledeal->moongcledeal_create_complete = false;
		$moongcledeal->save();

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'message' => '뭉클딜을 저장했습니다.',
			'moongcledealIdx' => $moongcledeal->moongcledeal_idx,
		], 200);
	}

	public static function update()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);
		$moongcledeal->priority = $input['priority'];
		$moongcledeal->selected = $input['selected'];
		$moongcledeal->status = 'pending';
		$moongcledeal->status_view = 'pending';
		$moongcledeal->moongcledeal_create_complete = false;
		$moongcledeal->save();

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'message' => '뭉클딜을 저장했습니다.',
			'moongcledealIdx' => $moongcledeal->moongcledeal_idx,
		], 200);
	}

	public static function editInProgress()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if(!empty($input['represent'])) {
			MoongcleDeal::where('user_idx', $user->user_idx)->update(['represent' => false]);
		}

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);

		if ($user->user_idx != $moongcledeal->user_idx) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클딜 유저가 다릅니다.',
			], 200);
		}

		$moongcledeal->selected = $input['selected'];
		$moongcledeal->represent = $input['represent'];
		$moongcledeal->status = 'in_progress';
		$moongcledeal->status_view = 'in_progress';
		$moongcledeal->save();

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '뭉클딜을 저장했습니다.',
			'moongcledealIdx' => $moongcledeal->moongcledeal_idx,
		], 200);
	}

	public static function priority()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
        }         
		// } else {
		// 	header('Location: /mypage');
		// }

		$input = json_decode(file_get_contents("php://input"), true);

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);
		$moongcledeal->priority = $input['priority'];
		$moongcledeal->status = 'in_progress';
		$moongcledeal->status_view = 'in_progress';
		$moongcledeal->moongcledeal_create_complete = true;
		$moongcledeal->save();

		if (!empty($input['priority'])) {
			MoongcleDealTag::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)->delete();

			foreach ($input['priority'] as $order => $tagName) {
				$tag = MoongcleTag::where('tag_machine_name', $tagName['tag_machine_name'])->first();

				if ($tag) {
					MoongcleDealTag::create([
						'moongcledeal_idx' => $moongcledeal->moongcledeal_idx,
						'tag_idx' => $tag->tag_idx,
						'tag_order' => $order + 1,
						'status' => 'active'
					]);
				}
			}
		}

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'message' => '뭉클딜을 저장했습니다.',
			'moongcledealIdx' => $moongcledeal->moongcledeal_idx,
		], 200);
	}

	public static function moongcleTagEncode()
	{
		$input = json_decode(file_get_contents("php://input"), true);

		$tags = [];
		foreach ($input['tags'] as $tag) {
			if (!empty($tag['tagName'])) {
				$tags[] = [
					'tag_name' => $tag['tagName'],
					'tag_machine_name' => $tag['tagMachineName'],
				];
			} else {
				$tags[] = [
					'tag_name' => $tag['tag_name'],
					'tag_machine_name' => $tag['tag_machine_name'],
				];
			}
		}

		$encodedTags = base64_encode(json_encode($tags));

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'encodedTags' => $encodedTags,
			'success' => true,
		], 200);
	}

	public static function stop()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);

		if ($moongcledeal->user_idx == $user->user_idx) {
			$moongcledeal->status = 'stop';
			$moongcledeal->save();

			MoongcleDealTag::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)->delete();

			MoongcleMatch::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)
				->where('notification_status', 'pending')
				->delete();
		} else {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클딜 유저가 다릅니다.',
			], 200);
		}

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '뭉클딜을 저장했습니다.',
		], 200);
	}

	public static function reopen()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);

		if ($moongcledeal->user_idx == $user->user_idx) {
			$moongcledeal->status = 'in_progress';
			$moongcledeal->status_view = 'in_progress';
			$moongcledeal->save();
		} else {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클딜 유저가 다릅니다.',
			], 200);
		}

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '뭉클딜을 저장했습니다.',
		], 200);
	}

	public static function changeTitle()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);

		if ($moongcledeal->user_idx == $user->user_idx) {
			$moongcledeal->title = $input['title'];
			$moongcledeal->save();
		} else {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클딜 유저가 다릅니다.',
			], 200);
		}

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '뭉클딜 타이틀을 저장했습니다.',
		], 200);
	}

	public static function moreOpen()
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		$input = json_decode(file_get_contents("php://input"), true);

		$moongcledeal = MoongcleDeal::find($input['moongcledealIdx']);

		if ($moongcledeal->user_idx == $user->user_idx) {
			$moongcledeal->status = 'in_progress';
			$moongcledeal->status_view = 'in_progress';
			$moongcledeal->save();
		} else {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클딜 유저가 다릅니다.',
			], 200);
		}

		// 결과를 JSON 형식으로 반환합니다.
		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '뭉클딜을 추가로 받습니다.',
		], 200);
	}
}
