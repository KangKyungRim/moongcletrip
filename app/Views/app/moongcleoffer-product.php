<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

$user = $data['user'];
$partner = $data['partner'];
$rooms = $data['rooms'];
$allRooms = $data['allRooms'];
$closedRooms = $data['closedRooms'];
$moongclePoint = $data['moongclePoint'];
$mainTagList = $data['mainTagList'];
$cancelRules = $data['cancelRules'];
$intervalDays = $data['intervalDays'];
$moongcleStayTags = $data['moongcleStayTags'];
$moongcleRoomTags = $data['moongcleRoomTags'];
$isGuest = $data['isGuest'];
$moongcleofferFavorites = $data['moongcleofferFavorites'];
$openCalendar = $data['openCalendar'];
$reviews = $data['reviews'];
$reviewRating = $data['reviewRating'];
$reviewCount = $data['reviewCount'];
$thirdparty = $partner->partner_thirdparty;
$facilities = $data['facilities'];
$services = $data['services'];

$stayImages = explode(':-:', $partner->image_paths);

if ($partner->image_curated) {
	$stayImages = explode(':-:', $partner->curated_image_paths);
}

$stayTypes = explode(':-:', $partner->types);
$stayTypesCleaned = array_filter(array_unique($stayTypes));
$stayTypesText = implode(', ', $stayTypesCleaned);

?>

<!-- Head -->
<?php 
    $address_parts = explode(' ', $partner->partner_address1);
    $short_address = implode(' ', array_slice($address_parts, 1)); 
    $page_title_01 = "$short_address $partner->partner_name";
    
    $page_title_02 = "$stayTypesText 숙소 추천";
    $page_image = $stayImages[0];
    $page_url = $_ENV['APP_HTTP'] . "/moongcleoffer/product/$partner->partner_idx";

    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>
<!-- Head -->

