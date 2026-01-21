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

    <div id="mobileWrap" style="height: 100vh;">
        <header class="header__wrap">
            <div class="header__inner">
				<button class="btn-back" onclick="previousBlankPage()"><span class="blind">뒤로가기</span></button>
				<div class="btn__wrap">
                    <!-- <button class="btn-share" onclick="sendShareLink('뭉클트립에서 <?= $partner->partner_name; ?>의 뭉클 정보를 공유했어요. 자세한 내용은 아래 링크를 확인해보세요.', '<?= $_ENV['APP_HTTP'] . $stayImages[0]; ?>')"><span class="blind">공유하기</span></button> -->
                    <button class="btn-share"><span class="blind">공유하기</span></button>
				</div>
			</div>
        </header>

        <div class="container__wrap search__wrap">
            <section class="layout__wrap" style="padding: 0;">
                <div class="full_banner">
                    <div class="banner">
                        <a href="#" class="banner__item">
                            <div class="img__box">
                                <img src="https://i.pinimg.com/736x/7e/9d/d6/7e9dd6d3a789613f0780638a76211305.jpg" alt="배너 이미지">
                            </div>
                            <div class="text__box">
                                <p class="title">시그니엘 부산</p>
                                <p class="desc">해운대를 대표하는 럭셔리 호텔의 기준</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="padding-20">
                    <div class="stay_etc">
                        <p class="stay-class"><span class="star"><i class="fa-solid fa-star"></i></span> 5성급</p>
                        <button type="button" class="like">
                            <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.434 12.9393C8.196 13.0202 7.804 13.0202 7.566 12.9393C5.536 12.2719 1 9.48764 1 4.76854C1 2.68539 2.743 1 4.892 1C6.166 1 7.293 2.37554 8 3.2924C8.707 2.37554 9.841 1 11.108 1C13.257 1 15 2.68539 15 4.76854C15 9.48764 10.464 12.2719 8.434 12.9393Z" stroke="#714CDC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            관심 등록
                        </button>
                    </div>
                    <div class="stay_desc">
                        해운대 해변과 광안대교가 한눈에 보이는 시그니엘 부산은 랜드마크 엘시티에 위치한 럭셔리 호텔입니다.
                        세계적인 건축가 Jason Fox의 디자인으로 완성된 객실에서는 탁 트인 오션뷰를 감상할 수 있으며, 미쉐린 스타 레스토랑과 국내 최고층 인피니티 풀을 갖추고 있습니다.<br>
                        차별화된 서비스와 최상의 시설로 국내외 셀러브리티들이 선호하는 부산의 대표 럭셔리 호텔입니다.
                    </div>
                </div>

                <div class="margin-bottom-30">
                    <div class="wrap__box">
                        <h4 class="wrap__title padding-x-20">
                            인기 시설
                        </h4>    
                        <div class="padding-x-20">
                            <ul class="facility__list">
                                <li class="facility__item">
                                    <p class="img">
                                        <img src="/uploads/tags/infinity_pool.png" alt="시설 아이콘">
                                    </p>
                                    <p class="tag_name">인피니티 풀</p>
                                </li>
                                <li class="facility__item">
                                    <p class="img">
                                        <img src="/uploads/tags/infinity_pool.png" alt="시설 아이콘">
                                    </p>
                                    <p class="tag_name">미쉐린 스타 레스토랑</p>
                                </li>
                                <li class="facility__item">
                                    <p class="img">
                                        <img src="/uploads/tags/infinity_pool.png" alt="시설 아이콘">
                                    </p>
                                    <p class="tag_name">스파&피트니스</p>
                                </li>
                                <li class="facility__item">
                                    <p class="img">
                                        <img src="/uploads/tags/infinity_pool.png" alt="시설 아이콘">
                                    </p>
                                    <p class="tag_name">룸서비스 24시간</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    
                </div>

                <div class="margin-bottom-30">
                    <div class="wrap__box">
                        <h4 class="wrap__title padding-x-20">
                            기본 정보
                        </h4>    
                        <div class="padding-x-20">
                            <table class="tb__wrap">
                                <colgroup>
                                    <col width="50%">
                                    <col width="50%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>체크인</th>
                                        <td>16:00 부터</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>16:00 부터</td>
                                        <td>11:00 까지</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 하단바 변경 -->
            <!-- <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?> -->
            <div class="bottom-navi__wrap">
                <ul class="bottom-navi__list">
                    <li class="navi navi-home <?= $_SERVER['REQUEST_URI'] === '/' ? 'active' : ''; ?>">
                        <a href="/">
                            <i class="ico ico-home"></i>
                            <span>홈</span>
                        </a>
                    </li>
                    <li class="navi navi-search <?= $_SERVER['REQUEST_URI'] === '/article/stay' ? 'active' : ''; ?>">
                        <a href="/search-home">
                            <i class="ico ico-search"></i>
                            <span>탐색</span>
                        </a>
                    </li>
                    <li id="moongcledealBubble" class="navi navi-logo <?= $_SERVER['REQUEST_URI'] === '/moongcledeals' ? 'active' : ''; ?>">
                        <div id="bubbleText" class="bubble__wrap hidden" style="animation: float 2s ease-in-out infinite;">
                            <p class="txt">나만의 뭉클딜을 받아볼까요?</p>
                        </div>
                        <a href="/moongcledeals">
                            <div id="lottie-container" class="ico ico-logo">
                                <?php if (!empty($unreadMoocledealCount)) : ?>
                                    <p class="num"><?= $unreadMoocledealCount; ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>
                    <li class="navi navi-community <?= $_SERVER['REQUEST_URI'] === '/community' ? 'active' : ''; ?>">
                        <a href="/community">
                            <i class="ico ico-community"></i>
                            <span>리뷰</span>
                        </a>
                    </li>
                    <li class="navi navi-mypage <?= $_SERVER['REQUEST_URI'] === '/mypage' ? 'active' : ''; ?>">
                        <a href="/mypage">
                            <i class="ico ico-mypage"></i>
                            <span>마이</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="pageLoader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        sessionStorage.setItem('previousPage', window.location.href);
    </script>

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>