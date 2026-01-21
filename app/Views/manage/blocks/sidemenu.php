<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
	<div class="sidenav-header">
		<i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
		<a class="navbar-brand m-0" href="/manage/dashboard" target="_blank">
			<img src="/assets/app/images/common/moongcle_color.png" class="navbar-brand-img h-100 border-radius-md" alt="main_logo">
			<!-- <span class="ms-1 font-weight-bold">뭉클트립</span> -->
		</a>
	</div>
	<hr class="horizontal dark mt-0">
	<div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/dashboard') === false ? '' : 'active' ?>" href="/manage/dashboard">
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
			<!-- <li class="nav-item">
				<a data-bs-toggle="collapse" href="#user-menu" aria-controls="user-menu" role="button" aria-expanded="false" class="nav-link<?= strpos($_SERVER['REQUEST_URI'], '/manage/user') === false ? '' : ' active' ?>">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/manage/user') === false ? '' : 'active' ?>">
                        <i class="fa-solid fa-users"></i>
					</div>
					<span class="nav-link-text ms-1">회원 관리</span>
				</a>
				<div class="collapse <?= strpos($_SERVER['REQUEST_URI'], '/manage/user') === false ? '' : 'show' ?>" id="user-menu">
					<ul class="nav ms-4 ps-3">
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-user-list') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-user-list') === false ? '' : 'active' ?>" href="/manage/partner-user-list">
                                <span class="sidenav-mini-icon">Manager</span>
                                <span class="sidenav-normal">숙소 계정 관리</span>
							</a>
						</li>
					</ul>
				</div>
			</li> -->
            
            <li class="nav-item">
				<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/users') === false ? '' : 'active' ?>" href="/manage/users">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/manage/users') === false ? '' : 'active' ?>">
                        <i class="fa-solid fa-users"></i>
					</div>
					<span class="nav-link-text ms-1">회원 관리</span>
				</a>
			</li>

            <?php
                // 숙소 관리 메뉴 활성화 여부
                $isPartnerMenuActive = strpos($_SERVER['REQUEST_URI'], '/manage/partner') !== false && strpos($_SERVER['REQUEST_URI'], '/manage/partner-rateplan') === false;

                // 요금 및 인벤토리 메뉴 활성화 여부
                $isRateMenuActive = strpos($_SERVER['REQUEST_URI'], '/manage/partner-rateplan') !== false;

                // 뭉클딜 메뉴 활성화 여부
                $isMoongcleMenuActive = strpos($_SERVER['REQUEST_URI'], '/manage/moongcleoffers') !== false;
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
						<li class="nav-item">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-select') === false ? '' : 'active' ?>" href="/manage/partner-select">
								<span class="sidenav-mini-icon">Partner Select</span>
								<span class="sidenav-normal">숙소 선택</span>
							</a>
						</li>
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-user') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-user') === false ? '' : 'active' ?>" href="/manage/partner-user-list">
								<span class="sidenav-mini-icon">Manager</span>
								<span class="sidenav-normal">숙소 계정 관리</span>
							</a>
						</li>
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-basic-info') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-basic-info') === false ? '' : 'active' ?>" href="/manage/partner-basic-info">
								<span class="sidenav-mini-icon">Basic Info</span>
								<span class="sidenav-normal">숙소 정보 관리</span>
							</a>
						</li>
						<?php if ($selectedPartner['partner_category'] == 'stay') : ?>
							<!-- <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-stay-info') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-stay-info') === false ? '' : 'active' ?>" href="/manage/partner-stay-info">
									<span class="sidenav-mini-icon">Stay Info</span>
									<span class="sidenav-normal">숙소 상세 정보</span>
								</a>
							</li> -->
						<?php elseif ($selectedPartner['partner_category'] == 'activity') : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-activity-info') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-activity-info') === false ? '' : 'active' ?>" href="/manage/partner-activity-info">
									<span class="sidenav-mini-icon">Activity Info</span>
									<span class="sidenav-normal">상세 정보</span>
								</a>
							</li>
						<?php elseif ($selectedPartner['partner_category'] == 'tour') : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-tour-info') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-tour-info') === false ? '' : 'active' ?>" href="/manage/partner-tour-info">
									<span class="sidenav-mini-icon">Tour Info</span>
									<span class="sidenav-normal">상세 정보</span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($selectedPartner['partner_category'] == 'stay') : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-room') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-room') === false ? '' : 'active' ?>" href="/manage/partner-room-list">
									<span class="sidenav-mini-icon">Room Info</span>
									<span class="sidenav-normal">객실 관리</span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($selectedPartnerIdx > 0) : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-moongcle-point') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-moongcle-point') === false ? '' : 'active' ?>" href="/manage/partner-moongcle-point">
									<span class="sidenav-mini-icon">MoongclePoint</span>
									<span class="sidenav-normal">뭉클 포인트</span>
								</a>
							</li>
						<?php endif; ?>
                        <?php if ($selectedPartnerIdx > 0) : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-facilities') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-facilities') === false ? '' : 'active' ?>" href="/manage/partner-facilities">
									<span class="sidenav-mini-icon">Partner Facilities</span>
									<span class="sidenav-normal">부대 시설</span>
								</a>
							</li>
						<?php endif; ?>
                        <?php if ($selectedPartnerIdx > 0) : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-service') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-service') === false ? '' : 'active' ?>" href="/manage/partner-service">
									<span class="sidenav-mini-icon">Partner Service</span>
									<span class="sidenav-normal">아이와 함께 이용 가능 서비스<br>& 꿀팁</span>
								</a>
							</li>
						<?php endif; ?>
                        <?php if ($selectedPartnerIdx > 0) : ?>
                            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-faq') === false ? '' : 'active' ?>">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-faq') === false ? '' : 'active' ?>" href="/manage/partner-faq">
                                    <span class="sidenav-mini-icon">Faq</span>
                                    <span class="sidenav-normal">자주 묻는 질문</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($selectedPartnerIdx > 0) : ?>
							<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-safe') === false ? '' : 'active' ?>">
								<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-safe') === false ? '' : 'active' ?>" href="/manage/partner-safe">
									<span class="sidenav-mini-icon">Safe Cancel</span>
									<span class="sidenav-normal">안심 예약 보장제 </span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($selectedPartnerIdx > 0 && $selectedPartner['partner_thirdparty'] === "onda") : ?>
                            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-curated-images') === false ? '' : 'active' ?>">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-curated-images') === false ? '' : 'active' ?>" href="/manage/partner-curated-images">
                                    <span class="sidenav-mini-icon">CuratedImages</span>
                                    <span class="sidenav-normal">숙소 사진 보강</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <!-- <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-exposure') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-exposure') === false ? '' : 'active' ?>" href="/manage/partner-exposure">
								<span class="sidenav-mini-icon">Exposure Management</span>
								<span class="sidenav-normal">숙소 노출 관리</span>
							</a>
						</li> -->
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
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/manage/partner-rateplan-list') !== false || strpos($_SERVER['REQUEST_URI'], '/manage/partner-rateplan-info') !== false) ? 'active' : '' ?>" href="/manage/partner-rateplan-list">
                                <span class="sidenav-mini-icon">Rateplan Management</span>
                                <span class="sidenav-normal">요금제 관리</span>
                            </a>
                        </li>
                        <?php if ($data['selectedPartner']->partner_thirdparty !== "onda") : ?>
                            <!-- <li class="nav-item">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/partner-rateplan-calendar') === false ? '' : 'active' ?>" href="/manage/partner-rateplan-calendar">
                                    <span class="sidenav-mini-icon">Rate Calendar</span>
                                    <span class="sidenav-normal">요금 캘린더</span>
                                </a>
                            </li> -->
                            <?php
                                $currentUri = $_SERVER['REQUEST_URI'];
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $currentUri === '/manage/partner-rateplan-calendar' ? 'active' : '' ?>" href="/manage/partner-rateplan-calendar">
                                    <span class="sidenav-mini-icon">Rate Calendar</span>
                                    <span class="sidenav-normal">통합 관리</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $currentUri === '/manage/partner-rateplan-calendar?viewItems=quantity%7Cselect' ? 'active' : '' ?>" href="/manage/partner-rateplan-calendar?viewItems=quantity%7Cselect">
                                    <span class="sidenav-mini-icon">Rate Calendar</span>
                                    <span class="sidenav-normal">재고 관리</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $currentUri === '/manage/partner-rateplan-calendar?viewItems=price' ? 'active' : '' ?>" href="/manage/partner-rateplan-calendar?viewItems=price">
                                    <span class="sidenav-mini-icon">Rate Calendar</span>
                                    <span class="sidenav-normal">요금 관리</span>
                                </a>
                            </li>
                        <?php endif; ?>    
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
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/manage/moongcleoffers') !== false && strpos($_SERVER['REQUEST_URI'], '/manage/moongcleoffers/operate') === false) ? 'active' : '' ?>" href="/manage/moongcleoffers">
                                <span class="sidenav-mini-icon">Moongcle Management</span>
                                <span class="sidenav-normal">뭉클딜 관리</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/manage/moongcleoffers/operate') !== false) ? 'active' : '' ?>" href="/manage/moongcleoffers/operate">
                                <span class="sidenav-mini-icon">Moongcle Management Manage</span>
                                <span class="sidenav-normal">(관리자 전용) 뭉클딜 관리</span>
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
                            <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/manage/moongcleoffers') !== false) ? 'active' : '' ?>" href="/manage/moongcleoffers">
                                <span class="sidenav-mini-icon">Moongcle Management</span>
                                <span class="sidenav-normal">뭉클딜 관리</span>
                            </a>
                        </li>
					</ul>
				</div>
			</li> -->

			<li class="nav-item">
				<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/reservations') === false ? '' : 'active' ?>" href="/manage/reservations">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/manage/reservations') === false ? '' : 'active' ?>">
						<i class="ni ni-basket"></i>
					</div>
					<span class="nav-link-text ms-1">예약 관리</span>
				</a>
			</li>

            <!-- <li class="nav-item">
				<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/curation') === false ? '' : 'active' ?>" href="/manage/curations">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/manage/curation') === false ? '' : 'active' ?>">
                        <i class="fa-solid fa-cubes"></i>
					</div>
					<span class="nav-link-text ms-1">큐레이션 관리</span>
				</a>
			</li> -->

			<?php if ($data['user']->partner_user_level > 4) : ?>
				<?php 
					$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
					$inAppManage = preg_match('#^/manage/(curation|banners?)#', $path); 

					//하위 메뉴
					$isCuration  = strpos($path, '/manage/curation') === 0;
					$isBanner    = preg_match('#^/manage/banners?#', $path);
				?>
			<li class="nav-item">
				<a data-bs-toggle="collapse" href="#app-manage-menu" aria-controls="app-manage-menu" role="button" aria-expanded="<?= $inAppManage ? 'true' : 'false' ?>" class="nav-link <?= $inAppManage ? 'active' : '' ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= $inAppManage ? 'active' : '' ?>">
                        <i class="fa-solid fa-money-bill"></i>
                    </div>
                    <span class="nav-link-text ms-1">앱 관리</span>
                </a>
				<div class="collapse <?= $inAppManage ? 'show' : '' ?>" id="app-manage-menu">
					<ul class="nav ms-4 ps-3">
						<li class="nav-item">
							<a class="nav-link <?= $isCuration ? 'active' : '' ?>" href="/manage/curations">
								<span class="sidenav-mini-icon">Curation Manage</span>
								<span class="sidenav-normal">큐레이션 관리</span>
							</a>
						</li>
						<!--<li class="nav-item">
							<a class="nav-link <?= $isBanner ? 'active' : '' ?>" href="/manage/banners">
								<span class="sidenav-mini-icon">Curation Manage</span>
								<span class="sidenav-normal">배너 관리</span>
							</a>
						</li>-->
					</ul>
				</div>
			</li>
			<? endif; ?>

			<li class="nav-item">
				<a data-bs-toggle="collapse" href="#reward-menu" aria-controls="reward-menu" role="button" aria-expanded="false" class="nav-link<?= strpos($_SERVER['REQUEST_URI'], '/manage/reward') === false ? '' : ' active' ?>">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/manage/reward') === false ? '' : 'active' ?>">
						<i class="ni ni-money-coins"></i>
					</div>
					<span class="nav-link-text ms-1">리워드 관리</span>
				</a>
				<div class="collapse <?= strpos($_SERVER['REQUEST_URI'], '/manage/reward') === false ? '' : 'show' ?>" id="reward-menu">
					<ul class="nav ms-4 ps-3">
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/reward/discount-coupon') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/reward/discount-coupon') === false ? '' : 'active' ?>" href="/manage/reward/discount-coupons">
								<span class="sidenav-mini-icon">Coupon Manage</span>
								<span class="sidenav-normal">쿠폰 관리</span>
							</a>
						</li>
					</ul>
				</div>
			</li>

			<li class="nav-item">
				<a data-bs-toggle="collapse" href="#thirdparty-menu" aria-controls="thirdparty-menu" role="button" aria-expanded="false" class="nav-link<?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty') === false ? '' : ' active' ?>">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center <?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty') === false ? '' : 'active' ?>">
						<i class="ni ni-settings"></i>
					</div>
					<span class="nav-link-text ms-1">외부 연동 설정</span>
				</a>
				<div class="collapse <?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty') === false ? '' : 'show' ?>" id="thirdparty-menu">
					<ul class="nav ms-4 ps-3">
                        <?php if ($data['selectedPartner'] !== null && $data['selectedPartner']->partner_thirdparty !== "onda") : ?>
                            <?php if ($data['user']->partner_user_level > 4) : ?>
                                <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty/config') === false ? '' : 'active' ?>">
                                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty/config') === false ? '' : 'active' ?>" href="/manage/thirdparty/config">
                                        <span class="sidenav-mini-icon">Interworking System Settings</span>
                                        <span class="sidenav-normal">연동 시스템 설정</span>
                                    </a>
                                </li>
                            <?php endif; ?> 
                        <?php endif; ?> 
						<li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty/onda') === false ? '' : 'active' ?>">
							<a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/manage/thirdparty/onda') === false ? '' : 'active' ?>" href="/manage/thirdparty/onda">
								<span class="sidenav-mini-icon">Onda Migration</span>
								<span class="sidenav-normal">Onda</span>
							</a>
						</li>
					</ul>
				</div>
			</li>

			<li class="nav-item mt-3">
				<h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
			</li>
			<li class="nav-item">
				<a class="nav-link  " href="/manage/profile">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>customer-support</title>
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
										<g transform="translate(1.000000, 0.000000)">
											<path class="color-background opacity-6" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"></path>
											<path class="color-background" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"></path>
											<path class="color-background" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"></path>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<span class="nav-link-text ms-1">Profile</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link  " href="/manage/sign-in">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<svg width="12px" height="12px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>document</title>
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
										<g transform="translate(154.000000, 300.000000)">
											<path class="color-background opacity-6" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"></path>
											<path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<span class="nav-link-text ms-1">Sign In</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link  " href="/manage/sign-up">
					<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
						<svg width="12px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>spaceship</title>
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
										<g transform="translate(4.000000, 301.000000)">
											<path class="color-background" d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"></path>
											<path class="color-background opacity-6" d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z"></path>
											<path class="color-background opacity-6" d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"></path>
											<path class="color-background opacity-6" d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"></path>
										</g>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<span class="nav-link-text ms-1">Sign Up</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="sidenav-footer mx-3 ">
		<div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
			<div class="full-background" style="background-image: url('/assets/manage/images/curved-images/white-curved.jpg')"></div>
			<div class="card-body text-start p-3 w-100">
				<div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
					<i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true" id="sidenavCardIcon"></i>
				</div>
				<div class="docs-info">
					<h6 class="text-white up mb-0">Need help?</h6>
					<p class="text-xs font-weight-bold mb-3">Please check our docs</p>
					<a href="https://www.creative-tim.com/learning-lab/bootstrap/license/soft-ui-dashboard" target="_blank" class="btn btn-white btn-sm w-100 mb-0">Documentation</a>
				</div>
			</div>
		</div>
	</div>
</aside>