<?php

namespace App\Services;

use App\Models\StayMoongcleOffer;
use App\Models\RoomPrice;
use App\Models\MoongcleOffer;

use Carbon\Carbon;

class MoongcleofferService
{
	public static function changeMoongcleofferStatus()
	{
		$now = Carbon::now();

		$stayMoongcleoffers = StayMoongcleOffer::where('sale_start_date', '<=', $now)
			->where('sale_end_date', '>=', $now)
			->where('stay_moongcleoffer_status', 'disabled')
			->get();

		foreach ($stayMoongcleoffers as $stayMoongcleoffer) {
			$stayMoongcleoffer->stay_moongcleoffer_status = 'enabled';
			$stayMoongcleoffer->save();

			foreach ($stayMoongcleoffer->rooms as $roomIdx) {
				$moongcleoffers = null;

				$roomPrices = RoomPrice::where('room_idx', $roomIdx)
					->where('rateplan_idx', $rateplanIdx)
					->get();

				if (empty($roomPrices[0])) {
					continue;
				}

				$moongcleoffers = MoongcleOffer::where('stay_moongcleoffer_idx', $stayMoongcleoffer->stay_moongcleoffer_idx)
					->get();

				foreach ($moongcleoffers as $moongcleoffer) {
					$moongcleoffer->moongcleoffer_status = 'enabled';
					$moongcleoffer->save();
				}
			}
		}

		$stayMoongcleoffers = StayMoongcleOffer::where('sale_end_date', '<', $now)
			->where('stay_moongcleoffer_status', 'enabled')
			->get();

		foreach ($stayMoongcleoffers as $stayMoongcleoffer) {
			$stayMoongcleoffer->stay_moongcleoffer_status = 'disabled';
			$stayMoongcleoffer->save();

			foreach ($stayMoongcleoffer->rooms as $roomIdx) {
				$moongcleoffers = null;

				$roomPrices = RoomPrice::where('room_idx', $roomIdx)
					->where('rateplan_idx', $rateplanIdx)
					->get();

				if (empty($roomPrices[0])) {
					continue;
				}

				$moongcleoffers = MoongcleOffer::where('stay_moongcleoffer_idx', $stayMoongcleoffer->stay_moongcleoffer_idx)
					->get();

				foreach ($moongcleoffers as $moongcleoffer) {
					$moongcleoffer->moongcleoffer_status = 'disabled';
					$moongcleoffer->save();
				}
			}
		}
	}
}
