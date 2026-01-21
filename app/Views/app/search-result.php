<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

$moongcleoffers = $data['moongcleoffers'];

$stays = $data['stays'];
$activities = $data['activities'];
$tour = $data['tour'];
$user = $data['user'];
$isGuest = $data['isGuest'];
$partnerFavorites = $data['partnerFavorites'];
$interval = $data['interval'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

?>

<!-- Head -->
<?php 
    $searchText = isset($_GET['text']) ? htmlspecialchars($_GET['text']) : '';

    $page_title_01 = "'$searchText' 검색 결과";
    $page_description = "뭉클트립에서 '$searchText'에 대한 검색 결과를 확인하세요.";

    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>
<!-- Head -->

<style>
    .filter__wrap.fixed-top {
        top: 9.1rem !important;
        z-index: 49 !important;
    }

    .search-form__wrap.fixed-top {
        position: fixed;
        border-radius: 0;
        margin-left: -2rem;
        margin-right: -2rem;
        padding-left: 3rem;
        width: 100%;
        border-top: 1px solid #f8f8f8;
        border-bottom: 1px solid #f8f8f8;
        background: #fff;
    }

    
    @media (min-width: 500px) {
        .search-form__wrap.fixed-top {
            max-width: 500px;
        }
    }
</style>

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
                <button type="button" class="btn-back" onclick="location.href='/search-start'"><span class="blind">뒤로가기</span></button>
                <div class="header-input">
                    <h2 class="ft-default"><?= $_GET['text']; ?></h2>
                    <button type="button" class="btn-input__delete" onclick="location.href='/search-start'"><i class="ico ico-input__delete"></i></button>
                </div>
            </div>
        </header>

        <div class="container__wrap search__wrap">
            <section class="layout__wrap pt12" style="padding-bottom: 1rem;">
                <!-- 검색 폼 -->
                <div class="search-form__wrap fnStickyTop">
                    <div class="search-form">
                        <div class="search-form__con search-date fnOpenPop" data-name="popupDate">
                            <i class="ico ico-date__mint"></i>
                            <p class="txt txt__mint" id="selectedDate">날짜 선택</p>
                        </div>
                        <div class="search-form__con search-guest fnOpenPop" data-name="popupGuest">
                            <i class="ico ico-person__mint"></i>
                            <p class="txt txt__mint" id="selectedGuests">인원 선택</p>
                        </div>
                    </div>
                </div>
                <!-- //검색 폼 -->
            </section>

            <!-- 탭 -->
            <div class="tab__wrap tab-line__wrap full-line">
                <!-- <div class="select__wrap custom multi-select overflow-x-visible">
                    <ul>
                        <li class="tab-sub__con">
                            <a href="#none">
                                #태그1
                            </a>
                        </li>
                    </ul>
                </div> -->

                <div class="filter__wrap fnStickyTop" style="background: #fff; top">
                    <button type="button" class="btn-filter fnOpenPop" data-name="popupFilter">필터</button>

                    <div class="filter-select__wrap">
                        <p class="filter-select__tit">
                            정렬 <i class="ico ico-arrow__down black"></i>
                        </p>
                        <div class="filter-select__list">
                            <ul>
                                <li><a href="">낮은 가격순</a></li>
                                <li><a href="">높은 가격순</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="tab-contents__wrap" style="position: relative;">

                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/refresh.php"; ?>

                    <div class="refresh__wrap">
                        <div class="tab-contents active">

                            <!-- 상품 리스트 -->
                            <?php if (!empty($stays)) : ?>
                                <div class="product-list__wrap">
                                    <!-- 스켈레톤 -->
                                    <?php foreach ($stays as $index => $stay) : ?>
                                        <div class="product-list__con skeleton">
                                            <div class="splide skeleton-img"></div>
                                            <a href="#" class="product-list__detail">
                                                <p class="detail-sub">
                                                    <span class="skeleton-text skeleton-location"></span>
                                                    <span class="skeleton-text skeleton-category"></span>
                                                </p>
                                                <p class="detail-name skeleton-text skeleton-name"></p>
                                                <div class="tag_badge_wrap">
                                                    <ul>
                                                        <li class="tag_badge skeleton-text skeleton-tag"></li>
                                                        <li class="tag_badge skeleton-text skeleton-tag"></li>
                                                        <li class="tag_badge skeleton-text skeleton-tag"></li>
                                                    </ul>
                                                </div>
                                                <div class="product-list__price">
                                                    <p class="skeleton-text skeleton-price"></p>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php foreach ($stays as $index => $stay) : ?>
                                        <?php
                                            $stayMoongcle = $data['stayMoongcleExist'][$index] ?? null;
                                        ?>
                                        <div class="product-list__con real-data">
                                            <!-- 이미지 슬라이드 -->
                                            <div class="splide splide__product">
                                                <div class="splide__track" onclick="gotoStayDetailPage(event, <?= $stay->partner_idx ?>)">
                                                    <ul class="splide__list">
                                                        <?php
                                                        $stayImages = explode(':-:', $stay->image_paths);

                                                        if($stay->image_curated) {
                                                            $stayImages = explode(':-:', $stay->curated_image_paths);
                                                        }
                                                        ?>
                                                        
                                                        <?php if (!empty($stayImages[0])) : ?>
                                                            <?php foreach ($stayImages as $stayImage) : ?>
                                                                <li class="splide__slide splide__list__product"><img src="<?= $stayImage; ?>" alt=""></li>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <div class="slide-counter">
                                                    <span class="current-slide">1</span>/<span class="total-slides"></span>
                                                </div>
                                                <button type="button" class="btn-product__like <?= in_array($stay->partner_idx, $partnerFavorites) ? 'active' : '' ?>" data-user-idx="<?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>" data-partner-idx="<?= !empty($stay->partner_idx) ? $stay->partner_idx : 0 ?>"><span class="blind">찜하기</span></button>
                                                            
                                                <!-- 뱃지 노출 -->
                                                <?php if ($stayMoongcle && $stayMoongcle->moongcleoffer_count !== 0) : ?>
                                                    <div class="badge-group left-top">   
                                                        <?php if ($stayMoongcle->sale_end_date === null) : ?>
                                                            <div class="badge badge__lavender">실시간 뭉클딜</div>
                                                        <?php else : ?>
                                                            <?php
                                                                $saleEndDate = new DateTime($stayMoongcle->sale_end_date);
                                                                $now = new DateTime();

                                                                if ($saleEndDate > $now) {
                                                                    $interval = $now->diff($saleEndDate);
                                                                    $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                                                                    $days = floor($totalMinutes / (24 * 60));
                                                                    $hours = floor(($totalMinutes % (24 * 60)) / 60);
                                                                    $minutes = $totalMinutes % 60;

                                                                    echo "<div class='badge badge__lavender'>뭉클딜 {$days}일 {$hours}시간 {$minutes}분 남음</div>";
                                                                }
                                                            ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <!-- //이미지 슬라이드 -->
                                            <a href="" class="product-list__detail" onclick="gotoStayDetailPage(event, <?= $stay->partner_idx ?>)">
                                                <p class="detail-sub">
                                                    <?php if (!empty($stay->partner_address1)) : ?>
                                                        <span><?= $stay->partner_address1; ?></span>
                                                    <?php endif; ?>
                                                    <?php $stayTypes = explode(':-:', $stay->types); ?>
                                                    <?php if (!empty($stayTypes[0])) : ?>
                                                        <span>
                                                            <?php foreach ($stayTypes as $tagKey => $stayType) : ?>
                                                                <?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
                                                            <?php endforeach; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php
                                                    $ratingKeywords = ['1성', '2성', '3성', '4성', '5성'];
                                                    $hasRating = false;

                                                    if (!empty($stay->tags)) {
                                                        foreach ($ratingKeywords as $keyword) {
                                                            if (strpos($stay->tags, $keyword) !== false) {
                                                                $hasRating = true;
                                                                break;
                                                            }
                                                        }
                                                    }

                                                    $rating = extract_stay_rating($stay->tags);
                                                    ?>

                                                    <?php if ($hasRating && !empty($rating)) : ?>
                                                        <span><?= $rating ?></span>
                                                    <?php endif; ?>
                                                </p>
                                                <p class="detail-name"><?= $stay->partner_name; ?></p>

                                                <!-- 태그 뱃지 노출 -->
                                                <?php
                                                    $rawBadgeString = $stay->partner_search_badge;
                                                    $decodedBadges = json_decode($rawBadgeString, true);
                                                ?>
                                                <?php if ($decodedBadges) : ?>
                                                    <div class="tag_badge_wrap">
                                                        <ul>
                                                            <?php foreach ($decodedBadges as $searchBadge) : ?>
                                                                <li class="tag_badge badge tag_badge-1">#<?= $searchBadge; ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="product-list__price">
                                                    <?php if ($stay->basic_price != $stay->lowest_price) : ?>
                                                        <p class="sale-percent"><?= number_format((($stay->basic_price - $stay->lowest_price) / $stay->basic_price) * 100, 1) ?>%</p>
                                                        <p class="default-price"><?= number_format($stay->basic_price); ?>원</p>
                                                    <?php endif; ?>
                                                    <p class="sale-price"><?= number_format($stay->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            <?php else : ?>
                                <div class="nodata__wrap search_result">
                                    <div class="nodata__con">
                                        검색 결과가 없습니다.
                                    </div>
                                </div>
                            <?php endif; ?>
                            <!-- //상품 리스트 -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- //탭 -->

            <?php if (!empty($stays)) : ?>
                <div class="btn__wrap btn-fixed__wrap" style="bottom: calc(env(safe-area-inset-bottom) + 10.6rem);">
                    <button id="searchMapBtn" type="button" class="btn-map">지도 보기</button>
                </div>
            <?php endif; ?>

            <!-- 하단바 변경 -->
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

        <!-- 바텀 팝업(필터) -->
        <div id="popupFilter" class="layerpop__wrap">
            <div class="layerpop__container">
                 <div class="layerpop__header">
                    <p class="title">필터</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents new">
                    <!-- 가격 -->
                     <div id="priceBox">
                        <div class="price-filter">
                            <h3 class="filter__name">가격</h3>
                            <div id="price-slider"></div>
                            <div class="price-range">
                                <span id="min-price">0원</span>
                                <span id="max-price">1,000,000만원</span>
                            </div>
                        </div>
                     </div>
                    <!-- //가격 -->

                    <!-- 성급 -->
                     <div id="starBox" class="margin-top-24">
                        <div class="star-filter">
                            <h3 class="filter__name">성급</h3>
                            <ul class="starList">
                                <li data-star="1_star">1성급</li>
                                <li data-star="2_star">2성급</li>
                                <li data-star="3_star">3성급</li>
                                <li data-star="4_star">4성급</li>
                                <li data-star="5_star">5성급</li>
                            </ul>
                        </div>
                     </div>
                    <!-- //성급 -->

                    <!-- 취향 태그 -->
                    <div id="starBox" class="margin-top-24">
                        <div class="star-filter">
                            <h3 class="filter__name">취향 태그</h3>
                            
                            <div class="custom-list__wrap">
                                <ul>
                                    <li id="stayTaste" class="custom-list">
                                        <div class="flex-between">
                                            <p class="ft-default">숙소 취향 <span data-section="stayTaste"></span></p>
                                            <a class="fnOpenPop" data-name="popupStayTaste"><i class="ico ico-plus__small"></i></a>
                                        </div>
                                    </li>
                                    <li id="travelTaste" class="custom-list">
                                        <div class="flex-between">
                                            <p class="ft-default">선호 시설 <span data-section="travelTaste"></span></p>
                                            <a class="fnOpenPop" data-name="popupTravelTaste"><i class="ico ico-plus__small"></i></a>
                                        </div>
                                    </li>
                                    <li id="stayType" class="custom-list">
                                        <div class="flex-between">
                                            <p class="ft-default">숙소 유형 <span data-section="stayType"></span></p>
                                            <a class="fnOpenPop" data-name="popupStayType"><i class="ico ico-plus__small"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                     </div>
                    <!-- //취향 태그 -->
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button type="button" class="btn-full__line__primary reset"><i class="fa-solid fa-arrow-rotate-right"></i> 초기화</button>
                        <button type="button" id="filterSaveBtn" class="btn-full__primary">적용</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //바텀 팝업(필터) -->        

        <!-- 태그 선택 -->
        <div id="popupStayTaste" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">숙소 취향</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap type-img">
                        <ul>
                            <?php foreach ($data['tags']['newStayTasteTags'] as $newStayTasteTag) : ?>
                                <li data-taste-machine-name="<?= $newStayTasteTag['tag_machine_name']; ?>" data-section="<?= $newStayTasteTag['tag_section']; ?>">
                                    <a>
                                        <img src="/uploads/tags/<?= $newStayTasteTag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                        <span><?= $newStayTasteTag['tag_name']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="popupTravelTaste" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">선호 시설</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap type-img">
                        <ul>
                            <?php foreach ($data['tags']['newTravelTasteTags'] as $newTravelTasteTag) : ?>
                                <li data-taste-machine-name="<?= $newTravelTasteTag['tag_machine_name']; ?>" data-section="<?= $newTravelTasteTag['tag_section']; ?>">
                                    <a>
                                        <img src="/uploads/tags/<?= $newTravelTasteTag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                        <span><?= $newTravelTasteTag['tag_name']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="popupStayType" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">숙소 유형</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap type-img">
                        <ul>
                            <?php foreach ($data['tags']['newStayTypeTags'] as $newStayTypeTag) : ?>
                                <li data-taste-machine-name="<?= $newStayTypeTag['tag_machine_name']; ?>" data-section="<?= $newStayTypeTag['tag_section']; ?>">
                                    <a>
                                        <img src="/uploads/tags/<?= $newStayTypeTag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                        <span><?= $newStayTypeTag['tag_name']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- //태그 선택 -->

        <!-- 알럿 팝업 -->
        <div id="betaAlert" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">아직 준비중이에요!</p>
                        <p class="desc">
                            현재는 모두 <span style="color: red; font-weight: bold;">테스트 상품</span>으로 <br>
                            12월 19일 부터 예약이 가능해요!
                        </p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary fnClosePop">확인</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //알럿 팝업 -->

        <!-- 토스트팝업 -->
        <div id="toastPopup" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p>태그는 최대 3개까지만 선택할 수 있어요.</p>
            </div>
        </div>
        <!-- //토스트팝업 -->

        <!-- 토스트팝업 -->
        <div id="toastPopupLike" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p></p>
            </div>
        </div>
        <!-- //토스트팝업 -->

        <!-- 찜하기 로그인 팝업 -->
        <div id="loginLikePopup" class="layerpop__wrap type-center mobileweb-popup">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<i class="ico ico-logo__big"></i>
					<p class="ft-xxl" style="word-break: keep-all;">
						로그인이 필요해요.<br>
						아래의 방법으로 간편하게 로그인 해보시겠나요?
					</p>
				</div>
				<div class="layerpop__footer" style="display: flex; align-items: center; gap: 1rem;">
					<button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap; font-size: 1.2rem;">카카오 1초 로그인</button>
                    <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()" style="font-size: 1.2rem;">이메일로 계속하기</button>
				</div>
			</div>
		</div>
    </div>

    <div id="pageLoader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div id="moreLoader" class="more-loader" style="display: none;"></div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        // 검색 결과 페이지에서만 캐노니컬 쿼리 붙이기
        document.addEventListener('DOMContentLoaded', function() {
            const canonicalLink = document.querySelector('link[rel="canonical"]');
            if (canonicalLink) {
                const url = new URL(canonicalLink.href);
                const params = new URLSearchParams(window.location.search);

                params.forEach((value, key) => {
                    url.searchParams.set(key, value);
                });

                canonicalLink.href = url.toString();
            }
        }); 
    </script>

    <script>
        // 스켈레톤
        window.addEventListener('load', () => {
            const skeletons = document.querySelectorAll('.product-list__con.skeleton');
            const realItems = document.querySelectorAll('.product-list__con.real-data');

            skeletons.forEach((skeleton, i) => {
                const real = realItems[i];

                skeleton.classList.add('fade-out');
                setTimeout(() => {
                    skeleton.remove();
                    if (real) {
                        real.classList.add('show');
                    }
                }, 300);
            });
        });
    </script>

    <script>
        sessionStorage.setItem('previousPage', window.location.href);
    </script>

    <script>
        // 초기화 버튼 클릭 시 필터 리셋
        let isResetClicked = false;
        const resetBtn = document.querySelector('.btn-full__line__primary.reset');

        function enableResetButton() {
            isResetClicked = false;
            resetBtn.style.pointerEvents = 'auto';
            resetBtn.style.opacity = '1';
        }

        resetBtn.addEventListener('click', () => {
            isResetClicked = true;

            // 가격 초기화
            const priceSlider = document.getElementById('price-slider');
            if (priceSlider && priceSlider.noUiSlider) {
                priceSlider.noUiSlider.set([0, 1000000]); 
            }

            document.getElementById('min-price').textContent = '0원';
            document.getElementById('max-price').textContent = '1,000,000원';

            // 성급 초기화
            const starItems = document.querySelectorAll('#starBox .starList li');
            starItems.forEach(item => item.classList.remove('active'));

            // 태그 초기화
            const tagItems = document.querySelectorAll('[data-taste-machine-name]');
            tagItems.forEach(item => item.classList.remove('active'));

            updateTagCounts();

            resetBtn.style.pointerEvents = 'none';
            resetBtn.style.opacity = '0.3';
        });
    </script>

    <script>
        // 필터
        // range slider
        const priceSlider = document.getElementById('price-slider');

        noUiSlider.create(priceSlider, {
            start: [0, 1000000], // 초기값
            connect: true,
            step: 100000, // 10만원 단위
            range: {
                'min': 0,
                'max': 1000000
            },
            format: {
                to: function(value) {
                    return Math.round(value);
                },
                from: function(value) {
                    return Number(value);
                }
            }
        });

        const minPrice = document.getElementById('min-price');
        const maxPrice = document.getElementById('max-price');

        priceSlider.noUiSlider.on('update', function(values) {
            minPrice.textContent = `${(values[0]).toLocaleString()}원`;
            maxPrice.textContent = `${(values[1]).toLocaleString()}원`;

            enableResetButton();
        });

        // 성급 선택
        let selectedStar = null; 

        const starItems = document.querySelectorAll('#starBox .starList li');

        // active 유지
        const urlParams = new URLSearchParams(window.location.search);
        const currentStar = urlParams.get('starRating');

        if (currentStar) {
            starItems.forEach(item => {
                if (item.dataset.star === currentStar) {
                    item.classList.add('active');
                    selectedStar = currentStar; 
                }
            });
        }

        // 선택 시 active
        starItems.forEach(item => {
            item.addEventListener('click', () => {
                starItems.forEach(el => el.classList.remove('active'));
                item.classList.add('active');

                selectedStar = item.dataset.star; 

                enableResetButton();
            });
        });

        // 취향 태그 active 유지
        const tagNamesParam = urlParams.get('tagName');

        if (tagNamesParam) {
            const selectedTags = tagNamesParam.split(',');

            selectedTags.forEach(tag => {
                const tagEl = document.querySelector(`[data-taste-machine-name="${tag}"]`);
                if (tagEl) tagEl.classList.add('active');
            });
        }

        // 취향 태그 선택
        const tagItems = document.querySelectorAll('[data-taste-machine-name]');
        const MAX_TAGS = 3;

        tagItems.forEach(item => {
            item.addEventListener('click', () => {
                const activeTags = document.querySelectorAll('[data-taste-machine-name].active');

                if (item.classList.contains('active')) {
                    item.classList.remove('active');
                } else {
                    if (activeTags.length >= MAX_TAGS) {
                        fnToastPop('toastPopup');
                        return;
                    }
                    item.classList.add('active');
                    enableResetButton();
                }

                updateTagCounts();
            });
        });

        updateTagCounts();

        // 갯수 표시
        function updateTagCounts() {
            const sections = ['travelTaste', 'event', 'stayTaste', 'stayType', 'petFacility'];

            sections.forEach(section => {
                
                const popupId = `popup${section.charAt(0).toUpperCase() + section.slice(1)}`;
                const activeItems = document.querySelectorAll(`#${popupId} [data-taste-machine-name].active`);
                const count = activeItems.length;

                
                const countSpan = document.querySelector(`span[data-section="${section}"]`);
                if (countSpan) {
                    countSpan.textContent = count > 0 ? `+${count}` : '';
                }
            });
        }

        // 필터 적용
        document.getElementById('filterSaveBtn').addEventListener('click', function () {
            const urlParams = new URLSearchParams(window.location.search);

            const sliderValues = priceSlider.noUiSlider.get();
            const startPrice = Math.round(sliderValues[0]);
            const endPrice = Math.round(sliderValues[1]);

            // 가격
            if (isResetClicked || (startPrice === 0 && endPrice === 1000000)) {
                urlParams.delete('startPrice');
                urlParams.delete('endPrice');
            } else {
                urlParams.set('startPrice', startPrice);
                urlParams.set('endPrice', endPrice);
            }

            // 성급
            const selectedStar = document.querySelector('#starBox .starList li.active');
            if (isResetClicked || !selectedStar) {
                urlParams.delete('starRating');
            } else {
                urlParams.set('starRating', selectedStar.dataset.star);
            }

            // 태그 통합 처리 
            if (isResetClicked) {
                urlParams.delete('tagName');
            } else {
                const selectedTags = Array.from(document.querySelectorAll('[data-taste-machine-name].active')).map(li =>
                    li.dataset.tasteMachineName
                );

                if (selectedTags.length > 0) {
                    urlParams.set('tagName', selectedTags.join(','));
                } else {
                    urlParams.delete('tagName');
                }
            }

            // URL
            showLoader();
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.location.href = newUrl;
        });

        const priceParams = new URLSearchParams(window.location.search);
        const startPrice = priceParams.get('startPrice');
        const endPrice = priceParams.get('endPrice');

        if (startPrice && endPrice) {
            priceSlider.noUiSlider.set([startPrice, endPrice]);
        }
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

            if (filterStartDate || filterEndDate) {
                if (filterStartDate.includes('-') && filterEndDate.includes('-')) {
                    let dateString = filterStartDate;
                    let dateParts = dateString.split('-');
                    let formattedStartDate = `${dateParts[0].slice(2)}.${dateParts[1]}.${dateParts[2]}`;

                    dateString = filterEndDate;
                    dateParts = dateString.split('-');
                    let formattedEndDate = `${dateParts[0].slice(2)}.${dateParts[1]}.${dateParts[2]}`;

                    dateText = formattedStartDate + '~' + formattedEndDate;
                } else {
                    let formattedStartDate = filterStartDate;
                    let formattedEndDate = filterEndDate;

                    dateText = formattedStartDate + '~' + formattedEndDate;
                }
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
                firstDayOfWeek: 0,
                weekdays: {
                    shorthand: ["일", "월", "화", "수", "목", "금", "토"],
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

        const deleteButton = document.getElementById('searchTextDeleteBtn');
        const featuredTags = document.getElementById('featuredTags');
        const popularTags = document.getElementById('popularTags');
        const realTimeSearch = document.getElementById('realTimeSearch');

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
                } else if (type === 'child') {
                    childCount = currentCount;
                } else if (type === 'infant') {
                    infantCount = currentCount;
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

            const urlParams = new URLSearchParams(window.location.search);

            // 날짜 관련 필드만
            urlParams.set('startDate', filterStartDate);
            urlParams.set('endDate', filterEndDate);

            showLoader();
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });

        document.getElementById('undecidedBtn').addEventListener('click', () => {
            const selectedDateText = `날짜 선택`;
            document.getElementById('selectedDate').textContent = selectedDateText;
            document.getElementById('popupDate').classList.remove('show');

            const urlParams = new URLSearchParams(window.location.search);

            // 날짜 관련 필드 제거
            urlParams.delete('startDate');
            urlParams.delete('endDate');

            showLoader();
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });

        // "선택" 버튼 클릭 시 인원 수 업데이트 및 팝업 닫기
        document.getElementById('selectGuestsBtn').addEventListener('click', () => {
            const selectedGuestsText = `성인 ${adultCount}, 아동 ${childCount}, 유아 ${infantCount}`;
            document.getElementById('selectedGuests').textContent = selectedGuestsText;
            document.getElementById('popupGuest').classList.remove('show');

            const urlParams = new URLSearchParams(window.location.search);

            // 인원 관련 필드 업데이트
            urlParams.set('adult', adultCount);
            urlParams.set('child', childCount);
            urlParams.set('infant', infantCount);
            urlParams.set('childAge', JSON.stringify(selectedAges));
            urlParams.set('infantMonth', JSON.stringify(selectedMonths));

            showLoader();
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });

        function gotoStayDetailPage(event, partnerIdx) {
            event.preventDefault();

            const queryParams = new URLSearchParams({
                startDate: filterStartDate,
                endDate: filterEndDate,
                adult: adultCount,
                child: childCount,
                infant: infantCount,
                childAge: JSON.stringify(selectedAges),
                infantMonth: JSON.stringify(selectedMonths),
            });

            showLoader();
            window.location.href = `/stay/detail/${partnerIdx}?${queryParams.toString()}`;
        }

        function gotoMoongcleoffer(event, partnerIdx) {
            event.preventDefault();

            const queryParams = new URLSearchParams({
                startDate: filterStartDate,
                endDate: filterEndDate,
                adult: adultCount,
                child: childCount,
                infant: infantCount,
                childAge: JSON.stringify(selectedAges),
                infantMonth: JSON.stringify(selectedMonths),
            });

            showLoader();
            window.location.href = `/moongcleoffer/product/${partnerIdx}?${queryParams.toString()}`;
        }

        // 지도 보기
        document.getElementById('searchMapBtn').addEventListener('click', () => {
            const queryParams = new URLSearchParams(window.location.search);
            const queryString = queryParams.toString();

            sessionStorage.removeItem('search');

            showLoader();

            window.location.href = `/search-map?${queryString}`;
        });

        let currentPage = 1;
        let isLoading = false;
        let hasMoreData = true;
        let partnerFavorites = <?= !empty($partnerFavorites) ? json_encode($partnerFavorites) : json_encode([]); ?>;

        function showSkeletonsForLoadMore(count) {
            const container = document.querySelector('.product-list__wrap');
            if (!container || count === 0) return;

            const skeletonHTML = Array.from({ length: count }).map(() => `
                <div class="product-list__con skeleton">
                    <div class="splide skeleton-img"></div>
                    <a href="#" class="product-list__detail">
                        <p class="detail-sub">
                            <span class="skeleton-text skeleton-location"></span>
                            <span class="skeleton-text skeleton-category"></span>
                        </p>
                        <p class="detail-name skeleton-text skeleton-name"></p>
                        <div class="tag_badge_wrap">
                            <ul>
                                <li class="tag_badge skeleton-text skeleton-tag"></li>
                                <li class="tag_badge skeleton-text skeleton-tag"></li>
                                <li class="tag_badge skeleton-text skeleton-tag"></li>
                            </ul>
                        </div>
                        <div class="product-list__price">
                            <p class="skeleton-text skeleton-price"></p>
                        </div>
                    </a>
                </div>
            `).join('');

            container.insertAdjacentHTML('beforeend', skeletonHTML);
        }

        function removeSkeletons() {
            document.querySelectorAll('.skeleton').forEach(el => {
                el.classList.add('fade-out');
                setTimeout(() => el.remove(), 400);
            });
        }

        const loadMoreSearchResult = () => {
            return new Promise((resolve, reject) => {
                currentPage++;

                fetch('/api/search/loadmore', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            page: currentPage,
                            text: '<?= $_GET['text']; ?>',
                            startDate: '<?= $_GET['startDate']; ?>',
                            endDate: '<?= $_GET['endDate']; ?>',
                            adult: '<?= $_GET['adult']; ?>',
                            child: '<?= $_GET['child']; ?>',
                            infant: '<?= $_GET['infant']; ?>',
                            categoryType: '<?= $_GET['categoryType']; ?>',
                            startPrice: '<?= $_GET['startPrice']; ?>',
                            endPrice: '<?= $_GET['endPrice']; ?>',
                            starRating: '<?= $_GET['starRating']; ?>',
                            tagName: <?= isset($_GET['tagName']) && $_GET['tagName'] !== ''  ? json_encode(explode(',', $_GET['tagName']))  : '""' ?>,
                            orderType:'<?= $_GET['orderType']; ?>',
                            order:'<?= $_GET['order']; ?>'
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.data && data.data.length > 0) {
                            showSkeletonsForLoadMore(data.data.length);

                            setTimeout(() => {
                                removeSkeletons();
                                appendSearchResult(data.data, data.stayMoongcleExist);
                            }, 500);
                        } else {
                            hasMoreData = false;
                        }

                        resolve();
                    })
                    .catch((error) => {
                        console.error('Error loading reviews:', error);
                        currentPage--;
                        reject(error);
                    }).finally(() => {
                        hideLoader();
                    });
            });
        };

        const appendSearchResult = (stays, stayMoongcleExist) => {
            const container = document.querySelector('.product-list__wrap'); // stays를 담는 컨테이너

            stays.forEach((stay, index) => {
                const stayMoongcle = stayMoongcleExist[index];

                let stayImages = stay.image_paths ? stay.image_paths.split(':-:') : [];

                if(stay.image_curated) {
                    stayImages = stay.curated_image_paths ? stay.curated_image_paths.split(':-:') : [];
                }
                
                const stayTags = stay.tags ? stay.tags.split(':-:') : [];
                const stayTypes = stay.types ? stay.types.split(':-:') : [];
                let stayRating = '';

                stayTags.forEach((tag) => {
                    if (['1성', '2성', '3성', '4성', '5성'].includes(tag)) {
                        stayRating = tag;
                    }
                });

                let imageElements = '';

                if (stayImages.length > 0) {
                    imageElements = stayImages.map(image => `
                        <li class="splide__slide splide__list__product">
                            <img src="${image}" alt="">
                        </li>
                    `).join('');
                } else {
                    imageElements = `
                        <li class="splide__slide splide__list__product">
                            <img src="/assets/app/images/demo/moongcle-noimg.png" alt="">
                        </li>
                    `;
                }

                const typeElements = stayTypes.map((type, index) => `
                    ${type}${index < stayTypes.length - 1 ? ', ' : ''}
                `).join('');

                const reviewNumFormatted = stay.review_count ? Number(stay.review_count).toLocaleString() : '0';
                const salePercent = stay.basic_price !== stay.lowest_price ?
                    `${(((stay.basic_price - stay.lowest_price) / stay.basic_price) * 100).toFixed(1)}%` :
                    '';

                // 뭉클딜 배지
                let badgeHTML = '';
                if (stayMoongcle && stayMoongcle.moongcleoffer_count !== 0) {
                    if (!stayMoongcle.sale_end_date) {
                        badgeHTML = `<div class="badge-group left-top"><div class="badge badge__lavender">실시간 뭉클딜</div></div>`;
                    } else {
                        const end = new Date(stayMoongcle.sale_end_date);
                        const now = new Date();
                        if (end > now) {
                            const diff = end - now;
                            const minutes = Math.floor(diff / 60000);
                            const days = Math.floor(minutes / (60 * 24));
                            const hours = Math.floor((minutes % (60 * 24)) / 60);
                            const mins = minutes % 60;
                            badgeHTML = `<div class="badge-group left-top"><div class="badge badge__lavender">뭉클딜 ${days}일 ${hours}시간 ${mins}분 남음</div></div>`;
                        }
                    }
                }

                // 태그 뱃지
                let searchBadgeHTML = '';
                const badgeList = JSON.parse(stay.partner_search_badge);

                if (Array.isArray(badgeList) && badgeList.length > 0) {
                    const badges = badgeList.map((badge) => {
                        return `<li class="tag_badge badge tag_badge-1">${badge}</li>`;
                    }).join('');

                    searchBadgeHTML = `
                        <div class="tag_badge_wrap">
                            <ul>${badges}</ul>
                        </div>
                    `;
                }

                const stayElement = document.createElement('div');
                stayElement.classList.add('product-list__con');

                stayElement.innerHTML = `
                    <!-- 이미지 슬라이드 -->
                    <div class="splide splide__product">
                        <div class="splide__track" onclick="gotoStayDetailPage(event, ${stay.partner_idx})">
                            <ul class="splide__list">
                                ${imageElements}
                            </ul>
                        </div>
                        <div class="slide-counter">
                            <span class="current-slide">1</span> / <span class="total-slides">${stayImages.length}</span>
                        </div>
                        <button type="button" class="btn-product__like ${partnerFavorites.includes(stay.partner_idx) ? 'active' : ''}" 
                            data-user-idx="<?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>" data-partner-idx="${stay.partner_idx || 0}">
                            <span class="blind">찜하기</span>
                        </button>
                        ${badgeHTML}
                    </div>
                    <!-- //이미지 슬라이드 -->
                    <a class="product-list__detail" onclick="gotoStayDetailPage(event, ${stay.partner_idx})">
                        <p class="detail-sub">
                            ${stay.partner_address1 ? `<span>${stay.partner_address1}</span>` : ''}
                            ${stayTypes.length > 0 ? `<span>${typeElements}</span>` : ''}
                            ${stayRating ? `<span>${stayRating}</span>` : ''}
                        </p>
                        <p class="detail-name">${stay.partner_name}</p>
                        ${searchBadgeHTML}
                        <div class="product-list__price">
                            ${salePercent ? `<p class="sale-percent">${salePercent}</p>` : ''}
                            ${stay.basic_price !== stay.lowest_price ? `<p class="default-price">${Number(stay.basic_price).toLocaleString()}원</p>` : ''}
                            <p class="sale-price">${Number(stay.lowest_price).toLocaleString()}원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                        </div>
                    </a>
                `;

                container.appendChild(stayElement);
            });

            // 슬라이더 초기화 함수 호출
            fnProductSlide();
        };

        document.addEventListener('DOMContentLoaded', () => {

            // scroll
            const container = document.querySelector('#scrollContainer');
            const moreLoader = document.querySelector('#moreLoader');
            let isLoading = false;
            let lastFetchedPage = 1;
            const threshold = 300;

            const showMoreLoader = () => {
                requestAnimationFrame(() => {
                    moreLoader.style.display = 'grid';
                });
            };

            const hideMoreLoader = () => {
                requestAnimationFrame(() => {
                    moreLoader.style.display = 'none';
                });
            };

            const loadPage = (page) => {
                if (!hasMoreData) return;
                if (isLoading) return;
                isLoading = true;
                showMoreLoader();

                loadMoreSearchResult(page)
                    .then(() => {
                        isLoading = false;
                        lastFetchedPage = page;
                        hideMoreLoader();
                    })
                    .catch(() => {
                        isLoading = false;
                        hideMoreLoader();
                    });
            };

            const handleScroll = () => {
                let scrollHeight, scrollTop, clientHeight;

                if (container) {
                    scrollHeight = container.scrollHeight;
                    scrollTop = container.scrollTop;
                    clientHeight = container.clientHeight;
                } else {
                    scrollHeight = document.documentElement.scrollHeight;
                    scrollTop = window.scrollY || document.documentElement.scrollTop;
                    clientHeight = window.innerHeight;
                }

                if (scrollHeight - scrollTop - threshold <= clientHeight) {
                    const nextPage = lastFetchedPage + 1;
                    loadPage(nextPage);
                }
            };

            const throttleScroll = throttle(handleScroll, 200);

            if (container) {
                container.addEventListener('scroll', throttleScroll);
            } else {
                document.addEventListener('scroll', throttleScroll);
            }

            // 상품 정렬
            const filterTitle = document.querySelector('.filter-select__tit');
            const filterItems = document.querySelectorAll('.filter-select__list a');

            filterItems.forEach(function (item) {
                item.addEventListener('click', function (e) {
                    e.preventDefault();

                    const selectedText = this.textContent.trim();
                    const order = selectedText === '낮은 가격순' ? 'ASC' : 'DESC';
                    const params = new URLSearchParams(window.location.search);

                    params.set('orderType', 'price');
                    params.set('order', order);

                    showLoader();
                    const newUrl = `${window.location.pathname}?${params.toString()}`;
                    window.location.href = newUrl;
                });
            });

            const params = new URLSearchParams(window.location.search);
            const orderType = params.get('orderType');
            const order = params.get('order');

            if (orderType === 'price') {
                const text = order === 'DESC' ? '높은 가격순' : '낮은 가격순';
                filterTitle.innerHTML = `${text} <i class="ico ico-arrow__down black"></i>`;
            }
        });

        function throttle(func, limit) {
            let lastFunc;
            let lastRan;
            return function(...args) {
                const context = this;
                if (!lastRan) {
                    func.apply(context, args);
                    lastRan = Date.now();
                } else {
                    clearTimeout(lastFunc);
                    lastFunc = setTimeout(() => {
                        if (Date.now() - lastRan >= limit) {
                            func.apply(context, args);
                            lastRan = Date.now();
                        }
                    }, limit - (Date.now() - lastRan));
                }
            };
        }

        document.addEventListener('click', function(event) {
            const target = event.target.closest('.btn-product__like');
            if (target) {
                event.preventDefault();
                const userIdx = target.dataset.userIdx;
                const partnerIdx = target.dataset.partnerIdx;

                toggleFavorite(userIdx, partnerIdx);
            }
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
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>