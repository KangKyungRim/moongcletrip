<?php

namespace App\Services;

use App\Models\MoongcleDeal;

use App\Services\RecommendOffers;

use Carbon\Carbon;

class RecommandDealHandlerService
{
	public function processPendingDealMatch()
	{
		$moongcledealsQuery = MoongcleDeal::where('status', 'in_progress');

		$perPage = 500; // 한 번에 처리할 레코드 수
		$executionLimit = 60; // 실행 시간 제한 (초)
		$startTime = time(); // 시작 시간

		while (true) {
			// 남은 시간이 없는 경우 루프 종료
			if (time() - $startTime >= $executionLimit) {
				break;
			}

			// 조건에 맞는 데이터 가져오기
			$moongcledeals = $moongcledealsQuery->limit($perPage)
				->get();

			if ($moongcledeals->isEmpty()) {
				sleep(5);

				continue;
			}

			foreach ($moongcledeals as $moongcledeal) {
				try {
					// 상태를 진행 중으로 변경
					$moongcledeal->status = 'matching';
					$moongcledeal->status_view = 'matching';
					$moongcledeal->save();

					RecommendOffers::recommendOffers($moongcledeal);

					// 처리 완료 상태로 업데이트
					$moongcledeal->status = 'matched';
					$moongcledeal->status_view = 'matched';
					$moongcledeal->save();

					// 남은 시간이 초과되면 루프 종료
					if (time() - $startTime >= $executionLimit) {
						break 2; // 이중 루프 종료
					}
				} catch (\Exception $e) {
					// 오류 발생 시 상태 업데이트 및 에러 로깅
					$moongcledeal->status = 'error';
					$moongcledeal->status_view = 'error';
					$moongcledeal->save();

					error_log($e->getMessage());

					// 남은 시간이 초과되면 루프 종료
					if (time() - $startTime >= $executionLimit) {
						break 2; // 이중 루프 종료
					}
				}
			}
		}
	}
}
