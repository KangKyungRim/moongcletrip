<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\MoongcleDeal;
use App\Models\StayMoongcleOffer;
use App\Models\MoongcleMatch;
use App\Models\BenefitItem;
use App\Services\FCMNotificationService;
use App\Models\Token;
use App\Models\Notification;
use App\Models\User;

use Carbon\Carbon;
use PDO;

class SendMoongcledealService
{
	public static function sendMoongcledeal()
	{
		$moongcleMatches = MoongcleMatch::where('moongcle_match_status', 'enabled')
			->where('notification_status', 'pending')
			->where('notification_time', '<', Carbon::now())
			->whereNull('processing_at')
			->get();

		if ($moongcleMatches->isEmpty()) {
			return;
		} else {
			$now = Carbon::now();
			foreach ($moongcleMatches as $match) {
				$match->update(['processing_at' => $now]);
			}
		}

		$fcmService = new FCMNotificationService($_ENV['FCM_KEY']);

		foreach ($moongcleMatches as $match) {
			$moongcledeal = $match->deal;
			$moongcleoffer = $match->getProduct();
			$partner = Partner::find($moongcleoffer->partner_idx);

			if (empty($moongcledeal->user_idx)) {
				continue;
			}

			$tokens = Token::where('user_idx', $moongcledeal->user_idx)
				->where('token_is_active', true)
				->get();

			if ($tokens->isEmpty()) {
				$tokens = Token::where('guest_idx', $moongcledeal->user_idx)
					->where('token_is_active', true)
					->get();

				if ($tokens->isEmpty()) {
					$match->notification_status = 'token_x';
					$match->save();

					$matches = MoongcleMatch::where('moongcle_match_status', 'enabled')
						->where('moongcledeal_idx', $match->moongcledeal_idx)
						->get();

					foreach ($matches as $matche) {
						$matche->notification_status = 'sent';
						$matche->save();
					}

					$user = User::find($moongcledeal->user_idx);

					if (empty($user->user_nickname) || strpos($user->user_nickname, 'Guest') !== false) {
						$data['user_name'] = '뭉클러';
					} else {
						$data['user_name'] = $user->user_nickname;
					}

					$data['partner_name'] = $partner->partner_name;
					$data['moongcledeal_idx'] = $match->moongcledeal_idx;
					$priorityArray = [];

					if (!empty($moongcledeal->priority)) {
						foreach ($moongcledeal->priority as $priority) {
							if (!empty($priority['tag_name'])) {
								$priorityArray[] = '#' . $priority['tag_name'];
							}
						}
					}

					$data['priority'] = implode(', ', $priorityArray);

					$items = BenefitItem::where('item_idx', $moongcleoffer->base_product_idx)->get();

					$benefits = [];
					if (!$items->isEmpty()) {
						foreach ($items as $item) {
							if (!empty($item->benefit_name)) {
								$benefits[] = '#' . $item->benefit_name;
							}
						}
					}

					$data['benefits'] = implode(', ', $benefits);

					$message = [];

					if (!empty($moongcleoffer->stay_moongcleoffer_idx)) {
						$stayMoongcleoffer = StayMoongcleOffer::find($moongcleoffer->stay_moongcleoffer_idx);

						if (!empty($stayMoongcleoffer->custom_message)) {
							$message = explode(':-:', $stayMoongcleoffer->custom_message);
						}
					}

					if (!empty($message[0]) && !empty($message[1])) {
						$noti = [
							'title' => $message[0],
							'message' => $message[1],
							'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
						];
					} else {
						$noti = SendMoongcledealService::randomMessage($data);
					}

					Notification::create([
						'user_idx' => $moongcledeal->user_idx,
						'base_idx' => $match->moongcledeal_idx,
						'target_idx' => $match->moongcle_match_idx,
						'notification_type' => 'moongcledeal',
						'title' => $noti['title'],
						'message' => $noti['message'],
						'link' => $noti['link'],
						'is_read' => false,
						'push_status' => 'token_x',
					]);

					continue;
				}
			}

			$userIdx = 0;

			foreach ($tokens as $token) {
				$data = [];

				if (!empty($token->user_idx)) {
					$userIdx = $token->user_idx;
				} else if (!empty($token->guest_idx)) {
					$userIdx = $token->guest_idx;
				} else {
					continue;
				}

				$user = User::find($userIdx);

				if (strpos($user->user_nickname, 'Guest') !== false) {
					$data['user_name'] = '뭉클러';
				} else {
					$data['user_name'] = $user->user_nickname;
				}

				$data['partner_name'] = $partner->partner_name;
				$data['moongcledeal_idx'] = $match->moongcledeal_idx;
				$priorityArray = [];

				if (!empty($moongcledeal->priority)) {
					foreach ($moongcledeal->priority as $priority) {
						if (!empty($priority['tag_name'])) {
							$priorityArray[] = '#' . $priority['tag_name'];
						}
					}
				}

				$data['priority'] = implode(', ', $priorityArray);

				$items = BenefitItem::where('item_idx', $moongcleoffer->base_product_idx)->get();

				$benefits = [];
				if (!$items->isEmpty()) {
					foreach ($items as $item) {
						if (!empty($item->benefit_name)) {
							$benefits[] = '#' . $item->benefit_name;
						}
					}
				}

				$data['benefits'] = implode(', ', $benefits);

				$message = [];

				if (!empty($moongcleoffer->stay_moongcleoffer_idx)) {
					$stayMoongcleoffer = StayMoongcleOffer::find($moongcleoffer->stay_moongcleoffer_idx);

					if (!empty($stayMoongcleoffer->custom_message)) {
						$message = explode(':-:', $stayMoongcleoffer->custom_message);
					}
				}

				if (!empty($message[0]) && !empty($message[1])) {
					$noti = [
						'title' => $message[0],
						'message' => $message[1],
						'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
					];
				} else {
					$noti = SendMoongcledealService::randomMessage($data);
				}

				Notification::create([
					'user_idx' => $userIdx,
					'base_idx' => $match->moongcledeal_idx,
					'target_idx' => $match->moongcle_match_idx,
					'notification_type' => 'moongcledeal',
					'title' => $noti['title'],
					'message' => $noti['message'],
					'link' => $noti['link'],
					'is_read' => false,
					'push_status' => 'success',
				]);
			}

			$match->notification_status = 'sent';
			$match->save();

			$matches = MoongcleMatch::where('moongcle_match_status', 'enabled')
				->where('moongcledeal_idx', $match->moongcledeal_idx)
				->get();

			foreach ($matches as $matche) {
				$matche->notification_status = 'sent';
				$matche->save();
			}

			$result = $fcmService->sendNotification($tokens, $noti['title'], $noti['message'], $noti['link'], $match->moongcledeal_idx, $match->moongcle_match_idx);
		}

		//FCM 알림 PUSH 되어야지, 뭉클딜 목록에 노출가능한 상태로 변경
		$moongcledeal->status_view = 'completed';
		$moongcledeal->save();
		//moongcledeals에 컬럼 하나 업데이트 (push완료) / 뭉클딜 추가했을때 다시 pending
	}

