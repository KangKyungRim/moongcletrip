<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$selectedTags = $data['selectedTags'];
$companionTags = $data['companionTags'];
$petDetailTags = $data['petDetailTags'];
$cityTags = $data['cityTags'];
$overseasTags = $data['overseasTags'];
$travelTasteTags = $data['travelTasteTags'];
$eventTags = $data['eventTags'];
$stayTasteTags = $data['stayTasteTags'];
$stayTypeTags = $data['stayTypeTags'];
$petFacilityTags = $data['petFacilityTags'];
$stayBrandTags = $data['stayBrandTags'];
$moongcledeal = $data['moongcledeal'];

$newStayTasteTags = $data['newStayTasteTags'];
$newTravelTasteTags = $data['newTravelTasteTags'];
$newStayTypeTags = $data['newStayTypeTags'];

$allTasteTags = array_merge(
    $newStayTasteTags,
    $newTravelTasteTags,
    $newStayTypeTags
);

shuffle($allTasteTags);
$randomTasteTags = array_slice($allTasteTags, 0, 10);

$selectedDays = [];
$selectedPersonnel = null;

if ($data['existMoongcledeal']) {
    if (!empty($moongcledeal->selected['days'][0])) {
        $selectedDays = $moongcledeal->selected['days'][0];
    }
    if (!empty($moongcledeal->selected['personnel'])) {
        $selectedPersonnel = $moongcledeal->selected['personnel'];
    }

    if (!empty($moongcledeal->selected['companion'])) {
        $selectedTags[] = $moongcledeal->selected['companion'];
    }

    if (!empty($moongcledeal->selected['pet']['size'])) {
        $selectedTags[] = $moongcledeal->selected['pet']['size'];
    }

    if (!empty($moongcledeal->selected['pet']['weight'])) {
        $selectedTags[] = $moongcledeal->selected['pet']['weight'];
    }

    if (!empty($moongcledeal->selected['pet']['counts'])) {
        $selectedTags[] = $moongcledeal->selected['pet']['counts'];
    }

    if (!empty($moongcledeal->selected['city'])) {
        $selectedTags[] = $moongcledeal->selected['city'];
    }

    if (!empty($moongcledeal->selected['taste'])) {
        $selectedTags = array_merge($selectedTags, $moongcledeal->selected['taste']);
    }
}

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

        // 연도 구분을 위해 ul 닫기
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

