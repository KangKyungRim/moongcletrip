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



	<div class="mobileWrap">
		<header class="header__wrap">
			<div class="header__inner">
				<h2 class="header-tit__center">가입 완료</h2>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">
			<section class="join__wrap">
				<div class="box-white__wrap signup-complete__wrap">
					<div class="complete-title">
						<img src="/assets/app/images/congratulation.png">
						<div class="complete-text">가입을 완료했습니다.</div>
					</div>
					<div class="congratulate-gif">
						<img src="/assets/app/images/signup-complete.gif">
					</div>
				</div>
			</section>

			<!-- 하단 버튼 영역 -->
			<div class="bottom-fixed__wrap">
				<div class="btn__wrap">
					<?php if (!empty($_GET['return'])) : ?>
						<button id="completeButton" class="btn-full__primary" onclick="location.href='<?= urldecode($_GET['return']); ?>'">확인</button>
					<?php else : ?>
						<button id="comapleteButton" class="btn-full__primary" onclick="gotoMypage();">확인</button>
					<?php endif; ?>
				</div>
			</div>
			<!-- //하단 버튼 영역 -->
		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

    <!-- NAVER 회원가입(sign_up) SCRIPT -->
    <script type='text/javascript'>
        if(window.wcs){
        if(!wcs_add) var wcs_add = {};
        wcs_add['wa'] = 's_2744685fd307';
        var _conv = {};
            _conv.type = 'sign_up';    	
        wcs.trans(_conv);
        }
    </script>

</body>

</html>