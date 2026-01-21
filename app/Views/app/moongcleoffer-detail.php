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
$moongcleoffer = $data['moongcleoffer'];
$moongcleofferInfo = $data['moongcleofferInfo'];
$otherMoongcleoffers = $data['otherMoongcleoffers'];
$moongcleStayTags = $data['moongcleStayTags'];
$moongcleRoomTags = $data['moongcleRoomTags'];
$isGuest = $data['isGuest'];
$moongcleofferFavorites = $data['moongcleofferFavorites'];
$openCalendar = $data['openCalendar'];
$reviews = $data['reviews'];
$reviewRating = $data['reviewRating'];
$reviewCount = $data['reviewCount'];
$thirdparty = $partner->partner_thirdparty;

$stayImages = explode(':-:', $partner->image_paths);

if ($partner->image_curated) {
	$stayImages = explode(':-:', $partner->curated_image_paths);
}

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<body>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
	}
	?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>



	<div id="mobileWrap" style="padding-bottom: 10rem;">
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
				<p class="header-product-name"></p>
				<div class="btn__wrap">
					<!-- <button type="button" class="btn-cart" onclick="gotoTravelCart()"><span class="blind">장바구니</span></button> -->
					<button type="button" class="btn-home" onclick="gotoMain()"><span class="blind">홈</span></button>
					<button class="btn-share" onclick="sendShareLink('뭉클트립에서 <?= $partner->partner_name; ?>의 뭉클 정보를 공유했어요. 자세한 내용은 아래 링크를 확인해보세요.', '<?= $_ENV['APP_HTTP'] . $stayImages[0]; ?>')"><span class="blind">공유하기</span></button>
				</div>
			</div>
		</header>

		<div class="container__wrap" style="padding-bottom:0px;">

			<div class="product-detail__wrap">
				<!-- 상품 이미지 슬라이드 -->
				<div class="product-detail__img" onclick="fnOpenLayerPop('allPictures')">
					<div class="splide splide__product" style="height: 40rem;">
						<div class="splide__track">
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
					</div>
				</div>
				<!-- //상품 이미지 슬라이드 -->
				<!-- 상품 타이틀 -->
				<div class="product-detail__tit">
					<button type="button" class="btn-product__like type-black <?= in_array($moongcleofferInfo->moongcleoffer_idx, $moongcleofferFavorites) ? 'active' : '' ?>" data-user-idx="<?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>" data-partner-idx="<?= !empty($moongcleofferInfo->partner_idx) ? $moongcleofferInfo->partner_idx : 0 ?>" data-moongcleoffer-idx="<?= !empty($moongcleofferInfo->moongcleoffer_idx) ? $moongcleofferInfo->moongcleoffer_idx : 0 ?>"><span class="blind">찜하기</span></button>
					<!-- <a href="#" class="seller-name fnOpenPop" data-name="sellerPopup">판매자</a> -->
					<div class="product-tit">
						<p id="product-name" class="product-name"><?= $partner->partner_name; ?></p>
						<p class="product-sub">
							<?php
							$stayRating = '';
							$stayTags = explode(':-:', $partner->tags);

							if (!empty($stayTags)) {
								foreach ($stayTags as $stayTag) {
									if ($stayTag === '1성' || $stayTag === '2성' || $stayTag === '3성' || $stayTag === '4성' || $stayTag === '5성') {
										$stayRating = $stayTag;
									}
								}
							}
							?>
							<?php $stayTypes = explode(':-:', $partner->types); ?>
							<?php if (!empty($stayTypes)) : ?>
								<span>
									<?php foreach ($stayTypes as $tagKey => $stayType) : ?>
										<?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
									<?php endforeach; ?>
								</span>
							<?php endif; ?>
							<?php if (!empty($stayRating)) : ?>
								<span><?= $stayRating; ?></span>
							<?php endif; ?>
							<a id="selectStayAddressButton" href="" class="place"><?= $partner->partner_address1; ?></a>
						</p>
					</div>
					<div class="flex-between">
						<div class="product-category">
							<p class="badge badge__lavender">나만의 뭉클딜</p>
							<p class="ft-xxs">숙박</p>
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

				<!-- 뭉클한 혜택 -->
				<div id="mainMoongcleoffer" class="product-detail__benefit">
					<div class="box-white__list">
						<!-- 검색 폼 -->
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
						<!-- //검색 폼 -->

						<?php
						$curatedTags = json_decode($moongcleofferInfo->curated_tags);
						$room_benefits = json_decode($moongcleofferInfo->room_benefits);
						$rateplan_benefits = json_decode($moongcleofferInfo->rateplan_benefits);
						$moongcleoffer_benefits = json_decode($moongcleofferInfo->moongcleoffer_benefits);
						?>
						<div class="box-white__wrap" style="margin-top: 1.2rem;">
							<p class="title">연관 뭉클태그</p>
							<div class="tag-list__wrap type-img">
								<ul>
									<?php foreach ($curatedTags as $key => $curatedTag) : ?>
										<?php if ($key > 3) continue; ?>
										<li>
											<span>
												<img src="/uploads/tags/<?= $curatedTag->tag_machine_name; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
												<span><?= $curatedTag->tag_name; ?></span>
											</span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>

						<div class="box-white__wrap flex-between" style="margin-top: 1.2rem;">
							<p class="ft-default ft-bold"><span class="txt-primary">70,000원</span> 선착순 쿠폰팩 받기</p>
							<button type="button" class="btn-sm__primary btn-sm__round" onclick="downloadCouponPack()">
								<i class="ico ico-download"></i>
								다운로드
							</button>
						</div>

						<!-- <div class="box-white__wrap">
							<p class="title">이 뭉클딜의 혜택</p>
							<div class="thumb__wrap">
								<div class="thumb__gift">
									<ul>
										<?php if (!empty($room_benefits)) : ?>
											<?php foreach ($room_benefits as $benefit) : ?>
												<li><?= $benefit->benefit_name; ?></li>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php if (!empty($rateplan_benefits)) : ?>
											<?php foreach ($rateplan_benefits as $benefit) : ?>
												<li><?= $benefit->benefit_name; ?></li>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php if (!empty($moongcleoffer_benefits)) : ?>
											<?php foreach ($moongcleoffer_benefits as $benefit) : ?>
												<li><?= $benefit->benefit_name; ?></li>
											<?php endforeach; ?>
										<?php endif; ?>
									</ul>
								</div>
							</div>
						</div> -->

						<!-- <div class="room-list__wrap">
							<div class="room-list__con">
								<div class="splide splide__product">
									<div class="splide__track">
										<ul class="splide__list">
											<?php $roomImages = json_decode($moongcleofferInfo->room_images); ?>
											<?php if (!empty($roomImages[0])) : ?>
												<?php foreach ($roomImages as $roomImage) : ?>
													<li class="splide__slide splide__list__product"><img src="<?= $roomImage->image_path; ?>" alt=""></li>
												<?php endforeach; ?>
											<?php else : ?>
												<li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
											<?php endif; ?>
										</ul>
									</div>
									<div class="slide-counter">
										<span class="current-slide">1</span> / <span class="total-slides"></span>
									</div>
								</div>
								<div class="room-name-block">
									<p class="room-name fnOpenPop" data-name="roomDetail" data-room-idx="<?= $moongcleofferInfo->room_idx; ?>"><?= $moongcleofferInfo->room_name; ?></p>
									<a href="" style="white-space: nowrap;" class="btn-txt__arrow fnOpenPop" data-name="roomDetail" data-room-idx="<?= $moongcleofferInfo->room_idx; ?>">객실상세</a>
								</div>

								<?php if (!empty($moongcleoffer->moongcleoffer_idx)) : ?>
									<?php
									$possibleSale = true;

									if (!empty($moongcleoffer->rateplan_sales_from) && !empty($moongcleoffer->rateplan_sales_to)) {
										if (strtotime($moongcleoffer->rateplan_sales_from) > strtotime('now') || strtotime($moongcleoffer->rateplan_sales_to) < strtotime('now')) {
											$possibleSale = false;
										}
									}
									?>
									<?php if ($possibleSale === false) : ?>
										<p class="room-num">남은 객실 <span>0개</span></p>
									<?php else : ?>
										<?php if ($moongcleoffer->min_inventory_quantity < 5) : ?>
											<p class="room-num">남은 객실 <span><?= $moongcleoffer->min_inventory_quantity; ?>개</span></p>
										<?php endif; ?>
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
										<?php if (array_sum($bedTypes) === 0) : ?>
										<?php else : ?>
											<li class="option-bed">
												<div>
													<?php
													foreach ($bedTypes as $bedType => $bedCount) {
														if ($bedCount == 0) continue;

														if ($bedType === 'single_beds') {
															echo '<div>싱글베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'super_single_beds') {
															echo '<div>슈퍼싱글베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'double_beds') {
															echo '<div>더블베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'queen_beds') {
															echo '<div>퀸베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'king_beds') {
															echo '<div>킹베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'sofa_beds') {
															echo '<div>소파베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'air_beds') {
															echo '<div>에어베드 ' . $bedCount . '개</div>';
														}
													}
													?>
												</div>
											</li>
										<?php endif; ?>
										<?php if (!empty($moongcleoffer->room_size)) : ?>
											<li class="option-area">객실크기 <?= $moongcleoffer->room_size; ?>&#13217;</li>
										<?php endif; ?>
										<li class="option-people">기준 <?= $moongcleoffer->room_standard_person; ?>명 / 최대 <?= $moongcleoffer->room_max_person; ?>명</li>
									</ul>

									<?php if ($possibleSale) : ?>
										<div class="accordion__wrap">
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
														<div>
															<p class="room-type-name"><?= $moongcleoffer->rateplan_name; ?></p>
															<div class="thumb__wrap" style="margin-top: 2rem;">
																<div class="thumb__gift">
																	<ul>
																		<?php if (!empty($room_benefits)) : ?>
																			<?php foreach ($room_benefits as $benefit) : ?>
																				<li><?= $benefit->benefit_name; ?></li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																		<?php if (!empty($rateplan_benefits)) : ?>
																			<?php foreach ($rateplan_benefits as $benefit) : ?>
																				<li><?= $benefit->benefit_name; ?></li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																		<?php if (!empty($moongcleoffer_benefits)) : ?>
																			<?php foreach ($moongcleoffer_benefits as $benefit) : ?>
																				<li><?= $benefit->benefit_name; ?></li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="room-price" style="display: flex; flex-direction: column; align-items: flex-end;">
															<?php
															$basicPrice = $moongcleoffer->total_basic_price;
															$salePrice = $moongcleoffer->total_sale_price;
															?>
															<a href="" class="btn-txt__arrow fnOpenPop" data-name="roomRateplanDetail" data-moongcleoffer-idx="<?= $moongcleoffer->moongcleoffer_idx; ?>" style="margin-bottom: 1rem;">상세보기</a>
															<span>
																<?php if ($basicPrice != $salePrice) : ?>
																	<span class="sale-percent">-<?= number_format((($basicPrice - $salePrice) / $basicPrice) * 100, 1); ?>%</span>
																	<span class="default-price"><?= number_format($basicPrice); ?>원</span>
																<?php endif; ?>
															</span>
															<span class="sale-price"><em><?= number_format($salePrice); ?></em>원 (<?= $intervalDays; ?>박)</span>
														</div>
													</div>
													<div class="btn__wrap">
														<?php if ($moongcleoffer->rateplan_stay_min <= $intervalDays && ($moongcleoffer->rateplan_stay_max == 0 || $moongcleoffer->rateplan_stay_max >= $intervalDays)) : ?>
															<button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity" data-name="popupRoomQuantity" data-room-name="<?= $moongcleoffer->room_name; ?>" data-rateplan-name="<?= $moongcleoffer->rateplan_name; ?>" data-room-idx="<?= $moongcleoffer->room_idx ?>" data-rateplan-idx="<?= $moongcleoffer->rateplan_idx ?>" data-moongcleoffer-idx="<?= $moongcleoffer->moongcleoffer_idx ?>">예약하기</button>
														<?php else : ?>
															<button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity disabled" data-name="popupRoomQuantity" data-room-name="<?= $moongcleoffer->room_name; ?>" data-rateplan-name="<?= $moongcleoffer->rateplan_name; ?>" data-room-idx="<?= $moongcleoffer->room_idx ?>" data-rateplan-idx="<?= $moongcleoffer->rateplan_idx ?>" data-moongcleoffer-idx="<?= $moongcleoffer->moongcleoffer_idx ?>" disabled>예약하기</button>
														<?php endif; ?>
													</div>
												</div>

											</div>
										</div>
									<?php endif; ?>
								<?php else : ?>
									<p class="room-num">남은 객실 <span>0개</span></p>
									<ul class="room-option">
										<?php if (empty($moongcleofferInfo->views)) : ?>
										<?php else : ?>
											<?php $roomViews = explode(':-:', $moongcleofferInfo->views); ?>
											<li class="option-view">
												<div>
													<?php foreach ($roomViews as $roomView) : ?>
														<div><?= $roomView; ?></div>
													<?php endforeach; ?>
												</div>
											</li>
										<?php endif; ?>
										<?php $bedTypes = json_decode($moongcleofferInfo->room_bed_type, true); ?>
										<?php if (array_sum($bedTypes) === 0) : ?>
										<?php else : ?>
											<li class="option-bed">
												<div>
													<?php
													foreach ($bedTypes as $bedType => $bedCount) {
														if ($bedCount == 0) continue;

														if ($bedType === 'single_beds') {
															echo '<div>싱글베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'super_single_beds') {
															echo '<div>슈퍼싱글베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'double_beds') {
															echo '<div>더블베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'queen_beds') {
															echo '<div>퀸베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'king_beds') {
															echo '<div>킹베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'sofa_beds') {
															echo '<div>소파베드 ' . $bedCount . '개</div>';
														}
														if ($bedType === 'air_beds') {
															echo '<div>에어베드 ' . $bedCount . '개</div>';
														}
													}
													?>
												</div>
											</li>
										<?php endif; ?>
										<?php if (!empty($moongcleofferInfo->room_size)) : ?>
											<li class="option-area">객실크기 <?= $moongcleofferInfo->room_size; ?>&#13217;</li>
										<?php endif; ?>
										<li class="option-people">기준 <?= $moongcleofferInfo->room_standard_person; ?>명 / 최대 <?= $moongcleofferInfo->room_max_person; ?>명</li>
									</ul>
								<?php endif; ?>
							</div>
						</div> -->

					</div>
					<!-- <p class="txt-warning mt16">뭉클딜은 상황에 따라 조기종료 될 수 있습니다.</p> -->
				</div>
				<!-- //뭉클한 혜택 -->

				<!-- 상품 셀렉트 -->

				<div id="productList" class="product-detail__con" style="padding-top: 1rem;">
					<?php if (!empty($otherMoongcleoffers)) : ?>
						<!-- <div class="tit__wrap">
							<p class="ft-default">또 다른 뭉클딜도 확인해보세요.</p>
						</div> -->

						<!-- 룸 리스트 -->
						<div class="room-list__wrap">

							<?php foreach ($otherMoongcleoffers as $otherMoongcleoffer) : ?>
								<?php if (!empty($otherMoongcleoffer['moongcleoffers'][0])) : ?>
									<div class="room-list__con">
										<div class="splide splide__product">
											<div class="splide__track">
												<ul class="splide__list">
													<?php $roomImages = json_decode($otherMoongcleoffer['room_images']); ?>
													<?php if (!empty($roomImages[0])) : ?>
														<?php foreach ($roomImages as $roomImage) : ?>
															<li class="splide__slide splide__list__product"><img src="<?= $roomImage->image_path; ?>" alt=""></li>
														<?php endforeach; ?>
													<?php else : ?>
														<li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
													<?php endif; ?>
												</ul>
											</div>
											<div class="slide-counter">
												<span class="current-slide">1</span> / <span class="total-slides"></span>
											</div>
										</div>
										<div class="room-name-block">
											<p class="room-name fnOpenPop" data-name="roomDetail" data-room-idx="<?= $otherMoongcleoffer['room_idx']; ?>"><?= $otherMoongcleoffer['room_name']; ?></p>
											<a href="" style="white-space: nowrap;" class="btn-txt__arrow fnOpenPop" data-name="roomDetail" data-room-idx="<?= $otherMoongcleoffer['room_idx']; ?>">객실상세</a>
										</div>
										<?php if ($otherMoongcleoffer['min_inventory_quantity'] < 5) : ?>
											<p class="room-num">남은 객실 <span><?= $otherMoongcleoffer['min_inventory_quantity']; ?>개</span></p>
										<?php endif; ?>
										<ul class="room-option">
											<?php if (empty($otherMoongcleoffer['views'])) : ?>
												<!-- <li class="option-view">
													<div>전망 없음</div>
												</li> -->
											<?php else : ?>
												<?php $roomViews = explode(':-:', $otherMoongcleoffer['views']); ?>
												<li class="option-view">
													<div>
														<?php foreach ($roomViews as $roomView) : ?>
															<div><?= $roomView; ?></div>
														<?php endforeach; ?>
													</div>
												</li>
											<?php endif; ?>
											<?php $bedTypes = json_decode($otherMoongcleoffer['room_bed_type'], true); ?>
											<?php if (array_sum($bedTypes) === 0) : ?>
												<!-- <li class="option-bed">
													<div>침대 없음</div>
												</li> -->
											<?php else : ?>
												<li class="option-bed">
													<div>
														<?php
														foreach ($bedTypes as $bedType => $bedCount) {
															if ($bedCount == 0) continue;

															if ($bedType === 'single_beds') {
																echo '<div>싱글베드 ' . $bedCount . '개</div>';
															}
															if ($bedType === 'super_single_beds') {
																echo '<div>슈퍼싱글베드 ' . $bedCount . '개</div>';
															}
															if ($bedType === 'double_beds') {
																echo '<div>더블베드 ' . $bedCount . '개</div>';
															}
															if ($bedType === 'queen_beds') {
																echo '<div>퀸베드 ' . $bedCount . '개</div>';
															}
															if ($bedType === 'king_beds') {
																echo '<div>킹베드 ' . $bedCount . '개</div>';
															}
															if ($bedType === 'sofa_beds') {
																echo '<div>소파베드 ' . $bedCount . '개</div>';
															}
															if ($bedType === 'air_beds') {
																echo '<div>에어베드 ' . $bedCount . '개</div>';
															}
														}
														?>
													</div>
												</li>
											<?php endif; ?>
											<?php if (!empty($otherMoongcleoffer['room_size'])) : ?>
												<li class="option-area">객실크기 <?= $otherMoongcleoffer['room_size']; ?>&#13217;</li>
											<?php endif; ?>
											<li class="option-people">기준 <?= $otherMoongcleoffer['room_standard_person']; ?>명 / 최대 <?= $otherMoongcleoffer['room_max_person']; ?>명</li>
										</ul>
										<!-- Rateplan 옵션 더보기 아코디언 -->
										<div class="accordion__wrap accordion__wrap_custom">
											<div class="room-type__wrap">
												<!-- 디폴트로 보여지는 Rateplan -->
												<div class="room-type__con">
													<div class="flex-between">
														<?php
														if (
															$otherMoongcleoffer['moongcleoffers'][0]['rateplan_name'] == '[Room only]'
															|| $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name'] == '[회원특가] Room only'
															|| $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name'] == 'room only'
															|| $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name'] == 'standalone'
															|| $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name'] == '룸온리'
														) {
															$otherMoongcleoffer['moongcleoffers'][0]['rateplan_name'] = $intervalDays . '박 요금';
														}
														?>
														<div>
															<p class="room-type-name"><?= $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name']; ?></p>
															<div class="thumb__wrap" style="margin-top: 2rem;">
																<div class="thumb__gift">
																	<ul>
																		<?php
																		$room_benefits = json_decode($otherMoongcleoffer['moongcleoffers'][0]['room_benefits']);
																		$rateplan_benefits = json_decode($otherMoongcleoffer['moongcleoffers'][0]['rateplan_benefits']);
																		$moongcleoffer_benefits = json_decode($otherMoongcleoffer['moongcleoffers'][0]['moongcleoffer_benefits']);
																		?>
																		<?php if (!empty($room_benefits)) : ?>
																			<?php foreach ($room_benefits as $benefit) : ?>
																				<li><?= $benefit->benefit_name; ?></li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																		<?php if (!empty($rateplan_benefits)) : ?>
																			<?php foreach ($rateplan_benefits as $benefit) : ?>
																				<li><?= $benefit->benefit_name; ?></li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																		<?php if (!empty($moongcleoffer_benefits)) : ?>
																			<?php foreach ($moongcleoffer_benefits as $benefit) : ?>
																				<li><?= $benefit->benefit_name; ?></li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="room-price" style="display: flex; flex-direction: column; align-items: flex-end;">
															<?php
															$basicPrice = $otherMoongcleoffer['moongcleoffers'][0]['total_basic_price'];
															$salePrice = $otherMoongcleoffer['moongcleoffers'][0]['total_sale_price'];
															?>
															<a href="" class="btn-txt__arrow fnOpenPop" data-name="roomRateplanDetail" data-moongcleoffer-idx="<?= $otherMoongcleoffer['moongcleoffers'][0]['moongcleoffer_idx']; ?>" style="margin-bottom: 1rem;">상세보기</a>
															<span>
																<?php if ($basicPrice != $salePrice) : ?>
																	<span class="sale-percent">-<?= number_format((($basicPrice - $salePrice) / $basicPrice) * 100, 1); ?>%</span>
																	<span class="default-price"><?= number_format($basicPrice); ?>원</span>
																<?php endif; ?>
															</span>
															<span class="sale-price"><em><?= number_format($salePrice); ?></em>원 (<?= $intervalDays; ?>박)</span>
														</div>
													</div>
													<div class="btn__wrap">
														<!-- <button type="button" class="btn-sm__line__gray"><i class="ico ico-cart"></i></button> -->
														<?php if ($otherMoongcleoffer['moongcleoffers'][0]['rateplan_stay_min'] <= $intervalDays && ($otherMoongcleoffer['moongcleoffers'][0]['rateplan_stay_max'] == 0 || $otherMoongcleoffer['moongcleoffers'][0]['rateplan_stay_max'] >= $intervalDays)) : ?>
															<button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity" data-name="popupRoomQuantity" data-room-name="<?= $otherMoongcleoffer['room_name']; ?>" data-rateplan-name="<?= $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name']; ?>" data-room-idx="<?= $otherMoongcleoffer['room_idx'] ?>" data-rateplan-idx="<?= $otherMoongcleoffer['moongcleoffers'][0]['rateplan_idx'] ?>" data-moongcleoffer-idx="<?= $otherMoongcleoffer['moongcleoffers'][0]['moongcleoffer_idx'] ?>">예약하기</button>
														<?php else : ?>
															<button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity disabled" data-name="popupRoomQuantity" data-room-name="<?= $otherMoongcleoffer['room_name']; ?>" data-rateplan-name="<?= $otherMoongcleoffer['moongcleoffers'][0]['rateplan_name']; ?>" data-room-idx="<?= $otherMoongcleoffer['room_idx'] ?>" data-rateplan-idx="<?= $otherMoongcleoffer['moongcleoffers'][0]['rateplan_idx'] ?>" data-moongcleoffer-idx="<?= $otherMoongcleoffer['moongcleoffers'][0]['moongcleoffer_idx'] ?>" disabled>예약하기</button>
														<?php endif; ?>
													</div>
												</div>
												<!-- //디폴트로 보여지는 Rateplan -->

												<?php if (count($otherMoongcleoffer['moongcleoffers']) > 1) : ?>
													<!-- 숨겨진 Rateplan -->
													<?php for ($i = 1; !empty($otherMoongcleoffer['moongcleoffers'][$i]); $i++) : ?>
														<div class="accordion__con_custom">
															<div class="room-type__con">
																<div class="flex-between">
																	<?php
																	if (
																		$otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] == '[Room only]'
																		|| $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] == '[회원특가] Room only'
																		|| $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] == 'room only'
																		|| $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] == 'standalone'
																		|| $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] == '룸온리'
																	) {
																		$otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] = $intervalDays . '박 요금';
																	}
																	?>
																	<div>
																		<p class="room-type-name"><?= $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name']; ?></p>
																		<div class="thumb__wrap" style="margin-top: 2rem;">
																			<div class="thumb__gift">
																				<ul>
																					<?php
																					$room_benefits = json_decode($otherMoongcleoffer['moongcleoffers'][$i]['room_benefits']);
																					$rateplan_benefits = json_decode($otherMoongcleoffer['moongcleoffers'][$i]['rateplan_benefits']);
																					$moongcleoffer_benefits = json_decode($otherMoongcleoffer['moongcleoffers'][$i]['moongcleoffer_benefits']);
																					?>
																					<?php if (!empty($room_benefits)) : ?>
																						<?php foreach ($room_benefits as $benefit) : ?>
																							<li><?= $benefit->benefit_name; ?></li>
																						<?php endforeach; ?>
																					<?php endif; ?>
																					<?php if (!empty($rateplan_benefits)) : ?>
																						<?php foreach ($rateplan_benefits as $benefit) : ?>
																							<li><?= $benefit->benefit_name; ?></li>
																						<?php endforeach; ?>
																					<?php endif; ?>
																					<?php if (!empty($moongcleoffer_benefits)) : ?>
																						<?php foreach ($moongcleoffer_benefits as $benefit) : ?>
																							<li><?= $benefit->benefit_name; ?></li>
																						<?php endforeach; ?>
																					<?php endif; ?>
																				</ul>
																			</div>
																		</div>
																	</div>
																	<div class="room-price" style="display: flex; flex-direction: column; align-items: flex-end;">
																		<?php
																		$basicPrice = $otherMoongcleoffer['moongcleoffers'][$i]['total_basic_price'];
																		$salePrice = $otherMoongcleoffer['moongcleoffers'][$i]['total_sale_price'];
																		?>
																		<a href="" class="btn-txt__arrow fnOpenPop" data-name="roomRateplanDetail" data-moongcleoffer-idx="<?= $otherMoongcleoffer['moongcleoffers'][$i]['moongcleoffer_idx']; ?>" style="margin-bottom: 1rem;">상세보기</a>
																		<span>
																			<?php
																			$promotionType = '';
																			if (!empty($otherMoongcleoffer['moongcleoffers'][$i]['room_price_promotion_type'])) {
																				if ($otherMoongcleoffer['moongcleoffers'][$i]['room_price_promotion_type'] == 'earlybird') {
																					$promotionType = '얼리버드 할인';
																				} else if ($otherMoongcleoffer['moongcleoffers'][$i]['room_price_promotion_type'] == 'lastminute') {
																					$promotionType = '마감임박 할인';
																				} else {
																					$promotionType = $otherMoongcleoffer['moongcleoffers'][$i]['room_price_promotion_type'];
																				}
																			}
																			?>
																			<?php if (!empty($promotionType)) : ?>
																				<span>[<?= $promotionType; ?>]</span>
																			<?php endif; ?>
																			<span class="sale-percent">-<?= number_format((($basicPrice - $salePrice) / $basicPrice) * 100, 1); ?>%</span>
																			<span class="default-price"><?= number_format($basicPrice); ?>원</span>
																		</span>
																		<span class="sale-price"><em><?= number_format($salePrice); ?></em>원 (<?= $intervalDays; ?>박)</span>
																	</div>
																</div>
																<div class="btn__wrap">
																	<!-- <button type="button" class="btn-sm__line__gray"><i class="ico ico-cart"></i></button> -->
																	<?php if ($otherMoongcleoffer['moongcleoffers'][$i]['rateplan_stay_min'] <= $intervalDays && ($otherMoongcleoffer['moongcleoffers'][$i]['rateplan_stay_max'] == 0 || $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_stay_max'] >= $intervalDays)) : ?>
																		<button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity" data-name="popupRoomQuantity" data-room-name="<?= $otherMoongcleoffer['room_name'] ?>" data-rateplan-name="<?= $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] ?>" data-room-idx="<?= $otherMoongcleoffer['room_idx'] ?>" data-rateplan-idx="<?= $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_idx'] ?>" data-moongcleoffer-idx="<?= $otherMoongcleoffer['moongcleoffers'][$i]['moongcleoffer_idx'] ?>">예약하기</button>
																	<?php else : ?>
																		<button type="button" class="btn-sm__primary fnOpenPop openRoomQuantity disabled" data-name="popupRoomQuantity" data-room-name="<?= $otherMoongcleoffer['room_name'] ?>" data-rateplan-name="<?= $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_name'] ?>" data-room-idx="<?= $otherMoongcleoffer['room_idx'] ?>" data-rateplan-idx="<?= $otherMoongcleoffer['moongcleoffers'][$i]['rateplan_idx'] ?>" data-moongcleoffer-idx="<?= $otherMoongcleoffer['moongcleoffers'][$i]['moongcleoffer_idx'] ?>" disabled>예약하기</button>
																	<?php endif; ?>
																</div>
															</div>
														</div>
													<?php endfor; ?>
													<!-- //숨겨진 Rateplan -->

													<!-- 옵션 더보기 -->
													<div class="accordion__tit active">
														<button type="button" class="btn-full__line__primary">옵션 접기</button>
													</div>
													<!-- //옵션 더보기 -->
												<?php endif; ?>

											</div>
										</div>
										<!-- //룸 타입 옵션 더보기 아코디언 -->
									</div>
								<?php endif; ?>
							<?php endforeach; ?>

						</div>
						<!-- //룸 리스트 -->
						<!-- <div class="btn__wrap btn-product__more">
						<button type="button" class="btn-full__black"><span>n</span>개 객실 더보기</button>
					</div> -->
					<?php endif; ?>
				</div>
				<!-- //상품 셀렉트 -->


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
													<div class="splide__track">
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
												</div>
												<div class="room-name-block" style="gap: 6rem;">
													<p class="room-name fnOpenPop" data-name="roomDetail" data-room-idx="<?= $closedRoom->room_idx; ?>"><?= $closedRoom->room_name; ?></p>
													<a href="" style="white-space: nowrap;" class="btn-txt__arrow fnOpenPop" data-name="roomDetail" data-room-idx="<?= $closedRoom->room_idx; ?>">객실상세</a>
												</div>
												<p class="room-num">남은 객실 <span>0개</span></p>
												<ul class="room-option">
													<?php if (empty($closedRoom->views)) : ?>
														<!-- <li class="option-view">
															<div>전망 없음</div>
														</li> -->
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
													<?php if (array_sum($bedTypes) === 0) : ?>
														<!-- <li class="option-bed">
															<div>침대 없음</div>
														</li> -->
													<?php else : ?>
														<li class="option-bed">
															<div>
																<?php
																foreach ($bedTypes as $bedType => $bedCount) {
																	if ($bedCount == 0) continue;

																	if ($bedType === 'single_beds') {
																		echo '<div>싱글베드 ' . $bedCount . '개</div>';
																	}
																	if ($bedType === 'super_single_beds') {
																		echo '<div>슈퍼싱글베드 ' . $bedCount . '개</div>';
																	}
																	if ($bedType === 'double_beds') {
																		echo '<div>더블베드 ' . $bedCount . '개</div>';
																	}
																	if ($bedType === 'queen_beds') {
																		echo '<div>퀸베드 ' . $bedCount . '개</div>';
																	}
																	if ($bedType === 'king_beds') {
																		echo '<div>킹베드 ' . $bedCount . '개</div>';
																	}
																	if ($bedType === 'sofa_beds') {
																		echo '<div>소파베드 ' . $bedCount . '개</div>';
																	}
																	if ($bedType === 'air_beds') {
																		echo '<div>에어베드 ' . $bedCount . '개</div>';
																	}
																}
																?>
															</div>
														</li>
													<?php endif; ?>
													<?php if (!empty($closedRoom->room_size)) : ?>
														<li class="option-area">객실크기 <?= $closedRoom->room_size; ?>&#13217;</li>
													<?php endif; ?>
													<li class="option-people">기준 <?= $closedRoom->room_standard_person; ?>명 / 최대 <?= $closedRoom->room_max_person; ?>명</li>
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

				<!-- 숙소 공지사항 및 정보 -->
				<div class="product-detail__notice" id="product-detail">

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

					<?php if (!empty($cancelRules->count() > 0)) : ?>
						<div class="bullet__wrap">
							<p class="title">취소 및 환불 규정</p>
							<div class="stay-detail-info" style="white-space: unset; margin-left: 2rem;">
								<ul style="list-style: disc;">
                                        <?php 
                                            $showAfterCancel = false;
                                        foreach ($cancelRules as $key => $cancelRule) : ?>

										<?php if ($cancelRule->cancel_rules_percent != 0) : ?>
											<?php if (!empty($cancelRules[$key + 1]->cancel_rules_percent) && $cancelRules[$key + 1]->cancel_rules_percent == 100) continue; ?>
											<li>체크인 <?= $cancelRule->cancel_rules_day; ?>일 전 <?= !empty($cancelRule->cancel_rules_time) ? $cancelRule->cancel_rules_time : '23:50'; ?>까지 취소 시 <?= $cancelRule->cancel_rules_percent; ?>% 환불</li>
										<?php elseif ($cancelRule->cancel_rules_percent == 0 && !$showAfterCancel) : $showAfterCancel = true; ?>
											<li>이후 취소 시 <?= $cancelRule->cancel_rules_percent; ?>% 환불</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
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

                <!-- 자주 묻는 질문 -->
                <?php if (count($data['partnerFaq']) !== 0) : ?>
                    <div class="product-detail__notice">
                        <div class="bullet__wrap">
                            <p class="title">자주 묻는 질문</p>
                            <a href="" class="btn-txt__arrow fnOpenPop" data-name="faqDetail">더보기</a>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- //자주 묻는 질문 -->

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
						<div id="map" style="height:20rem;" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></div>
					</div>
				</div>
				<!-- //숙소 위치 정보 -->

				<div class="product-detail__notice">
					<div class="bullet__wrap">
						<p class="title">판매자 정보</p>
						<a href="#" class="btn-txt__arrow fnOpenPop" data-name="sellerPopup">자세히 보기</a>
					</div>
				</div>

				<!-- 비슷한 숙소 -->
				<!-- <div class="product-detail__similar">
					<p class="title">비슷한 숙소</p>

					<div class="splide splide__default">
						<div class="splide__track">
							<ul class="splide__list">
								<li class="splide__slide">
									<div class="box-gray__wrap">
										<div class="thumb__wrap">
											<p class="thumb__img large"><img src="/assets/app/images/demo/img_hotel_large.png" alt=""></p>
											<div class="thumb__con">
												<p class="detail-sub">
													<span>제주특별자치도 서귀포시</span><span>5성급</span>
												</p>
												<p class="detail-name">스위트호텔 제주</p>
											</div>
											<div class="thumb__price">
												<div>
													<p class="sale-percent">80%</p>
													<p class="default-price">400,000원</p>
													<p class="sale-price">80,000원~</p>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="splide__slide">
									<div class="box-gray__wrap">
										<div class="thumb__wrap">
											<p class="thumb__img large"><img src="/assets/app/images/demo/img_hotel_large.png" alt=""></p>
											<div class="thumb__con">
												<p class="detail-sub">
													<span>제주특별자치도 서귀포시</span><span>5성급</span>
												</p>
												<p class="detail-name">스위트호텔 제주</p>
											</div>
											<div class="thumb__price">
												<div>
													<p class="sale-percent">80%</p>
													<p class="default-price">400,000원</p>
													<p class="sale-price">80,000원~</p>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="splide__slide">
									<div class="box-gray__wrap">
										<div class="thumb__wrap">
											<p class="thumb__img large"><img src="/assets/app/images/demo/img_hotel_large.png" alt=""></p>
											<div class="thumb__con">
												<p class="detail-sub">
													<span>제주특별자치도 서귀포시</span><span>5성급</span>
												</p>
												<p class="detail-name">스위트호텔 제주</p>
											</div>
											<div class="thumb__price">
												<div>
													<p class="sale-percent">80%</p>
													<p class="default-price">400,000원</p>
													<p class="sale-price">80,000원~</p>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="splide__slide">
									<div class="box-gray__wrap">
										<div class="thumb__wrap">
											<p class="thumb__img large"><img src="/assets/app/images/demo/img_hotel_large.png" alt=""></p>
											<div class="thumb__con">
												<p class="detail-sub">
													<span>제주특별자치도 서귀포시</span><span>5성급</span>
												</p>
												<p class="detail-name">스위트호텔 제주</p>
											</div>
											<div class="thumb__price">
												<div>
													<p class="sale-percent">80%</p>
													<p class="default-price">400,000원</p>
													<p class="sale-price">80,000원~</p>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="splide__slide">
									<div class="box-gray__wrap">
										<div class="thumb__wrap">
											<p class="thumb__img large"><img src="/assets/app/images/demo/img_hotel_large.png" alt=""></p>
											<div class="thumb__con">
												<p class="detail-sub">
													<span>제주특별자치도 서귀포시</span><span>5성급</span>
												</p>
												<p class="detail-name">스위트호텔 제주</p>
											</div>
											<div class="thumb__price">
												<div>
													<p class="sale-percent">80%</p>
													<p class="default-price">400,000원</p>
													<p class="sale-price">80,000원~</p>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>

				</div> -->
				<!-- //비슷한 숙소 -->
			</div>

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
						<!-- <ul class="tab__inner">
                            <li class="tab-switch__con active">
                                <a>날짜 선택</a>
                            </li>
                            <li class="tab-switch__con">
                                <a>월 선택</a>
                            </li>
                        </ul> -->
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
						<button class="btn-full__primary" id="selectRoomQuantityBtn">선택</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(객실 수량 선택) -->

		<!-- 알럿 팝업 -->
		<!-- <div id="alertAlarm" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">재오픈되면 알림을 보내드릴까요?</p>
						<p class="desc">
							객실이 재오픈되면 푸시알림을 보내드려요. <br>
							다시 마감되기 전에 서둘러 예약해보세요!
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">아니요</button>
						<button class="btn-full__primary">알림받기</button>
					</div>
				</div>
			</div>
		</div> -->
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
					<p class="ft-xxl">
						예약 진행을 위해 로그인이 필요해요.<br>
						아래의 방법으로 간편하게 로그인 해보시겠나요?
					</p>
				</div>
				<div class="layerpop__footer">
					<button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap;">카카오로 간편 로그인</button>
				</div>
			</div>
		</div>

		<div id="loginKakaoPopupCoupon" class="layerpop__wrap type-center mobileweb-popup">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<i class="ico ico-logo__big"></i>
					<p class="ft-xxl">
						쿠폰 다운로드를 위해 로그인이 필요해요.<br>
						아래의 방법으로 간편하게 로그인 해보시겠나요?
					</p>
				</div>
				<div class="layerpop__footer">
					<button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap;">카카오로 간편 로그인</button>
				</div>
			</div>
		</div>

		<div id="reviewDetail" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
					<p class="title">리뷰</p>
				</div>
				<div class="review-list__wrap" style="margin-top: 2rem; width: 100%; overflow-y: scroll; height: calc(100vh - 9.6rem);">
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
							<div class="review-img">
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
														<li class="splide__slide splide__list__product height-auto"><img src="<?= $reviewImage->path; ?>" alt=""></li>
													<?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
														<li class="splide__slide splide__list__product">
															<video class="video-element" controls>
																<source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
																현재 브라우저가 지원하지 않는 영상입니다.
															</video>
														</li>
													<?php else : ?>
														<li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endif; ?>
											<!-- <li class="splide__slide"><img src="../../assets/images/demo/img_product.jpg" alt=""></li> -->
										</ul>
									</div>
								</div>
							</div>
							<div class="review-txt">
								<p class="review">
									<?= $review->review_content; ?>
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
					<div class="product-detail__allpicture zoom-container">
						<div class="splide splide__product splide__product_full_img">
							<div class="splide__track">
								<ul class="splide__list full-size-image-list">
									<?php foreach ($stayImages as $stayImage) : ?>
										<li class="splide__slide image-position-center"><img src="<?= $stayImage; ?>" alt="" class="zoom-image"></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="slide-counter">
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

				const isVisible = rect.top <= window.innerHeight && rect.bottom >= 0;
				bottomFixedWrap.style.display = isVisible ? "block" : "none";
				bottomFixedWrap.style.bottom = isVisible ? "6rem" : "0";
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
			window.location.href = `/moongcleoffer/product/<?= $moongcleofferInfo->partner_idx; ?>?${queryParams.toString()}`;
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
			window.location.href = `/moongcleoffer/product/<?= $moongcleofferInfo->partner_idx; ?>?${queryParams.toString()}`;
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
			window.location.href = `/moongcleoffer/product/<?= $moongcleofferInfo->partner_idx; ?>?${queryParams.toString()}`;
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
						$('.header-product-name').text('');
					}

					// if ($scrollTop >= 10) {
					// 	$('.top-banner__wrap').addClass('active');
					// } else {
					// 	$('.top-banner__wrap').removeClass('active');
					// }
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
						$('.header-product-name').text('');
					}

					// if ($scrollTop >= 10) {
					// 	$('.top-banner__wrap').addClass('active');
					// } else {
					// 	$('.top-banner__wrap').removeClass('active');
					// }
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

				const response = await fetch(`/api/moongcleoffer/${moongcleofferIdx}`, {
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

		const zoomSlider = document.querySelector('.splide__product_full_img');

		var fullImageSplide = new Splide(zoomSlider, {
			arrows: true,
			perPage: 1,
			pagination: false,
			drag: false,
			swipe: false
		}).mount();

		function fullImageView(imageIndex) {
			fnOpenLayerPop('allPicture');
			fullImageSplide.go(imageIndex);
		}

		document.addEventListener("DOMContentLoaded", function() {
			const zoomContainer = document.querySelector('.zoom-container');
			const zoomImages = document.querySelectorAll('.zoom-image');

			fullImageSplide.on('mounted moved', function() {
				var currentSlide = fullImageSplide.index + 1;
				zoomSlider.querySelector('.current-slide').textContent = currentSlide;
				activeImage = zoomImages[fullImageSplide.index];
			});

			let scale = 1; // 확대/축소 스케일
			let lastScale = 1; // 이전 스케일
			let lastX = 0,
				lastY = 0; // 이전 위치
			let currentX = 0,
				currentY = 0; // 현재 위치
			let isPanning = false; // 팬 여부
			let hammer = new Hammer(zoomContainer);

			activeImage = zoomImages[0];

			// Pinch 및 Pan 활성화
			hammer.get("pinch").set({
				enable: true
			});
			hammer.get("pan").set({
				direction: Hammer.DIRECTION_ALL
			});

			// 활성화된 이미지 클릭 설정
			zoomImages.forEach(image => {
				image.addEventListener('click', function() {
					// 이전 활성 이미지 초기화
					if (activeImage && activeImage !== this) {
						resetImage(activeImage);
					}

					// 현재 클릭된 이미지를 활성화
					activeImage = this;
					lastX = 0;
					lastY = 0;
					currentX = 0;
					currentY = 0;
				});
			});

			// Pinch 확대/축소
			hammer.on("pinch", function(event) {
				if (!activeImage) return;

				scale = Math.max(1, Math.min(lastScale * event.scale, 4)); // 확대/축소 범위 (1배 ~ 4배)
				applyTransform();
			});

			hammer.on("pinchend", function() {
				if (!activeImage) return;

				lastScale = scale; // 종료 시 스케일 저장
			});

			// Pan 이동
			hammer.on("pan", function(event) {
				if (!activeImage || scale <= 1) return;

				currentX = lastX + event.deltaX; // 현재 X 좌표
				currentY = lastY + event.deltaY; // 현재 Y 좌표
				applyTransform();
			});

			hammer.on("panend", function() {
				if (!activeImage || scale <= 1) return;

				lastX = currentX; // 종료 시 위치 저장
				lastY = currentY;
			});

			// 더블탭 초기화
			hammer.on("doubletap", function() {
				if (!activeImage) return;

				resetImage(activeImage);
			});

			// 트랜스폼 적용
			function applyTransform() {
				activeImage.style.transform = `translate(${currentX}px, ${currentY}px) scale(${scale})`;
			}

			// 이미지 초기화
			function resetImage(image) {
				scale = 1;
				lastScale = 1;
				lastX = 0;
				lastY = 0;
				currentX = 0;
				currentY = 0;
				image.style.transform = "translate(0, 0) scale(1)";
			}
		});

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

	<!-- <script src="https://t1.kakaocdn.net/kakao_js_sdk/2.7.4/kakao.min.js" integrity="sha384-DKYJZ8NLiK8MN4/C5P2dtSmLQ4KwPaoqAfyA/DfmEc1VDxu4yyC7wy6K1Hs90nka" crossorigin="anonymous"></script>
	<script>
		Kakao.init('a09a9506a8284c662059e618d6ec7b42');

		function loginWithKakao() {
			const currentUrl = encodeURIComponent(window.location.href);
			const randomString = generateRandomString(16);
			const state = currentUrl + ':::' + randomString;

			Kakao.Auth.authorize({
				redirectUri: '<?= $_ENV['APP_HTTP']; ?>/auth/kakao/callback',
				state: state,
				serviceTerms: 'account_email'
			})
		}

		function generateRandomString(length) {
			const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			let result = '';
			for (let i = 0; i < length; i++) {
				result += characters.charAt(Math.floor(Math.random() * characters.length));
			}
			return result;
		}
	</script> -->

</body>

</html>