<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];

?>

<!-- Head -->
<?php 
    $page_title_01 = "공지사항";

    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>
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
				<button class="btn-back" onclick="goBack()"><span class="blind">뒤로가기</span></button>
				<h2 class="header-tit__center">공지사항</h2>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">

			<!-- 공지사항 리스트 -->
			<div class="notice__wrap">
				<div class="notice-list__wrap">
					<ul>
                        <li>
							<a href="/notice/8">
								<p class="tit">1분 만에 숙소 고민 끝! 뭉클트립 사용안내서</p>
								<p class="date">2025.09.18</p>
							</a>
						</li>
                        <li>
							<a href="/notice/7">
								<p class="tit">📢 [리뷰이벤트] 당첨자 공지사항</p>
								<p class="date">2025.08.05</p>
							</a>
						</li>
                        <li>
							<a href="/notice/6">
								<p class="tit">📢 [뭉클X토스페이] 할인&캐시백 이벤트</p>
								<p class="date">2025.08.01</p>
							</a>
						</li>
                        <li>
							<a href="/notice/5">
								<p class="tit">📢 [뭉클 단독] 2025 고려대학교 AI 로보틱스 여름캠프 OPEN!</p>
								<p class="date">2025.07.21</p>
							</a>
						</li>
                        <li>
							<a href="/notice/4">
								<p class="tit">📢 [프로모션] AI 분리수업 키캉스 끝판왕🤖 에듀캉스 패키지✨</p>
								<p class="date">2025.07.18</p>
							</a>
						</li>
                        <li>
							<a href="/notice/3">
								<p class="tit">📢 [리뷰이벤트] 후기 남기면 선물이 팡팡!</p>
								<p class="date">2025.06.27</p>
							</a>
						</li>
                        <li>
							<a href="/notice/2">
								<p class="tit">📢 [공지] 뭉클트립에 오신 것을 진심으로 환영합니다!</p>
								<p class="date">2025.06.18</p>
							</a>
						</li>
						<li>
							<a href="/notice/1">
								<p class="tit">뭉클트립 v2.1 베타가 곧 오픈합니다!</p>
								<p class="date">2024.12.10</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- //공지사항 리스트 -->

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

</body>

</html>