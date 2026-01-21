<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$reservations = $data['reservations'];
$statusCount = $data['statusCount'];

$allCount = 0;

if (!empty($statusCount)) {
	$allCount = $statusCount->confirmed + $statusCount->completed + $statusCount->canceled;
}

$reservationGroups = [
    'all' => [],
    'confirmed' => [],
    'completed' => [],
    'canceled' => [],
  ];
  
  foreach ($reservations as $r) {
      $reservationGroups['all'][] = $r;
      $status = $r->reservation_status;
      if (isset($reservationGroups[$status])) {
          $reservationGroups[$status][] = $r;
      }
  }

?>

<!-- Head -->
<?php 
    // $page_title = "";
    // $page_description = "";

    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>
<!-- Head -->

<style>
    .reservation-section { 
        display: none;
     }

    .reservation-section.active { 
        display: block;
    }
</style>

<body>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
	}
	?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>



	<div id="mobileWrap">
		<header class="header__wrap">
			<div class="header__inner">
				<button class="btn-back" onclick="gotoMypage()"><span class="blind">뒤로가기</span></button>
				<h2 class="header-tit__center">예약 내역</h2>
			</div>
		</header>

		<div class="container__wrap travelcart__wrap reservation__wrap">
			<!-- 검색 폼 -->
			<!-- <div class="search-form__wrap only-input">
				<div class="search-form__con search-input">
					<i class="ico ico-search__small"></i>
					<div class="input__wrap">
						<input type="text" class="input" placeholder="숙소, 액티비티&체험, 투어명 검색">
						<button type="button" class="btn-input__delete"><i class="ico ico-input__delete"></i></button>
					</div>
				</div>
			</div> -->
			<!-- //검색 폼 -->

            <div class="tab__wrap tab-line__wrap full-flex">
                <ul class="tab__inner">
                    <li class="tab-line__con active"><a>숙박</a></li>
                    <li class="tab-line__con"><a></a></li>
                    <li class="tab-line__con"><a></a></li>
                </ul>

                <div class="tab-contents__wrap">
                    
                    <!-- 숙박 -->
                    <div class="tab-contents active">
                        
                        <div class="tab-sub__wrap type-primary">
                            <ul class="tab-sub__inner">
                                <li class="tab-sub__con active" data-tab="all"><a>전체 <?= $allCount ?></a></li>
                                <li class="tab-sub__con" data-tab="confirmed"><a>예약 완료 <?= $statusCount->confirmed ?? 0; ?></a></li>
                                <li class="tab-sub__con" data-tab="completed"><a>투숙 완료 <?= $statusCount->completed ?? 0; ?></a></li>
                                <li class="tab-sub__con" data-tab="canceled"><a>취소 <?= $statusCount->canceled ?? 0; ?></a></li>
                            </ul>
                        </div>

                        <?php foreach ($reservationGroups as $key => $group) : ?>
                            <?php if (!empty($group)) : ?>
                                <section class="layout__wrap pt20 bg-gray reservation-section <?= $key === 'all' ? 'active' : ''; ?>" data-tab-content="<?= $key ?>">
                                    <!-- 이벤트 배너 -->
                                    <!-- <div class="event-banner__wrap" style="margin-bottom: 1.2rem;">
                                        <a href="/notice/3" class="event-banner__link">
                                            <img src="/assets/app/images/event_banner_02.png" alt="이벤트 배너" style="border-radius: 2rem;" width="100%" height="94">
                                        </a>
                                    </div> -->

                                    <div class="box-white__list">

                                        <?php foreach ($group as $reservation) : ?>
                                            <?php $reservationImages = explode(':-:', $reservation->image_paths); ?>
                                            <div class="box-white__wrap">
                                                <div class="reservation-top">
                                                    <div class="flex-center">
                                                        <div class="badge badge__lavender"><?= getProductCategory($reservation->product_category); ?></div>
                                                        <p class="ft-xxs txt-gray"><?= getProductStatus($reservation->reservation_status); ?></p>
                                                    </div>
                                                    <?php if ($reservation->reservation_status === 'confirmed') : ?>
                                                        <a href="/my/reservation/<?= $reservation->payment_idx; ?>" class="btn-txt__arrow">예약 상세</a>
                                                    <?php elseif ($reservation->reservation_status === 'completed') : ?>
                                                        <a href="/my/reservation/<?= $reservation->payment_idx; ?>" class="btn-txt__arrow">예약 상세</a>
                                                    <?php else : ?>
                                                        <a href="/my/canceled-reservation/<?= $reservation->payment_idx; ?>" class="btn-txt__arrow">예약 상세</a>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="product-name"><?= $reservation->product_partner_name; ?></p>
                                                <div class="thumb__wrap">
                                                    <?php if (!empty($reservationImages[0])) : ?>
                                                        <p class="thumb__img large"><img src="<?= $reservationImages[0]; ?>" alt=""></p>
                                                    <?php else : ?>
                                                        <p class="thumb__img large"><img src="/assets/app/images/demo/img_hotel_large.png" alt=""></p>
                                                    <?php endif; ?>
                                                    <div class="thumb__con">
                                                        <p class="detail-name"><?= $reservation->product_detail_name; ?></p>
                                                        <p class="detail-sub">
                                                            <span><?= $reservation->partner_address1; ?></span>
                                                            <?php
                                                            $ratingKeywords = ['1성', '2성', '3성', '4성', '5성'];
                                                            $hasRating = false;

                                                            if (!empty($reservation->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($reservation->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($reservation->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php
                                                        $startDate = new DateTime($reservation->start_date);
                                                        $dayOfWeek = date('l', strtotime($reservation->start_date));
                                                        $startDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

                                                        $endDate = new DateTime($reservation->end_date);
                                                        $dayOfWeek = date('l', strtotime($reservation->end_date));
                                                        $endDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

                                                        $interval = $startDate->diff($endDate);
                                                        ?>
                                                        <p class="detail-sub"><?= $startDate->format('Y.m.d'); ?>(<?= $startDayOfWeekKorean; ?>)-<?= $endDate->format('Y.m.d'); ?>(<?= $endDayOfWeekKorean; ?>) | <?= $interval->days; ?>박</p>
                                                        <!-- <p class="detail-sub">성인 1명, 아동 1명, 유아 1명</p> -->
                                                    </div>
                                                    <div class="thumb__price">
                                                        <div>
                                                            <?php
                                                            $basicPrice = $reservation->item_basic_price;
                                                            $salePrice = $reservation->item_sale_price;
                                                            ?>
                                                            <p class="sale-percent">+ <?= number_format((($basicPrice - $salePrice) / $basicPrice) * 100, 1); ?>%</p>
                                                            <p class="default-price"><?= number_format($basicPrice) ?>원</p>
                                                            <p class="sale-price"><?= number_format($salePrice) ?>원</p>
                                                        </div>
                                                        <?php
                                                        $freeCancelDate = new DateTime($reservation->free_cancel_date);
                                                        ?>
                                                        <!-- <?php if ($reservation->refundable) : ?>
                                                            <p class="ft-xxs"><?= $freeCancelDate->format('Y.m.d H:i'); ?>까지 무료 취소</p>
                                                        <?php endif; ?> -->
                                                    </div>
                                                    <?php if (!empty($reservation->product_benefits)) : ?>
                                                        <?php $benefits = json_decode($reservation->product_benefits); ?>
                                                        <div class="thumb__gift">
                                                            <ul>
                                                                <?php foreach ($benefits as $benefit) : ?>
                                                                    <li><?= $benefit->benefit_name; ?></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="reservation-bottom">
                                                    <p class="reservation-num">예약번호 <span><?= $reservation->reservation_number; ?></span></p>
                                                    
                                                    <?php if ($reservation->reservation_status === 'confirmed') : ?>
                                                        <div style="display: flex; gap: 0.5rem; width: 100%; justify-content: flex-end;">
                                                            <?php if ($reservation->refundable) : ?>
                                                                <a href="/my/cancel-reservation/<?= $reservation->payment_idx; ?>" class="btn-reservation btn-reservation__cancle">예약 취소</a>
                                                            <?php endif; ?>

                                                            <?php
                                                            $today = date('Y-m-d');
                                                            $reservationDate = substr($reservation->start_date, 0, 10);

                                                            if ($reservationDate <= $today) : ?>
                                                                <?php if ($reservation->has_review == 0) : ?>
                                                                    <a href="/my/create-review/<?= $reservation->payment_item_idx; ?>" class="btn-reservation btn-reservation__review" style="background-color: #00cb9c;">후기 작성</a>
                                                                <?php else : ?>
                                                                    <button type="button" class="btn-reservation btn-reservation__review disabled" disabled>후기 작성 완료</button>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php elseif ($reservation->reservation_status === 'completed') : ?>
                                                            <?php if ($reservation->has_review == 0) : ?>
                                                                <a href="/my/create-review/<?= $reservation->payment_item_idx; ?>" class="btn-reservation btn-reservation__review" style="background-color: #00cb9c;">후기 작성</a>
                                                            <?php else : ?>
                                                                <button type="button" class="btn-reservation btn-reservation__review disabled" disabled>후기 작성 완료</button>
                                                            <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                </section>
                            <?php else : ?>
                                <section class="layout__wrap pt20 reservation-section <?= $key === 'all' ? 'active' : ''; ?>" data-tab-content="<?= $key ?>">
                                    <div class="no__wrap" style="width: 100%; height: 73vh; display: flex; align-items: center; justify-content: center;">
                                        <div class="nodata__con" style="font-size: 1.4rem;">아직 예약 내역이 없어요</div>
                                    </div>
                                </section>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
		</div>

		<div id="gotoPreVersion" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">이전 버전 뭉클의 예약 내역이 필요하신가요?</p>
						<p class="desc">
							아래 버튼을 눌러 링크를 복사 후 웹브라우저에서 확인해주세요!
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">닫기</button>
						<button class="btn-full__primary" onclick="copyPreVersionLink()">링크복사</button>
					</div>
				</div>
			</div>
		</div>

		<div id="linkCopyToast" class="toast__wrap">
			<div class="toast__container">
				<i class="ico ico-info"></i>
				<p>링크를 클립보드에 복사했습니다!</p>
			</div>
		</div>

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
    document.querySelectorAll('.tab-sub__con').forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.tab;

        // 탭 활성화 클래스
        document.querySelectorAll('.tab-sub__con').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        // 예약 리스트 영역 전환
        document.querySelectorAll('.reservation-section').forEach(section => {
        section.classList.remove('active');
        if (section.dataset.tabContent === target) {
            section.classList.add('active');
        }
        });
    });
    });
    </script>

	<script>
		function copyPreVersionLink() {
			navigator.clipboard.writeText('https://moongcletrip.com/my.php').then(function() {
				fnToastPop('linkCopyToast');
			}).catch(function(error) {
				console.error('복사에 실패했습니다:', error);
			});
		}
	</script>

</body>

</html>