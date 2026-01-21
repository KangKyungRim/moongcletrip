<?php

namespace App\Helpers;

use App\Models\Partner;
use App\Models\PartnerUserAssignment;

class PartnerHelper
{
	public static function adminDefaultProcess()
	{
		$user = MiddleHelper::checkPartnerLoginCookie();

		if (!$user) {
			header('Location: /manage/login');
			exit;
		} else {
			if ($user->partner_user_level < 7) {
				header('Location: /partner/dashboard');
				exit;
			}
		}

		$selectedPartnerIdx = -1;

		if (!empty($_COOKIE['partner'])) {
			$selectedPartnerIdx = $_COOKIE['partner'];
			$selectedPartner = Partner::find($selectedPartnerIdx);
		}

		return array(
			'user' => $user,
			'selectedPartnerIdx' => $selectedPartnerIdx,
			'selectedPartner' => $selectedPartner
		);
	}

	public static function partnerDefaultProcess()
	{
		$user = MiddleHelper::checkPartnerLoginCookie();

		if (!$user) {
			header('Location: /partner/login');
			exit;
		} else {
			if ($user->partner_user_level < 4) {
				header('Location: /partner/login');
				exit;
			}

			if ($user->partner_user_level > 5) {
				header('Location: /manage/dashboard');
				exit;
			}
		}

		$selectedPartnerIdx = -1;

		$partnerUserAssignment = PartnerUserAssignment::where('partner_user_idx', $user->partner_user_idx)->first();

		if (!empty($partnerUserAssignment->partner_idx)) {
			$selectedPartnerIdx = $partnerUserAssignment->partner_idx;
			$selectedPartner = Partner::find($selectedPartnerIdx);
		}

		return array(
			'user' => $user,
			'selectedPartnerIdx' => $selectedPartnerIdx,
			'selectedPartner' => $selectedPartner
		);
	}
}
