<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

$user = $data['user'];
$isGuest = $data['isGuest'];
$reviews = $data['reviews'];
$popularities = $data['popularity'];
$around_beaches = $data['groupedPartnerData']['around_beach'];
$character_rooms = $data['groupedPartnerData']['character_room'];
$cost_effectives = $data['groupedPartnerData']['cost_effective'];
$glampings = $data['groupedPartnerData']['glamping'];
$hanok_experiences = $data['groupedPartnerData']['hanok_experience'];
$having_large_rooms = $data['groupedPartnerData']['having_large_room'];
$kids_pensions = $data['groupedPartnerData']['kids_pension'];
$with_children = $data['groupedPartnerData']['with_child'];
$with_natures = $data['groupedPartnerData']['with_nature'];
$with_swimmings = $data['groupedPartnerData']['with_swimming'];

$companionTags = $data['companionTags'];
$petDetailTags = $data['petDetailTags'];
$cityTags = $data['cityTags'];
$overseasTags = $data['overseasTags'];
$travelTasteTags = $data['travelTasteTags'];
$eventTags = $data['eventTags'];
$stayTasteTags = $data['stayTasteTags'];
$stayTypeTags = $data['stayTypeTags'];
$petFacilityTags = $data['petFacilityTags'];

$selectedDays = [];
$selectedPersonnel = null;

function generateMonthSelection($startDate, $monthsToGenerate)
{
    $currentDate = new DateTime($startDate);
    $html = '<div class="select__wrap col-3">';

    for ($i = 0; $i < $monthsToGenerate; $i++) {
        $year = $currentDate->format('Y');
        $month = $currentDate->format('m');
        $monthName = $currentDate->format('n월');

        // 연도별 제목 생성
        if ($i === 0 || $currentDate->format('m') === '01') {
            $html .= "<p class='title'>{$year}년</p><ul>";
        }

        // 각 월의 리스트 항목 생성
        $id = "month-{$year}{$month}";
        $html .= "<li id='{$id}'><a href='#none'>{$monthName}</a></li>";

        // 다음 달로 이동
        $currentDate->modify('+1 month');

        // 연도 구분을 위해 ul 닫기3
        if ($currentDate->format('m') === '01' || $i === $monthsToGenerate - 1) {
            $html .= '</ul>';
        }
    }

    $html .= '</div>';
    return $html;
}

