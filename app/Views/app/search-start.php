<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

?>

<!-- Head -->
<?php 
    $page_title_01 = "숙소·여행 상품 검색";

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

    <div id="mobileWrap" style="position: relative;">

        <!-- 검색 -->
        <div class="search__new show">
            <header class="header__wrap" style="height: auto;">
                <div class="header__inner" style="gap: 10px; padding: 1rem 2rem;">
                    <button class="btn-back" onclick="goBackToSearchHome()"><span class="blind">뒤로가기</span></button>
                    <div class="search-form__con search-input custom">
                        <i class="ico ico-search__small"></i>
                        <div class="input__wrap">
                            <input 
                                id="searchText" 
                                type="text" 
                                class="input" 
                                placeholder="아이와 함께 떠나볼까요?" 
                                value="<?= !empty($_GET['text']) ? $_GET['text'] : ''; ?>">
                            <button id="searchTextDeleteBtn" type="button" class="btn-input__delete"><i class="ico ico-input__delete"></i></button>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container__wrap search__wrap new">
                <!-- 현재 내 주변 탐색 -->
                <div class="around_me margin-bottom-30">
                    <p>
                        <a href="/search-map" id="goToMapBtn"><img src="/assets/app/images/common/ico_search_location.jpg" alt="위치 아이콘" style="width: 1.5rem;"> 현재 내 주변 탐색</a>
                    </p>
                </div>

                <section class="layout__wrap" style="padding: 0; padding-bottom: 8.4rem;">
                    <!-- 최근 탐색어 -->
                    <div id="recentSearchWords" class="type-img margin-bottom-30 padding-x-20">
                        <div class="tit__wrap">
                            <p>최근 탐색어</p>
                            <button type="button" id="allDelete" class="allDelete">지우기</button>
                        </div>
                        <ul id="searchWordList" class="searchWordList"></ul>
                    </div>
                    <!-- //최근 탐색어 -->

                    <!-- 최근 본 숙소 -->
                    <div id="recentStay" class="type-img margin-bottom-30">
                        <div class="tit__wrap padding-x-20">
                            <p>최근 본 숙소</p>
                        </div>
                        <div class="overflow-x-visible padding-x-20">
                            <ul id="recentStayList" class="recentStayList"></ul>
                        </div>
                    </div>
                    <!-- //최근 본 숙소 -->

                    <!-- 인기 여행 -->
                    <div id="popularTrip" class="type-img">
                        <div class="tit__wrap padding-x-20">
                            <p>인기 여행</p>
                        </div>
                        <div class="overflow-x-visible padding-x-20">
                            <ul class="popularTripList">
                                <?php foreach ($data['popularTerms'] as $popularTerm) : ?>
                                    <li onclick="clickPopularTrip('<?= $popularTerm['term_name']; ?>', '<?= $popularTerm['category_type']; ?>')">
                                        <div class="img__box">
                                            <img src="<?= $popularTerm['image_path']; ?>" alt="인기 여행 이미지">
                                        </div>
                                        <p class="trip__name"><?= $popularTerm['term_name']; ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- //인기 여행 -->                

                    <!-- 요즘 뜨는 소도시 -->
                    <div id="popularSmallTown" class="type-img margin-top-30">
                        <div class="tit__wrap padding-x-20">
                            <p>요즘 뜨는 소도시</p>
                        </div>
                        <div class="overflow-x-visible padding-x-20">
                            <ul class="popularSmallTownList">
                                <?php foreach ($data['risingCities'] as $risingCity) : ?>
                                    <li onclick="clickPopularTrip('<?= $risingCity['term_name']; ?>', '<?= $risingCity['category_type']; ?>')">
                                        <div class="img__box">
                                            <img src="<?= $risingCity['image_path']; ?>" alt="인기 여행 이미지">
                                        </div>
                                        <p class="trip__name"><?= $risingCity['term_name']; ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- //요즘 뜨는 소도시 -->                

                    <!-- 우리 아이와 함께 숙소, 고민된다면? -->
                    <div class="padding-x-20">
                        <div id="recommendationBox" class="recommendation__box margin-top-30">
                            <div>
                                <p class="text">우리 아이와 함께 숙소, 고민된다면?</p>
                                <button type="button" id="gettingRecommendation" class="gettingRecommendation" onclick="location.href='/moongcledeals'">맘 편하게 숙소 추천 받기</button>
                            </div>
                        </div>
                    </div>
                    <!-- //우리 아이와 함께 숙소, 고민된다면? -->

                    <!-- 검색 결과 -->
                    <div id="realTimeSearch" class="category-list__wrap hidden padding-x-20" style="padding-top: 0;">
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
                    <!-- //검색 결과 -->
                    
                    <div class="nodata__wrap hidden">
                        <div class="nodata__con">
                            <span id="nodata-text" class="txt-primary"></span>에 대한 검색 결과가 없습니다. <br>
                            다른 검색어를 입력하시거나<br>
                            철자를 확인해보세요.
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- //검색 -->

        <!-- 하단바 변경 -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>
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

        document.getElementById('goToMapBtn')?.addEventListener('click', function (e) {
            e.preventDefault();
            sessionStorage.setItem('search', 'search-start');

            window.location.href = `/search-map`;
        });
    </script>

    <script>
        // 진입 애니메이션
        const searchAnimation = document.querySelector('.search__animation');
        const searchNew = document.querySelector('.search__new');
        const searchInput = document.getElementById('searchText');

        // 애니메이션 초기화
        function resetAnimation() {
            searchAnimation.style.removeProperty('display');
            searchAnimation.classList.remove('hide');
            searchNew.classList.remove('show');
        }

        // 애니메이션 실행
        function startSearchAnimation() {
            if (!searchAnimation || !searchNew) return;

            searchAnimation.classList.add('hide');

            setTimeout(() => {
                searchNew.classList.add('show');
            }, 300);

            setTimeout(() => {
                searchAnimation.style.display = 'none';
                forceFocus();
            }, 500);
        }

        // 모바일 사파리 대응 focus 강제 처리
        function forceFocus() {
            requestAnimationFrame(() => {
                setTimeout(() => {
                    searchInput.focus();
                }, 50);  
            });
        }

        // 뒤로가기
        function goBackToSearchHome() {
            setTimeout(() => {
                window.history.back();
            }, 300);
        }
 
        // 진입 / 뒤로가기 복원
        window.addEventListener('pageshow', function() {
            resetAnimation();
            startSearchAnimation();
        });
    </script>

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });

        const inputField = document.getElementById('searchText');

        const deleteButton = document.getElementById('searchTextDeleteBtn');
        const recentSearchWords = document.getElementById('recentSearchWords');
        const popularTrip = document.getElementById('popularTrip');
        const popularSmallTown = document.getElementById('popularSmallTown');
        const recommendationBox = document.getElementById('recommendationBox');
        const recentStay = document.getElementById('recentStay');

        const realTimeSearch = document.getElementById('realTimeSearch');

        // 입력 필드에서 입력이 발생할 때마다 실행
        inputField.addEventListener('input', () => {
            if (inputField.value.trim() !== '') {
                recentSearchWords.classList.add('hidden');
                popularTrip.classList.add('hidden');
                popularSmallTown.classList.add('hidden');
                recommendationBox.classList.add('hidden');
                recentStay.classList.add('hidden');
                deleteButton.style.display = 'block';
                realTimeSearch.classList.remove('hidden');
            } else {
                 // 최근 검색어가 있으면 보여주기
                if (getSearchWords().length > 0) {
                    recentSearchWords.classList.remove('hidden');
                } else {
                    recentSearchWords.classList.add('hidden');
                }

                popularTrip.classList.remove('hidden');
                popularSmallTown.classList.remove('hidden');
                recommendationBox.classList.remove('hidden');
                recentStay.classList.remove('hidden');
                realTimeSearch.classList.add('hidden');
                deleteButton.style.display = 'none';

                const noDataWrap = document.querySelector('.nodata__wrap');
                noDataWrap.classList.add('hidden');
            }
        });

        // 삭제 버튼 클릭 시 입력 필드 초기화
        deleteButton.addEventListener('click', () => {
            inputField.value = '';

            // 최근 검색어가 있으면 보여주기
            if (getSearchWords().length > 0) {
                recentSearchWords.classList.remove('hidden');
            } else {
                recentSearchWords.classList.add('hidden');
            }

            recentStay.classList.remove('hidden');
            popularTrip.classList.remove('hidden');
            popularSmallTown.classList.remove('hidden');
            recommendationBox.classList.remove('hidden');
            realTimeSearch.classList.add('hidden');
            deleteButton.style.display = 'none';
            inputField.focus(); // 포커스 유지

            const noDataWrap = document.querySelector('.nodata__wrap');
            noDataWrap.classList.add('hidden');
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

        // 검색 결과 없을 때
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

        // 최근 탐색어 남겨두기
        const STORAGE_KEY = 'RECENT_SEARCH_WORDS';
        const MAX_LENGTH = 5; // 최대 저장 개수

        // 저장된 탐색어 데이터 불러오기
        function getSearchWords() {
            const data = localStorage.getItem(STORAGE_KEY);
            return data ? JSON.parse(data) : [];
        }

        // 데이터 저장
        function setSearchWords(words) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(words));
        }

        // 탐색어 있을 경우 화면 랜더링
        function renderSearchWords() {
            const wrap = document.getElementById('recentSearchWords');
            const list = document.getElementById('searchWordList');
            const words = getSearchWords();

            if (words.length === 0) {
                wrap.classList.add('hidden');
                return;
            }

            list.innerHTML = words.map(item => `
                <li>
                    <div class="textBox" onclick="clickRecentSearchWord('${item.word}', '${item.category}')">
                        <span class="ico"><i class="fa-regular fa-clock"></i></span>
                        <p class="search_word" style="cursor: pointer;">${item.word}</p>
                    </div>
                    <button type="button" class="deleteSearchWord" data-word="${item.word}" data-category="${item.category}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </li>
            `).join('');
        }

        // 최근 탐색어 검색 반영
        function clickRecentSearchWord(query, region) {
            showLoader();

            const queryParams = new URLSearchParams({
                text: query,
                categoryType: region
            });
            window.location.href = `/search-result?${queryParams.toString()}`;
        }

        // 탐색어 로컬 스토리지에 추가
        function addSearchWord(word, category) {
            let words = getSearchWords(); // 기존 데이터 불러오기

            // 중복 제거 (같은 word와 category 조합이 있는 경우 제거)
            words = words.filter(w => !(w.word === word && w.category === category));

            // 맨 앞에 새 검색어 추가
            words.unshift({ word, category });

            // 최대 개수 초과 시 마지막 항목 제거
            if (words.length > MAX_LENGTH) {
                words.pop();
            }

            setSearchWords(words);
        }

        // 개별 삭제
        document.getElementById('searchWordList').addEventListener('click', function(e) {
            const target = e.target.closest('.deleteSearchWord');
            if (!target) return;

            const word = target.dataset.word;
            const category = target.dataset.category;

            let words = getSearchWords().filter(item => !(item.word === word && item.category === category));

            setSearchWords(words);
            renderSearchWords();
        });

        // 전체 삭제
        document.getElementById('allDelete').addEventListener('click', function() {
            localStorage.removeItem(STORAGE_KEY);
            renderSearchWords();
        });

        // 검색 결과 표시 함수
        function displayResults(results) {
            const categories = [{
                    id: 'realtimeRegionList',
                    data: results.region,
                    parentId: 'realtimeRegion',
                    type: 'city'
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
                    parent.style.display = 'block';
                } else {
                    container.innerHTML = '';
                    parent.style.display = 'none'; 
                }

                // 각 링크에 클릭 이벤트 추가하여 searchText 쿼리스트링으로 페이지 이동
                container.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();

                        showLoader();

                        const query = link.getAttribute('data-query');
                        const categoryType = category.type;

                        addSearchWord(query, categoryType);
 
                        const queryParams = new URLSearchParams({
                            text: query,
                            categoryType: categoryType,
                        });
                        window.location.href = `/search-result?${queryParams.toString()}`;
                    });
                });
            });

            toggleNoDataMessage(hasResults, queryText);

            // 검색어 없고 최근검색어 없으면 영역 숨김 처리
            const recentSearchWordsWrap = document.getElementById('recentSearchWords');

            if (!queryText && getSearchWords().length === 0) {
                recentSearchWordsWrap.classList.add('hidden');
            } else if (!queryText && getSearchWords().length > 0) {
                recentSearchWordsWrap.classList.remove('hidden');
            }
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
            renderSearchWords();

            if (inputField.value) {
                recentSearchWords.classList.add('hidden');
                recentStay.classList.add('hidden');
                popularTrip.classList.add('hidden');
                popularSmallTown.classList.add('hidden');
                realTimeSearch.classList.remove('hidden');
                deleteButton.style.display = 'inline-block';

                // 기본값을 이용해 debounce 적용 후 검색 실행
                debounce(function() {
                    search(inputField.value);
                }, 100)();
            }
        });

    </script>

    <script>
        // 최근 본 숙소
        function getRecentHotels() {
            return JSON.parse(localStorage.getItem("RECENT_VIEWED_HOTELS")) || [];
        }

        function renderRecentHotels() {
            const hotels = getRecentHotels();
            const list = document.getElementById("recentStayList"); 
            const listWrap = document.getElementById("recentStay"); 

            if (!list) return;

            if (hotels.length === 0) {
                listWrap.style.display = "none";
            }

            list.innerHTML = hotels.map(hotel => {
                const hasDiscount = parseFloat(hotel.discountedPrice) > 0;

                const saleInfo = hotel.discountRate !== "0.0" ? `
                    <p class="sale-percent">${hotel.discountRate}%</p>
                    <p class="default-price">${hotel.originalPrice}원</p>
                ` : '';

                const salePrice = hasDiscount ? `<p class="sale-price search">${hotel.discountedPrice}원 <span class="intervalDays">(${hotel.intervalDays}박)</span></p>` : '';

                return `
                    <li>
                        <a href="${hotel.link}">
                            <div class="thumb__wrap">
                                <p class="thumb__img large">
                                    <img src="${hotel.img}" alt="숙소 이미지">
                                </p>

                                <div class="thumb__con">
                                    <p class="detail-sub">
                                        ${hotel.address ? `<span>${hotel.address}</span>` : ''}
                                        ${hotel.stayRating ? `<span>${hotel.stayRating}</span>` : ''}
                                    </p>
                                    <p class="detail-name">${hotel.stayName}</p>
                                </div>

                                <div class="thumb__price">
                                    <div style="padding-bottom: 0;">
                                        ${saleInfo}
                                        ${salePrice}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                `;
            }).join('');
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderRecentHotels();
        });

        // 인기 여행
        function clickPopularTrip(query, region) {
            showLoader();

            addSearchWord(query, region);            

            const queryParams = new URLSearchParams({
                text: query,
                categoryType: region
            });
            window.location.href = `/search-result?${queryParams.toString()}`;
        }

        // 요즘 뜨는 소도시
        function clickPopularSmallTown(query, region) {
            showLoader();

            addSearchWord(query, region);   

            const queryParams = new URLSearchParams({
                text: query,
                categoryType: region
            });
            window.location.href = `/search-result?${queryParams.toString()}`;
        }
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>