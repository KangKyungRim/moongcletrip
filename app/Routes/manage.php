<?php
return [
	'GET' => [
		'/partner-user/verify' => [App\Controllers\Api\VerificationApiController::class, 'verifyPartnerEmail'],

		// [[[Manage]]] Dashboard
		'/manage/dashboard' => [App\Controllers\Manage\DashboardViewController::class, 'dashboard'],

		'/manage/partner-select' => [App\Controllers\Manage\PartnerViewController::class, 'selectPartner'],

		'/manage/users' => [App\Controllers\Manage\UserViewController::class, 'userList'],

		// [[Manage]] Partner - user
		'/manage/partner-user-list' => [App\Controllers\Manage\UserViewController::class, 'partnerUserList'],
		'/manage/partner-user-create' => [App\Controllers\Manage\UserViewController::class, 'partnerUserCreate'],
		'/manage/partner-user-edit' => [App\Controllers\Manage\UserViewController::class, 'partnerUserEdit'],

		// [[[Manage]]] Partner - Basic
		'/manage/partner-basic-info' => [App\Controllers\Manage\PartnerViewController::class, 'basicInfo'],
		'/manage/partner-basic-info-edit' => [App\Controllers\Manage\PartnerViewController::class, 'editBasicInfo'],

		// [[[Manage]]] Partner - Room
		'/manage/partner-room-list' => [App\Controllers\Manage\RoomViewController::class, 'roomList'],
		'/manage/partner-room-info' => [App\Controllers\Manage\RoomViewController::class, 'roomInfo'],
		'/manage/partner-room-info-edit' => [App\Controllers\Manage\RoomViewController::class, 'editRoomInfo'],
		'/manage/partner-room-info-create' => [App\Controllers\Manage\RoomViewController::class, 'createRoomInfo'],

		// [[[Manage]]] Partner - Rateplan
		'/manage/partner-rateplan-list' => [App\Controllers\Manage\RateplansViewController::class, 'rateplanList'],
		'/manage/partner-rateplan-info' => [App\Controllers\Manage\RateplansViewController::class, 'rateplanInfo'],
		'/manage/partner-rateplan-info-edit' => [App\Controllers\Manage\RateplansViewController::class, 'editRateplanInfo'],
		'/manage/partner-rateplan-info-create' => [App\Controllers\Manage\RateplansViewController::class, 'createRateplanInfo'],
        '/manage/partner-rateplan-calendar' => [App\Controllers\Manage\InventoryViewController::class, 'inventory'],

		'/manage/partner-moongcle-point' => [App\Controllers\Manage\MoongclePointViewController::class, 'moongclepoint'],
		'/manage/partner-moongcle-point/update' => [App\Controllers\Manage\MoongclePointViewController::class, 'moongclepointUpdate'],

		'/manage/partner-curated-images' => [App\Controllers\Manage\PartnerViewController::class, 'curatedImages'],

        // [[[Manage]]] Partner - facilities
        '/manage/partner-facilities' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'facilities'],
        '/manage/partner-facilities-create' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'facilitiesCreate'],
        '/manage/partner-facilities-edit' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'facilitiesEdit'],

        // [[[Manage]]] Partner - service
        '/manage/partner-service' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'service'],
        '/manage/partner-service-create' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'serviceCreate'],
        '/manage/partner-service-edit' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'serviceEdit'],

        // [[[Manage]]] Partner - social reviews
        '/manage/partner-social' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'social'],
        '/manage/partner-social-create' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'socialCreate'],
        '/manage/partner-social-edit' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'socialEdit'],

        // [[[Manage]]] Partner - faq
        '/manage/partner-faq' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'faq'],
        '/manage/partner-faq-edit' => [App\Controllers\Manage\PartnerMiniHomeViewController::class, 'faqUpdate'],

        // [[[Manage]]] Partner - exposure
        '/manage/partner-exposure' => [App\Controllers\Manage\PartnerViewController::class, 'partnerExposure'],

		'/manage/reservations' => [App\Controllers\Manage\ReservationViewController::class, 'reservations'],

        // [[[Manage]]] Curation
        '/manage/curations' => [App\Controllers\Manage\CurationViewController::class, 'curations'],
        '/manage/curation-create' => [App\Controllers\Manage\CurationViewController::class, 'curationCreate'],
        '/manage/curation-edit' => [App\Controllers\Manage\CurationViewController::class, 'curationEdit'],

		// [[[Manage]]] Banners
		'/manage/banners' => [App\Controllers\Manage\BannerViewController::class, 'banners'],

		// [[[Manage]]] Coupon
		'/manage/reward/discount-coupons' => [App\Controllers\Manage\RewardViewController::class, 'discountCoupon'],
		'/manage/reward/discount-coupon/create' => [App\Controllers\Manage\RewardViewController::class, 'createDiscountCoupon'],
		'/manage/reward/discount-coupon/edit' => [App\Controllers\Manage\RewardViewController::class, 'editDiscountCoupon'],

		// [[[Manage]]] Moongcleoffer
		'/manage/moongcleoffers' => [App\Controllers\Manage\MoongcleofferViewController::class, 'moongcleoffers'],
		'/manage/moongcleoffers/create' => [App\Controllers\Manage\MoongcleofferViewController::class, 'createMoongcleoffer'],
		'/manage/moongcleoffers/edit' => [App\Controllers\Manage\MoongcleofferViewController::class, 'editMoongcleoffer'],
        '/manage/moongcleoffers/operate' => [App\Controllers\Manage\MoongcleofferViewController::class, 'moongcleoffersOperate'],
        '/manage/moongcleoffers/operate/edit' => [App\Controllers\Manage\MoongcleofferViewController::class, 'editMoongcleofferOperate'],
		'/manage/moongcleoffers/onda' => [App\Controllers\Manage\MoongcleofferViewController::class, 'moongcleoffersOnda'],

		'/manage/thirdparty/config' => [App\Controllers\Manage\ThirdpartyViewController::class, 'config'],
		'/manage/thirdparty/onda' => [App\Controllers\Manage\ThirdpartyViewController::class, 'onda'],
        '/manage/partner-safe' => [App\Controllers\Manage\ThirdpartyViewController::class, 'safeCancel'],

		'/manage/login' => [App\Controllers\Manage\LoginViewController::class, 'login'],
		'/manage/register' => [App\Controllers\Manage\LoginViewController::class, 'register'],

		'/manage/500' => [App\Controllers\Manage\CommonViewController::class, 'page500'],
	],
	'POST' => []
];
