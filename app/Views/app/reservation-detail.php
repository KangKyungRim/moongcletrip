<!DOCTYPE html>
<html lang="ko">

<?php
// 전화번호 가운데 4자리를 '*'로 마스킹하는 함수
function maskPhoneNumber($phone)
{
    if (empty($phone)) {
        return '';
    }
    // 정규식을 사용하여 '010-1234-5678' 또는 '01012345678' 같은 형식의 중간 4자리를 변경
    return preg_replace('/(\d{3}-?)(\d{4})(-?\d{4})/', '$1****$3', $phone);
}

// 이메일 아이디의 뒤에서 3자리를 '*'로 마스킹하는 함수
function maskEmail($email)
{
    if (empty($email) || !strpos($email, '@')) {
        return '';
    }
    // 정규식을 사용하여 '@' 앞의 1~3자리 문자를 '*'로 변경
    return preg_replace('/.{1,3}(?=@)/', '***', $email);
}


$deviceType = $data['deviceType'];
$user = $data['user'];
$reservation = $data['reservation'];
$payment = $data['payment'];
$cancelRules = $data['cancelRules'];

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
				<button class="btn-back" onclick="gotoReservationList()"><span class="blind">뒤로가기</span></button>
				<h2 class="header-tit__center">예약 상세</h2>
			</div>
		</header>

		<div class="container__wrap payment__wrap reservation__wrap">
            <!-- 결제 정보 타이틀 -->
            <div class="payment__top">
                <div class="reservation-top">
                    <div class="flex-center">
                        <div class="badge badge__lavender"><?= getProductCategory($reservation->product_category); ?></div>
                        <p class="ft-xxs"><?= getProductStatus($reservation->reservation_status); ?></p>
                    </div>
                    <p class="ft-xxs txt-gray">예약번호 <span><?= $reservation->reservation_number; ?></span></p>
                </div>
                <div class="product-name"><?= $reservation->product_partner_name; ?></div>
                <div class="flex__box">
                    <div class="thumb__img large">
                        <img src="<?= $reservation->image_path; ?>" alt="대표 이미지">
                    </div>
                    <div class="tit__wrap thumb__con">
                        <p class="name" style="padding-bottom: 0.8rem;"><?= $reservation->product_detail_name; ?></p> 
                        <p class="detail-sub">
                            <?php
                            $stayRating = '';
                            $stayTags = explode(':-:', $reservation->tags);

                            if (!empty($stayTags)) {
                                foreach ($stayTags as $stayTag) {
                                    if (in_array($stayTag, ['1성', '2성', '3성', '4성', '5성'])) {
                                        $stayRating = $stayTag;
                                        break;
                                    }
                                }
                            }

                            // 성급 정보가 있으면 구분자 추가
                            $address = $reservation->partner_address1;
                            $displayText = $stayRating ? "$address | $stayRating" : $address;
                            ?>
                            <span><?= $displayText; ?></span>
                        </p>
                    </div>
                    <?php 
                        $benefits_raw = $reservation->product_benefits;
                        $decoded_once = json_decode($benefits_raw, true);

                        if (is_string($decoded_once)) {
                            $benefits = json_decode($decoded_once, true);
                        } else {
                            $benefits = $decoded_once;
                        }

                        $hasContent = false;
                        ob_start(); // 출력 버퍼링 시작

                        if (is_array($benefits)) {
                            foreach ($benefits as $benefit) {
                                if (!empty($benefit['benefit_name'])) {
                                    echo '<li>' . htmlspecialchars($benefit['benefit_name']) . '</li>';
                                    $hasContent = true;
                                }
                            }
                        }

                        $benefitList = ob_get_clean(); // 출력된 li를 변수로 저장

                        if ($hasContent):
                    ?>
                        <hr class="divide__small" style="margin-top: 2rem;">
                        <div class="thumb__gift" style="margin-top: 2rem;">
                            <ul>
                                <?= $benefitList ?>
                            </ul>
                        </div>
                    <?php endif; ?>
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
                            <?php if (!empty($data['room']->views)) : ?>
                                <?php $roomViews = explode(':-:', $data['room']->views); ?>
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
                            $bedTypes = json_decode($data['room']->room_bed_type, true);

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
                                <p class="con"><?= $data['room']->room_standard_person; ?>인 기준 / 최대 <?= $data['room']->room_max_person; ?>인</p>
                            </li>
                            <hr class="divide__small">
                            <li>
                                <p class="tit">객실 수량</p>
                                <p class="con"><?= $reservation->quantity; ?>개</p>
                            </li>
                            <?php 
                            $personnel = json_decode($reservation->reservation_personnel, true);
                            $people = [];

                            // 성인 추가
                            if (!empty($personnel['adult'])) {
                                $people[] = "성인 {$personnel['adult']}명";
                            }

                            // 아동 추가 (나이 포함)
                            if (!empty($personnel['child'])) {
                                $ages = !empty($personnel['childAge']) ? ' ('.implode(', ', $personnel['childAge']).'세)' : '';
                                $people[] = "아동 {$personnel['child']}명{$ages}";
                            }

                            // 유아 추가 (개월 수 포함)
                            if (!empty($personnel['infant'])) {
                                $months = !empty($personnel['infantMonth']) ? ' ('.implode(', ', $personnel['infantMonth']).'개월)' : '';
                                $people[] = "유아 {$personnel['infant']}명{$months}";
                            }

                            // 인원 수에 따른 스타일 조정
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
                                <?php
                                $startDate = new DateTime($reservation->start_date);
                                $dayOfWeek = date('l', strtotime($reservation->start_date));
                                $startDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

                                $endDate = new DateTime($reservation->end_date);
                                $dayOfWeek = date('l', strtotime($reservation->end_date));
                                $endDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

                                $interval = $startDate->diff($endDate);
                                ?>
                                <p class="tit">투숙 기간</p>
                                <p class="con"><?= $interval->days; ?>박</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="payment__top" style="padding: 0px;">
                    <div class="date-box" style="margin-top: 2.4rem;">
                        <?php
                        $startDate = new DateTime($reservation->start_date);
                        $dayOfWeek = date('l', strtotime($reservation->start_date));
                        $startDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

                        $endDate = new DateTime($reservation->end_date);
                        $dayOfWeek = date('l', strtotime($reservation->end_date));
                        $endDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);
                        ?>
                        <div class="start-date">
                            <p class="tit">체크인</p>
                            <p class="date"><?= $startDate->format('Y-m-d'); ?> (<?= $startDayOfWeekKorean; ?>)</p>
                            <p class="time"><?= substr($reservation->stay_checkin_rule, 0, 5); ?> 부터</p>
                        </div>
                        <div class="end-date">
                            <p class="tit">체크아웃</p>
                            <p class="date"><?= $endDate->format('Y-m-d'); ?> (<?= $endDayOfWeekKorean; ?>)</p>
                            <p class="time"><?= substr($reservation->stay_checkout_rule, 0, 5); ?> 까지</p>
                        </div>
                    </div>
                    <p class="total-price"><?= number_format($reservation->item_sale_price) ?>원</p>
                    <div class="reservation-bottom">
                        <!-- <?php
                        $freeCancelDate = new DateTime($reservation->free_cancel_date);
                        ?>
                        <?php if ($reservation->refundable) : ?>
                            <p class="txt-primary"><?= $freeCancelDate->format('Y-m-d H:i'); ?>까지 무료취소 가능<br>(여행 현지 시간 기준)</p>
                        <?php else : ?>
                            <p class="txt-primary">취소가 불가능한 상품입니다.</p>
                        <?php endif; ?>

                        <?php if ($reservation->refundable) : ?>
                            <?php if ($reservation->reservation_status === 'confirmed') : ?>
                                <a href="/my/cancel-reservation/<?= $reservation->payment_idx; ?>" class="btn-reservation btn-reservation__cancle">예약 취소</a>
                            <?php elseif ($reservation->reservation_status === 'completed') : ?>
                            <?php else : ?>
                            <?php endif; ?>
                        <?php endif; ?> -->

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
                        <?php if ($reservation->refundable) : ?>
                            <p class="txt-primary">
                                <?php if ($minDay100Rule): ?>
                                    체크인 <?= htmlspecialchars($minDay100Rule->cancel_rules_day); ?>일 전 <?= htmlspecialchars($minDay100Rule->cancel_rules_time); ?>까지 무료취소 가능<br>(여행 현지 시간 기준)
                                <?php endif; ?>
                            </p>
                        <?php else : ?>
                            <p class="txt-primary">취소가 불가능한 상품입니다.</p>
                        <?php endif; ?>

                        <?php if ($reservation->refundable) : ?>
                            <?php if ($reservation->reservation_status === 'confirmed') : ?>
                                <a href="/my/cancel-reservation/<?= $reservation->payment_idx; ?>" class="btn-reservation btn-reservation__cancle">예약 취소</a>
                            <?php elseif ($reservation->reservation_status === 'completed') : ?>
                            <?php else : ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- //예약 정보 -->
			<hr class="divide">
			<!-- 예약자 정보  -->
			<div class="reservation__con">
				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">예약자 정보</p>
					</div>
					<div class="reservation-list__con">
						<ul>
							<li>
								<p class="tit">성함</p>
								<p class="con"><?= $payment->reservation_name; ?></p>
							</li>
							<li>
								<p class="tit">전화번호</p>
								<p class="con"><?= maskPhoneNumber($payment->reservation_phone); ?></p>
							</li>
							<li>
								<p class="tit">이메일</p>
								<p class="con"><?= maskEmail($payment->reservation_email); ?></p>
							</li>
						</ul>
					</div>
				</div>
				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">이용자 정보</p>
					</div>
					<div class="reservation-list__con">
						<ul>
							<li>
								<p class="tit">성함</p>
								<p class="con"><?= $payment->visit_name; ?></p>
							</li>
							<li>
								<p class="tit">전화번호</p>
								<p class="con"><?= maskPhoneNumber($payment->visit_phone); ?></p>
							</li>
							<li>
								<p class="tit">이메일</p>
								<p class="con"><?= maskEmail($payment->visit_email); ?></p>
							</li>
							<li>
								<p class="tit">방문수단</p>
								<p class="con"><?= getVisitWay($payment->visit_way); ?></p>
							</li>
						</ul>
					</div>
				</div>
				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">결제 정보</p>
						<?php
						$createdAt = new DateTime($payment->created_at);
						$dayOfWeek = date('l', strtotime($payment->created_at));
						$createdDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);
						?>
						<p class="sub-desc">결제일시 <?= $createdAt->format('Y.m.d'); ?> (<?= $createdDayOfWeekKorean; ?>) <?= $createdAt->format('H:i'); ?></p>
					</div>
					<div class="reservation-list__con">
						<ul>
							<?php
							$basicPrice = $payment->payment_total_amount;
							$salePrice = $payment->payment_sale_amount;
							$couponDiscount = $payment->coupon_discount_amount;
							?>
							<li>
								<p class="tit">일반 요금</p>
								<p class="con"><?= number_format($basicPrice); ?>원</p>
							</li>
							<li>
								<p class="tit">뭉클 특가</p>
								<p class="con">-<?= number_format($basicPrice - $salePrice - $couponDiscount); ?>원</p>
							</li>
							<li>
								<p class="tit">쿠폰 할인</p>
								<p class="con">-<?= number_format($couponDiscount); ?>원</p>
							</li>
							<!-- <li>
								<p class="tit">포인트 할인</p>
								<p class="con">-1,000원</p>
							</li> -->
						</ul>
						<hr class="divide__small">
						<ul>
							<li>
								<p class="tit">총 결제 금액</p>
								<p class="con total-price"><?= number_format($salePrice); ?>원</p>
							</li>
							<li>
								<p class="tit">결제 수단</p>
								<p class="con">신용/체크 카드</p>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- //예약자 정보  -->
		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<script>
		// sessionStorage.setItem('previousPage', window.location.href);
	</script>

</body>

</html>