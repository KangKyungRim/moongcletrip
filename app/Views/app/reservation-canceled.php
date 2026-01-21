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
$reservations = $data['reservations'];
$payment = $data['payment'];

$paymentItemIdxArray = array_map(function ($reservation) {
	return $reservation->payment_item_idx;
}, $reservations);

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

			<?php foreach ($reservations as $reservation) : ?>
				<?php if ($reservation->product_category === 'stay') : ?>
					<!-- 결제 정보 타이틀 -->
					<div class="payment__top">
						<div class="reservation-top">
							<div class="flex-center">
								<div class="badge badge__lavender">숙박</div>
								<p class="ft-xxs">취소 완료</p>
							</div>
							<p class="ft-xxs txt-gray">예약번호 <span><?= $reservation->reservation_number; ?></span></p>
						</div>
						<div class="tit__wrap">
							<p class="sub-name"><?= $reservation->product_partner_name; ?></p>
							<p class="name"><?= $reservation->product_detail_name; ?></p>
						</div>
						<div class="date-box">
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
							</div>
							<div class="end-date">
								<p class="tit">체크아웃</p>
								<p class="date"><?= $endDate->format('Y-m-d'); ?> (<?= $endDayOfWeekKorean; ?>)</p>
							</div>
						</div>
						<p class="total-price"><?= number_format($reservation->item_sale_price); ?>원</p>
					</div>
					<!-- //결제 정보 타이틀 -->
				<?php endif; ?>
			<?php endforeach; ?>

			<hr class="divide">
			<!-- 예약 정보  -->
			<div class="reservation__con">

				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">예약자 정보</p>
					</div>
					<div class="reservation-list__con">
						<ul>
							<li>
								<p class="tit">성명</p>
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
								<p class="tit">성명</p>
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
						<?php
						$createdAt = new DateTime($payment->created_at);
						$dayOfWeek = date('l', strtotime($payment->created_at));
						$createdDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);

						$updatedAt = new DateTime($payment->updated_at);
						$dayOfWeek = date('l', strtotime($payment->updated_at));
						$updatedDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);
						?>
						<p class="ft-xl">결제 정보</p>
						<p class="sub-desc">결제일시 <?= $createdAt->format('Y.m.d'); ?> (<?= $createdDayOfWeekKorean; ?>) <?= $createdAt->format('H:i'); ?></p>
						<p class="sub-desc">취소일시 <?= $updatedAt->format('Y.m.d'); ?> (<?= $updatedDayOfWeekKorean; ?>) <?= $updatedAt->format('H:i'); ?></p>
					</div>
					<div class="reservation-list__con">
						<ul>
							<?php
							$basicPrice = $payment->payment_total_amount;
							$salePrice = $payment->payment_sale_amount;
							$refundAmount = $payment->refund_amount;
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
								<p class="con"><?= number_format($salePrice); ?>원</p>
							</li>
							<li>
								<p class="tit">환불 금액</p>
								<p class="con total-price"><?= number_format($refundAmount); ?>원</p>
							</li>
							<!-- <li>
								<p class="tit">환불 포인트</p>
								<p class="con total-point">2,000원</p>
							</li> -->
							<li>
								<p class="tit">결제 수단</p>
								<p class="con">신용/체크 카드</p>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- //예약 정보  -->
		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

</body>

</html>