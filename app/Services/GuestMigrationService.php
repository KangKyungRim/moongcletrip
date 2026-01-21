<?php

namespace App\Services;

use GuzzleHttp\Client;

use App\Models\CouponUser;
use App\Models\MoongcleDeal;
use App\Models\TravelCart;

use App\Helpers\MiddleHelper;
use Carbon\Carbon;

class GuestMigrationService
{
	public function guestDataToNewUser($guestUserIdx, $newUserIdx)
	{
		if(empty($guestUserIdx) || empty($newUserIdx)) {
			return;
		}

		// $couponUsers = CouponUser::where('user_idx', $guestUserIdx)->get();

		// foreach($couponUsers as $couponUser) {
		// 	$couponUser->user_idx = $newUserIdx;
		// 	$couponUser->save();
		// }

		$moongcledeals = MoongcleDeal::where('user_idx', $guestUserIdx)->get();

		foreach($moongcledeals as $moongcledeal) {
			$moongcledeal->user_idx = $newUserIdx;
			$moongcledeal->save();
		}

		$travelCarts = TravelCart::where('user_idx', $guestUserIdx)->get();

		foreach($travelCarts as $travelCart) {
			$travelCart->user_idx = $newUserIdx;
			$travelCart->save();
		}
	}
}
