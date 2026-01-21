<!DOCTYPE html>
<html lang="ko">

<?php

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
				<h2 class="header-tit__center">예약 취소</h2>
			</div>
		</header>

		<div class="container__wrap payment__wrap reservation__wrap">

			<?php foreach ($reservations as $reservation) : ?>
				<?php if ($reservation->product_category === 'stay') : ?>
					<?php
					$refundPolicy = json_decode($reservation->refund_policy);

					$today = new DateTime();
					$nextPercent = null;
					$nextIndex = null;

					foreach ($refundPolicy as $index => $refund) {
						$refundDate = new DateTime($refund->until);

						if ($refundDate > $today) {
							$nextPercent = $refund->percent;
							$nextIndex = $index;
							break;
						}
					}

					?>
					<!-- 결제 정보 타이틀 -->
					<div class="payment__top">
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
						<div class="reservation-list__con">
							<ul>
								<?php
								$createdAt = new DateTime($payment->created_at);
								?>
								<li>
									<p class="tit">예약일</p>
									<p class="con"><?= $createdAt->format('Y.m.d H:i'); ?></p>
								</li>
								<li>
									<p class="tit">예약번호</p>
									<p class="con"><?= $reservation->reservation_number; ?></p>
								</li>
								<li>
									<p class="tit">성명</p>
									<p class="con"><?= $payment->reservation_name; ?></p>
								</li>
							</ul>
							<?php
							$freeCancelDate = new DateTime($reservation->free_cancel_date);
							$dayOfWeek = date('l', strtotime($reservation->free_cancel_date));
							$freeDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);
							?>
							<!-- <?php if ($reservation->refundable) : ?>
								<p class="sub-desc txt-primary"><?= $freeCancelDate->format('Y-m-d'); ?> (<?= $freeDayOfWeekKorean; ?>) <?= $freeCancelDate->format('H:i'); ?>까지 무료취소 가능 (숙박시설 현지 시간 기준)</p>
							<?php else : ?>
								<p class="sub-desc txt-primary">취소가 불가능한 상품입니다.</p>
							<?php endif; ?> -->
						</div>
					</div>
					<!-- //결제 정보 타이틀 -->
				<?php endif; ?>
			<?php endforeach; ?>

			<hr class="divide">
			<!-- 예약 정보  -->
			<div class="reservation__con">

				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">취소 사유</p>
					</div>
					<div class="select-list fnOpenPop" data-name="popupCancle">
						<p id="selectedReasonText">취소 사유를 선택해주세요</p>
						<i class="ico ico-arrow__down"></i>
					</div>
				</div>
				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">취소 금액 정보</p>
					</div>
					<div class="reservation-list__con">
						<ul>
							<?php
							$basicPrice = $payment->payment_total_amount;
							$salePrice = $payment->payment_sale_amount;
							$couponDiscount = $payment->coupon_discount_amount;
							?>
							<li>
								<p class="tit">총 결제 금액</p>
								<p class="con"><?= number_format($salePrice); ?>원</p>
							</li>
							<li>
								<p class="tit">예상 취소 금액</p>
								<p class="con total-price"><?= number_format($salePrice * ($nextPercent / 100)); ?>원</p>
							</li>
							<li>
								<p class="tit">취소 수단</p>
								<p class="con">신용/체크 카드</p>
							</li>
						</ul>
					</div>
				</div>
				<div class="reservation-list__wrap">
					<div class="tit__wrap">
						<p class="ft-xl">취소 및 환불 규정</p>
					</div>
					<div class="bullet__wrap">
						<ul>
							<?php foreach ($refundPolicy as $policy) : ?>
								<?php $until = new DateTime($policy->until); ?>
								<?php if ($policy->percent != 0) : ?>
									<li><?= $until->format('Y-m-d H:i'); ?> 이전 취소 시 <?= $policy->percent; ?>% 환불</li>
								<?php else : ?>
									<li>이후 취소 시 <?= $policy->percent; ?>% 환불</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
			<!-- //예약 정보  -->
			<?php if ($reservation->refundable) : ?>
				<?php if ($salePrice * ($nextPercent / 100) > 0) : ?>
					<div class="bottom-fixed__wrap">
						<div class="btn__wrap">
							<button id="sendCancelButton" class="btn-full__primary disabled" disabled>예약 취소하기</button>
						</div>
					</div>
				<?php else : ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<!-- 바텀 팝업(취소 사유) 디자인 없어서 나이선택 팝업 동일하게 작업함 -->
		<div id="popupCancle" class="layerpop__wrap">
			<div class="layerpop__container">
				<div class="layerpop__header">
					<p class="title">취소 사유</p>
					<a class="fnClosePop"><i class="ico ico-close"></i></a>
				</div>
				<div class="layerpop__contents">
					<div class="select__wrap type-list single-select">
						<ul id="reasonList">
							<li><a data-reason="personal">개인 사유</a></li>
							<li><a data-reason="seller">판매자 사유</a></li>
							<li><a data-reason="other">기타</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- //바텀 팝업(취소 사유) -->

		<!-- 알럿 팝업 -->
		<div id="cancelFail" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">취소 진행 중 문제가 발생했습니다.</p>
						<p class="desc">
							잠시 후 다시 시도해주시기 바랍니다.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary" onclick="fnCloseLayerPop('cancelFail')">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->

		<!-- 알럿 팝업 -->
		<div id="cancelSuccess" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">취소를 완료했습니다.</p>
						<p class="desc">
							취소 상세 내역을 확인해주세요.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary" onclick="gotoCancelDetail(<?= $payment->payment_idx ?? 0; ?>)">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //알럿 팝업 -->

		<!-- 토스트팝업 -->
		<div id="validationToast" class="toast__wrap">
			<div class="toast__container">
				<i class="ico ico-warning"></i>
				<p id="toastDetail">취소 사유를 선택해주세요.</p>
			</div>
		</div>
		<!-- //토스트팝업 -->
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<div id="loader" class="loader" style="display: none;">
		<div class="spinner"></div>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const reasonList = document.getElementById("reasonList");
			const sendCancelButton = document.getElementById("sendCancelButton");
			const selectedReasonText = document.getElementById("selectedReasonText");

			let reasonCode = '';

			reasonList.addEventListener("click", function(event) {
				event.preventDefault();

				const target = event.target;

				if (target.tagName.toLowerCase() === "a") {
					const reasonText = target.textContent;
					reasonCode = target.getAttribute("data-reason");

					selectedReasonText.textContent = reasonText;

					document.querySelector(".fnClosePop").click();

					sendCancelButton.disabled = false;
					sendCancelButton.classList.remove('disabled');
				}
			});

			sendCancelButton.addEventListener("click", function(event) {
				event.preventDefault();

				if (!reasonCode) {
					fnToastPop('validationToast');
					return;
				}

				document.getElementById('loader').style.display = 'flex';

				fetch("/api/payment/cancel", {
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: JSON.stringify({
							reason: reasonCode,
							cancelPaymentIdx: <?= $payment->payment_idx ?? ''; ?>,
							cancelItems: <?= json_encode($paymentItemIdxArray); ?>
						}),
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							document.getElementById('loader').style.display = 'none';
							fnOpenLayerPop('cancelSuccess');
						} else {
							document.getElementById('loader').style.display = 'none';
						}
					})
					.catch(error => {
						document.getElementById('loader').style.display = 'none';
						fnOpenLayerPop('cancelFail');
					});
			});
		});
	</script>

</body>

</html>