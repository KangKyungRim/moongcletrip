<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$isGuest = $data['isGuest'];
$mainTagsAll = $data['mainTags'];
$reviewsV1 = $data['reviewsV1'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];
$recommendStays = $data['recommendStays'];
$hotStays = $data['hotStays'];
$mainMoongcledeal = $data['mainMoongcledeal'];
$mainMoongcledealOffers = $data['mainMoongcledealOffers'];

$mainTags = getRandomTags($mainTagsAll, intval(count($mainTagsAll) / 3));

// $deviceType = 'app';
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
                <h1 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h1>
                <div class="btn__wrap">
                    <button type="button" class="btn-search" onclick="gotoSearch()"><span class="blind">검색</span></button>
                    <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
                    <!-- <button type="button" class="btn-cart__gray" onclick="gotoTravelCart()"><span class="blind">장바구니</span></button> -->
                </div>
            </div>
        </header>

        <div class="container__wrap main__wrap">
            <?php if ($isGuest || empty($user)) : ?>
                <section class="layout__wrap bg-gray">
                    <?php if (!empty($mainMoongcledealOffers[0])) : ?>
                        <div class="tit__wrap">
                            <p class="tit type2"><span class="nickname">뭉클러님,</span> 안녕하세요!</p>
                            <p class="sub-tit">나만의 뭉클딜을 확인해보세요</p>
                        </div>
                    <?php else : ?>
                        <div class="tit__wrap">
                            <p class="tit">설레는 여행 찾기</p>
                            <p class="sub-tit">이제, 내 여행 취향만 등록하면 끝!</p>
                        </div>
                    <?php endif; ?>
                    <div class="box-white__list">

                        <?php if (!empty($mainMoongcledealOffers[0])) : ?>
                            <!-- 나만의 뭉클딜 -->
                            <div class="box-white__wrap moongcle">
                                <p class="title" onclick="gotoMoongcledeal(event, <?= $mainMoongcledeal->moongcledeal_idx; ?>)">
                                    나만의 뭉클딜
                                </p>
                                <?php
                                $moongcledealPriority = json_decode($mainMoongcledeal->priority);
                                ?>
                                <div class="review-tag" onclick="gotoMoongcledeal(event, <?= $mainMoongcledeal->moongcledeal_idx; ?>)">
                                    <ul>
                                        <?php foreach ($moongcledealPriority as $priority) : ?>
                                            <li>
                                                <img src="/uploads/tags/<?= $priority->tag_machine_name; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                <span><?= $priority->tag_name; ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="splide splide__moongcle">
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            <?php foreach ($mainMoongcledealOffers as $moongcleoffer) : ?>
                                                <?php
                                                $category = '';
                                                if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
                                                    $category = '숙박';
                                                }

                                                $curatedTags = json_decode($moongcleoffer->curated_tags);
                                                $room_benefits = json_decode($moongcleoffer->room_benefits);
                                                $rateplan_benefits = json_decode($moongcleoffer->rateplan_benefits);
                                                $moongcleoffer_benefits = json_decode($moongcleoffer->moongcleoffer_benefits);
                                                ?>
                                                <li class="splide__slide" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>)">
                                                    <div class="box-gray__wrap">
                                                        <div class="thumb__wrap">
                                                            <p class="thumb__img large"><img src="<?= $moongcleoffer->image_normal_path; ?>" alt=""></p>
                                                            <div class="thumb__con">
                                                                <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                    <div class="thumb-badge">최대 <?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>% 할인!</div>
                                                                <?php endif; ?>
                                                                <p class="detail-sub">
                                                                    <span><?= $moongcleoffer->partner_address1; ?></span>
                                                                    <span>
                                                                        <?php $stayTypes = explode(':-:', $moongcleoffer->types); ?>
                                                                        <?php if (!empty($stayTypes)) : ?>
                                                                            <span>
                                                                                <?php foreach ($stayTypes as $tagKey => $stayType) : ?>
                                                                                    <?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
                                                                                <?php endforeach; ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </p>
                                                                <p class="detail-name"><?= $moongcleoffer->partner_name; ?></p>
                                                            </div>
                                                            <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                <div class="thumb__price">
                                                                    <div>
                                                                        <p class="sale-percent"><?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>%</p>
                                                                        <p class="default-price"><?= number_format($moongcleoffer->basic_price); ?>원</p>
                                                                        <p class="sale-price"><?= number_format($moongcleoffer->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                                    </div>
                                                                    <!-- <p class="ft-xxs">취소 불가 상품</p> -->
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="thumb__gift">
                                                                <ul>
                                                                    <?php if (!empty($room_benefits)) : ?>
                                                                        <?php foreach ($room_benefits as $benefit) : ?>
                                                                            <li><?= $benefit->benefit_name; ?></li>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($rateplan_benefits)) : ?>
                                                                        <?php foreach ($rateplan_benefits as $benefit) : ?>
                                                                            <li><?= $benefit->benefit_name; ?></li>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($moongcleoffer_benefits)) : ?>
                                                                        <?php foreach ($moongcleoffer_benefits as $benefit) : ?>
                                                                            <li><?= $benefit->benefit_name; ?></li>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- //나만의 뭉클딜 -->
                        <?php endif; ?>

                        <!-- 태그 알림받기 -->
                        <div class="box-white__wrap">
                            <div class="tag-animation__wrap">
                                <div class="tag-animation__tit">
                                    <p class="tit">
                                        <span class="typing-ani">내가 원하는 <strong class="txt-primary">여행</strong>은?</span>
                                    </p>
                                    <p class="sub-tit">내 취향 태그를 아래 고리에 걸어주세요</p>
                                </div>
                                <div class="tag-animation__con">
                                    <div class="main-tag__select">
                                        <div class="main-tag__thumb"></div>
                                        <div class="main-tag__thumb"></div>
                                        <div class="main-tag__thumb"></div>
                                    </div>
                                    <div class="main-tag__slide">
                                        <div class="swiper-container">
                                            <ul id="mainSlider" class="swiper-wrapper">
                                                <?php foreach ($mainTags as $mainTag) : ?>
                                                    <li class="swiper-slide">
                                                        <a href="" class="main-tag__btn" data-machine-name="<?= $mainTag['tag_machine_name']; ?>">
                                                            <p class="img"><img src="/uploads/tags/<?= $mainTag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                                            <p class="txt tag-text-width"><?= $mainTag['tag_name']; ?></p>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <button id="tagRefresh" type="button" class="btn-txt__gray"><i class="ico ico-refresh"></i>새로고침</button>
                                    <button type="button" class="btn-full__primary fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertWeb' : 'alertApp' ?>">여 행 시 작 하 기</button>
                                </div>
                            </div>
                        </div>
                        <!-- //태그 알림받기 -->

                        <div class="box-white__wrap">
                            <p class="title">
                                어떤 설레는 여행을<br>
                                떠나볼까요? ✨
                            </p>
                            <div class="splide splide__default_custom splide__small" style="height: 27vh;">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/005_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/006_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/011_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/004_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/001_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/002_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/003_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/007_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/008_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/009_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/010_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex-between">
                                <div class="txt__wrap">
                                    <p id="recommendationTagText" class="sub-txt">#아이와 갈만한 곳</p>
                                    <p class="txt">나만의 여행 무료로 추천받기</p>
                                </div>
                                <button id="recommendTagCopy" type="button" class="btn-sm__black fnOpenPop" style="width: fit-content;" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">여행 추천 받기</button>
                            </div>
                        </div>

                        <div class="box-white__wrap">
                            <p class="title">
                                어떤 숙소를 찾아드릴까요?
                            </p>

                            <div class="recommend-stay-container">
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/hotel.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">호텔<br>리조트</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/infinity_pool.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">풀빌라<br>펜션</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/emotional_accommodation.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">감성 숙소</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/glamping.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">글램핑<br>캠핑</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/gunsan.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">한옥<br>스테이</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/romantic_spot_recommendation.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">커플<br>데이트</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/with_kids.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">아이와<br>함께</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/pet_friendly_pension.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">애견동반</p>
                                </div>
                            </div>
                        </div>

                        <div class="box-white__wrap">
                            <p class="title">
                                지금 인기 숙소 🔥<br>
                                뭉클딜 제안을 받아보세요
                            </p>
                            <div class="splide splide__default_custom2 splide__small" style="height: 27vh;">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                        <?php foreach ($hotStays as $hotStay) : ?>
                                            <li class="splide__slide" style="text-shadow: 1px 1px 2px black; border-radius: 1.2rem;" onclick="location.href='/stay/detail/<?= $hotStay->partner_idx; ?>'">
                                                <img src="<?= $hotStay->image_normal_path; ?>" alt="">
                                                <div class="overlay"></div>
                                                <div class="text-overlay-top">
                                                    <p><?= $hotStay->partner_address1; ?> | <?= $hotStay->lowest_price_korean; ?>~</p>
                                                </div>
                                                <div class="text-overlay" style="text-shadow: 1px 1px 2px black;">
                                                    <h2><?= $hotStay->partner_name; ?></h2>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex-between">
                                <div class="txt__wrap">
                                    <p class="sub-txt">마음에 드는 숙소를 발견했다면?</p>
                                    <p class="txt">뭉클딜 특가 제안받기</p>
                                </div>
                                <button type="button" id="gotoMainMoongcleoffer" class="btn-sm__black" style="width: fit-content;">뭉클딜 받으러 가기</button>
                            </div>
                        </div>

                        <!-- //뭉클태그 혜택 -->
                        <!-- 뭉클러 혜택 -->
                        <div class="box-white__wrap">
                            <p class="title">
                                지난 달 뭉클러들이 누린 혜택
                            </p>
                            <div class="benefit-box__wrap">
                                <div class="benefit-box">
                                    <img src="/assets/app/images/layout/img_main_benefit01.png" alt="">
                                    <p class="txt">
                                        평균 <span>32,159원</span><br>
                                        여행비 절약!
                                    </p>
                                </div>
                                <i class="ico ico-main__plus"></i>
                                <div class="benefit-box">
                                    <img src="/assets/app/images/layout/img_main_benefit02.png" alt="">
                                    <p class="txt">
                                        무료 혜택 <br>
                                        <span>평균 1.3회</span>
                                    </p>
                                </div>
                            </div>
                            <div class="benefit-list__wrap">
                                <div class="benefit-list">
                                    <p class="tit">무료 조식or할인</p>
                                    <p class="con">3,815회</p>
                                </div>
                                <div class="benefit-list">
                                    <p class="tit">레이트 체크아웃</p>
                                    <p class="con">평균 0.3회</p>
                                </div>
                                <div class="benefit-list">
                                    <p class="tit">무료 룸업글</p>
                                    <p class="con">평균 0.4회</p>
                                </div>
                            </div>
                        </div>
                        <!-- //뭉클러 혜택 -->
                    </div>
                </section>
                <!-- 뭉클러 이용 후기 -->
                <?php if (!empty($reviewsV1[0])) : ?>
                    <section class="layout__wrap">
                        <div class="tit__wrap">
                            <p class="tit">뭉클러들의 생생한 이용기</p>
                        </div>
                        <div class="review-list__wrap">

                            <?php foreach ($reviewsV1 as $reviewV1) : ?>
                                <div class="review-list__con">
                                    <div class="review-tit">
                                        <div class="tit__wrap">
                                            <p class="title">
                                                <span class="nickname"><?= $reviewV1->user_nickname; ?></span>님의 <span>#뭉클태그</span>
                                            </p>
                                        </div>
                                        <button type="button" style="white-space: nowrap; margin-left: 0.5rem;" class="btn-sm__black moongcleTagCopy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>" data-encoded-tags="<?= $reviewV1->encoded_tags; ?>">태그 복사</button>
                                    </div>
                                    <div class="review-tag">
                                        <?php
                                        $reviewTags = json_decode($reviewV1->tag_list);
                                        ?>
                                        <ul>
                                            <?php foreach ($reviewTags as $reviewTag) : ?>
                                                <li>
                                                    <img src="/uploads/tags/<?= $reviewTag->tag_machine_name; ?>.png" alt="">
                                                    <span><?= $reviewTag->tag_name; ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="review-img">
                                        <?php if (!empty($reviewV1->image_list)) : ?>
                                            <div class="splide splide__default">
                                                <div class="splide__track">
                                                    <ul class="splide__list">
                                                        <?php
                                                        $reviewImages = json_decode($reviewV1->image_list);
                                                        ?>

                                                        <?php foreach ($reviewImages as $reviewImage) : ?>
                                                            <?php
                                                            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                            $allowedVideoExtensions = ['mov', 'mp4'];
                                                            ?>
                                                            <?php if (in_array($reviewImage->extension, $allowedImageExtensions)) : ?>
                                                                <li class="splide__slide"><img src="<?= $reviewImage->path; ?>" alt=""></li>
                                                            <?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
                                                                <li class="splide__slide">
                                                                    <video class="video-element" controls>
                                                                        <source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
                                                                        현재 브라우저가 지원하지 않는 영상입니다.
                                                                    </video>
                                                                </li>
                                                            <?php else : ?>
                                                                <li class="splide__slide"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
                                                            <?php endif; ?>

                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="review-txt">
                                        <p class="review" style="white-space: pre-wrap;"><?= $reviewV1->review_content; ?></p>
                                        <a class="btn-more">더보기</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </section>
                <?php endif; ?>
                <!-- // 뭉클러 이용 후기 -->
            <?php else : ?>
                <section class="layout__wrap bg-gray">
                    <?php if (!empty($mainMoongcledealOffers[0])) : ?>
                        <div class="tit__wrap">
                            <p class="tit type2"><span class="nickname"><?= $user->user_nickname; ?>님,</span> 안녕하세요!</p>
                            <p class="sub-tit">나만의 뭉클딜을 확인해보세요</p>
                        </div>
                    <?php else : ?>
                        <div class="tit__wrap">
                            <p class="tit">설레는 여행 찾기</p>
                            <p class="sub-tit">이제, 내 여행 취향만 등록하면 끝!</p>
                        </div>
                    <?php endif; ?>
                    <div class="box-white__list">
                        <?php if (!empty($mainMoongcledealOffers[0])) : ?>
                            <!-- 나만의 뭉클딜 -->
                            <div class="box-white__wrap moongcle">
                                <p class="title" onclick="gotoMoongcledeal(event, <?= $mainMoongcledeal->moongcledeal_idx; ?>)">
                                    나만의 뭉클딜
                                </p>
                                <?php
                                $moongcledealPriority = json_decode($mainMoongcledeal->priority);
                                ?>
                                <div class="review-tag" onclick="gotoMoongcledeal(event, <?= $mainMoongcledeal->moongcledeal_idx; ?>)">
                                    <ul>
                                        <?php foreach ($moongcledealPriority as $priority) : ?>
                                            <li>
                                                <img src="/uploads/tags/<?= $priority->tag_machine_name; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                <span><?= $priority->tag_name; ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="splide splide__moongcle">
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            <?php foreach ($mainMoongcledealOffers as $moongcleoffer) : ?>
                                                <?php
                                                $category = '';
                                                if ($moongcleoffer->moongcleoffer_category == 'roomRateplan') {
                                                    $category = '숙박';
                                                }

                                                $curatedTags = json_decode($moongcleoffer->curated_tags);
                                                $room_benefits = json_decode($moongcleoffer->room_benefits);
                                                $rateplan_benefits = json_decode($moongcleoffer->rateplan_benefits);
                                                $moongcleoffer_benefits = json_decode($moongcleoffer->moongcleoffer_benefits);
                                                ?>
                                                <li class="splide__slide" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>)">
                                                    <div class="box-gray__wrap">
                                                        <div class="thumb__wrap">
                                                            <p class="thumb__img large"><img src="<?= $moongcleoffer->image_normal_path; ?>" alt=""></p>
                                                            <div class="thumb__con">
                                                                <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                    <div class="thumb-badge">최대 <?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>% 할인!</div>
                                                                <?php endif; ?>
                                                                <p class="detail-sub">
                                                                    <span><?= $moongcleoffer->partner_address1; ?></span>
                                                                    <span>
                                                                        <?php $stayTypes = explode(':-:', $moongcleoffer->types); ?>
                                                                        <?php if (!empty($stayTypes)) : ?>
                                                                            <span>
                                                                                <?php foreach ($stayTypes as $tagKey => $stayType) : ?>
                                                                                    <?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
                                                                                <?php endforeach; ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </p>
                                                                <p class="detail-name"><?= $moongcleoffer->partner_name; ?></p>
                                                            </div>
                                                            <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                <div class="thumb__price">
                                                                    <div>
                                                                        <p class="sale-percent"><?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>%</p>
                                                                        <p class="default-price"><?= number_format($moongcleoffer->basic_price); ?>원</p>
                                                                        <p class="sale-price"><?= number_format($moongcleoffer->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                                    </div>
                                                                    <!-- <p class="ft-xxs">취소 불가 상품</p> -->
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="thumb__gift">
                                                                <ul>
                                                                    <?php if (!empty($room_benefits)) : ?>
                                                                        <?php foreach ($room_benefits as $benefit) : ?>
                                                                            <li><?= $benefit->benefit_name; ?></li>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($rateplan_benefits)) : ?>
                                                                        <?php foreach ($rateplan_benefits as $benefit) : ?>
                                                                            <li><?= $benefit->benefit_name; ?></li>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($moongcleoffer_benefits)) : ?>
                                                                        <?php foreach ($moongcleoffer_benefits as $benefit) : ?>
                                                                            <li><?= $benefit->benefit_name; ?></li>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- //나만의 뭉클딜 -->
                        <?php endif; ?>

                        <!-- 태그 알림받기 -->
                        <div class="box-white__wrap">
                            <div class="tag-animation__wrap">
                                <div class="tag-animation__tit">
                                    <p class="tit">
                                        <span class="typing-ani">내가 원하는 <strong class="txt-primary">여행</strong>은?</span>
                                    </p>
                                    <p class="sub-tit">내 취향 태그를 아래 고리에 걸어주세요</p>
                                </div>
                                <div class="tag-animation__con">
                                    <div class="main-tag__select">
                                        <div class="main-tag__thumb"></div>
                                        <div class="main-tag__thumb"></div>
                                        <div class="main-tag__thumb"></div>
                                    </div>
                                    <div class="main-tag__slide">
                                        <div class="swiper-container">
                                            <ul id="mainSlider" class="swiper-wrapper">
                                                <?php foreach ($mainTags as $mainTag) : ?>
                                                    <li class="swiper-slide">
                                                        <a href="" class="main-tag__btn" data-machine-name="<?= $mainTag['tag_machine_name']; ?>">
                                                            <p class="img"><img src="/uploads/tags/<?= $mainTag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                                            <p class="txt tag-text-width"><?= $mainTag['tag_name']; ?></p>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <button id="tagRefresh" type="button" class="btn-txt__gray"><i class="ico ico-refresh"></i>새로고침</button>
                                    <button type="button" class="btn-full__primary fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertWeb' : 'alertApp' ?>">여 행 시 작 하 기</button>
                                </div>
                            </div>
                        </div>
                        <!-- //태그 알림받기 -->

                        <div class="box-white__wrap">
                            <p class="title">
                                어떤 설레는 여행을<br>
                                떠나볼까요? ✨
                            </p>
                            <div class="splide splide__default_custom splide__small" style="height: 27vh;">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/005_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/006_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/011_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/004_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/001_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/002_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/003_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/007_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/008_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/009_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                        <li class="splide__slide recommend-tag-copy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                            <img src="/assets/app/images/main/010_home_recommendation.png" alt="" style="border-radius: 1.2rem;">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex-between">
                                <div class="txt__wrap">
                                    <p id="recommendationTagText" class="sub-txt">#아이와 갈만한 곳</p>
                                    <p class="txt">나만의 여행 무료로 추천받기</p>
                                </div>
                                <button id="recommendTagCopy" type="button" class="btn-sm__black fnOpenPop" style="width: fit-content;" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">여행 추천 받기</button>
                            </div>
                        </div>

                        <div class="box-white__wrap">
                            <p class="title">
                                어떤 숙소를 찾아드릴까요?
                            </p>

                            <div class="recommend-stay-container">
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/hotel.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">호텔<br>리조트</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/infinity_pool.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">풀빌라<br>펜션</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/emotional_accommodation.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">감성 숙소</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/glamping.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">글램핑<br>캠핑</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/gunsan.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">한옥<br>스테이</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/romantic_spot_recommendation.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">커플<br>데이트</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/with_kids.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">아이와<br>함께</p>
                                </div>
                                <div class="recommend-stay fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                    <p class="img"><img src="/uploads/tags/pet_friendly_pension.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                    <p class="txt">애견동반</p>
                                </div>
                            </div>
                        </div>

                        <div class="box-white__wrap">
                            <p class="title">
                                지금 인기 숙소 🔥<br>
                                뭉클딜 제안을 받아보세요
                            </p>
                            <div class="splide splide__default_custom2 splide__small" style="height: 27vh;">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                        <?php foreach ($hotStays as $hotStay) : ?>
                                            <li class="splide__slide" style="text-shadow: 1px 1px 2px black; border-radius: 1.2rem;" onclick="location.href='/stay/detail/<?= $hotStay->partner_idx; ?>'">
                                                <img src="<?= $hotStay->image_normal_path; ?>" alt="">
                                                <div class="overlay"></div>
                                                <div class="text-overlay-top">
                                                    <p><?= $hotStay->partner_address1; ?> | <?= $hotStay->lowest_price_korean; ?>~</p>
                                                </div>
                                                <div class="text-overlay" style="text-shadow: 1px 1px 2px black;">
                                                    <h2><?= $hotStay->partner_name; ?></h2>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex-between">
                                <div class="txt__wrap">
                                    <p class="sub-txt">마음에 드는 숙소를 발견했다면?</p>
                                    <p class="txt">뭉클딜 특가 제안받기</p>
                                </div>
                                <button type="button" id="gotoMainMoongcleoffer" class="btn-sm__black" style="width: fit-content;">뭉클딜 받으러 가기</button>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- 뭉클러 이용 후기 -->
                <?php if (!empty($reviewsV1[0])) : ?>
                    <section class="layout__wrap">
                        <div class="tit__wrap">
                            <p class="tit">뭉클러들의 생생한 이용기</p>
                        </div>
                        <div class="review-list__wrap">

                            <?php foreach ($reviewsV1 as $reviewV1) : ?>
                                <div class="review-list__con">
                                    <div class="review-tit">
                                        <div class="tit__wrap">
                                            <p class="title">
                                                <span class="nickname"><?= $reviewV1->user_nickname; ?></span>님의 <span>#뭉클태그</span>
                                            </p>
                                        </div>
                                        <button type="button" style="white-space: nowrap; margin-left: 0.5rem;" class="btn-sm__black moongcleTagCopy fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>" data-encoded-tags="<?= $reviewV1->encoded_tags; ?>">태그 복사</button>
                                    </div>
                                    <div class="review-tag">
                                        <?php
                                        $reviewTags = json_decode($reviewV1->tag_list);
                                        ?>
                                        <ul>
                                            <?php foreach ($reviewTags as $reviewTag) : ?>
                                                <li>
                                                    <img src="/uploads/tags/<?= $reviewTag->tag_machine_name; ?>.png" alt="">
                                                    <span><?= $reviewTag->tag_name; ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="review-img">
                                        <?php if (!empty($reviewV1->image_list)) : ?>
                                            <div class="splide splide__default">
                                                <div class="splide__track">
                                                    <ul class="splide__list">
                                                        <?php
                                                        $reviewImages = json_decode($reviewV1->image_list);
                                                        ?>

                                                        <?php foreach ($reviewImages as $reviewImage) : ?>
                                                            <?php
                                                            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                            $allowedVideoExtensions = ['mov', 'mp4'];
                                                            ?>
                                                            <?php if (in_array($reviewImage->extension, $allowedImageExtensions)) : ?>
                                                                <li class="splide__slide"><img src="<?= $reviewImage->path; ?>" alt=""></li>
                                                            <?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
                                                                <li class="splide__slide">
                                                                    <video class="video-element" controls>
                                                                        <source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
                                                                        현재 브라우저가 지원하지 않는 영상입니다.
                                                                    </video>
                                                                </li>
                                                            <?php else : ?>
                                                                <li class="splide__slide"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
                                                            <?php endif; ?>

                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="review-txt">
                                        <p class="review" style="white-space: pre-wrap;"><?= $reviewV1->review_content; ?></p>
                                        <a class="btn-more">더보기</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </section>
                <?php endif; ?>
                <!-- // 뭉클러 이용 후기 -->
            <?php endif; ?>

            <?php if ($deviceType !== 'app') : ?>
                <div style="padding: 0rem 2rem 2.4rem 2rem;" onclick="openAppDownloadTab()">
                    <img src="/assets/app/images/main/download_section.png" alt="">
                </div>
            <?php endif; ?>

            <hr class="divide">
            <!-- 자주 묻는 질문 -->
            <div class="faq__wrap accordion__wrap">
                <p class="title">자주 묻는 질문</p>
                <div class="accordion__list">
                    <div class="accordion__tit">
                        <p class="ft-default">뭉클딜 받기는 무료인가요?</p>
                        <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                    </div>
                    <div class="accordion__con">
                        네, 무료입니다.<br>
                        뭉클딜 알림부터 간편한 예약까지, 마음 편히 이용하실 수 있습니다.
                    </div>
                </div>
                <div class="accordion__list">
                    <div class="accordion__tit">
                        <p class="ft-default">뭉클딜은 무엇인가요?</p>
                        <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                    </div>
                    <div class="accordion__con">
                        고객님께서 원하시는 여행을 등록만 해두시면 뭉클이 알아서 찾아드리는 새로운 방식의 여행앱입니다. 고객님 취향에 꼭 맞는 여행을 더 쉽고, 빠르게, 그리고 더 합리적인 가격과 혜택으로 찾아드려요.<br>
                        <br>
                        [간단한 뭉클딜 등록 방법]<br>
                        1. ‘뭉클딜 등록하기’ 클릭 <br>
                        2. 관심 있는 여행을 간편하게 등록 (30초 소요)<br>
                        3. 나에게 맞는 뭉클딜 알림 도착<br>
                        4. 원하는 뭉클딜을 선택하고 예약 완료!
                    </div>
                </div>
                <div class="accordion__list">
                    <div class="accordion__tit">
                        <p class="ft-default">뭉클딜로 예약하면 무엇이 좋나요?</p>
                        <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                    </div>
                    <div class="accordion__con">
                        뭉클은 고객님께 최적화된 여행을 더 저렴하고, 맞춤 혜택으로 제공해드립니다. 뭉클은 고객님의 요청사항을 기반으로 여행파트너와 협상하여 더 좋은 가격과 혜택을 제안합니다.
                    </div>
                </div>
                <div class="accordion__list">
                    <div class="accordion__tit">
                        <p class="ft-default">뭉클딜을 어떻게 활용할 수 있나요?</p>
                        <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                    </div>
                    <div class="accordion__con">
                        뭉클딜 알림을 통해 고객님의 여행 일정에 맞는 최적의 딜을 받을 수 있습니다. 예를 들어, 커플이시라면 #도심 속 호캉스, 아이동반 가족이시라면 #아이와 함께 갈만한 곳과 같은 여행딜을 추천받을 수 있습니다. 나만의 뭉클태그를 등록하고 설레는 여행을 만나보세요.
                    </div>
                </div>
                <div class="accordion__list">
                    <div class="accordion__tit">
                        <p class="ft-default">뭉클태그 복사하기는 무엇인가요?</p>
                        <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                    </div>
                    <div class="accordion__con">
                        다른 여행자의 후기나 콘텐츠에서 마음에 드는 뭉클태그를 쉽게 복사하여, 새로운 뭉클딜을 간편하게 등록할 수 있습니다.
                    </div>
                </div>
            </div>
            <!-- //자주 묻는 질문 -->
            <!-- 푸터 -->
            <footer class="footer__wrap">
                <div class="footer__inner">
                    <div class="company-info">
                        <p class="name">(주)호놀룰루컴퍼니</p>
                        <p>한국관광공사 인증 관광벤처기업</p>
                    </div>

                    <div class="btn__wrap">
                        <button type="button" class="btn-sm__primary btn-sns__kakao" onclick="outLink('https://pf.kakao.com/_dEwbG')">톡 상담</button>
                        <button type="button" class="btn-sm__primary" onclick="outLink('https://tally.so/r/nWEqpk')">뭉클 입점문의</button>
                    </div>

                    <div class="company-details__wrap">
                        <div class="company-details">
                            <p>대표자 : 김범수</p>
                            <p>주소 : 경기도 안양시 동안구 시민대로327번길 11-41 5층</p>
                        </div>
                        <div class="company-details">
                            <p>사업자 등록번호 : 485-87-02613</p>
                            <p>대표번호 : 070-7537-2694</p>
                        </div>
                        <div class="company-details">
                            <p>통신판매업 신고번호 : 제2023-안양동안-0792호</p>
                            <p>관광사업자 등록번호 : 제2023-000006호</p>
                        </div>
                        <div class="company-details">
                            <span>(주)호놀룰루컴퍼니는 통신판매중개자로서 통신판매의 당사자가 아니며, 상품의 예약, 이용 및 환불 등과 관련된 의무와 책임은 각 판매자에게 있습니다.</span>
                        </div>
                    </div>

                    <div class="footer__links">
                        <a href="/notices" class="footer-link">공지사항</a>
                        <a onclick="outLink('http://www.ftc.go.kr/bizCommPop.do?wrkr_no=56454644253')" target="_blank" class="footer-link cursor-pointer">사업자정보확인</a>
                        <a onclick="outLink('https://www.instagram.com/moongcletrip')" target="_blank" class="footer-link cursor-pointer">인스타그램</a>
                        <a href="/term/financial-transaction" class="footer-link">전자금융거래이용약관</a>
                        <a href="/term/privacy-policy" class="footer-link">개인정보처리방침</a>
                        <a href="/term/youth-protection-policy" class="footer-link">청소년보호정책</a>
                        <a href="/term/review-polocy" class="footer-link">리뷰운영정책</a>
                        <a href="/term/consumer-dispute-resolution-standards" class="footer-link">소비자분쟁해결기준</a>
                        <a href="/" class="footer-link">처음으로</a>
                    </div>
                </div>
            </footer>
            <!-- //푸터 -->

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

        </div>

        <!-- 뭉클딜 등록 팝업 -->
        <div id="alertApp" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <p class="title">
                        아래의 여행정보로 <br>
                        뭉클딜을 받아볼까요?
                    </p>
                </div>
                <div class="layerpop__contents">
                    <div class="select-tag__wrap">
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button id="startMoongcledeal" class="btn-full__primary">지금 뭉클딜 등록하기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // 뭉클딜 등록 팝업 -->
        <!-- 뭉클딜 등록 팝업 (웹)-->
        <div id="alertWeb" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <div class="align__left">
                        <p class="title">
                            여행 추천 받기는 앱에서만 가능해요!
                        </p>
                        <p class="desc">아래의 태그로 나만의 여행을 추천 받아볼까요?</p>
                    </div>
                </div>
                <div class="layerpop__contents">
                    <div class="select-tag__wrap">
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary" onclick="openAppDownloadTab()">지금 앱으로 여행 추천 받기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // 뭉클딜 등록 팝업 (웹) -->
        <!-- 뭉클태그 카피 팝업 (앱) -->
        <div id="alertCopyApp" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <p class="title">
                        아래의 여행정보로 <br>
                        뭉클딜을 받아볼까요?
                    </p>
                </div>
                <div class="layerpop__contents">
                    <div class="select-tag__wrap">
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button id="startMoongcleTagCopy" class="btn-full__primary">지금 뭉클딜 등록하기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // 뭉클태그 카피 팝업 (앱) -->
        <!-- 뭉클태그 카피 팝업 (웹)-->
        <div id="alertCopyWeb" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <div class="align__left">
                        <p class="title">
                            여행 추천 받기는 앱에서만 가능해요!
                        </p>
                        <p class="desc">아래의 태그로 나만의 여행을 추천 받아볼까요?</p>
                    </div>
                </div>
                <div class="layerpop__contents">
                    <div class="select-tag__wrap">
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary" onclick="openAppDownloadTab()">지금 앱으로 여행 추천 받기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // 뭉클태그 카피 팝업 (웹) -->

        <div id="appDownPopup1" class="layerpop__wrap type-center mobileweb-popup">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <i class="ico ico-logo__big"></i>
                    <p class="ft-xxl">
                        숙소 추천받기는 앱에서만 가능해요!<br>
                        무료로 숙소 추천을 받아볼까요?
                    </p>
                </div>
                <div class="layerpop__footer">
                    <button class="btn-full__black" onclick="openAppDownloadTab()" style="white-space: nowrap;">지금 앱 다운로드</button>
                </div>
            </div>
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
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });

        sessionStorage.setItem('previousPage', window.location.href);

        function openAppDownloadTab() {
            let url = 'https://play.google.com/store/apps/details?id=com.mungkeultrip';

            <?php if (isMacOS() || isIOS()) : ?>
                url = 'https://apps.apple.com/kr/app/%EB%AD%89%ED%81%B4%ED%8A%B8%EB%A6%BD/id6472235149';
            <?php endif; ?>

            window.open(url, '_blank');
        }

        function openMoongcledealPage() {
            location.href = '/moongcledeal/create/01';
        }

        function openMoongcledeal(event) {
            event.preventDefault();

            <?php if ($deviceType !== 'app') : ?>
                fnOpenLayerPop('appDownPopup1');
            <?php else : ?>
                openMoongcledealPage();
            <?php endif; ?>
        }
    </script>

    <script>
        const deviceType = '<?= $deviceType; ?>';
        const mainTagsAll = <?= json_encode($mainTagsAll); ?>;
        const recommendStays = <?= json_encode($recommendStays); ?>;
        const hotStays = <?= json_encode($hotStays); ?>;

        var swiper = new Swiper(".main-tag__slide .swiper-container", {
            initialSlide: 5,
            loop: true,
            grabCursor: true,
            slidesPerView: 3,
            effect: "creative",
            observer: true,
            observeParents: true,
            loopAdditionalSlides: 3,
            lazy: true,
            autoplay: {
                delay: 1500,
            },
            creativeEffect: {
                perspective: true,
                limitProgress: 3,
                prev: {
                    translate: ["-65%", "-10%", 0],
                    origin: "top",
                },
                next: {
                    translate: ["65%", "-10%", 0],
                    origin: "top",
                }
            }
        });

        <?php if (empty($user) || $isGuest) : ?>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.querySelector('#scrollContainer');
                const target = document.querySelector('#bubbleText');
                const travelProposal = document.querySelector('#travelProposal');

                const handleScroll = () => {
                    let scrollHeight, scrollTop, clientHeight;

                    if (container) {
                        // #scrollContainer가 존재할 때
                        scrollHeight = container.scrollHeight;
                        scrollTop = container.scrollTop;
                        clientHeight = container.clientHeight;
                    } else {
                        // #scrollContainer가 존재하지 않을 때
                        scrollHeight = document.documentElement.scrollHeight;
                        scrollTop = window.scrollY || document.documentElement.scrollTop;
                        clientHeight = window.innerHeight;
                    }

                    if (travelProposal) {
                        const travelProposalPosition = travelProposal.getBoundingClientRect().top;

                        if (travelProposalPosition < clientHeight && travelProposalPosition > 0) {
                            target.classList.add('hidden');
                        } else {
                            target.classList.remove('hidden');
                        }
                    }
                };

                if (container) {
                    container.addEventListener('scroll', handleScroll);
                } else {
                    document.addEventListener('scroll', handleScroll);
                }
            });
        <?php endif; ?>

        function getRandomTags(tagsArray, count) {
            const shuffled = [...tagsArray].sort(() => 0.5 - Math.random());
            return shuffled.slice(0, count);
        }

        function updateSwiperContent(tags) {
            const sliderContainer = document.getElementById("mainSlider");
            sliderContainer.innerHTML = "";

            tags.forEach((tag) => {
                const slide = document.createElement("li");
                slide.classList.add("swiper-slide");
                slide.innerHTML = `
                    <a href="#" class="main-tag__btn" data-machine-name="${tag.tag_machine_name}">
                        <p class="img"><img src="/uploads/tags/${tag.tag_machine_name}.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                        <p class="txt tag-text-width">${tag.tag_name}</p>
                    </a>
                `;
                sliderContainer.appendChild(slide);
            });

            swiper.update();
        }

        document.getElementById("tagRefresh").addEventListener("click", function() {
            const randomTags = getRandomTags(mainTagsAll, Math.ceil(mainTagsAll.length / 3));
            updateSwiperContent(randomTags);
        });

        // 최대 선택 가능한 태그 수
        const MAX_SELECTIONS = 3;

        // 선택된 태그를 관리하는 배열 (초기 값은 null)
        let selectedTags = Array(MAX_SELECTIONS).fill(null);

        // 클릭 이벤트 처리
        function handleTagClick(event, tagElement, tagName, tagMachineName) {
            event.preventDefault(); // <a> 태그 기본 동작 막기

            const tagContainer = document.querySelector('.main-tag__select');
            const tagIndex = selectedTags.findIndex(
                (tag) => tag && tag.tagName === tagName && tag.tagMachineName === tagMachineName
            );

            if (tagIndex !== -1) {
                // 이미 선택된 태그: 선택 해제
                selectedTags[tagIndex] = null;

                // 해당 태그 제거 및 뒤에 있는 태그 당기기
                const thumbs = Array.from(tagContainer.querySelectorAll('.main-tag__thumb'));
                for (let i = tagIndex; i < MAX_SELECTIONS - 1; i++) {
                    const nextTag = selectedTags[i + 1];
                    selectedTags[i] = nextTag;

                    const thumb = thumbs[i];
                    if (thumb) {
                        if (nextTag) {
                            thumb.innerHTML = thumbs[i + 1]?.innerHTML || '';
                            thumb.classList.add('active');
                            thumb.setAttribute('data-machine-name', nextTag.tagMachineName);
                        } else {
                            thumb.innerHTML = ''; // 초기화
                            thumb.classList.remove('active');
                            thumb.removeAttribute('data-machine-name');
                        }
                    }
                }

                // 마지막 태그 초기화
                const lastThumb = thumbs[MAX_SELECTIONS - 1];
                if (lastThumb) {
                    lastThumb.innerHTML = ''; // 초기화
                    lastThumb.classList.remove('active');
                    lastThumb.removeAttribute('data-machine-name');
                }
                selectedTags[MAX_SELECTIONS - 1] = null;
            } else if (selectedTags.filter(Boolean).length < MAX_SELECTIONS) {
                // 새로운 태그 선택
                const firstEmptyIndex = selectedTags.indexOf(null);
                selectedTags[firstEmptyIndex] = {
                    tagName,
                    tagMachineName
                };

                const thumb = tagContainer.children[firstEmptyIndex];
                if (thumb) {
                    thumb.innerHTML = `
                        <p class="img">${tagElement.querySelector('.img').innerHTML}</p>
                        <p class="txt">${tagName}</p>
                    `;
                    thumb.classList.add('active');
                    thumb.setAttribute('data-machine-name', tagMachineName); // 머신네임 추가
                }
            }
        }

        // 슬라이더의 클릭 이벤트 위임
        document.querySelector('#mainSlider').addEventListener('click', function(event) {
            const anchor = event.target.closest('a.main-tag__btn');
            if (!anchor) return;

            const tagName = anchor.querySelector('.txt')?.textContent.trim();
            const tagMachineName = anchor.dataset.machineName; // 데이터 속성에서 tagMachineName 추출

            if (tagName && tagMachineName) {
                handleTagClick(event, anchor, tagName, tagMachineName);
            }
        });

        // 선택된 태그의 <a> 태그 클릭 이벤트
        document.querySelectorAll('.main-tag__thumb').forEach(thumb => {
            thumb.addEventListener('click', function(event) {
                const tagName = this.querySelector('.txt')?.textContent.trim();
                const tagMachineName = this.dataset.machineName; // 데이터 속성에서 tagMachineName 추출

                if (tagName && tagMachineName) {
                    handleTagClick(event, this, tagName, tagMachineName);
                }
            });
        });

        // 태그를 팝업에 표시하는 함수
        async function displaySelectedTags(type) {
            const startButton = document.querySelector('#startMoongcledeal');
            startButton.disabled = true;

            let selectTagWrap = '';
            if (type === 'web') {
                selectTagWrap = document.querySelector('#alertWeb .select-tag__wrap');
            } else {
                selectTagWrap = document.querySelector('#alertApp .select-tag__wrap');
            }
            selectTagWrap.innerHTML = ''; // 기존 내용을 초기화

            const encodedTags = await fetchEncodedTags(selectedTags);

            selectedTags.forEach(tag => {
                if (tag) {
                    const tagElement = document.createElement('div');
                    tagElement.classList.add('select-tag');
                    tagElement.innerHTML = `
                        <p class="img"><img src="/uploads/tags/${tag.tagMachineName}.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                        <p class="txt">${tag.tagName}</p>
                    `;
                    selectTagWrap.appendChild(tagElement);
                }
            });

            startButton.setAttribute('data-encoded-tags', encodedTags);
            startButton.disabled = false;
        }

        async function displaySelectedTagsRandom(type) {
            const startButton = document.querySelector('#startMoongcledeal');
            startButton.disabled = true;

            let selectTagWrap = '';
            if (type === 'web') {
                selectTagWrap = document.querySelector('#alertWeb .select-tag__wrap');
            } else {
                selectTagWrap = document.querySelector('#alertApp .select-tag__wrap');
            }
            selectTagWrap.innerHTML = ''; // 기존 내용을 초기화

            const randomTags = mainTagsAll
                .slice()
                .sort(() => Math.random() - 0.5)
                .slice(0, 3);

            const encodedTags = await fetchEncodedTags(randomTags);

            randomTags.forEach(tag => {
                const tagElement = document.createElement('div');
                tagElement.classList.add('select-tag');
                tagElement.innerHTML = `
                    <p class="img"><img src="/uploads/tags/${tag.tag_machine_name}.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                    <p class="txt">${tag.tag_name}</p>
                `;
                selectTagWrap.appendChild(tagElement);
            });

            startButton.setAttribute('data-encoded-tags', encodedTags);
            startButton.disabled = false;
        }

        const openPopButtonWeb = document.querySelector('.fnOpenPop[data-name="alertWeb"]');
        const openPopButtonApp = document.querySelector('.fnOpenPop[data-name="alertApp"]');

        if (openPopButtonWeb) {
            openPopButtonWeb.addEventListener('click', () => {
                const activeTags = selectedTags.filter(tag => tag);

                if (activeTags.length === 0) {
                    displaySelectedTagsRandom('web');
                } else {
                    displaySelectedTags('web');
                }
            });
        }

        if (openPopButtonApp) {
            openPopButtonApp.addEventListener('click', () => {
                const activeTags = selectedTags.filter(tag => tag);

                if (activeTags.length === 0) {
                    displaySelectedTagsRandom('app');
                } else {
                    displaySelectedTags('app');
                }
            });
        }

        async function fetchEncodedTags(tags) {
            const response = await fetch('/api/moongcletag/encode-tags', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    tags
                }),
            });

            const data = await response.json();
            if (data.success) {
                return data.encodedTags; // 서버에서 인코딩된 값을 리턴
            } else {
                console.error('Failed to encode tags:', data.message);
                return '';
            }
        }

        document.querySelector('#startMoongcledeal').addEventListener('click', function() {
            const encodedTags = this.getAttribute('data-encoded-tags');
            if (encodedTags) {
                window.location.href = `/moongcledeal/create/02?selected=${encodedTags}`;
            }
        });

        let currentRecommendTagIndex = 0;
        let currentHotStayIndex = 0;

        // 태그 카피
        async function displayCopyRecommendTags(index) {
            let copyTags = '';

            if (index == 0) { // 5
                copyTags = [{
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }, {
                    tagName: '아이와',
                    tagMachineName: 'with_kids'
                }];
            } else if (index == 1) { // 6
                copyTags = [{
                    tagName: '데이트장소 추천',
                    tagMachineName: 'romantic_spot_recommendation'
                }, {
                    tagName: '커플기념일',
                    tagMachineName: 'couple_anniversary'
                }, {
                    tagName: '로맨틱 분위기',
                    tagMachineName: 'romantic_atmosphere'
                }];
            } else if (index == 2) { // 11
                copyTags = [{
                    tagName: '반려동물 동반가능',
                    tagMachineName: 'pet_friendly'
                }, {
                    tagName: '애견펜션',
                    tagMachineName: 'pet_friendly_pension'
                }];
            } else if (index == 3) { // 4
                copyTags = [{
                    tagName: '여유로운 힐링',
                    tagMachineName: 'relaxing_healing'
                }, {
                    tagName: '숲캉스',
                    tagMachineName: 'forest_staycation'
                }, {
                    tagName: '자연과 함께',
                    tagMachineName: 'with_nature'
                }];
            } else if (index == 4) { // 1
                copyTags = [{
                    tagName: '도심 속 호캉스',
                    tagMachineName: 'urban_hotel_staycation'
                }];
            } else if (index == 5) { // 2
                copyTags = [{
                    tagName: '사계절 온수/미온수풀',
                    tagMachineName: 'year_round_heated_pool'
                }, {
                    tagName: '수영장',
                    tagMachineName: 'swimming_pool'
                }];
            } else if (index == 6) { // 3
                copyTags = [{
                    tagName: 'OTT(넷플릭스 등)',
                    tagMachineName: 'OTT_services_(e.g._Netflix)'
                }];
            } else if (index == 7) { // 7
                copyTags = [{
                    tagName: '해수욕장 주변',
                    tagMachineName: 'near_beach'
                }, {
                    tagName: '바다 전망',
                    tagMachineName: 'sea_view'
                }];
            } else if (index == 8) { // 8
                copyTags = [{
                    tagName: '독채형',
                    tagMachineName: 'private_house_type'
                }, {
                    tagName: '개별 수영장',
                    tagMachineName: 'private_pool_available'
                }];
            } else if (index == 9) { // 9
                copyTags = [{
                    tagName: '사색있는 여행',
                    tagMachineName: 'reflective_travel'
                }, {
                    tagName: '혼자',
                    tagMachineName: 'alone'
                }];
            } else if (index == 10) { // 10
                copyTags = [{
                    tagName: '트리플룸',
                    tagMachineName: 'triple_room'
                }, {
                    tagName: '트윈룸',
                    tagMachineName: 'twin_room'
                }, {
                    tagName: '친구와',
                    tagMachineName: 'with_friends'
                }];
            }




            const startButton = document.querySelector('#startMoongcleTagCopy');
            startButton.disabled = true;

            let selectTagWrap = document.querySelector('#alertCopyApp .select-tag__wrap');

            if (deviceType !== 'app') {
                selectTagWrap = document.querySelector('#alertCopyWeb .select-tag__wrap');
            }

            selectTagWrap.innerHTML = '';

            const encodedTags = await fetchEncodedTags(copyTags);

            copyTags.forEach(tag => {
                if (tag) {
                    const tagElement = document.createElement('div');
                    tagElement.classList.add('select-tag');
                    tagElement.innerHTML = `
                        <p class="img"><img src="/uploads/tags/${tag.tagMachineName}.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                        <p class="txt">${tag.tagName}</p>
                    `;
                    selectTagWrap.appendChild(tagElement);
                }
            });

            startButton.setAttribute('data-encoded-tags', encodedTags);
            startButton.disabled = false;
        }

        document.querySelector('#recommendTagCopy').addEventListener('click', function() {
            displayCopyRecommendTags(currentRecommendTagIndex);
        });

        document.querySelectorAll('.recommend-tag-copy').forEach(function(element) {
            element.addEventListener('click', function() {
                displayCopyRecommendTags(currentRecommendTagIndex); // 함수 호출 (필요한 파라미터를 전달)
            });
        });

        document.querySelector('#startMoongcleTagCopy').addEventListener('click', function() {
            const encodedTags = this.getAttribute('data-encoded-tags');
            if (encodedTags) {
                window.location.href = `/moongcledeal/create/02?selected=${encodedTags}`;
            }
        });

        document.querySelectorAll('.moongcleTagCopy').forEach(function(element) {
            element.addEventListener('click', function(event) {
                const encodedTags = event.target.getAttribute('data-encoded-tags');
                const popId = event.target.getAttribute('data-name');
                const parentElement = event.target.closest('.review-list__con');
                const tagContainer = document.getElementById(popId).querySelector('.select-tag__wrap');

                const startButton = document.querySelector('#startMoongcleTagCopy');
                startButton.disabled = true;

                // 기존 태그 초기화
                tagContainer.innerHTML = '';

                const tagElements = parentElement.querySelectorAll('.review-tag ul li');
                if (tagElements.length > 0) {
                    tagElements.forEach((tagElement) => {
                        const imgSrc = tagElement.querySelector('img').getAttribute('src');
                        const tagText = tagElement.querySelector('span').textContent;

                        // 동적으로 태그 추가
                        const tagHtml = `
                            <div class="select-tag">
                                <p class="img"><img src="${imgSrc}<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                                <p class="txt">${tagText}</p>
                            </div>`;
                        tagContainer.innerHTML += tagHtml;
                    });
                } else {
                    // 태그가 없는 경우 처리
                    tagContainer.innerHTML = '<p class="no-tags">태그가 없습니다.</p>';
                }

                startButton.setAttribute('data-encoded-tags', encodedTags);
                startButton.disabled = false;
            })
        });

        function displayRecommendTagText(index) {
            let tagText = '';

            if (index == 0) { // 5
                tagText = '#아이와 갈만한 곳';
            } else if (index == 1) { // 6
                tagText = '#데이트장소 추천';
            } else if (index == 2) { // 11
                tagText = '#반려동물 동반가능';
            } else if (index == 3) { // 4
                tagText = '#여유로운 힐링';
            } else if (index == 4) { // 1
                tagText = '#도심 속 호캉스';
            } else if (index == 5) { // 2
                tagText = '#사계절 온수/미온수풀';
            } else if (index == 6) { // 3
                tagText = '#OTT(넷플릭스 등)';
            } else if (index == 7) { // 7
                tagText = '#해수욕장 주변';
            } else if (index == 8) { // 8
                tagText = '#독채형';
            } else if (index == 9) { // 9
                tagText = '#사색있는 여행';
            } else if (index == 10) { // 10
                tagText = '#트리플룸';
            }

            return tagText;
        }

        function gotoMoongcleoffer(event, partnerIdx) {
            event.preventDefault();

            const queryParams = new URLSearchParams({
                startDate: '',
                endDate: '',
                adult: 0,
                child: 0,
                infant: 0,
                childAge: JSON.stringify({}),
                infantMonth: JSON.stringify({}),
            });

            showLoader();
            window.location.href = `/moongcleoffer/product/${partnerIdx}?${queryParams.toString()}`;
        }

        function gotoMoongcledeal(event, moongcledealIdx) {
            event.preventDefault();

            showLoader();
            window.location.href = `/moongcledeal/detail/${moongcledealIdx}`;
        }

        document.querySelectorAll('.recommend-stay').forEach((element, index) => {
            element.addEventListener('click', (event) => {
                const startButton = document.querySelector('#startMoongcleTagCopy');
                startButton.disabled = true;

                let selectTagWrap = document.querySelector('#alertCopyApp .select-tag__wrap');

                if (deviceType !== 'app') {
                    selectTagWrap = document.querySelector('#alertCopyWeb .select-tag__wrap');
                }

                selectTagWrap.innerHTML = '';

                recommendStays[index].tags.forEach(tag => {
                    if (tag) {
                        const tagElement = document.createElement('div');
                        tagElement.classList.add('select-tag');
                        tagElement.innerHTML = `
                            <p class="img"><img src="/uploads/tags/${tag.tag_machine_name}.png<?= '?v=' . $_ENV['VERSION']; ?>" alt=""></p>
                            <p class="txt">${tag.tag_name}</p>
                        `;
                        selectTagWrap.appendChild(tagElement);
                    }
                });

                startButton.setAttribute('data-encoded-tags', recommendStays[index].encoded_tags);
                startButton.disabled = false;
            });
        });

        const recommendTagSplide = new Splide('.splide__default_custom', {
            arrows: false,
            type: 'loop',
            perPage: 1,
        }).mount();

        const hotStaySplide = new Splide('.splide__default_custom2', {
            arrows: false,
            type: 'loop',
            perPage: 1,
        }).mount();

        // 슬라이더가 이동할 때마다 활성 슬라이드 확인
        recommendTagSplide.on('moved', function() {
            currentRecommendTagIndex = recommendTagSplide.index;

            const tagTextDom = document.getElementById('recommendationTagText');
            tagTextDom.innerText = displayRecommendTagText(currentRecommendTagIndex);
        });

        hotStaySplide.on('moved', function() {
            currentHotStayIndex = hotStaySplide.index;
        });

        document.getElementById('gotoMainMoongcleoffer').addEventListener('click', function() {
            location.href = '/stay/detail/' + hotStays[currentHotStayIndex].partner_idx;
        });

        // 타이핑 효과
        TypeHangul.type('.typing-ani', {
            intervalType: 60
        });
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>