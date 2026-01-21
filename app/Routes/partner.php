<?php
return [
	'GET' => [
		'/partner-user/verify' => [App\Controllers\Api\VerificationApiController::class, 'verifyPartnerEmail'],

		// [[[Partner]]] Dashboard
		'/partner/dashboard' => [App\Controllers\Partner\DashboardViewController::class, 'dashboard'],

		'/partner/partner-select' => [App\Controllers\Partner\PartnerViewController::class, 'selectPartner'],

		// [[Partner]] Partner - user
		'/partner/partner-user-list' => [App\Controllers\Partner\UserViewController::class, 'partnerUserList'],
		'/partner/partner-user-create' => [App\Controllers\Partner\UserViewController::class, 'partnerUserCreate'],
		'/partner/partner-user-edit' => [App\Controllers\Partner\UserViewController::class, 'partnerUserEdit'],

		// [[[Partner]]] Partner - Basic
		'/partner/partner-basic-info' => [App\Controllers\Partner\PartnerViewController::class, 'basicInfo'],
		'/partner/partner-basic-info-edit' => [App\Controllers\Partner\PartnerViewController::class, 'editBasicInfo'],

		// [[[Partner]]] Partner - Room
		'/partner/partner-room-list' => [App\Controllers\Partner\RoomViewController::class, 'roomList'],
		'/partner/partner-room-info' => [App\Controllers\Partner\RoomViewController::class, 'roomInfo'],
		'/partner/partner-room-info-edit' => [App\Controllers\Partner\RoomViewController::class, 'editRoomInfo'],
		'/partner/partner-room-info-create' => [App\Controllers\Partner\RoomViewController::class, 'createRoomInfo'],

		// [[[Partner]]] Partner - Rateplan
		'/partner/partner-rateplan-list' => [App\Controllers\Partner\RateplansViewController::class, 'rateplanList'],
		'/partner/partner-rateplan-info' => [App\Controllers\Partner\RateplansViewController::class, 'rateplanInfo'],
		'/partner/partner-rateplan-info-edit' => [App\Controllers\Partner\RateplansViewController::class, 'editRateplanInfo'],
		'/partner/partner-rateplan-info-create' => [App\Controllers\Partner\RateplansViewController::class, 'createRateplanInfo'],
		'/partner/partner-rateplan-calendar' => [App\Controllers\Partner\InventoryViewController::class, 'inventory'],

		'/partner/partner-moongcle-point' => [App\Controllers\Partner\MoongclePointViewController::class, 'moongclepoint'],
		'/partner/partner-moongcle-point/update' => [App\Controllers\Partner\MoongclePointViewController::class, 'moongclepointUpdate'],

		'/partner/partner-curated-images' => [App\Controllers\Partner\PartnerViewController::class, 'curatedImages'],

        // [[[Partner]]] Partner - facilities
        '/partner/partner-facilities' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'facilities'],
        '/partner/partner-facilities-create' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'facilitiesCreate'],
        '/partner/partner-facilities-edit' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'facilitiesEdit'],

        // [[[Partner]]] Partner - social reviews
        '/partner/partner-social' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'social'],
        '/partner/partner-social-create' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'socialCreate'],
        '/partner/partner-social-edit' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'socialEdit'],

        // [[[Partner]]] Partner - service
        '/partner/partner-service' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'service'],
        '/partner/partner-service-create' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'serviceCreate'],
        '/partner/partner-service-edit' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'serviceEdit'],

		// [[[Partner]]] Partner - faq
		'/partner/partner-faq' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'faq'],
		'/partner/partner-faq-edit' => [App\Controllers\Partner\PartnerMiniHomeViewController::class, 'faqUpdate'],

		'/partner/reservations' => [App\Controllers\Partner\ReservationViewController::class, 'reservations'],

		// [[[Partner]]] Coupon
		'/partner/reward/discount-coupons' => [App\Controllers\Partner\RewardViewController::class, 'discountCoupon'],
		'/partner/reward/discount-coupon/create' => [App\Controllers\Partner\RewardViewController::class, 'createDiscountCoupon'],
		'/partner/reward/discount-coupon/edit' => [App\Controllers\Partner\RewardViewController::class, 'editDiscountCoupon'],

		// [[[Partner]]] Moongcleoffer
		'/partner/moongcleoffers' => [App\Controllers\Partner\MoongcleofferViewController::class, 'moongcleoffers'],
		'/partner/moongcleoffers/create' => [App\Controllers\Partner\MoongcleofferViewController::class, 'createMoongcleoffer'],
		'/partner/moongcleoffers/edit' => [App\Controllers\Partner\MoongcleofferViewController::class, 'editMoongcleoffer'],
		'/partner/moongcleoffers/onda' => [App\Controllers\Partner\MoongcleofferViewController::class, 'moongcleoffersOnda'],

		'/partner/thirdparty/config' => [App\Controllers\Partner\ThirdpartyViewController::class, 'config'],
		'/partner/thirdparty/onda' => [App\Controllers\Partner\ThirdpartyViewController::class, 'onda'],
        '/partner/partner-safe' => [App\Controllers\Partner\ThirdpartyViewController::class, 'safeCancel'],

		'/partner/login' => [App\Controllers\Partner\LoginViewController::class, 'login'],
		'/partner/register' => [App\Controllers\Partner\LoginViewController::class, 'register'],

		'/partner/500' => [App\Controllers\Partner\CommonViewController::class, 'page500'],
	],
	'POST' => []
];
