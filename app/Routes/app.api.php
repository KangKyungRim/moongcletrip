<?php
return [
	'GET' => [
		'/api/search/real-time' => [App\Controllers\Api\SearchApiController::class, 'realtimeSearch'],

		// Email login
		'/auth/email/redirect' => [App\Controllers\Api\EmailAuthController::class, 'redirect'],

		// Kakao login
		'/auth/kakao/redirect' => [App\Controllers\Api\KakaoAuthController::class, 'redirect'],
		'/auth/kakao/callback' => [App\Controllers\Api\KakaoAuthController::class, 'callback'],

		// Apple login
		'/auth/apple/redirect' => [App\Controllers\Api\AppleAuthController::class, 'redirect'],
		'/auth/apple/callback' => [App\Controllers\Api\AppleAuthController::class, 'callbackGet'],

		// [[App]] 큐레이션
		'/api/curations' => [App\Controllers\Api\CurationApiController::class, 'getCurations'],
	],
	'POST' => [

		// [[App]] 큐레이션
		'/api/getCuration/{curationIdx}' => [App\Controllers\Api\CurationApiController::class, 'getCuration'],

		// Users
		'/api/users/signup-email' => [App\Controllers\Api\UserApiController::class, 'signupEmail'],
		'/api/users/update-agrees' => [App\Controllers\Api\UserApiController::class, 'updateAgrees'],
		'/api/users/login-email' => [App\Controllers\Api\UserApiController::class, 'loginEmail'],
		'/api/users/logout' => [App\Controllers\Api\UserApiController::class, 'logout'],
		'/api/users/refresh-token' => [App\Controllers\Api\UserApiController::class, 'refreshToken'],
		'/api/user/update-profile' => [App\Controllers\Api\UserApiController::class, 'updateProfile'],
		'/api/user/delete-account' => [App\Controllers\Api\UserApiController::class, 'deleteAccount'],

		// Apple login
		'/auth/apple/callback' => [App\Controllers\Api\AppleAuthController::class, 'callbackPost'],

		// Stays
		'/api/stays/draft' => [App\Controllers\Api\StayApiController::class, 'storeDraft'],
		'/api/stays/approve' => [App\Controllers\Api\StayApiController::class, 'approve'],

		// Rooms
		'/api/rooms/draft' => [App\Controllers\Api\RoomApiController::class, 'storeDraft'],
		'/api/rooms/approve' => [App\Controllers\Api\RoomApiController::class, 'approve'],
		'/api/rooms/toggle-active/{roomIdx}' => [App\Controllers\Api\RoomApiController::class, 'toggleRoomActive'],
		'/api/room/{roomIdx}' => [App\Controllers\Api\RoomApiController::class, 'roomDetailInfo'],
		'/api/room-rateplan/{roomRateplanIdx}' => [App\Controllers\Api\RoomRateplanApiController::class, 'roomRateplanDetailInfo'],
		'/api/moongcleoffer/{moongcleofferIdx}' => [App\Controllers\Api\MoongcleofferApiController::class, 'moongcleofferDetailInfo'],

		// Images
		'/api/images/upload' => [App\Controllers\Api\ImageUploadController::class, 'uploadTempImage'],
		'/api/curated-images/upload' => [App\Controllers\Api\ImageUploadController::class, 'uploadCuratedTempImage'],
		'/api/moongcle-point-images/upload' => [App\Controllers\Api\ImageUploadController::class, 'uploadMoongclePointTempImage'],
		'/api/facility-detail-images/upload' => [App\Controllers\Api\ImageUploadController::class, 'uploadFacilityDetailImage'],
		'/api/images/uploadAws' => [App\Controllers\Api\ImageUploadController::class, 'uploadCurationThumbnailImage'],
		'/api/images/deleteAws' => [App\Controllers\Api\ImageUploadController::class, 'deleteCurationThumbnailImage'],


		// Excel
		'/api/excel/tag-upload' => [App\Controllers\Api\ExcelUploadController::class, 'tagExcelUpload'],

		// Payment
		'/api/payment/prepare' => [App\Controllers\Api\PaymentApiController::class, 'prepare'],
		'/api/payment/cancel' => [App\Controllers\Api\PaymentApiController::class, 'cancel'],

		// Moongcledeal
		'/api/moongcledeal/store-main' => [App\Controllers\Api\MoongcledealApiController::class, 'storeMain'],
		'/api/moongcledeal/store' => [App\Controllers\Api\MoongcledealApiController::class, 'store'],
		'/api/moongcledeal/update' => [App\Controllers\Api\MoongcledealApiController::class, 'update'],
		'/api/moongcledeal/priority' => [App\Controllers\Api\MoongcledealApiController::class, 'priority'],
		'/api/moongcledeal/stop' => [App\Controllers\Api\MoongcledealApiController::class, 'stop'],
		'/api/moongcledeal/reopen' => [App\Controllers\Api\MoongcledealApiController::class, 'reopen'],
		'/api/moongcledeal/edit-in-progress' => [App\Controllers\Api\MoongcledealApiController::class, 'editInProgress'],
		'/api/moongcletag/encode-tags' => [App\Controllers\Api\MoongcledealApiController::class, 'moongcleTagEncode'],
		'/api/moongcletag/change-title' => [App\Controllers\Api\MoongcledealApiController::class, 'changeTitle'],
		'/api/moongcletag/more-open' => [App\Controllers\Api\MoongcledealApiController::class, 'moreOpen'],

		// Coupon
		'/api/coupon/{couponIdx}/download' => [App\Controllers\Api\CouponApiController::class, 'downloadCoupon'],

		'/api/my/favorite-partner' => [App\Controllers\Api\PartnerFavoriteApiController::class, 'toggleFavorite'],
		'/api/my/favorite-moongcleoffer' => [App\Controllers\Api\PartnerFavoriteApiController::class, 'toggleFavoriteMoongcleoffer'],

		// Community
		'/api/review/v1/loadmore' => [App\Controllers\Api\CommunityApiController::class, 'reviewV1LoadMore'],

		// Search
		'/api/search/loadmore' => [App\Controllers\Api\SearchApiController::class, 'searchLoadMore'],
		'/api/search/loadmap' => [App\Controllers\Api\SearchApiController::class, 'searchLoadMap'],

		// App Register
		'/api/register-device' => [App\Controllers\Api\DeviceApiController::class, 'registerDevice'],

		'/api/review/create' => [App\Controllers\Api\ReviewApiController::class, 'create'],

		// Sanha
		'/api/sanha/availability-and-rates' => [App\Controllers\Api\SanhaProcessCallController::class, 'handleAvailabilityAndRates'],
		'/api/sanha/process-call' => [App\Controllers\Api\SanhaProcessCallController::class, 'handleProcess'],

		//내부 테스트용
		'/api/traveltech-test/fcm' => [App\Controllers\Api\TravelTechTestController::class, 'sendFcm'],

	],
	'PUT' => [
		// Webhook
		'/api/webhook/onda' => [App\Controllers\Api\OndaWebhookController::class, 'handleWebhook'],
	]
];
