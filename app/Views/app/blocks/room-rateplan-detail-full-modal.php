<?php

$checkin = $stay->stay_checkin_rule;
$checkout = $stay->stay_checkout_rule;

$minCancelRuleDay = $cancelRule->cancel_rules_day;
$minCancelRuleTime = explode(':', $cancelRule->cancel_rules_time);

$startDateObj = new DateTime($startDate);

$freeCancelUntilDate = clone $startDateObj;
$freeCancelUntilDate->modify("-{$minCancelRuleDay} days");

if (!empty($cancelRule->cancel_rules_time)) {
	$freeCancelUntilDate->setTime($minCancelRuleTime[0], $minCancelRuleTime[1], $minCancelRuleTime[2]);
} else {
	$freeCancelUntilDate->setTime(23, 50);
}

$currentDate = new DateTime('now', new DateTimeZone('Asia/Seoul'));

$isFreeCancelAvailable = $currentDate <= $freeCancelUntilDate;

$dayNames = [
	'Monday'    => '월',
	'Tuesday'   => '화',
	'Wednesday' => '수',
	'Thursday'  => '목',
	'Friday'    => '금',
	'Saturday'  => '토',
	'Sunday'    => '일',
];

$dayOfWeek = $dayNames[$freeCancelUntilDate->format('l')];
$formattedDate = $freeCancelUntilDate->format('Y-m-d') . "({$dayOfWeek}) " . $freeCancelUntilDate->format('H:i');

if (
	$roomRateplan[0]->rateplan_name == '[Room only]'
	|| $roomRateplan[0]->rateplan_name == '[회원특가] Room only'
	|| $roomRateplan[0]->rateplan_name == 'room only'
	|| $roomRateplan[0]->rateplan_name == 'standalone'
	|| $roomRateplan[0]->rateplan_name == '룸온리'
) {
	$roomRateplan[0]->rateplan_name = $room->room_name;
}

?>

<div class="product-detail__wrap room-detail__wrap" style="max-height: 80vh; overflow-y: auto;">
	<!-- 상품 타이틀 -->
	<div class="product-detail__tit">
		<div class="product-tit">
			<p class="product-name"><?= $roomRateplan[0]->rateplan_name; ?></p>
		</div>

		<div style="padding: 1rem 0rem;">
			<?php if (empty($roomRateplan[0]->rateplan_is_refundable)) : ?>
				<p class="txt-red-warning" style="font-weight: bold;">환불 불가</p>
			<?php else : ?>
				<?php if ($isFreeCancelAvailable) : ?>
					<span style="display: flex; align-items: center; gap: 1rem;">
						<p class="txt-primary-warning" style="font-weight: bold;">무료취소 가능</p>
						<p class="txt-black" style="font-size: 1.2rem;"><?= $formattedDate; ?>까지 (현지시간 기준)</p>
					</span>
				<?php else : ?>
					<span style="display: flex; align-items: center; gap: 1rem;">
						<p class="txt-red-warning" style="font-weight: bold;">무료취소 불가</p>
					</span>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<?php if (!empty($roomRateplan[0]->benefits)) : ?>
			<?php $benefits = json_decode($roomRateplan[0]->benefits); ?>
			<div class="thumb__wrap" style="margin-top: 2rem;">
				<div class="thumb__gift">
					<ul>
						<?php foreach ($benefits as $benefit) : ?>
							<li><?= $benefit->benefit_name; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>

		<?php if (!empty($roomRateplan[0]->rateplan_description)) : ?>
			<?php if ($roomRateplan[0]->rateplan_description != 'standalone') : ?>
				<div class="bullet__wrap" style="margin-top: 3rem;">
					<p class="title">패키지 정보</p>
					<div class="room-type__con">
						<div class="rateplan-detail-info"><?= $roomRateplan[0]->rateplan_description; ?></div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if (!empty($roomRateplan[0]->benefits)) : ?>
			<p class="txt-warning mt16">뭉클딜은 상황에 따라 조기종료 될 수 있습니다.</p>
		<?php endif; ?>

		<?php if (!empty($roomRateplan[0]->rateplan_has_breakfast) || !empty($roomRateplan[0]->rateplan_has_lunch) || !empty($roomRateplan[0]->rateplan_has_dinner)) : ?>
			<div class="bullet__wrap" style="margin-top: 3rem;">
				<p class="title">기본 정보</p>
				<div class="room-type__con">
					<?php if (!empty($roomRateplan[0]->rateplan_has_breakfast)) : ?>
						<div class="rateplan-detail-info txt-primary-check">조식 포함</div>
					<?php endif; ?>
					<?php if (!empty($roomRateplan[0]->rateplan_has_lunch)) : ?>
						<div class="rateplan-detail-info txt-primary-check">중식 포함</div>
					<?php endif; ?>
					<?php if (!empty($roomRateplan[0]->rateplan_has_dinner)) : ?>
						<div class="rateplan-detail-info txt-primary-check">석식 포함</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($roomRateplan[0]->rateplan_stay_min > 1 || $roomRateplan[0]->rateplan_stay_max != 0 || !empty($roomRateplan[0]->rateplan_sales_from) || !empty($roomRateplan[0]->rateplan_sales_to)) : ?>
			<div class="bullet__wrap" style="margin-top: 3rem;">
				<p class="title">추가 정보</p>
				<div class="room-type__con">
					<?php if (!empty($roomRateplan[0]->rateplan_sales_from) || !empty($roomRateplan[0]->rateplan_sales_to)) : ?>
						<?php
						$startDayOfWeekKorean = '';
						if (!empty($roomRateplan[0]->rateplan_sales_from)) {
							$dayOfWeek = date('l', strtotime($roomRateplan[0]->rateplan_sales_from));
							$startDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);
							$startDayOfWeekKorean = date('y.m.d', strtotime($roomRateplan[0]->rateplan_sales_from)) . '(' . $startDayOfWeekKorean . ')';
						}

						$endDayOfWeekKorean = '';
						if (!empty($roomRateplan[0]->rateplan_sales_from)) {
							$dayOfWeek = date('l', strtotime($roomRateplan[0]->rateplan_sales_to));
							$endDayOfWeekKorean = getDaysInOtherLanguage($dayOfWeek);
							$endDayOfWeekKorean = date('y.m.d', strtotime($roomRateplan[0]->rateplan_sales_to)) . '(' . $endDayOfWeekKorean . ')';
						}
						?>
						<div class="rateplan-detail-info">판매 기간 : <?= $startDayOfWeekKorean; ?> ~ <?= $endDayOfWeekKorean; ?></div>
					<?php endif; ?>
					<?php if ($roomRateplan[0]->rateplan_stay_min > 1) : ?>
						<div class="rateplan-detail-info">최소 숙박 가능일 : <?= $roomRateplan[0]->rateplan_stay_min; ?>박</div>
					<?php endif; ?>
					<?php if ($roomRateplan[0]->rateplan_stay_max != 0) : ?>
						<div class="rateplan-detail-info">최대 숙박 가능일 : <?= $roomRateplan[0]->rateplan_stay_max; ?>박</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<table class="tb__wrap" style="margin-top: 3rem;">
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
					$formattedCheckinTime = date("H:i", strtotime($checkin));
					$formattedCheckoutTime = date("H:i", strtotime($checkout));
					?>
					<td style="font-weight: bold;"><?= $formattedCheckinTime; ?> 부터</td>
					<td style="font-weight: bold;"><?= $formattedCheckoutTime; ?> 까지</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- //상품 타이틀 -->

</div>