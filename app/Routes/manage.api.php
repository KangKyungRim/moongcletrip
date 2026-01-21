<?php
return [
	'GET' => [
		// Export Stays Excel file
		'/api/onda/export/stays' => [App\Controllers\Api\ExportApiController::class, 'downloadOndaStays'],
		'/api/onda/export/stay/{partnerOndaIdx}' => [App\Controllers\Api\ExportApiController::class, 'downloadOndaStay'],
		'/api/onda/export/stay/on-server' => [App\Controllers\Api\ExportApiController::class, 'downloadOndaStayOnServer'],

		'/api/export/users' => [App\Controllers\Api\ExportApiController::class, 'downloadUsers'],

		
	],
	'POST' => [
		// Partners
		'/api/partners/register' => [App\Controllers\Api\PartnerApiController::class, 'partnerRegister'],
		'/api/partners/email-login' => [App\Controllers\Api\PartnerApiController::class, 'partnerLogin'],
		'/api/partners/draft' => [App\Controllers\Api\PartnerApiController::class, 'storeDraft'],
		'/api/partners/approve' => [App\Controllers\Api\PartnerApiController::class, 'approve'],

		'/api/reward/discount-coupon/store' => [App\Controllers\Api\RewardApiController::class, 'storeDiscountCoupon'],
		'/api/reward/discount-coupon/edit' => [App\Controllers\Api\RewardApiController::class, 'editDiscountCoupon'],

		// Onda
		'/api/onda/properties' => [App\Controllers\Api\OndaPropertyController::class, 'storeProperties'],
		'/api/onda/detail-properties' => [App\Controllers\Api\OndaPropertyController::class, 'storeDetailProperties'],
		'/api/onda/detail-property' => [App\Controllers\Api\OndaPropertyController::class, 'storeDetailProperty'],
		'/api/onda/roomtypes' => [App\Controllers\Api\OndaRoomController::class, 'storeRoomtypes'],
		'/api/onda/detail-roomtypes' => [App\Controllers\Api\OndaRoomController::class, 'storeDetailRoomtypes'],
		'/api/onda/detail-roomtype' => [App\Controllers\Api\OndaRoomController::class, 'storeDetailRoomtype'],
		'/api/onda/rateplans' => [App\Controllers\Api\OndaRateplanController::class, 'storeRateplans'],
		'/api/onda/detail-rateplans' => [App\Controllers\Api\OndaRateplanController::class, 'storeDetailRateplans'],
		'/api/onda/detail-rateplan' => [App\Controllers\Api\OndaRateplanController::class, 'storeDetailRateplan'],
		'/api/onda/inventories' => [App\Controllers\Api\OndaRoomInventoryController::class, 'storeInventories'],
		'/api/onda/inventory' => [App\Controllers\Api\OndaRoomInventoryController::class, 'storeInventory'],
		'/api/onda/detail-property-all' => [App\Controllers\Api\OndaPropertyController::class, 'storeDetailPropertyAll'],

		'/api/partner/curated-images' => [App\Controllers\Api\CurateImageApiController::class, 'curateImages'],
		'/api/partner/moongcle-point' => [App\Controllers\Api\MoongclePointApiController::class, 'update'],

		'/api/partner/create-facility-detail' => [App\Controllers\Api\FacilityDetailApiController::class, 'create'],
		'/api/partner/edit-facility-detail' => [App\Controllers\Api\FacilityDetailApiController::class, 'update'],
		'/api/partner/delete-facility-detail' => [App\Controllers\Api\FacilityDetailApiController::class, 'delete'],

		'/api/partner/create-service-detail' => [App\Controllers\Api\ServiceDetailApiController::class, 'create'],
		'/api/partner/edit-service-detail' => [App\Controllers\Api\ServiceDetailApiController::class, 'update'],
		'/api/partner/delete-service-detail' => [App\Controllers\Api\ServiceDetailApiController::class, 'delete'],

		'/api/partners/create' => [App\Controllers\Api\Manage\PartnerApiController::class, 'createPartner'],
		'/api/partners/create-user' => [App\Controllers\Api\Manage\PartnerUserApiController::class, 'createPartnerUser'],
		'/api/partners/edit-user' => [App\Controllers\Api\Manage\PartnerUserApiController::class, 'editPartnerUser'],

		'/api/partners/edit' => [App\Controllers\Api\Manage\PartnerApiController::class, 'editPartner'],
		'/api/partners/approve' => [App\Controllers\Api\Manage\PartnerApiController::class, 'approve'],
		'/api/partners/toggle-status' => [App\Controllers\Api\Manage\PartnerApiController::class, 'partnerStatusToggle'],
		'/api/partners/edit-search-index' => [App\Controllers\Api\Manage\PartnerApiController::class, 'editPartnerSearchIndex'],
		'/api/partners/exposure' => [App\Controllers\Api\Manage\PartnerApiController::class, 'partnerShowMain'],

		'/api/partners/change-thirdparty' => [App\Controllers\Api\Manage\PartnerConfigApiController::class, 'changeThirdparty'],
		'/api/partners/change-calculation-type' => [App\Controllers\Api\Manage\PartnerConfigApiController::class, 'changeCalculationType'],
		'/api/partners/change-safe-cancel' => [App\Controllers\Api\Manage\PartnerConfigApiController::class, 'changeSafeCancel'],

		'/api/partner/edit-faqs' => [App\Controllers\Api\Manage\PartnerMiniHomeApiController::class, 'editPartnerFaq'],

		'/api/partner/room/create' => [App\Controllers\Api\Manage\RoomApiController::class, 'createRoom'],
		'/api/partner/room/edit' => [App\Controllers\Api\Manage\RoomApiController::class, 'editRoom'],
		'/api/partner/room/approve' => [App\Controllers\Api\Manage\RoomApiController::class, 'approve'],
		'/api/partner/room/status' => [App\Controllers\Api\Manage\RoomApiController::class, 'editStatus'],
		'/api/partner/room/copy' => [App\Controllers\Api\Manage\RoomApiController::class, 'copyRoom'],
		'/api/partner/room/change-order' => [App\Controllers\Api\Manage\RoomApiController::class, 'changeRoomOrder'],

		'/api/partner/rateplan/create' => [App\Controllers\Api\Manage\RateplanApiController::class, 'createRateplan'],
		'/api/partner/rateplan/edit' => [App\Controllers\Api\Manage\RateplanApiController::class, 'editRateplan'],
		'/api/partner/rateplan/status-toggle' => [App\Controllers\Api\Manage\RateplanApiController::class, 'roomRateplanStatusToggle'],
		'/api/partner/rateplan/status-toggle-all' => [App\Controllers\Api\Manage\RateplanApiController::class, 'rateplanStatusToggle'],

		'/api/partner/rates' => [App\Controllers\Api\Manage\InventoryApiController::class, 'saveRates'],
		'/api/partner/rates-range' => [App\Controllers\Api\Manage\InventoryApiController::class, 'saveRatesRange'],
		'/api/partner/rates-discount' => [App\Controllers\Api\Manage\InventoryApiController::class, 'saveRatesDiscount'],
		'/api/partner/rates-status' => [App\Controllers\Api\Manage\InventoryApiController::class, 'saveRoomStatus'],

		'/api/partner/create-moongcleoffer' => [App\Controllers\Api\Manage\MoongcleofferApiController::class, 'createMoongcleoffer'],
		'/api/partner/edit-moongcleoffer' => [App\Controllers\Api\Manage\MoongcleofferApiController::class, 'editMoongcleoffer'],
		'/api/partner/edit-moongcleoffer-status' => [App\Controllers\Api\Manage\MoongcleofferApiController::class, 'editMoongcleofferStatus'],

		'/api/reservation/edit-confirmed-code' => [App\Controllers\Api\Manage\ReservationApiController::class, 'changeReservationCode'],
		'/api/reservation/resend-reservation' => [App\Controllers\Api\Manage\ReservationApiController::class, 'resendReservation'],
		'/api/reservation/resend-reservation-cancel' => [App\Controllers\Api\Manage\ReservationApiController::class, 'resendReservationCancel'],

		//큐레이션 목록 조회
		'/api/manage/getCurations' => [App\Controllers\Api\Manage\CurationApiController::class, 'getCurations'],
		'/api/manage/getCuration/{curationIdx}' => [App\Controllers\Api\Manage\CurationApiController::class, 'getCuration'],
		//큐레이션 등록 수정
		'/api/manage/postCuration' => [App\Controllers\Api\Manage\CurationApiController::class, 'postCuration'],
		'/api/manage/putCuration/{curationIdx}' => [App\Controllers\Api\Manage\CurationApiController::class, 'putCuration'],
		'/api/manage/putCurationActive/{curationIdx}' => [App\Controllers\Api\Manage\CurationApiController::class, 'putCurationActive'],
		'/api/manage/putCurationItemActive/{curationIdx}/{curationItemIdx}' => [App\Controllers\Api\Manage\CurationApiController::class, 'putCurationItemActive'],
		'/api/manage/putCurationOrder' => [App\Controllers\Api\Manage\CurationApiController::class, 'putCurationOrder'],
		'/api/manage/putCurationItemOrder/{curationIdx}' => [App\Controllers\Api\Manage\CurationApiController::class, 'putCurationItemOrder'],

	]
];
