<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$myCoupons = $data['myCoupons'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

?>

<!-- Head -->
<?php 
    // $page_title = "마이페이지";
    // $page_description = "마이페이지";

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



	<div id="mobileWrap">
		<header class="header__wrap">
			<div class="header__inner">
				<h2 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h2>
				<div class="btn__wrap">
					<button type="button" class="btn-search" onclick="gotoSearch()"><span class="blind">검색</span></button>
					<button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
					<!-- <button type="button" class="btn-cart__gray" onclick="gotoTravelCart()"><span class="blind">장바구니</span></button> -->
				</div>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">
			<section class="layout__wrap pd-big">
				<!-- 사용자 설정 이미지 -->
				<div class="user-img__wrap">
					<a href="/my/profile" class="user-img">
						<img src="/assets/app/images/common/no_profile.jpg" alt="">
						<i class="ico ico-fix"></i>
					</a>
					<p class="name"><?= $user['user_nickname']; ?></p>
				</div>
				<!-- //사용자 설정 이미지 -->
				<!-- 사용자 포인트 내용 -->
				<div class="user-detail__box">
					<!-- <div class="user-detail">
						<p class="tit" style="font-weight: 700;">뭉클심박수</p>
						<p class="con"><?= $user['user_heartbeat']; ?> bpm</p>
					</div>
					<div class="user-detail">
						<p class="tit" style="font-weight: 700;">포인트</p>
						<p class="con"><?= number_format($user['user_points']); ?></p>
					</div> -->
                    <!-- 수정 -->
					<div class="user-detail" style="display: flex; justify-content: space-between; align-items: center; padding: 0 2rem;">
						<p class="tit" style="font-weight: 700; padding-bottom: 0; font-size: 1.4rem;">
                             <i class="fa-solid fa-money-bill" style="width: 1.0rem; height: 1.0rem; background: #fff; color: #714bdd; border-radius: 0.6rem; padding: 1.4rem; font-size: 1.6rem; display: inline-flex; align-items: center; justify-content: center;"></i>
                             &nbsp;&nbsp;쿠폰
                        </p>
                        <div style="width: 30%; display: flex; align-items: center; justify-content: flex-end; gap: 1rem;">
                            <p class="con cursor-pointer" style="color: #714bdd; font-weight: bold;" onclick="location.href='/coupons'"><?= empty($myCoupons) ? '0' : count($myCoupons); ?>장</p>
                            <i class="ico ico-arrow__right" onclick="location.href='/coupons'"></i>
                        </div>
					</div>
				</div>
				<!-- //사용자 포인트 내용 -->
				<div class="user-list__wrap">
					<ul>
						<li class="user-list user-list__reserve">
							<a href="/my/reservations">
								<p class="tit">예약 내역 확인</p>
								<!-- <p class="desc">숙박 · 액티비티&체험 · 투어</p> -->
							</a>
						</li>
						<li class="user-list user-list__wish">
							<a href="/my/favorites" id="favoritesLink">
								<p class="tit">찜하기 확인</p>
								<!-- <p class="desc">숙박 · 액티비티&체험 · 투어 · 판매자</p> -->
							</a>
						</li>
						<li class="user-list user-list__review">
							<a href="/my/reviews">
								<p class="tit">내 후기 확인</p>
								<!-- <p class="desc">바로 예약 · 뭉클딜</p> -->
							</a>
						</li>
					</ul>
				</div>
			</section>

            <!-- 이벤트 배너 -->
            <div class="event-banner__wrap" style="padding: 0 2rem;">
                <div class="swiper-container notice" style="overflow: hidden; position: relative; ">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="/notice/6" class="event-banner__link">
                                <img src="/assets/app/images/event_toss_mini.png" alt="이벤트 배너" style="border-radius: 2rem;">
                            </a>
                        </div>                    
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

			<section class="layout__wrap" >
				<div class="list__wrap">
					<ul>
						<li>
							<a href="/notices">
								<p class="tit">공지사항</p>
								<i class="ico ico-arrow__right"></i>
							</a>
						</li>
						<li>
							<a href="/faq">
								<p class="tit">자주 묻는 질문</p>
								<i class="ico ico-arrow__right"></i>
							</a>
						</li>
						<li>
							<a href="/term/terms-of-service">
								<p class="tit">서비스 이용 약관</p>
								<i class="ico ico-arrow__right"></i>
							</a>
						</li>
						<li>
							<a href="/term/privacy-policy">
								<p class="tit">개인정보 처리방침</p>
								<i class="ico ico-arrow__right"></i>
							</a>
						</li>
						<li>
							<a href="/term/location-based-service">
								<p class="tit">위치기반 이용약관</p>
								<i class="ico ico-arrow__right"></i>
							</a>
						</li>
						<li>
							<a href="" onclick="preventEvent(event)">
								<p class="tit">버전 정보</p>
								<p class="txt-primary ft-xxs">Beta 2.1</p>
							</a>
						</li>
						<li>
							<a href="/users/logout">
								<p class="tit">로그아웃</p>
							</a>
						</li>
					</ul>
				</div>
			</section>

			<!-- 푸터 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/footer.php"; ?>
            <!-- //푸터 -->

			<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

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
		sessionStorage.setItem('previousPage', window.location.href);
	</script>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                document.getElementById('pageLoader').style.display = 'none';
            }
        });

        document.getElementById('favoritesLink').addEventListener('click', function (e) {
            const spinner = document.getElementById('pageLoader');
            spinner.style.display = '';
        });
    </script>

    <script>
        var swiper1 = new Swiper('.swiper-container.notice', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 10000,
                disableOnInteraction: false,
            },
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
        });
    </script>

</body>

</html>