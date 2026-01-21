<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

use App\Models\OndaWebhook;
use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\RoomInventory;
use App\Models\RoomPrice;
use App\Models\MoongcleOffer;
use App\Models\MoongcleOfferPrice;

use App\Services\OndaPropertyService;
use App\Services\OndaRoomService;
use App\Services\OndaRateplanService;
use App\Services\OndaRoomInventoryService;

// $roomInventory = RoomInventory::all();

// foreach ($roomInventory as $inv) {
// 	if (empty($inv->room_rateplan_idx) && $inv->rateplan_idx != 0) {
// 		$roomRateplan = RoomRateplan::where('room_idx', $inv->room_idx)
// 			->where('rateplan_idx', $inv->rateplan_idx)
// 			->first();

// 		$inv->room_rateplan_idx = $roomRateplan->room_rateplan_idx;
// 		$inv->save();
// 	}
// }

MoongcleOfferPrice::chunk(1000, function ($moongcleofferPrices) {
	foreach ($moongcleofferPrices as $moongcleofferPrice) {
		if (empty($moongcleofferPrice->room_rateplan_idx)) {
			$roomPrice = RoomPrice::find($moongcleofferPrice->base_idx);

			if (!empty($roomPrice)) {
				$moongcleofferPrice->room_idx = $roomPrice->room_idx;
				$moongcleofferPrice->rateplan_idx = $roomPrice->rateplan_idx;
				$moongcleofferPrice->room_rateplan_idx = $roomPrice->room_rateplan_idx;
				$moongcleofferPrice->save();
			}
		}
	}
});
