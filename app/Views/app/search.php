<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

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
        <header class="header__wrap">
            <div class="header__inner">
                <h2 class="header-tit__left">검색</h2>
                <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
            </div>
        </header>

        <div class="container__wrap search__wrap">
            <section class="layout__wrap hieght-full">
                <!-- 검색 폼 -->
                <div class="search-form__wrap">
                    <div class="search-form">
                        <div class="search-form__con search-input">
                            <i class="ico ico-search__small"></i>
                            <div class="input__wrap">
                                <input id="searchText" type="text" class="input" placeholder="#뭉클태그 또는 도시, 상품명 입력" value="<?= !empty($_GET['text']) ? $_GET['text'] : ''; ?>">
                                <button id="searchTextDeleteBtn" type="button" class="btn-input__delete"><i class="ico ico-input__delete"></i></button>
                            </div>

                        </div>
                        <div class="search-form__con search-date fnOpenPop" data-name="popupDate">
                            <i class="ico ico-date"></i>
                            <p class="txt" id="selectedDate">날짜 선택</p>
                        </div>
                        <div class="search-form__con search-guest fnOpenPop" data-name="popupGuest">
                            <i class="ico ico-person"></i>
                            <p class="txt" id="selectedGuests">인원 선택</p>
                        </div>
                    </div>
                </div>
                <!-- //검색 폼 -->

                <!-- 추천 뭉클 태그 -->
                <div id="featuredTags" class="search-tag__wrap" style="display: none;">
                    <div class="tit__wrap">
                        <p class="ft-s">추천 뭉클태그</p>
                    </div>
                    <div class="recommend-list__wrap type-small">
                        <a href="" class="recommend-list">
                            <div>
                                <img src="/assets/app/images/demo/img_recommend.png" alt="">
                                <span>4인가족</span>
                            </div>
                            <div>
                                <img src="/assets/app/images/demo/img_recommend.png" alt="">
                                <span>제주도</span>
                            </div>
                            <div>
                                <img src="/assets/app/images/demo/img_recommend.png" alt="">
                                <span>가성비 중요</span>
                            </div>
                        </a>
                        <a href="" class="recommend-list">
                            <div>
                                <img src="/assets/app/images/demo/img_recommend.png" alt="">
                                <span>4인가족</span>
                            </div>
                            <div>
                                <img src="/assets/app/images/demo/img_recommend.png" alt="">
                                <span>제주도</span>
                            </div>
                            <div>
                                <img src="/assets/app/images/demo/img_recommend.png" alt="">
                                <span>성수기</span>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- //추천 뭉클 태그 -->

                <div id="popularCity" class="select__wrap type-img notfavorite-tag">
                    <div class="tit__wrap">
                        <p class="ft-s">지금 인기 도시</p>
                    </div>
                    <ul style="padding-top: 2rem;">
                        <li class="cursor-pointer" onclick="clickPopularCity('제주', 'region')">
                            <a>
                                <img src="/uploads/tags/jeju.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>제주</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('남해', 'city')">
                            <a>
                                <img src="/uploads/tags/namhae.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>남해</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('강릉', 'city')">
                            <a>
                                <img src="/uploads/tags/gangneung.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>강릉</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('여수', 'city')">
                            <a>
                                <img src="/uploads/tags/yeosu.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>여수</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('경주', 'city')">
                            <a>
                                <img src="/uploads/tags/gyeongju.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>경주</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('가평', 'city')">
                            <a>
                                <img src="/uploads/tags/gapyeong.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>가평</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('거제', 'city')">
                            <a>
                                <img src="/uploads/tags/geoje.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>거제</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularCity('전주', 'city')">
                            <a>
                                <img src="/uploads/tags/jeonju.png?v=<?= $_ENV['VERSION'] ?>" alt="">
                                <span>전주</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div id="popularCompanion" class="select__wrap type-gray">
                    <div class="tit__wrap">
                        <p class="ft-s">숙소 유형별 검색</p>
                    </div>
                    <ul style="padding-top: 2rem;">
                        <li class="cursor-pointer" onclick="clickPopularTag('호텔')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#호텔</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('리조트')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#리조트</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('키즈펜션')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#키즈펜션</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('풀빌라')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#풀빌라</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('펜션')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#펜션</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('한옥')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#한옥</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('게스트하우스')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#게스트하우스</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('카라반')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#카라반</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('글램핑')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#글램핑</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('캠핑')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#캠핑</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('감성 넘치는 분위기')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#감성숙소</span>
                            </a>
                        </li>
                        <li class="cursor-pointer" onclick="clickPopularTag('애견펜션')">
                            <a>
                                <span class="tag" style="font-size: 1.4rem;">#애견펜션</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- 인기 뭉클 태그 -->
                <div id="popularTags" class="search-tag__wrap">
                    <div class="tit__wrap">
                        <p class="ft-s">인기 뭉클태그</p>
                    </div>
                    <div class="popular-list__wrap">
                        <div class="popular-list"><a onclick="clickPopularTag('도심 속 호캉스')">도심 속 호캉스</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('수영장')">수영장</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('사계절 온수/미온수풀')">사계절 온수/미온수풀</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('커플기념일')">커플기념일</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('아이와 갈만한 곳')">아이와 갈만한 곳</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('바다 전망')">바다 전망</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('OTT(넷플릭스 등)')">OTT(넷플릭스 등)</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('로맨틱 분위기')">로맨틱 분위기</a></div>
                        <div class="popular-list"><a onclick="clickPopularTag('조식 서비스')">조식 서비스</a></div>
                    </div>
                </div>
                <!-- //인기 뭉클 태그 -->

                <div id="realTimeSearch" class="category-list__wrap hidden">
                    <ul>
                        <li id="realtimeTag" class="hidden">
                            <p class="title">뭉클태그</p>
                            <div id="realtimeTagList" class="category-list__con category-hashtag">
                            </div>
                        </li>
                        <li id="realtimeRegion" class="hidden">
                            <p class="title">지역</p>
                            <div id="realtimeRegionList" class="category-list__con category-location">
                            </div>
                        </li>
                        <li id="realtimeCity" class="hidden">
                            <p class="title">도시</p>
                            <div id="realtimeCityList" class="category-list__con category-location">
                            </div>
                        </li>
                        <li id="realtimeCountry" class="hidden">
                            <p class="title">국가</p>
                            <div id="realtimeCountryList" class="category-list__con category-national">
                            </div>
                        </li>
                        <li id="realtimeLandmark" class="hidden">
                            <p class="title">랜드마크</p>
                            <div id="realtimeLandmarkList" class="category-list__con category-landmark">
                            </div>
                        </li>
                        <li id="realtimeHub" class="hidden">
                            <p class="title">교통중심지</p>
                            <div id="realtimeHubList" class="category-list__con category-bus">
                            </div>
                        </li>
                        <li id="realtimeStay" class="hidden">
                            <p class="title">숙소</p>
                            <div id="realtimeStayList" class="category-list__con category-hotel">
                            </div>
                        </li>
                        <li id="realtimeActivity" class="hidden">
                            <p class="title">액티비티</p>
                            <div id="realtimeActivityList" class="category-list__con category-activity">
                            </div>
                        </li>
                        <li id="realtimeTour" class="hidden">
                            <p class="title">투어</p>
                            <div id="realtimeTourList" class="category-list__con category-tour">
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="nodata__wrap hidden">
                    <div class="nodata__con">
                        <span id="nodata-text" class="txt-primary"></span>에 대한 검색 결과가 없습니다. <br>
                        다른 검색어를 입력하시거나<br>
                        철자를 확인해보세요.
                    </div>
                </div>
            </section>

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

        </div>

        <!-- 바텀 팝업(일정 선택) -->
        <div id="popupDate" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">일정 선택</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <!-- 날짜 선택 -->
                    <div class="tab__wrap tab-switch__wrap">
                        <!-- <ul class="tab__inner">
                            <li class="tab-switch__con active">
                                <a>날짜 선택</a>
                            </li>
                            <li class="tab-switch__con">
                                <a>월 선택</a>
                            </li>
                        </ul> -->
                        <div class="tab-contents__wrap">
                            <!-- 날짜 선택 탭 -->
                            <div class="tab-contents active">
                                <div class="calendar-wrap">
                                    <div class="placeholder"></div>
                                </div>
                            </div>
                            <!-- //날짜 선택 탭 -->
                            <!-- 월 선택 탭 -->
                            <div class="tab-contents">
                                <div class="select__wrap col-3 multi-select">
                                    <p class="title">2024년</p>
                                    <ul>
                                        <li><a>9월</a></li>
                                        <li><a>10월</a></li>
                                        <li><a>11월</a></li>
                                        <li><a>12월</a></li>
                                    </ul>

                                    <p class="title">2025년</p>
                                    <ul>
                                        <li><a>1월</a></li>
                                        <li><a>1월</a></li>
                                        <li><a>3월</a></li>
                                        <li><a>4월</a></li>
                                        <li><a>5월</a></li>
                                        <li><a>6월</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- //월 선택 탭 -->
                        </div>
                    </div>
                    <!-- //날짜 선택 -->
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__line__primary" id="undecidedBtn">날짜 미정</button>
                        <button class="btn-full__primary" id="selectDateBtn">선택</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //바텀 팝업(일정 선택) -->

        <!-- 바텀 팝업(인원 선택) -->
        <div id="popupGuest" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">인원 선택</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="count__wrap">
                        <ul>
                            <li>
                                <p class="ft-default">성인</p>
                                <div class="count__con">
                                    <button type="button" class="btn-count btn-count__minus" data-type="adult"><i class="ico ico-count__minus"></i></button>
                                    <span class="num" id="adultCount">0</span>
                                    <button type="button" class="btn-count btn-count__plus" data-type="adult"><i class="ico ico-count__plus"></i></button>
                                </div>
                            </li>
                            <li>
                                <p class="ft-default">아동</p>
                                <div class="count__con">
                                    <button type="button" class="btn-count btn-count__minus" data-type="child"><i class="ico ico-count__minus"></i></button>
                                    <span class="num" id="childCount">0</span>
                                    <button type="button" class="btn-count btn-count__plus" data-type="child"><i class="ico ico-count__plus"></i></button>
                                </div>

                                <div class="count-age__wrap" id="countAgeWrap"></div>
                            </li>
                            <li>
                                <p class="ft-default">유아</p>
                                <div class="count__con">
                                    <button type="button" class="btn-count btn-count__minus" data-type="infant"><i class="ico ico-count__minus"></i></button>
                                    <span class="num" id="infantCount">0</span>
                                    <button type="button" class="btn-count btn-count__plus" data-type="infant"><i class="ico ico-count__plus"></i></button>
                                </div>

                                <div class="count-age__wrap" id="countMonthWrap"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary" id="selectGuestsBtn">선택</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //바텀 팝업(인원 선택) -->

        <!-- 바텀 팝업(나이 선택) -->
        <div id="popupAge" class="layerpop__wrap layerpop__select">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">나이 선택</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap type-list single-select">
                        <ul id="ageOptions">
                            <li><a data-age="1">만 0세</a></li>
                            <li><a data-age="1">만 1세</a></li>
                            <li><a data-age="2">만 2세</a></li>
                            <li><a data-age="3">만 3세</a></li>
                            <li><a data-age="4">만 4세</a></li>
                            <li><a data-age="5">만 5세</a></li>
                            <li><a data-age="6">만 6세</a></li>
                            <li><a data-age="7">만 7세</a></li>
                            <li><a data-age="8">만 8세</a></li>
                            <li><a data-age="9">만 9세</a></li>
                            <li><a data-age="10">만 10세</a></li>
                            <li><a data-age="11">만 11세</a></li>
                            <li><a data-age="12">만 12세</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- //바텀 팝업(나이 선택) -->

        <!-- 바텀 팝업(개월수 선택) -->
        <div id="popupMonth" class="layerpop__wrap layerpop__select">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">개월수 선택</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap type-list single-select">
                        <ul id="monthOptions">
                            <li><a data-month="1">1개월</a></li>
                            <li><a data-month="2">2개월</a></li>
                            <li><a data-month="3">3개월</a></li>
                            <li><a data-month="4">4개월</a></li>
                            <li><a data-month="5">5개월</a></li>
                            <li><a data-month="6">6개월</a></li>
                            <li><a data-month="7">7개월</a></li>
                            <li><a data-month="8">8개월</a></li>
                            <li><a data-month="9">9개월</a></li>
                            <li><a data-month="10">10개월</a></li>
                            <li><a data-month="11">11개월</a></li>
                            <li><a data-month="12">12개월</a></li>
                            <li><a data-month="13">13개월</a></li>
                            <li><a data-month="14">14개월</a></li>
                            <li><a data-month="15">15개월</a></li>
                            <li><a data-month="16">16개월</a></li>
                            <li><a data-month="17">17개월</a></li>
                            <li><a data-month="18">18개월</a></li>
                            <li><a data-month="19">19개월</a></li>
                            <li><a data-month="20">20개월</a></li>
                            <li><a data-month="21">21개월</a></li>
                            <li><a data-month="22">22개월</a></li>
                            <li><a data-month="23">23개월</a></li>
                            <li><a data-month="24">24개월</a></li>
                            <li><a data-month="25">25개월</a></li>
                            <li><a data-month="26">26개월</a></li>
                            <li><a data-month="27">27개월</a></li>
                            <li><a data-month="28">28개월</a></li>
                            <li><a data-month="29">29개월</a></li>
                            <li><a data-month="30">30개월</a></li>
                            <li><a data-month="31">31개월</a></li>
                            <li><a data-month="32">32개월</a></li>
                            <li><a data-month="33">33개월</a></li>
                            <li><a data-month="34">34개월</a></li>
                            <li><a data-month="35">35개월</a></li>
                            <li><a data-month="36">36개월</a></li>
                            <li><a data-month="37">37개월</a></li>
                            <li><a data-month="38">38개월</a></li>
                            <li><a data-month="39">39개월</a></li>
                            <li><a data-month="40">40개월</a></li>
                            <li><a data-month="41">41개월</a></li>
                            <li><a data-month="42">42개월</a></li>
                            <li><a data-month="43">43개월</a></li>
                            <li><a data-month="44">44개월</a></li>
                            <li><a data-month="45">45개월</a></li>
                            <li><a data-month="46">46개월</a></li>
                            <li><a data-month="47">47개월</a></li>
                            <li><a data-month="48">48개월</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- //바텀 팝업(개월수 선택) -->

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

        let params = new URLSearchParams(window.location.search);
        let filterStartDate = params.get('startDate') || '';
        let filterEndDate = params.get('endDate') || '';
        let filterAdult = parseInt(params.get('adult')) || 0;
        let filterChild = parseInt(params.get('child')) || 0;
        let filterInfant = parseInt(params.get('infant')) || 0;
        let filterChildAge = JSON.parse(params.get('childAge')) || {};
        let filterInfantMonth = JSON.parse(params.get('infantMonth')) || {};

        function updateSelectedDateText() {
            let dateText = '날짜 선택';

            if (filterStartDate && filterEndDate) {
                dateText = filterStartDate + '~' + filterEndDate;
            }

            document.getElementById('selectedDate').textContent = dateText;
        }

        // 페이지 로드 시 초기화
        updateSelectedDateText();

        flatpickr('.calendar-wrap .placeholder', {
            inline: true,
            minDate: 'today',
            mode: "range",
            locale: "ko",
            altFormat: "m.d D",
            dateFormat: "Y-m-d",
            locale: {
                firstDayOfWeek: 0, // 일요일을 주의 시작으로 설정
                weekdays: {
                    shorthand: ["일", "월", "화", "수", "목", "금", "토"], // 요일을 한글로 표시
                    longhand: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"]
                },
                months: {
                    shorthand: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                    longhand: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                },
            },
            defaultDate: filterStartDate && filterEndDate ? [filterStartDate, filterEndDate] : null,
            onReady: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    // 디폴트 값으로 선택된 날짜를 표시
                    const formattedStartDate = instance.formatDate(selectedDates[0], "m.d D");
                    const formattedEndDate = instance.formatDate(selectedDates[1], "m.d D");
                    document.getElementById("selectedDate").textContent = `${formattedStartDate} ~ ${formattedEndDate}`;
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    if (selectedDates[0].getTime() === selectedDates[1].getTime()) {
                        const newEndDate = new Date(selectedDates[0]);
                        newEndDate.setDate(newEndDate.getDate() + 1);
                        selectedDates[1] = newEndDate;
                        instance.setDate([selectedDates[0], newEndDate]);
                    }

                    const startDate = instance.formatDate(selectedDates[0], "m.d D");
                    const endDate = instance.formatDate(selectedDates[1], "m.d D");
                    document.getElementById("selectedDate").textContent = `${startDate} ~ ${endDate}`;

                    filterStartDate = instance.formatDate(selectedDates[0], "Y-m-d");
                    filterEndDate = instance.formatDate(selectedDates[1], "Y-m-d");
                }
            },
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateObj = new Date(dayElem.dateObj.getTime() + (9 * 60 * 60 * 1000));
                const date = dateObj.toISOString().split('T')[0];

                const today = new Date(new Date().getTime() + (9 * 60 * 60 * 1000))
                    .toISOString()
                    .split('T')[0];

                const currentMonth = fp.currentMonth;
                const currentYear = fp.currentYear;

                const dayMonth = dateObj.getMonth();
                const dayYear = dateObj.getFullYear();

                if (date < today) {
                    dayElem.classList.add('flatpickr-disabled');
                    return;
                }

                if (dayYear === currentYear && dayMonth === currentMonth) {
                    const dayOfWeek = dateObj.getDay();

                    if (dayOfWeek === 0) {
                        dayElem.style.color = "red";
                    } else if (dayOfWeek === 6) {
                        dayElem.style.color = "blue";
                    }
                }
            }
        });

        // 선택된 나이를 저장하는 객체
        let selectedAges = {};
        let selectedMonths = {};

        let adultCount = 0;
        let childCount = 0;
        let infantCount = 0;

        // 선택된 인원 수 및 나이 초기화
        function initializeGuestSettings() {
            // 인원 수 초기화
            document.getElementById('adultCount').textContent = filterAdult;
            document.getElementById('childCount').textContent = filterChild;
            document.getElementById('infantCount').textContent = filterInfant;

            adultCount = filterAdult;
            childCount = filterChild;
            infantCount = filterInfant;
            selectedAges = filterChildAge;
            selectedMonths = filterInfantMonth;

            // 각 인원 수에 따라 나이 선택 항목 초기화
            initializeChildAges(filterChild, selectedAges);
            initializeInfantAges(filterInfant, selectedMonths);
            updateSelectedGuestsText();
        }

        // 선택한 인원을 표시하는 함수
        function updateSelectedGuestsText() {
            let guestText = '';

            // 선택된 인원수에 따라 텍스트 설정
            if (filterAdult > 0 || filterChild > 0 || filterInfant > 0) {
                guestText = `성인 ${filterAdult}`;
                if (filterChild > 0) guestText += `, 아동 ${filterChild}`;
                if (filterInfant > 0) guestText += `, 유아 ${filterInfant}`;
            } else {
                guestText = '인원 선택';
            }

            document.getElementById('selectedGuests').textContent = guestText;
        }

        // 아동 수에 따라 나이 선택 항목 업데이트
        function initializeChildAges(childCount, selectedAges = {}) {
            const countAgeWrap = document.getElementById('countAgeWrap');

            for (let i = 1; i <= childCount; i++) {
                const ageDiv = document.createElement('div');
                ageDiv.className = 'count-age';
                ageDiv.innerHTML = `
                    <p class="ft-xxs">아동 <span>${i}</span></p>
                    <div class="count-select" data-name="popupAge" data-child="${i}">
                        <p>${selectedAges[i] ? `만 ${selectedAges[i]}세` : '나이 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
                ageDiv.querySelector('.count-select').addEventListener('click', openAgePopup);
                countAgeWrap.appendChild(ageDiv);
            }
        }

        // 유아 수에 따라 개월수 선택 항목 업데이트
        function initializeInfantAges(infantCount, selectedMonths = {}) {
            const countMonthWrap = document.getElementById('countMonthWrap');

            for (let i = 1; i <= infantCount; i++) {
                const monthDiv = document.createElement('div');
                monthDiv.className = 'count-age';
                monthDiv.innerHTML = `
                    <p class="ft-xxs">유아 <span>${i}</span></p>
                    <div class="count-select" data-name="popupMonth" data-infant="${i}">
                        <p>${selectedMonths[i] ? `${selectedMonths[i]}개월` : '개월수 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
                monthDiv.querySelector('.count-select').addEventListener('click', openMonthPopup);
                countMonthWrap.appendChild(monthDiv);
            }
        }

        // 페이지 로드 시 초기화
        initializeGuestSettings();

        const inputField = document.getElementById('searchText');
        const deleteButton = document.getElementById('searchTextDeleteBtn');
        const featuredTags = document.getElementById('featuredTags');
        const popularTags = document.getElementById('popularTags');
        const popularCity = document.getElementById('popularCity');
        const popularCompanion = document.getElementById('popularCompanion');
        const realTimeSearch = document.getElementById('realTimeSearch');

        // 입력 필드에서 입력이 발생할 때마다 실행
        inputField.addEventListener('input', () => {
            if (inputField.value.trim() !== '') {
                featuredTags.classList.add('hidden');
                popularTags.classList.add('hidden');
                popularCity.classList.add('hidden');
                popularCompanion.classList.add('hidden');
                realTimeSearch.classList.remove('hidden');
            } else {
                featuredTags.classList.remove('hidden');
                popularTags.classList.remove('hidden');
                popularCity.classList.remove('hidden');
                popularCompanion.classList.remove('hidden');
                realTimeSearch.classList.add('hidden');
            }
        });

        // 삭제 버튼 클릭 시 입력 필드 초기화
        deleteButton.addEventListener('click', () => {
            inputField.value = '';
            featuredTags.classList.remove('hidden');
            popularTags.classList.remove('hidden');
            popularCity.classList.remove('hidden');
            popularCompanion.classList.remove('hidden');
            realTimeSearch.classList.add('hidden');
            inputField.focus(); // 포커스 유지
        });

        // 인원 수 조절 버튼 이벤트
        document.querySelectorAll('.btn-count').forEach(button => {
            button.addEventListener('click', () => {
                const type = button.getAttribute('data-type');
                const isPlus = button.classList.contains('btn-count__plus');
                const countElement = document.getElementById(`${type}Count`);
                let currentCount = parseInt(countElement.textContent);

                // 인원 수 증감 로직
                if (isPlus) {
                    currentCount += 1;
                } else {
                    if (currentCount > 0) {
                        currentCount -= 1;
                    }
                }
                countElement.textContent = currentCount;

                // 아동 수 변경 시 나이 선택 항목 동적 생성
                if (type === 'child') {
                    updateChildAges(currentCount);

                    // 아동 수가 감소할 경우 선택된 나이에서 제거
                    if (!isPlus) {
                        removeLastChildAge(currentCount);
                    }
                }

                if (type === 'infant') {
                    updateInfantAges(currentCount);

                    // 아동 수가 감소할 경우 선택된 나이에서 제거
                    if (!isPlus) {
                        removeLastInfantAge(currentCount);
                    }
                }

                // 현재 인원 수 업데이트
                if (type === 'adult') {
                    adultCount = currentCount;
                    filterAdult = adultCount;
                } else if (type === 'child') {
                    childCount = currentCount;
                    filterChild = currentCount;
                } else if (type === 'infant') {
                    infantCount = currentCount;
                    filterInfant = currentCount;
                }
            });
        });

        // 아동 수에 따라 나이 선택 항목 업데이트
        function updateChildAges(childCount) {
            const countAgeWrap = document.getElementById('countAgeWrap');

            // 현재 아동 수보다 작은 수만큼의 기존 요소만 남기고 나머지를 추가
            const currentChildren = countAgeWrap.children.length;

            for (let i = currentChildren + 1; i <= childCount; i++) {
                const ageDiv = document.createElement('div');
                ageDiv.className = 'count-age';
                ageDiv.innerHTML = `
                <p class="ft-xxs">아동 <span>${i}</span></p>
                    <div class="count-select" data-name="popupAge" data-child="${i}">
                        <p>${selectedAges[i] ? `만 ${selectedAges[i]}세` : '나이 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
                ageDiv.querySelector('.count-select').addEventListener('click', openAgePopup);
                countAgeWrap.appendChild(ageDiv);
            }

            // 남은 아동 수보다 많은 항목이 있으면 잘라내기
            while (countAgeWrap.children.length > childCount) {
                countAgeWrap.removeChild(countAgeWrap.lastElementChild);
            }
        }

        function updateInfantAges(infantCount) {
            const countMonthWrap = document.getElementById('countMonthWrap');

            // 현재 아동 수보다 작은 수만큼의 기존 요소만 남기고 나머지를 추가
            const currentChildren = countMonthWrap.children.length;

            for (let i = currentChildren + 1; i <= infantCount; i++) {
                const monthDiv = document.createElement('div');
                monthDiv.className = 'count-age';
                monthDiv.innerHTML = `
                <p class="ft-xxs">유아 <span>${i}</span></p>
                    <div class="count-select" data-name="popupMonth" data-infant="${i}">
                        <p>${selectedMonths[i] ? `${selectedMonths[i]}개월` : '개월수 선택'}</p>
                        <i class="ico ico-arrow__down"></i>
                    </div>
                `;
                monthDiv.querySelector('.count-select').addEventListener('click', openMonthPopup);
                countMonthWrap.appendChild(monthDiv);
            }

            // 남은 아동 수보다 많은 항목이 있으면 잘라내기
            while (countMonthWrap.children.length > infantCount) {
                countMonthWrap.removeChild(countMonthWrap.lastElementChild);
            }
        }

        // 선택된 나이에서 마지막 아동 나이 제거
        function removeLastChildAge(newCount) {
            const lastChildIndex = newCount + 1;
            delete selectedAges[lastChildIndex]; // 마지막 아동 나이 제거
        }

        function removeLastInfantAge(newCount) {
            const lastInfantIndex = newCount + 1;
            delete selectedMonths[lastInfantIndex];
        }

        // 나이 선택 팝업 열기
        function openAgePopup(event) {
            const childIndex = event.target.closest('.count-select').getAttribute('data-child');
            const popup = document.getElementById('popupAge');
            popup.classList.add('show');
            popup.dataset.childIndex = childIndex;
        }

        function openMonthPopup(event) {
            const childIndex = event.target.closest('.count-select').getAttribute('data-infant');
            const popup = document.getElementById('popupMonth');
            popup.classList.add('show');
            popup.dataset.childIndex = childIndex;
        }

        // 나이 선택 후 값 저장 및 팝업 닫기
        document.getElementById('ageOptions').addEventListener('click', event => {
            event.preventDefault(); // <a> 태그의 기본 동작 막기

            if (event.target.tagName === 'A') {
                const selectedAge = event.target.getAttribute('data-age');
                const childIndex = document.getElementById('popupAge').dataset.childIndex;

                // 선택한 나이를 객체에 저장
                selectedAges[childIndex] = selectedAge;

                // 선택한 나이로 텍스트 업데이트
                const countSelect = document.querySelector(`.count-select[data-child="${childIndex}"] p`);
                countSelect.textContent = `만 ${selectedAge}세`;

                document.getElementById('popupAge').classList.remove('show');
            }
        });

        document.getElementById('monthOptions').addEventListener('click', event => {
            event.preventDefault(); // <a> 태그의 기본 동작 막기

            if (event.target.tagName === 'A') {
                const selectedMonth = event.target.getAttribute('data-month');
                const childIndex = document.getElementById('popupMonth').dataset.childIndex;

                // 선택한 나이를 객체에 저장
                selectedMonths[childIndex] = selectedMonth;

                // 선택한 나이로 텍스트 업데이트
                const countSelect = document.querySelector(`.count-select[data-infant="${childIndex}"] p`);
                countSelect.textContent = `${selectedMonth}개월`;

                document.getElementById('popupMonth').classList.remove('show');
            }
        });

        // 팝업 바깥 클릭 시 닫기
        document.getElementById('popupAge').addEventListener('click', (event) => {
            if (event.target === document.getElementById('popupAge')) {
                document.getElementById('popupAge').classList.remove('show');
            }
        });

        document.getElementById('popupMonth').addEventListener('click', (event) => {
            if (event.target === document.getElementById('popupMonth')) {
                document.getElementById('popupMonth').classList.remove('show');
            }
        });

        document.getElementById('selectDateBtn').addEventListener('click', () => {
            document.getElementById('popupDate').classList.remove('show');
        });

        document.getElementById('undecidedBtn').addEventListener('click', () => {
            const selectedDateText = `날짜 선택`;
            document.getElementById('selectedDate').textContent = `${selectedDateText}`;
            document.getElementById('popupDate').classList.remove('show');
        });

        // "선택" 버튼 클릭 시 인원 수 업데이트 및 팝업 닫기
        document.getElementById('selectGuestsBtn').addEventListener('click', () => {
            const selectedGuestsText = `성인 ${adultCount}, 아동 ${childCount}, 유아 ${infantCount}`;
            document.getElementById('selectedGuests').textContent = `${selectedGuestsText}`;
            document.getElementById('popupGuest').classList.remove('show');
        });

        $(document).ready(function() {
            $('.input').on('input', function() {
                if ($(this).val().length > 0) {
                    $('.btn-input__delete').show();
                } else {
                    $('.btn-input__delete').hide();
                }
            });
            $('.btn-input__delete').click(function() {
                $('.input').val('');
                $(this).hide();
            });
        });

        // debounce 함수: 일정 시간 동안 입력이 멈추면 함수를 호출
        function debounce(func, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // 검색 API 호출 함수
        async function search(query) {
            if (!query) {
                return;
            }

            try {
                const response = await fetch(`/api/search/real-time?query=${encodeURIComponent(query)}`);
                const data = await response.json();
                displayResults(data.searchResult);
            } catch (error) {
                console.error("검색 오류:", error);
            }
        }

        function toggleNoDataMessage(hasResults, query) {
            const noDataWrap = document.querySelector('.nodata__wrap');
            const noDataText = document.getElementById('nodata-text');

            if (hasResults) {
                // 검색 결과가 있는 경우
                noDataWrap.classList.add('hidden');
            } else {
                // 검색 결과가 없는 경우
                noDataText.textContent = query || "입력하신 검색어"; // 검색어가 없으면 기본 텍스트
                noDataWrap.classList.remove('hidden');
            }
        }

        // 검색 결과 표시 함수
        function displayResults(results) {
            const categories = [{
                    id: 'realtimeRegionList',
                    data: results.region,
                    parentId: 'realtimeRegion',
                    type: 'region'
                }, {
                    id: 'realtimeCityList',
                    data: results.city,
                    parentId: 'realtimeCity',
                    type: 'city'
                },
                {
                    id: 'realtimeCountryList',
                    data: results.country,
                    parentId: 'realtimeCountry',
                    type: 'country'
                },
                {
                    id: 'realtimeLandmarkList',
                    data: results.landmark,
                    parentId: 'realtimeLandmark',
                    type: 'landmark'
                },
                {
                    id: 'realtimeHubList',
                    data: results.hub,
                    parentId: 'realtimeHub',
                    type: 'hub'
                },
                {
                    id: 'realtimeStayList',
                    data: results.stay,
                    parentId: 'realtimeStay',
                    type: 'stay'
                },
                {
                    id: 'realtimeActivityList',
                    data: results.activity,
                    parentId: 'realtimeActivity',
                    type: 'activity'
                },
                {
                    id: 'realtimeTourList',
                    data: results.tour,
                    parentId: 'realtimeTour',
                    type: 'tour'
                },
                {
                    id: 'realtimeTagList',
                    data: results.tag,
                    parentId: 'realtimeTag',
                    type: 'tag'
                }
            ];

            let hasResults = false;
            const queryText = document.getElementById("searchText").value.trim();

            categories.forEach(category => {
                const container = document.getElementById(category.id);
                const parent = document.getElementById(category.parentId);

                // 결과가 있는 경우
                if (category.data && category.data.length > 0) {
                    hasResults = true;

                    container.innerHTML = category.data.map(item => `
                        <a href="#" data-query="${item}">
                            <p class="ft-s">${highlightText(item, queryText)}</p>
                        </a>
                    `).join('');
                    parent.style.display = 'block'; // li 요소 표시
                } else {
                    container.innerHTML = '';
                    parent.style.display = 'none'; // 결과가 없는 경우 li 요소 숨기기
                }

                // 각 링크에 클릭 이벤트 추가하여 searchText 쿼리스트링으로 페이지 이동
                container.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();

                        showLoader();

                        const query = link.getAttribute('data-query');
                        const queryParams = new URLSearchParams({
                            text: query,
                            categoryType: category.type,
                            startDate: filterStartDate,
                            endDate: filterEndDate,
                            adult: adultCount,
                            child: childCount,
                            infant: infantCount,
                            childAge: JSON.stringify(selectedAges),
                            infantMonth: JSON.stringify(selectedMonths),
                        });
                        window.location.href = `/search-result?${queryParams.toString()}`;
                    });
                });
            });

            toggleNoDataMessage(hasResults, queryText);
        }

        function clickPopularTag(query) {
            showLoader();

            const queryParams = new URLSearchParams({
                text: query,
                categoryType: 'tag',
                startDate: filterStartDate,
                endDate: filterEndDate,
                adult: adultCount,
                child: childCount,
                infant: infantCount,
                childAge: JSON.stringify(selectedAges),
                infantMonth: JSON.stringify(selectedMonths),
            });
            window.location.href = `/search-result?${queryParams.toString()}`;
        }

        function clickPopularCity(query, region) {
            showLoader();

            const queryParams = new URLSearchParams({
                text: query,
                categoryType: region,
                startDate: filterStartDate,
                endDate: filterEndDate,
                adult: adultCount,
                child: childCount,
                infant: infantCount,
                childAge: JSON.stringify(selectedAges),
                infantMonth: JSON.stringify(selectedMonths),
            });
            window.location.href = `/search-result?${queryParams.toString()}`;
        }

        function highlightText(text, query) {
            if (!query) {
                return text; // 검색어가 없으면 원본 텍스트 반환
            }
            // 검색어를 대소문자 구분 없이 강조
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<span class="txt-primary ft-bold">$1</span>');
        }

        // 입력 필드에서 debounce 된 검색 호출 설정
        inputField.addEventListener("keyup", debounce(function() {
            search(this.value);
        }, 500));

        // 페이지 로드 시 기본값으로 검색 함수 실행
        window.addEventListener("load", function() {
            if (inputField.value) {
                featuredTags.classList.add('hidden');
                popularTags.classList.add('hidden');
                popularCity.classList.add('hidden');
                popularCompanion.classList.add('hidden');
                realTimeSearch.classList.remove('hidden');
                deleteButton.style.display = 'inline-block';

                // 기본값을 이용해 debounce 적용 후 검색 실행
                debounce(function() {
                    search(inputField.value);
                }, 100)();
            }
        });

        // inputField.addEventListener('keydown', function(event) {
        //     if (event.key === "Enter" && inputField.value) {
        //         const queryParams = new URLSearchParams({
        //             text: inputField.value,
        //             categoryType: 'text',
        //             startDate: filterStartDate,
        //             endDate: filterEndDate,
        //             adult: adultCount,
        //             child: childCount,
        //             infant: infantCount,
        //             childAge: JSON.stringify(selectedAges),
        //             infantMonth: JSON.stringify(selectedMonths),
        //         });

        //         showLoader();
        //         window.location.href = `/search-result?${queryParams.toString()}`;
        //     }
        // });
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>