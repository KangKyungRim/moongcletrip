<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$message = $data['message'];

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

		<!-- 알럿 팝업 -->
		<div id="alert" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">예약 진행 중 문제가 발생했습니다.</p>
						<p class="desc" style="word-break: keep-all;">
							<?= $message; ?>
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary" onclick="gotoMain()">돌아가기</button>
                        <button type="button" class="btn-full__primary" onclick="location.href='/auth/kakao/redirect'">카카오톡 1:1 문의</button>
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

	<script>
		fnOpenLayerPop('alert');
	</script>
</body>

</html>