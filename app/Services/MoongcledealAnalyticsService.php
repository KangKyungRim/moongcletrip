<?php

namespace App\Services;

use App\Models\MoocleDeal;
use App\Models\MoongcleDeal;
use App\Models\MoongcledealAnalytics;
use App\Models\CityDateAnalytics;
use App\Models\CompanionAnalytics;
use App\Models\TasteAnalytics;

use Carbon\Carbon;

class MoongcledealAnalyticsService
{
	public static function analytics()
	{
		$months = [];
		$citiesCompanion = [];
		$citiesTaste = [];

		foreach (MoongcleDeal::where('moongcledeal_create_complete', 1)->where('analytics', false)->cursor() as $moongcledeal) {
			$selected = $moongcledeal->selected;

			if (empty($selected) || !is_array($selected)) {
				continue;
			}

			$checkinDate = null;
			$checkoutDate = null;

			$now = Carbon::now();

			if (!empty($selected['days'][0]['dates'])) {
				if ($selected['days'][0]['type'] == 'period') {
					$dates = explode('~', $selected['days'][0]['dates']);
					$checkinDate = isset($dates[0]) ? Carbon::parse($dates[0]) : null;
					$checkoutDate = isset($dates[1]) ? Carbon::parse($dates[1]) : null;
				} else {
					$monthText = $selected['days'][0]['dates'];

					preg_match('/\d+/', $monthText, $matches);
					$monthNumber = isset($matches[0]) ? (int) $matches[0] : null;

					if ($monthNumber && $monthNumber >= 1 && $monthNumber <= 12) {
						$year = $monthNumber < $now->month ? $now->year + 1 : $now->year;

						$checkinDate = Carbon::create($year, $monthNumber, 1)->startOfMonth();
						$checkoutDate = Carbon::create($year, $monthNumber, 1)->endOfMonth();
					}
				}
			}
			
			$personnel = isset($selected['personnel']) ? (int) $selected['personnel'] : 1;
			$companionTag = $selected['companion']['tag_machine_name'] ?? null;
			$petSizeTag = $selected['pet']['size']['tag_machine_name'] ?? null;
			$petWeightTag = $selected['pet']['weight']['tag_machine_name'] ?? null;
			$petCountTag = $selected['pet']['counts']['tag_machine_name'] ?? null;
			$cityTag = $selected['city']['tag_machine_name'] ?? null;

			$tasteTagsArray = array_column($selected['taste'] ?? [], 'tag_machine_name');
			// $tasteTags = implode(',', array_slice($tasteTagsArray, 0, 5));

			if ($cityTag) {
				if (!$checkinDate || !$checkoutDate || $checkinDate > $checkoutDate) {
					if (empty($months['always'])) {
						$months['always'] = [];
						$months['always'][$cityTag] = 1;
					} else {
						if (empty($months['always'][$cityTag])) {
							$months['always'][$cityTag] = 1;
						} else {
							$months['always'][$cityTag] += 1;
						}
					}
				}

				if ($checkinDate && $checkoutDate) {
					$cursor = $checkinDate->copy()->startOfMonth();
					$endMonth = $checkoutDate->copy()->startOfMonth();

					while ($cursor <= $endMonth) {
						if (empty($months[$cursor->format('Y-m')])) {
							$months[$cursor->format('Y-m')] = [];
							$months[$cursor->format('Y-m')][$cityTag] = 1;
						} else {
							if (empty($months[$cursor->format('Y-m')][$cityTag])) {
								$months[$cursor->format('Y-m')][$cityTag] = 1;
							} else {
								$months[$cursor->format('Y-m')][$cityTag] += 1;
							}
						}

						$cursor->addMonth();
					}
				}
			}

			if ($cityTag && $companionTag && $personnel) {
				$compKey = "{$cityTag}||{$companionTag}||{$personnel}";

				if (isset($citiesCompanion[$compKey])) {
					$citiesCompanion[$compKey]['count'] += 1;
				} else {
					$citiesCompanion[$compKey] = [
						'city_tag' => $cityTag,
						'companion_tag' => $companionTag,
						'personnel' => $personnel,
						'count' => 1,
					];
				}
			} else {
				if ($companionTag && $personnel) {
					$compKey = "all||{$companionTag}||{$personnel}";

					if (isset($citiesCompanion[$compKey])) {
						$citiesCompanion[$compKey]['count'] += 1;
					} else {
						$citiesCompanion[$compKey] = [
							'city_tag' => 'all',
							'companion_tag' => $companionTag,
							'personnel' => $personnel,
							'count' => 1,
						];
					}
				}
			}

			foreach ($tasteTagsArray as $tasteTag) {
				if ($cityTag && $tasteTag) {
					$tasteKey = "{$cityTag}||{$tasteTag}";

					if (isset($citiesTaste[$tasteKey])) {
						$citiesTaste[$tasteKey]['count'] += 1;
					} else {
						$citiesTaste[$tasteKey] = [
							'city_tag' => $cityTag,
							'taste_tag' => $tasteTag,
							'count' => 1,
						];
					}
				} else {
					if ($tasteTag) {
						$tasteKey = "all||{$tasteTag}";

						if (isset($citiesTaste[$tasteKey])) {
							$citiesTaste[$tasteKey]['count'] += 1;
						} else {
							$citiesTaste[$tasteKey] = [
								'city_tag' => 'all',
								'taste_tag' => $tasteTag,
								'count' => 1,
							];
						}
					}
				}
			}

			$moongcledeal->analytics = true;
			$moongcledeal->save();
		}

		foreach ($months as $monthKey => $cities) {
			foreach ($cities as $cityTag => $cityCount) {
				$record = CityDateAnalytics::where('month_key', $monthKey)
					->where('city_tag', $cityTag)
					->first();

				if ($record) {
					$record->count += $cityCount;
					$record->save();
				} else {
					CityDateAnalytics::create([
						'month_key' => $monthKey,
						'city_tag' => $cityTag,
						'count' => $cityCount,
					]);
				}
			}
		}

		foreach ($citiesCompanion as $entry) {
			$record = CompanionAnalytics::where('city_tag', $entry['city_tag'])
				->where('companion_tag', $entry['companion_tag'])
				->where('personnel', $entry['personnel'])
				->first();

			if ($record) {
				$record->count += $entry['count'];
				$record->save();
			} else {
				CompanionAnalytics::create([
					'city_tag' => $entry['city_tag'],
					'companion_tag' => $entry['companion_tag'],
					'personnel' => $entry['personnel'],
					'count' => $entry['count'],
				]);
			}
		}

		foreach ($citiesTaste as $entry) {
			$record = TasteAnalytics::where('city_tag', $entry['city_tag'])
				->where('taste_tag', $entry['taste_tag'])
				->first();

			if ($record) {
				$record->count += $entry['count'];
				$record->save();
			} else {
				TasteAnalytics::create([
					'city_tag' => $entry['city_tag'],
					'taste_tag' => $entry['taste_tag'],
					'count' => $entry['count'],
				]);
			}
		}
	}
}