<body>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
	}
	?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>



	<div id="mobileWrap" style="padding-bottom: 6rem;">
		<?php if ($deviceType != 'app') : ?>
			<!-- 모웹에서 뜨는 상단 배너 -->
			<div id="appDownloadTopBanner" class="top-banner__wrap">
				<div class="top-banner__con">
					<div class="logo">
						<img src="/assets/app/images/common/moongcle_color.png" alt="">
					</div>
					<div class="tit__wrap">
						<p class="title">뭉클딜 서비스는 앱에서만 가능해요</p>
						<p class="desc">무료로 나만의 혜택을 누려볼까요?</p>
					</div>
				</div>
				<button type="button" class="btn-md__yellow" onclick="openAppDownloadTab()">앱 열기</button>
				<button id="appDownloadTopBannerClose" type="button" class="btn-close"><i class="ico ico-close__small"></i></button>
			</div>
			<!-- //모웹에서 뜨는 상단 배너 -->
		<?php endif; ?>

		<header class="header__wrap">
			<div class="header__inner">
				<button class="btn-back" onclick="previousBlankPage()"><span class="blind">뒤로가기</span></button>
				<div class="header-product-name"><h2 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h2></div>
				<div class="btn__wrap">
					<button type="button" class="btn-home" onclick="gotoMain()"><span class="blind">홈</span></button>
					<button class="btn-share" onclick="sendShareLink('뭉클트립에서 <?= $partner->partner_name; ?>의 뭉클 정보를 공유했어요. 자세한 내용은 아래 링크를 확인해보세요.', '<?= $_ENV['APP_HTTP'] . $stayImages[0]; ?>')"><span class="blind">공유하기</span></button>
				</div>
			</div>
		</header>

        <!-- 검색 폼 -->    
        <div class="search custom" style="padding: 0 2rem;">
            <div class="search-form__wrap fnStickyTop">
                <div class="search-form">
                    <div class="search-form__con search-date fnOpenPop" data-name="popupDate">
                        <i class="ico ico-date__mint"></i>
                        <p class="txt" id="selectedDate"></p>
                    </div>
                    <div class="search-form__con search-guest fnOpenPop" data-name="popupGuest">
                        <i class="ico ico-person__mint"></i>
                        <p class="txt" id="selectedGuests">2명</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- //검색 폼 -->

		<div class="container__wrap" style="padding-bottom:0px; padding-top: 4.2rem;">

			<div class="product-detail__wrap">
				<!-- 상품 이미지 슬라이드 -->
				<div class="product-detail__img" onclick="fnOpenLayerPop('allPictures')">
					<div class="splide splide__product">
						<div class="splide__track real-image-main">
							<ul class="splide__list">
								<?php if (!empty($stayImages[0])) : ?>
									<?php foreach ($stayImages as $stayImage) : ?>
										<li class="splide__slide splide__list__product"><img src="<?= $stayImage; ?>" alt=""></li>
									<?php endforeach; ?>
								<?php else : ?>
									<li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
								<?php endif; ?>
							</ul>
						</div>
						<div class="slide-counter">
							<span class="current-slide">1</span> / <span class="total-slides"></span>
						</div>

                        <div class="skeleton-img-loader-main"></div>
					</div>
				</div>
				<!-- //상품 이미지 슬라이드 -->
				<!-- 상품 타이틀 -->
				<div class="product-detail__tit" id="product-detail__tit">

                    <!-- 조건 걸어주기 -->
					<button type="button" 
                        class="btn-product__like type-black <?= ($moongcleofferFavorites->favorite_idx) ? 'active' : '' ?>"        
                        data-user-idx="<?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>" 
                        data-partner-idx="<?= !empty($partner->partner_idx) ? $partner->partner_idx : 0 ?>" 
                        data-moongcleoffer-idx="0">
                            <span class="blind">찜하기</span>
                    </button>

					<div class="product-tit">

						<h2 id="product-name" class="product-name"><?= $partner->partner_name; ?></h2>
						<p class="product-sub">
							<?php $stayTypes = explode(':-:', $partner->types); ?>
							<?php if (!empty($stayTypes[0])) : ?>
								<span>
									<?php foreach ($stayTypes as $tagKey => $stayType) : ?>
										<?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
									<?php endforeach; ?>
								</span>
							<?php endif; ?>
							<?php
                            $ratingKeywords = ['1성', '2성', '3성', '4성', '5성'];
                            $hasRating = false;

                            if (!empty($partner->tags)) {
                                foreach ($ratingKeywords as $keyword) {
                                    if (strpos($partner->tags, $keyword) !== false) {
                                        $hasRating = true;
                                        break;
                                    }
                                }
                            }

                            $rating = extract_stay_rating($partner->tags);
                            ?>

                            <?php if ($hasRating && !empty($rating)) : ?>
                                <span><?= $rating ?></span>
                            <?php endif; ?>
							<a id="selectStayAddressButton" href="" class="place"><?= $partner->partner_address1; ?></a>
						</p>
					</div>
					<div class="flex-between">
						<div class="product-category">
							<p class="badge badge__lavender">나만의 뭉클딜</p>
                            <p class="ft-xxs">
                                <a href="/stay/detail/<?= $partner->partner_idx; ?>" style="color: #696d70;">
                                    상시상품 이동 
                                    <img src="/assets/app/images/common/ico_arrow_right.svg" alt="화살표 아이콘">
                                </a>
                            </p>
						</div>
						<?php if ($reviewCount !== 0) : ?>
							<p class="review-con">
								<i class="ico ico-star"></i>
								<span class="rating-num"><?= $reviewRating; ?></span>
								<span class="review-num fnOpenPop" data-name="reviewDetail"><a>리뷰 <span><?= number_format($reviewCount); ?></span>개</a></span>
							</p>
						<?php endif; ?>
					</div>
				</div>
				<!-- //상품 타이틀 -->

				<!-- 뭉클한 혜택 -->
				<div id="mainMoongcleoffer" class="product-detail__benefit custom">
					<div class="box-white__list">
                        <?php if (!empty($data['stayMoongcleoffers'])) : ?>

                            <!-- 뭉클딜 슬라이드 -->
                            <div style="height: auto;">
                                <div class="rateplan__slide">

									<?php if (count($data['stayMoongcleoffers']) > 1) : ?>
                                        <!-- 뭉클딜 복수 상태 -->
                                        <P class="mcd_text"><i class="fa-solid fa-arrow-left left"></i> 좌우로 슬라이드 해주세요 <i class="fa-solid fa-arrow-right right"></i></P>
                                    <?php endif; ?>

                                    <div class="swiper-container">
                                        <ul id="mainSlider" class="swiper-wrapper">
                                            <?php foreach ($data['stayMoongcleoffers'] as $stayMoongcleoffer) : ?>
                                                <li class="swiper-slide" data-stayMoongcleoffer-idx="<?= $stayMoongcleoffer->stay_moongcleoffer_idx; ?>">
                                                    <!-- 혜택 -->
                                                    <?php if (!empty($stayMoongcleoffer->benefits) && count($stayMoongcleoffer->benefits) > 0) : ?>
                                                    <div class="box-white__wrap">
                                                        <p class="title">
                                                            <?php if ($stayMoongcleoffer->stay_moongcleoffer_title === "") : ?>
                                                                이름 없음
                                                            <?php else : ?>
                                                                <?= $stayMoongcleoffer->stay_moongcleoffer_title; ?>
                                                            <?php endif; ?>
                                                        </p>
                                                        <div class="thumb__wrap">
                                                            <div class="thumb__gift">
                                                                <ul>
                                                                    <?php foreach ($stayMoongcleoffer->benefits as $benefit) : ?>  
                                                                        <li><?= $benefit; ?></li>
                                                                    <?php endforeach; ?> 
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <!-- //혜택 -->

                                                    <?php if (!empty($stayMoongcleoffer->moongcleoffers) && count($stayMoongcleoffer->moongcleoffers) > 0) : ?>
                                                        <!-- 룸 리스트 -->
                                                        <div class="product__wrap">
                                                            <?php foreach ($stayMoongcleoffer->moongcleoffers as $index => $moongcleoffer) : ?>
                                                            <div class="room-list__wrap">
                                                                <div class="room-list__con">
                                                                    <div class="splide splide__product">
                                                                        <div class="splide__track real-image">
                                                                            <ul class="splide__list">
                                                                                <?php $roomImages = explode(':-:', $moongcleoffer->image_paths); ?>
                                                                                <?php if (!empty($roomImages[0])) : ?>
                                                                                    <?php foreach ($roomImages as $roomImage) : ?>
                                                                                        <li class="splide__slide splide__list__product"><img src="<?= $roomImage; ?>" alt="객실 이미지"></li>
                                                                                    <?php endforeach; ?>
                                                                                <?php else : ?>
                                                                                    <li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
                                                                                <?php endif; ?>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="slide-counter">
                                                                            <span class="current-slide">1</span> / <span class="total-slides"></span>
                                                                        </div>

                                                                        <div class="skeleton-img-loader"></div>
                                                                    </div>
                                                                    <div class="room-name-block">
                                                                        <p class="room-name fnOpenPop" data-name="roomDetail" data-room-idx="<?= $moongcleoffer->room_idx; ?>"><?= $moongcleoffer->room_name; ?></p>
                                                                        <a href="" style="white-space: nowrap;" class="btn-txt__arrow fnOpenPop" data-name="roomDetail" data-room-idx="<?= $moongcleoffer->room_idx; ?>">객실상세</a>
                                                                    </div>

                                                                    <?php if ($moongcleoffer->min_inventory_quantity < 5) : ?>
                                                                        <p class="room-num">⏰마감 임박⏰ 남은 객실 <span><?= $moongcleoffer->min_inventory_quantity; ?>개</span></p>
                                                                    <?php endif; ?>

                                                                    <ul class="room-option">
                                                                        <?php if (empty($moongcleoffer->views)) : ?>
                                                                        <?php else : ?>
                                                                            <?php $roomViews = explode(':-:', $moongcleoffer->views); ?>
                                                                            <li class="option-view">
                                                                                <div>
                                                                                    <?php foreach ($roomViews as $roomView) : ?>
                                                                                        <div><?= $roomView; ?></div>
                                                                                    <?php endforeach; ?>
                                                                                </div>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                        <?php $bedTypes = json_decode($moongcleoffer->room_bed_type, true); ?>
                                                                        <?php
                                                                        $totalBeds = array_sum($bedTypes);
                                                                        ?>

                                                                        <?php if ($totalBeds > 0) : ?>
                                                                            <li class="option-bed">
                                                                                <div>
                                                                                    <?php
                                                                                    $bedNames = [
                                                                                        'dormitory_beds' => '도미토리',
                                                                                        'single_beds' => '싱글베드',
                                                                                        'super_single_beds' => '슈퍼싱글베드',
                                                                                        'semi_double_beds' => '세미더블베드',
                                                                                        'double_beds' => '더블베드',
                                                                                        'queen_beds' => '퀸베드',
                                                                                        'king_beds' => '킹베드',
                                                                                        'hollywood_twin_beds' => '할리우드베드',
                                                                                        'double_story_beds' => '이층 침대',
                                                                                        'bunk_beds' => '벙크베드',
                                                                                        'rollaway_beds' => '간이 침대',
                                                                                        'futon_beds' => '요이불 세트',
                                                                                        'capsule_beds' => '캡슐 침대',
                                                                                        'sofa_beds' => '소파베드',
                                                                                        'air_beds' => '에어베드'
                                                                                    ];

                                                                                    foreach ($bedTypes as $bedType => $bedCount) {
                                                                                        if ($bedCount == 0) continue;

                                                                                        if (isset($bedNames[$bedType])) {
                                                                                            echo '<div>' . $bedNames[$bedType] . ' ' . $bedCount . '개</div>';
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($moongcleoffer->room_size)) : ?>
                                                                            <li class="option-area">객실크기 <?= $moongcleoffer->room_size; ?>&#13217;</li>
                                                                        <?php endif; ?>
                                                                        <li class="option-people">
                                                                            기준 <?= $moongcleoffer->room_standard_person; ?>명 / 최대 
                                                                            <span style="display: inline-block; margin-left: 0.4rem;<?= $moongcleoffer->room_max_person >= 3 ? ' color: #00CB9C; font-weight: bold;' : '' ?>">
                                                                                <?= $moongcleoffer->room_max_person; ?>
                                                                            </span>명
                                                                        </li>
                                                                    </ul>

                                                                    <div class="accordion__wrap accordion__wrap_custom">
                                                                        <div class="room-type__wrap">
                                                                            <div class="room-type__con">
                                                                                <div class="flex-between">
                                                                                <?php
                                                                                    if (
                                                                                        $moongcleoffer->rateplan_name == '[Room only]'
                                                                                        || $moongcleoffer->rateplan_name == '[회원특가] Room only'
                                                                                        || $moongcleoffer->rateplan_name == 'room only'
                                                                                        || $moongcleoffer->rateplan_name == 'standalone'
                                                                                        || $moongcleoffer->rateplan_name == '룸온리'
                                                                                    ) {
                                                                                        $moongcleoffer->rateplan_name = $intervalDays . '박 요금';
                                                                                    }
                                                                                    ?>
                                                                                    <div style="width: 45%; word-break: keep-all; white-space: normal;">
                                                                                        <p class="room-type-name"><?= $moongcleoffer->rateplan_name; ?></p>
                                                                                        <div class="thumb__wrap" style="margin-top: 2rem;">
                                                                                            <div class="thumb__gift">
                                                                                                <ul>
                                                                                                    <?php foreach ($stayMoongcleoffer->benefits as $benefit) : ?>  
                                                                                                        <li>
                                                                                                            <span class="d-inline-block" style="width: 84%;"><?= $benefit; ?><span>
                                                                                                        </li>
                                                                                                    <?php endforeach; ?>  
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="room-price" style="display: flex; flex-direction: column; align-items: flex-end;">
                                                                                        <?php
                                                                                        $basicPrice = $moongcleoffer->total_basic_price;
                                                                                        $salePrice = $moongcleoffer->total_sale_price;
                                                                                        ?>
                                                                                        <a href="" class="btn-txt__arrow fnOpenPop" data-name="roomRateplanDetail" data-moongcleoffer-idx="<?= $moongcleoffer->base_product_idx; ?>" style="margin-bottom: 1rem;">상세보기</a>
                                                                                        <span>
                                                                                            <?php if ($basicPrice != $salePrice) : ?>
                                                                                                <span class="sale-percent">-<?= number_format((($basicPrice - $salePrice) / $basicPrice) * 100, 1); ?>%</span>
                                                                                                <span class="default-price"><?= number_format($basicPrice); ?>원</span>
                                                                                            <?php endif; ?>
                                                                                        </span>
                                                                                        <span class="sale-price"><em><?= number_format($salePrice); ?></em>원 (<?= $intervalDays; ?>박)</span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="btn__wrap custom">
                                                                                    <!-- <button type="button" id="freeCancel" class="badge badge__lavender">무료 취소 가능</button> -->
                                                                                    <?php if ($moongcleoffer->rateplan_stay_min <= $intervalDays && ($moongcleoffer->rateplan_stay_max == 0 || $moongcleoffer->rateplan_stay_max >= $intervalDays)) : ?>
                                                                                        <button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity" data-name="popupRoomQuantity" data-room-name="<?= $moongcleoffer->room_name; ?>" data-rateplan-name="<?= $moongcleoffer->rateplan_name; ?>" data-room-idx="<?= $moongcleoffer->room_idx; ?>" data-rateplan-idx="<?= $moongcleoffer->rateplan_idx; ?>" data-moongcleoffer-idx="<?= $moongcleoffer->moongcleoffer_idx; ?>">지금 예약하기</button>
                                                                                    <?php else : ?>
                                                                                        <button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity disabled" data-name="popupRoomQuantity" data-room-name="<?= $moongcleoffer->room_name; ?>" data-rateplan-name="<?= $moongcleoffer->rateplan_name; ?>" data-room-idx="<?= $moongcleoffer->room_idx ?>" data-rateplan-idx="<?= $moongcleoffer->rateplan_idx ?>" data-moongcleoffer-idx="<?= $moongcleoffer->moongcleoffer_idx; ?>" disabled>지금 예약하기</button>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>          
                                                            </div>       
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <!-- //룸 리스트 -->
                                                    <?php else : ?>
                                                        <div class="no__wrap">
                                                            <div class="nodata__con" style="font-size: 1.4rem;">
                                                                선택하신 인원 수 또는 날짜에 맞는 상품이 없어요<br>
                                                                인원 또는 날짜를 수정해 보세요!
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>


                                        <div class="navigation_box" style="display: <?= count($data['stayMoongcleoffers']) === 1 ? 'none' : 'block' ?>;">
                                            <div class="swiper-button-prev arrow_btn"></div>
                                            <div class="swiper-button-next arrow_btn"></div>
                                        </div>
                                    </div>

                                    <!-- 쿠폰팩 -->
                                    <div class="box-white__wrap flex-between" style="margin: 4rem 2rem 0rem 2rem;">
                                        <p class="ft-default ft-bold">💰 <span class="txt-primary">70,000원</span> 선착순 쿠폰팩 받기</p>
                                        <button type="button" class="btn-sm__primary btn-sm__round" onclick="downloadCouponPack()">
                                            <i class="ico ico-download"></i>
                                            다운로드
                                        </button>
                                    </div>
                                    <!-- //쿠폰팩 -->

                                     <!-- 뭉클 포인트 -->
                                    <?php if (!empty($moongclePoint)) : ?>
                                        <div class="product-detail__point">
                                            <div class="box-white__wrap">
                                                <div class="tit__wrap">
                                                    <p class="title" style="padding-bottom: 0;">이 숙소의 뭉클포인트</p>
                                                    <p class="desc" style="white-space: pre-line;">
                                                        <?= !empty($moongclePoint->moongcle_point_introduction) ? $moongclePoint->moongcle_point_introduction : ''; ?>
                                                    </p>
                                                </div>
                                                <?php $moongclePointImages = json_decode($moongclePoint->images); ?>
                                                <div class="splide splide__point">
                                                    <div class="splide__track">
                                                        <ul class="splide__list">
                                                            <?php if (!empty($moongclePointImages[0])) : ?>
                                                                <li class="splide__slide">
                                                                    <div class="overlay"></div>
                                                                    <img class="splide_cover" src="<?= $moongclePointImages[0]->image_path; ?>" alt="">
                                                                    <div class="image-text-wrapper">
                                                                        <div class="image-text">
                                                                            <?= !empty($moongclePoint->moongcle_point_1_title) ? $moongclePoint->moongcle_point_1_title : ''; ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($moongclePointImages[1])) : ?>
                                                                <li class="splide__slide">
                                                                    <div class="overlay"></div>
                                                                    <img class="splide_cover" src="<?= $moongclePointImages[1]->image_path; ?>" alt="">
                                                                    <div class="image-text-wrapper">
                                                                        <div class="image-text">
                                                                            <?= !empty($moongclePoint->moongcle_point_2_title) ? $moongclePoint->moongcle_point_2_title : ''; ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($moongclePointImages[2])) : ?>
                                                                <li class="splide__slide">
                                                                    <div class="overlay"></div>
                                                                    <img class="splide_cover" src="<?= $moongclePointImages[2]->image_path; ?>" alt="">
                                                                    <div class="image-text-wrapper">
                                                                        <div class="image-text">
                                                                            <?= !empty($moongclePoint->moongcle_point_3_title) ? $moongclePoint->moongcle_point_3_title : ''; ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($moongclePointImages[3])) : ?>
                                                                <li class="splide__slide">
                                                                    <div class="overlay"></div>
                                                                    <img class="splide_cover" src="<?= $moongclePointImages[3]->image_path; ?>" alt="">
                                                                    <div class="image-text-wrapper">
                                                                        <div class="image-text">
                                                                            <?= !empty($moongclePoint->moongcle_point_4_title) ? $moongclePoint->moongcle_point_4_title : ''; ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($moongclePointImages[4])) : ?>
                                                                <li class="splide__slide">
                                                                    <div class="overlay"></div>
                                                                    <img class="splide_cover" src="<?= $moongclePointImages[4]->image_path; ?>" alt="">
                                                                    <div class="image-text-wrapper">
                                                                        <div class="image-text">
                                                                            <?= !empty($moongclePoint->moongcle_point_5_title) ? $moongclePoint->moongcle_point_5_title : ''; ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <!-- //뭉클 포인트 -->
                                </div>
                            </div>
                            <!-- //뭉클딜 슬라이드 -->
                        <?php else : ?>
                            <div class="no__wrap">
                                <div class="nodata__con">
                                    선택하신 인원 수 또는 날짜에 맞는 상품이 없어요<br>
                                    인원 또는 날짜를 수정해 보세요!
                                </div>
                            </div>
                        <?php endif; ?>
					</div>
				</div>
				<!-- //뭉클한 혜택 -->

				<?php if (count($closedRooms) > 0) : ?>
					<!-- 찾으시는 객실이 마감되었나요? -->
					<div class="product-detail__close">
						<div class="tit__wrap">
							<p class="ft-default">찾으시는 객실이 마감되었나요?</p>
						</div>
						<div class="splide splide__close room-list__wrap">
							<div class="splide__track">
								<ul class="splide__list">

									<?php foreach ($closedRooms as $closedRoom) : ?>
										<li class="splide__slide">
											<div class="room-list__con">
												<div class="splide splide__within__product">
													<div class="splide__track real-image">
														<ul class="splide__list">
															<?php $roomImages = explode(':-:', $closedRoom->image_paths); ?>
															<?php if (!empty($roomImages[0])) : ?>
																<?php foreach ($roomImages as $roomImage) : ?>
																	<li class="splide__slide splide__list__product"><img src="<?= $roomImage; ?>" alt=""></li>
																<?php endforeach; ?>
															<?php else : ?>
																<li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
															<?php endif; ?>
														</ul>
													</div>
													<div class="slide-counter">
														<span class="current-slide">1</span> / <span class="total-slides"></span>
													</div>

                                                    <div class="skeleton-img-loader"></div>
												</div>
												<div class="room-name-block" style="gap: 6rem;">
													<p class="room-name fnOpenPop" data-name="roomDetail" data-room-idx="<?= $closedRoom->room_idx; ?>"><?= $closedRoom->room_name; ?></p>
													<a href="" style="white-space: nowrap;" class="btn-txt__arrow fnOpenPop" data-name="roomDetail" data-room-idx="<?= $closedRoom->room_idx; ?>">객실상세</a>
												</div>
												<p class="room-num">⏰마감 임박⏰ 남은 객실 <span>0개</span></p>
												<ul class="room-option">
													<?php if (empty($closedRoom->views)) : ?>
													<?php else : ?>
														<?php $roomViews = explode(':-:', $closedRoom->views); ?>
														<li class="option-view">
															<div>
																<?php foreach ($roomViews as $roomView) : ?>
																	<div><?= $roomView; ?></div>
																<?php endforeach; ?>
															</div>
														</li>
													<?php endif; ?>
													<?php $bedTypes = json_decode($closedRoom->room_bed_type, true); ?>
                                                                    
                                                    <?php
                                                    $totalBeds = array_sum($bedTypes);
                                                    ?>

                                                    <?php if ($totalBeds > 0) : ?>
                                                        <li class="option-bed">
                                                            <div>
                                                                <?php
                                                                $bedNames = [
                                                                    'dormitory_beds' => '도미토리',
                                                                    'single_beds' => '싱글베드',
                                                                    'super_single_beds' => '슈퍼싱글베드',
                                                                    'semi_double_beds' => '세미더블베드',
                                                                    'double_beds' => '더블베드',
                                                                    'queen_beds' => '퀸베드',
                                                                    'king_beds' => '킹베드',
                                                                    'hollywood_twin_beds' => '할리우드베드',
                                                                    'double_story_beds' => '이층 침대',
                                                                    'bunk_beds' => '벙크베드',
                                                                    'rollaway_beds' => '간이 침대',
                                                                    'futon_beds' => '요이불 세트',
                                                                    'capsule_beds' => '캡슐 침대',
                                                                    'sofa_beds' => '소파베드',
                                                                    'air_beds' => '에어베드'
                                                                ];

                                                                foreach ($bedTypes as $bedType => $bedCount) {
                                                                    if ($bedCount == 0) continue;

                                                                    if (isset($bedNames[$bedType])) {
                                                                        echo '<div>' . $bedNames[$bedType] . ' ' . $bedCount . '개</div>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>

													<?php if (!empty($closedRoom->room_size)) : ?>
														<li class="option-area">객실크기 <?= $closedRoom->room_size; ?>&#13217;</li>
													<?php endif; ?>
                                                    <li class="option-people">
                                                        기준 <?= $closedRoom->room_standard_person; ?>명 / 최대 
                                                        <span style="display: inline-block; margin-left: 0.4rem;<?= $closedRoom->room_max_person >= 3 ? ' color: #00CB9C; font-weight: bold;' : '' ?>">
                                                            <?= $closedRoom->room_max_person; ?>
                                                        </span>명
                                                    </li>
												</ul>
												<div class="btn__wrap">
													<button type="button" class="btn-sm__black fnOpenPop" data-name="alertAlarm">재오픈 알림</button>
												</div>
											</div>
										</li>
									<?php endforeach; ?>

								</ul>
							</div>
						</div>
					</div>
					<!-- //찾으시는 객실이 마감되었나요? -->
				<?php endif; ?>

                <!-- 부대 시설 -->
                <?php if (!empty($facilities)) : ?>
                    <div class="bullet__wrap">
                        <p class="title" style="padding: 2rem;">부대 시설</p>
                        <div class="overflow-x-visible padding-x-20">
                            <ul class="facility_slide overflow_slide">
                                <?php foreach ($facilities as $facility) : ?>
                                    <?php
                                        $imagePath = '';

                                        if (!empty($facility->images)) {
                                            $decoded = json_decode($facility->images, true);

                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded[0]['image_origin_path'])) {
                                                $imagePath = $decoded[0]['image_origin_path'];
                                            }
                                        }
                                    ?>
                                    <li class="fnOpenPop" 
                                        data-name="facilityInfo"
                                        data-image="<?= htmlspecialchars($imagePath); ?>"
                                        data-title="<?= htmlspecialchars($facility->facility_name); ?>"
                                        data-sub="<?= htmlspecialchars($facility->facility_sub); ?>"
                                        data-info="<?= htmlspecialchars($facility->facility_description); ?>">
                                        <div class="img_box">
                                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="이미지">
                                        </div>
                                        <div class="txt_box">
                                            <p class="tit"><?= htmlspecialchars($facility->facility_name); ?></p>
                                            <?php if (!empty($facility->facility_sub)) : ?>
                                                <span class="sub"><?= htmlspecialchars($facility->facility_sub); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

				<!-- 숙소 공지사항 및 정보 -->
				<div class="product-detail__notice" id="product-detail">
                    
                    <?php if (!empty($services)) : ?>
                        <!-- 아이와 함께 이용 가능 서비스 & 꿀팁 -->
                        <div class="bullet__wrap">
                            <p class="title">아이와 함께 이용 가능 서비스 & 꿀팁</p>
                            <div>
                                <ul class="kids_service__list">
                                    <?php foreach ($services as $service) : ?>
                                        <li>
                                            <span class="icon"><i class="fa-solid fa-circle-check"></i></span>
                                            <div>
                                                <p class="tit"><?= $service->service_name; ?></p>
                                                <p class="txt">
                                                    <?= $service->service_description; ?>
                                                </p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

					<div class="bullet__wrap">
						<p class="title">기본정보</p>
						<table class="tb__wrap">
							<colgroup>
								<col width="50%">
								<col width="50%">
							</colgroup>
							<thead>
								<tr>
									<th>체크인</th>
									<th>체크아웃</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php
									$formattedCheckinTime = date("H:i", strtotime($partner->stay_checkin_rule));
									$formattedCheckoutTime = date("H:i", strtotime($partner->stay_checkout_rule));
									?>
									<td><?= $formattedCheckinTime; ?> 부터</td>
									<td><?= $formattedCheckoutTime; ?> 까지</td>
								</tr>
							</tbody>
						</table>

                        <!-- 자주 묻는 질문 -->
                        <?php if (count($data['partnerFaq']) !== 0) : ?>
                            <div class="product-detail__notice" style="padding: 2rem 0;">
                                <div class="bullet__wrap">
                                    <p class="title" style="padding-bottom: 0;">자주 묻는 질문</p>
                                    <a href="" class="btn-txt__arrow fnOpenPop" data-name="faqDetail">더보기</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- //자주 묻는 질문 -->

						<?php if (!empty($partner->stay_basic_info)) : ?>
							<?php if ($partner->partner_thirdparty == 'onda') : ?>
								<div class="stay-detail-info"><?= textToTagUser($partner->stay_basic_info); ?></div>
							<?php else : ?>
								<div class="stay-detail-info"><?= textToTagBeauty($partner->stay_basic_info); ?></div>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<?php if (!empty($mainTagList['facility'])) : ?>
						<div class="bullet__wrap">
							<p class="title">편의시설</p>
							<a href="" class="btn-txt__arrow fnOpenPop" data-name="facilityDetail">더보기</a>
							<div class="amenities__list" style="margin-left: 4rem; font-size: 1.6rem;">
								<ul style="list-style: disc;">
									<?php foreach ($mainTagList['facility'] as $tag) : ?>
										<li style="display: list-item;">
											<span style="font-size: 1.6rem;"><?= $tag->tag_name; ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>

					<!-- new 취소 및 안심 환불 규정  -->
                    <?php if (!empty($cancelRules->count() > 0)) : ?>
						<div class="bullet__wrap cancel_new">
							<p class="title">취소 및 안심 환불 규정</p>
							<div class="stay-detail-info" style="white-space: unset;">
                                <?php
                                    $minDay100Rule = null;
                                    foreach ($cancelRules as $rule) {
                                        if ($rule->cancel_rules_percent == 100) {
                                            if (is_null($minDay100Rule) || $rule->cancel_rules_day < $minDay100Rule->cancel_rules_day) {
                                                $minDay100Rule = $rule;
                                            }
                                        }
                                    }
                                ?>
                                <div class="custom tit">
                                    <?php if ($minDay100Rule): ?>
                                        체크인 <?= htmlspecialchars($minDay100Rule->cancel_rules_day); ?>일 전까지 무료 취소 가능해요
                                    <?php endif; ?>
                                </div>
								<ul style="list-style: disc;">
                                        <?php 
                                            $showAfterCancel = false;
                                        foreach ($cancelRules as $key => $cancelRule) : ?>

										<?php if (!empty($cancelRules[$key + 1]->cancel_rules_percent) && $cancelRules[$key + 1]->cancel_rules_percent == 100) continue; ?>

										<?php if ($cancelRule->cancel_rules_percent != 0) : ?>
											<li>체크인 <?= $cancelRule->cancel_rules_day; ?>일 전 <?= !empty($cancelRule->cancel_rules_time) ? $cancelRule->cancel_rules_time : '23:50'; ?>까지 : <span class="point"><?= $cancelRule->cancel_rules_percent; ?>% 환불</span></li>
										<?php elseif ($cancelRule->cancel_rules_percent == 0 && !$showAfterCancel) : $showAfterCancel = true; ?>
											<li>이후 취소 시 <?= $cancelRule->cancel_rules_percent; ?>% 환불</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>

                                <?php if ($partner->partner_safe_cancel) : ?>
                                    <div class="secure_reservation">
                                        <div>
                                            <p class="tit">
                                                <i class="fa-solid fa-heart"></i> &nbsp;아이가 갑자기 아프다면? 걱정 마세요.
                                            </p>
                                            <p>
                                                이 숙소는 <span class="point">뭉클 안심 예약 보장제</span> 적용 숙소입니다. 투숙일 1일 전 갑자기 아이가 아프다면 진단서와 가족 관계서 증빙 시 1회에 한하여 날짜 변경이 가능해요. (단, 객실 가능 시 변경 가능하고, 일자에 따라 현장 추가 금액이 발생할 수 있어요.)
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($partner->stay_notice_info)) : ?>
						<div class="bullet__wrap">
							<p class="title">공지사항</p>
							<?php if ($partner->partner_thirdparty == 'onda') : ?>
								<div class="stay-detail-info"><?= textToTagUser($partner->stay_notice_info); ?></div>
							<?php else : ?>
								<div class="stay-detail-info"><?= textToTagBeauty($partner->stay_notice_info); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($partner->stay_important_info)) : ?>
						<div class="bullet__wrap">
							<p class="title">중요사항</p>
							<?php if ($partner->partner_thirdparty == 'onda') : ?>
								<div class="stay-detail-info"><?= textToTagUser($partner->stay_important_info); ?></div>
							<?php else : ?>
								<div class="stay-detail-info"><?= textToTagBeauty($partner->stay_important_info); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($partner->stay_amenity_info)) : ?>
						<div class="bullet__wrap">
							<p class="title">부대시설 정보</p>
							<?php if ($partner->partner_thirdparty == 'onda') : ?>
								<div class="stay-detail-info"><?= $partner->stay_amenity_info; ?></div>
							<?php else : ?>
								<div class="stay-detail-info"><?= textToTagBeauty($partner->stay_amenity_info); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($partner->stay_breakfast_info)) : ?>
						<div class="bullet__wrap">
							<p class="title">조식 정보</p>
							<?php if ($partner->partner_thirdparty == 'onda') : ?>
								<div class="stay-detail-info"><?= $partner->stay_breakfast_info; ?></div>
							<?php else : ?>
								<div class="stay-detail-info"><?= textToTagBeauty($partner->stay_breakfast_info); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($partner->stay_personnel_info)) : ?>
						<div class="bullet__wrap">
							<p class="title">인원 정보</p>
							<?php if ($partner->partner_thirdparty == 'onda') : ?>
								<div class="stay-detail-info"><?= $partner->stay_personnel_info; ?></div>
							<?php else : ?>
								<div class="stay-detail-info"><?= textToTagBeauty($partner->stay_personnel_info); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($mainTagList['barrierfree_public'])) : ?>
						<div class="bullet__wrap">
							<p class="title">공용공간 베리어프리 시설 및 서비스</p>
							<ul>
								<?php foreach ($mainTagList['barrierfree_public'] as $tag) : ?>
									<li>
										<span><?= $tag->tag_name; ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>

				<!-- //숙소 공지사항 및 정보 -->

				<!-- 숙소 위치 정보 -->
				<div class="product-detail__address">
					<div class="tit__wrap">
						<p class="title">숙소 위치정보</p>
						<div class="flex-between">
							<p id="partnerAddress" class="address"><?= $partner->partner_address1 . ' ' . $partner->partner_address2 . ' ' . $partner->partner_address3; ?></p>
							<button id="addressCopyButton" type="button" class="btn-copy"><span class="blind">복사하기</span></button>
						</div>
					</div>
					<div class="map__wrap">
						<input type="hidden" name="latitude" id="latitude" value="<?= $partner->partner_latitude; ?>">
						<input type="hidden" name="longitude" id="longitude" value="<?= $partner->partner_longitude; ?>">
						<div id="map" style="height:30rem;" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></div>
					</div>
				</div>
				<!-- //숙소 위치 정보 -->

                <!-- 우리 아이와 함께 숙소, 고민된다면? -->
                <div class="padding-x-20" style="padding-top: 2.4rem; padding-bottom: 2.4rem;">
                    <div id="recommendationBox" class="recommendation__box margin-top-30">
                        <div>
                            <p class="text">우리 아이와 함께 숙소, 고민된다면?</p>
                            <button type="button" id="gettingRecommendation" class="gettingRecommendation" onclick="location.href='/moongcledeals'">맘 편하게 숙소 추천 받기</button>
                        </div>
                    </div>
                </div>
                <!-- //우리 아이와 함께 숙소, 고민된다면? -->

                <?php if ($thirdparty == 'onda') : ?>
                    <div class="product-detail__notice">
                        <div class="bullet__wrap">
                            <p class="title">판매자 정보</p>
                            <a href="#" class="btn-txt__arrow fnOpenPop" data-name="sellerPopup">자세히 보기</a>
                        </div>
                    </div>
                <?php endif; ?>
			</div>

            <!-- 푸터 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/footer.php"; ?>
            <!-- //푸터 -->

			<!-- 하단 버튼 영역 -->
			<div class="bottom-fixed__wrap" style="background:none;">
				<div class="btn__wrap">
					<button id="selectRoomButton" class="btn-full__primary">객실 선택</button>
				</div>
			</div>
			<!-- //하단 버튼 영역 -->
		</div>
        
		<!-- 룸 상세 팝업 -->
		<div id="roomDetail" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
					<p class="title">객실 상세</p>
				</div>
				<div id="roomDetailFullModalContents" class="layerpop__contents enable-scroll">

				</div>
				<div class="layerpop__footer">

				</div>
			</div>
		</div>
		<!-- //룸 상세 팝업 -->

		<!-- 바텀 팝업(요금제 상세보기) -->
		<div id="roomRateplanDetail" class="layerpop__wrap">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">요금제 상세</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div id="moongcleofferDetailFullModalContents" class="layerpop__contents">

				</div>
			</div>
		</div>
		<!-- //바텀 팝업(상세보기) -->

        <!-- 부대 시설 상세 팝업 -->
		<div id="facilityInfo" class="layerpop__wrap type-full">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
                    <p class="title">부대 시설</p>
                </div>
                <div class="product-detail__wrap modal-scroll" style="width: 100%; padding: 2rem; padding-top: 0;">
                    <div class="img_box">
                        <img src="" alt="부대 시설 이미지">
                    </div>  
                    <div class="info_box">
                        <p class="facility_title"></p>
                        <span class="sub"></span>
                        <div class="info"></div> 
                    </div>
                </div>
            </div>
        </div>

		<!-- 태그 상세 팝업 -->
		<div id="facilityDetail" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
					<p class="title">편의시설</p>
				</div>
				<div class="product-detail__wrap modal-scroll" style="width: 100%;">
					<?php if (!empty($mainTagList['stay_type_detail'])) : ?>
						<div class="bullet__wrap">
							<p class="title">숙소구분</p>
							<div class="amenities__list" style="margin-left: 4rem; font-size: 1.6rem;">
								<ul style="list-style: disc;">
									<?php foreach ($mainTagList['stay_type_detail'] as $tag) : ?>
										<li style="display: list-item;">
											<span style="font-size: 1.6rem;"><?= $tag->tag_name; ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>
					<?php if (!empty($mainTagList['facility'])) : ?>
						<div class="bullet__wrap">
							<p class="title">편의시설</p>
							<div class="amenities__list" style="margin-left: 4rem; font-size: 1.6rem;">
								<ul style="list-style: disc;">

									<?php foreach ($mainTagList['facility'] as $tag) : ?>
										<li style="display: list-item;">
											<span style="font-size: 1.6rem;"><?= $tag->tag_name; ?></span>
										</li>
									<?php endforeach; ?>

								</ul>
							</div>
						</div>
					<?php endif; ?>
					<?php if (!empty($mainTagList['attraction'])) : ?>
						<div class="bullet__wrap">
							<p class="title">주변 즐길거리</p>
							<div class="amenities__list" style="margin-left: 4rem; font-size: 1.6rem;">
								<ul style="list-style: disc;">
									<?php foreach ($mainTagList['attraction'] as $tag) : ?>
										<li style="display: list-item;">
											<span style="font-size: 1.6rem;"><?= $tag->tag_name; ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>
					<?php if (!empty($mainTagList['service'])) : ?>
						<div class="bullet__wrap">
							<p class="title">서비스</p>
							<div class="amenities__list" style="margin-left: 4rem; font-size: 1.6rem;">
								<ul style="list-style: disc;">
									<?php foreach ($mainTagList['service'] as $tag) : ?>
										<li style="display: list-item;">
											<span style="font-size: 1.6rem;"><?= $tag->tag_name; ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="layerpop__footer">

				</div>
			</div>
		</div>
		<!-- //태그 상세 팝업 -->

        <!-- 자주 묻는 질문 상세 팝업 -->
		<div id="faqDetail" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
					<p class="title">자주 묻는 질문</p>
				</div>
                <div class="faq__wrap accordion__wrap modal-scroll"  style="width: 100%; padding: 0;">
                    <?php foreach ($data['partnerFaq'] as $partnerFaq) : ?>
                        <div class="accordion__list">
                            <div class="accordion__tit">
                                <p class="ft-default"><?= $partnerFaq->question; ?></p>
                                <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                            </div>
                            <div class="accordion__con">
                                <?= nl2br(htmlspecialchars($partnerFaq->answer)); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
			</div>
		</div>
		<!-- //자주 묻는 질문 상세 팝업 -->

		<!-- 바텀 팝업(일정 선택) -->
		<div id="popupDate" class="layerpop__wrap">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">일정 선택</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div class="layerpop__contents">
					<!-- 날짜 선택 -->
					<div class="tab__wrap tab-switch__wrap">
						<div class="tab-contents__wrap">
							<!-- 날짜 선택 탭 -->
							<div class="tab-contents active">
								<div class="calendar-wrap">
									<div class="placeholder"></div>
								</div>
							</div>
							<!-- //날짜 선택 탭 -->
							<!-- 월 선택 탭 -->
							<div class="tab-contents">
								<div class="select__wrap col-3 multi-select">
									<p class="title">2024년</p>
									<ul>
										<li><a>9월</a></li>
										<li><a>10월</a></li>
										<li><a>11월</a></li>
										<li><a>12월</a></li>
									</ul>

									<p class="title">2025년</p>
									<ul>
										<li><a>1월</a></li>
										<li><a>1월</a></li>
										<li><a>3월</a></li>
										<li><a>4월</a></li>
										<li><a>5월</a></li>
										<li><a>6월</a></li>
									</ul>
								</div>
							</div>
							<!-- //월 선택 탭 -->
						</div>
					</div>
					<!-- //날짜 선택 -->
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__line__primary" id="undecidedBtn">날짜 미정</button>
						<button class="btn-full__primary" id="selectDateBtn">선택</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(일정 선택) -->

		<!-- 바텀 팝업(인원 선택) -->
		<div id="popupGuest" class="layerpop__wrap">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">인원 선택</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
                <span style="
                        color: #a1a1a1;
                        font-size: 1.2rem;
                        display: block;
                        width: 80%;
                        margin: 2rem auto 0;
                        text-align: center;
                        word-break: keep-all;
                        line-height: 1.5;
                ">
                    * 요금은 <span style="color: #714CDC;">'기준 인원'</span>에 대한 금액이며, <span style="color: #714CDC;">최대 인원</span>까지 투숙 가능하나 기준 인원 외 인원은 현장 추가 요금이 발생할 수 있습니다.
                </span>
				<div class="layerpop__contents">
					<div class="count__wrap">
						<ul>
							<li>
								<p class="ft-default">성인</p>
								<div class="count__con">
									<button type="button" class="btn-count btn-count__minus" data-type="adult"><i class="ico ico-count__minus"></i></button>
									<span class="num" id="adultCount">0</span>
									<button type="button" class="btn-count btn-count__plus" data-type="adult"><i class="ico ico-count__plus"></i></button>
								</div>
							</li>
							<li>
								<p class="ft-default">아동</p>
								<div class="count__con">
									<button type="button" class="btn-count btn-count__minus" data-type="child"><i class="ico ico-count__minus"></i></button>
									<span class="num" id="childCount">0</span>
									<button type="button" class="btn-count btn-count__plus" data-type="child"><i class="ico ico-count__plus"></i></button>
								</div>

								<div class="count-age__wrap" id="countAgeWrap"></div>
							</li>
							<li>
								<p class="ft-default">유아</p>
								<div class="count__con">
									<button type="button" class="btn-count btn-count__minus" data-type="infant"><i class="ico ico-count__minus"></i></button>
									<span class="num" id="infantCount">0</span>
									<button type="button" class="btn-count btn-count__plus" data-type="infant"><i class="ico ico-count__plus"></i></button>
								</div>

								<div class="count-age__wrap" id="countMonthWrap"></div>
							</li>
						</ul>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary" id="selectGuestsBtn">선택</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(인원 선택) -->

		<!-- 바텀 팝업(나이 선택) -->
		<div id="popupAge" class="layerpop__wrap layerpop__select">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">나이 선택</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div class="layerpop__contents">
					<div class="select__wrap type-list single-select">
						<ul id="ageOptions">
							<li><a data-age="1">만 0세</a></li>
							<li><a data-age="1">만 1세</a></li>
							<li><a data-age="2">만 2세</a></li>
							<li><a data-age="3">만 3세</a></li>
							<li><a data-age="4">만 4세</a></li>
							<li><a data-age="5">만 5세</a></li>
							<li><a data-age="6">만 6세</a></li>
							<li><a data-age="7">만 7세</a></li>
							<li><a data-age="8">만 8세</a></li>
							<li><a data-age="9">만 9세</a></li>
							<li><a data-age="10">만 10세</a></li>
							<li><a data-age="11">만 11세</a></li>
							<li><a data-age="12">만 12세</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(나이 선택) -->

		<!-- 바텀 팝업(개월수 선택) -->
		<div id="popupMonth" class="layerpop__wrap layerpop__select">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">개월수 선택</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div class="layerpop__contents">
					<div class="select__wrap type-list single-select">
						<ul id="monthOptions">
							<li><a data-month="1">1개월</a></li>
							<li><a data-month="2">2개월</a></li>
							<li><a data-month="3">3개월</a></li>
							<li><a data-month="4">4개월</a></li>
							<li><a data-month="5">5개월</a></li>
							<li><a data-month="6">6개월</a></li>
							<li><a data-month="7">7개월</a></li>
							<li><a data-month="8">8개월</a></li>
							<li><a data-month="9">9개월</a></li>
							<li><a data-month="10">10개월</a></li>
							<li><a data-month="11">11개월</a></li>
							<li><a data-month="12">12개월</a></li>
							<li><a data-month="13">13개월</a></li>
							<li><a data-month="14">14개월</a></li>
							<li><a data-month="15">15개월</a></li>
							<li><a data-month="16">16개월</a></li>
							<li><a data-month="17">17개월</a></li>
							<li><a data-month="18">18개월</a></li>
							<li><a data-month="19">19개월</a></li>
							<li><a data-month="20">20개월</a></li>
							<li><a data-month="21">21개월</a></li>
							<li><a data-month="22">22개월</a></li>
							<li><a data-month="23">23개월</a></li>
							<li><a data-month="24">24개월</a></li>
							<li><a data-month="25">25개월</a></li>
							<li><a data-month="26">26개월</a></li>
							<li><a data-month="27">27개월</a></li>
							<li><a data-month="28">28개월</a></li>
							<li><a data-month="29">29개월</a></li>
							<li><a data-month="30">30개월</a></li>
							<li><a data-month="31">31개월</a></li>
							<li><a data-month="32">32개월</a></li>
							<li><a data-month="33">33개월</a></li>
							<li><a data-month="34">34개월</a></li>
							<li><a data-month="35">35개월</a></li>
							<li><a data-month="36">36개월</a></li>
							<li><a data-month="37">37개월</a></li>
							<li><a data-month="38">38개월</a></li>
							<li><a data-month="39">39개월</a></li>
							<li><a data-month="40">40개월</a></li>
							<li><a data-month="41">41개월</a></li>
							<li><a data-month="42">42개월</a></li>
							<li><a data-month="43">43개월</a></li>
							<li><a data-month="44">44개월</a></li>
							<li><a data-month="45">45개월</a></li>
							<li><a data-month="46">46개월</a></li>
							<li><a data-month="47">47개월</a></li>
							<li><a data-month="48">48개월</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(개월수 선택) -->

        <!-- 알럿 팝업 -->
		<div id="alertPerson" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">인원을 먼저 선택해 주세요!</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary fnClosePop">확인</button>
					</div>
				</div>
			</div>
		</div>

		<!-- 바텀 팝업(객실 수량 선택) -->
		<div id="popupRoomQuantity" class="layerpop__wrap">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">객실 수량 선택</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div class="layerpop__contents">
					<div class="count__wrap">
						<ul>
							<li>
								<div>
									<p id="roomRateplanName1" class="ft-default"></p>
									<p id="roomRateplanName2" class="ft-default"></p>
								</div>
                                <div style="margin-left: auto;">
                                    <div class="count__con">
                                        <button type="button" class="btn-count btn-count__minus" data-type="room"><i class="ico ico-count__minus"></i></button>
                                        <span class="num" id="roomCount">0</span>
                                        <button type="button" class="btn-count btn-count__plus" data-type="room"><i class="ico ico-count__plus"></i></button>
                                    </div>
                                </div>
							</li>
						</ul>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
                        <button 
                            class="btn-full__primary" id="selectRoomQuantityBtn">
                            선택
                        </button>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(객실 수량 선택) -->

        <!-- 임시 팝업 (제거) -->
        <div id="alertMobile" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
                <div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">웹 브라우저에서 계속하기</p>
						<p class="desc" style="word-break: keep-all;">
                            임시 점검 중인 관계로 잠시 모바일 웹에서만 로그인 및 원활한 이용이 가능합니다. 너그러운 양해 부탁드립니다.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary fnClosePop" style="background: #714CDC; color: #fff" onclick="outLink('https://www.moongcletrip.com/moongcleoffer/product/<?= $partner->partner_idx ?>')">모바일 웹에서 계속하기</button>
					</div>
				</div>
			</div>
		</div>

		<!-- 알럿 팝업 -->
		<div id="alertAlarm" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">아직 준비중이에요.</p>
						<p class="desc">
							재오픈 알림 기능은 아직 준비중이에요! 서둘러 준비하여 아쉽게 놓친 숙소를 빠르게 만나보실 수 있도록 준비할게요.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">확인</button>
						<!-- <button class="btn-full__primary">알림받기</button> -->
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->

		<!-- 알럿 팝업_스크롤 객실 1개 지나갈 때 자동 팝업(본인의 뭉클딜이 없을 경우에만)-->
		<div id="alertAlarm2" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">더 좋은 혜택을 원하시나요?</p>
						<p class="desc">
							원하는 여행을 등록하면 <br>
							꼭 맞는 뭉클딜 제안이 도착해요
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">아니요</button>
						<button class="btn-full__primary">뭉클딜 등록하기</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->

		<!-- 뭉클딜받기 모웹 팝업 -->
		<div id="mobilePopup" class="layerpop__wrap type-center mobileweb-popup">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<i class="ico ico-logo__big"></i>
					<p class="ft-xxl">
						숙소 추천받기는 앱에서만 가능해요! <br>
						무료로 숙소 추천을 받아볼까요?
					</p>
				</div>
				<div class="layerpop__footer">
					<button class="btn-full__black">지금 앱 다운로드</button>
				</div>
			</div>
		</div>
		<!-- //뭉클딜받기 모웹 팝업  -->

		<div id="loginKakaoPopup" class="layerpop__wrap type-center mobileweb-popup">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<i class="ico ico-logo__big"></i>
					<p class="ft-xxl" style="word-break: keep-all;">
						예약 진행을 위해 로그인이 필요해요.<br>
						아래의 방법으로 간편하게 로그인 해보시겠나요?
					</p>
				</div>
                <div class="layerpop__footer" style="display: flex; align-items: center; gap: 1rem;">
					<button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap; font-size: 1.2rem;">카카오 1초 로그인</button>
                    <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()" style="font-size: 1.2rem;">이메일로 계속하기</button>
				</div>
			</div>
		</div>

         <!-- 찜하기 로그인 팝업 -->
         <div id="loginLikePopup" class="layerpop__wrap type-center mobileweb-popup">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<i class="ico ico-logo__big"></i>
					<p class="ft-xxl" style="word-break: keep-all;">
						로그인이 필요해요.<br>
						아래의 방법으로 간편하게 로그인 해보시겠나요?
					</p>
				</div>
				<div class="layerpop__footer" style="display: flex; align-items: center; gap: 1rem;">
					<button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap; font-size: 1.2rem;">카카오 1초 로그인</button>
                    <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()" style="font-size: 1.2rem;">이메일로 계속하기</button>
				</div>
			</div>
		</div>

		<div id="loginKakaoPopupCoupon" class="layerpop__wrap type-center mobileweb-popup">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<i class="ico ico-logo__big"></i>
					<p class="ft-xxl" style="word-break: keep-all;">
						쿠폰 다운로드를 위해 로그인이 필요해요.<br>
						아래의 방법으로 간편하게 로그인 해보시겠나요?
					</p>
				</div>
				<div class="layerpop__footer">
                    <button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap; font-size: 1.2rem;">카카오 1초 로그인</button>
                    <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()" style="font-size: 1.2rem;">이메일로 계속하기</button>
				</div>
			</div>
		</div>

		<div id="reviewDetail" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
					<p class="title">리뷰</p>
				</div>
				<div class="review-list__wrap" style="margin-top: 2rem; width: 100%; overflow-y: scroll; height: calc(100vh - 9.6rem); padding: 0 2rem">
					<?php foreach ($reviews as $review) : ?>
						<div class="review-list__con">
							<div class="community-top">
								<div class="user-wrap">
									<p class="img"><img src="/assets/app/images/common/no_profile.jpg" alt=""></p>
									<div>
										<p class="name"><?= $review->user_nickname; ?></p>
										<div class="start">
											<?php
											$fullStars = floor($review->rating);
											$halfStar = ($review->rating - $fullStars) >= 0.5 ? 1 : 0;
											$emptyStars = 5 - $fullStars - $halfStar;
											?>
											<?php for ($i = 0; $i < $fullStars; $i++) : ?>
												<i class="ico ico-star"></i>
											<?php endfor; ?>
											<?php if ($halfStar) : ?>
												<i class="ico ico-star__half"></i>
											<?php endif; ?>
											<?php for ($i = 0; $i < $emptyStars; $i++) : ?>
												<i class="ico ico-star__empty"></i>
											<?php endfor; ?>
										</div>
									</div>
								</div>
								<div class="flex-center">
									<p class="date"><?= explode(' ', $review->created_at)[0]; ?></p>
								</div>
							</div>
							<?php if (!empty($review->image_list)) : ?>
                                <div class="review-img" style="padding-bottom: 0;">
                                    <div class="splide splide__default">
                                        <div class="splide__track">
                                            <ul class="splide__list">
                                                <?php
                                                $reviewImages = json_decode($review->image_list);
                                                ?>

                                                <?php if (!empty($reviewImages)) : ?>
                                                    <?php foreach ($reviewImages as $reviewImage) : ?>
                                                        <?php
                                                        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                        $allowedVideoExtensions = ['mov', 'mp4'];
                                                        ?>
                                                        <?php if (in_array($reviewImage->extension, $allowedImageExtensions)) : ?>
                                                            <li class="splide__slide splide__list__product"><img src="<?= $reviewImage->path; ?>" alt=""></li>
                                                        <?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
                                                            <li class="splide__slide splide__list__product">
                                                                <video class="video-element" controls>
                                                                    <source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
                                                                    현재 브라우저가 지원하지 않는 영상입니다.
                                                                </video>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
							<div class="review-txt">
								<p class="review">
									<?= nl2br($review->review_content); ?>
								</p>
								<a class="btn-more">더보기</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="layerpop__footer">

				</div>
			</div>
		</div>

		<div id="sellerPopup" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap" style="margin-bottom: 1rem;">
						<p class="title">판매자 정보</p>
					</div>

					<table class="tb__wrap" style="margin-bottom: 3rem; padding: 1rem;">
						<thead>
							<tr style="background-color: #f2f2f2;">
								<th style="width: 20%;">항목</th>
								<th>내용</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>상호</td>
								<td>주식회사 온다</td>
							</tr>
							<tr>
								<td>대표자명</td>
								<td>오현석</td>
							</tr>
							<tr>
								<td>주소</td>
								<td>서울특별시 강남구 테헤란로83길 49, 피프스애비뉴 2~4층 (주)온다</td>
							</tr>
							<tr>
								<td>사업자 번호</td>
								<td>332-87-00460</td>
							</tr>
						</tbody>
					</table>
					<p class="ft-xs" style="text-align: left;">
						* 판매자 정보는 판매자의 명시적 동의 없이 영리 목적의 마케팅·광고 등에 활용할 수 없습니다.
						이를 어길 시 정보통신망법 등 관련 법령에 의거하여 과태료 부과 및 민형사상 책임을 지게 될 수 있습니다.
					</p>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">확인</button>
					</div>
				</div>
			</div>
		</div>

		<!-- 토스트팝업 -->
		<div id="addressCopyToast" class="toast__wrap">
			<div class="toast__container">
				<i class="ico ico-info"></i>
				<p>주소를 클립보드에 복사했습니다!</p>
			</div>
		</div>
		<!-- //토스트팝업 -->

        <!-- 토스트팝업 -->
        <div id="toastPopupLike" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p></p>
            </div>
        </div>
        <!-- //토스트팝업 -->

		<div id="allPictures" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-close fnClosePop"><span class="blind">닫기</span></button>
					<p class="title">전체 사진</p>
				</div>
				<div class="layerpop__contents scroll-enabled">
					<div class="product-detail__picture">
						<ul>
							<?php foreach ($stayImages as $key => $stayImage) : ?>
								<li onclick="fullImageView(<?= $key; ?>)"><img src="<?= $stayImage; ?>" alt=""></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<div class="layerpop__footer">

				</div>
			</div>
		</div>

		<div id="allPicture" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-close fnClosePop"><span class="blind">닫기</span></button>
				</div>
				<div class="layerpop__contents">
					<div class="product-detail__allpicture zoom-box">
						<div class="splide splide__product splide__product_full_img zoom">
							<div class="splide__track zoom-wrapper">
								<ul class="splide__list full-size-image-list">
									<?php foreach ($stayImages as $stayImage) : ?>
										<li class="splide__slide image-position-center zoom-container"><img src="<?= $stayImage; ?>" alt="" class="zoom-image"></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="slide-counter zoom">
								<span class="current-slide">1</span> / <span><?= count($stayImages); ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="layerpop__footer">

				</div>
			</div>
		</div>

		<!-- 히단 네비게이션 -->
		<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>
	</div>

	<div id="pageLoader" class="loader" style="display: none;">
		<div class="spinner"></div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

   <?php

        // 이미지 배열 추출
        $firstImage = !empty($stayImages[0]) ? $stayImages[0] : '/assets/app/images/demo/moongcle-noimg.png';

        // 숙소 등급 추출
        $stayRating = '';
        $stayTags = explode(':-:', $partner->tags);
        foreach ($stayTags as $stayTag) { 
            if (in_array($stayTag, ['1성', '2성', '3성', '4성', '5성'])) {
                $stayRating = $stayTag;
                break;
            }
        }

        // 숙소 유형 추출
        $stayTypes = explode(':-:', $partner->types);
        $stayTypesText = implode(', ', array_filter($stayTypes));

        // 최저가 정보 초기화
        $minDiscountRate = 0;
        $minDiscountPrice = 0;
        $minOriginalPrice = 0;

        if (!empty($data['stayMoongcleoffers']) && is_array($data['stayMoongcleoffers'])) {
            foreach ($data['stayMoongcleoffers'] as $stayMoongcleoffer) {
				if (!empty($stayMoongcleoffer->moongcleoffers)) {
                    foreach ($stayMoongcleoffer->moongcleoffers as $moongcleoffer) {
                        $original = $moongcleoffer->total_basic_price;
                        $discounted = $moongcleoffer->total_sale_price;
                        if ($discounted > 0 && ($minDiscountPrice == 0 || $discounted < $minDiscountPrice)) {
                            $minDiscountPrice = $discounted;
                            $minOriginalPrice = $original;
                            $minDiscountRate = $original > 0 ? round((($original - $discounted) / $original) * 100, 1) : 0;
                        }
                    }
                }
            }
        }

        $formattedDiscountPrice = number_format($minDiscountPrice);
        $formattedOriginalPrice = number_format($minOriginalPrice);
        $formattedDiscountRate = number_format($minDiscountRate, 1);
    ?>

    <script>
        window.addEventListener('load', () => {
            handleImageSkeletons('.real-image', '.skeleton-img-loader');
            handleImageSkeletons('.real-image-main', '.skeleton-img-loader-main');
        });

        function handleImageSkeletons(trackClass, loaderClass) {
            const tracks = document.querySelectorAll(trackClass);
            const loaders = document.querySelectorAll(loaderClass);

            tracks.forEach((track, index) => {
                const loader = loaders[index]; 

                const images = track.querySelectorAll('img');
                let loadedCount = 0;
                const totalImages = images.length;

                if (totalImages === 0) {
                    showImageTrack(track, loader);
                    return;
                }

                images.forEach((img) => {
                    if (img.complete) {
                        loadedCount++;
                        if (loadedCount === totalImages) showImageTrack(track, loader);
                    } else {
                        img.addEventListener('load', () => {
                            loadedCount++;
                            if (loadedCount === totalImages) showImageTrack(track, loader);
                        });
                    }
                });
            });
        }

        function showImageTrack(track, loader) {
            if (loader) {
                loader.classList.add('fade-out');
                setTimeout(() => loader.remove(), 300);
            }

            if (track) {
                track.classList.add('show');
            }
        }
    </script>

    <script>
        // 숙소 정보 저장
        const hotel = {
            id: <?= $partner->partner_idx ?>,
            img: "<?= $firstImage ?>",  
            stayName: "<?= $partner->partner_name ?>",
            stayRating: "<?= $stayRating ?>",
            stayTypes: "<?= $stayTypesText ?>",
            address: "<?= $partner->partner_address1 ?>",
            reviewCount: "<?= $reviewCount ?>",
            reviewRating: "<?= $reviewRating ?>",
            discountRate: "<?= $formattedDiscountRate ?>",         
            discountedPrice: "<?= $formattedDiscountPrice ?>",
            originalPrice: "<?= $formattedOriginalPrice ?>",
            intervalDays: <?= $intervalDays ?>,
            link: window.location.pathname + window.location.search
        };

        document.addEventListener("DOMContentLoaded", () => {
			let test = <?= json_encode($data['stayMoongcleoffers']); ?>;
			// console.log(test);
            saveToRecentHotels(hotel);

            // 더보기 노출 컨트롤
            checkReviewLineCount();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const mainSlider = document.querySelector("#mainSlider");
            const fixedRateplanTitle = document.querySelector("#fixedRateplanTitle");

            // 뭉클딜 슬라이드
            var swiper1 = new Swiper(".rateplan__slide .swiper-container", {
                slidesPerView: "auto",
                slidesPerGroup: 1,
                autoHeight: true, 
                centeredSlides: false,
                loop: true,
                init: false,

                allowTouchMove: true,
                simulateTouch: true,  
                grabCursor: true,   
                threshold: 10,       
                touchRatio: 0.9,     
                resistance: true,     
                resistanceRatio: 0.5,  
                longSwipes: true,     
                longSwipesRatio: 0.3, 
                longSwipesMs: 300,   

                observer: true,
                observeParents: true,
                spaceBetween: 0,
                speed: 600, 

                noSwipingClass: ["splide__product", "fnOpenPop"],

                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },

                on: {
                    init: function () {
                        adjustSlideHeight(this);
                        moveToHashSlide(this);
                    },

                    slideChange: function () {
                        adjustSlideHeight(this);
                    },
                },
            });

            swiper1.init();

            // 슬라이드 변환 시 이벤트
            var prevIndex = swiper1.realIndex;
            var isFirstLoad = true; 

            swiper1.on('slideChange', function () {
                if (prevIndex !== swiper1.realIndex) {
                    prevIndex = swiper1.realIndex;

                    // 첫 진입 + 해시 없음
                    if (isFirstLoad && !location.hash) {
                        const currentSlide = swiper1.slides[swiper1.activeIndex];
                        const stayMoongcleIdx = currentSlide.getAttribute('data-stayMoongcleoffer-idx');

                        if (stayMoongcleIdx) {
                            location.hash = `#${stayMoongcleIdx}`;
                        }

                        isFirstLoad = false;
                        setTimeout(() => {
                            scrollToMainSlider();
                        }, swiper1.params.speed);
                        return;
                    }

                    // 첫 진입 + 해시 있음
                    if (isFirstLoad) {
                        isFirstLoad = false;

                        const scrollContainer = document.querySelector("#mobileWrap") || window;
                        scrollContainer.scrollTo({
                            top: 0,
                            behavior: "auto"
                        });
                        return;
                    }

                    setTimeout(() => {
                        scrollToMainSlider();
                    }, swiper1.params.speed);

                    const currentSlide = swiper1.slides[swiper1.activeIndex];
                    const stayMoongcleIdx = currentSlide.getAttribute('data-stayMoongcleoffer-idx');

                    if (stayMoongcleIdx) {
                        const newHash = `#${stayMoongcleIdx}`;

                        if (location.hash !== newHash) {
                            location.hash = newHash;
                        }
                    }

                    prevIndex = swiper1.realIndex;
                }
            });

            function moveToHashSlide(swiper) {
                const hash = location.hash.replace('#', '');

                if (hash) {
                    let targetIndex = -1;

                    swiper.slides.forEach((slide) => {
                        if (slide.classList.contains('swiper-slide-duplicate')) return;

                        if (slide.getAttribute('data-stayMoongcleoffer-idx') === hash) {
                            targetIndex = slide.getAttribute('data-swiper-slide-index');
                        }
                    });

                    if (targetIndex !== -1) {
                        swiper.slideToLoop(Number(targetIndex), 0);
                    }
                } 
            }
            
            function scrollToMainSlider() {
                if (!mainSlider) return;

                const targetElement = document.getElementById("product-detail__tit");
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            }

            // 슬라이드 높이 조정
            function adjustSlideHeight(swiper) {
                if (!swiper.slides || swiper.slides.length === 0) return;

                var currentSlide = swiper.slides[swiper.activeIndex];
                if (!currentSlide) return;

                var slideHeight = currentSlide.offsetHeight || 0;
                swiper.wrapperEl.style.height = slideHeight + 'px';

                swiper.slides.forEach(slide => {
                    slide.style.height = '';
                });
            }

            // 화살표 노출 컨트롤
            const slider = document.querySelector("#mainSlider");
            const navBox = document.querySelector(".navigation_box");

            if (!slider || !navBox) return;

            function checkSliderInView() {
                const rect = slider.getBoundingClientRect();
                const inViewport = rect.top < window.innerHeight && rect.bottom > 0;

                if (inViewport) {
                    navBox.classList.add("show");
                } else {
                    navBox.classList.remove("show");
                }
            }

            // 초기 실행
            checkSliderInView();

            [window, document, document.documentElement,
            document.querySelector("#scrollContainer"),
            document.querySelector("#mobileWrap")
            ].forEach(el => {
                if (el) {
                    el.addEventListener("scroll", checkSliderInView);
                }
            });
        });
    </script>

	<script>
		// 하단 네비게이션바 컨트롤
		document.addEventListener("DOMContentLoaded", () => {
			const productDetail = document.getElementById("product-detail");
			const bottomNaviWrap = document.querySelector(".bottom-navi__wrap");
			const bottomFixedWrap = document.querySelector(".bottom-fixed__wrap");
			const scrollContainer = document.getElementById("scrollContainer") || window;

			if (!productDetail || !bottomNaviWrap || !bottomFixedWrap) return;

			scrollContainer.addEventListener("scroll", () => {
				const rect = productDetail.getBoundingClientRect();

				const isVisible = rect.top <= window.innerHeight && rect.bottom >= 100;
				bottomFixedWrap.style.display = isVisible ? "block" : "none";
				bottomFixedWrap.style.bottom = isVisible ? "10rem" : "0";
				bottomFixedWrap.style.opacity = isVisible ? "84%" : "0";
			});
		});
	</script>

	<script>
		window.addEventListener('pageshow', function(event) {
			if (event.persisted) { // 페이지가 캐시에서 복원된 경우
				hideLoader();
			} else {
				hideLoader(); // 페이지가 새로 로드된 경우에도 처리
			}
		});

		sessionStorage.setItem('previousPage', window.location.href);

		const allRoomsData = <?= !empty($allRooms) ? json_encode($allRooms) : '{}'; ?>;
		const calendarData = <?= !empty($openCalendar) ? json_encode($openCalendar) : '{}'; ?>;

		let currentIdx = 0;
		let activeImage = null;
		let thirdparty = '<?= $thirdparty; ?>';

		<?php if (!empty($user->user_idx) && !$isGuest) : ?>
			currentIdx = <?= $user->user_idx; ?>
		<?php endif; ?>

		let params = new URLSearchParams(window.location.search);
		let filterStartDate = params.get('startDate') || '';
		let filterEndDate = params.get('endDate') || '';
		let filterAdult = parseInt(params.get('adult')) || 0;
		let filterChild = parseInt(params.get('child')) || 0;
		let filterInfant = parseInt(params.get('infant')) || 0;
		let filterChildAge = JSON.parse(params.get('childAge')) || {};
		let filterInfantMonth = JSON.parse(params.get('infantMonth')) || {};

		let reservationPartnerIdx = <?= $partner->partner_idx ?>;
		let reservationRoomIdx = 0;
		let reservationRateplanIdx = 0;
		let reservationMoongcleofferIdx = 0;

		flatpickr('.calendar-wrap .placeholder', {
			inline: true,
			mode: "range",
			minDate: 'today',
			altFormat: "m.d D", // 월.일 요일 형태
			dateFormat: "Y-m-d", // 서버로 전달할 형식 (기본 값)
			locale: {
				firstDayOfWeek: 0,
				weekdays: {
					shorthand: ["일", "월", "화", "수", "목", "금", "토"],
					longhand: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"]
				},
				months: {
					shorthand: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
					longhand: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
				},
			},
			defaultDate: filterStartDate && filterEndDate ? [filterStartDate, filterEndDate] : null,
			onReady: function(selectedDates, dateStr, instance) {
				if (selectedDates.length === 2) {
					// 디폴트 값으로 선택된 날짜를 표시
					const formattedStartDate = instance.formatDate(selectedDates[0], "m.d D");
					const formattedEndDate = instance.formatDate(selectedDates[1], "m.d D");
					document.getElementById("selectedDate").textContent = `${formattedStartDate} ~ ${formattedEndDate}`;
				}
			},
			onChange: function(selectedDates, dateStr, instance) {
				if (selectedDates.length === 2) {
					if (selectedDates[0].getTime() === selectedDates[1].getTime()) {
						const newEndDate = new Date(selectedDates[0]);
						newEndDate.setDate(newEndDate.getDate() + 1);
						selectedDates[1] = newEndDate;
						instance.setDate([selectedDates[0], newEndDate]);
					}

					const startDate = instance.formatDate(selectedDates[0], "m.d D");
					const endDate = instance.formatDate(selectedDates[1], "m.d D");
					document.getElementById("selectedDate").textContent = `${startDate} ~ ${endDate}`;

					filterStartDate = instance.formatDate(selectedDates[0], "Y-m-d");
					filterEndDate = instance.formatDate(selectedDates[1], "Y-m-d");
				}
			},
			onDayCreate: function(dObj, dStr, fp, dayElem) {
				const dateObj = new Date(dayElem.dateObj.getTime() + (9 * 60 * 60 * 1000));
				const date = dateObj.toISOString().split('T')[0];

				const today = new Date(new Date().getTime() + (9 * 60 * 60 * 1000))
					.toISOString()
					.split('T')[0];

				const currentMonth = fp.currentMonth;
				const currentYear = fp.currentYear;

				const dayMonth = dateObj.getMonth();
				const dayYear = dateObj.getFullYear();

				if (date < today) {
					dayElem.classList.add('flatpickr-disabled');
					return;
				}

				if (dayYear === currentYear && dayMonth === currentMonth) {
					const dayOfWeek = dateObj.getDay();

					if (dayOfWeek === 0) {
						dayElem.style.color = "red";
					} else if (dayOfWeek === 6) {
						dayElem.style.color = "blue";
					}
				}

				const dayData = calendarData.find(item => item.inventory_date === date);

				if (dayData && dayData.status === 'open' && dayData.lowest_price !== null) {
					const priceLabel = document.createElement('div');
					priceLabel.className = 'price-label';
					priceLabel.textContent = `${formatToMan(dayData.lowest_price)}`;
					dayElem.appendChild(priceLabel);
				}
			}
		});

		function formatToMan(price) {
			const manUnit = (price / 10000).toFixed(1);

			return `${manUnit}만`;
		}

		const deleteButton = document.getElementById('searchTextDeleteBtn');
		const featuredTags = document.getElementById('featuredTags');
		const popularTags = document.getElementById('popularTags');
		const realTimeSearch = document.getElementById('realTimeSearch');

		// 선택된 나이를 저장하는 객체
		let selectedAges = {};
		let selectedMonths = {};

		let adultCount = 0;
		let childCount = 0;
		let infantCount = 0;
		let roomCount = 1;

		// 선택된 인원 수 및 나이 초기화
		function initializeGuestSettings() {
			// 인원 수 초기화
			document.getElementById('roomCount').textContent = 1;
			document.getElementById('adultCount').textContent = filterAdult;
			document.getElementById('childCount').textContent = filterChild;
			document.getElementById('infantCount').textContent = filterInfant;

			roomCount = 1;
			adultCount = filterAdult;
			childCount = filterChild;
			infantCount = filterInfant;
			selectedAges = filterChildAge;
			selectedMonths = filterInfantMonth;

			// 각 인원 수에 따라 나이 선택 항목 초기화
			initializeChildAges(filterChild, selectedAges);
			initializeInfantAges(filterInfant, selectedMonths);
			updateSelectedGuestsText();
		}

		// 선택한 인원을 표시하는 함수
		function updateSelectedGuestsText() {
			let guestText = '';

			// 선택된 인원수에 따라 텍스트 설정
			if (filterAdult > 0 || filterChild > 0 || filterInfant > 0) {
				guestText = `성인 ${filterAdult}`;
				if (filterChild > 0) guestText += `, 아동 ${filterChild}`;
				if (filterInfant > 0) guestText += `, 유아 ${filterInfant}`;
			} else {
				guestText = '인원 선택';
			}

			document.getElementById('selectedGuests').textContent = guestText;
		}

		// 아동 수에 따라 나이 선택 항목 업데이트
		function initializeChildAges(childCount, selectedAges = {}) {
			const countAgeWrap = document.getElementById('countAgeWrap');

			for (let i = 1; i <= childCount; i++) {
				const ageDiv = document.createElement('div');
				ageDiv.className = 'count-age';
				ageDiv.innerHTML = `
                    <p class="ft-xxs">아동 <span>${i}</span></p>
                    <div class="count-select" data-name="popupAge" data-child="${i}">
                        <p>${selectedAges[i] ? `만 ${selectedAges[i]}세` : '나이 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
				ageDiv.querySelector('.count-select').addEventListener('click', openAgePopup);
				countAgeWrap.appendChild(ageDiv);
			}
		}

		// 유아 수에 따라 개월수 선택 항목 업데이트
		function initializeInfantAges(infantCount, selectedMonths = {}) {
			const countMonthWrap = document.getElementById('countMonthWrap');

			for (let i = 1; i <= infantCount; i++) {
				const monthDiv = document.createElement('div');
				monthDiv.className = 'count-age';
				monthDiv.innerHTML = `
                    <p class="ft-xxs">유아 <span>${i}</span></p>
                    <div class="count-select" data-name="popupMonth" data-infant="${i}">
                        <p>${selectedMonths[i] ? `${selectedMonths[i]}개월` : '개월수 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
				monthDiv.querySelector('.count-select').addEventListener('click', openMonthPopup);
				countMonthWrap.appendChild(monthDiv);
			}
		}

		// 페이지 로드 시 초기화
		initializeGuestSettings();

		// 인원 수 조절 버튼 이벤트
		document.querySelectorAll('.btn-count').forEach(button => {
			button.addEventListener('click', () => {
				const type = button.getAttribute('data-type');

				if (thirdparty === 'onda' && type === 'room') {
					return;
				}

				const isPlus = button.classList.contains('btn-count__plus');
				const countElement = document.getElementById(`${type}Count`);
				let currentCount = parseInt(countElement.textContent);

				// 인원 수 증감 로직
				if (isPlus) {
					currentCount += 1;
				} else {
					if (currentCount > 0) {
						currentCount -= 1;
					}
				}
				countElement.textContent = currentCount;

				// 아동 수 변경 시 나이 선택 항목 동적 생성
				if (type === 'child') {
					updateChildAges(currentCount);

					// 아동 수가 감소할 경우 선택된 나이에서 제거
					if (!isPlus) {
						removeLastChildAge(currentCount);
					}
				}

				if (type === 'infant') {
					updateInfantAges(currentCount);

					// 아동 수가 감소할 경우 선택된 나이에서 제거
					if (!isPlus) {
						removeLastInfantAge(currentCount);
					}
				}

				// 현재 인원 수 업데이트
				if (type === 'adult') {
					adultCount = currentCount;
				} else if (type === 'child') {
					childCount = currentCount;
				} else if (type === 'infant') {
					infantCount = currentCount;
				} else if (type === 'room') {
					roomCount = currentCount;
				}
			});
		});

		// 아동 수에 따라 나이 선택 항목 업데이트
		function updateChildAges(childCount) {
			const countAgeWrap = document.getElementById('countAgeWrap');

			// 현재 아동 수보다 작은 수만큼의 기존 요소만 남기고 나머지를 추가
			const currentChildren = countAgeWrap.children.length;

			for (let i = currentChildren + 1; i <= childCount; i++) {
				const ageDiv = document.createElement('div');
				ageDiv.className = 'count-age';
				ageDiv.innerHTML = `
                <p class="ft-xxs">아동 <span>${i}</span></p>
                    <div class="count-select" data-name="popupAge" data-child="${i}">
                        <p>${selectedAges[i] ? `만 ${selectedAges[i]}세` : '나이 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
				ageDiv.querySelector('.count-select').addEventListener('click', openAgePopup);
				countAgeWrap.appendChild(ageDiv);
			}

			// 남은 아동 수보다 많은 항목이 있으면 잘라내기
			while (countAgeWrap.children.length > childCount) {
				countAgeWrap.removeChild(countAgeWrap.lastElementChild);
			}
		}

		function updateInfantAges(infantCount) {
			const countMonthWrap = document.getElementById('countMonthWrap');

			// 현재 아동 수보다 작은 수만큼의 기존 요소만 남기고 나머지를 추가
			const currentChildren = countMonthWrap.children.length;

			for (let i = currentChildren + 1; i <= infantCount; i++) {
				const monthDiv = document.createElement('div');
				monthDiv.className = 'count-age';
				monthDiv.innerHTML = `
                <p class="ft-xxs">유아 <span>${i}</span></p>
                    <div class="count-select" data-name="popupMonth" data-infant="${i}">
                        <p>${selectedMonths[i] ? `${selectedMonths[i]}개월` : '개월수 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
				monthDiv.querySelector('.count-select').addEventListener('click', openMonthPopup);
				countMonthWrap.appendChild(monthDiv);
			}

			// 남은 아동 수보다 많은 항목이 있으면 잘라내기
			while (countMonthWrap.children.length > infantCount) {
				countMonthWrap.removeChild(countMonthWrap.lastElementChild);
			}
		}

		// 선택된 나이에서 마지막 아동 나이 제거
		function removeLastChildAge(newCount) {
			const lastChildIndex = newCount + 1;
			delete selectedAges[lastChildIndex]; // 마지막 아동 나이 제거
		}

		function removeLastInfantAge(newCount) {
			const lastInfantIndex = newCount + 1;
			delete selectedMonths[lastInfantIndex];
		}

		// 나이 선택 팝업 열기
		function openAgePopup(event) {
			const childIndex = event.target.closest('.count-select').getAttribute('data-child');
			const popup = document.getElementById('popupAge');
			popup.classList.add('show');
			popup.dataset.childIndex = childIndex;
		}

		function openMonthPopup(event) {
			const childIndex = event.target.closest('.count-select').getAttribute('data-infant');
			const popup = document.getElementById('popupMonth');
			popup.classList.add('show');
			popup.dataset.childIndex = childIndex;
		}

		// 나이 선택 후 값 저장 및 팝업 닫기
		document.getElementById('ageOptions').addEventListener('click', event => {
			event.preventDefault(); // <a> 태그의 기본 동작 막기

			if (event.target.tagName === 'A') {
				const selectedAge = event.target.getAttribute('data-age');
				const childIndex = document.getElementById('popupAge').dataset.childIndex;

				// 선택한 나이를 객체에 저장
				selectedAges[childIndex] = selectedAge;

				// 선택한 나이로 텍스트 업데이트
				const countSelect = document.querySelector(`.count-select[data-child="${childIndex}"] p`);
				countSelect.textContent = `만 ${selectedAge}세`;

				document.getElementById('popupAge').classList.remove('show');
			}
		});

		document.getElementById('monthOptions').addEventListener('click', event => {
			event.preventDefault(); // <a> 태그의 기본 동작 막기

			if (event.target.tagName === 'A') {
				const selectedMonth = event.target.getAttribute('data-month');
				const childIndex = document.getElementById('popupMonth').dataset.childIndex;

				// 선택한 나이를 객체에 저장
				selectedMonths[childIndex] = selectedMonth;

				// 선택한 나이로 텍스트 업데이트
				const countSelect = document.querySelector(`.count-select[data-infant="${childIndex}"] p`);
				countSelect.textContent = `${selectedMonth}개월`;

				document.getElementById('popupMonth').classList.remove('show');
			}
		});

		// 팝업 바깥 클릭 시 닫기
		document.getElementById('popupAge').addEventListener('click', (event) => {
			if (event.target === document.getElementById('popupAge')) {
				document.getElementById('popupAge').classList.remove('show');
			}
		});

		document.getElementById('popupMonth').addEventListener('click', (event) => {
			if (event.target === document.getElementById('popupMonth')) {
				document.getElementById('popupMonth').classList.remove('show');
			}
		});

		document.getElementById('selectDateBtn').addEventListener('click', () => {
			document.getElementById('popupDate').classList.remove('show');

			const queryParams = new URLSearchParams({
				startDate: filterStartDate,
				endDate: filterEndDate,
				adult: adultCount,
				child: childCount,
				infant: infantCount,
				childAge: JSON.stringify(selectedAges),
				infantMonth: JSON.stringify(selectedMonths),
			});

			showLoader();
			window.location.href = `/moongcleoffer/product/<?= $partner->partner_idx; ?>?${queryParams.toString()}`;
		});

		document.getElementById('undecidedBtn').addEventListener('click', () => {
			const selectedDateText = `날짜 선택`;
			document.getElementById('selectedDate').textContent = `${selectedDateText}`;
			document.getElementById('popupDate').classList.remove('show');

			const queryParams = new URLSearchParams({
				startDate: filterStartDate,
				endDate: filterEndDate,
				adult: adultCount,
				child: childCount,
				infant: infantCount,
				childAge: JSON.stringify(selectedAges),
				infantMonth: JSON.stringify(selectedMonths),
			});

			showLoader();
			window.location.href = `/moongcleoffer/product/<?= $partner->partner_idx; ?>?${queryParams.toString()}`;
		});

		// "선택" 버튼 클릭 시 인원 수 업데이트 및 팝업 닫기
		document.getElementById('selectGuestsBtn').addEventListener('click', () => {
			const selectedGuestsText = `성인 ${adultCount}, 아동 ${childCount}, 유아 ${infantCount}`;
			document.getElementById('selectedGuests').textContent = `${selectedGuestsText}`;
			document.getElementById('popupGuest').classList.remove('show');

			const queryParams = new URLSearchParams({
				startDate: filterStartDate,
				endDate: filterEndDate,
				adult: adultCount,
				child: childCount,
				infant: infantCount,
				childAge: JSON.stringify(selectedAges),
				infantMonth: JSON.stringify(selectedMonths),
			});

			showLoader();
			window.location.href = `/moongcleoffer/product/<?= $partner->partner_idx; ?>?${queryParams.toString()}`;
		});

		$('.accordion__tit').click(function() {
			const $button = $(this).find('.btn-full__line__primary');
			if ($(this).hasClass('active')) {
				$button.text('옵션 더보기');
			} else {
				$button.text('옵션 접기');
			}
		});

		$(document).ready(function() {
			if ($('#scrollContainer').length) {
				// #scrollContainer가 존재하는 경우
				$('#scrollContainer').scroll(function() {
					var $prdNameTop = $('.product-detail__tit .product-name').offset().top - $('.header__wrap').height();
					var $prdName = $('#product-name').text();
					var $scrollTop = $('#scrollContainer').scrollTop();

					if ($prdNameTop <= 0) {
						$('.header-product-name').text($prdName);
					} else {
						$('.header-product-name').html(`<h2 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h2>`);
					}
				});
			} else {
				// #scrollContainer가 존재하지 않는 경우
				$(document).scroll(function() {
					var productNameRect = document.querySelector('.product-detail__tit .product-name').getBoundingClientRect();
					var $prdNameTop = productNameRect.top;
					var $prdName = $('#product-name').text();
					var $scrollTop = $(window).scrollTop(); // 스크롤 위치 감지

					if ($prdNameTop <= 0) {
						$('.header-product-name').text($prdName);
					} else {
						$('.header-product-name').html(`<h2 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h2>`);
					}
				});
			}
		});

		document.addEventListener("DOMContentLoaded", function() {
			const banner = document.getElementById("appDownloadTopBanner");
			const closeButton = document.getElementById("appDownloadTopBannerClose");

			// 배너와 닫기 버튼이 존재할 때만 로직 실행
			if (banner && closeButton) {
				// 쿠키가 설정되어 있으면 배너 숨기기
				if (getCookie("bannerClosed") === "true") {
					banner.style.display = "none";
				}

				// 닫기 버튼 클릭 이벤트
				closeButton.addEventListener("click", function() {
					banner.style.display = "none";
					setCookie("bannerClosed", "true", 1); // 쿠키를 1일 동안 설정
				});

				// 쿠키 설정 함수
				function setCookie(name, value, days) {
					const date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
				}

				// 쿠키 가져오는 함수
				function getCookie(name) {
					const cookies = document.cookie.split("; ");
					for (let cookie of cookies) {
						const [cookieName, cookieValue] = cookie.split("=");
						if (cookieName === name) return cookieValue;
					}
					return null;
				}
			}
		});

		document.getElementById("selectRoomButton").addEventListener("click", function() {
			const productList = document.getElementById("mainMoongcleoffer");
			productList.scrollIntoView({
				behavior: "smooth"
			});
		});

		document.getElementById("selectStayAddressButton").addEventListener("click", function(event) {
			event.preventDefault();
			const partnerAddress = document.getElementById("partnerAddress");
			partnerAddress.scrollIntoView({
				behavior: "smooth"
			});
		});

		document.getElementById('addressCopyButton').addEventListener('click', function() {
			const addressText = document.getElementById('partnerAddress').innerText;

			navigator.clipboard.writeText(addressText).then(function() {
				fnToastPop('addressCopyToast');
			}).catch(function(error) {
				console.error('복사에 실패했습니다:', error);
			});
		});

		document.querySelectorAll('.fnOpenPop[data-name="roomDetail"]').forEach(roomElement => {
			roomElement.addEventListener('click', function(event) {
				event.preventDefault();

				const roomIdx = this.getAttribute('data-room-idx');

				fetchRoomData(roomIdx);
			});
		});

		document.querySelectorAll('.fnOpenPop[data-name="roomRateplanDetail"]').forEach(roomElement => {
			roomElement.addEventListener('click', function(event) {
				event.preventDefault();

				const moongcleofferIdx = this.getAttribute('data-moongcleoffer-idx');

				fetchMoongcleofferData(moongcleofferIdx);
			});
		});

		const facilityButton = document.querySelector('.fnOpenPop[data-name="facilityDetail"]');

		if (facilityButton) {
			facilityButton.addEventListener('click', function(event) {
				event.preventDefault();
			});
		}

        const faqDetailButton = document.querySelector('.fnOpenPop[data-name="faqDetail"]');

		if (faqDetailButton) {
			faqDetailButton.addEventListener('click', function(event) {
				event.preventDefault();
			});
		}

		document.querySelectorAll('.openRoomQuantity').forEach(button => {
			button.addEventListener('click', function() {
                const $this = $(this);

                const queryParams = new URLSearchParams({
                    adult: adultCount,
                    child: childCount,
                    infant: infantCount
                });

                if (adultCount === 0 && childCount === 0 && infantCount === 0) {
                    $this.attr('data-name', 'alertPerson');
                } else {
                    $this.attr('data-name', 'popupRoomQuantity');
                }
                
				const roomName = this.getAttribute('data-room-name');
				const rateplanName = this.getAttribute('data-rateplan-name');

				reservationRoomIdx = this.getAttribute('data-room-idx');
				reservationRateplanIdx = this.getAttribute('data-rateplan-idx');
				reservationMoongcleofferIdx = this.getAttribute('data-moongcleoffer-idx');

				document.getElementById('roomRateplanName1').textContent = `${roomName}`;
				document.getElementById('roomRateplanName2').textContent = `${rateplanName}`;
			});
		});

		document.getElementById('selectRoomQuantityBtn').addEventListener('click', function() {
			if (currentIdx) {
				const newPath = `/payment/stay/${reservationPartnerIdx}/room/${reservationRoomIdx}/moongcleoffer/${reservationMoongcleofferIdx}`;

				const currentParams = new URLSearchParams(window.location.search);

				currentParams.set('roomQuantity', roomCount);

				const targetUrl = `${newPath}?${currentParams.toString()}`;

				showLoader();
				window.location.href = targetUrl;
			} else {
				fnOpenLayerPop('loginKakaoPopup');
			}
		});

		function downloadCouponPack() {
			if (currentIdx) {
				showLoader();
				window.location.href = '/coupons';
			} else {
				fnOpenLayerPop('loginKakaoPopupCoupon');
			}
		}

		async function fetchRoomData(roomIdx) {
			try {
				showLoader();

				const response = await fetch(`/api/room/${roomIdx}`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({})
				});

				hideLoader();

				if (!response.ok) {
					throw new Error('데이터를 가져오는 데 실패했습니다.');
				}

				const data = await response.json();

				document.getElementById('roomDetailFullModalContents').innerHTML = data.html;

				var roomModalSlider = document.getElementById('roomModalSlider');
				initializeRoomModalSplide(roomModalSlider);
			} catch (error) {
				console.error('데이터 가져오기 에러:', error);
			}
		}

		async function fetchMoongcleofferData(moongcleofferIdx) {
			try {
				showLoader();

				const response = await fetch(`/api/room-rateplan/${moongcleofferIdx}`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						startDate: '<?= $_GET['startDate']; ?>',
						endDate: '<?= $_GET['endDate']; ?>',
					})
				});

				hideLoader();

				if (!response.ok) {
					throw new Error('데이터를 가져오는 데 실패했습니다.');
				}

				const data = await response.json();

				document.getElementById('moongcleofferDetailFullModalContents').innerHTML = data.html;
			} catch (error) {
				console.error('데이터 가져오기 에러:', error);
			}
		}

		function initializeRoomModalSplide(sliderElement) {
			var splide = new Splide(sliderElement, {
				arrows: false,
				perPage: 1,
				pagination: false
			}).mount();

			var totalSlides = sliderElement.querySelectorAll('.splide__list__product').length;
			sliderElement.querySelector('.total-slides').textContent = totalSlides;

			splide.on('mounted moved', function() {
				var currentSlide = splide.index + 1;
				sliderElement.querySelector('.current-slide').textContent = currentSlide;
			});
		}

        document.addEventListener('DOMContentLoaded', () => {
            const zoomSlider = document.querySelector('.splide__product_full_img');
            
            // Splide 초기화
            const fullImageSplide = new Splide(zoomSlider, {
                arrows: true,
                pagination: false,
                perPage: 1,
                focus: 'center',
                padding: 0,
                gap: 0,
                drag: true,  
            }).mount();

            // 현재 슬라이드 표시 업데이트
            fullImageSplide.on('move', function(newIndex) {
                const currentSlide = newIndex + 1;
                zoomSlider.querySelector('.current-slide').textContent = currentSlide;
            });

            document.querySelectorAll('.zoom-image').forEach((container) => {
                const panzoom = Panzoom(container, {
                    maxScale: 10,
                    minScale: 1,
                    contain: 'outside',
                    pinchZoom: true,
                });

                container.addEventListener('wheel', panzoom.zoomWithWheel);

                let lastScale = 1;
                let isPinching = false;

                const setUIVisibility = (visible) => {
                    const arrows = zoomSlider.querySelectorAll('.splide__arrow');
                    arrows.forEach(arrow => {
                        arrow.style.display = visible ? '' : 'none';
                    });

                    const slideCounter = zoomSlider.querySelector('.slide-counter.zoom');
                    if (slideCounter) {
                        slideCounter.style.display = visible ? '' : 'none';
                    }
                };

                const updateSlideState = (enabled) => {
                    zoomSlider.style.pointerEvents = enabled ? 'auto' : 'none';
                    fullImageSplide.options = {
                        ...fullImageSplide.options,
                        drag: enabled,  
                        swipe: enabled, 
                    };
                    fullImageSplide.refresh();
                    setUIVisibility(enabled);
                };

                // 핀치 시작 감지
                container.addEventListener('touchstart', (e) => {
                    if (e.touches.length > 1) {
                        isPinching = true;
                        updateSlideState(false); 

                        zoomSlider.style.touchAction = 'none';
                        zoomSlider.style.pointerEvents = 'none';
                    }
                }, { passive: false });

                container.addEventListener('touchmove', (e) => {
                    if (isPinching) {
                        zoomSlider.style.touchAction = 'none';
                        zoomSlider.style.pointerEvents = 'none';
                        
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, { passive: false });

                // 핀치 종료 감지
                container.addEventListener('touchend', (e) => {
                    if (e.touches.length < 2) {
                        isPinching = false;

                        zoomSlider.style.touchAction = '';
                        zoomSlider.style.pointerEvents = '';

                        if (lastScale <= 1.1) updateSlideState(true);
                    }
                });

                container.addEventListener('panzoomchange', (e) => {
                    const scale = e.detail.scale;

                    if (scale > 1.1 && lastScale <= 1.1) {
                        updateSlideState(false); 
                    } else if (scale <= 1.1 && lastScale > 1.1 && !isPinching) {
                        updateSlideState(true);  
                    }

                    lastScale = scale;
                });

                fullImageSplide.on('moved', () => {
                    panzoom.zoom(1, { animate: true });
                });
            });

            window.fullImageView = function(imageIndex) {
                fnOpenLayerPop('allPicture');
                if (fullImageSplide) {
                    fullImageSplide.go(imageIndex);
                }
            };
        });

        const hash = location.hash.replace('#', '') || 0;
        const likeButton = document.querySelector('.btn-product__like');

        if (likeButton) {
            likeButton.setAttribute('data-moongcleoffer-idx', hash);
        }

		document.addEventListener('click', function(event) {
			const target = event.target.closest('.btn-product__like');
			if (target) {
				const userIdx = target.dataset.userIdx;
				const partnerIdx = target.dataset.partnerIdx;
				const moongcleofferIdx = target.dataset.moongcleofferIdx;

                

				toggleFavorite(userIdx, partnerIdx, moongcleofferIdx, 'moongcleoffer');
			}
		});
	</script>

	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=a09a9506a8284c662059e618d6ec7b42&libraries=services"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// 지도를 표시할 div 요소를 참조
			var container = document.getElementById('map');
			var lat = parseFloat(document.getElementById('latitude').value); // PHP에서 가져온 위도 값을 파싱
			var lng = parseFloat(document.getElementById('longitude').value); // PHP에서 가져온 경도 값을 파싱

			// 지도에 대한 옵션 설정
			var options = {
				center: new kakao.maps.LatLng(lat, lng), // 파싱한 위도와 경도로 위치 설정
				level: 2
			};

			// 지도 생성
			var map = new kakao.maps.Map(container, options);

			// 마커를 생성하고 위치를 설정
			var markerPosition = new kakao.maps.LatLng(lat, lng); // 파싱한 위도와 경도로 마커 위치 설정
			var marker = new kakao.maps.Marker({
				position: markerPosition
			});

			// 마커를 지도에 추가
			marker.setMap(map);
		});
	</script>

    <script>
        // 부대 시설 팝업
        document.querySelectorAll('.fnOpenPop[data-name="facilityInfo"]').forEach(item => {
            item.addEventListener('click', function () {
                const image = this.getAttribute('data-image') || '';
                const title = this.getAttribute('data-title') || '';
                const sub = this.getAttribute('data-sub') || '';
                const info = this.getAttribute('data-info') || '';

                const popup = document.getElementById('facilityInfo');
                popup.querySelector('.img_box img').src = image;
                popup.querySelector('.facility_title').textContent = title;
                popup.querySelector('.sub').textContent = sub;
                popup.querySelector('.info').innerHTML = info;

                popup.classList.add('on');
            });
        });
    </script>

	<?php if ($_ENV['ANALYTICS_ENV'] == 'production' || $_ENV['ANALYTICS_ENV'] == 'staging') : ?>
		<script>
			document.addEventListener("DOMContentLoaded", () => {
				window.dataLayer.push({
					event: "view_item",
					page_type: "deal"
				});
			});
		</script>
	<?php endif; ?>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

    <script>
        // 리뷰 3줄 체크
        function checkReviewLineCount() {
            document.querySelectorAll('.review-txt').forEach(function(el) {
                const review = el.querySelector('.review');
                const btnMore = el.querySelector('.btn-more');

                if (!review || !btnMore) return;

                const computedStyle = window.getComputedStyle(review);
                const lineHeight = parseFloat(computedStyle.lineHeight);
                const totalHeight = review.scrollHeight;
                const lineCount = Math.round(totalHeight / lineHeight);

                if (lineCount <= 3) {
                    btnMore.style.display = 'none';
                }
            });
        }
    </script>

    <!-- NAVER 상품상세(view_product) SCRIPT -->
    <script type='text/javascript'>
    if(window.wcs){
    if(!wcs_add) var wcs_add = {};
    wcs_add["wa"] = "s_2744685fd307"; 
    var _conv = {};
        _conv.type = 'view_product'; 
        _conv.items = [{
            id: <?= $partner->partner_idx; ?>,
            name: "<?= $partner->partner_name; ?>",
            category: "<?= $partner->partner_category; ?>",
        }];
    wcs.trans(_conv);
    }
    </script>
    <!-- NAVER 상품상세(view_product) SCRIPT END -->

</body>

</html>