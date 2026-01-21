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
            <section class="layout__wrap" style="padding: 2.4rem 0;">
                <div class="banner__wrap no_slide">
                    <div class="banner">
                        <a href="#" class="banner__item">
                            <div class="img__box">
                                <img src="https://i.pinimg.com/236x/fc/47/45/fc4745be3338d538222e56aa1fd3c929.jpg" alt="배너 이미지">
                            </div>
                            <div class="text__box">
                                <p class="title">목포<br>Mokpo</p>
                                <p class="desc">느림의 미학, 여유로운 목포 여행</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-visible margin-bottom-30 overflow__menu">
                    <ul id="overflowMenu" class="overflow__list">
                        <li class="menu active" style="width: 25%;">
                            <a href="#attraction">
                                <p class="title">관광지</p>
                            </a>
                        </li>
                        <li class="menu" style="width: 25%;">
                            <a href="#famousRestaurant">
                                <p class="title">맛집</p>
                            </a>
                        </li>
                        <li class="menu" style="width: 25%;">
                            <a href="#stay">
                                <p class="title">숙소</p>
                            </a>
                        </li>
                        <li class="menu" style="width: 25%;">
                            <a href="#course">
                                <p class="title">코스</p>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="margin-bottom-30" id="attraction">
                    <div class="wrap__box">
                        <h4 class="wrap__title padding-x-20">
                            목포에서 뭐하지?
                        </h4>
                        <div class="overflow-x-visible padding-x-20 play-do">
                            <div class="wrap__list">
                                <div class="item">
                                    <a href="#">
                                        <div class="img__box">
                                            <img src="https://i.pinimg.com/736x/7a/c5/57/7ac557b2d38ca371666277dce9d715fc.jpg" alt="이미지">
                                            <div class="position_text_box">
                                                <p class="title" style="font-size: 1.6rem;">유달산</p>
                                                <p class="desc">목포의 상징적인 명소</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="item">
                                    <a href="#">
                                        <div class="img__box">
                                            <img src="https://i.pinimg.com/736x/7a/c5/57/7ac557b2d38ca371666277dce9d715fc.jpg" alt="이미지">
                                            <div class="position_text_box">
                                                <p class="title" style="font-size: 1.6rem;">유달산</p>
                                                <p class="desc">목포의 상징적인 명소</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="margin-bottom-30" id="famousRestaurant">
                    <div class="wrap__box">
                        <h4 class="wrap__title padding-x-20">
                            로컬 맛집
                        </h4>
                        <div class="overflow-x-visible padding-x-20">
                            <div class="wrap__list">
                                <div class="item product">
                                    <a href="#">
                                        <div class="img__box">
                                            <img src="https://i.pinimg.com/236x/34/54/27/3454279b2e25ad1081c1fae37510969d.jpg" alt="이미지">
                                        </div>
                                        <p class="title">목포 연희네</p>
                                        <p class="desc">신선한 해산물 요리</p>
                                    </a>
                                </div>
                                <div class="item product">
                                    <a href="#">
                                        <div class="img__box">
                                            <img src="https://i.pinimg.com/236x/34/54/27/3454279b2e25ad1081c1fae37510969d.jpg" alt="이미지">
                                        </div>
                                        <p class="title">바다향기</p>
                                        <p class="desc">신선한 해산물 요리</p>
                                    </a>
                                </div>
                                <div class="item product">
                                    <a href="#">
                                        <div class="img__box">
                                            <img src="https://i.pinimg.com/236x/34/54/27/3454279b2e25ad1081c1fae37510969d.jpg" alt="이미지">
                                        </div>
                                        <p class="title">목포 연희네</p>
                                        <p class="desc">신선한 해산물 요리</p>
                                    </a>
                                </div>
                                <div class="item product">
                                    <a href="#">
                                        <div class="img__box">
                                            <img src="https://i.pinimg.com/236x/34/54/27/3454279b2e25ad1081c1fae37510969d.jpg" alt="이미지">
                                        </div>
                                        <p class="title">목포 연희네</p>
                                        <p class="desc">신선한 해산물 요리</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="margin-bottom-30" id="stay">
                    <div class="wrap__box">
                        <h4 class="wrap__title padding-x-20">추천 숙소</h4>
                        <div class="overflow-x-visible">
                            <div class="capsule-btns padding-x-20">
                                <button type="button" class="capsule-btn active"><img src="/uploads/tags/with_parents.png" alt="아이콘"> 가족</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/with_spouse.png" alt="아이콘"> 연인과</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/with_friends.png" alt="아이콘"> 친구와</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/kids_friendly_pension.png" alt="아이콘"> 키즈펜션</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/kids_friendly_pension.png" alt="아이콘"> 키즈펜션</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/kids_friendly_pension.png" alt="아이콘"> 키즈펜션</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/kids_friendly_pension.png" alt="아이콘"> 키즈펜션</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/kids_friendly_pension.png" alt="아이콘"> 키즈펜션</button>
                                <button type="button" class="capsule-btn"><img src="/uploads/tags/kids_friendly_pension.png" alt="아이콘"> 키즈펜션</button>
                            </div>
                        </div>

                        <div class="overflow-x-visible">
                            <div class="wrap__list padding-x-20">
                                <div class="item product">
                                    <a href="#">
                                        <div class="img__box" style="width: 28rem !important;"> 
                                            <img src="https://i.pinimg.com/236x/48/cb/66/48cb661fa164147fb72681ae80b2e802.jpg" alt="이미지">
                                        </div>
                                        <p class="title">목포 오션뷰 호텔</p>
                                        <p class="desc">바다가 보이는 프리미엄 객실</p>
                                        <div class="price__box" style="margin-top: 1rem;">
                                            <p class="discount">30%</p>
                                            <p class="price"><span>50,000</span>원</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="item product">
                                    <a href="#">
                                        <div class="img__box" style="width: 28rem !important;">
                                            <img src="https://i.pinimg.com/236x/48/cb/66/48cb661fa164147fb72681ae80b2e802.jpg" alt="이미지">
                                        </div>
                                        <p class="title">바다향기</p>
                                        <p class="desc">신선한 해산물 요리</p>
                                        <div class="price__box" style="margin-top: 1rem;">
                                            <p class="discount">30%</p>
                                            <p class="price"><span>50,000</span>원</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="padding-x-20" style="margin-top: 2rem;">
                            <button type="button" class="btn-full__primary">숙소 고민? 추천 받아볼까요?</button>
                        </div>
                    </div>
                </div>

                <div class="bg__wrap margin-bottom-30" id="course">
                    <div class="wrap__box">
                        <h4 class="wrap__title">
                            추천 여행 코스
                        </h4> 
                        <div class="overflow-x-visible">
                            <div class="capsule-btns">
                                <button type="button" class="capsule-btn active">당일치기</button>
                                <button type="button" class="capsule-btn">1박 2일</button>
                                <button type="button" class="capsule-btn">2박 3일</button>
                                <button type="button" class="capsule-btn">3박 4일</button>
                            </div>
                        </div>

                        <div class="content__wrap margin-bottom-30">
                            <div class="content__item course">
                                <a href="#">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img large">
                                            <img src="https://i.pinimg.com/236x/47/b3/c2/47b3c2739c64dec5430efadf21c69f73.jpg" alt="이미지">
                                        </p>
                                        <div class="thumb__con">
                                            <p class="time">10:00</p>
                                            <p class="detail-name">유달산 등반</p>
                                            <p class="detail-text">목포의 대표 명소 탐방</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img large">
                                            <img src="https://i.pinimg.com/236x/47/b3/c2/47b3c2739c64dec5430efadf21c69f73.jpg" alt="이미지">
                                        </p>
                                        <div class="thumb__con">
                                            <p class="time">12:30</p>
                                            <p class="detail-name">목포 근대역사관</p>
                                            <p class="detail-text">역사의 발자취 따라가기</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img large">
                                            <img src="https://i.pinimg.com/236x/47/b3/c2/47b3c2739c64dec5430efadf21c69f73.jpg" alt="이미지">
                                        </p>
                                        <div class="thumb__con">
                                            <p class="time">15:00</p>
                                            <p class="detail-name">목포 해상 케이블카</p>
                                            <p class="detail-text">하늘에서 보는 목포 전경</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div id="recommendationBox" class="recommendation__box margin-top-30">
                            <div>
                                <p class="text">목포 여행에 관심있다면?</p>
                                <button type="button" id="gettingRecommendation" class="gettingRecommendation">목포 단독 여행 혜택 제안받기</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap__box padding-x-20">
                    <h4 class="wrap__title">한 눈에 보는 지도</h4>
                    
                    <div id="map"></div>
                </div>
            </section>

            <!-- 하단바 변경 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>
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