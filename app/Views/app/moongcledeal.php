<!DOCTYPE html>
<html lang="ko">

<?php

$user = $data['user'];
$isGuest = $data['isGuest'];
$deviceType = $data['deviceType'];

$pendingMoongcledeals = $data['pendingMoongcledeal'];
$inProgressMoongcledeals = $data['inProgressMoongcledeal'];
$stopMoongcledeal = $data['stopMoongcledeal'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];
$moongcledeal = $data['moongcledeal'];
$moongcleoffers = $data['moongcleoffers'];
$moongcleofferFavorites = $data['moongcleofferFavorites'];
$moongcleMatches = $data['moongcleMatches'];
?>

<?php
$currentIdx = $_GET['moongcledealIdx'] ?? null;
$currentDealCount = count($moongcleMatches);
$currentStatus = null;

if ($currentIdx) {
    foreach ($inProgressMoongcledeals as $deal) {
        if ((string)$deal->moongcledeal_idx === (string)$currentIdx) {
            // 현재 뭉클딜의 status
            $currentStatus = $deal->status;
            break;
        }
    }
}

$matchingCompleteNoResult = false;
$matchingCompleteWithResult = false;
$matchingInProgress = false;
$moongcledealMatchInfo = $data['moongcledealMatchInfo'];
if(in_array($moongcledealMatchInfo->status, ['in_progress','matching'])) {
    //매칭진행중
    $matchingInProgress = true;
} else {
    //$moongcledealMatchInfo->status == matched
    if($moongcledealMatchInfo->status_view == 'matched') {
        //매칭완료
        if($moongcledealMatchInfo->match_count > 0 && $moongcledealMatchInfo->match_count != $moongcledealMatchInfo->match_count_fcm) {
            // fcm 미전송
            //매칭중으로 노출하기
            $matchingInProgress = true;
        } else if($moongcledealMatchInfo->match_count > 0 && $moongcledealMatchInfo->match_count == $moongcledealMatchInfo->match_count_fcm) {
            // fcm 전송
            //매칭중으로 노출하기
            //$matchingInProgress = true;
            $matchingCompleteWithResult = true;
        } else {
            // 0건 결과 없음
            //결과 없음
            $matchingCompleteNoResult = true;
        }
    } else if($moongcledealMatchInfo->status_view == 'completed') {
        //매칭완료, 결과 있음 - fcm 완료
        $matchingCompleteWithResult = true;
    } else {}
}

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
                <h2 class="header-tit__left">나를 위한 추천</h2>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="#" onclick="openMoongcledeal()" class="btn-txt__small" style="font-weight: 600; color: #714cdc;">신규 등록</a>
                    <button type="button" class="btn-create" onclick="openMoongcledeal()"><span class="blind">신규 등록</span></button>
                </div>
            </div>
        </header>

        <div class="container__wrap mkcle-home__wrap">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/refresh.php"; ?>

            <div class="layout__wrap" style="padding: 2.4rem 0;">

                <div class="refresh__wrap" style="padding-bottom: 7.4rem;">

                    <?php if ($isGuest && $deviceType !== 'app') : ?>

                    <!-- 로그인 안했을 때 -->
                    <div class="no-mymkcle__wrap">
                        <div class="tit__wrap align__center">
                            <p class="ft-xl">
                                좋은 숙소 대신 찾아드릴게요
                            </p>
                        </div>
                        <div class="splide splide__default <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup2' : '' ?>">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <li class="splide__slide"><img src="/assets/app/images/main/01.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/02.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/03.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/04.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/05.gif" alt=""></li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn__wrap">
                            <button type="button" class="btn-full__primary <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup1' : '' ?>">좋은 숙소 찾아줘</button>

                        </div>
                    </div>

                    <?php elseif (empty($inProgressMoongcledeals) || (is_object($inProgressMoongcledeals) && $inProgressMoongcledeals->count() === 0)) : ?>
                    <!-- 뭉클딜 없을 때 -->
                    <div class="no-mymkcle__wrap">
                        <div class="tit__wrap align__center">
                            <p class="ft-xl">
                                좋은 숙소 대신 찾아드릴게요
                            </p>
                        </div>
                        <div class="splide splide__default <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup1' : '' ?>">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <li class="splide__slide"><img src="/assets/app/images/main/01.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/02.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/03.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/04.gif" alt=""></li>
                                    <li class="splide__slide"><img src="/assets/app/images/main/05.gif" alt=""></li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn__wrap">
                            <button type="button" class="btn-full__primary <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup1' : '' ?>">좋은 숙소 찾아줘</button>
                        </div>
                    </div>

                    <?php else : ?>

                    <!-- 뭉클딜 있을 때 -->
                    <div class="tab__wrap tab-round__wrap moongcledeal_wrap">
                        <div class="overflow-x-visible">
                            <?php
                                $currentIdx = $_GET['moongcledealIdx'] ?? null;
                            ?>
                            <ul class="tab__inner capsule-btns moongcledeal_cards padding-x-20">
                                <?php foreach ($inProgressMoongcledeals as $inProgressMoongcledeal) : ?>
                                    <?php
                                        $isActive = ($currentIdx == $inProgressMoongcledeal->moongcledeal_idx) ? 'active' : '';
                                    ?>
                                    <li class="tab-round__con <?= $isActive; ?>">
                                        <a href="/moongcledeals?moongcledealIdx=<?= $inProgressMoongcledeal->moongcledeal_idx; ?>">
                                            <?php if ($inProgressMoongcledeal->unread_deal_count !== 0) : ?>
                                            <p class="new_num">
                                                NEW
                                            </p>
                                            <?php endif; ?>
                                            
                                            <div class="tit_wrap">
                                                <div class="tit_box view">
                                                    <h3>
                                                        <?= $inProgressMoongcledeal->title === "" || $inProgressMoongcledeal->title === null ? '새로운 여행' : $inProgressMoongcledeal->title; ?>
                                                    </h3>
                                                    <?php if ($isActive) : ?>
                                                        <i class="fa-solid fa-pen edit-btn"></i>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="tit_box edit" style="display: none;">
                                                    <input type="text" class="moongcledeal_name_input" value="<?= $inProgressMoongcledeal->title; ?>" placeholder="새로운 여행">
                                                    <i class="fa-solid fa-check fnOpenPop" data-name="alert"></i>
                                                </div>

                                                <div class="date_wrap">
                                                    <p class="date">
                                                        <?php 
                                                        $moongcledeal_period = $inProgressMoongcledeal['selected']['days'] ?? [];
                                                        echo isset($moongcledeal_period[0]['dates']) && $moongcledeal_period[0]['dates'] !== null 
                                                            ? $moongcledeal_period[0]['dates'] 
                                                            : '미정'; 
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>  
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="tab-contents__wrap">

                            <div class="tab-contents active">
                                <!-- 현재 진행 상황 -->
                                <div class="section_layout">
                                    <div class="tit_wrap" style="display: flex; align-items: center; gap: 1rem;">
                                        <h4>현재 진행 상황</h4>
                                        <?php if ($matchingInProgress) : ?>
                                            <button type="button" class="btn" onclick="window.location.reload(); showLoader();">
                                                <i class="fa-solid fa-arrow-rotate-right"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="padding-x-20">
                                        
                                        <!-- 매칭 완료, 결과 없음 -->
                                        <?php if ($matchingCompleteNoResult) : ?>
                                            <div class="no_completion" style=" display: flex; align-items: center; justify-content: center;">
                                                <div class="nodata__con" style="font-size: 1.4rem;">
                                                    매칭된 숙소가 없습니다.<br>
                                                    다른 날짜 또는 조건으로 추천을 받아보세요.

                                                    <div class="button_wrap" style="display: flex; align-items: center; justify-content: center;  gap: 1.2rem;">
                                                        <button type="button" class="btn" style="font-size: 1.0rem; box-sizing: border-box; padding: 0.8rem 2.2rem; border-radius: 0.8rem; background: #dddddd; color: #30333eff; display: block; margin: 3rem 0 0;">
                                                            <a href="#none" class="delete_btn fnOpenPop" data-name="stopAlert">
                                                                삭제하기
                                                            </a>
                                                        </button>
                                                        <button type="button" class="btn" style="font-size: 1.0rem; box-sizing: border-box; padding: 0.8rem 2.2rem; border-radius: 0.8rem; background: #714cdc; color: #ffffff; display: block; margin: 3rem 0 0;">
                                                            <a href="#none" onclick="openMoongcledeal()" >
                                                                다시 등록하기
                                                            </a>
                                                        </button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        <!-- 매칭 진행 중 -->
                                        <?php elseif ($matchingInProgress) : ?>
                                            <div class="no_completion">
                                                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/moongcledeal-loader.php"; ?>
                                            </div>

                                        <!-- 매칭 완료, 결과 있음 -->
                                        <?php elseif ($matchingCompleteWithResult) : ?>
                                        <div class="real-mkc completion">    
                                            <div class="completion txt_box">
                                                🎁
                                                <div class="txt_wrap">
                                                    <p class="tit">추천 완료 !</p>
                                                    <p class="txt"><b><?= count($moongcleoffers); ?>개</b>의 숙소 제안이 도착했어요</p>
                                                    <p class="color">일부 제안은 조기 마감될 수 있으니 지금 확인해 보세요</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 스켈레톤 - 현재 진행 상황 -->
                                        <div class="product-list__con skeleton-ing">
                                            <div class="completion txt_box skeleton-txt">
                                                <div class="txt_wrap"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 선택한 조건 -->
                                <div class="real-select completion">
                                    <div class="section_layout">
                                        <div class="tit_wrap d-flex">
                                            <h4>선택한 조건</h4>
                                            <!-- <button type="button" class="edit_btn" onclick="location.href='/moongcledeal/edit/01/<?= $moongcledeal->moongcledeal_idx; ?>'">
                                                <i class="fa-solid fa-pen"></i> 수정하기
                                            </button> -->
                                        </div>
                                        <div class="moongcledeal_select_wrap">
                                            <div>
                                                <div class="info_box">
                                                    <div class="select_box">
                                                        <div class="ico_box">
                                                            <img src="/assets/app/images/common/ico_person_mint.svg" alt="아이콘" width="14" height="14">
                                                        </div>
                                                        <div class="info">
                                                            <p>
                                                                <?php 
                                                                    $moongcledeal_personnel = $moongcledeal['selected']['personnel'] ?? null;
                                                                    $moongcledeal_companion = $moongcledeal['selected']['companion'] ?? null;
                                                                    $moongcledeal_pet_size = $moongcledeal['selected']['pet']['size'] ?? null;
                                                                    $moongcledeal_pet_weight = $moongcledeal['selected']['pet']['weight'] ?? null;
                                                                    $moongcledeal_pet_count = $moongcledeal['selected']['pet']['count'] ?? null;

                                                                    $infoParts = [];

                                                                    if (!empty($moongcledeal_personnel)) {
                                                                        $infoParts[] = "{$moongcledeal_personnel}명";
                                                                    }

                                                                    if (!empty($moongcledeal_companion['tag_name'])) {
                                                                        $infoParts[] = $moongcledeal_companion['tag_name'];
                                                                    } else {
                                                                        $infoParts[] = '미정';
                                                                    }

                                                                    if (!empty($moongcledeal_pet_size['tag_name'])) {
                                                                        $infoParts[] = $moongcledeal_pet_size['tag_name'];
                                                                    }

                                                                    if (!empty($moongcledeal_pet_weight['tag_name'])) {
                                                                        $infoParts[] = $moongcledeal_pet_weight['tag_name'];
                                                                    }

                                                                    if (!empty($moongcledeal_pet_count['tag_name'])) {
                                                                        $infoParts[] = $moongcledeal_pet_count['tag_name'];
                                                                    }

                                                                    echo implode(', ', $infoParts);
                                                                ?>
                                                            </p>
                                                            <span>인원</span>
                                                        </div>
                                                    </div>
                                                    <div class="select_box" style="margin: 1.2rem 0;">
                                                        <div class="ico_box">
                                                            <img src="/assets/app/images/common/ico_date_mint.svg" alt="아이콘" width="14" height="14">
                                                        </div>
                                                        <div class="info">
                                                            <p>
                                                                <?php 
                                                                    $moongcledeal_days = $moongcledeal['selected']['days'] ?? null;
                                                                    $dates = (is_array($moongcledeal_days) && isset($moongcledeal_days[0]['dates'])) 
                                                                        ? $moongcledeal_days[0]['dates'] 
                                                                        : "미정";
                                                                ?>
                                                                <?= $dates; ?>
                                                            </p>
                                                            <span>일정</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- 숨기기 -->
                                                    <div class="hidden-content" style="display: none;">
                                                        <div class="select_box">
                                                            <div class="ico_box">
                                                                <img src="/assets/app/images/common/ico_location_mint.svg" alt="아이콘" width="14" height="14">
                                                            </div>
                                                            <div class="info">
                                                                <p>
                                                                    <?php 
                                                                    $moongcledeal_city = $moongcledeal['selected']['city'] ?? null;
                                                                    $city = (is_array($moongcledeal_city) && isset($moongcledeal_city['tag_name'])) 
                                                                        ? $moongcledeal_city['tag_name'] 
                                                                        : "미정"; 
                                                                    ?>
                                                                    <?= $city; ?>
                                                                </p>
                                                                <span>도시</span>
                                                            </div>
                                                        </div>
                                                        <div class="select_tag">
                                                            <div class="tit">
                                                                <p>선호 조건</p>
                                                                <span style="
                                                                        margin-top: 0.5rem;
                                                                        display: flex;
                                                                        font-size: 1.2rem;
                                                                        color: #acacac;
                                                                        box-sizing: border-box;
                                                                        padding: 0 0.3rem;
                                                                        align-items: flex-start;
                                                                        gap: 0.5rem;
                                                                ">
                                                                    <i class="fa-solid fa-circle-info" style="margin-top: 0.4rem;"></i> 선호 조건은 일부만 반영될 수 있어요.<br>예약 전 포함 사항을 꼭 확인해 주세요.
                                                                </span>
                                                            </div>
                                                            <div class="select__wrap col-4">
                                                                <ul>
                                                                    <?php 
                                                                        $moongcledeal_taste = $moongcledeal['selected']['taste'] ?? null;
                                                                    ?>
                                                                    <?php foreach ($moongcledeal_taste as $taste) : ?>
                                                                        <li data-taste-machine-name="<?= $taste['machine_name'] ?>" data-section="<?= $taste['section'] ?>" data-tag-name="<?= $taste['tag_name'] ?>">
                                                                            <a href="#none"><?= $taste['tag_name'] ?></a>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="review-txt btn_wrap">
                                                    <a class="btn-more">더보기</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 스켈레톤 - 선택한 조건 -->
                                <div class="product-list__con skeleton-select">
                                    <div class="tit_wrap skeleton-tit"></div>
                                    <div class="padding-x-20">        
                                        <div class="completion txt_box skeleton-txt" style="height: 16rem;">
                                            <div class="txt_wrap"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 추천 숙소 -->
                                <div class="real-reco completion">
                                    <div class="section_layout">
                                    
                                        <div class="tit_wrap">
                                            <h4>추천 숙소 (<?= count($moongcleoffers); ?>)</h4>
                                        </div>
                                        <div class="padding-x-20" style="margin-top: 2rem;">
                                            <div class="check-list__wrap checkbox__wrap">
                                            <?php foreach ($moongcleoffers as $key => $moongcleoffer) : ?>
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
                                                <div class="check-list__con">
                                                    <div class="check-list__tit">
                                                        <div class="checkbox">
                                                            <label for="checkbox1">
                                                                <span class="ft-s ft-bold" style="padding: 0 1rem 0 1rem;">
                                                                    <?= $key + 1; ?>번째 추천
                                                                </span>
                                                            </label>
                                                            <div class="badge badge__lavender"><?= $category; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="box-gray__wrap">
                                                        <div class="thumb__wrap">
                                                            <p class="thumb__img large" style="width: 10rem; height: 10rem;"><img src="<?= $moongcleoffer->image_normal_path; ?>" alt="" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>, <?=$moongcleoffer->stay_moongcleoffer_idx; ?>)"></p>
                                                            <div class="thumb__con">
                                                                <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                    <div class="thumb-badge">최소 <?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>%~ 할인</div>
                                                                <?php endif; ?>
                                                                <button type="button" class="btn-product__like type-black <?= in_array($moongcleoffer->moongcleoffer_idx, $moongcleofferFavorites) ? 'active' : '' ?>" data-user-idx="<?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>" data-partner-idx="<?= !empty($moongcleoffer->partner_idx) ? $moongcleoffer->partner_idx : 0 ?>" data-moongcleoffer-idx="<?= !empty($moongcleoffer->moongcleoffer_idx) ? $moongcleoffer->moongcleoffer_idx : 0 ?>" style="top: 2.2rem; right: 2rem;"><span class="blind">찜하기</span></button>
                                                                <p class="detail-sub">
                                                                    <?php if (!empty($moongcleoffer->partner_address1)) : ?>
                                                                        <span><?= $moongcleoffer->partner_address1; ?></span>
                                                                    <?php endif; ?>
                                                                    <?php $stayTypes = explode(':-:', $moongcleoffer->types); ?>
                                                                    <?php if (!empty($stayTypes)) : ?>
                                                                        <span>
                                                                            <?php foreach ($stayTypes as $tagKey => $stayType) : ?>
                                                                                <?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
                                                                            <?php endforeach; ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </p>
                                                                <p class="detail-name" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>, <?=$moongcleoffer->stay_moongcleoffer_idx; ?> )"><?= $moongcleoffer->partner_name; ?></p>
                                                            </div>
                                                            <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                <div class="thumb__price" style="padding: 0.6rem 0;">
                                                                    <span style="color: #714cdc; font-size: 1.1rem; text-align: right; margin-bottom: 0.5rem; display: block;">* 평일 최저가 기준</span>
                                                                    <div>
                                                                        <p class="sale-percent"><?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>%</p>
                                                                        <p class="default-price"><?= number_format($moongcleoffer->basic_price); ?>원</p>
                                                                        <p class="sale-price"><?= number_format($moongcleoffer->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                                    </div>
                                                                    <!-- <p class="ft-xxs">취소 불가 상품</p> -->
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="thumb__gift" style=" margin-top: 1.2rem;">
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
                                                        <div class="btn__wrap">
                                                            <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                                <?php if ($moongcleoffer->room_status == 'enabled') : ?>
                                                                    <button type="button" class="btn-md__black" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>, <?=$moongcleoffer->stay_moongcleoffer_idx; ?>)">자세히 보기</button>
                                                                <?php else : ?>
                                                                    <button type="button" class="btn-md__black disabled" disabled>아쉽게도 마감되었어요.</button>
                                                                <?php endif; ?>
                                                            <?php else : ?>
                                                                <button type="button" class="btn-md__black disabled" disabled>아쉽게도 마감되었어요.</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div class="btn_wrap padding-x-20" style="margin-top: 2rem; padding-bottom: 9rem;">
                                            <button type="button" class="btn-full__primary fnOpenPop" data-name="moreRecoAlert">추가 추천 받기</button>
                                            <p class="delete_text">추천 리스트를 삭제하시겠습니까? <a href="#none" class="delete_btn fnOpenPop" data-name="stopAlert">삭제하기</a></p>
                                        </div>

                                        <!-- <div class="padding-x-20" style="margin-top: 5rem;">
                                            <div id="recommendationBox" class="recommendation__box" style="background-size: 112% auto;">
                                                <div>
                                                    <p class="text">혹시 찾으시는 숙소가 없나요?</p>
                                                    <span style="font-size: 1.2rem; color: #696D70; margin-top: 0.5rem; display: inline-block;">뭉클이 맘 편하게 대신 찾아드릴게요</span>
                                                    <button type="button" id="gettingRecommendation" class="gettingRecommendation" onclick="location.href='https://tally.so/r/n0lXjA'">
                                                        <i class="fa-solid fa-comment"></i> &nbsp;뭉클 담당자에게 직접 추천 받기
                                                    </button>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div> 

                                <!-- 스켈레톤 - 추천 숙소 -->
                                <div class="product-list__con skeleton-reco">
                                    <div class="tit_wrap skeleton-tit"></div>
                                    <div class="padding-x-20">        
                                        <div class="completion txt_box skeleton-txt" style="height: 20rem; margin-bottom: 1.2rem;"></div>
                                        <div class="completion txt_box skeleton-txt" style="height: 20rem; margin-bottom: 1.2rem;"></div>
                                        <div class="completion txt_box skeleton-txt" style="height: 20rem; margin-bottom: 1.2rem;"></div>
                                        <div class="completion txt_box skeleton-txt" style="height: 20rem;"></div>
                                    </div>
                                </div>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

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

        <!-- 하단 버튼 영역 -->
        <div class="bottom-fixed__wrap" style="background: none; bottom: 9rem; opacity: 0.84;">
            <div class="btn__wrap">
                <button type="button" class="btn-full__primary" onclick="openMoongcledeal()">신규 등록</button>
            </div>
        </div>
        <!-- //하단 버튼 영역 -->

        <div id="alert" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">뭉클딜 이름을 수정할까요?</p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__secondary fnClosePop moongcledeal_name_close">아니요</button>
                        <button class="btn-full__primary mooongcledeal_name_edit fnClosePop">확인</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 알럿 팝업 -->
        <div id="stopAlert" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">추천 리스트를 삭제하시겠습니까?</p>
                        <p class="desc">
                            삭제하실 경우 해당 추천을 더 이상 받으실 수 없습니다.<br>
                            게속 진행하시겠습니까?
                        </p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__secondary moongcledealStop">삭제하기</button>
                        <button class="btn-full__primary fnClosePop moongcledealStopCancel">취소</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 알럿 팝업 -->
        <div id="moreRecoAlert" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">추가 추천을 받으시겠습니까?</p>
                        <p class="desc">
                            선택하신 조건으로 추천을 더 받을 수 있습니다.<br>
                            ( 약 1 ~ 2분 소요 )
                        </p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__secondary fnClosePop">취소</button>
                        <button id="moreRecommendation" class="btn-full__primary">받기</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 토스트팝업 -->
        <div id="toastPopupLike" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p></p>
            </div>
        </div>
        <!-- //토스트팝업 -->
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
        window.addEventListener('load', () => {
            handleContentSkeletons('.real-mkc', '.skeleton-ing', 500);
            handleContentSkeletons('.real-select', '.skeleton-select', 500);
            handleContentSkeletons('.real-reco', '.skeleton-reco', 500);
        });

        function handleContentSkeletons(trackClass, loaderClass, minLoadingTime = 0) {
            const tracks = document.querySelectorAll(trackClass);
            const loaders = document.querySelectorAll(loaderClass);
            const startTime = performance.now(); 

            tracks.forEach((track, index) => {
                const loader = loaders[index];

                const tryShow = () => {
                    const now = performance.now();
                    const elapsed = now - startTime;

                    const waitTime = Math.max(minLoadingTime - elapsed, 0);

                    setTimeout(() => {
                        showRealContent(track, loader);
                    }, waitTime);
                };

                const isReady = track.textContent.trim().length > 0 || track.children.length > 0;

                if (isReady) {
                    tryShow();
                } else {
                    const fallback = setInterval(() => {
                        const isNowReady = track.textContent.trim().length > 0 || track.children.length > 0;
                        if (isNowReady) {
                            clearInterval(fallback);
                            tryShow();
                        }
                    }, 200);
                }
            });
        }

        function showRealContent(track, loader) {
            if (loader) {
                loader.classList.add('fade-out');
                setTimeout(() => {
                    loader.remove();
                    if (track) {
                        track.classList.add('show');
                    }
                }, 100);
            } else {
                track.classList.add('show');
            }
        }
    </script>

    <script>
        showLoader();

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

            location.href = '/moongcledeal/create/02';

        }

        function openMoongcledeal() {
            <?php if ($isGuest && $deviceType !== 'app') : ?>
                fnOpenLayerPop('appDownPopup1');
            <?php else : ?>
                openMoongcledealPage();
            <?php endif; ?>
        }

        function gotoMoongcleDeatilPage(event, link) {
            showLoader();

            event.preventDefault();

            window.location.href = link;
        }
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // 카드 focus 시 해당 카드로 스크롤
            const activeTab = document.querySelector('.tab-round__con.active');
            if (activeTab) {
                activeTab.scrollIntoView({
                    inline: 'start',
                    behavior: 'auto',
                    block: 'nearest'
                });
            }
        });
    </script>

    <script>
        // 뭉클딜 이름 수정
        async function editMoongcledealTitle (inputElement) {
            const moongcledealIdx = <?= isset($moongcledeal->moongcledeal_idx) ? (int)$moongcledeal->moongcledeal_idx : 'null' ?>;

            if (!moongcledealIdx) {
                return;
            }

            const formData = {
                moongcledealIdx: moongcledealIdx,
                title: inputElement.value
            }
            
            try {
                const response = await fetch('/api/moongcletag/change-title', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                if (!response.ok) {
                    throw new Error('API 요청 실패: ' + response.statusText);
                }

                const result = await response.json();

                if (result) {
                    window.location.reload();
                } else {
                    console.error('응답 없음');
                }
            } catch (error) {
                console.error('API 요청 중 오류:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // 아직 추천 안받았을 때
            // if (<?= $currentDealCount; ?> === 0) {
            //     setInterval(() => {
            //         location.replace(window.location.href);
            //     }, 10000);
            // }

            // 뭉클딜 이름 수정
            const tabItems = document.querySelectorAll('.tab-round__con');

            tabItems.forEach(item => {
                const viewBox = item.querySelector('.tit_box.view');
                const editBox = item.querySelector('.tit_box.edit');
                const editBtn = item.querySelector('.edit-btn');
                const checkBtn = item.querySelector('.fa-check');
                const saveBtn = document.querySelector('.mooongcledeal_name_edit');
                const closeBtn = document.querySelector('.moongcledeal_name_close');

                if (!viewBox || !editBox || !editBtn || !saveBtn) return;

                const input = item.querySelector('.moongcledeal_name_input');
                const title = viewBox.querySelector('h3');

                editBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const input = editBox.querySelector('input.moongcledeal_name_input');

                    viewBox.style.display = 'none';
                    editBox.style.display = 'flex';
                    input.focus();
                });

                checkBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                });

                input.addEventListener('click', function (e) {
                    e.preventDefault();
                });

                saveBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    editMoongcledealTitle(input);
                });

                // 저장 버튼 이벤트와 엔터 keydown 이벤트를 함께 처리
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        fnOpenLayerPop('alert');
                    }
                });

                closeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    editBox.style.display = 'none';
                    viewBox.style.display = 'flex';

                    // input vale 초기화
                    input.value = title.textContent.trim();
                });

                // 외부 클릭 시 편집 종료
                document.addEventListener('click', function (e) {
                    if (editBox.style.display === 'flex' && !item.contains(e.target)) {
                        editBox.style.display = 'none';
                        viewBox.style.display = 'flex';

                        // input vale 초기화
                        input.value = title.textContent.trim();
                    }
                });
            });

            // 선택한 조건 펼치기
            const btnMore = document.querySelector('.btn-more');
            const hiddenContent = document.querySelector('.hidden-content');

            if (!btnMore || !hiddenContent) return;

            btnMore.addEventListener('click', function (e) {
                e.preventDefault();

                if (hiddenContent.style.display === 'none' || hiddenContent.style.display === '') {
                    hiddenContent.style.display = 'block';
                    btnMore.textContent = '접기';
                } else {
                    hiddenContent.style.display = 'none';
                    btnMore.textContent = '더보기';
                }
            });

            // 추가 추천 받기
            const moreRecommendation = document.getElementById("moreRecommendation");

            moreRecommendation.addEventListener("click", async function() {
                try {
                    let url = '/api/moongcletag/more-open';
                    let moongcledealIdx = <?= json_encode(!empty($moongcledeal->moongcledeal_idx) ? $moongcledeal->moongcledeal_idx : null) ?>;

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            moongcledealIdx: moongcledealIdx
                        })
                    });

                    // 응답 처리
                    if (!response.ok) {
                        throw new Error('API 요청 실패: ' + response.statusText);
                    }

                    const result = await response.json();

                    if (result.success) {
                        window.location.href = '/moongcledeals?moongcledealIdx=' + moongcledealIdx;
                    } else {
                        console.error('idx 값이 응답에 포함되지 않았습니다.');
                    }
                } catch (error) {
                    console.error('API 요청 중 오류 발생:', error);
                    throw error;
                }
            });            
        });

        // 삭제하기
        document.querySelectorAll(".moongcledealStop").forEach((btn) => {
            
            btn.addEventListener("click", async function () {
                
                try {

                    let url = '/api/moongcledeal/stop';
                    let moongcledealIdx = <?= json_encode(!empty($moongcledeal->moongcledeal_idx) ? $moongcledeal->moongcledeal_idx : null) ?>;

                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                        "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                        moongcledealIdx: moongcledealIdx,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error("API 요청 실패: " + response.statusText);
                    }

                    const result = await response.json();

                    if (result.success) {
                        showLoader();
                        window.location.href = "/moongcledeals";
                    } else {
                        console.error("idx 값이 응답에 포함되지 않았습니다.");
                    }
                } catch (error) {
                    console.error("API 요청 중 오류 발생:", error);
                    throw error;
                }
            });
        });
    </script>

    <script>
        let moongcledeal = <?= json_encode($moongcledeal); ?>;
        
        function gotoMoongcleoffer(event, partnerIdx, stayMoongcleofferIdx) {
            event.preventDefault();

            let queryParams = new URLSearchParams({
                startDate: '',
                endDate: '',
                adult: 0,
                child: 0,
                infant: 0,
                childAge: JSON.stringify({}),
                infantMonth: JSON.stringify({}),
            });

            if (moongcledeal.selected &&
                Array.isArray(moongcledeal.selected['days']) &&
                moongcledeal.selected['days'].length > 0 &&
                moongcledeal.selected['days'][0].dates) {

                let selectedDate = moongcledeal.selected['days'][0].dates.split('~');
                let personnel = 0;

                if (moongcledeal.selected['personnel']) {
                    personnel = moongcledeal.selected['personnel'];
                }

                queryParams = new URLSearchParams({
                    startDate: selectedDate[0],
                    endDate: selectedDate[1],
                    adult: personnel,
                    child: 0,
                    infant: 0,
                    childAge: JSON.stringify({}),
                    infantMonth: JSON.stringify({}),
                });
            }

            showLoader();
            
            //기본 URL
            let url = `/moongcleoffer/product/${partnerIdx}?${queryParams.toString()}`;

            // 2. stayMoongcleofferIdx 값이 있으면(null, 0, undefined 등이 아니면)
            if (stayMoongcleofferIdx) {
                // URL 끝에 해시(#)와 id 값을 추가한다.
                url += `#${stayMoongcleofferIdx}`;
            }

            window.location.href = url;
        }

        document.addEventListener('click', function(event) {
            const target = event.target.closest('.btn-product__like');
            if (target) {
                const userIdx = target.dataset.userIdx;
                const partnerIdx = target.dataset.partnerIdx;
                const moongcleofferIdx = target.dataset.moongcleofferIdx;

                toggleFavorite(userIdx, partnerIdx, moongcleofferIdx, 'moongcleoffer');
            }
        });
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>