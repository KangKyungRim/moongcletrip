<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$coupons = $data['coupons'];
$myCoupons = $data['myCoupons'];

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



	<div id="mobileWrap">
		<header class="header__wrap bg-gray">
			<div class="header__inner">
				<button class="btn-back" onclick="goBack()"><span class="blind">뒤로가기</span></button>
				<h2 class="header-tit__center">쿠폰</h2>
			</div>
		</header>

		<div class="container__wrap mycoupon__wrap">

			<div class="tab__wrap tab-line__wrap full-flex">
				<ul class="tab__inner fnStickyTop">
					<li class="tab-line__con active"><a>뭉클한 쿠폰의 발견</a></li>
					<li class="tab-line__con"><a>내 쿠폰함</a></li>
				</ul>
				<div class="tab-contents__wrap">
					<!-- 뭉클한 쿠폰의 발견 -->
					<div class="tab-contents active">
						<div class="coupon-list__wrap">
							<?php foreach ($coupons as $coupon) : ?>
								<?php if (!$coupon->is_downloaded) : ?>
									<a href="#" class="coupon-list__con">
										<p class="coupon-name"><?= $coupon->coupon_name; ?></p>
										<p class="coupon-sub"><?= number_format($coupon->discount_amount); ?>원 할인</p>
										<p class="coupon-use">최소 <?= number_format($coupon->minimum_order_price); ?>원 이상 구매시에만 적용 가능</p>
										<p class="coupon-period"><?= !empty($coupon->start_date) ? formatKoreanDate($coupon->start_date) . ' 부터 ' : ''; ?> <?= !empty($coupon->end_date) ? formatKoreanDate($coupon->end_date) . ' 까지' : ''; ?></p>
										<button
                                            type="button"
                                            class="btn-sm__black download-button"
                                            data-coupon-idx="<?= $coupon->coupon_idx; ?>"
                                        >
                                            다운로드
                                        </button>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>

                            <?php if (empty($coupons)) : ?>
                                <div class="no__wrap" style="position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(-50%, -50%);">
                                    <div class="nodata__con" style="font-size: 1.4rem;">
                                        다운로드 가능한 쿠폰이 없거나 
                                        <br>이미 모두 받으셨어요
                                    </div>
                                </div>
                            <?php endif; ?>
						</div>
					</div>
					<!-- //뭉클한 쿠폰의 발견 -->
					<!-- 내 쿠폰함 -->
					<div class="tab-contents">
						<!-- <div class="filter__wrap">
							<div class="filter-select__wrap">
								<p class="filter-select__tit">
									사용기간 임박순 <i class="ico ico-arrow__down black"></i>
								</p>
								<div class="filter-select__list">
									<ul>
										<li><a href="">사용기간 임박순</a></li>
										<li><a href="">평점 높은순</a></li>
										<li><a href="">리뷰 많은순</a></li>
										<li><a href="">낮은 가격순</a></li>
										<li><a href="">높은 가격순</a></li>
									</ul>
								</div>
							</div>
						</div> -->
						<div class="coupon-list__wrap">
                            <?php if ($myCoupons) : ?>
							    <?php foreach ($myCoupons as $myCoupon) : ?>
                                
								<a href="#" class="coupon-list__con">
									<p class="coupon-name"><?= $myCoupon->coupon_name; ?></p>
									<p class="coupon-sub"><?= number_format($myCoupon->discount_amount); ?>원 할인</p>
									<p class="coupon-use">최소 <?= number_format($myCoupon->minimum_order_price); ?>원 이상 구매시에만 적용 가능</p>
									<p class="coupon-period"><?= !empty($myCoupon->start_date) ? formatKoreanDate($myCoupon->start_date) . ' 부터 ' : ''; ?> <?= !empty($myCoupon->end_date) ? formatKoreanDate($myCoupon->end_date) . ' 까지' : ''; ?></p>
								</a>
							<?php endforeach; ?>
                            <?php else : ?>
                                <div class="no__wrap" style="position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(-50%, -50%);">
                                    <div class="nodata__con" style="font-size: 1.4rem;">
                                        아직 다운로드 받은 쿠폰이 없습니다
                                    </div>
                                </div>
                            <?php endif; ?>
						</div>
					</div>
					<!-- //내 쿠폰함 -->
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
                    <div class="layerpop__footer" style="display: flex; align-items: center; gap: 1rem;">
                        <button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap; font-size: 1.2rem">카카오 1초 로그인</button>
                        <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()" style="font-size: 1.2rem;">이메일로 계속하기</button>
                    </div>
                </div>
                <div class="dim" aria-hidden="true"></div>
            </div>

		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<script>
		sessionStorage.setItem('previousPage', window.location.href);
	</script>

	<script>
		document.querySelectorAll('.download-button').forEach(button => {
                button.addEventListener('click', function() {
                    const couponIdx = this.dataset.couponIdx;

                    fetch(`/api/coupon/${couponIdx}/download`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error); // 오류 메시지 알림
                        } else {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        fnOpenLayerPop('loginKakaoPopupCoupon');
                    });
			});
		});
	</script>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

</body>

</html>