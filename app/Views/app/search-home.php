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

<body class="lock"> 

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
    }
    ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>

    <div id="mobileWrap">
        <header class="header__wrap" style="z-index: 0;">
            <div class="header__inner">
                <h2 class="header-tit__left">탐색</h2>
                <button type="button" class="btn-alarm header" onclick="gotoNotification()"><span class="blind">알림</span></button>
            </div>
        </header>

        <div class="container__wrap search__wrap">
            <section class="layout__wrap">
                <!-- 검색 바 -->
                <div id="searchBarWrap" class="searchBar__wrap">
                    <div class="img__box">
                        <img src="/assets/app/images/common/search_logo.png" alt="로고 이미지" style="width: 8rem;" class="logo_home">
                    </div>
                    <div class="search-form__con search-input custom"> 
                        <i class="ico ico-search__small"></i>
                        <div class="input__wrap">
                            <input 
                                id="searchText" 
                                type="text" 
                                class="input" 
                                placeholder="어디로 떠나볼까요?" 
                                onfocus="window.location.href='/search-start'"
                                readonly>
                            <button id="searchTextDeleteBtn" type="button" class="btn-input__delete"><i class="ico ico-input__delete"></i></button>
                        </div>
                    </div>
                </div>
                <!-- //검색 바 -->
            </section>

            <!-- 하단바 변경 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

            <!-- 탐색 -->
            <div class="searchBottomWrap" id="searchBottomWrap">
                <div class="bottom__header">
                    <div class="drag-handle"></div>
                </div>
                <div class="bottom__content">
                    <div id="bottomSearchBarWrap" class="searchBar__wrap bottom fixed" style="margin-top: 0; opacity: 0; z-index: -1;">
                        <div class="img__box">
                            <a href="/">
                                <img src="/assets/app/images/common/moongcle_color_807_257.png" alt="로고 이미지" class="logo_bar">
                            </a>
                        </div>
                        <div class="search-form__con search-input custom">
                            <i class="ico ico-search__small"></i>
                            <div class="input__wrap">
                                <input 
                                    type="text" 
                                    class="input" 
                                    name="searchBar"
                                    placeholder="어디로 떠나볼까요?" 
                                    onfocus="window.location.href='/search-start'"
                                    readonly>
                                <button id="searchTextDeleteBtn" type="button" class="btn-input__delete"><i class="ico ico-input__delete"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn-alarm search_bar" onclick="gotoNotification()"><span class="blind">알림</span></button>
                    </div>

                    <div class="bottomContentWrap" style="margin-top: -6rem;">
                        <h4 class="bottom__header__title">
                            <img src="/uploads/tags/package_tour.png" alt="패키지 투어 아이콘 이미지">
                            이런 여행은 어때요?
                        </h4>

                        <div class="banner__wrap margin-bottom-30">
                            <div class="swiper-container banner">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="/stay/detail/15066" class="banner__item">
                                            <div class="img__box">
                                                <img src="/assets/app/images/article-home/2509_H8.jpg" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="/stay/detail/15292" class="banner__item">
                                            <div class="img__box">
                                                <img src="/assets/app/images/article-home/2509_H9.jpg" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="/stay/detail/14249" class="banner__item">
                                            <div class="img__box">
                                                <img src="/assets/app/images/article-home/2509_H5.jpg" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a onclick="outLink('https://moongcle.oopy.io/2509promotion')" class="banner__item">
                                            <div class="img__box">
                                                <img src="/assets/app/images/article-home/2509_H4.jpg" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>

                                    <div class="swiper-slide">
                                        <a onclick="outLink('https://maily.so/moongcletrip/posts/vpzl6m20zk9')" class="banner__item">
                                            <div class="img__box">
                                                <img src="/assets/app/images/article-home/H1.png" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a onclick="outLink('https://maily.so/moongcletrip/posts/d5rylg38o1w')" class="banner__item">
                                            <div class="img__box">
                                                <img src="/assets/app/images/article-home/H3.png" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>                                    
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>

                        <!-- <div class="margin-bottom-30">
                            <div class="wrap__box">
                                <h4 class="wrap__title padding-x-20">
                                    이런 여행 어때?
                                </h4>
                                <div class="overflow-x-visible padding-x-20">
                                    <div class="wrap__list">
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/236x/e6/96/44/e69644559085f94f504693add552f5e0.jpg" alt="탭 메뉴 아이콘">
                                                    <p class="search__badge">#아이와 함께</p>
                                                </div>
                                                <p class="title">수월가</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/236x/e6/96/44/e69644559085f94f504693add552f5e0.jpg" alt="탭 메뉴 아이콘">
                                                    <p class="search__badge">#아이와 함께</p>
                                                </div>                                        
                                                <p class="title">수월가</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/236x/e6/96/44/e69644559085f94f504693add552f5e0.jpg" alt="탭 메뉴 아이콘">
                                                    <p class="search__badge">#아이와 함께</p>
                                                </div>
                                                <p class="title">수월가</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="margin-bottom-30">
                            <div class="wrap__box">
                                <h4 class="wrap__title padding-x-20">
                                    요즘 여행가기 좋은 도시
                                </h4>
                                <div class="overflow-x-visible padding-x-20">
                                    <div class="wrap__list nice-city">
                                        <div class="item">
                                            <a href="">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/736x/5d/70/87/5d70879b2343b73683cee897c802dbb1.jpg" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">제주도</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/736x/5d/70/87/5d70879b2343b73683cee897c802dbb1.jpg" alt="탭 메뉴 아이콘">
                                                </div>                                        
                                                <p class="title">부산</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/736x/5d/70/87/5d70879b2343b73683cee897c802dbb1.jpg" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">부산</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/736x/5d/70/87/5d70879b2343b73683cee897c802dbb1.jpg" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">부산</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#">
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/736x/5d/70/87/5d70879b2343b73683cee897c802dbb1.jpg" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">부산</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="margin-bottom-30">
                            <div class="wrap__box">
                                <h4 class="wrap__title padding-x-20">
                                    키워드로 찾는 여행
                                </h4>
                                <div class="overflow-x-visible padding-x-20">
                                    <div class="wrap__list video-trip">
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=sea_view">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword1_sea_view.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">오션뷰</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=with_kids">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword2_with_kids.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">아이와 함께</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=swimming_pool">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword3_swimming_pool.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">수영장</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=year_round_heated_pool">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword4_year_round_heated_pool.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">사계절 온수풀</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=infinity_pool">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword5_infinity_pool.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">인피티니풀</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=barbecue_area">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword6_barbecue_area.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">바베큐 냠냠</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=exotic_atmosphere">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword7_exotic_atmosphere.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">이국적인 분위기</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <!-- <a href="#none"> -->
                                                <div class="img__box">
                                                    <img src="https://i.pinimg.com/736x/9a/b7/f8/9ab7f8db78c3ea03526d2cd10c75932d.jpg" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title"></p>
                                            <!-- </a> -->
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=forest_staycation">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword8_forest_staycation.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">숲캉스</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=with_nature">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword9_with_nature.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">자연과 함께</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=pet_friendly">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword10_pet_friendly.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">반려동물 동반 가능</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=hip_atmosphere">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword11_hip_atmosphere.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">힙한 분위기</p>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="/search-result?text=&categoryType=city&tagName=emotional_vibe">
                                                <div class="img__box">
                                                    <img src="/assets/app/images/article-home/keywords/keyword12_emotional_vibe.png" alt="탭 메뉴 아이콘">
                                                </div>
                                                <p class="title">감성 넘치는</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg__wrap">
                            <div class="wrap__box">
                                <h4 class="wrap__title">
                                    추천 여행 콘텐츠
                                </h4> 
                                <div class="content__wrap">
                                    <div class="content__item">
                                        <a onclick="outLink('https://moongcle.oopy.io/exotic_atmosphere')">
                                            <img src="/assets/app/images/article-home/article_250709.png" alt="콘텐츠 이미지">
                                            <p class="search__badge">#이국적인</p>
                                            <h5 class="title">여권 없이 떠나는 해외여행</h5>
                                            <p class="desc">
                                                국내에 있는 이국적인 분위기의 숙소들을 모아왔어요
                                            </p>
                                        </a>
                                    </div>
                                    <div class="content__item">
                                        <a onclick="outLink('https://moongcle.oopy.io/kidsroom')">
                                            <img src="/assets/app/images/article-home/article2.png" alt="콘텐츠 이미지">
                                            <p class="search__badge">#캐릭터룸</p>
                                            <h5 class="title">엄마, 여기서 자면 좋은 꿈만 꿀 것 같아요!</h5>
                                            <p class="desc">
                                                우리 아이가 너무 좋아할 캐릭터룸을 보유한 숙소를 모아왔어요
                                            </p>
                                        </a>
                                    </div>
                                    <div class="content__item">
                                        <a onclick="outLink('https://maily.so/moongcletrip/posts/8mo5p0q4z9p')">
                                            <img src="/assets/app/images/article-home/article3.png" alt="콘텐츠 이미지">
                                            <p class="search__badge">#키즈풀</p>
                                            <h5 class="title">눈치 볼 필요 없어요! 키즈풀이 따로 있는 숙소 모음.zip</h5>
                                            <p class="desc">
                                                성인풀과 키즈풀을 별도로 운영해 맘 편하게 놀 수 있는 곳들이에요
                                            </p>
                                        </a>
                                    </div>
                                    <button type="button" class="more-btn" onclick="outLink('https://moongcle.oopy.io/heatedpoolvilla')">더보기</button>
                                </div>
                            </div>
                        </div>

                        <div class="moongcledeal__wrap margin-bottom-30">
                            <div>
                                <h4 class="title">뭉클 숏츠</h4>
                                <div class="moongcledeal-slide">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts1/shorts1_thumbnail_jnparkhotel.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts1/shorts1_video_jnparkhotel_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14302'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts2/shorts2_thumbnail_rollinghills.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts2/shorts2_video_rollinghills_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/9722'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts3/shorts3_thumbnail_enfordhotel.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts3/shorts3_video_enfordhotel_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14200'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts4/shorts4_thumbnail_midas.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts4/shorts4_video_midas_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14249'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts5/shorts5_thumbnail_oakvalley.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts5/shorts5_video_oakvalley_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14385'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts6/shorts6_thumbnail_marinabayseoul.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts6/shorts6_video_marinabayseoul_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14179'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts7/shorts7_thumbnail_takehotel.png"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts7/shorts7_video_takehotel_250708.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14334'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="swiper-slide">
                                                <a href="#">
                                                    <div class="img__box">
                                                        <div class="video">
                                                            <video
                                                                playsinline
                                                                webkit-playsinline
                                                                controls
                                                                loop
                                                                preload="metadata"
                                                                poster="/assets/app/images/article-home/shorts8/250808-shorts8_thum_leikkipuisto.jpg"
                                                                width="100%"
                                                            >
                                                                <source src="/assets/app/images/article-home/shorts8/250808-shorts8_video_leikkipuisto.mp4" type="video/mp4" />
                                                            </video>
                                                        </div>
                                                    </div>
                                                    <div class="play__btn">
                                                        <button type="button" title="숙소 보러가기" class="go_stay" onclick="location.href='/stay/detail/14754'"><i class="fa-solid fa-suitcase"></i></button>
                                                        <!-- <button type="button" title="영상 바로보기" class="go_video"><i class="fa-solid fa-video"></i></button> -->
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="margin-bottom-30 romentic__wrap">
                            <div class="wrap__box padding-x-20 tab__wrap tab-round__wrap type-circle">
                                <h4 class="wrap__title">뭉클 단독 네고딜</h4>
                                <p class="desc">뭉클에서 단독으로 네고 해왔어요!</p>
                                <div class="overflow-x-visible">
                                    <ul class="capsule-btns tab__inner">
                                        <li class="tab-round__con active">
                                            <a href="#none">진행 중</a>
                                        </li>
                                        <li class="tab-round__con">
                                            <a href="#none">오픈 예정</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-contents__wrap moongcledeal">
                                    
                                    <div class="product__box tab-contents active">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/14844">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/greenyard.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                            <p class="detail-name">오색 그린야드 호텔</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.02 (화) ~ 09.16 (화)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                                1) 온캉스 패키지 (온천+찜질 1회 2인 제공)<br><br>
                                                                2) 오색 풀패키지 (온천+찜질 1회 2인 + 조식 2인 제공)
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">112,960원 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15293">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/kimhae.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">김해 아세라 빌라드 아쿠아 호텔</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.03 (수) ~ 09.16 (화)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                                오픈기념 단독할인 + 1인 추가비 무료 + 레이트 체크아웃 혜택
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">82,400원 (쿠폰적용가)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15516">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/marina.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">금호 통영 마리나 리조트</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.16 (화) ~ 09.19 (금)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                               1) ★뭉클단독특가★ 룸온리특가<br><br>
                                                               2) ★뭉클단독특가★ 조식 3인PKG
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">10만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15515">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/kh_asan.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">금호 아산 스파포레</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.16 (화) ~ 09.19 (금)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                               1) ★뭉클단독특가★ 카라반(스파비스 2인 이용권 무료제공)<br><br>
                                                               2) ★뭉클단독특가★ 글램핑(스파비스 4인 이용권 무료제공)
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">15만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15185">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/kh_hs.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">금호화순스파리조트</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.16 (화) ~ 09.19 (금)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                               1) ★뭉클단독특가★ 룸온리특가<br><br>
                                                               2) ★뭉클단독특가★객실 + 조식3인 + 아쿠아나 3인
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">9만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14249">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/midas.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">마이다스호텔앤리조트</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.08 (월) ~ 09.30 (화)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                               1) ★뭉클단독특가★조식2인 + 베이비룸 2시간이용권(20개월이하)<br><br>
                                                               2) ★뭉클단독특가★조식2인 + 트니빌리지 클래스
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">20만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14249">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/golden.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">골든서울호텔</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.16 (화) ~ 09.30 (화)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                                ★뭉클단독특가★조식PKG (소인1인 조식무료 + 키즈기프트제공) 
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">특가 네고 중</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14302">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/j_an.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">제이앤파크 호텔</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.16 (화) ~ 09.30 (화)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                                ★뭉클단독특가★ 레이트체크아웃 12시 제공 (금/토/일 & 연휴 체크아웃 제외)
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">9만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15366">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/fkxpfktm.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                        <p class="detail-name">라테라스&깔라까따리조트 </p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">진행 중</span>
                                                                <span class="all">09.18 (목) ~ 09.30 (화)까지</span> 
                                                            </p>
                                                            <p class="text">
                                                                ★뭉클단독특가★ 워터파크 3인 + 인원추가비 무료 + 호텔바우처 5천원권 + 레이트체크아웃 12시(금/토/공휴일 체크아웃 제외) 
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">133,000원 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14179">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/marina.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                            <p class="detail-name">호텔 마리나베이서울</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">뭉클 단독 상시 프로모션 진행 중</span>
                                                            </p>
                                                            <p class="text">
                                                                1) 룸온리 단독 특가+캐릭터룸 특전 제공
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">10만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15068">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/picks/2-2.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                            <p class="detail-name">오크밸리 리조트</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">뭉클 단독 상시 프로모션 진행 중</span>
                                                            </p>
                                                            <p class="text">
                                                                1) 룸온리/힐스빌리지 패밀리특가<br><br>
                                                                2) 70평형 객실 뭉클 단독 판매
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">87,000원 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14200">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/picks/5-1.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                            <p class="detail-name">엔포드호텔</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">뭉클 단독 상시 프로모션 진행 중</span>
                                                            </p>
                                                            <p class="text">
                                                                1) [썸머 인 솔레아도 패키지] 실내수영장(어반오아시스)+인피니티풀(솔레아도) 2인 포함+조식 2인 포함<br><br>
                                                                2) [블루온 패키지] 인피니티풀(솔레아도) 2인+실내수영장(어반오아시스) 2인 포함
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-price">20만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14754">
                                                    <div class="thumb__wrap">
                                                        <p class="thumb__img large">
                                                            <img src="/assets/app/images/article-home/deals/ing-2.jpg" alt="숙소 이미지">
                                                        </p>

                                                        <div class="thumb__con">
                                                            <p class="detail-name">레이키푸이스토 풀빌라</p>
                                                            <p class="gpa">
                                                                <span class="start">⏰</span>
                                                                <span class="num purple">뭉클 단독 상시 프로모션 진행 중</span>
                                                            </p>
                                                            <p class="text">
                                                                1) 뭉클 단독 할인 5%+추가 할인쿠폰+아메리카노 2잔 무료 제공<br><br>
                                                                2) 대가족, 두가족 브롱스 패키지
                                                            </p>
                                                        </div>

                                                        <div class="thumb__price">
                                                            <div>
                                                                <p class="sale-percent" style="margin-right: 1rem;">5% ~</p>
                                                                <p class="sale-price">10만원대 ~</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product__box tab-contents">
                                        <div>
                                            <div class="product fnOpenPop" data-name="popupAlert">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="/assets/app/images/article-home/deals/fkxpfktm.jpg" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-name">라테라스&깔라까따리조트</p>
                                                        <p class="gpa">
                                                            <span class="start">⏰</span>
                                                            <span class="num">09.18 (목) 10:30</span>
                                                            <span class="all">오픈 예정</span>
                                                        </p>
                                                        <p class="text">
                                                            1) ★뭉클단독특가★ 워터파크 3인 + 인원추가비 무료 + 호텔바우처 5천원권 + 레이트체크아웃 12시(금/토/공휴일 체크아웃 제외) 
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div>
                                                            <p class="sale-price">특가 네고 중</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product fnOpenPop" data-name="popupAlert">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="/assets/app/images/article-home/deals/take.png" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-name">테이크호텔 서울 광명</p>
                                                        <p class="gpa">
                                                            <span class="start">⏰</span>
                                                            <span class="num">09.18 (목) 10:30</span>
                                                            <span class="all">오픈 예정</span>
                                                        </p>
                                                        <p class="text">
                                                            1) ★뭉클단독특가★ 룸온리특가 (인피니티풀 2인 무료) 
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div>
                                                            <p class="sale-price">특가 네고 중</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product fnOpenPop" data-name="popupAlert">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="/assets/app/images/article-home/deals/mauna.jpg" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-name">마우나 오션 리조트</p>
                                                        <p class="gpa">
                                                            <span class="start">⏰</span>
                                                            <span class="num">09.19 (금) 10:30</span>
                                                            <span class="all">오픈 예정</span>
                                                        </p>
                                                        <p class="text">
                                                            1) ★뭉클특가★ [성인2+소아1] 조식&바데풀 패키지<br>><br>
                                                            2) ★뭉클특가★ [성인2+소아1] 룸 업그레이드 패키지
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div>
                                                            <p class="sale-price">17만원대 ~</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product fnOpenPop" data-name="popupAlert">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="/assets/app/images/article-home/deals/echo.png" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-name">에코랜드 호텔</p>
                                                        <p class="gpa">
                                                            <span class="start">⏰</span>
                                                            <span class="num">09.18 (목) 10:30</span>
                                                            <span class="all">오픈 예정</span>
                                                        </p>
                                                        <p class="text">
                                                        ★뭉클특가★ 인원추가비 무료 (최대인원) + 1시간 얼리체크인
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div>
                                                            <p class="sale-price">10만원대 ~</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="margin-bottom-30">
                            <div class="wrap__box tab__wrap tab-round__wrap type-circle">
                                <h4 class="wrap__title padding-x-20">뭉클 추천 Pick</h4>
                                <div class="overflow-x-visible">
                                    <ul class="capsule-btns tab__inner padding-x-20">
                                        <li class="tab-round__con active">
                                            <a href="#none">수도권</a>
                                        </li>
                                        <li class="tab-round__con">
                                            <a href="#none">강원</a>
                                        </li>
                                        <li class="tab-round__con">
                                            <a href="#none">전라</a>
                                        </li>
                                        <li class="tab-round__con">
                                            <a href="#none">경상</a>
                                        </li>
                                        <li class="tab-round__con">
                                            <a href="#none">충청</a>
                                        </li>
                                        <li class="tab-round__con">
                                            <a href="#none">제주</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-contents__wrap padding-x-20">
                                    <!-- 수도권 -->
                                    <div class="product__box tab-contents active">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/14249">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/1-1.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">마이다스호텔앤리조트</p>
                                                    <p class="detail-sub">
                                                        <span>경기 가평군</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>200,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/9898">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/1-2.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">안토 (구, 파라스파라 서울)</p>
                                                    <p class="detail-sub">
                                                        <span>서울 강북구</span>
                                                        <span>리조트</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">8%</p>
                                                        <p class="price"><span>302.680</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14179">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/1-3.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">호텔 마리나베이서울</p>
                                                    <p class="detail-sub">
                                                        <span>경기 이천시</span>
                                                        <span>3성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">12%</p>
                                                        <p class="price"><span>70,400</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15460">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/1-4.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">호텔 엘로라</p>
                                                    <p class="detail-sub">
                                                        <span>경기 용인시</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">4.0%</p> -->
                                                        <p class="price"><span>99,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <button type="button" class="more-btn" style="margin-top: 3rem;" onclick="location.href='/search-result?text=경기&categoryType=city'">더보기</button>
                                    </div>

                                    <!-- 강원 -->
                                    <div class="product__box tab-contents">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/15410">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/deals/kh_tjfdkr.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">금호 설악 리조트</p>
                                                    <p class="detail-sub">
                                                        <span>강원특별자치도 속초시</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>85,228</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15066">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/deals/pyungchang.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">켄싱턴호텔 평창</p>
                                                    <p class="detail-sub">
                                                        <span>강원특별자치도 평창군</span>
                                                        <span>5성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">30%</p>
                                                        <p class="price"><span>119,901</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/7">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/2-4.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">카시아 속초</p>
                                                    <p class="detail-sub">
                                                        <span>강원특별자치도 속초시</span>
                                                        <span>5성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>220,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14248">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/2-5.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">더앤리조트</p>
                                                    <p class="detail-sub">
                                                        <span>강원특별자치도 양양군</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">76%</p>
                                                        <p class="price"><span>79,200</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <button type="button" class="more-btn" style="margin-top: 3rem;" onclick="location.href='search-result?text=강원&categoryType=city'">더보기</button>
                                    </div>

                                    <!-- 전라 -->
                                    <div class="product__box tab-contents">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/14408">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/3-1.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">에이본 호텔 군산</p>
                                                    <p class="detail-sub">
                                                        <span>전북 군산시</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>77,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14883">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/3-2.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">웰파크호텔 고창</p>
                                                    <p class="detail-sub">
                                                        <span>전북 고창군</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>110,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15185">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/deals/kh_hs.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">금호화순스파리조트</p>
                                                    <p class="detail-sub">
                                                        <span>전남 화순군</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <!-- <p class="price"><span>   ?     </span>원~</p> -->
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <button type="button" class="more-btn" style="margin-top: 3rem;" onclick="location.href='search-result?text=전남&categoryType=city'">더보기</button>
                                    </div>

                                    <!-- 경상 -->
                                    <div class="product__box tab-contents">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/518">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/4-1.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">디오브 해운대 호텔</p>
                                                    <p class="detail-sub">
                                                        <span>부산 해운대구</span>
                                                        <span>2성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>71,200</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/10109">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/4-2.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">켄싱턴리조트 경주</p>
                                                    <p class="detail-sub">
                                                        <span>경북 경주시</span>
                                                        <span>리조트</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>109,900</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/15293">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/4-3.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">김해 아세라 아쿠아 빌라드 호텔</p>
                                                    <p class="detail-sub">
                                                        <span>경남 김해시</span>
                                                        <span>2성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>92,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14387">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/4-4.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">뉴시즈 해운대 레지던스</p>
                                                    <p class="detail-sub">
                                                        <span>부산 해운대구</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">13%</p>
                                                        <p class="price"><span>102,660</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <button type="button" class="more-btn" style="margin-top: 3rem;" onclick="location.href='search-result?text=경남&categoryType=city'">더보기</button>
                                    </div>

                                    <!-- 충청 -->
                                    <div class="product__box tab-contents">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/14200">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/5-1.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">엔포드 호텔</p>
                                                    <p class="detail-sub">
                                                        <span>충북 청주시</span>
                                                        <!-- <span>4성</span> -->
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>176,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/1462">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/5-2.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">빌라드우</p>
                                                    <p class="detail-sub">
                                                        <span>충남 공주시</span>
                                                        <!-- <span>4성</span> -->
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>50,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/9892">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/5-3.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">스플라스 리솜</p>
                                                    <p class="detail-sub">
                                                        <span>충남 예산군</span>
                                                        <!-- <span>4성</span> -->
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>227,360</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/452">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/5-4.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">발레 리조트 보령 대천</p>
                                                    <p class="detail-sub">
                                                        <span>충남 보령시</span>
                                                        <!-- <span>4성</span> -->
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">20%</p>
                                                        <p class="price"><span>344,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <button type="button" class="more-btn" style="margin-top: 3rem;" onclick="location.href='search-result?text=충남&categoryType=city'">더보기</button>
                                    </div>

                                    <!-- 제주 -->
                                    <div class="product__box tab-contents">
                                        <div>
                                            <div class="product">
                                                <a href="/stay/detail/14953">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/6-1.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">해비치 호텔&리조트 제주</p>
                                                    <p class="detail-sub">
                                                        <span>제주특별자치도 서귀포시</span>
                                                        <span>5성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">30%</p>
                                                        <p class="price"><span>220,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14333">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/6-2.png" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">에코랜드 호텔</p>
                                                    <p class="detail-sub">
                                                        <span>제주특별자치도 서귀포시</span>
                                                        <span>5성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <!-- <p class="discount">30%</p> -->
                                                        <p class="price"><span>275,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14203">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/6-3.jpg" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">씨사이드아덴</p>
                                                    <p class="detail-sub">
                                                        <span>제주특별자치도 서귀포시</span>
                                                        <span>4성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="discount">20%</p>
                                                        <p class="price"><span>202,400</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product">
                                                <a href="/stay/detail/14438">
                                                    <div class="thumbnail__box">
                                                        <img src="/assets/app/images/article-home/picks/6-4.png" alt="숙소 이미지">
                                                    </div>
                                                    <p class="product-name">씨에스 호텔 앤 리조트</p>
                                                    <p class="detail-sub">
                                                        <span>제주특별자치도 서귀포시</span>
                                                        <span>5성</span>
                                                    </p>
                                                    <div class="price__box">
                                                        <p class="price"><span>310,000</span>원~</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <button type="button" class="more-btn" style="margin-top: 3rem;" onclick="location.href='search-result?text=제주&categoryType=city'">더보기</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="padding-x-20">
                            <div id="recommendationBox" class="recommendation__box margin-top-30">
                                <div>
                                    <p class="text">우리 아이와 함께 숙소, 고민된다면?</p>
                                    <button type="button" id="gettingRecommendation" class="gettingRecommendation" onclick="location.href='/moongcledeals'">맘 편하게 숙소 추천 받기</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- 네고딜 오픈 예정 팝업 -->
        <div id="popupAlert" class="layerpop__wrap type-center">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">오픈 예정 🎉</p>
                    <a href="#none" class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    해당 상품은 오픈 예정입니다. 
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary fnClosePop">확인</button>
                    </div>
                </div>
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
        const searchBottomWrap = document.getElementById('searchBottomWrap');
        const bottomSearchBar = document.getElementById('bottomSearchBarWrap');
        const headerWrap = document.querySelector('.header__wrap');
        const bottomContentWrap = document.querySelector('.bottomContentWrap');
        const toggleTargets = searchBottomWrap.querySelectorAll('.bottom__header, .bottom__header__title');

        function setVh() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh * 100}px`);
        }

        setVh();
        window.addEventListener('resize', setVh);

        function openBottomSheet() {
            searchBottomWrap.classList.add('open');
            bottomSearchBar.classList.add('fixed');
            bottomSearchBar.style.opacity = "1";
            bottomSearchBar.style.zIndex = "100";
            bottomContentWrap.style.marginTop = "0";
            headerWrap.style.opacity = "0";

            searchBottomWrap.style.transform = '';
        }

        function closeBottomSheet() {
            searchBottomWrap.classList.remove('open');
            bottomSearchBar.classList.remove('fixed');
            bottomSearchBar.style.opacity = "0";
            bottomSearchBar.style.zIndex = "-1";
            bottomContentWrap.style.marginTop = "-9rem";
            headerWrap.style.opacity = "1";

            searchBottomWrap.style.transform = '';
        }

        function toggleBottomSheet() {
            if (searchBottomWrap.classList.contains('open')) {
                closeBottomSheet();
            } else {
                openBottomSheet();
            }
        }

        // 헤더들 터치/클릭 시 → 열기/닫기 토글
        toggleTargets.forEach(target => {
            target.addEventListener('click', toggleBottomSheet);
            target.addEventListener('touchstart', toggleBottomSheet);
        });
    </script>

    <script>
        // 배너 Swiper
        var swiper1 = new Swiper('.swiper-container.banner', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 10000,
                disableOnInteraction: false,
            },
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
        });

        // 가로 탭 메뉴 active
        const menuItems = document.querySelectorAll('#overflowMenu .menu');

        menuItems.forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();

                menuItems.forEach(function (el) {
                    el.classList.remove('active');
                });

                this.classList.add('active');
            });
        });

        // 나만의 뭉클딜 swiper
        var swiper2 = new Swiper('.moongcledeal-slide .swiper-container', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            loop: true,
            slidesPerView: 'auto',
            centeredSlides: true,
            spaceBetween: 12,
            on: {
                init: function () {
                    setMoongcleBackground(this);
                },
                slideChangeTransitionEnd: function () {
                    setMoongcleBackground(this);
                }
            }
        });

        // 배너 슬라이드 배경 설정
        function setMoongcleBackground(swiper) {
            const activeSlide = swiper.slides[swiper.activeIndex];
            const img = activeSlide.querySelector('video');
            const imageUrl = img?.poster;
            const wrapper = document.querySelector('.moongcledeal__wrap');

            if (imageUrl && wrapper) {
                wrapper.style.setProperty('--bg-image', `url(${imageUrl})`);
                wrapper.style.setProperty('--bg-loaded', 'true'); 
                wrapper.style.setProperty('--bg-transition', 'opacity 0.1s ease');
                wrapper.style.setProperty('background-image', `url(${imageUrl})`);
                wrapper.style.setProperty('background-size', 'cover');
                wrapper.style.setProperty('background-position', 'center');
                wrapper.style.setProperty('background-repeat', 'no-repeat');
            }

            // 혹시 ::before로 처리할 경우
            wrapper.style.setProperty('--dynamic-bg', `url(${imageUrl})`);
            wrapper.querySelector('::before'); 
        }

        // 호캉스 가기 좋은 날씨 캡슐 버튼
        const buttons = document.querySelectorAll('.capsule-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
           
            buttons.forEach(b => b.classList.remove('active'));
           
            btn.classList.add('active');
            });
        });
        
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

    <script>
        // 모든 .item a에 클릭 이벤트 추가
        document.querySelectorAll('.item a').forEach(link => {
            link.addEventListener('click', function (e) {
            showLoader();
            });
        });

        // .go_stay 클릭 시
        document.querySelectorAll('.go_stay').forEach(el => {
            el.addEventListener('click', function () {
            showLoader();
            });
        });

        // .product 안의 a 태그 클릭 시
        document.querySelectorAll('.product a').forEach(el => {
            el.addEventListener('click', function () {
            showLoader();
            });
        });
    </script>

</body>

</html>