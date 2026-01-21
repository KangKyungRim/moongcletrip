<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

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
						<p class="title">존재하지 않는 주소입니다.</p>
						<p class="desc">
							홈으로 이동합니다.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary" onclick="gotoMain()">홈으로 가기</button>
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