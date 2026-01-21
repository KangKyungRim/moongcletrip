<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$partner = $data['partner'];
$refundPolicy = $data['refundPolicy'];
$refundDate = $data['refundDate'];
$user = $data['user'];
$myCoupons = $data['myCoupons'];
$terms2 = $data['terms2'];
$passToday = $data['passToday'];

$refundDate = new DateTime($refundDate);
// $refundDate->setTime(23, 50, 00);
$refundDate->format(DateTime::RFC3339);

$today = new DateTime();
$today->format(DateTime::RFC3339);

$dayOfWeek = date('l', strtotime($_GET['startDate']));
$startDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

$dayOfWeek = date('l', strtotime($_GET['endDate']));
$endDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

$dayOfWeek = date('l', strtotime($refundDate->format('Y-m-d H:i:s')));
$refundDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

if (
	$partner->rateplan_name == '[Room only]'
	|| $partner->rateplan_name == '[회원특가] Room only'
	|| $partner->rateplan_name == 'room only'
	|| $partner->rateplan_name == 'standalone'
	|| $partner->rateplan_name == '룸온리'
) {
	$partner->rateplan_name = '';
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



	<div id="mobileWrap">
		<header class="header__wrap">
			<div class="header__inner">
				<button class="btn-back fnOpenPop" data-name="alert"><span class="blind">뒤로가기</span></button>
				<p class="header-tit__center"></p>
			</div>
		</header>

		<div class="container__wrap payment__wrap">

            <!-- 결제 정보 타이틀 -->
			<div class="payment__top">
                <h2 class="product-name"><?= $partner->partner_name; ?></h2>
                <div class="flex__box">
                    <div class="thumb__img large">
                        <img src="<?= $partner->image_path; ?>" alt="대표 이미지">
                    </div>
                    <div class="tit__wrap thumb__con">
                        <p class="name" style="padding-bottom: 0.8rem;"><?= !empty($partner->rateplan_name) ? '[' . $partner->rateplan_name . '] ' : ''; ?><?= $partner->room_name; ?></p> 
                        <p class="detail-sub">
                            <?php
                            $stayRating = '';
                            $stayTags = explode(':-:', $partner->tags);

                            if (!empty($stayTags)) {
                                foreach ($stayTags as $stayTag) {
                                    if (in_array($stayTag, ['1성', '2성', '3성', '4성', '5성'])) {
                                        $stayRating = $stayTag;
                                        break;
                                    }
                                }
                            }

                            // 성급 정보가 있으면 구분자 추가
                            $address = $partner->partner_address1;
                            $displayText = $stayRating ? "$address | $stayRating" : $address;
                            ?>
                            <span><?= $displayText; ?></span>
                        </p>
                    </div>
                </div>
			</div>
			<!-- //결제 정보 타이틀 -->
            <hr class="divide">
            <!-- 예약 정보 -->
            <div class="reservation__con">
                <div class="reservation-list__wrap">
                    <div class="tit__wrap">
                        <p class="ft-xl">예약 정보</p>
                    </div>
                    <div class="reservation-list__con">
                        <ul>
                            <?php if (!empty($partner->views)) : ?>
                                <?php $roomViews = explode(':-:', $partner->views); ?>
                                <?php
                                $alignStyle = (count($roomViews) > 1) ? "flex-start" : "center";
                                ?>
                                <li style="align-items: <?= $alignStyle ?>;">
                                    <p class="tit">전망</p>
                                    <p class="con" style="text-align: right;">
                                        <?php
                                        
                                        if (count($roomViews) > 1) {
                                            echo implode('<br>', $roomViews); 
                                        } else {
                                            echo $roomViews[0];
                                        }
                                        ?>
                                    </p>
                                </li>
                            <?php endif; ?>
                            <?php 
                            $bedTypes = json_decode($partner->room_bed_type, true);

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

                            $bedList = [];

                            foreach ($bedTypes as $bedType => $bedCount) {
                                if ($bedCount == 0) continue;
                                if (isset($bedNames[$bedType])) {
                                    $bedList[] = $bedNames[$bedType] . " {$bedCount}개";
                                }
                            }

                            if (!empty($bedList)) {
                                $alignStyle = (count($bedList) > 1) ? "flex-start" : "center";
                                $paddingTop = (count($bedList) > 1) ? "1.6rem" : "1.2rem";
                            ?>
                                <li style="align-items: <?= $alignStyle ?>; padding-top: <?= $paddingTop ?>;">
                                    <p class="tit">침대 구성</p>
                                    <p class="con" style="text-align: right;">
                                        <?= implode('<br>', $bedList); ?>
                                    </p>
                                </li>
                            <?php 
                            } 
                            ?>
                            <li>
                                <p class="tit">기준 / 최대 인원</p>
                                <p class="con"><?= $partner->room_standard_person; ?>인 기준 / 최대 <?= $partner->room_max_person; ?>인</p>
                            </li>
                            <hr class="divide__small">
                            <li>
                                <p class="tit">객실 수량</p>
                                <p class="con">
                                    <?php 
                                        $roomQuantity = isset($_GET['roomQuantity']) ? (int)$_GET['roomQuantity'] : 1;
                                        echo "{$roomQuantity}개";
                                    ?>
                                </p>
                            </li>
                            <?php 
                            $adult = isset($_GET['adult']) ? (int)$_GET['adult'] : 0;
                            $child = isset($_GET['child']) ? (int)$_GET['child'] : 0;
                            $infant = isset($_GET['infant']) ? (int)$_GET['infant'] : 0;

                            $childAge = isset($_GET['childAge']) ? json_decode($_GET['childAge'], true) : [];
                            $infantMonth = isset($_GET['infantMonth']) ? json_decode($_GET['infantMonth'], true) : [];

                            $childAges = array_values($childAge);
                            $infantMonths = array_values($infantMonth);

                            $people = [];

                            // 성인 추가
                            if ($adult > 0) {
                                $people[] = "성인 {$adult}명";
                            }

                            // 아동 추가 (나이 포함)
                            if ($child > 0) {
                                $ageList = !empty($childAges) ? ' ('.implode(', ', $childAges).'세)' : ''; 
                                $people[] = "아동 {$child}명{$ageList}";
                            }

                            // 유아 추가 (개월 수 포함)
                            if ($infant > 0) {
                                $monthList = !empty($infantMonths) ? ' ('.implode(', ', $infantMonths).'개월)' : ''; 
                                $people[] = "유아 {$infant}명{$monthList}";
                            }

                            $peopleCount = count($people);
                            $alignStyle = ($peopleCount > 1) ? "flex-start" : "center";
                            $paddingTop = ($peopleCount > 1) ? "1.6rem" : "1.2rem";
                            ?>

                            <li style="align-items: <?= $alignStyle ?>; padding-top: <?= $paddingTop ?>;">
                                <p class="tit">투숙 인원</p>
                                <p class="con" style="text-align: right;">
                                    <?= !empty($people) ? implode('<br>', $people) : '인원 없음'; ?>
                                </p>
                            </li>
                            <li>
                                <p class="tit">투숙 기간</p>
                                <p class="con"><?= $data['interval']; ?>박</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="payment__top" style="padding: 0px;">
                    <div class="date-box" style="margin-top: 2.4rem;">
                        <div class="start-date">
                            <p class="tit">체크인</p>
                            <p class="date"><?= $_GET['startDate']; ?> (<?= $startDayOfWeekKorean; ?>)</p>
                            <p class="time"><?= substr($partner->stay_checkin_rule, 0, 5); ?> 부터</p>
                        </div>
                        <div class="end-date">
                            <p class="tit">체크아웃</p>
                            <p class="date"><?= $_GET['endDate']; ?> (<?= $endDayOfWeekKorean; ?>)</p>
                            <p class="time"><?= substr($partner->stay_checkout_rule, 0, 5); ?> 까지</p>
                        </div>
                    </div>
                    <?php if ($partner->rateplan_is_refundable) : ?>
                        <?php if ($refundDate < $today) : ?>
                            <p class="txt-primary cursor-pointer txt-primary-warning fnOpenPop" data-name="terms1">무료취소는 불가해요 (예약 전, 취소 규정을 꼭 확인해주세요)</p>
                        <?php elseif ($refundDate == $today) : ?>
                            <p class="txt-primary">오늘까지 무료취소 가능 (숙박시설 현지 시간 기준)</p>
                        <?php else : ?>
                            <p class="txt-primary"><?= $refundDate->format('Y-m-d H:i'); ?> (<?= $refundDayOfWeekKorean; ?>) 까지 무료취소 가능 (현지시간 기준)</p>
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="txt-primary">취소가 불가능한 상품입니다.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- //예약 정보 -->
			<hr class="divide">
			<!-- 결제 정보  -->
			<div class="payment__con">
				<!-- 비로그인 시 노출 -->
				<!-- <div class="payment__list">
					<div class="tit__wrap type2">
						<p class="title">할인 혜택</p>
					</div>
					<a href="" class="box-yellow__wrap">
						<div>
							<p class="ft-s">로그인 후 할인받아 예약해보세요.</p>
							<p class="ft-xxs">로그인만 해도 5만원 쿠폰팩을 선물해드려요.</p>
						</div>
						<i class="ico ico-arrow__right__black"></i>
					</a>
				</div> -->
				<!-- //비로그인 시 노출 -->
				<div id="reservationUser" class="payment__list reservation-collapse">
					<div class="tit__wrap">
						<p class="title">예약자 정보</p>
						<p class="desc">*정확한 실명 및 연락처를 입력해 주세요. 예약관련 중요 안내 알림톡 / 이메일이 발송됩니다.</p>
					</div>
					<div class="input__wrap">
						<div class="input__con">
							<label for="reservationName" class="input-label">성함<span class="required-indicator">*</span></label>
							<input id="reservationName" type="text" class="input-default" placeholder="성함을 입력해 주세요" value="<?= !empty($user->reservation_name) ? $user->reservation_name : ''; ?>" required>
						</div>
						<div class="input__con">
							<label for="reservationTel" class="input-label">전화번호<span class="required-indicator">*</span></label>
							<input id="reservationTel" type="tel" class="input-default" placeholder="- 는 제외하고 숫자만 입력해 주세요" value="<?= !empty($user->reservation_phone) ? $user->reservation_phone : ''; ?>" required>
						</div>
						<div class="input__con">
							<label for="reservationEmail" class="input-label">이메일<span class="required-indicator">*</span></label>
							<input id="reservationEmail" type="email" class="input-default" placeholder="이메일을 입력해 주세요" value="<?= !empty($user->reservation_email) ? $user->reservation_email : ''; ?>" required>
						</div>
					</div>
				</div>
				<div id="visitUser" class="payment__list">
					<div class="tit__wrap flex-between">
						<p class="title">이용자 정보</p>
						<div class="checkbox__wrap">
							<div class="checkbox">
								<input type="checkbox" id="sameUser" />
								<label for="sameUser">
									<span class="ft-xxs">예약자와 동일</span>
								</label>
							</div>
						</div>
					</div>
					<div class="input__wrap">
						<div class="input__con">
							<label for="visitName" class="input-label">성함<span class="required-indicator">*</span></label>
							<input id="visitName" type="text" class="input-default" placeholder="성함을 입력해 주세요" required>
						</div>
						<div class="input__con">
							<label for="visitTel" class="input-label">전화번호<span class="required-indicator">*</span></label>
							<input id="visitTel" type="tel" class="input-default" placeholder="- 는 제외하고 숫자만 입력해 주세요" required>
						</div>
						<div class="input__con">
							<label for="visitEmail" class="input-label">이메일<span class="required-indicator">*</span></label>
							<input id="visitEmail" type="email" class="input-default" placeholder="이메일을 입력해 주세요" required>
						</div>
						<div class="input__con flex-between">
							<label class="input-label">방문수단<span class="required-indicator">*</span></label>
							<div class="checkbox__wrap">
								<div class="checkbox">
									<input type="radio" id="vehicle" name="visit" value="vehicle" checked />
									<label for="vehicle">
										<span class="ft-default">차량</span>
									</label>
								</div>
								<div class="checkbox">
									<input type="radio" id="walk" name="visit" value="walk" />
									<label for="walk">
										<span class="ft-default">도보</span>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="payment__list">
					<div class="tit__wrap">
						<p class="title">할인 혜택</p>
					</div>
					<div class="payment__sale input__wrap">
						<button type="button" class="btn-payment__coupon fnOpenPop" data-name="couponList">
							<span class="tit">쿠폰</span>
							<div class="flex-center">
								<span class="txt">사용 가능 <em><?= !empty($myCoupons) ? count($myCoupons) : '0' ?></em>장</span>
								<i class="ico ico-arrow__down"></i>
							</div>
						</button>
						<!-- <div class="input__con input-point">
							<div>
								<label for="point" class="input-label">포인트</label>
								<input id="point" type="text" class="input-default" placeholder="0">
								<span class="unit">원</span>
							</div>
							<button type="button" class="btn-full__black">모두 사용</button>
						</div>
						<p class="txt-point">
							<span>사용 가능 : 1,000원</span>
							<span class="divider">|</span>
							<span class="txt-gray">보유 포인트 : 1,011원</span>
						</p> -->
					</div>
				</div>

				<div class="payment__total">
					<p class="title">결제 금액</p>
					<ul class="pay-list">
						<li>
							<span class="tit">일반요금</span>
							<span id="totalBasicPrice" class="money"><?= number_format($partner->total_basic_price); ?>원</span>
						</li>
						<li>
							<span class="tit">뭉클 특가</span>
							<span id="totalSalePrice" class="money txt-primary">-<?= number_format($partner->total_basic_price - $partner->total_sale_price); ?>원</span>
						</li>
						<li>
							<span class="tit">쿠폰 할인</span>
							<span id="discountCouponPrice" class="money txt-primary">-0원</span>
						</li>
						<!-- <li>
							<span class="tit">포인트 할인</span>
							<span class="money txt-primary">-0원</span>
						</li> -->
						<li class="total">
							<span class="tit">총 결제 금액</span>
							<span id="totalFinalPrice" class="money"><?= number_format($partner->total_sale_price); ?>원</span>
						</li>
					</ul>
				</div>
				<div class="payment__list">
					<!-- <div class="tit__wrap type2">
						<p class="title">결제 수단</p>
					</div> -->

					<!-- Toss 결제 UI -->
					<!-- <div id="payment-method"></div> -->

					<!-- Toss 이용약관 UI -->
					<div id="agreement"></div>

					<!-- 전체 동의 -->
					<div class="checkbox__wrap check-list__wrap agree__list custom">
						<div class="check-list__all">
							<div class="checkbox">
								<input type="checkbox" id="agreeAll" required />
								<label for="agreeAll">
									<span class="ft-default">전체 동의<span class="required-indicator">*</span></span>
								</label>
							</div>
						</div>

						<div class="check-list__con">
							<div class="checkbox">
								<input type="checkbox" id="agreeTerms1" />
								<label for="agreeTerms1">
									<p class="flex-between">
										<span class="ft-s">[필수] 취소 및 환불 규정 동의<span class="required-indicator">*</span></span>
										<span href="" class="btn-checkbox__more fnOpenPop" data-name="terms1"><i class="ico ico-arrow__right"></i></span>
									</p>
								</label>
							</div>
							<div class="checkbox">
								<input type="checkbox" id="agreeTerms2" />
								<label for="agreeTerms2">
									<p class="flex-between">
										<span class="ft-s">[필수] 개인정보 제 3자 제공 동의<span class="required-indicator">*</span></span>
										<span href="" class="btn-checkbox__more fnOpenPop" data-name="terms2"><i class="ico ico-arrow__right"></i></span>
									</p>
								</label>
							</div>
						</div>
					</div>
					<!-- //전체 동의 -->
				</div>
			</div>
			<!-- //결제 정보  -->
			<!-- 하단 버튼 영역 -->
			<!-- <div class="bottom-fixed__wrap">
				<div class="btn__wrap">
					<button id="paymentNext" class="btn-full__primary">
						<span><?= number_format($partner->total_sale_price); ?>원</span> 결제하기
					</button>
				</div>
				<p class="bottom-txt">(주)호놀룰루컴퍼니는 통신판매중개자로서 통신판매의 당사자가 아니며, 상품의 예약, 이용 및 환불 등과 관련된 의무와 책임은 각 판매자에게 있습니다.</p>
			</div> -->
			<!-- //하단 버튼 영역 -->
		</div>

        <!-- 알럿 팝업 -->
		<div id="alertOpen" class="layerpop__wrap type-center">
			<div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">알림</p>
                    <a href="#none" class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
				<div class="layerpop__contents">
                    <p class="desc">
                        죄송합니다.<br>
                        시스템 오류로 인해 예약이 불가능합니다.
                    </p>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
                        <button class="btn-full__primary fnClosePop">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const popup = document.getElementById('alertOpen'); // ID는 문자열
                fnOpenLayerPop("alertOpen");                        // 클래스명도 문자열
            });
        </script>

		<!-- 알럿 팝업 -->
		<div id="alert" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">예약을 중단하시겠어요?</p>
						<p class="desc">
							입력하신 정보가 모두 사라집니다.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary" onclick="goBack()">중단하기</button>
						<button class="btn-full__primary fnClosePop">아니요</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->

		<!-- 쿠폰 팝업 -->
		<div id="couponList" class="layerpop__wrap type-full">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<button class="btn-back fnClosePop"><span class="blind">뒤로가기</span></button>
					<p class="title">쿠폰</p>
				</div>
				<div class="layerpop__contents" style="overflow-y: scroll; height: calc(100vh - 4.8rem);">
					<div class="coupon-list__wrap">
                        <?php if ($myCoupons) : ?>
                            <?php foreach ($myCoupons as $myCoupon) : ?>
                            <a href="#" class="coupon-list__con" data-coupon-user-idx="<?= $myCoupon->coupon_user_idx; ?>" data-coupon-name="<?= $myCoupon->coupon_name; ?>" data-discount-amount="<?= intval($myCoupon->discount_amount); ?>" data-minimum-order-price="<?= intval($myCoupon->minimum_order_price); ?>">
                                <p class="coupon-name"><?= $myCoupon->coupon_name; ?></p>
                                <p class="coupon-sub"><?= number_format($myCoupon->discount_amount); ?>원 할인</p>
                                <p class="coupon-use">최소 <?= number_format($myCoupon->minimum_order_price); ?>원 이상 구매시에만 적용 가능</p>
                                <p class="coupon-period"><?= !empty($myCoupon->start_date) ? formatKoreanDate($myCoupon->start_date) . ' 부터 ' : ''; ?> <?= !empty($myCoupon->end_date) ? formatKoreanDate($myCoupon->end_date) . ' 까지' : ''; ?></p>
                            </a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no__wrap" style="position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(-50%, -50%);">
                                <div class="nodata__con" style="font-size: 1.4rem;">
                                    앗 이런.. 적용 가능한 쿠폰이 없습니다.
                                    <button type="button" class="btn-full__primary" onclick="location.href='/coupons'" style="width: 67%; margin: 4rem auto 0;">쿠폰함 바로가기</button>
                                </div>
                            </div>
                        <?php endif; ?>
					</div>
				</div>
				<div class="layerpop__footer">

				</div>
			</div>
		</div>
		<!-- // 쿠폰 팝업 -->

		<!-- 취소 및 환불 규정 동의 팝업 -->
		<div id="terms1" class="layerpop__wrap">
			<div class="layerpop__container" style="height: fit-content;">
				<div class="layerpop__header">
					<p class="title">취소 및 환불 규정</p>
					<button class="fnClosePop"><i class="ico ico-close"></i></button>
				</div>
				<div class="layerpop__contents" style="height: fit-content; overflow-y: scroll;">
					<div class="bullet__wrap">
						<p class="title">[취소 및 환불 규정]</p>
						<div style="padding: 3rem 0; font-size: 1.5rem;">* 취소/환불 규정에 따라 구매 당일부터 취소 수수료가 발생할 수 있습니다.</div>
						<ul>
							<?php foreach ($refundPolicy as $policy) : ?>
								<?php
								$until = new DateTime($policy->until);
								// $until->setTime(23, 50, 00);
								?>
								<?php if ($policy->percent != 0) : ?>
									<li><?= $until->format('Y-m-d H:i'); ?>까지 취소 시 <?= $policy->percent; ?>% 환불</li>
								<?php else : ?>
									<li>이후 취소 시 <?= $policy->percent; ?>% 환불</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- // 취소 및 환불 규정 동의 팝업 -->

		<!-- 개인정보 제 3자 제공 동의 팝업 -->
		<div id="terms2" class="layerpop__wrap">
			<div class="layerpop__container" style="height: 95%;">
				<div class="layerpop__header">
					<p class="title">개인정보 제 3자 제공</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div class="layerpop__contents" style="height: 85%; overflow-y: scroll;">
					<?= $terms2; ?>
				</div>
			</div>
		</div>
		<!-- // 개인정보 제 3자 제공 동의 팝업 -->

		<!-- 토스트팝업 -->
		<div id="validationToast" class="toast__wrap">
			<div class="toast__container">
				<i class="ico ico-warning"></i>
				<p id="toastDetail"></p>
			</div>
		</div>
		<!-- //토스트팝업 -->

		<div id="agreeAllPopup" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">필수 이용 동의 항목에 모두 동의(체크)해주세요.</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary fnClosePop">확인</button>
					</div>
				</div>
			</div>
		</div>

		<!-- 알럿 팝업 -->
		<div id="passTodayAlert" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">당일 예약은 오후 11시 50분까지만 가능해요.</p>
						<p class="desc">
							날짜를 다시 선택해주세요.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary" onclick="goBack()">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<?php if ($passToday) : ?>
		<script>
			fnOpenLayerPop('passTodayAlert');
		</script>
	<?php endif; ?>

	<script>
		let widgets = null;
		let paymentButton = null;
		const appEnv = '<?= $_ENV['APP_ENV'] ?>';

		let clientKey = "test_gck_6BYq7GWPVvyRaeRQvXe73NE5vbo1";

		if (appEnv == 'production') {
			clientKey = "live_gck_mBZ1gQ4YVX4KEy9BMbqjVl2KPoqN";
		} else {
			clientKey = "test_gck_6BYq7GWPVvyRaeRQvXe73NE5vbo1";
		}

		main();

		async function main() {
			paymentButton = document.getElementById("payment-button");
			// ------  결제위젯 초기화 ------

			const tossPayments = TossPayments(clientKey);
			// 회원 결제
			const customerKey = '<?= !empty($user->user_customer_key) ? $user->user_customer_key : generateRandomKey(50); ?>';
			widgets = tossPayments.widgets({
				customerKey,
			});

			// ------ 주문의 결제 금액 설정 ------
			await widgets.setAmount({
				currency: "KRW",
				value: <?= $partner->total_sale_price ?>,
			});

			// var interval = '';

			var variantKey = 'FIRST';
			// if (interval > 90) {
			// 	variantKey = 'SECOND';
			// }

			await Promise.all([
				// ------  결제 UI 렌더링 ------
				widgets.renderPaymentMethods({
					selector: "#payment-method",
					variantKey: variantKey,
				}),
				// ------  이용약관 UI 렌더링 ------
				widgets.renderAgreement({
					selector: "#agreement",
					variantKey: "AGREEMENT"
				}),
			]);
		}

		let selectedCoupon = null; // 선택된 쿠폰
		let totalPrice = <?= $partner->total_sale_price; ?>;
		let discountedPrice = <?= $partner->total_sale_price; ?>;

		const partner = <?= json_encode($partner); ?>;
		const personnelJson = {};
		personnelJson['adult'] = <?= !empty($_GET['adult']) ? (int) $_GET['adult'] : 0; ?>;
		personnelJson['child'] = <?= !empty($_GET['child']) ? (int) $_GET['child'] : 0; ?>;
		personnelJson['infant'] = <?= !empty($_GET['infant']) ? (int) $_GET['infant'] : 0; ?>;
		personnelJson['childAge'] = <?= !empty($_GET['childAge']) ? $_GET['childAge'] : 'null'; ?>;
		personnelJson['infantMonth'] = <?= !empty($_GET['infantMonth']) ? $_GET['infantMonth'] : 'null'; ?>;

		let basicPrice = partner.total_basic_price;
		let salePrice = partner.total_sale_price;

		// 필드 요소 가져오기
		const reservationName = document.getElementById('reservationName');
		const reservationTel = document.getElementById('reservationTel');
		const reservationEmail = document.getElementById('reservationEmail');
		const visitName = document.getElementById('visitName');
		const visitTel = document.getElementById('visitTel');
		const visitEmail = document.getElementById('visitEmail');
		const agreeAll = document.getElementById('agreeAll');
		const visitOptions = document.getElementsByName('visit');
		const paymentNext = document.getElementById('paymentNext');

		// 유효성 검사 함수
		function validateInputs() {
			const phonePattern = /^[0-9]+$/;
			const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

			// 방문수단이 선택되었는지 확인
			const visitSelected = Array.from(visitOptions).some(option => option.checked);

			// 모든 필드가 채워지고 조건을 만족하는지 검사
			const isValid = reservationName.value.trim() !== '' &&
				phonePattern.test(reservationTel.value) &&
				emailPattern.test(reservationEmail.value) &&
				visitName.value.trim() !== '' &&
				phonePattern.test(visitTel.value) &&
				emailPattern.test(visitEmail.value) &&
				agreeAll.checked &&
				visitSelected;

			// 버튼 활성화 여부
			if (isValid) {
				// paymentNext.classList.remove('disabled');
				// paymentNext.removeAttribute('disabled');
			} else {
				// paymentNext.classList.add('disabled');
				// paymentNext.setAttribute('disabled', 'disabled');
			}
		}

		// 각 필드에 이벤트 리스너 추가
		[reservationName, reservationTel, reservationEmail, visitName, visitTel, visitEmail].forEach(input => {
			input.addEventListener('input', validateInputs);
		});
		Array.from(visitOptions).forEach(option => {
			option.addEventListener('change', validateInputs);
		});

		// 초기 호출로 상태 설정
		validateInputs();

		document.querySelectorAll(".coupon-list__con").forEach(couponElement => {
			couponElement.addEventListener("click", function(e) {
				e.preventDefault();

				// 기존 선택된 쿠폰 초기화
				if (selectedCoupon) {
					selectedCoupon.classList.remove("selected"); // 선택 상태 해제
				}

				// 현재 쿠폰이 선택된 경우 해제
				if (selectedCoupon === this) {
					selectedCoupon = null;
					updateDiscountUI(0); // 할인 금액 초기화
				} else {
					// 새로운 쿠폰 선택
					selectedCoupon = this;
					selectedCoupon.classList.add("selected"); // 선택 상태 표시

					const discountAmount = parseFloat(this.dataset.discountAmount);
					const minOrderPrice = parseFloat(this.dataset.minimumOrderPrice);

					if (totalPrice >= minOrderPrice) {
						updateDiscountUI(discountAmount); // 할인 적용
					} else {
						alert("최소 구매 금액을 충족하지 못했습니다.");
					}
				}
			});
		});

		async function updateDiscountUI(discountAmount) {
			const couponDiscountElement = document.getElementById("discountCouponPrice");
			const totalAmountElement = document.getElementById("totalFinalPrice");

			discountedPrice = totalPrice - discountAmount;

			couponDiscountElement.textContent = `-${discountAmount.toLocaleString()}원`;
			totalAmountElement.textContent = `${Math.max(0, discountedPrice).toLocaleString()}원`;

			await widgets.setAmount({
				currency: "KRW",
				value: discountedPrice,
			});

			// 버튼 텍스트 업데이트
			const paymentButton = document.getElementById("paymentNext");
			paymentButton.querySelector("span").textContent = `${Math.max(0, discountedPrice).toLocaleString()}원`;
		}

		document.getElementById('agreeAll').addEventListener('change', function() {
			const checked = this.checked;
			document.getElementById('agreeTerms1').checked = checked;
			document.getElementById('agreeTerms2').checked = checked;
			validateInputs();
		});

		function updateAgreeAll() {
			const agreeTerms1 = document.getElementById('agreeTerms1').checked;
			const agreeTerms2 = document.getElementById('agreeTerms2').checked;
			const allChecked = agreeTerms1 && agreeTerms2;
			document.getElementById('agreeAll').checked = allChecked;
			validateInputs();
		}

		document.getElementById('agreeTerms1').addEventListener('change', updateAgreeAll);
		document.getElementById('agreeTerms2').addEventListener('change', updateAgreeAll);

		// 예약자와 동일 체크박스의 클릭 이벤트 리스너 설정
		document.getElementById('sameUser').addEventListener('change', function() {
			const reservationUser = document.getElementById('reservationUser'); // 예약자 정보 섹션
			const visitUser = document.getElementById('visitUser'); // 이용자 정보 섹션

			if (this.checked) {
				// 예약자 정보 숨기기 (서서히 접히며 사라지게)
				reservationUser.style.height = reservationUser.scrollHeight + 'px';
				reservationUser.style.overflow = 'vhidden';
				setTimeout(() => {
					reservationUser.classList.add('vhidden');
					reservationUser.style.height = '0';
					reservationUser.style.opacity = '0';
				}, 10);

				// 이용자 정보에 예약자 정보 복사
				document.getElementById('visitName').value = document.getElementById('reservationName').value;
				document.getElementById('visitTel').value = document.getElementById('reservationTel').value;
				document.getElementById('visitEmail').value = document.getElementById('reservationEmail').value;

				validateInputs();
			} else {
				// 예약자 정보 다시 나타나게 (서서히 펼쳐지며 보이게)
				reservationUser.classList.remove('vhidden');
				reservationUser.style.height = reservationUser.scrollHeight + 'px';
				reservationUser.style.opacity = '1';
				setTimeout(() => {
					reservationUser.style.height = 'auto';
					reservationUser.style.overflow = '';
				}, 500);

				// 이용자 정보 초기화
				document.getElementById('visitName').value = '';
				document.getElementById('visitTel').value = '';
				document.getElementById('visitEmail').value = '';

				validateInputs();
			}
		});

		document.getElementById('paymentNext').addEventListener('click', async function(event) {
			event.preventDefault();

			const reservationName = document.getElementById('reservationName').value.trim();
			const reservationTel = document.getElementById('reservationTel').value.trim();
			const reservationEmail = document.getElementById('reservationEmail').value.trim();
			const visitName = document.getElementById('visitName').value.trim();
			const visitTel = document.getElementById('visitTel').value.trim();
			const visitEmail = document.getElementById('visitEmail').value.trim();

			const reservationNameElement = document.getElementById('reservationName');
			const reservationTelElement = document.getElementById('reservationTel');
			const reservationEmailElement = document.getElementById('reservationEmail');
			const visitNameElement = document.getElementById('visitName');
			const visitTelElement = document.getElementById('visitTel');
			const visitEmailElement = document.getElementById('visitEmail');
			const agreeAllElement = document.getElementById('agreeAll');

			reservationNameElement.classList.remove('invalid');
			reservationTelElement.classList.remove('invalid');
			reservationEmailElement.classList.remove('invalid');
			visitNameElement.classList.remove('invalid');
			visitTelElement.classList.remove('invalid');
			visitEmailElement.classList.remove('invalid');
			agreeAllElement.classList.remove('invalid');

			let valid = true;

			if (!reservationName) {
				document.getElementById('toastDetail').innerText = '예약자 성명을 입력해 주세요.';
				fnToastPop('validationToast');
				valid = false;

				const rect = reservationNameElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				reservationNameElement.focus();

				reservationNameElement.classList.add('invalid');

				return;
			}

			if (!visitName) {
				document.getElementById('toastDetail').innerText = '이용자 성명을 입력해 주세요.';
				fnToastPop('validationToast');
				valid = false;

				const rect = visitNameElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				visitNameElement.focus();

				visitNameElement.classList.add('invalid');

				return;
			}

			// 전화번호가 숫자로만 구성되어 있는지 확인
			const phonePattern = /^[0-9]+$/;
			if (!phonePattern.test(reservationTel)) {
				document.getElementById('toastDetail').innerText = '전화번호는 숫자만 입력해 주세요.';
				fnToastPop('validationToast');
				valid = false;

				const rect = reservationTelElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				reservationTelElement.focus();

				reservationTelElement.classList.add('invalid');

				return;
			}

			if (!phonePattern.test(visitTel)) {
				document.getElementById('toastDetail').innerText = '전화번호는 숫자만 입력해 주세요.';
				fnToastPop('validationToast');
				valid = false;

				const rect = visitTelElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				visitTelElement.focus();

				visitTelElement.classList.add('invalid');

				return;
			}

			// 이메일 형식 확인
			const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailPattern.test(reservationEmail)) {
				document.getElementById('toastDetail').innerText = '이메일 주소가 유효하지 않습니다.';
				fnToastPop('validationToast');
				valid = false;

				const rect = reservationEmailElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				reservationEmailElement.focus();

				reservationEmailElement.classList.add('invalid');

				return;
			}

			if (!emailPattern.test(visitEmail)) {
				document.getElementById('toastDetail').innerText = '이메일 주소가 유효하지 않습니다.';
				fnToastPop('validationToast');
				valid = false;

				const rect = visitEmailElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				visitEmailElement.focus();

				visitEmailElement.classList.add('invalid');

				return;
			}

			// 필수 필드 확인
			if (!reservationName || !reservationTel || !reservationEmail || !visitName || !visitTel || !visitEmail) {
				document.getElementById('toastDetail').innerText = '모든 필수 정보를 입력해 주세요.';
				fnToastPop('validationToast');
				valid = false;
			}

			// 전체 동의 확인
			const agreeAll = document.getElementById('agreeAll').checked;
			if (!agreeAll) {
				fnOpenLayerPop('agreeAllPopup');
				valid = false;

				const rect = agreeAllElement.getBoundingClientRect();
				const absoluteTop = rect.top + window.pageYOffset;

				window.scrollTo({
					top: absoluteTop - 100,
					behavior: 'smooth',
				});

				agreeAllElement.focus();

				return;
			}

			let selectedVisitOption = null;

			for (const option of visitOptions) {
				if (option.checked) {
					selectedVisitOption = option.value;
					break;
				}
			}

			// 조건을 모두 만족할 경우 다음 단계 진행
			if (valid) {
				try {
					const formData = new FormData();
					formData.append(`payment[payment_total_amount]`, basicPrice);
					formData.append(`payment[payment_sale_amount]`, discountedPrice);
					formData.append(`payment[used_point_amount]`, 0);
					formData.append(`payment[payment_type]`, 'NORMAL');
					formData.append(`payment[reservation_name]`, reservationName);
					formData.append(`payment[reservation_phone]`, reservationTel);
					formData.append(`payment[reservation_email]`, reservationEmail);
					formData.append(`payment[visit_name]`, visitName);
					formData.append(`payment[visit_phone]`, visitTel);
					formData.append(`payment[visit_email]`, visitEmail);
					formData.append(`payment[visit_way]`, selectedVisitOption);

					formData.append(`paymentItems[0][partner_idx]`, partner.partner_idx);
					formData.append(`paymentItems[0][product_idx]`, partner.room_rateplan_idx);
					formData.append(`paymentItems[0][product_category]`, 'stay');
					formData.append(`paymentItems[0][product_type]`, 'room_rateplan');
					formData.append(`paymentItems[0][product_name]`, partner.partner_name);
					formData.append(`paymentItems[0][product_partner_name]`, partner.partner_name);
					formData.append(`paymentItems[0][product_detail_name]`, `${partner.rateplan_name ? `[${partner.rateplan_name}] ` : ''}${partner.room_name}`);
					formData.append(`paymentItems[0][datewise_product_data]`, JSON.stringify(partner.datewise_product_data));
					formData.append(`paymentItems[0][item_basic_price]`, partner.total_basic_price);
					formData.append(`paymentItems[0][item_sale_price]`, partner.total_sale_price);
					formData.append(`paymentItems[0][item_origin_sale_price]`, partner.total_sale_price);
					formData.append(`paymentItems[0][quantity]`, <?= $_GET['roomQuantity']; ?>);
					formData.append(`paymentItems[0][start_date]`, '<?= $_GET['startDate']; ?>');
					formData.append(`paymentItems[0][end_date]`, '<?= $_GET['endDate']; ?>');
					formData.append(`paymentItems[0][free_cancel_date]`, '<?= !empty($refundDate) ? $refundDate->format('Y-m-d H:i:s') : ''; ?>');
					formData.append(`paymentItems[0][refundable]`, <?= $partner->rateplan_is_refundable; ?>);
					formData.append(`paymentItems[0][reservation_personnel]`, JSON.stringify(personnelJson));
					formData.append(`paymentItems[0][thirdparty_type]`, partner.rateplan_thirdparty);

					if (selectedCoupon) {
						formData.append("selectedCoupon[coupon_user_idx]", selectedCoupon.dataset.couponUserIdx);
						formData.append("selectedCoupon[coupon_name]", selectedCoupon.dataset.couponName);
						formData.append("selectedCoupon[discount_amount]", selectedCoupon.dataset.discountAmount);
					}

					const response = await fetch("/api/payment/prepare", {
						method: "POST",
						body: formData,
						cache: "no-cache",
					});

					// 응답 상태 확인
					if (!response.ok) {
						const errorData = await response.json(); // 서버에서 에러 메시지를 반환했을 경우 확인
						console.error("API 에러:", errorData);
						document.getElementById('toastDetail').innerText = errorData.message || "결제 준비 중 문제가 발생했습니다.";
						fnToastPop('validationToast');
						return; // 문제가 있으면 다음 단계로 넘어가지 않음
					}

					const responseData = await response.json();

					// ------ '결제하기' 버튼 누르면 결제창 띄우기 ------
					await widgets.requestPayment({
						orderId: responseData.orderId,
						orderName: responseData.orderName,
						successUrl: window.location.origin + "/payment/blank/success",
						failUrl: window.location.origin + "/payment/fail",
						customerEmail: responseData.customerEmail,
						customerName: responseData.customerName,
						customerMobilePhone: responseData.customerMobilePhone,
					});
				} catch (error) {
					console.error("결제 준비 요청 중 오류:", error);
					document.getElementById('toastDetail').innerText = "결제 준비 요청 중 문제가 발생했습니다. 다시 시도해주세요.";
					fnToastPop('validationToast');
				}
			}
		});

		// $('.input-point input').on('input', function() {
		// 	if ($(this).val()) {
		// 		$(this).addClass('active');
		// 	} else {
		// 		$(this).removeClass('active');
		// 	}
		// });
		// if ($('.input-point input').val()) {
		// 	$('.input-point input').addClass('active');
		// }
	</script>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

	<?php if ($_ENV['ANALYTICS_ENV'] == 'production' || $_ENV['ANALYTICS_ENV'] == 'staging') : ?>
		<script>
			document.addEventListener("DOMContentLoaded", () => {
				window.dataLayer.push({
					event: "begin_checkout",
					page_type: "product"
				});
			});
		</script>
	<?php endif; ?>

    <!-- NAVER 결제시작(begin_checkout) SCRIPT -->
    <script type="text/javascript">
    if (window.wcs) {
        if(!wcs_add) var wcs_add = {};
        wcs_add["wa"] = "s_2744685fd307";
        var _conv = {};
        _conv.type = 'begin_checkout';
        _conv.items = [{
                id: <?= $partner->room_idx; ?>,
                name: "<?= $partner->room_name; ?>",
                payAmount: "<?= $partner->total_sale_price; ?>"
            }
        ];
        wcs.trans(_conv);
    }
    </script>

</body>

</html>