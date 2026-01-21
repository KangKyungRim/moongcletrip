<?php

namespace App\Services;

class EmailTemplateService
{
	public static function reservationBookingTemplate($mailData)
	{
		$to = $mailData['reservation_email'];
		$subject = "[" . $mailData['product_partner_name'] . "] - 예약 완료";

		$date = new \DateTime($mailData['start_date']);
		$formattedDate = $date->format('Y-m-d');
		$productLink = '';

		if ($mailData['product_type'] == 'moongcledeal') {
			$productLink = '/moongcleoffer/product/' . $mailData['partner_idx'];
		} else {
			$productLink = '/stay/detail/' . $mailData['partner_idx'];
		}

		$template = '
		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="Generator" content="뭉클트립">
			<meta name="Author" content="뭉클트립">
			<meta name="Keywords" content="뭉클트립">
			<meta name="Description" content="뭉클트립">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
			<meta name="apple-mobile-web-app-title" content="뭉클트립">
			<meta content="telephone=no" name="format-detection">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta property="og:title" content="뭉클트립">
			<meta property="og:description" content="뭉클트립">
			<meta property="og:image" content="' . $_ENV['APP_HTTP'] . '/assets/app/images/og-image.png">
			<link rel="apple-touch-icon" sizes="180x180" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/apple-touch-icon.png">
			<link rel="icon" type="image/png" sizes="32x32" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="16x16" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-16x16.png">
			<link rel="manifest" href="">
			<link rel="mask-icon" href="" color="#ffffff">
			<meta name="msapplication-TileColor" content="">
			<meta name="theme-color" content="">
			<title>뭉클트립</title>


			<!-- 폰트 -->
			<link href="https://hangeul.pstatic.net/hangeul_static/css/nanum-gothic.css" rel="stylesheet">
		</head>

		<body>
			<div style=" width: 640px; font-size: 12px; margin: 0 auto; padding-bottom: 40px;font-family: \'NanumGothic\';">
				<div><img src="' . $_ENV['APP_HTTP'] . '/assets/app/images/email_customer.png"></div>
				<div style="padding: 50px 30px 40px; margin-bottom: 40px; border-bottom: 1px solid #e3e3e3;">
					<h2 style="font-size: 33px; margin-bottom: 30px; font-weight: 400;">' . $mailData['product_partner_name'] . ' <br>예약 완료</h2>
					<div style=" font-size: 16px; line-height: 150%;"><b>' . $mailData['reservation_name'] . '</b>님 안녕하세요.<br><b>' . $formattedDate . '</b>로  <b style="color: #FF3370;">' . $mailData['product_partner_name'] . '</b>에 예약이 완료 되었습니다.</div>

					<h3 style="font-size: 15px; margin-bottom: 10px; font-weight: 600;margin-top:30px;">' . $mailData['product_partner_name'] . '</h3>
					<table style="font-size: 12px; border-top: 1px solid #000;" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">옵션명</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['product_detail_name'] . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">객실수</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['quantity'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객정보</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_name'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">입실/퇴실일자</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . EmailTemplateService::formatStayDates($mailData['start_date'], $mailData['end_date']) . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약번호</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_number'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약상태</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">예약 완료</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">결제금액(vat포함) </th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . number_format($mailData['payment_sale_amount']) . '원(vat포함)</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">객실상세보기</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;"><a href="' . $_ENV['APP_HTTP'] . $productLink . '">상세보기</a></td>
						</tr>
					</table>
					<div style=" background: #F1F1FF; font-sizE: 12px; color: #6E718B; padding: 20px; border-radius: 14px; margin: 20px 0;">
						<ul>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>본 예약은 상품페이지에 기재된 \'취소 및 환불 규정\'에 동의 후 예약이 되었으며, 취소수수료 없이, 무료취소가 가능한 기한은 상품마다 상이하오니, 상품페이지에 기재된 반드시 숙지해주시기 바랍니다.</li>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>차량 이용시, 주차가능 여부를 반드시 확인해주세요.</li>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>문의 : [' . $mailData['partner_phonenumber'] . ']</li>
						</ul>
					</div>

					<div style="font-size:16px; font-weight:600; text-align:center; margin-top:40px; margin-bottom:40px;">그럼 편안하고 즐거운 투숙이 되시길 바라겠습니다.<br>
						이용해주셔서 감사합니다.</div>
					<a href="' . $_ENV['APP_HTTP'] . '/my/reservations" style="display: block; font-size: 16px; font-weight: 600; margin: 40px auto 0; height: 56px; color: #fff; background: #FF3370; border-radius: 14px; width: 350px; line-height: 56px; text-align: center;">예약 내역보기</a>
				</div>
			</div>
		</body>
		</html>';

		return array(
			"fromName" => "뭉클트립",
			"fromEmail" => "moongcletrip@honolulu.co.kr",
			"toName" => $mailData['reservation_name'],
			"toEmail" => $to,
			"subject" => $subject,
			"contents" => $template,
		);
	}

	public static function reservationCancelTemplate($mailData)
	{
		$to = $mailData['reservation_email'];
		$subject = "[" . $mailData['product_partner_name'] . "] - 예약 취소 완료";

		if ($mailData['payment_sale_amount'] == $mailData['refund_amount']) {
			$freeCancel = "무료취소";
		} else {
			$freeCancel = "위약금 발생";
		}

		$template = '
		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="Generator" content="뭉클트립">
			<meta name="Author" content="뭉클트립">
			<meta name="Keywords" content="뭉클트립">
			<meta name="Description" content="뭉클트립">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
			<meta name="apple-mobile-web-app-title" content="뭉클트립">
			<meta content="telephone=no" name="format-detection">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta property="og:title" content="뭉클트립">
			<meta property="og:description" content="뭉클트립">
			<meta property="og:image" content="' . $_ENV['APP_HTTP'] . '/assets/app/images/og-image.png">
			<link rel="apple-touch-icon" sizes="180x180" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/apple-touch-icon.png">
			<link rel="icon" type="image/png" sizes="32x32" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="16x16" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-16x16.png">
			<link rel="manifest" href="">
			<link rel="mask-icon" href="" color="#ffffff">
			<meta name="msapplication-TileColor" content="">
			<meta name="theme-color" content="">
			<title>뭉클트립</title>


			<!-- 폰트 -->
			<link href="https://hangeul.pstatic.net/hangeul_static/css/nanum-gothic.css" rel="stylesheet">
		</head>

		<body>	
		<div style=" width: 640px; font-size: 12px; margin: 0 auto; padding-bottom: 40px;font-family: \'NanumGothic\';">
				<div><img src="' . $_ENV['APP_HTTP'] . '/assets/app/images/email_customer.png"></div>
				<div style="padding: 50px 30px 40px; margin-bottom: 40px; border-bottom: 1px solid #e3e3e3;">
					<h2 style="font-size: 33px; margin-bottom: 30px; font-weight: 400;">' . $mailData['product_partner_name'] . ' <br>예약 취소 완료</h2>
					<div style=" font-size: 16px; line-height: 150%;"><b>' . $mailData['reservation_name'] . '</b>님 안녕하세요.<br>뭉클트립을 이용해주셔서 감사합니다.<br>예약 취소 안내입니다.</div>

					<h3 style="font-size: 15px; margin-bottom: 10px; font-weight: 600;margin-top:30px;">(예약취소정보)</h3>
					<table style="font-size: 12px; border-top: 1px solid #000;" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">호텔명</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['product_partner_name'] . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">옵션명</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['product_detail_name'] . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">객실수</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['quantity'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">입실/퇴실일자</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . EmailTemplateService::formatStayDates($mailData['start_date'], $mailData['end_date']) . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약번호</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_number'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">결제금액(vat포함) </th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . number_format($mailData['payment_sale_amount']) . '원(vat포함)</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">무료취소 여부</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $freeCancel . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">취소위약금</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . number_format($mailData['payment_sale_amount'] - $mailData['refund_amount']) . '원</td>
						</tr>
					</table>
					<div style=" background: #F1F1FF; font-sizE: 12px; color: #6E718B; padding: 20px; border-radius: 14px; margin: 20px 0;">
						<ul>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>취소위약금은 예약시 동의하셨던 상품 취소 및 환불 정책에 따라 책정되었습니다.</li>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>부분 환불금액이 발생 시 취소완료 후 환불까지 영업일 3~7일 정도 소요될 수 있습니다.</li>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>상세내역은 \'예약내역\'을 확인해주세요.</li>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>감사합니다.</li>
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>문의 : [' . $mailData['partner_phonenumber'] . ']</li>
						</ul>
					</div>

					<a href="' . $_ENV['APP_HTTP'] . '/my/reservations" style="display: block; font-size: 16px; font-weight: 600; margin: 40px auto 0; height: 56px; color: #fff; background: #FF3370; border-radius: 14px; width: 350px; line-height: 56px; text-align: center;">예약 내역보기</a>
				</div>
			</div>
		</body>
		</html>';

		return array(
			"fromName" => "뭉클트립",
			"fromEmail" => "moongcletrip@honolulu.co.kr",
			"toName" => $mailData['reservation_name'],
			"toEmail" => $to,
			"subject" => $subject,
			"contents" => $template,
		);
	}

	public static function reservationBookingToHotelTemplate($mailData)
	{
		$startDate = new \DateTime($mailData['start_date']);
		$formattedStartDate = $startDate->format('Y-m-d');

		$endDate = new \DateTime($mailData['end_date']);
		$formattedEndDate = $endDate->format('Y-m-d');

		$interval = $startDate->diff($endDate);
		$daysDifference = $interval->days;

		$reservationCode = $mailData['reservation_number'];

		if ($mailData['reservation_confirmed_code'] != $mailData['reservation_pending_code']) {
			$reservationCode = $mailData['reservation_confirmed_code'];
		}

		$to = $mailData['partner_reservation_email'];
		$subject = "[뭉클] " . $mailData['reservation_name'] . " 님 (" . $reservationCode . "), " . $formattedStartDate . "(" . $daysDifference . "박)" . " 예약 안내";

		$productLink = '/partner/reservations';

		$template = '
		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="Generator" content="뭉클트립">
			<meta name="Author" content="뭉클트립">
			<meta name="Keywords" content="뭉클트립">
			<meta name="Description" content="뭉클트립">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
			<meta name="apple-mobile-web-app-title" content="뭉클트립">
			<meta content="telephone=no" name="format-detection">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta property="og:title" content="뭉클트립">
			<meta property="og:description" content="뭉클트립">
			<meta property="og:image" content="' . $_ENV['APP_HTTP'] . '/assets/app/images/og-image.png">
			<link rel="apple-touch-icon" sizes="180x180" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/apple-touch-icon.png">
			<link rel="icon" type="image/png" sizes="32x32" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="16x16" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-16x16.png">
			<link rel="manifest" href="">
			<link rel="mask-icon" href="" color="#ffffff">
			<meta name="msapplication-TileColor" content="">
			<meta name="theme-color" content="">
			<title>뭉클트립</title>


			<!-- 폰트 -->
			<link href="https://hangeul.pstatic.net/hangeul_static/css/nanum-gothic.css" rel="stylesheet">
		</head>

		<body>
			<div class="mail_wrap" style=" width: 640px; font-size: 12px; margin: 0 auto; padding-bottom: 40px;font-family: \'NanumGothic\';">
				<div><img src="' . $_ENV['APP_HTTP'] . '/assets/app/images/email_partner.png"></div>
				<div style="padding: 50px 30px 40px; margin-bottom: 40px; border-bottom: 1px solid #e3e3e3;">
					<h2 style="font-size: 33px; margin-bottom: 30px; font-weight: 400;">안녕하세요 파트너님,<br>
						<span style="color: #FF3370;">뭉클</span>입니다.<br>
						신규예약이 접수되었습니다.
					</h2>
					<h3 style="font-size: 15px; margin-bottom: 10px; font-weight: 600;margin-top:30px;">' . $mailData['product_partner_name'] . '</h3>
					<table style="font-size: 12px; border-top: 1px solid #000;" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">결제방식</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">온라인 사전 결제</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객정보</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_name'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약상태</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">예약완료</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객연락처</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_phone'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객이메일</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_email'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">입실/퇴실일자</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . EmailTemplateService::formatStayDates($mailData['start_date'], $mailData['end_date']) . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약번호</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $reservationCode . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">옵션명</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['product_detail_name'] . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">객실수</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['quantity'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">입금가(vat포함) </th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . number_format($mailData['item_origin_sale_price'] * ((100 - $mailData['partner_charge']) / 100)) . '원(vat포함)</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">판매가(vat포함) </th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . number_format($mailData['item_origin_sale_price']) . '원(vat포함)</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약일시</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_datetime'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">투숙정보</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_personnel'] . '</td>
						</tr>
					</table>
					<div style=" background: #F1F1FF; font-sizE: 12px; color: #6E718B; padding: 20px; border-radius: 14px; margin: 20px 0;">
						<ul>			
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>취소 및 환불정책 : [' . $mailData['stay_cancel_info'] . ']</li>
						</ul>
					</div>

					<div style="font-size:16px; font-weight:600; text-align:center; margin-top:40px; margin-bottom:40px;">감사합니다.</div>

					<a href="' . $_ENV['APP_HTTP'] . $productLink . '" style="display: block; font-size: 16px; font-weight: 600; margin: 40px auto 0; height: 56px; color: #fff; background: #FF3370; border-radius: 14px; width: 350px; line-height: 56px; text-align: center;">예약 내역보기</a>
				</div>
			</div>
		</body>
		</html>';

		return array(
			"fromName" => "뭉클트립",
			"fromEmail" => "moongcletrip@honolulu.co.kr",
			"toName" => $mailData['partner_reservation_email'],
			"toEmail" => $to,
			"subject" => $subject,
			"contents" => $template,
		);
	}

	public static function reservationCancelToHotelTemplate($mailData)
	{
		$startDate = new \DateTime($mailData['start_date']);
		$formattedStartDate = $startDate->format('Y-m-d');

		$endDate = new \DateTime($mailData['end_date']);
		$formattedEndDate = $endDate->format('Y-m-d');

		$interval = $startDate->diff($endDate);
		$daysDifference = $interval->days;

		$reservationCode = $mailData['reservation_number'];

		if ($mailData['reservation_confirmed_code'] != $mailData['reservation_pending_code']) {
			$reservationCode = $mailData['reservation_confirmed_code'];
		}

		if ($mailData['payment_sale_amount'] == $mailData['refund_amount']) {
			$freeCancel = "무료취소";
		} else {
			$freeCancel = "위약금 발생";
		}

		$to = $mailData['partner_reservation_email'];
		$subject = "[뭉클] " . $mailData['reservation_name'] . " 님 (" . $reservationCode . "), " . $formattedStartDate . "(" . $daysDifference . "박)" . " 예약 취소 안내";

		$productLink = '/partner/reservations';

		$template = '
		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="Generator" content="뭉클트립">
			<meta name="Author" content="뭉클트립">
			<meta name="Keywords" content="뭉클트립">
			<meta name="Description" content="뭉클트립">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
			<meta name="apple-mobile-web-app-title" content="뭉클트립">
			<meta content="telephone=no" name="format-detection">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta property="og:title" content="뭉클트립">
			<meta property="og:description" content="뭉클트립">
			<meta property="og:image" content="' . $_ENV['APP_HTTP'] . '/assets/app/images/og-image.png">
			<link rel="apple-touch-icon" sizes="180x180" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/apple-touch-icon.png">
			<link rel="icon" type="image/png" sizes="32x32" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="16x16" href="' . $_ENV['APP_HTTP'] . '/assets/app/images/favicon-16x16.png">
			<link rel="manifest" href="">
			<link rel="mask-icon" href="" color="#ffffff">
			<meta name="msapplication-TileColor" content="">
			<meta name="theme-color" content="">
			<title>뭉클트립</title>


			<!-- 폰트 -->
			<link href="https://hangeul.pstatic.net/hangeul_static/css/nanum-gothic.css" rel="stylesheet">
		</head>

		<body>
			<div class="mail_wrap" style=" width: 640px; font-size: 12px; margin: 0 auto; padding-bottom: 40px;font-family: \'NanumGothic\';">
				<div><img src="' . $_ENV['APP_HTTP'] . '/assets/app/images/email_partner.png"></div>
				<div style="padding: 50px 30px 40px; margin-bottom: 40px; border-bottom: 1px solid #e3e3e3;">
					<h2 style="font-size: 33px; margin-bottom: 30px; font-weight: 400;">안녕하세요 파트너님,<br>
						<span style="color: #FF3370;">뭉클</span>입니다.<br>
						기존 예약이 취소되었습니다.
					</h2>
					<h3 style="font-size: 15px; margin-bottom: 10px; font-weight: 600;margin-top:30px;">' . $mailData['product_partner_name'] . '</h3>
					<table style="font-size: 12px; border-top: 1px solid #000;" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">무료취소 여부</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $freeCancel . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">결제방식</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">온라인 사전 결제</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객정보</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_name'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약상태</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">예약취소</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객연락처</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_phone'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">고객이메일</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_email'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">입실/퇴실일자</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . EmailTemplateService::formatStayDates($mailData['start_date'], $mailData['end_date']) . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약번호</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $reservationCode . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">옵션명</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['product_detail_name'] . '</td>
						</tr>
						<tr>
							<th width="130px" style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">객실수</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['quantity'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">예약일시</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . $mailData['reservation_datetime'] . '</td>
						</tr>
						<tr>
							<th style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3; background: #F8F8F8; color: #666; font-weight: 400; text-align: left;">취소위약금</th>
							<td style="padding: 15px 15px; border-bottom: 1px solid #E3E3E3;">' . number_format($mailData['payment_sale_amount'] - $mailData['refund_amount']) . '원</td>
						</tr>
					</table>
					<div style=" background: #F1F1FF; font-sizE: 12px; color: #6E718B; padding: 20px; border-radius: 14px; margin: 20px 0;">
						<ul>			
							<li style="display: flex; margin-bottom: 7px;"><span style="margin-right:10px;">※</span>취소 및 환불정책 : [' . $mailData['stay_cancel_info'] . ']</li>
						</ul>
					</div>

					<div style="font-size:16px; font-weight:600; text-align:center; margin-top:40px; margin-bottom:40px;">감사합니다.</div>

					<a href="' . $_ENV['APP_HTTP'] . $productLink . '" style="display: block; font-size: 16px; font-weight: 600; margin: 40px auto 0; height: 56px; color: #fff; background: #FF3370; border-radius: 14px; width: 350px; line-height: 56px; text-align: center;">예약 내역보기</a>
				</div>
			</div>
		</body>
		</html>';

		return array(
			"fromName" => "뭉클트립",
			"fromEmail" => "moongcletrip@honolulu.co.kr",
			"toName" => $mailData['partner_reservation_email'],
			"toEmail" => $to,
			"subject" => $subject,
			"contents" => $template,
		);
	}

	public static function formatStayDates($start_date, $end_date)
	{
		// 요일 배열 설정
		$daysOfWeek = ['일', '월', '화', '수', '목', '금', '토'];

		// DateTime 객체 생성
		$start = new \DateTime($start_date);
		$end = new \DateTime($end_date);

		// DateTime 객체의 요일 구하기
		$startDayOfWeek = $daysOfWeek[$start->format('w')];
		$endDayOfWeek = $daysOfWeek[$end->format('w')];

		// 날짜 포맷 변경
		$formattedStart = $start->format('y.m.d');
		$formattedEnd = $end->format('y.m.d');

		// 날짜 차이 계산
		$interval = $start->diff($end)->days;

		// 결과 문자열 생성
		return "{$interval}박 {$formattedStart}({$startDayOfWeek})~{$formattedEnd}({$endDayOfWeek})";
	}
}
