<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

use App\Services\ImportService;

ImportService::importTags('/home/ubuntu/250604-4.xlsx');

// use App\Models\RoomPrice;

// $existingSalePercents = RoomPrice::where('room_rateplan_idx', 57)
// 	->whereIn('room_price_date', ['2025-05-01', '2025-05-02', '2025-05-03', '2025-05-04', '2025-05-05'])
// 	->get()
// 	->keyBy(function ($item) {
// 		return $item->room_rateplan_idx . '|' . explode(' ', $item->room_price_date)[0];
// 	});

// print_r($existingSalePercents);

// use App\Models\Partner;
// use App\Models\Room;
// use App\Models\Image;

// use App\Services\OndaPropertyService;
// use App\Services\OndaRoomService;

// $room = Room::find(66421);

// $service = new OndaRoomService();

// $service->saveRoomtypeDetails($room);

// $service = new OndaPropertyService();
// $property = $service->fetchPropertyDetails($partner->partner_onda_idx);
// $service->updatePartnerDetails($partner, $property);

// if (!empty($property['classifications'])) {
// 	$property['tags']['classifications'] = $property['classifications'];
// }

// $stay = $service->saveOrUpdateStay($partner, $property);
// $service->saveTagConnections($stay->stay_idx, $property['tags']);
// $service->saveImages($stay->stay_idx, $property['images'], 'basic');
// $service->saveCancelRules($partner->partner_idx, $property['property_refunds']);

// use App\Models\Room;

// $totalCount = Room::where('room_idx', '>', 10000)->where('room_thirdparty', 'onda')->count();
// $totalCount = 500;
// $batchSize = 500;
// $processCount = 20;
// $base = 87232;

// for ($i = 0; $i < $processCount; $i++) {
// 	$offset = $base + $i * $batchSize;
// 	$command = "/usr/local/php/bin/php ./onda001.php $offset $batchSize > /dev/null &";
// 	exec($command);
// }
