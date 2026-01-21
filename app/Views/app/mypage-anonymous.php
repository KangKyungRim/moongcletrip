<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

$user = $data['user'];
$myCoupons = $data['myCoupons'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

?>

<!-- Head -->
<?php 
    // $page_title = "마이페이지";
    // $page_description = "마이페이지";

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
                <h2 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h2>
                <div class="btn__wrap">
                    <button type="button" class="btn-search" onclick="gotoSearch()"><span class="blind">검색</span></button>
                    <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
                    <!-- <button type="button" class="btn-cart__gray" onclick="gotoTravelCart()"><span class="blind">장바구니</span></button> -->
                </div>
            </div>
        </header>

        <div class="container__wrap mypage__wrap">
            <section class="layout__wrap bg-gray pd-big">
                <div class="tit__wrap">
                    <p class="ft-xxxl">맘 편한 숙소 찾기</p>
                    <p class="ft-default">이젠 뭉클이 대신 찾아드릴게요</p>
                </div>
                <div class="box-white__list">
                    <div class="box-white__wrap">
                        <p class="title">
                            원하는 여행을 등록하면 <br>
                            꼭 맞는 제안이 도착해요
                        </p>
                        <div class="splide splide__default">
                            <div class="splide__track <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup2' : '' ?>">
                                <ul class="splide__list">
                                    <li class="splide__slide"><img src="/assets/app/images/main/01.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/02.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/03.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/04.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/05.gif" alt=""></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="box-white__wrap">
                        <p class="title">
                            간편하게 로그인하고<br>
                            혜택을 받아보세요
                        </p>
                        <div class="sns-btn__wrap">
                            <!-- <button type="button" class="btn-full__primary">휴대폰번호로 계속하기</button> -->
                            
                            <!-- <button type="button" class="btn-full__primary btn-sns__naver">네이버로 계속하기</button> -->

                            <button type="button" class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect'">카카오로 계속하기</button>

                            <?php if (isMacOS() || isIOS()) : ?> 
                                <button type="button" class="btn-full__primary btn-sns__apple" onclick="location.href='/auth/apple/redirect'">애플로 계속하기</button>
                            <?php endif; ?>

                            <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()">이메일로 계속하기</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="layout__wrap">
                <div class="list__wrap">
                    <ul>
                        <li>
                            <a href="/notices">
                                <p class="tit">공지사항</p>
                                <i class="ico ico-arrow__right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/faq">
                                <p class="tit">자주 묻는 질문</p>
                                <i class="ico ico-arrow__right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/term/terms-of-service">
                                <p class="tit">서비스 이용 약관</p>
                                <i class="ico ico-arrow__right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/term/privacy-policy">
                                <p class="tit">개인정보 처리방침</p>
                                <i class="ico ico-arrow__right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/term/location-based-service">
                                <p class="tit">위치기반 이용약관</p>
                                <i class="ico ico-arrow__right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="" onclick="preventEvent(event)">
                                <p class="tit">버전 정보</p>
                                <p class="txt-primary ft-xxs">Beta 2.1</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- 푸터 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/footer.php"; ?>
            <!-- //푸터 -->

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

        </div>

        <div id="appDownPopup2" class="layerpop__wrap type-center mobileweb-popup">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <i class="ico ico-logo__big"></i>
                    <p class="ft-xxl">
                        알아서 찾아주는 나만의 여행혜택<br>
                        무료로 누려볼까요?
                    </p>
                </div>
                <div class="layerpop__footer">
                    <button class="btn-full__black" onclick="openAppDownloadTab()" style="white-space: nowrap;">지금 앱 다운로드</button>
                </div>
            </div>
        </div>

        <!-- 임시 팝업 (제거) -->
        <div id="alertMobile" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">웹 브라우저에서 계속하기</p>
						<p class="desc" style="word-break: keep-all;">
                            임시 점검 중인 관계로 잠시 모바일 웹에서만 로그인 및 원활한 이용이 가능합니다. 너그러운 양해 부탁드립니다.
						</p>
					</div>
				</div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary fnClosePop" style="background: #714CDC; color: #fff" onclick="outLink('https://www.moongcletrip.com/mypage')">로그인 하러가기</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        sessionStorage.setItem('previousPage', window.location.href);

        function openAppDownloadTab() {
            let url = 'https://play.google.com/store/apps/details?id=com.mungkeultrip';

            <?php if (isMacOS() || isIOS()) : ?>
                url = 'https://apps.apple.com/kr/app/%EB%AD%89%ED%81%B4%ED%8A%B8%EB%A6%BD/id6472235149';
            <?php endif; ?>

            window.open(url, '_blank');
        }
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

    <!-- 
    <script src="https://t1.kakaocdn.net/kakao_js_sdk/2.7.4/kakao.min.js" integrity="sha384-DKYJZ8NLiK8MN4/C5P2dtSmLQ4KwPaoqAfyA/DfmEc1VDxu4yyC7wy6K1Hs90nka" crossorigin="anonymous"></script>
    <script>
        Kakao.init('a09a9506a8284c662059e618d6ec7b42');

        function loginWithKakao() {
            Kakao.Auth.authorize({
                redirectUri: '<?= $_ENV['APP_HTTP']; ?>/auth/kakao/callback',
                serviceTerms: 'account_email'
            })
        }
    </script> -->

</body>

</html>