$priority = $moongcledeal->priority;

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
                <button class="btn-close" onclick="gotoMoongcledealDetail('<?= $moongcledeal->moongcledeal_idx; ?>')"><span class="blind">닫기</span></button>
                <h2 class="header-tit__center">뭉클딜 편집</h2>
            </div>
        </header>

        <div class="container__wrap mkcle-create__wrap">
            <section class="layout__wrap bg-gray">
                <div class="recommend-list__wrap type-white">
                    <div class="recommend-list">
                        <p class="ft-xxs">생성일: <?= date('Y년 m월 d일', strtotime($moongcledeal->created_at)); ?></p>
                        <?php foreach ($priority as $tag) : ?>
                            <div class="item">
                                <img src="/uploads/tags/<?= $tag['tag_machine_name']; ?>.png<?= '?v='.$_ENV['VERSION']; ?>" alt="">
                                <span><?= $tag['tag_name']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="box-white__list accordion__wrap">
                    <!-- 언제가나요? -->
                    <div class="box-white__wrap accordion__list">
                        <div id="whenAccordion" class="accordion__tit">
                            <div class="tit__wrap">
                                <p class="ft-default">언제 가나요?</p>
                                <!-- <p class="ft-xxs">다중 선택이 가능해요.</p> -->
                            </div>
                            <button id="dayBeforeSelected" class="btn-sm__line">미정</button>

                            <p id="daySelected" class="txt-status__complete hidden">선택 완료!</p>
                            <!-- <p id="dayNotSelected" class="txt-status__pending hidden">미정</p> -->

                        </div>

                        <!-- 선택 후 -->
                        <div id="selectedTagsContainer">
                        </div>
                        <!-- //선택 후 -->

                        <div class="accordion__con">
                            <!-- 날짜 선택 -->
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

                                        <div class="btn__wrap">
                                            <button class="btn-full__line selectDaysUndefined">미정</button>
                                            <button class="btn-full__black selectDays disabled" disabled>선택</button>
                                        </div>
                                    </div>
                                    <!-- //날짜 선택 탭 -->
                                    <!-- 월 선택 탭 -->
                                    <div class="tab-contents">
                                        <?= generateMonthSelection($startDate, $monthsToGenerate); ?>
                                        <div class="btn__wrap">
                                            <button class="btn-full__line selectDaysUndefined">미정</button>
                                            <button class="btn-full__black selectMonth disabled" disabled>선택</button>
                                        </div>
                                    </div>
                                    <!-- //월 선택 탭 -->
                                </div>
                            </div>
                            <!-- //날짜 선택 -->
                        </div>
                    </div>
                    <!-- //언제가나요? -->
                    <!-- 누구와 가나요? -->
                    <div class="box-white__wrap accordion__list">
                        <div id="whoAccordion" class="accordion__tit">
                            <div class="tit__wrap">
                                <p class="ft-default">누구와 가나요?</p>
                            </div>
                            <button id="companionBeforeSelected" class="btn-sm__line">미정</button>

                            <p id="companionSelected" class="txt-status__complete hidden">선택 완료!</p>

                        </div>
                        <div class="accordion__con">
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

                            <hr class="divide__small">

                            <div class="select__wrap type-img single-select">
                                <ul>
                                    <?php foreach ($companionTags as $companionTag) : ?>
                                        <li id="companion-<?= $companionTag['tag_machine_name']; ?>" data-companion-machine-name="<?= $companionTag['tag_machine_name']; ?>">
                                            <a>
                                                <img src="/uploads/tags/<?= $companionTag['tag_machine_name']; ?>.png<?= '?v='.$_ENV['VERSION']; ?>" alt="">
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
                                            <div class="select__wrap type-img single-select">
                                                <ul>
                                                    <?php foreach ($petDetailTags['size'] as $petDetailTag) : ?>
                                                        <li id="pet-size-<?= $petDetailTag['tag_machine_name']; ?>" data-pet-size-machine-name="<?= $petDetailTag['tag_machine_name']; ?>">
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
                                            <div class="select__wrap type-img single-select">
                                                <ul>
                                                    <?php foreach ($petDetailTags['weight'] as $petDetailTag) : ?>
                                                        <li id="pet-weight-<?= $petDetailTag['tag_machine_name']; ?>" data-pet-weight-machine-name="<?= $petDetailTag['tag_machine_name']; ?>">
                                                            <a>
                                                                <img src="/uploads/tags/<?= $petDetailTag['tag_machine_name']; ?>.png?v=1" alt="">
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
                                            <div class="select__wrap type-img single-select">
                                                <ul>
                                                    <?php foreach ($petDetailTags['counts'] as $petDetailTag) : ?>
                                                        <li id="pet-count-<?= $petDetailTag['tag_machine_name']; ?>" data-pet-count-machine-name="<?= $petDetailTag['tag_machine_name']; ?>">
                                                            <a>
                                                                <img src="/uploads/tags/<?= $petDetailTag['tag_machine_name']; ?>.png" alt="">
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

                            <div class="btn__wrap mt30">
                                <button id="selectCompanionUndefined" class="btn-full__line">미정</button>
                                <button id="selectCompanion" class="btn-full__black disabled" disabled>선택</button>
                            </div>
                        </div>
                    </div>
                    <!-- //누구와 가나요? -->
                    <!-- 어디로 가나요? -->
                    <div class="box-white__wrap accordion__list">
                        <div id="whereAccordion" class="accordion__tit">
                            <div class="tit__wrap">
                                <p class="ft-default">어디로 가나요?</p>
                            </div>
                            <button id="cityBeforeSelected" class="btn-sm__line">미정</button>

                            <p id="citySelected" class="txt-status__complete hidden">선택 완료!</p>

                        </div>
                        <div class="accordion__con">
                            <!-- 지역 선택 -->
                            <div class="tab__wrap tab-switch__wrap">
                                <ul class="tab__inner">
                                    <li class="tab-switch__con active">
                                        <a>국내</a>
                                    </li>
                                    <li class="tab-switch__con">
                                        <a>해외</a>
                                    </li>
                                </ul>
                                <div class="tab-contents__wrap">
                                    <!-- 국내 선택 탭 -->
                                    <div class="tab-contents active">
                                        <div class="select__wrap col-3 single-select">
                                            <ul>
                                                <?php foreach ($cityTags as $cityTag) : ?>
                                                    <li id="city-<?= $cityTag['tag_machine_name']; ?>" data-city-machine-name="<?= $cityTag['tag_machine_name']; ?>">
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
                                                    <input id="customCityInput" type="text" class="input-default" placeholder="도시를 입력해주세요"  style="width: auto;">
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
                                        <div class="select__wrap col-3 single-select">
                                            <ul>
                                                <?php foreach ($overseasTags as $overseasTag) : ?>
                                                    <li id="overseas-<?= $overseasTag['tag_machine_name']; ?>" data-city-machine-name="<?= $overseasTag['tag_machine_name']; ?>">
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
                                <div class="btn__wrap">
                                    <button id="selectCityUndefined" class="btn-full__line">미정</button>
                                    <button id="selectCity" class="btn-full__black disabled" disabled>선택</button>
                                </div>
                            </div>
                            <!-- //지역 선택 -->
                        </div>
                    </div>
                    <!-- //어디로 가나요? -->
                    <!-- 숙소 선호 조건을 알려주세요! -->
                    <div class="box-white__wrap">
                        <div class="flex-between">
                            <div class="tit__wrap">
                                <p class="ft-default">숙소 선호 조건을 알려주세요!</p>
                            </div>
                            <p id="selectedTasteText" class="txt-status__complete hidden">선택 완료!</p>
                            <p id="undefinedTasteText" class="btn-sm__line">미정</p>
                        </div>

                        <!-- 여행 취향 탭 -->
                        <div class="tab__wrap tab-switch__wrap">
                            <ul class="tab__inner">
                                <li class="tab-switch__con active"  style="margin-right:0rem;">
                                    <a>랜덤 추천</a>
                                </li>
                                <li class="tab-switch__con">
                                    <a>직접 선택</a>
                                </li>
                            </ul>
                            <div class="tab-contents__wrap">
                                <!-- 랜덤추천 탭 콘텐츠 -->
                                <div class="tab-contents active">
                                    <div id="selectedRandomTags" class="select__wrap type-img favorite-tag hidden">
                                        <ul>

                                        </ul>
                                    </div>

                                    <hr id="randomTagDivider" class="divide__small hidden">

                                    <div id="randomTagContainer" class="select__wrap type-img">
                                        <ul>
                                            <?php foreach ($randomTasteTags as $randomTasteTag) : ?>
                                                <li data-random-taste-machine-name="<?= $randomTasteTag['tag_machine_name']; ?>" data-section="<?= $randomTasteTag['tag_section']; ?>">
                                                    <a>
                                                        <img src="/uploads/tags/<?= $randomTasteTag['tag_machine_name']; ?>.png<?= '?v='.$_ENV['VERSION']; ?>" alt="">
                                                        <span><?= $randomTasteTag['tag_name']; ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="flex-center">
                                        <button id="refreshRandomTags" class="btn-txt btn-txt__default">다른 추천 받아보기 <i class="ico ico-refresh"></i></button>
                                    </div>

                                </div>
                                <!-- //랜덤추천 탭 콘텐츠 -->
                                <!-- 직접 선택 탭 콘텐츠 -->
                                <div class="tab-contents">
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
                                <!-- //직접 선택 탭 콘텐츠 -->
                            </div>
                            <!-- <div class="btn__wrap">
                                <button id="resetTaste" class="btn-full__line">초기화</button>
                                <button id="confirmTaste" class="btn-full__black disabled" disabled>선택</button>
                            </div> -->
                        </div>
                        <!-- //여행 취향 탭 -->
                    </div>
                    <!-- //숙소 선호 조건을 알려주세요! -->
                </div>
            </section>

            <!-- 알림 받지 않기 -->
            <section class="layout__wrap">
                <!-- <div class="box-gray__wrap accordion__wrap">
                    <div class="accordion__list">
                        <div class="accordion__tit">
                            <p class="ft-default">받고 싶지 않은 태그 알림 설정하기</p>
                            <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                        </div>
                        <div class="accordion__con">
                            <div class="flex-start">
                                <p class="ft-xxs">최대 3개까지</p>
                                <p class="ft-xxs txt-primary"><span>0</span>/3</p>
                            </div>

                            <div class="select__wrap type-line multi-select">
                                <ul>
                                    <li><a>#8월 2일</a></li>
                                    <li><a>#2명</a></li>
                                    <li><a>#도심 속 호캉스</a></li>
                                    <li><a>#여유로운 힐링</a></li>
                                    <li><a>#가성비 중요</a></li>
                                    <li><a>#야경즐기기</a></li>
                                </ul>
                            </div>
                            <hr class="divide__small">
                            <div class="select__wrap type-line type-disabled">
                                <ul>
                                    <li><a>#전망좋은 곳</a></li>
                                    <li><a>#제주</a></li>
                                    <li><a>#연인과</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="checkbox__wrap">
                    <!-- <div class="checkbox">
                        <input type="checkbox" id="representative" <?= $moongcledeal->represent ? 'checked' : '' ?> />
                        <label for="representative">
                            <span class="ft-default ft-bold">대표 뭉클딜로 설정</span>
                            <span class="ft-xxs text-gray">
                                대표 뭉클딜로 설정하면 홈화면에서 가장 쉽고, <br>
                                빠르게 확인할 수 있어요
                            </span>
                        </label>
                    </div> -->
                    <div class="checkbox">
                        <input type="checkbox" id="stop" />
                        <label for="stop">
                            <span class="ft-default ft-bold">그만 받기</span>
                        </label>
                    </div>
                </div>
            </section>
            <!-- //알림 받지 않기 -->

            <!-- 하단 버튼 영역 -->
            <div id="gotoNextStepContainer" class="bottom-fixed__wrap">
                <div class="btn__wrap">
                    <!-- <button class="btn-full-secondary">취소</button> -->
                    <button id="gotoNextStep" class="btn-full__primary">다음</button>
                </div>
            </div>
            <!-- //하단 버튼 영역 -->
        </div>

        <!-- 바텀 팝업 -->
        <div id="popupStayTaste" class="layerpop__wrap">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <p class="title">숙소 취향</p>
                    <a class="fnClosePop"><i class="ico ico-close"></i></a>
                </div>
                <div class="layerpop__contents">
                    <div class="select__wrap type-img">
                        <ul>
                            <?php foreach ($newStayTasteTags as $newStayTasteTag) : ?>
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
                            <?php foreach ($newTravelTasteTags as $newTravelTasteTag) : ?>
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
                            <?php foreach ($newStayTypeTags as $newStayTypeTag) : ?>
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
        <!-- //바텀 팝업 -->

        <!-- 토스트팝업 -->
        <div id="toastPopup" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p>언제 갈지 선택해주세요.</p>
            </div>
        </div>
        <!-- //토스트팝업 -->

        <!-- 토스트팝업(준비 중입니다) -->
        <div id="toastPrepare" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p>준비 중입니다.</p>
            </div>
        </div>
        <!-- //토스트팝업(준비 중입니다) -->

        <div id="toastUndefined" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p>하나 이상의 태그를 선택해주세요.</p>
            </div>
        </div>

        <div id="alertBack" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">뭉클딜 작성을 중단하시겠어요?</p>
                        <p class="desc">
                            작성 중인 뭉클딜은 이어서 진행하실 수 있도록 임시 저장됩니다.
                        </p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button id="tempSave" class="btn-full__secondary">중단하기</button>
                        <button class="btn-full__primary fnClosePop">이어하기</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 알럿 팝업 -->
        <div id="stopAlert" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">해당 뭉클딜을 그만 받으시겠습니까?</p>
                        <p class="desc">
                            그만 받기를 선택하실 경우 해당 뭉클딜의 추천을 더 이상 받으실 수 없습니다. <br>
                            계속 진행하시겠습니까?
                        </p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button id="moongcledealStop" class="btn-full__secondary">그만 받기</button>
                        <button id="moongcledealStopCancel" class="btn-full__primary fnClosePop">취소</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //알럿 팝업 -->
    </div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        const selectedTags = <?= json_encode($selectedTags); ?>;

        /** 
         * 
         * 언제 가나요?
         * 
         */
        const initialDaysSetting = <?= json_encode($selectedDays); ?>;

        let selectedDates = null;
        let selectedMonth = null;
        let selectedDays = [];

        const selectedTagsContainer = document.getElementById("selectedTagsContainer");
        const dayBeforeSelected = document.getElementById("dayBeforeSelected");
        const daySelected = document.getElementById("daySelected");
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

        // 초기 설정 적용
        function initializeSelectionDays(setting) {
            if (setting.type === 'period') {
                const [startDate, endDate] = setting.dates.split('~');
                if (startDate && endDate) {
                    selectedDates = [new Date(startDate), new Date(endDate)];
                    selectedDays[0] = {
                        type: 'period',
                        dates: setting.dates
                    };

                    // Flatpickr 달력 세팅
                    calendar.setDate(selectedDates);
                    updateButtonState(selectDaysButton, true);
                    updateSelectionTag(`#${formatDate(startDate)}~${formatDate(endDate)}`);
                    toggleSelectionStatus(true);
                }
            } else if (setting.type === 'month') {
                selectedMonth = setting.dates;
                selectedDays[0] = {
                    type: 'month',
                    dates: setting.dates
                };

                // 해당 월을 선택된 상태로 표시
                const monthElement = Array.from(document.querySelectorAll("[id^='month-']")).find(el => el.textContent === setting.dates);
                if (monthElement) {
                    monthElement.classList.add("active");
                }
                updateButtonState(selectMonthButton, true);
                updateSelectionTag(`#${setting.dates}`);
                toggleSelectionStatus(true);
            }
        }

        // 초기 값 설정
        initializeSelectionDays(initialDaysSetting);

        // "미정" 버튼 클릭
        document.querySelectorAll(".selectDaysUndefined").forEach((button) => {
            button.addEventListener("click", () => {
                resetSelections();
                toggleAccordionById('whenAccordion');
            });
        });

        // 날짜 선택 버튼 클릭
        selectDaysButton.addEventListener("click", () => {
            if (selectedDates && selectedDates.length === 2) {
                const startDate = formatDate(selectedDates[0]);
                const endDate = formatDate(selectedDates[1]);
                updateSelectionTag(`#${startDate}~${endDate}`);
                toggleSelectionStatus(true);
                toggleAccordionById('whenAccordion');

                selectedDays[0] = {
                    type: 'period',
                    dates: `${startDate}~${endDate}`
                };
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
                updateSelectionTag(`#${selectedMonth}`);
                toggleSelectionStatus(true);
                toggleAccordionById('whenAccordion');
                selectedDays[0] = {
                    type: 'month',
                    dates: `${selectedMonth}`
                };
            }
        });

        // 선택 상태 업데이트
        function updateSelectionTag(tag) {
            selectedTagsContainer.innerHTML = `
            <div class="mytag-con__wrap">
                <div class="mytag-con">
                    <span class="ft-xxs">${tag}</span>
                    <a href="#" onclick="resetSelections()"><i class="ico ico-tag__delete"></i></a>
                </div>
            </div>
        `;
        }

        // 선택 상태 초기화
        function resetSelections() {
            resetDateSelection();
            resetMonthSelection();
            toggleSelectionStatus(false);
            selectedTagsContainer.innerHTML = '';
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

        // 상태에 따라 표시 업데이트
        function toggleSelectionStatus(isSelected) {
            dayBeforeSelected.classList.toggle("hidden", isSelected);
            daySelected.classList.toggle("hidden", !isSelected);
            // dayNotSelected.classList.toggle("hidden", isSelected);
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
         * 누구와 가나요?
         * 
         */
        let originalPersonnel = <?= json_encode(!empty($selectedPersonnel) ? $selectedPersonnel : null) ?>;
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

        const personnelItems = document.querySelectorAll("[id^='personnel-']");
        const companionItems = document.querySelectorAll(".select__wrap li[data-companion-machine-name]");
        const petItemsSize = document.querySelectorAll(".select__wrap li[data-pet-size-machine-name]");
        const petItemsWeight = document.querySelectorAll(".select__wrap li[data-pet-weight-machine-name]");
        const petItemsCount = document.querySelectorAll(".select__wrap li[data-pet-count-machine-name]");
        const petDetailTagsWrap = document.querySelector(".companionAnimalsWrap");
        const companionBeforeSelected = document.getElementById("companionBeforeSelected");
        const companionSelected = document.getElementById("companionSelected");
        const selectCompanionButton = document.getElementById("selectCompanion");
        const selectCompanionUndefinedButton = document.getElementById("selectCompanionUndefined");
        const whoAccordion = document.getElementById("whoAccordion");

        // 초기화
        function initializeSelectionCompanion() {
            selectedTags.forEach((tag) => {
                const matchingCompanion = Array.from(companionItems).find(
                    (companion) => companion.dataset.companionMachineName === tag.tag_machine_name
                );

                if (matchingCompanion) {
                    matchingCompanion.classList.add("active");
                    // 현재 선택 상태 업데이트
                    currentCompanion = tag.tag_machine_name;
                    originalCompanion = tag.tag_machine_name;
                }
            });

            // UI 업데이트
            updateUICompanion();

            if (currentCompanion === "pet_friendly") {
                petDetailTagsWrap.style.display = "block";
            } else {
                petDetailTagsWrap.style.display = "none";
                
                petItemsSize.forEach(item => {
                    if (item.classList.contains('active')) {
                        item.classList.remove('active');
                    }
                });

                petItemsWeight.forEach(item => {
                    if (item.classList.contains('active')) {
                        item.classList.remove('active');
                    }
                });

                petItemsCount.forEach(item => {
                    if (item.classList.contains('active')) {
                        item.classList.remove('active');
                    }
                });
            }
        }

        // 초기화
        function initializeSelectionPet() {
            currentPetSize = null;
            currentPetWeight = null;
            currentPetCount = null;
            
            selectedTags.forEach((tag) => {
                // 크기 (size) 태그 찾기
                const matchingSize = Array.from(petItemsSize).find(
                    (pet) => pet.dataset.petSizeMachineName === tag.tag_machine_name
                );
                if (matchingSize) {
                    matchingSize.classList.add("active");
                    currentPetSize = tag.tag_machine_name
                }

                // 무게 (weight) 태그 찾기
                const matchingWeight = Array.from(petItemsWeight).find(
                    (pet) => pet.dataset.petWeightMachineName === tag.tag_machine_name
                );
                if (matchingWeight) {
                    matchingWeight.classList.add("active");
                    currentPetWeight = tag.tag_machine_name
                }

                // 마릿수 (counts) 태그 찾기
                const matchingCount = Array.from(petItemsCount).find(
                    (pet) => pet.dataset.petCountMachineName === tag.tag_machine_name
                );
                if (matchingCount) {
                    matchingCount.classList.add("active");
                    currentPetCount = tag.tag_machine_name
                }
            });

            originalPetSize = currentPetSize;
            originalPetWeight = currentPetWeight;
            originalPetCount = currentPetCount;

            // UI 업데이트
            updateUICompanion();
        }

        // 초기화
        function initializeSelectionPersonnel() {
            // 초기 personnel 설정
            if (originalPersonnel) {
                selectItem(personnelItems, "personnel", originalPersonnel);
            }

            // UI 업데이트
            updateUICompanion();
        }

        // UI 업데이트
        function updateUICompanion() {
            const isSelected = currentPersonnel || currentCompanion;
            companionBeforeSelected.classList.toggle("hidden", isSelected);
            companionSelected.classList.toggle("hidden", !isSelected);

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
                updateUICompanion();
            });
        });

        // 크기 (size) 선택 처리
        petItemsSize.forEach(item => {
            item.addEventListener("click", () => {
                const tagMachineName = item.id.replace("pet-size-", "");
                
                currentPetSize = tagMachineName; 
                updateUICompanion();
            });
        });

        // 무게 (weight) 선택 처리
        petItemsWeight.forEach(item => {
            item.addEventListener("click", () => {
                const tagMachineName = item.id.replace("pet-weight-", "");
                            
                currentPetWeight = tagMachineName;  
                updateUICompanion();
            });
        });

        // 마릿수 (counts) 선택 처리
        petItemsCount.forEach(item => {
            item.addEventListener("click", () => {
                const tagMachineName = item.id.replace("pet-count-", "");
                
                currentPetCount = tagMachineName; 
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
        });

        // 미정 버튼
        selectCompanionUndefinedButton.addEventListener("click", () => {
            originalPersonnel = null;
            originalCompanion = null;
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

        initializeSelectionPersonnel();
        initializeSelectionCompanion();
        initializeSelectionPet();

        /**
         * 
         * 어디로 가나요? (국내/해외 통합)
         * 
         */
        let originalCity = null;
        let currentCity = null;

        // 도시 태그 DOM 요소
        const cityItems = document.querySelectorAll(".select__wrap li[data-city-machine-name]");
        const cityBeforeSelected = document.getElementById("cityBeforeSelected");
        const citySelected = document.getElementById("citySelected");
        const selectCityButton = document.getElementById("selectCity");
        const selectCityUndefinedButton = document.getElementById("selectCityUndefined");
        const whereAccordion = document.getElementById("whereAccordion");

        const customCityContainer = document.getElementById("customCity");
        const customCityInput = document.getElementById("customCityInput");

        // 초기화
        function initializeSelectionCity() {
            // 전달받은 태그 배열에서 도시 태그와 일치하는 항목 찾기
            selectedTags.forEach((tag) => {
                const matchingCity = Array.from(cityItems).find(
                    (city) => city.dataset.cityMachineName === tag.tag_machine_name
                );

                if (matchingCity) {
                    // 일치하는 도시 태그를 선택
                    matchingCity.classList.add("active");
                    // 현재 선택 상태 업데이트
                    currentCity = tag.tag_machine_name;
                    originalCity = tag.tag_machine_name;
                }
            });

            // UI 업데이트
            updateUICity();
        }

        // UI 업데이트
        function updateUICity() {
            const isSelected = currentCity || originalCity;
            cityBeforeSelected.classList.toggle("hidden", isSelected);
            citySelected.classList.toggle("hidden", !isSelected);

            selectCityButton.disabled = !isSelected;
            selectCityButton.classList.toggle("disabled", !isSelected);
        }

        initializeSelectionCity();

        // 도시 선택 이벤트
        cityItems.forEach(item => {
            item.addEventListener("click", () => {
                const isDomestic = item.id.startsWith("city-");
                const isOverseas = item.id.startsWith("overseas-");

                // 도시 식별자 가져오기
                const cityId = isDomestic ? item.id.replace("city-", "") : item.id.replace("overseas-", "");

                // 기존 선택 초기화
                clearCustomCity();
                cityItems.forEach((city) => city.classList.remove("active"));

                // 선택된 도시 설정
                item.classList.add("active");
                currentCity = cityId;

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
        });

        // "선택" 버튼 처리
        selectCityButton.addEventListener("click", () => {
            originalCity = currentCity;
            toggleAccordionById('whereAccordion');
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

        /**
         * 
         * 여행 취향
         * 
         */

        // 전체 태그 데이터 (PHP에서 JSON으로 변환해 넘겨줌)
        const allTasteTags = <?= json_encode($allTasteTags); ?>;

        // 선택된 태그를 관리하는 배열
        let selectedTasteTags = [];
        let confirmedTasteTags = [];

        let travelTasteTagsSelected = [];
        let eventTagsSelected = [];
        let stayTasteTagsSelected = [];
        let stayTypeTagsSelected = [];
        let petFacilityTagsSelected = [];
        let stayBrandTagsSelected = [];

        // DOM 요소 참조
        const selectedRandomTagsContainer = document.querySelector("#selectedRandomTags ul");
        const randomTagDivider = document.getElementById("randomTagDivider");
        const randomTagsContainer = document.querySelector("#randomTagContainer ul");
        const refreshRandomTagsButton = document.getElementById("refreshRandomTags");
        // const confirmTasteButton = document.getElementById("confirmTaste");
        // const resetTasteButton = document.getElementById("resetTaste");
        const selectedTasteText = document.getElementById("selectedTasteText");
        const undefinedTasteText = document.getElementById("undefinedTasteText");

        // 섹션별 태그 개수를 업데이트
        function updateSectionTagCount(section, count) {
            const sectionElement = document.querySelector(`span[data-section="${section}"]`);

            if (count == 0) {
                sectionElement.textContent = '';
            } else {
                sectionElement.textContent = `+${count}`;
            }
        }

        // 태그 추가 함수
        function addTag(tagName, machineName, section) {
            const sectionArray = getSectionArray(section);
            const exists = sectionArray.some(tag => tag.tag_machine_name === machineName);

            if (!exists) {
                sectionArray.push({
                    tag_machine_name: machineName,
                    tag_name: tagName
                });
                selectedTasteTags.push({
                    tag_machine_name: machineName,
                    tag_name: tagName,
                    section: section
                });
            }

            // 랜덤 추천 위쪽에 복제 추가
            replicateTagToSelectedArea(tagName, machineName);

            updateSectionTagCount(section, sectionArray.length);
            syncTagUI(machineName, true);
        }

        // 태그 제거 함수
        function removeTag(machineName, section) {
            const sectionArray = getSectionArray(section);
            const sectionIndex = sectionArray.findIndex(tag => tag.tag_machine_name === machineName);
            const globalIndex = selectedTasteTags.findIndex(tag => tag.tag_machine_name === machineName);

            if (sectionIndex !== -1) {
                sectionArray.splice(sectionIndex, 1);
            }
            if (globalIndex !== -1) {
                selectedTasteTags.splice(globalIndex, 1);
            }

            // 랜덤 추천 위쪽에서 제거
            removeTagFromSelectedArea(machineName);

            updateSectionTagCount(section, sectionArray.length);
            syncTagUI(machineName, false);
        }

        // 섹션 배열 반환 함수
        function getSectionArray(section) {
            switch (section) {
                case "travelTaste":
                    return travelTasteTagsSelected;
                case "event":
                    return eventTagsSelected;
                case "stayTaste":
                    return stayTasteTagsSelected;
                case "stayType":
                    return stayTypeTagsSelected;
                case "petFacility":
                    return petFacilityTagsSelected;
                case "stayBrand":
                    return stayBrandTagsSelected;
                default:
                    return [];
            }
        }

        // 랜덤 추천 태그 클릭 이벤트 핸들러
        function handleRandomTagClick(event) {
            const tagElement = event.currentTarget;
            const machineName = tagElement.getAttribute("data-random-taste-machine-name");
            const tagName = tagElement.querySelector("span").textContent.trim();
            const section = tagElement.getAttribute("data-section");

            if (tagElement.classList.contains("active")) {
                removeTag(machineName, section);
            } else {
                addTag(tagName, machineName, section);
            }

            updateSelectedRandomTagsUI();
        }

        // 직접 선택 태그 클릭 이벤트 핸들러
        function handleDirectTagClick(event) {
            const tagElement = event.currentTarget;
            const machineName = tagElement.getAttribute("data-taste-machine-name");
            const tagName = tagElement.querySelector("span").textContent.trim();
            const section = tagElement.getAttribute("data-section");

            if (tagElement.classList.contains("active")) {
                removeTag(machineName, section);
            } else {
                addTag(tagName, machineName, section);
            }

            // 모든 태그가 해제되었는지 확인
            if (selectedTasteTags.length === 0) {
                updateSelectedRandomTagsUI();
            }
        }

        // 태그 상태 동기화
        function syncTagUI(machineName, isSelected) {
            const randomTag = document.querySelector(`[data-random-taste-machine-name="${machineName}"]`);
            const directTag = document.querySelector(`[data-taste-machine-name="${machineName}"]`);

            if (randomTag) {
                randomTag.classList.toggle("active", isSelected);
            }
            if (directTag) {
                directTag.classList.toggle("active", isSelected);
            }
        }

        // 랜덤 추천 위쪽에 태그 복제
        function replicateTagToSelectedArea(tagName, machineName) {
            const exists = selectedRandomTagsContainer.querySelector(`[data-selected-taste-machine-name="${machineName}"]`);
            if (exists) return;

            const newTag = document.createElement("li");
            newTag.setAttribute("data-selected-taste-machine-name", machineName);
            newTag.innerHTML = `
                <a>
                <img src="/uploads/tags/${machineName}.png<?= '?v='.$_ENV['VERSION']; ?>" alt="">
                    <span>${tagName}</span>
                </a>
            `;
            newTag.classList.add("active");

            // 삭제 이벤트 추가
            newTag.querySelector("a").addEventListener("click", () => {
                const section = selectedTasteTags.find(tag => tag.tag_machine_name === machineName).section;
                removeTag(machineName, section);
            });

            selectedRandomTagsContainer.appendChild(newTag);
        }

        // 랜덤 추천 위쪽에서 태그 제거
        function removeTagFromSelectedArea(machineName) {
            const tagToRemove = selectedRandomTagsContainer.querySelector(`[data-selected-taste-machine-name="${machineName}"]`);
            if (tagToRemove) {
                tagToRemove.remove();
            }

            // 모든 태그가 제거되었는지 확인 후 UI 업데이트
            if (selectedTasteTags.length === 0) {
                updateSelectedRandomTagsUI();
            }
        }

        // 선택된 태그 UI 업데이트
        function updateSelectedRandomTagsUI() {
            const hasSelectedTags = selectedTasteTags.length > 0;
            selectedRandomTagsContainer.parentElement.classList.toggle("hidden", !hasSelectedTags);
            randomTagDivider.classList.toggle("hidden", !hasSelectedTags);

            // confirmTasteButton.classList.toggle("disabled", !hasSelectedTags);
            // confirmTasteButton.disabled = !hasSelectedTags;
        }

        // 랜덤 태그 10개 뽑기
        function getRandomTags(tags, count = 10) {
            const shuffled = [...tags].sort(() => Math.random() - 0.5);
            return shuffled.slice(0, count);
        }

        // 랜덤 태그 목록 갱신
        function updateRandomTags() {
            const randomTags = getRandomTags(allTasteTags);

            // 기존 태그 초기화
            randomTagsContainer.innerHTML = "";

            // 새로운 태그 추가
            randomTags.forEach(tag => {
                const tagElement = document.createElement("li");
                tagElement.setAttribute("data-random-taste-machine-name", tag.tag_machine_name);
                tagElement.setAttribute("data-section", tag.tag_section);
                tagElement.innerHTML = `
                    <a>
                        <img src="/uploads/tags/${tag.tag_machine_name}.png<?= '?v='.$_ENV['VERSION']; ?>" alt="">
                        <span>${tag.tag_name}</span>
                    </a>
                `;
                randomTagsContainer.appendChild(tagElement);

                // 태그 클릭 이벤트 추가
                tagElement.addEventListener("click", handleRandomTagClick);

                // 이전 선택된 태그와 비교해 활성화 상태 유지
                const isActive = selectedTasteTags.some(selected => selected.tag_machine_name === tag.tag_machine_name);
                if (isActive) {
                    tagElement.classList.add("active");
                }
            });
        }

        // 새로고침 버튼 클릭 이벤트
        refreshRandomTagsButton.addEventListener("click", () => {
            updateRandomTags();
        });

        // 초기화 버튼 클릭 이벤트
        // resetTasteButton.addEventListener("click", () => {
        //     confirmedTasteTags = [];
        //     selectedTasteTags = [];
        //     travelTasteTagsSelected = [];
        //     eventTagsSelected = [];
        //     stayTasteTagsSelected = [];
        //     stayTypeTagsSelected = [];
        //     stayBrandTagsSelected = [];

        //     const allRamdomTags = randomTagsContainer.querySelectorAll("[data-random-taste-machine-name]");
        //     allRamdomTags.forEach(tag => {
        //         tag.classList.remove("active");
        //     });
        //     const allTasteTags = document.querySelectorAll("[data-taste-machine-name]");
        //     allTasteTags.forEach(tag => {
        //         tag.classList.remove("active");
        //     });

        //     updateSectionTagCount("travelTaste", 0);
        //     updateSectionTagCount("event", 0);
        //     updateSectionTagCount("stayTaste", 0);
        //     updateSectionTagCount("stayType", 0);
        //     updateSectionTagCount("stayBrand", 0);

        //     selectedRandomTagsContainer.innerHTML = "";
        //     updateSelectedRandomTagsUI();

        //     selectedTasteText.classList.add('hidden');
        //     undefinedTasteText.classList.remove('hidden');
        // });

        // confirmTasteButton.addEventListener("click", () => {
        //     selectedTasteText.classList.remove('hidden');
        //     undefinedTasteText.classList.add('hidden');

        //     // 선택된 태그 확정
        //     confirmedTasteTags = [...selectedTasteTags];
        // });

        // 초기화
        function initialize() {
            randomTagsContainer.querySelectorAll("[data-random-taste-machine-name]").forEach(tag => {
                tag.addEventListener("click", handleRandomTagClick);
            });

            document.querySelectorAll("[data-taste-machine-name]").forEach(tag => {
                tag.addEventListener("click", handleDirectTagClick);
            });

            updateSelectedRandomTagsUI();
        }

        // 초기화 함수
        function initializeConfirmedTags() {
            // previousTags와 allTasteTags 비교
            selectedTags.forEach(previousTag => {
                const matchedTag = allTasteTags.find(
                    allTag => allTag.tag_machine_name === previousTag.tag_machine_name
                );

                if (matchedTag) {
                    // 일치하는 태그를 confirmedTasteTags에 추가
                    confirmedTasteTags.push(matchedTag);

                    // 태그를 선택된 상태로 처리
                    initializeTagSelection(matchedTag);

                    selectedTasteText.classList.remove('hidden');
                    undefinedTasteText.classList.add('hidden');
                }
            });

            // 선택된 태그 UI 업데이트
            updateSelectedRandomTagsUI();
        }

        // 태그 선택 상태 초기화
        function initializeTagSelection(tag) {
            const machineName = tag.tag_machine_name;
            const tagName = tag.tag_name;
            const section = tag.tag_section;

            // 랜덤 추천 및 직접 선택에서 매칭되는 태그 찾기
            const randomTag = document.querySelector(`[data-random-taste-machine-name="${machineName}"]`);
            const directTag = document.querySelector(`[data-taste-machine-name="${machineName}"]`);

            // 선택 상태로 설정
            addTag(tagName, machineName, section);

            // UI 동기화
            if (randomTag) randomTag.classList.add("active");
            if (directTag) directTag.classList.add("active");
        }

        // 초기 실행
        document.addEventListener("DOMContentLoaded", () => {
            initializeConfirmedTags(); // 이전 태그를 기준으로 초기화
            initialize(); // 기존 UI 초기화 작업
        });

        const gotoNextStepButton = document.getElementById("gotoNextStep");

        const companionTags = <?= json_encode($data['companionTags']); ?>;
        const cityTags = <?= json_encode($data['cityTags']); ?>;
        const petTags = <?= json_encode($data['petDetailTags']); ?>;
        const overseasTags = <?= json_encode($data['overseasTags']); ?>;

        gotoNextStepButton.addEventListener("click", async () => {
            confirmedTasteTags = [...selectedTasteTags];

            let matchedCompanionTag = null;
            let matchedPetTag = {
                size: null,
                weight: null,
                counts: null
            };
            let matchedCityTag = null;

            if (selectedDays.length == 0 && !currentPersonnel && !currentCompanion && !currentCity && confirmedTasteTags.length == 0) {
                fnToastPop('toastUndefined');
                return;
            }

            // 동기적으로 companion 값 확인
            if (currentCompanion) {
                matchedCompanionTag = companionTags.find(tag => tag.tag_machine_name === currentCompanion);
            }

            // pet 데이터 처리
            if (currentPetSize || currentPetWeight || currentPetCount) {

                if (currentPetSize) {
                    matchedPetTag.size = petTags.size.find(tag => tag.tag_machine_name === currentPetSize) || null;
                }

                if (currentPetWeight) {
                    matchedPetTag.weight = petTags.weight.find(tag => tag.tag_machine_name === currentPetWeight) || null;
                }

                if (currentPetCount) {
                    matchedPetTag.counts = petTags.counts.find(tag => tag.tag_machine_name === currentPetCount) || null;
                }

            }

            // city 값 처리
            if (currentCity) {
                matchedCityTag = cityTags.find(tag => tag.tag_machine_name === currentCity) ||
                    overseasTags.find(tag => tag.tag_machine_name === currentCity);

                if (matchedCityTag) {
                    matchedCityTag.type = cityTags.some(tag => tag.tag_machine_name === currentCity) ? 'domestic' : 'overseas';
                } else {
                    matchedCityTag = {
                        type: 'custom',
                        tag_name: currentCity
                    };
                }
            }

            // 최종 선택 데이터 준비
            let finalSelected = {
                days: selectedDays,
                personnel: currentPersonnel,
                companion: matchedCompanionTag,
                pet: matchedPetTag,
                city: matchedCityTag,
                taste: confirmedTasteTags
            };

            try {
                let url = '/api/moongcledeal/edit-in-progress';
                let moongcledealIdx = <?= json_encode(!empty($moongcledeal->moongcledeal_idx) ? $moongcledeal->moongcledeal_idx : null) ?>;
                // const representative = document.getElementById("representative");

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        moongcledealIdx: moongcledealIdx,
                        selected: finalSelected,
                        // represent: representative.checked
                    })
                });

                // 응답 처리
                if (!response.ok) {
                    throw new Error('API 요청 실패: ' + response.statusText);
                }

                const result = await response.json();

                if (result.moongcledealIdx) {
                    window.location.href = `/moongcledeal/edit/02/${result.moongcledealIdx}`;
                } else {
                    console.error('idx 값이 응답에 포함되지 않았습니다.');
                }
            } catch (error) {
                console.error('API 요청 중 오류 발생:', error);
                throw error;
            }
        });

        const tempSaveButton = document.getElementById("tempSave");

        tempSaveButton.addEventListener("click", async () => {
            let matchedCompanionTag = null;
            let matchedPetTag = {
                size: null,
                weight: null,
                counts: null
            };
            let matchedCityTag = null;

            if (selectedDays.length == 0 && !currentPersonnel && !currentCompanion && !currentCity && confirmedTasteTags.length == 0) {
                fnToastPop('toastUndefined');
                return;
            }

            // 동기적으로 companion 값 확인
            if (currentCompanion) {
                matchedCompanionTag = companionTags.find(tag => tag.tag_machine_name === currentCompanion);
            }

            // pet 데이터 처리
            if (currentPetSize || currentPetWeight || currentPetCount) {

                if (currentPetSize) {
                    matchedPetTag.size = petTags.size.find(tag => tag.tag_machine_name === currentPetSize) || null;
                }

                if (currentPetWeight) {
                    matchedPetTag.weight = petTags.weight.find(tag => tag.tag_machine_name === currentPetWeight) || null;
                }

                if (currentPetCount) {
                    matchedPetTag.counts = petTags.counts.find(tag => tag.tag_machine_name === currentPetCount) || null;
                }

            }

            // city 값 처리
            if (currentCity) {
                matchedCityTag = cityTags.find(tag => tag.tag_machine_name === currentCity) ||
                    overseasTags.find(tag => tag.tag_machine_name === currentCity);

                if (matchedCityTag) {
                    matchedCityTag.type = cityTags.some(tag => tag.tag_machine_name === currentCity) ? 'domestic' : 'overseas';
                } else {
                    matchedCityTag = {
                        type: 'custom',
                        tag_name: currentCity
                    };
                }
            }

            // 최종 선택 데이터 준비
            let finalSelected = {
                days: selectedDays,
                personnel: currentPersonnel,
                companion: matchedCompanionTag,
                pet: matchedPetTag,
                city: matchedCityTag,
                taste: confirmedTasteTags
            };

            try {
                let url = '<?= !empty($moongcledeal->moongcledeal_idx) ? '/api/moongcledeal/update' : '/api/moongcledeal/store' ?>';
                let moongcledealIdx = <?= json_encode(!empty($moongcledeal->moongcledeal_idx) ? $moongcledeal->moongcledeal_idx : null) ?>;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        moongcledealIdx: moongcledealIdx,
                        priority: null,
                        selected: finalSelected
                    })
                });

                // 응답 처리
                if (!response.ok) {
                    throw new Error('API 요청 실패: ' + response.statusText);
                }

                const result = await response.json();

                if (result.moongcledealIdx) {
                    window.location.href = '/moongcledeals';
                } else {
                    console.error('idx 값이 응답에 포함되지 않았습니다.');
                }
            } catch (error) {
                console.error('API 요청 중 오류 발생:', error);
                throw error;
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const stopCheckbox = document.getElementById("stop");
            const moongcledealStop = document.getElementById("moongcledealStop");
            const moongcledealStopCancel = document.getElementById("moongcledealStopCancel");

            stopCheckbox.addEventListener("change", function() {
                if (this.checked) {
                    fnOpenLayerPop('stopAlert');
                }
            });

            moongcledealStopCancel.addEventListener("click", function() {
                stopCheckbox.checked = false;
            });

            moongcledealStop.addEventListener("click", async function() {
                try {
                    let url = '/api/moongcledeal/stop';
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
                        window.location.href = '/moongcledeals';
                    } else {
                        console.error('idx 값이 응답에 포함되지 않았습니다.');
                    }
                } catch (error) {
                    console.error('API 요청 중 오류 발생:', error);
                    throw error;
                }
            });
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

        // fnToastPop('toastPopup');

        $(document).ready(function() {
            // notfavorite-tag에서 favorite-tag로 이동
            $('.notfavorite-tag').on('click', 'a', function(e) {
                e.preventDefault();
                const listItem = $(this).closest('li');
                $('.favorite-tag ul').append(listItem);
                $(this).closest('li').addClass('active')
            });

            // favorite-tag에서 notfavorite-tag로 이동
            $('.favorite-tag').on('click', 'a', function(e) {
                e.preventDefault();
                const listItem = $(this).closest('li');
                $('.notfavorite-tag ul').append(listItem);
                $(this).closest('li').removeClass('active')
            });
        });
    </script>

</body>

</html>