<?php
return [
	'GET' => [
		// [[App]] Main
		'/main' => [App\Controllers\View\MainViewController::class, 'main'],
        '/main-old' => [App\Controllers\View\MainViewController::class, 'test'],

        // [[App]] 큐레이션
		'/' => [App\Controllers\View\CurationViewController::class, 'curations'],
		'/curation/{curationIdx}' => [App\Controllers\View\CurationViewController::class, 'curationDetail'],

		'/search-home' => [App\Controllers\View\SearchViewController::class, 'searchHome'],
		'/moongcledeals' => [App\Controllers\View\MoongcledealViewController::class, 'main'],
        '/moongcledeals-new' => [App\Controllers\View\MoongcledealViewController::class, 'new'],
        '/moongcledeals-old' => [App\Controllers\View\MoongcledealViewController::class, 'test'],

		'/community' => [App\Controllers\View\CommunityViewController::class, 'main'],

		'/mypage' => [App\Controllers\View\MyPageViewController::class, 'main'],

		'/community/article/{articleIdx}' => [App\Controllers\View\CommunityViewController::class, 'article'],

		'/notification' => [App\Controllers\View\NotificationViewController::class, 'main'],
		'/travel-cart' => [App\Controllers\View\TravelCartViewController::class, 'main'],

		// [[App]] Signup
		'/users/signup-agree' => [App\Controllers\View\SignupViewController::class, 'signupAgree'],
		'/users/signup-email' => [App\Controllers\View\SignupViewController::class, 'signupEmail'],
		'/users/signup-complete' => [App\Controllers\View\SignupViewController::class, 'signupComplete'],

		// [[App]] Login
		'/users/login-email' => [App\Controllers\View\LoginViewController::class, 'loginEmail'],
		'/users/logout' => [App\Controllers\View\LoginViewController::class, 'logout'],
		'/users/login-fail' => [App\Controllers\View\LoginViewController::class, 'loginFail'],

		// [[App]] Search
        '/search-start' => [App\Controllers\View\SearchViewController::class, 'main'],
		'/search-result' => [App\Controllers\View\SearchViewController::class, 'searchResult'],  
        '/search-map' => [App\Controllers\View\SearchViewController::class, 'searchMap'],        

        // [[App]] Search - old
        '/search' => [App\Controllers\View\SearchViewController::class, 'test'],
        '/search-result-old' => [App\Controllers\View\SearchViewController::class, 'searchResultTest'],   

        // [[App]] Article
        '/article/city' => [App\Controllers\View\ArticleViewController::class, 'articleCity'],
        '/article/stay' => [App\Controllers\View\ArticleViewController::class, 'articleStay'],
        '/article/exhibitions' => [App\Controllers\View\ArticleViewController::class, 'articleExhibitions'],
        '/article/freeform' => [App\Controllers\View\ArticleViewController::class, 'articleFreeform'],

		// [[App]] Product
		'/stay/detail/{partnerIdx}' => [App\Controllers\View\StayDetailViewController::class, 'main'],

		'/moongcleoffer/detail/{moongcleofferIdx}' => [App\Controllers\View\MoongcleofferDetailViewController::class, 'main'],

		'/moongcleoffer/product/{partnerIdx}' => [App\Controllers\View\MoongcleofferDetailViewController::class, 'product'],

		// [[App]] Payment
		'/payment/stay/{partnerIdx}/room/{roomIdx}/rateplan/{rateplanIdx}' => [App\Controllers\View\PaymentViewController::class, 'stay'],
		'/payment/stay/{partnerIdx}/room/{roomIdx}/moongcleoffer/{moongcleofferIdx}' => [App\Controllers\View\PaymentViewController::class, 'moongcleoffer'],
		'/payment/blank/success' => [App\Controllers\View\PaymentViewController::class, 'success'],
		'/payment/fail' => [App\Controllers\View\PaymentViewController::class, 'fail'],

		// [[App]] Coupons
		'/coupons' => [App\Controllers\View\CouponViewController::class, 'list'],

		// [[App]] My - Reservation
		'/my/reservations' => [App\Controllers\View\MyReservationViewController::class, 'list'],
		'/my/reservation/{paymentIdx}' => [App\Controllers\View\MyReservationViewController::class, 'detail'],
		'/my/cancel-reservation/{paymentIdx}' => [App\Controllers\View\MyReservationViewController::class, 'cancel'],
		'/my/canceled-reservation/{paymentIdx}' => [App\Controllers\View\MyReservationViewController::class, 'canceled'],
		'/my/cancel-reservation-fail' => [App\Controllers\View\MyReservationViewController::class, 'cancelFail'],

		// [[App]] My - Other Pages
		'/my/profile' => [App\Controllers\View\MyPageViewController::class, 'profile'],
		'/my/reviews' => [App\Controllers\View\ReviewViewController::class, 'reviews'],
		'/my/create-review/{paymentItemIdx}' => [App\Controllers\View\ReviewViewController::class, 'createReview'],
		'/my/favorites' => [App\Controllers\View\MyPageViewController::class, 'favorites'],
		'/notices' => [App\Controllers\View\MyPageViewController::class, 'notices'],
		'/notice/{noticeIdx}' => [App\Controllers\View\MyPageViewController::class, 'notice'],
		'/faq' => [App\Controllers\View\MyPageViewController::class, 'faq'],
		'/term/terms-of-service' => [App\Controllers\View\MyPageViewController::class, 'termsOfService'],
		'/term/privacy-policy' => [App\Controllers\View\MyPageViewController::class, 'privacyPolicy'],
		'/term/location-based-service' => [App\Controllers\View\MyPageViewController::class, 'locationBasedService'],
		'/term/financial-transaction' => [App\Controllers\View\MyPageViewController::class, 'financialTransaction'],
		'/term/review-polocy' => [App\Controllers\View\MyPageViewController::class, 'reviewPolocy'],
		'/term/consumer-dispute-resolution-standards' => [App\Controllers\View\MyPageViewController::class, 'consumerDisputeResolutionStandards'],
		'/term/youth-protection-policy' => [App\Controllers\View\MyPageViewController::class, 'youthProtectionPolicy'],

		// [[App]] Moongcledeal - Create
		'/moongcledeal/create/01' => [App\Controllers\View\MoongcledealViewController::class, 'create01'],
		'/moongcledeal/create/02' => [App\Controllers\View\MoongcledealViewController::class, 'create02'],
		'/moongcledeal/create/03' => [App\Controllers\View\MoongcledealViewController::class, 'create03'],
		'/moongcledeal/edit/01/{moongcledealIdx}' => [App\Controllers\View\MoongcledealViewController::class, 'edit01'],
		'/moongcledeal/edit/02/{moongcledealIdx}' => [App\Controllers\View\MoongcledealViewController::class, 'edit02'],

		// [[App]] Moongcledeal - Detail
		'/moongcledeal/detail/{moongcledealIdx}' => [App\Controllers\View\MoongcledealViewController::class, 'moongcledealDetail'],

		// [[App]] ETC
		'/404' => [App\Controllers\View\MainViewController::class, 'wrongApproach'],

        // [[App]] onboarding
		'/onboarding' => [App\Controllers\View\MainViewController::class, 'onboarding'],
	],
	'POST' => []
];
