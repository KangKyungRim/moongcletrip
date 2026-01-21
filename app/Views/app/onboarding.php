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

		<div class="container__wrap mypage__wrap" >

            <div class="onbarding">
                <div class="top_box">
                    <h3>숙소 추천,<br>알림으로 받아보세요</h3>
                    <p><span>뭉클트립</span>을 원활히 이용하시려면 권한 허용이 필요해요.</p>
                </div> 

                <div class="icon_box">
                    <p class="tit">선택 접근 권한</p>
                    <ul>
                        <li>
                            <div class="icon">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div class="text">
                                <p>알림</p>
                                <span>맞춤 숙소 추천 알림 발송</span>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="text">
                                <p>사진</p>
                                <span>후기 사진 업로드</span>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="text">
                                <p>위치</p>
                                <span>현 위치 기반의 지도 검색</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="desc">
                    <p>선택 권한을 허용하지 않아도 서비스 이용은 가능하지만, 일부 기능은 제한될 수 있습니다.</p>
                </div>
            </div>

			<!-- 하단 버튼 영역 -->
			<div class="bottom-fixed__wrap" style="max-width: auto;">
				<div class="btn__wrap">
                    <button id="right_ok" class="btn-full__primary">확인</button>
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
        document.getElementById('right_ok').addEventListener('click', function () {
            if (window.ReactNativeWebView && window.ReactNativeWebView.postMessage) {
            window.ReactNativeWebView.postMessage(JSON.stringify({ type: 'req-perms' }));
            }
        });
    </script>

</body>

</html>