// 오늘부터 시작해서 1년(12개월) 생성
$startDate = date('Y-m-01'); // 오늘의 첫째 날
$monthsToGenerate = 12; // 1년

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
                <h2 class="logo" onclick="gotoMain()"><span class="blind">뭉클트립</span></h2>
                <div class="btn__wrap">
                    <button type="button" class="btn-search" onclick="gotoSearch()"><span class="blind">검색</span></button>
                    <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
                </div>
            </div>
        </header>

        <main class="container__wrap main__wrap">
            <section class="layout__wrap padding-x-0" style="padding-bottom: 0;">
                <div class="tit__wrap">
                    <p class="tit">우리 아이와 갈만한 숙소</p>
                    <p class="sub-tit"><span class="point">맘 편</span>하게 뭉클이 대신 찾아 드릴게요</p>
                </div>

                <div class="input_select_box">
                    <div class="search-form__con search-input custom fnOpenPop" data-name="popupWho" id="selectedWhoContainer">
                        <i class="ico ico-search__small select_1"></i>
                        <div class="input__wrap" id="whoAccordion">
                            <input type="text" class="input" placeholder="인원 선택" value="" readonly>
                        </div>
                    </div>

                    <div class="search-form__con search-input custom fnOpenPop" style="margin: 1.5rem 0;" data-name="popupDate" id="selectedTagsContainer">
                        <i class="ico ico-search__small select_2"></i>
                        <div class="input__wrap">
                            <input type="text" class="input" placeholder="관심 날짜" value="" readonly>
                        </div>
                    </div>

                    <div class="search-form__con search-input custom fnOpenPop" data-name="popupCity" id="selectedCityContainer">
                        <i class="ico ico-search__small select_3"></i>
                        <div class="input__wrap" id="whereAccordion">
                            <input type="text" class="input" placeholder="관심 도시" value="" readonly>
                        </div>
                    </div>
                </div>

                <!-- 선호 조건 -->
                <div class="tag_select">
                    <div class="tit__wrap">
                        <p class="sub-tit">선호 조건</p>
                    </div>
                    <div class="tag_box">
                        <div class="select__wrap col-4 multi-select">
                            <ul>
                                <li data-taste-machine-name="swimming_pool" data-section="stayTaste" data-tag-name="수영장">
                                    <a href="#none">수영장</a>
                                </li>
                                <li data-taste-machine-name="kids_playroom" data-section="stayTaste" data-tag-name="키즈플레이룸">
                                    <a href="#none">키즈플레이룸</a>
                                </li>
                                <li data-taste-machine-name="family_room" data-section="stayTaste" data-tag-name="패밀리룸">
                                    <a href="#none">패밀리룸</a>
                                </li>
                                <li data-taste-machine-name="private_house_type" data-section="stayTaste" data-tag-name="독채형">
                                    <a href="#none">독채형</a>
                                </li>
                                <li data-taste-machine-name="barbecue_area" data-section="stayTaste" data-tag-name="바베큐장">
                                    <a href="#none">바베큐장</a>
                                </li>
                                <li data-taste-machine-name="value_for_money_important" data-section="stayTaste" data-tag-name="가성비 중요">
                                    <a href="#none">가성비 중요</a>
                                </li>
                                <li data-taste-machine-name="large_rooms_for_5_or_more_people" data-section="stayTaste" data-tag-name="대형 객실 보유(5인+)">
                                    <a href="#none">대형 객실 보유(5인+)</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="btn__wrap">
                        <button type="button" class="btn-full__primary fnOpenPop" data-name="<?= $deviceType !== 'app' ? 'alertWeb' : 'alertApp' ?>">
                            좋은 숙소 찾아줘 &nbsp;<img src="/assets/app/images/common/ico_star_txt.svg" alt="별 아이콘" style="width: 2.1rem;">
                        </button>
                    </div>
                </div>

                <!-- 숙소가 다했다! 실시간 인기 뭉클딜 -->
                <div class="section_layout bg-gray-2">
                    <div class="tit_wrap">
                        <h4>숙소가 다했다! 실시간 인기 뭉클딜 🔥</h4>
                    </div>
                    <div class="overflow-x-visible padding-x-20">
                        <ul class="moongcledeal_slide overflow_slide">
                            <?php
                                $shuffledPopularities = $popularities;
                                shuffle($shuffledPopularities);
                            ?>

                            <?php foreach ($shuffledPopularities as $popularity) : ?>
                                <li>
                                    <a href="/moongcleoffer/product/<?= $popularity->partner_idx; ?>">
                                        <div class="product-list__con">
                                            <div class="img_box">
                                                <img src="<?= $popularity->image_normal_path; ?>" alt="숙소 이미지">

                                                <!-- 우측 상단 -->
                                                <div class="badge-group right-top">
                                                    <?php if ($popularity->sale_end_date === null) : ?>
                                                        <div class="badge badge__lavender">실시간 뭉클딜</div>
                                                    <?php else : ?>
                                                        <?php
                                                        $saleEndDate = new DateTime($popularity->sale_end_date);
                                                        $now = new DateTime();

                                                        if ($saleEndDate > $now) {
                                                            $interval = $now->diff($saleEndDate);
                                                            $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                                                            $days = floor($totalMinutes / (24 * 60));
                                                            $hours = floor(($totalMinutes % (24 * 60)) / 60);
                                                            $minutes = $totalMinutes % 60;

                                                            echo "<div class='badge badge__lavender'>{$days}일 {$hours}시간 {$minutes}분 남음</div>";
                                                        } 
                                                        ?>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- 좌측 상단 -->
                                                <?php
                                                    $benefits = $popularity->benefits;

                                                    if (!empty($popularity) && !empty($popularity->benefits)) {
                                                        $decoded = json_decode($popularity->benefits);
                                                        if (is_array($decoded) || is_object($decoded)) {
                                                            $benefits = $decoded;
                                                        }
                                                    }

                                                    $benefitCount = count($benefits);
                                                ?>
                                                <div class="badge-group left-top">
                                                    <?php if ($popularity->minimum_discount) : ?>
                                                        <div class="badge badge__red"><?= intval($popularity->minimum_discount); ?>% 할인</div>
                                                    <?php endif; ?>

                                                    <?php if ($benefitCount < 2): ?>
                                                        <div class="badge badge__red">특전 포함</div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- 우측 하단 -->
                                                <div class="badge-group right-bottom">
                                                    <?php if ($benefitCount >= 2) : ?>
                                                        <div class="badge badge__purple">특전 <?= $benefitCount; ?>개 포함</div>
                                                    <?php endif; ?>

                                                    <?php if ($popularity->moongcleoffer_count >= 2) : ?>
                                                        <div class="badge badge__purple"><?= $popularity->moongcleoffer_count; ?>개 뭉클딜 진행 중</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="product-list__detail">
                                                <p class="detail-sub"> 
                                                    <?php if (!empty($popularity->partner_address1)) : ?>
                                                        <span><?= $popularity->partner_address1; ?></span>
                                                    <?php endif; ?>

                                                    <?php $stayTypes = explode(':-:', $popularity->types); ?>
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

                                                    if (!empty($popularity->tags)) {
                                                        foreach ($ratingKeywords as $keyword) {
                                                            if (strpos($popularity->tags, $keyword) !== false) {
                                                                $hasRating = true;
                                                                break;
                                                            }
                                                        }
                                                    }

                                                    $rating = extract_stay_rating($popularity->tags);
                                                    ?>

                                                    <?php if ($hasRating && !empty($rating)) : ?>
                                                        <span><?= $rating ?></span>
                                                    <?php endif; ?>
                                                </p>
                                                <p class="detail-name">
                                                    <?= $popularity->partner_name; ?>
                                                </p>
                                                <div class="product-list__price">
                                                    <?php if ($popularity->basic_price != $popularity->lowest_price) : ?>
                                                        <p class="sale-percent"><?= number_format((($popularity->basic_price - $popularity->lowest_price) / $popularity->basic_price) * 100, 1) ?>%</p>
                                                        <p class="default-price"><?= number_format($popularity->basic_price); ?>원</p>
                                                    <?php endif; ?>
                                                    <p class="sale-price"><?= number_format($popularity->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- 뭉클맘들이 극찬한 숙소 -->
                 <div class="section_layout">
                    <div class="tit_wrap">
                        <h4>뭉클맘들이 극찬한 숙소</h4>
                    </div>
                    <div class="tab__wrap tab-round__wrap type-circle">
                        <div class="overflow-x-visible ">
                            <ul class="tab__inner capsule-btns padding-x-20">
                                <li class="tab-round__con active">
                                    <a href="#none">아이와 함께</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">수영장이 있는</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">자연과 함께</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">해수욕장 주변</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">캐릭터룸</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">키즈 펜션</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">가성비 좋은</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">글램핑</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">한옥 체험</a>
                                </li>
                                <li class="tab-round__con">
                                    <a href="#none">대형 객실 보유</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-contents__wrap main padding-x-20 moongclemoms">
                            <!-- 아이와 함께 -->
                            <div class="tab-contents active">
                                <ul>
                                    <?php foreach ($with_children as $with_child) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $with_child->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $with_child->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($with_child->partner_address1)) : ?>
                                                                <span><?= $with_child->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php 
                                                                $stayTypes = explode(':-:', $with_child->types); 
                                                            ?>
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

                                                            if (!empty($with_child->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($with_child->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($with_child->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $with_child->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($with_child->basic_price != $with_child->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($with_child->basic_price - $with_child->lowest_price) / $with_child->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($with_child->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($with_child->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay"  data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>
                            
                            <!-- 수영장이 있는 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($with_swimmings as $with_swimming) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $with_swimming->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $with_swimming->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($with_swimming->partner_address1)) : ?>
                                                                <span><?= $with_swimming->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $with_swimming->types); ?>
                                                            <?php if (!empty($stayTypes[0])) : ?>
                                                                <span>
                                                                    <?php foreach ($stayTypes as $tagKey => $stayType) : ?>
                                                                        <?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
                                                                    <?php endforeach; ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($stayRating)) : ?>
                                                                <span><?= $stayRating; ?></span>
                                                            <?php endif; ?>
                                                            <?php
                                                            $ratingKeywords = ['1성', '2성', '3성', '4성', '5성'];
                                                            $hasRating = false;

                                                            if (!empty($with_swimming->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($with_swimming->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($with_swimming->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $with_swimming->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($with_swimming->basic_price != $with_swimming->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($with_swimming->basic_price - $with_swimming->lowest_price) / $with_swimming->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($with_swimming->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($with_swimming->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay"  data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 자연과 함께 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($with_natures as $with_nature) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $with_nature->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $with_nature->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($with_nature->partner_address1)) : ?>
                                                                <span><?= $with_nature->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $with_nature->types); ?>
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

                                                            if (!empty($with_nature->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($with_nature->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($with_nature->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $with_nature->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($with_nature->basic_price != $with_nature->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($with_nature->basic_price - $with_nature->lowest_price) / $with_nature->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($with_nature->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($with_nature->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 해수욕장 주변 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($around_beaches as $around_beach) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $around_beach->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $around_beach->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($around_beach->partner_address1)) : ?>
                                                                <span><?= $around_beach->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $around_beach->types); ?>
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

                                                            if (!empty($around_beach->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($around_beach->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($around_beach->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $around_beach->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($around_beach->basic_price != $around_beach->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($around_beach->basic_price - $around_beach->lowest_price) / $around_beach->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($around_beach->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($around_beach->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 캐릭터룸 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($character_rooms as $character_room) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $character_room->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $character_room->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($character_room->partner_address1)) : ?>
                                                                <span><?= $character_room->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $character_room->types); ?>
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

                                                            if (!empty($character_room->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($character_room->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($character_room->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $character_room->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($character_room->basic_price != $character_room->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($character_room->basic_price - $character_room->lowest_price) / $character_room->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($character_room->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($character_room->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 키즈 펜션 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($kids_pensions as $kids_pension) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $kids_pension->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $kids_pension->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($kids_pension->partner_address1)) : ?>
                                                                <span><?= $kids_pension->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $kids_pension->types); ?>
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

                                                            if (!empty($kids_pension->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($kids_pension->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($kids_pension->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $kids_pension->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($kids_pension->basic_price != $kids_pension->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($kids_pension->basic_price - $kids_pension->lowest_price) / $kids_pension->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($kids_pension->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($kids_pension->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 가성비 좋은 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($cost_effectives as $cost_effective) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $cost_effective->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $cost_effective->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($cost_effective->partner_address1)) : ?>
                                                                <span><?= $cost_effective->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $cost_effective->types); ?>
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

                                                            if (!empty($cost_effective->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($cost_effective->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($cost_effective->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $cost_effective->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($cost_effective->basic_price != $cost_effective->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($cost_effective->basic_price - $cost_effective->lowest_price) / $cost_effective->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($cost_effective->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($cost_effective->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 글램핑 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($glampings as $glamping) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $glamping->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $glamping->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($glamping->partner_address1)) : ?>
                                                                <span><?= $glamping->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $glamping->types); ?>
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

                                                            if (!empty($glamping->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($glamping->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($glamping->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $glamping->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($glamping->basic_price != $glamping->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($glamping->basic_price - $glamping->lowest_price) / $glamping->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($glamping->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($glamping->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 한옥 체험 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($hanok_experiences as $hanok_experience) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $hanok_experience->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $hanok_experience->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($hanok_experience->partner_address1)) : ?>
                                                                <span><?= $hanok_experience->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $hanok_experience->types); ?>
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

                                                            if (!empty($hanok_experience->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($hanok_experience->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($hanok_experience->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $hanok_experience->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($hanok_experience->basic_price != $hanok_experience->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($hanok_experience->basic_price - $hanok_experience->lowest_price) / $hanok_experience->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($hanok_experience->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($hanok_experience->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>

                            <!-- 대형 객실 보유 -->
                            <div class="tab-contents">
                                <ul>
                                    <?php foreach ($having_large_rooms as $having_large_room) : ?>
                                        <li>
                                            <a href="/stay/detail/<?= $having_large_room->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <p class="thumb__img large">
                                                        <img src="<?= $having_large_room->image_normal_path; ?>" alt="숙소 이미지">
                                                    </p>

                                                    <div class="thumb__con">
                                                        <p class="detail-sub">
                                                            <?php if (!empty($having_large_room->partner_address1)) : ?>
                                                                <span><?= $having_large_room->partner_address1; ?></span>
                                                            <?php endif; ?>

                                                            <?php $stayTypes = explode(':-:', $having_large_room->types); ?>
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

                                                            if (!empty($having_large_room->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($having_large_room->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($having_large_room->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name">
                                                            <?= $having_large_room->partner_name; ?>
                                                        </p>
                                                    </div>

                                                    <div class="thumb__price">
                                                        <div style="padding-bottom: 0;">
                                                            <?php if ($having_large_room->basic_price != $having_large_room->lowest_price) : ?>
                                                                <p class="sale-percent"><?= number_format((($having_large_room->basic_price - $having_large_room->lowest_price) / $having_large_room->basic_price) * 100, 1) ?>%</p>
                                                                <p class="default-price"><?= number_format($having_large_room->basic_price); ?>원</p>
                                                            <?php endif; ?>
                                                            <p class="sale-price search"><?= number_format($having_large_room->lowest_price); ?>원~ <span style="font-weight: 400; font-size: 1.2rem;">(1박)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btn__wrap">
                                    <button type="button" class="btn-full__line fnOpenPop recommend-stay" data-name="<?= $deviceType !== 'app' ? 'alertCopyWeb' : 'alertCopyApp' ?>">
                                        더 추천 받기
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>

                 <!-- 아이와 갈만한 숙소: 지도로 한 눈에 보기 -->
                 <div class="section_layout" style="margin-top: 4.5rem;">
                    <div class="tit_wrap">
                        <h4>아이와 갈만한 숙소: 지도로 한 눈에 보기</h4>
                    </div>
                    <div class="overflow-x-visible padding-x-20">
                        <ul class="city_slide overflow_slide">
                            <li onclick="clickSearchMap('경기', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_gyeonggi.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">경기</p>
                                    <p class="disc">서울 근교 아이와 함께 추천 숙소</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('인천', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_incheon.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">인천</p>
                                    <p class="disc">서울 근접 바다 키즈 숙소</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('가평', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_gapyeong.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">가평</p>
                                    <p class="disc">전국 1위 키즈 펜션 성지</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('경남', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_gyeongnambusan.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">부산 · 경남</p>
                                    <p class="disc">부산 가족 여행 추천 숙소</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('제주', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_jeju.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">제주</p>
                                    <p class="disc">아이와 첫 제주 여행</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('강원', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_gangwon.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">강원</p>
                                    <p class="disc">맘 편한 자연속 힐링</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('경주', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_gyeongju.jpg" alt="이미지" style="object-position: 20% 57%;">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">경주</p>
                                    <p class="disc">역사 + 체험 모두 가능한 곳</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('전주', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_jeonju.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">전주</p>
                                    <p class="disc">한옥 마을 키즈 숙소</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('강릉', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_gangneung.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">강릉</p>
                                    <p class="disc">우리 아이 첫 동해 바다</p>
                                </div>
                            </li>
                            <li onclick="clickSearchMap('여수', 'city', 'places_to_visit_with_kids')">
                                <div class="img_box">
                                    <img src="/assets/app/images/main/main_map_yeosu.jpg" alt="이미지">
                                </div>
                                <div class="txt_box">
                                    <p class="tit">여수</p>
                                    <p class="disc">우리 가족 낭만의 도시 여수</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                 </div>

                 <!-- 맞춤 숙소 추천받기 -->
                 <div class="btn_links">
                    <button type="button" onclick="location.href='/moongcledeals'">
                        <div>
                            <span class="tit">맞춤 숙소 추천받기</span>
                            <span class="disc">뭉클맘을 위한 특별 할인 혜택</span>
                        </div>
                        <span class="icon">
                            <i class="fa-solid fa-angle-right"></i>
                        </span>
                    </button>
                </div>

                 <!-- 뭉클맘 리얼 후기 -->
                 <div class="section_layout" style="margin-top: 4.5rem;">
                    <div class="tit_wrap">
                        <h4>뭉클맘 리얼 후기</h4>
                    </div>
                    <div class="overflow-x-visible padding-x-20">
                        <ul class="review_slide overflow_slide community-list__con">
                            <?php foreach (array_slice($reviews, 0, 10) as $review) : ?>
                                <?php if (!empty($review->image_list)) : ?>
                                <li class="review-list__con">
                                    <div class="community-top">
                                        <div class="user-wrap">
                                            <p class="img"><img src="/assets/app/images/common/no_profile.jpg" alt=""></p>
                                            <div>
                                                <p class="name">
                                                    <?= $review->user_nickname; ?>
                                                </p>
                                                <div class="start">
                                                    <?php
                                                        $fullStars = floor($review->rating);
                                                        $halfStar = ($review->rating - $fullStars) >= 0.5 ? 1 : 0;
                                                        $emptyStars = 5 - $fullStars - $halfStar;
                                                    ?>
                                                    <?php for ($i = 0; $i < $fullStars; $i++) : ?>
                                                        <i class="ico ico-star"></i>
                                                    <?php endfor; ?>
                                                    <?php if ($halfStar) : ?>
                                                        <i class="ico ico-star__half"></i>
                                                    <?php endif; ?>
                                                    <?php for ($i = 0; $i < $emptyStars; $i++) : ?>
                                                        <i class="ico ico-star__empty"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="community-bottom">
                                                    <?php
                                                        $createdAt = new DateTime($review->created_at);
                                                        $formattedDate = $createdAt->format('y. m. d H:i');
                                                    ?>
                                                    <div class="community-time"><?= $formattedDate; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="img_box">
                                        <div class="splide splide__product">
                                            <div class="splide__track">
                                                <ul class="splide__list">
                                                    <?php
                                                        $reviewImages = [];

                                                        if (!empty($review) && !empty($review->image_list)) {
                                                            $decoded = json_decode($review->image_list);

                                                            if (is_array($decoded) || is_object($decoded)) {
                                                                $reviewImages = $decoded;
                                                            }
                                                        }
                                                    ?>
                                                    <?php if (!empty($reviewImages)) : ?>
                                                        <?php foreach ($reviewImages as $reviewImage) : ?>
                                                            <?php
                                                            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                            $allowedVideoExtensions = ['mov', 'mp4'];
                                                            ?>
                                                            <?php if (in_array($reviewImage->extension, $allowedImageExtensions)) : ?>
                                                                <li class="splide__slide splide__list__product"><img src="<?= $reviewImage->path; ?>" alt=""></li>
                                                            <?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
                                                                <li class="splide__slide splide__list__product">
                                                                    <video class="video-element" controls>
                                                                        <source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
                                                                        현재 브라우저가 지원하지 않는 영상입니다.
                                                                    </video>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <li class="splide__slide splide__list__product">
                                                            <img src="/assets/app/images/demo/moongcle-noimg.png" alt="">
                                                        </li>
                                                    <?php endif; ?> 
                                                </ul>
                                            </div>
                                            <?php if (!empty($reviewImages)) : ?>
                                                <div class="slide-counter">
                                                    <span class="current-slide">1</span> / <span class="total-slides"></span>
                                                </div>
                                            <?php endif; ?> 
                                        </div>
                                    </div>
                                    <div class="review-list__con">
                                        <p class="title">
                                            <?= $review->partner_name; ?>
                                        </p>

                                        <?php
                                        $content = $review->review_content;
                                        $limit = 100; 

                                        $isLong = mb_strlen(strip_tags($content)) > $limit;
                                        ?>
                                        <div class="review-txt">
                                            <p class="review" style="white-space: pre-line;"><?= htmlspecialchars($content); ?></p>
                                            <?php if ($isLong): ?>
                                                <a class="btn-more">더보기</a>
                                            <?php else: ?>
                                                <a style="height: 1.4rem; display: block; margin-top: 0.8rem;"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="/stay/detail/<?= $review->partner_idx; ?>" class="review-product">
                                        <?php if ($review->partner_image_path === null) : ?>
                                            <p class="img"><img src="/assets/app/images/demo/moongcle-noimg.png" alt="" style="height: 100%; object-fit: cover; border-radius: 1.2rem;"></p>
                                        <?php else : ?>
                                            <p class="img"><img src="<?= $review->partner_image_path; ?>" alt="" style="height: 100%; object-fit: cover; border-radius: 1.2rem;"></p>
                                        <?php endif; ?>
                                        <div class="tit__wrap">
                                            <p class="detail-name"><?= $review->partner_name; ?></p>
                                            <?php if ($review->partner_address1 !== null) : ?>
                                                <p class="detail-sub"><span><?= $review->partner_address1; ?></span>
                                            <?php endif; ?>
                                                <?php $stayTypes = explode(':-:', $review->partner_types); ?>
                                                <?php if (!empty($stayTypes)) : ?>
                                                    <span>
                                                        <?php foreach ($stayTypes as $tagKey => $stayType) : ?>
                                                            <?= !empty($stayTypes[$tagKey + 1]) ? $stayType . ', ' : $stayType; ?>
                                                        <?php endforeach; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <i class="ico ico-stick-arrow__right"></i>
                                    </a>
                                    
                                </li>
                                <?php endif; ?>                                
                            <?php endforeach; ?>
                        </ul>
                    </div>
                 </div>

                 <?php if ($deviceType !== 'app') : ?>
                    <div style="padding: 0rem 2rem 2.4rem 2rem;" onclick="openAppDownloadTab()">
                        <img src="/assets/app/images/main/download_section.png" alt="">
                    </div>
                <?php endif; ?>
            </section>

            <!-- 푸터 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/footer.php"; ?>
            <!-- //푸터 -->

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>
        </main>

        <!-- 바텀 팝업(인원 선택) -->
        <div id="popupWho" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">누구와 갈까요?</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap col-4 single-select">
                        <ul>
                            <li id="personnel-1"><a>1명</a></li>
                            <li id="personnel-2"><a>2명</a></li>
                            <li id="personnel-3"><a>3명</a></li>
                            <li id="personnel-4"><a>4명</a></li>
                            <li id="personnel-5"><a>5명</a></li>
                            <li id="personnel-6"><a>6명</a></li>
                            <li id="personnel-7"><a>7명</a></li>
                            <li id="personnel-8"><a>8명 이상</a></li>
                        </ul>
                    </div>

                    <hr class="divide__small" style="margin: 2.4rem 0;">

                    <div class="select__wrap type-img single-select">
                        <ul>
                            <?php foreach ($companionTags as $companionTag) : ?>
                                <li id="companion-<?= $companionTag['tag_machine_name']; ?>" data-companion-machine-name="<?= $companionTag['tag_machine_name']; ?>" data-tag-name="<?= $companionTag['tag_name']; ?>">
                                    <a>
                                        <img src="/uploads/tags/<?= $companionTag['tag_machine_name']; ?>.png" alt="">
                                        <span><?= $companionTag['tag_name']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- 반려동물 상세 선택  -->
                    <div class="companionAnimalsWrap" style="display: none; position:relative; margin-top:5.4rem;">

                        <div class="tab__wrap tab-switch__wrap"
                            style="border-top: 1px solid #eee; padding: 2rem; padding-bottom:0px;">
                            
                            <div style="position: absolute; left: 50%; transform: translateX(-50%); top: -18px; width:100%;">
                                <ul class="tab__inner">
                                    <li class="tab-switch__con active" style="margin-right:0rem;">
                                        <a>크기</a>
                                    </li>
                                    <li class="tab-switch__con">
                                        <a>무게</a>
                                    </li>
                                    <li class="tab-switch__con">
                                        <a>마릿수</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-contents__wrap">
                                <!-- 크기 -->
                                <div class="tab-contents active">
                                    <div class="select__wrap type-img single-select" style="margin: 2.4rem 0;">
                                        <ul>
                                            <?php foreach ($petDetailTags['size'] as $petDetailTag) : ?>
                                                <li id="pet-size-<?= $petDetailTag['tag_machine_name']; ?>" data-pet-size-machine-name="<?= $petDetailTag['tag_machine_name']; ?>" data-tag-name="<?= $petDetailTag['tag_name']; ?>">
                                                    <a>
                                                        <img src="/uploads/tags/<?= $petDetailTag['tag_machine_name']; ?>.png" alt="">
                                                        <span><?= $petDetailTag['tag_name']; ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <p style="font-size:12px; opacity:50%;">* 2마리 이상일 때 최대 크기로 설정해 주세요</p>
                                </div>

                                <!-- 무게 -->
                                <div class="tab-contents">
                                    <div class="select__wrap type-img single-select" style="margin: 2.4rem 0;">
                                        <ul>
                                            <?php foreach ($petDetailTags['weight'] as $petDetailTag) : ?>
                                                <li id="pet-weight-<?= $petDetailTag['tag_machine_name']; ?>" data-pet-weight-machine-name="<?= $petDetailTag['tag_machine_name']; ?>" data-tag-name="<?= $petDetailTag['tag_name']; ?>">
                                                    <a>
                                                        <img src="/uploads/tags/<?= $petDetailTag['tag_machine_name']; ?>.png" alt="">
                                                        <span><?= $petDetailTag['tag_name']; ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <p style="font-size:12px; opacity:50%;">* 2마리 이상일 때 최대 무게로 설정해 주세요</p>
                                </div>

                                <!-- 마릿수 -->
                                <div class="tab-contents">
                                    <div class="select__wrap type-img single-select" style="margin: 2.4rem 0;">
                                        <ul>
                                            <?php foreach ($petDetailTags['counts'] as $petDetailTag) : ?>
                                                <li id="pet-count-<?= $petDetailTag['tag_machine_name']; ?>" data-pet-count-machine-name="<?= $petDetailTag['tag_machine_name']; ?>" data-tag-name="<?= $petDetailTag['tag_name']; ?>">
                                                    <a>
                                                        <img src="/uploads/tags/<?= $petDetailTag['tag_machine_name']; ?>.png?v=1" alt="">
                                                        <span><?= $petDetailTag['tag_name']; ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button id="selectCompanionUndefined" class="btn-full__line">미정</button>
                        <button id="selectCompanion" class="btn-full__black disabled" disabled>선택</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 바텀 팝업(관심 날짜) -->
        <div id="popupDate" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">언제 갈까요?</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="tab__wrap tab-switch__wrap">
                        <ul class="tab__inner">
                            <li class="tab-switch__con active">
                                <a>날짜 선택</a>
                            </li>
                            <li class="tab-switch__con">
                                <a>월 선택</a>
                            </li>
                        </ul>
                        <div class="tab-contents__wrap">
                            <!-- 날짜 선택 탭 -->
                            <div class="tab-contents active">
                                <div class="calendar-wrap">
                                    <div class="placeholder"></div>
                                </div>

                                <div class="btn__wrap mt30">
                                    <button class="btn-full__line selectDaysUndefined">미정</button>
                                    <button class="btn-full__black selectDays disabled" disabled>선택</button>
                                </div>
                            </div>
                            <!-- //날짜 선택 탭 -->
                            <!-- 월 선택 탭 -->
                            <div class="tab-contents">
                                <?= generateMonthSelection($startDate, $monthsToGenerate); ?>
                                <div class="btn__wrap mt30">
                                    <button class="btn-full__line selectDaysUndefined">미정</button>
                                    <button class="btn-full__black selectMonth disabled" disabled>선택</button>
                                </div>
                            </div>
                            <!-- //월 선택 탭 -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 바텀 팝업(관심 도시) -->
        <div id="popupCity" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">어디로 갈까요?</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>

                <div class="layerpop__contents">
                    <div class="tab__wrap tab-switch__wrap">
                        <ul class="tab__inner">
                            <li class="tab-switch__con active" style="margin-right:0rem;">
                                <a>국내</a>
                            </li>
                            <li class="tab-switch__con">
                                <a>해외</a>
                            </li>
                        </ul>
                        <div class="tab-contents__wrap">
                            <!-- 국내 선택 탭 -->
                            <div class="tab-contents active">
                                <div class="select__wrap col-3 single-select" style="margin: 2.4rem 0;">
                                    <ul>
                                        <?php foreach ($cityTags as $cityTag) : ?>
                                            <li id="city-<?= $cityTag['tag_machine_name']; ?>" data-city-machine-name="<?= $cityTag['tag_machine_name']; ?>" data-tag-name="<?= $cityTag['tag_name']; ?>">
                                                <a> 
                                                    <?= $cityTag['tag_name']; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="custom-input__wrap">
                                    <div class="custom-input__after">
                                        <div class="btn__wrap">
                                            <button class="btn-txt btn-txt__default custom-input active">직접 입력 <i class="ico ico-keyboard"></i></button>
                                        </div>
                                        <div class="input__wrap" style="display:none;">
                                            <input id="customCityInput" type="text" class="input-default" placeholder="도시를 입력해주세요" style="width: auto;">
                                            <span style="display: block; margin-top: 1.2rem; color: #d0d0d4; font-size: 1.2rem;">'OO시' 제외 / 예) 여수, 강릉 등</span>
                                        </div>
                                        <div id="customCity">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- //국내 선택 탭 -->
                            <!-- 해외 선택 탭 -->
                            <div class="tab-contents">
                                <div class="select__wrap col-3 single-select" style="margin: 2.4rem 0;">
                                    <ul>
                                        <?php foreach ($overseasTags as $overseasTag) : ?>
                                            <li id="overseas-<?= $overseasTag['tag_machine_name']; ?>" data-city-machine-name="<?= $overseasTag['tag_machine_name']; ?>" data-tag-name="<?= $overseasTag['tag_name']; ?>">
                                                <a>
                                                    <?= $overseasTag['tag_name']; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- //해외 선택 탭 -->
                        </div>
                        <div class="btn__wrap mt30">
                            <button id="selectCityUndefined" class="btn-full__line">미정</button>
                            <button id="selectCity" class="btn-full__black disabled" disabled>선택</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 좋은 숙소 찾아줘 로그인 (앱)-->
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
                        <button id="moongcledealConfirmBtn" class="btn-full__primary">지금 뭉클딜 등록하기</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 좋은 숙소 찾아줘 비로그인 (웹)-->
        <div id="alertWeb" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <div class="align__left">
                        <p class="title">
                            숙소 추천 받기는 앱에서만 가능해요!
                        </p>
                        <p class="desc">아래의 조건으로 나만의 숙소를 추천 받아볼까요?</p>
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

        <!-- 뭉클딜 등록 팝업 -->
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
                        <button id="startMoongcledeal" class="btn-full__primary">지금 뭉클딜 등록하기</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 뭉클딜 등록 팝업 (웹)-->
        <div id="alertCopyWeb" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <div class="align__left">
                        <p class="title">
                            숙소 추천 받기는 앱에서만 가능해요!
                        </p>
                        <p class="desc">아래의 조건으로 나만의 숙소를 추천 받아볼까요?</p>
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

        <div id="toastUndefined" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p>하나 이상의 태그를 선택해주세요.</p>
            </div>
        </div>

        <div id="moongcledealLoading" class="complete__wrap loading" style="display: none;">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/moongcledeal-loader.php"; ?>

            <div class="btn__wrap">
                <button class="btn-full__primary" onclick="location.href='/moongcledeals'">나만의 뭉클딜 바로가기</button>
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
            location.href = '/moongcledeal/create/02';
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
        thirdpartyWebviewZoomFontIgnore();
    </script>

    <script>
        document.querySelectorAll('.moongcledeal_slide li a').forEach(el => {
            el.addEventListener('click', function () {
                showLoader();
            });
        });

        document.querySelectorAll('.moongclemoms .tab-contents li a').forEach(el => {
            el.addEventListener('click', function () {
                showLoader();
            });
        });

        document.querySelectorAll('.city_slide li').forEach(el => {
            el.addEventListener('click', function () {
                showLoader();
            });
        });

        document.querySelectorAll('.review_slide .review-product').forEach(el => {
            el.addEventListener('click', function () {
                showLoader();
            });
        });
    </script>
    
    <script>
        /** 
         * 
         * 관심 날짜
         * 
         */

        let selectedDates = null;
        let selectedMonth = null;
        let selectedDays = [];

        const selectedTagsContainer = document.getElementById("selectedTagsContainer");
        const dayNotSelected = document.getElementById("dayNotSelected");
        const selectDaysButton = document.querySelector(".selectDays");
        const selectMonthButton = document.querySelector(".selectMonth");
        const calendarPlaceholder = document.querySelector(".calendar-wrap .placeholder");

        // Flatpickr 초기화
        const calendar = flatpickr(calendarPlaceholder, {
            inline: true,
            mode: "range",
            minDate: "today",
            locale: "ko",
            onChange: function(selectedDatesArray) {
                // 날짜 선택 시 월 초기화
                if (selectedMonth) {
                    resetMonthSelection();
                }

                selectedDates = selectedDatesArray;
                updateButtonState(selectDaysButton, selectedDatesArray.length === 2);
            },
        });

        // "미정" 버튼 클릭
        document.querySelectorAll(".selectDaysUndefined").forEach((button) => {
            button.addEventListener("click", () => {
                resetSelections();
                toggleAccordionById('whenAccordion');
                
                const closeBtn = document.querySelector('#popupDate .fnClosePop');
                if (closeBtn) closeBtn.click();
            });
        });

        // 날짜 선택 버튼 클릭
        selectDaysButton.addEventListener("click", () => {
            if (selectedDates && selectedDates.length === 2) {
                const startDate = formatDate(selectedDates[0]);
                const endDate = formatDate(selectedDates[1]);
                updateSelectionTag(`${startDate}~${endDate}`);
                toggleAccordionById('whenAccordion');

                selectedDays[0] = {
                    type: 'period',
                    dates: `${startDate}~${endDate}`
                };

                const closeBtn = document.querySelector('#popupDate .fnClosePop');
                if (closeBtn) closeBtn.click();
            }
        });

        // 월 선택
        document.querySelectorAll("[id^='month-']").forEach((monthElement) => {
            monthElement.addEventListener("click", (event) => {
                // 기존 활성화된 월 초기화
                document.querySelectorAll("[id^='month-']").forEach((el) => el.classList.remove("active"));

                // 현재 월 활성화
                selectedMonth = event.target.textContent;
                monthElement.classList.add("active");

                // 날짜 초기화
                if (selectedDates) {
                    resetDateSelection();
                }

                updateButtonState(selectMonthButton, !!selectedMonth);
            });
        });

        // 월 선택 버튼 클릭
        selectMonthButton.addEventListener("click", () => {
            if (selectedMonth) {
                updateSelectionTag(`${selectedMonth}`);
                toggleAccordionById('whenAccordion');
                selectedDays[0] = {
                    type: 'month',
                    dates: `${selectedMonth}`
                };
            }

            const closeBtn = document.querySelector('#popupDate .fnClosePop');
            if (closeBtn) closeBtn.click();
        });

        const hiddenInputBox = document.querySelector('#selectedTagsContainer');
        const hiddenInput = hiddenInputBox.querySelector('input');

        // 선택 상태 업데이트
        function updateSelectionTag(tag) {
            hiddenInput.value = tag;
            hiddenInputBox.classList.add('active');
        }

        // 선택 상태 초기화
        function resetSelections() {
            resetDateSelection();
            resetMonthSelection();

            hiddenInput.value = '';
            hiddenInputBox.classList.remove('active');
        }

        function resetDateSelection() {
            calendar.clear();
            selectedDates = null;
            selectedDays = [];
            updateButtonState(selectDaysButton, false);
        }

        function resetMonthSelection() {
            selectedMonth = null;
            selectedDays = [];
            document.querySelectorAll("[id^='month-']").forEach((el) => el.classList.remove("active"));
            updateButtonState(selectMonthButton, false);
        }

        // 버튼 상태 업데이트 함수
        function updateButtonState(button, isEnabled) {
            button.disabled = !isEnabled;

            // 클래스 토글
            if (isEnabled) {
                button.classList.remove("disabled");
            } else {
                button.classList.add("disabled");
            }
        }

        // 날짜 포맷 함수
        function formatDate(date) {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, "0");
            const day = String(d.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        }

        function toggleAccordionById(accordionId, open = null) {
            const $accBtn = $(`#${accordionId}`);
            const $accordionList = $accBtn.closest('.accordion__list');
            let $accorCont = $accBtn.next();

            // 다다음 요소를 찾기 위해 한 번 더 next() 사용
            if ($accorCont.length && !$accorCont.hasClass('accordion__con')) {
                $accorCont = $accorCont.next();
            }

            const isCurrentlyOpen = $accBtn.hasClass('active');
            const shouldOpen = open === null ? !isCurrentlyOpen : open;

            if (shouldOpen) {
                $accBtn.addClass('active');
                $accordionList.addClass('active');
                $accorCont.stop(true, true).slideDown(300); // 열기 애니메이션

                $("#gotoNextStepContainer").addClass('hidden');
            } else {
                $accBtn.removeClass('active');
                $accordionList.removeClass('active');
                $accorCont.stop(true, true).slideUp(300); // 닫기 애니메이션

                $("#gotoNextStepContainer").removeClass('hidden');
            }
        }

        /**
         * 
         * 인원 선택
         * 
         */
        let originalPersonnel = null;
        let originalCompanion = null;
        let originalPetSize = null;
        let originalPetWeight = null;
        let originalPetCount = null;

        // 현재 선택 중인 값
        let currentPersonnel = originalPersonnel;
        let currentCompanion = originalCompanion;
        let currentPetSize = originalPetSize;
        let currentPetWeight = originalPetWeight;
        let currentPetCount = originalPetCount;
        
        let currentPersonnelText = null;
        let currentCompanionText = null;
        let currentPetSizeText = null;
        let currentPetWeightText = null;
        let currentPetCountText = null;

        // 추가
        const personnelItems = document.querySelectorAll("[id^='personnel-']");
        const companionItems = document.querySelectorAll(".select__wrap li[data-companion-machine-name]");
        const petItemsSize = document.querySelectorAll(".select__wrap li[data-pet-size-machine-name]");
        const petItemsWeight = document.querySelectorAll(".select__wrap li[data-pet-weight-machine-name]");
        const petItemsCount = document.querySelectorAll(".select__wrap li[data-pet-count-machine-name]");
        const petDetailTagsWrap = document.querySelector(".companionAnimalsWrap");
        const companionBeforeSelected = document.getElementById("companionBeforeSelected");
        const selectCompanionButton = document.getElementById("selectCompanion");
        const selectCompanionUndefinedButton = document.getElementById("selectCompanionUndefined");
        const whoAccordion = document.getElementById("whoAccordion");

        // UI 업데이트
        function updateUICompanion() {
            const isSelected = currentPersonnel || currentCompanion;

            selectCompanionButton.disabled = !isSelected;
            selectCompanionButton.classList.toggle("disabled", !isSelected);         
        }

        // 클릭 이벤트 처리
        personnelItems.forEach(item => {
            item.addEventListener("click", () => {
                currentPersonnel = item.id.replace("personnel-", "");
                updateUICompanion();
            });
        });

        companionItems.forEach(item => {
            item.addEventListener("click", () => {
                currentCompanion = item.id.replace("companion-", "");
                currentCompanionText = item.getAttribute("data-tag-name");
                updateUICompanion();
            });
        });

       // 크기 (size) 선택 처리
        petItemsSize.forEach(item => {
            item.addEventListener("click", () => {
                const tagMachineName = item.id.replace("pet-size-", "");
                const tagName = item.getAttribute("data-tag-name");

                currentPetSize = tagMachineName; 
                currentPetSizeText = tagName;
                updateUICompanion();
            });
        });

        // 무게 (weight) 선택 처리
        petItemsWeight.forEach(item => {
            item.addEventListener("click", () => {
                const tagMachineName = item.id.replace("pet-weight-", "");
                const tagName = item.getAttribute("data-tag-name");            

                currentPetWeight = tagMachineName;  
                currentPetWeightText = tagName;
                updateUICompanion();
            });
        });

        // 마릿수 (counts) 선택 처리
        petItemsCount.forEach(item => {
            item.addEventListener("click", () => {
                const tagMachineName = item.id.replace("pet-count-", "");
                const tagName = item.getAttribute("data-tag-name");
                
                currentPetCount = tagMachineName; 
                currentPetCountText = tagName;
                updateUICompanion();
            });
        });

        // 반려 동물 상세 hidden
        companionItems.forEach(item => {
            item.addEventListener("click", function () {
                // 클릭한 요소의 data 속성 가져오기
                let tag = item.getAttribute("data-companion-machine-name");

                if (tag === "pet_friendly") {
                    petDetailTagsWrap.style.display = "block";
                } else {
                    petDetailTagsWrap.style.display = "none";
                    currentPetSize = null;
                    currentPetWeight = null;
                    currentPetCount = null;

                    petItemsSize.forEach(item => item.classList.remove("active"));
                    petItemsWeight.forEach(item => item.classList.remove("active"));
                    petItemsCount.forEach(item => item.classList.remove("active"));
                }
            });
        });

        // 선택 버튼
        selectCompanionButton.addEventListener("click", () => {
            // 변경 확정
            originalPersonnel = currentPersonnel;
            originalCompanion = currentCompanion;
            originalPetSize = currentPetSize;
            originalPetWeight = currentPetWeight;
            originalPetCount = currentPetCount;
            toggleAccordionById('whoAccordion');

            const closeBtn = document.querySelector('#popupWho .fnClosePop');
            if (closeBtn) closeBtn.click();

            const hiddenInputBox = document.querySelector('#selectedWhoContainer');
            const hiddenInput = hiddenInputBox.querySelector('input');

            // 텍스트 구성
            const texts = [];

            if (currentPersonnel) texts.push(`${currentPersonnel}명`);
            if (currentCompanion) texts.push(`${currentCompanionText}`);
            if (currentPetSize) texts.push(`${currentPetSizeText}`);
            if (currentPetWeight) texts.push(`${currentPetWeightText}`);
            if (currentPetCount) texts.push(`${currentPetCountText}`);

            // 값 설정
            hiddenInput.value = texts.join(', ');
        
            hiddenInputBox.classList.add('active');
        });

        // 미정 버튼
        selectCompanionUndefinedButton.addEventListener("click", () => {
            originalPersonnel = null;
            originalCompanion = null;
            originalPetSize = null;
            originalPetWeight = null;
            originalPetCount = null;
            currentPersonnel = null;
            currentCompanion = null;
            currentPetSize = null;
            currentPetWeight = null;
            currentPetCount = null;
            personnelItems.forEach(item => item.classList.remove("active"));
            companionItems.forEach(item => item.classList.remove("active"));
            petItemsSize.forEach(item => item.classList.remove("active"));
            petItemsWeight.forEach(item => item.classList.remove("active"));
            petItemsCount.forEach(item => item.classList.remove("active"));
            petDetailTagsWrap.style.display = "none";
            updateUICompanion();
            toggleAccordionById('whoAccordion');

            const hiddenInputBox = document.querySelector('#selectedWhoContainer');
            const hiddenInput = hiddenInputBox.querySelector('input');

            hiddenInput.value = '';
            hiddenInputBox.classList.remove('active');

            const closeBtn = document.querySelector('#popupWho .fnClosePop');
            if (closeBtn) closeBtn.click();
        });

        // 선택 처리
        function selectItem(items, idPrefix, value) {
            items.forEach(item => item.classList.remove("active"));
            if (value) {
                document.getElementById(`${idPrefix}-${value}`).classList.add("active");
            }
        }

        whoAccordion.addEventListener("click", () => {
            const isCurrentlyOpen = whoAccordion.classList.contains("active");

            if (isCurrentlyOpen) {
                currentPersonnel = originalPersonnel;
                currentCompanion = originalCompanion;
                currentPetSize = originalPetSize;
                currentPetWeight = originalPetWeight;
                currentPetCount = originalPetCount;

                selectItem(personnelItems, "personnel", originalPersonnel);
                selectItem(companionItems, "companion", originalCompanion); 
                selectItem(petItemsSize, "pet-size", originalPetSize);
                selectItem(petItemsWeight, "pet-weight", originalPetWeight);
                selectItem(petItemsCount, "pet-count", originalPetCount);
                updateUICompanion();
            }
        });

        /**
         * 
         * 어디로 가나요? (국내/해외 통합)
         * 
         */
        let originalCity = null;
        let currentCity = null;

        let currentCityText = null;

        // 도시 태그 DOM 요소
        const cityItems = document.querySelectorAll(".select__wrap li[data-city-machine-name]");
        const selectCityButton = document.getElementById("selectCity");
        const selectCityUndefinedButton = document.getElementById("selectCityUndefined");
        const whereAccordion = document.getElementById("whereAccordion");

        const customCityContainer = document.getElementById("customCity");
        const customCityInput = document.getElementById("customCityInput");

        // UI 업데이트
        function updateUICity() {
            const isSelected = currentCity || originalCity;

            selectCityButton.disabled = !isSelected;
            selectCityButton.classList.toggle("disabled", !isSelected);
        }

        // 도시 선택 이벤트
        cityItems.forEach(item => {
            item.addEventListener("click", () => {
                const isDomestic = item.id.startsWith("city-");
                const isOverseas = item.id.startsWith("overseas-");

                // 도시 식별자 가져오기
                const cityId = isDomestic ? item.id.replace("city-", "") : item.id.replace("overseas-", "");
                const tagName = item.getAttribute("data-tag-name");

                // 기존 선택 초기화
                clearCustomCity();
                cityItems.forEach((city) => city.classList.remove("active"));

                // 선택된 도시 설정
                item.classList.add("active");
                currentCity = cityId;
                currentCityText = tagName;

                updateUICity();
            });
        });

        // "미정" 버튼 처리
        selectCityUndefinedButton.addEventListener("click", () => {
            // 모든 도시 선택 해제
            cityItems.forEach((city) => city.classList.remove("active"));
            clearCustomCity();

            // 선택 초기화
            originalCity = null;
            currentCity = null;

            updateUICity();
            toggleAccordionById('whereAccordion');

            const closeBtn = document.querySelector('#popupCity .fnClosePop');
            if (closeBtn) closeBtn.click();

            const hiddenInputBox = document.querySelector('#selectedCityContainer');
            const hiddenInput = hiddenInputBox.querySelector('input');

            hiddenInput.value = '';
            hiddenInputBox.classList.remove('active');
        });

        // "선택" 버튼 처리
        selectCityButton.addEventListener("click", () => {
            originalCity = currentCity;
            toggleAccordionById('whereAccordion');

            const closeBtn = document.querySelector('#popupCity .fnClosePop');
            if (closeBtn) closeBtn.click();

            const hiddenInputBox = document.querySelector('#selectedCityContainer');
            const hiddenInput = hiddenInputBox.querySelector('input');

            hiddenInput.value = currentCityText;
            hiddenInputBox.classList.add('active');
        });

        // 입력 필드에서 엔터 키 입력 시
        customCityInput.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                const customCityName = customCityInput.value.trim();
                if (customCityName) {
                    setCustomCity(customCityName);
                    customCityInput.value = ""; // 입력 필드 초기화
                    customCityInput.parentElement.style.display = 'none';
                    updateUICity();
                }
            }
        });

        // 입력된 도시 설정
        function setCustomCity(cityName) {
            // 기존 선택된 도시 해제
            cityItems.forEach((city) => city.classList.remove("active"));

            // UI 업데이트
            currentCity = cityName;
            currentCityText = cityName;
            customCityContainer.innerHTML = `
                <div class="myplace-tag">
                    <span>${cityName}</span>
                    <a id="deleteCustomCity" href="#"><i class="ico ico-tag__delete__white"></i></a>
                </div>
            `;

            // 삭제 버튼 이벤트 재설정
            const deleteButton = customCityContainer.querySelector("#deleteCustomCity");
            deleteButton.addEventListener("click", () => {
                clearCustomCity();
                updateUICity();
            });
        }

        // 입력된 도시 삭제
        function clearCustomCity() {
            customCityContainer.innerHTML = ""; // 태그 삭제
            currentCity = null; // 현재 도시 초기화
            customCityInput.value = ""; // 입력 필드 초기화
        }

        // 아코디언 상태 복원 처리
        whereAccordion.addEventListener("click", () => {
            const isCurrentlyOpen = whereAccordion.classList.contains("active");

            if (isCurrentlyOpen) {
                let exist = false;

                // 기존 선택 복원
                cityItems.forEach((city) => {
                    const cityId = city.id.replace("city-", "").replace("overseas-", "");
                    if (cityId === originalCity) {
                        city.classList.add("active");
                        clearCustomCity();
                        currentCity = originalCity;
                        exist = true;
                    } else {
                        city.classList.remove("active");
                    }
                });

                if (!exist) {
                    currentCity = originalCity;
                    setCustomCity(originalCity);
                }

                updateUICity();
            }
        });

    </script>

    <script>
        // 좋은 숙소 찾아줘 이벤트
        const moongcledealConfirmBtn = document.querySelector('#moongcledealConfirmBtn');

        const companionTags = <?= json_encode($data['companionTags']); ?>;
        const cityTags = <?= json_encode($data['cityTags']); ?>;
        const petTags = <?= json_encode($data['petDetailTags']); ?>;
        const overseasTags = <?= json_encode($data['overseasTags']); ?>;

        let selectedTags = [];
        let confirmedTasteTags = [];
        let priorityItems = [];
        let finalSelected = {
            days: selectedDays,
            personnel: currentPersonnel,
            companion: null,
            pet: {
                size: null,
                weight: null,
                counts: null
            },
            city: null,
            taste: []
        };

        const tastePriorityOrder = [
            'swimming_pool',
            'large_rooms_for_5_or_more_people',
            'private_house_type',
            'kids_playroom',
            'family_room',
            'value_for_money_important',
            'barbecue_area',
        ];

        function getPriorityItemsFromConfirmedTags(confirmedTags) {
            return tastePriorityOrder
                .map(machineName => {
                    const tag = confirmedTags.find(tag => tag.tag_machine_name === machineName);
                    if (tag) {
                        return {
                            tag_name: tag.tag_name,
                            tag_machine_name: tag.tag_machine_name
                        };
                    }
                    return null;
                })
                .filter(Boolean);
        }

        // 선택된 taste 태그들을 수집
        function updateConfirmedTasteTagsAsync() {
            return new Promise(resolve => {
                setTimeout(() => {
                    const activeTasteLis = document.querySelectorAll('.select__wrap.multi-select li.active');

                    const selectedTasteTags = Array.from(activeTasteLis).map(li => ({
                        tag_machine_name: li.dataset.tasteMachineName,
                        tag_name: li.dataset.tagName,
                        section: li.dataset.section
                    }));

                    confirmedTasteTags = selectedTasteTags;
                    selectedTags = selectedTasteTags;
                    finalSelected.taste = confirmedTasteTags;

                    priorityItems = getPriorityItemsFromConfirmedTags(confirmedTasteTags);

                    resolve(); 
                }, 0);
            });
        }
 
        document.querySelectorAll('.select__wrap.multi-select li').forEach(li => {
            li.addEventListener('click', function (e) {
                setTimeout(() => {
                    updateConfirmedTasteTagsAsync();
                }, 0);
            });
        });

        async function displaySelectedTags(type) {
            const startButton = document.querySelector('#startMoongcledeal');
            // startButton.disabled = true;

            let selectTagWrap = '';
            if (type === 'web') {
                selectTagWrap = document.querySelector('#alertWeb .select-tag__wrap');
            } else {
                selectTagWrap = document.querySelector('#alertApp .select-tag__wrap');
            }
            selectTagWrap.innerHTML = '';

            const encodedTags = await fetchEncodedTags(selectedTags);

            selectedTags.forEach(tag => {
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

            startButton.setAttribute('data-encoded-tags', encodedTags);
            startButton.disabled = false;
        }

        moongcledealConfirmBtn.addEventListener('click', async () => {

            <?php if ($_ENV['APP_ENV'] == 'production') : ?>
            if (deviceType !== 'app') return;
            <?php endif; ?>

            await updateConfirmedTasteTagsAsync();

            if (confirmedTasteTags.length === 0) {
                fnToastPop('toastUndefined');
            }

            let matchedCompanionTag = null;
            let matchedPetTag = {
                size: null,
                weight: null,
                counts: null
            };
            let matchedCityTag = null;

            if (currentCompanion) {
                matchedCompanionTag = companionTags.find(tag => tag.tag_machine_name === currentCompanion);
            }

            if (currentPetSize) {
                matchedPetTag.size = petTags.size.find(tag => tag.tag_machine_name === currentPetSize) || null;
            }
            if (currentPetWeight) {
                matchedPetTag.weight = petTags.weight.find(tag => tag.tag_machine_name === currentPetWeight) || null;
            }
            if (currentPetCount) {
                matchedPetTag.counts = petTags.counts.find(tag => tag.tag_machine_name === currentPetCount) || null;
            }

            if (currentCity) {
                matchedCityTag = cityTags.find(tag => tag.tag_machine_name === currentCity)
                    || overseasTags.find(tag => tag.tag_machine_name === currentCity);

                if (matchedCityTag) {
                    matchedCityTag.type = cityTags.some(tag => tag.tag_machine_name === currentCity) ? 'domestic' : 'overseas';
                } else {
                    matchedCityTag = {
                        type: 'custom',
                        tag_name: currentCity
                    };
                }
            }

            finalSelected.personnel = currentPersonnel;
            finalSelected.companion = matchedCompanionTag;
            finalSelected.pet = matchedPetTag;
            finalSelected.city = matchedCityTag;

            if (confirmedTasteTags.length === 0) {
                fnToastPop('toastUndefined');
                return;
            }

            try {
                const response = await fetch('/api/moongcledeal/store-main', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        selected: finalSelected,
                        priority: priorityItems
                    })
                });

                if (!response.ok) {
                    throw new Error('API 요청 실패: ' + response.statusText);
                }

                const result = await response.json();

                if (result) {
                    // 로딩
                    moongcledealLoading.style.display = 'flex';

                    // // 5초 후에 페이지 이동
                    setTimeout(() => {
                        window.location.href = '/moongcledeals';
                    }, 5000);

                    <?php if ($_ENV['ANALYTICS_ENV'] == 'production' || $_ENV['ANALYTICS_ENV'] == 'staging') : ?>
                        window.dataLayer.push({
                            event: "complete_deal",
                        });
                    <?php endif; ?>
                } else {
                    console.error('응답 없음');
                }
            } catch (error) {
                console.error('API 요청 중 오류:', error);
            }
    });
    </script>

    <script>
        $('.custom-input').click(function() {
            if ($(this).parents().hasClass('select__wrap')) {
                $(this).addClass('active');
                $(this).parents().next('.input__wrap').slideDown();
                $(this).siblings().click(function() {
                    $('.input__wrap').slideUp();
                })
            } else {
                $(this).toggleClass('active');
                $(this).parents().next('.input__wrap').slideToggle();
            }
        });
    </script>

    <script>   
        const deviceType = '<?= $deviceType; ?>';

        const stayTasteTagsAll = <?= json_encode($stayTasteTags); ?>;

        async function displaySelectedTagsRandom(type) {
            const startButton = document.querySelector('#startMoongcledeal');
            // startButton.disabled = true;

            let selectTagWrap = '';
            if (type === 'web') {
                selectTagWrap = document.querySelector('#alertWeb .select-tag__wrap');
            } else {
                selectTagWrap = document.querySelector('#alertApp .select-tag__wrap');
            }
            selectTagWrap.innerHTML = ''; // 기존 내용을 초기화

            const randomTags = stayTasteTagsAll
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

                if (selectedTags.length === 0) {
                    fnToastPop('toastUndefined');
                    openPopButtonApp.classList.remove('fnOpenPop');    
                } else {
                    displaySelectedTags('app');
                    openPopButtonApp.classList.add('fnOpenPop');
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
            const startButton = document.querySelector('#startMoongcledeal');
            // startButton.disabled = true;

            let copyTags = '';

            if (index == 0) { 
                copyTags = [{
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }, {
                    tagName: '아이와',
                    tagMachineName: 'with_kids'
                }];
            } else if (index == 1) {
                copyTags = [{
                    tagName: '수영장',
                    tagMachineName: 'swimming_pool'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 2) {
                copyTags = [{
                    tagName: '자연과 함께',
                    tagMachineName: 'with_nature'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 3) {
                copyTags = [{
                    tagName: '해수욕장 주변',
                    tagMachineName: 'near_beach'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 4) {
                copyTags = [{
                    tagName: '캐릭터룸 보유',
                    tagMachineName: 'character_rooms_available'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 5) { 
                copyTags = [{
                    tagName: '키즈펜션',
                    tagMachineName: 'kids_friendly_pension'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 6) {
                copyTags = [{
                    tagName: '가성비 중요',
                    tagMachineName: 'value_for_money_important'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 7) { 
                copyTags = [{
                    tagName: '글램핑',
                    tagMachineName: 'glamping'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 8) {
                copyTags = [{
                    tagName: '한옥',
                    tagMachineName: 'hanok_traditional_house'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } else if (index == 9) { 
                copyTags = [{
                    tagName: '대형 객실 보유(5인+)',
                    tagMachineName: 'large_rooms_for_5_or_more_people'
                }, {
                    tagName: '아이와 갈만한 곳',
                    tagMachineName: 'places_to_visit_with_kids'
                }];
            } 

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

        document.querySelectorAll('.recommend-stay').forEach((button, index) => {
            button.addEventListener('click', function () {
                displayCopyRecommendTags(index); // 인덱스 기반으로 호출
            });
        });

        document.querySelectorAll('.recommend-tag-copy').forEach(function(element) {
            element.addEventListener('click', function() {
                displayCopyRecommendTags(currentRecommendTagIndex); // 함수 호출 (필요한 파라미터를 전달)
            });
        });
    </script>

    <script>
        // 아이와 갈만한 숙소: 지도로 한 눈에 보기
        function clickSearchMap(query, region, tagName) {

            const queryParams = new URLSearchParams({
                text: query,
                categoryType: region,
                tagName: tagName
            });
            window.location.href = `/search-map?${queryParams.toString()}`;
        }
    </script>

</body>

</html>