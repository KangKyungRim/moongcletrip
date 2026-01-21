<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
	<div class="sidenav-header">
		<i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
		<a class="navbar-brand m-0" href="/partner/dashboard" target="_blank">
			<img src="/assets/app/images/common/moongcle_color.png" class="navbar-brand-img h-100 border-radius-md" alt="main_logo">
			<!-- <span class="ms-1 font-weight-bold">뭉클트립</span> -->
		</a>
	</div>
	<hr class="horizontal dark mt-0">
	<div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
		<ul class="navbar-nav" style="padding-bottom: 42px;">
			<li class="nav-item">
				<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/dashboard') === false ? '' : 'active' ?>" href="/partner/dashboard">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>Dashboard</title>
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
										<g transform="translate(0.000000, 148.000000)">
											<path class="color-background opacity-6" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
											<path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<span class="nav-link-text ms-1">대시보드</span>
				</a>
			</li>
	
            <?php
                // 숙소 관리 메뉴 활성화 여부
                $isPartnerMenuActive = strpos($_SERVER['REQUEST_URI'], '/partner/partner') !== false && strpos($_SERVER['REQUEST_URI'], '/partner/partner-rateplan') === false;

                // 요금 및 인벤토리 메뉴 활성화 여부
                $isRateMenuActive = strpos($_SERVER['REQUEST_URI'], '/partner/partner-rateplan') !== false;

                // 뭉클딜 메뉴 활성화 여부
                $isMoongcleMenuActive = strpos($_SERVER['REQUEST_URI'], '/partner/moongcleoffers') !== false;
            ?>


			<li class="nav-item">
                <a data-bs-toggle="collapse" href="#partner-menu" aria-controls="partner-menu" role="button" aria-expanded="<?= $isPartnerMenuActive ? 'true' : 'false' ?>" class="nav-link<?= $isPartnerMenuActive ? ' active' : '' ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= $isPartnerMenuActive ? 'active' : '' ?>">
                        <i class="ni ni-building"></i>
                    </div>
                    <span class="nav-link-text ms-1">숙소 관리</span>
                </a>
                <div class="collapse <?= $isPartnerMenuActive ? 'show' : '' ?>" id="partner-menu">
                    <ul class="nav ms-4 ps-3">
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-user') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-user') === false ? '' : 'active' ?>" href="/partner/partner-user-list">
								<span class="sidenav-mini-icon">Manager</span>
								<span class="sidenav-normal">숙소 계정 관리</span>
							</a>
						</li>
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-basic-info') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-basic-info') === false ? '' : 'active' ?>" href="/partner/partner-basic-info">
								<span class="sidenav-mini-icon">Basic Info</span>
								<span class="sidenav-normal">숙소 정보 관리</span>
							</a>
						</li>
						<?php if ($selectedPartner['partner_category'] == 'stay') : ?>
							<!-- <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-stay-info') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-stay-info') === false ? '' : 'active' ?>" href="/partner/partner-stay-info">
									<span class="sidenav-mini-icon">Stay Info</span>
									<span class="sidenav-normal">숙소 상세 정보</span>
								</a>
							</li> -->
						<?php elseif ($selectedPartner['partner_category'] == 'activity') : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-activity-info') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-activity-info') === false ? '' : 'active' ?>" href="/partner/partner-activity-info">
									<span class="sidenav-mini-icon">Activity Info</span>
									<span class="sidenav-normal">상세 정보</span>
								</a>
							</li>
						<?php elseif ($selectedPartner['partner_category'] == 'tour') : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-tour-info') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-tour-info') === false ? '' : 'active' ?>" href="/partner/partner-tour-info">
									<span class="sidenav-mini-icon">Tour Info</span>
									<span class="sidenav-normal">상세 정보</span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($selectedPartner['partner_category'] == 'stay') : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-room') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-room') === false ? '' : 'active' ?>" href="/partner/partner-room-list">
									<span class="sidenav-mini-icon">Room Info</span>
									<span class="sidenav-normal">객실 관리</span>
								</a>
							</li>
						<?php endif; ?>
                        <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-facilities') === false ? '' : 'active' ?>">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-facilities') === false ? '' : 'active' ?>" href="/partner/partner-facilities">
                                <span class="sidenav-mini-icon">Partner Facilities</span>
                                <span class="sidenav-normal">부대 시설</span>
                            </a>
                        </li> 
                        <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-service') === false ? '' : 'active' ?>">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-service') === false ? '' : 'active' ?>" href="/partner/partner-service">
                                <span class="sidenav-mini-icon">Partner Service</span>
                                <span class="sidenav-normal">아이와 함께 이용 가능 서비스<br>& 꿀팁</span>
                            </a>
                        </li>
                        <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-social') === false ? '' : 'active' ?>">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-social') === false ? '' : 'active' ?>" href="/partner/partner-social">
                                <span class="sidenav-mini-icon">Partner social</span>
                                <span class="sidenav-normal">뭉클맘들의 소셜 후기</span>
                            </a>
                        </li>
                        <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-faq') === false ? '' : 'active' ?>">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-faq') === false ? '' : 'active' ?>" href="/partner/partner-faq">
                                <span class="sidenav-mini-icon">Faq</span>
                                <span class="sidenav-normal">자주 묻는 질문</span>
                            </a>
                        </li>
                        <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-safe') === false ? '' : 'active' ?>">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-safe') === false ? '' : 'active' ?>" href="/partner/partner-safe">
                                <span class="sidenav-mini-icon">Safe Cancel</span>
                                <span class="sidenav-normal">안심 예약 보장제 </span>
                            </a>
                        </li>
						<?php if ($selectedPartnerIdx > 0 && $selectedPartner['partner_thirdparty'] === "onda") : ?>
                            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-curated-images') === false ? '' : 'active' ?>">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-curated-images') === false ? '' : 'active' ?>" href="/partner/partner-curated-images">
                                    <span class="sidenav-mini-icon">CuratedImages</span>
                                    <span class="sidenav-normal">숙소 사진 보강</span>
                                </a>
                            </li>
                        <?php endif; ?>
					</ul>
				</div>
			</li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#rate-menu" aria-controls="rate-menu" role="button" aria-expanded="<?= $isRateMenuActive ? 'true' : 'false' ?>" class="nav-link<?= $isRateMenuActive ? ' active' : '' ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= $isRateMenuActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-money-bill"></i>
                    </div>
                    <span class="nav-link-text ms-1">요금 및 인벤토리</span>
                </a>
                <div class="collapse <?= $isRateMenuActive ? 'show' : '' ?>" id="rate-menu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/partner/partner-rateplan-list') !== false || strpos($_SERVER['REQUEST_URI'], '/partner/partner-rateplan-info') !== false) ? 'active' : '' ?>" href="/partner/partner-rateplan-list">
                                <span class="sidenav-mini-icon">Rateplan Management</span>
                                <span class="sidenav-normal">요금제 관리</span>
                            </a>
                        </li>
						<!-- <li class="nav-item">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/partner-rateplan-calendar') === false ? '' : 'active' ?>" href="/partner/partner-rateplan-calendar">
								<span class="sidenav-mini-icon">Rate Calendar</span>
								<span class="sidenav-normal">요금 캘린더</span>
							</a>
						</li> -->
                        <?php
                            $currentUri = $_SERVER['REQUEST_URI'];
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri === '/partner/partner-rateplan-calendar' ? 'active' : '' ?>" href="/partner/partner-rateplan-calendar">
                                <span class="sidenav-mini-icon">Rate Calendar</span>
                                <span class="sidenav-normal">통합 관리</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri === '/partner/partner-rateplan-calendar?viewItems=quantity%7Cselect' ? 'active' : '' ?>" href="/partner/partner-rateplan-calendar?viewItems=quantity%7Cselect">
                                <span class="sidenav-mini-icon">Rate Calendar</span>
                                <span class="sidenav-normal">재고 관리</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri === '/partner/partner-rateplan-calendar?viewItems=price' ? 'active' : '' ?>" href="/partner/partner-rateplan-calendar?viewItems=price">
                                <span class="sidenav-mini-icon">Rate Calendar</span>
                                <span class="sidenav-normal">요금 관리</span>
                            </a>
                        </li>
					</ul>
				</div>
			</li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#moongcel-menu" aria-controls="moongcel-menu" role="button" aria-expanded="true" class="nav-link active">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center active">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <span class="nav-link-text ms-1">뭉클딜</span>
                </a>
                <div class="collapse show" id="moongcel-menu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/partner/moongcleoffers') !== false) ? 'active' : '' ?>" href="/partner/moongcleoffers">
                                <span class="sidenav-mini-icon">Moongcle Management</span>
                                <span class="sidenav-normal">뭉클딜 관리</span>
                            </a>
                        </li>
					</ul>
				</div>
			</li>

            <!-- <li class="nav-item">
                <a data-bs-toggle="collapse" href="#moongcel-menu" aria-controls="moongcel-menu" role="button" aria-expanded="<?= $isMoongcleMenuActive ? 'true' : 'false' ?>" class="nav-link<?= $isMoongcleMenuActive ? ' active' : '' ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= $isMoongcleMenuActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <span class="nav-link-text ms-1">뭉클딜</span>
                </a>
                <div class="collapse <?= $isMoongcleMenuActive ? 'show' : '' ?>" id="moongcel-menu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/partner/moongcleoffers') !== false) ? 'active' : '' ?>" href="/partner/moongcleoffers">
                                <span class="sidenav-mini-icon">Moongcle Management</span>
                                <span class="sidenav-normal">뭉클딜 관리</span>
                            </a>
                        </li>
					</ul>
				</div>
			</li> -->

			<li class="nav-item">
				<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/reservations') === false ? '' : 'active' ?>" href="/partner/reservations">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/partner/reservations') === false ? '' : 'active' ?>">
						<i class="ni ni-basket"></i>
					</div>
					<span class="nav-link-text ms-1">예약 관리</span>
				</a>
			</li>

			<!-- <li class="nav-item">
				<div class="collapse <?= strpos($_SERVER['REQUEST_URI'], '/partner/reward') === false ? '' : 'show' ?>" id="reward-menu">
					<ul class="nav ms-4 ps-3">
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/partner/reward/discount-coupon') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/partner/reward/discount-coupon') === false ? '' : 'active' ?>" href="/partner/reward/discount-coupons">
								<span class="sidenav-mini-icon">Coupon Manage</span>
								<span class="sidenav-normal">쿠폰 관리</span>
							</a>
						</li>
					</ul>
				</div>
			</li> -->
		</ul>
	</div>
    <div class="sidenav-footer mx-3 ">
		<div class="card card-background-mask-secondary">
			<div class="card-body text-start p-3 w-100">
                <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" onclick="window.open('https://works.do/GN7U8Qa', '_blank')">[뭉클 2.0 소개서 바로보기]</button>
			</div>
		</div>
	</div>
</aside>

<script>
    //파트너 아이디  쿠키 저장
    function setCookie(name, value) {
        document.cookie = `${name}=${value}; path=/;`;
    }

    // 데이터에서 idx 값을 가져와 쿠키에 추가
    function saveIdxToCookie(data) {
        if (data) {
            setCookie("partner", data);
        } else {
            console.error("idx 값이 없습니다.");
        }
    }

    window.addEventListener("load", function () {
        const data = <?= json_encode($data['selectedPartnerIdx']); ?>;

        saveIdxToCookie(data);
    });
</script>