	public static function randomMessage($data)
	{
		$messages = [
			[
				'title' => '두근두근, 설레는 뭉클딜 도착!✨',
				'message' => '취향에 딱 맞는 ' . $data['partner_name'] . ' 뭉클딜 제안이 도착했어요. 지금 확인해볼까요?',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '기다리시던 뭉클딜 도착! 🎁',
				'message' => '취향에 꼭 맞는 여행을 찾았어요. ' . $data['partner_name'] . '에서 도착한 뭉클딜을 확인해보세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => $data['partner_name'] . '에서 뭉클딜이 도착했어요!🎁',
				'message' => '매칭된 숙소에서 단독 뭉클딜 혜택이 기다려요. 지금 확인해보세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '새로운 뭉클딜 도착!🌟',
				'message' => $data['user_name'] . '님, 취향에 딱 맞는 숙소 ' . $data['partner_name'] . ' 드디어 찾았어요! ' . $data['benefits'] . ' 포함 혜택까지! 놓치지 마세요!🏨',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => $data['partner_name'] . '에서 도착한 뭉클딜!💎',
				'message' => '추천된 뭉클딜 제안 숙소에서 여유로운 시간을 보내세요. 지금 확인하세요!✨',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '똑똑, 설레는 뭉클딜이 배송되었어요💌',
				'message' => '등록하신 취향에 맞는 뭉클딜이 도착했어요!✨ ' . $data['partner_name'] . '에서 도착한 제안을 확인해볼까요?',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => $data['user_name'] . '님 취향에 맞는 여행을 찾았어요! ',
				'message' => '등록된 뭉클태그 추천 숙소 ' . $data['partner_name'] . '! 드디어 발견했어요. 지금 확인해보세요! 🌟',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '떠나고 싶은 순간, 내 취향 뭉클딜 도착!🌟',
				'message' => $data['user_name'] . '님, ' . '취향에 딱 맞는 숙소 ' . $data['partner_name'] . ' 마침내 찾았어요! 추가 혜택과 함께 만나보세요!🎁',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '고객님을 위한 뭉클딜이 도착했어요!🚀',
				'message' => '등록하신 취향 추천 숙소 ' . $data['partner_name'] . '에서 특별 혜택이 도착했어요. 확인해보세요!💎',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '기다리던 뭉클딜 도착!🚀',
				'message' => '취향에 꼭 맞는 ' . $data['partner_name'] . '에서 제공되는 한정 혜택, 지금 확인해보세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => $data['user_name'] . '님에게 도착한 뭉클딜!💌',
				'message' => '뭉클딜 혜택으로 딱 추천 드리는 ' . $data['partner_name'] . '에서 여유로운 시간을 보내세요. 지금 확인하세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '오늘도 즐거운 하루 보내시고 계신가요?🌿',
				'message' => '고객님 스타일에 꼭 맞는 ' . $data['partner_name'] . ' 뭉클딜이 도착했어요. 지금 확인해보세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '설렘을 더해줄 새로운 뭉클딜 도착!🎉',
				'message' => '여행 고민 끝! 🏖️ 등록해두신 취향에 딱 맞는 ' . $data['partner_name'] . '에서 뭉클딜 제안이 도착했어요. 지금 확인해보세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '마음에 쏙 드는 숙소를 찾았어요! 🏨',
				'message' => $data['partner_name'] . '에서 도착한 새로운 뭉클딜을 확인하고 혜택까지 추가로 챙겨가세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '똑똑, 기다리시던 뭉클딜이 도착하였습니다🏨',
				'message' => '특별한 여행을 위한 뭉클딜 도착! 🌟 ' . $data['partner_name'] . ' 특가 혜택과 함께 여행 준비 끝내보세요!',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '✨ 딱 맞는 뭉클딜 혜택! 지금 만나보세요!',
				'message' => $data['user_name'] . '님 스타일에 꼭 맞는 숙소를 찾았어요! 🏖️ 추가 혜택과 함께 지금 예약해보세요.',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '기다리시던 뭉클딜 도착! 🛎️',
				'message' => $data['partner_name'] . '에서 더 특별한 혜택과 함께 설레는 여행을 떠나보세요',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '설렘을 더할 완벽한 숙소 추천!😊',
				'message' => $data['user_name'] . '님, 방금 도착한 ' . $data['partner_name'] . ' ✨ 뭉클딜 혜택을 확인해보세요.',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '특별한 여행을 위한 뭉클딜 도착! 🌟',
				'message' => $data['user_name'] . '님만을 위한 ' . $data['partner_name'] . ' 뭉클딜 혜택을 지금 확인해보세요',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
			[
				'title' => '설레는 여행을 위한 완벽한 제안!🌿',
				'message' => $data['user_name'] . ' 님에게 도착한 새로운 ' . $data['partner_name'] . ' 뭉클딜을 확인해보세요',
				'link' => $_ENV['APP_HTTP'] . '/moongcledeals?moongcledealIdx=' . $data['moongcledeal_idx']
			],
		];

		$randomIndex = array_rand($messages);

		return $messages[$randomIndex];
	}
}
