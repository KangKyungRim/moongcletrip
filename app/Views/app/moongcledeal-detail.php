<!DOCTYPE html>
<html lang="ko">

<?php

$user = $data['user'];
$isGuest = $data['isGuest'];
$deviceType = $data['deviceType'];
$moongcledeal = $data['moongcledeal'];
$moongcleMatches = $data['moongcleMatches'];
$moongcleoffers = $data['moongcleoffers'];
$moongcleofferFavorites = $data['moongcleofferFavorites'];

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
                <button type="button" class="btn-back" onclick="gotoMoongcledeals()"><span class="blind">뒤로가기</span></button>
                <h2 class="header-tit__center">나만의 뭉클딜</h2>
                <?php if ($moongcledeal->status == 'stop') : ?>
                    <button type="button" class="btn-txt__gray" onclick="fnOpenLayerPop('reopenAlert')">편집</button>
                <?php else : ?>
                    <a href="/moongcledeal/edit/01/<?= $moongcledeal->moongcledeal_idx ?>" type="button" class="btn-txt__gray">편집</a>
                <?php endif; ?>
            </div>
        </header>

        <div class="container__wrap mkcle-detail__wrap">
            <section class="layout__wrap pt20">
                <div class="recommend-list__wrap">
                    <div class="recommend-list">
                        <span style="display: flex; width: 100%;">
                            <p class="ft-xxs"><?= !empty($moongcledeal->selected['city']['tag_name']) ? '#' . $moongcledeal->selected['city']['tag_name'] . ' ' : ''; ?><?= !empty($moongcledeal->selected['personnel']) ? '#' . $moongcledeal->selected['personnel'] . '명 ' : ''; ?><?= !empty($moongcledeal->selected['companion']['tag_name']) ? '#' . $moongcledeal->selected['companion']['tag_name'] . ' ' : ''; ?></p>
                            <p class="ft-xxs" style="text-align: right;">받은 제안: <?= $moongcledeal->deal_count; ?>개 (<?= date('m월 d일 생성됨', strtotime($moongcledeal->created_at)); ?>)</p>
                        </span>
                        <?php foreach ($priority as $tag) : ?>
                            <div class="item">
                                <img src="/uploads/tags/<?= $tag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                <span><?= $tag['tag_name']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <p class="txt-warning mt16" style="gap: 5px; overflow-wrap: break-word; align-items: flex-start;">
                    <span class="d-inline-block" style="width: 96%; margin-top: -2px;">뭉클딜은 상황에 따라 조기 종료될 수 있으며, 추천 상품이 위 조건과 일부만 일치할 수 있으니, 상품별 포함 사항을 꼭 확인해 주세요.</span>
                </p>
            </section>

            <hr class="divide">

            <section class="layout__wrap">

                <?php if (!empty($moongcleoffers)) : ?>
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
                                            <span class="ft-s ft-bold" style="padding: 0 1rem 0 1rem;"><?= count($moongcleoffers) - $key; ?>번째 뭉클딜</span>
                                        </label>
                                        <div class="badge badge__lavender"><?= $category; ?></div>
                                    </div>
                                </div>
                                <div class="box-gray__wrap">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img large"><img src="<?= $moongcleoffer->image_normal_path; ?>" alt="" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>)"></p>
                                        <div class="thumb__con">
                                            <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                                <div class="thumb-badge">최대 <?= number_format((($moongcleoffer->basic_price - $moongcleoffer->lowest_price) / $moongcleoffer->basic_price) * 100, 1) ?>% 할인!</div>
                                            <?php endif; ?>
                                            <button type="button" class="btn-product__like type-black <?= in_array($moongcleoffer->moongcleoffer_idx, $moongcleofferFavorites) ? 'active' : '' ?>" data-user-idx="<?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>" data-partner-idx="<?= !empty($moongcleoffer->partner_idx) ? $moongcleoffer->partner_idx : 0 ?>" data-moongcleoffer-idx="<?= !empty($moongcleoffer->moongcleoffer_idx) ? $moongcleoffer->moongcleoffer_idx : 0 ?>" style="top: 2.2rem; right: 2rem;"><span class="blind">찜하기</span></button>
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
                                            <p class="detail-name" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>)"><?= $moongcleoffer->partner_name; ?></p>
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
                                    <div class="btn__wrap">
                                        <?php if (!empty($moongcleoffer->lowest_price)) : ?>
                                            <?php if ($moongcleoffer->room_status == 'enabled') : ?>
                                                <button type="button" class="btn-md__black" onclick="gotoMoongcleoffer(event, <?= $moongcleoffer->partner_idx; ?>)">자세히 보기</button>
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

                <?php endif; ?>
            </section>
        </div>

        <!-- 알럿 팝업 -->
        <div id="reopenAlert" class="layerpop__wrap type-alert">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <div class="tit__wrap">
                        <p class="title">해당 뭉클딜을 다시 받으시겠습니까?</p>
                        <p class="desc">
                            뭉클딜 받기가 활성화된 상태에서만 해당 뭉클딜을 수정할 수 있어요!
                        </p>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button id="moongcledealStop" class="btn-full__secondary fnClosePop">취소</button>
                        <button id="moongcledealStopReopen" class="btn-full__primary">다시 받기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //알럿 팝업 -->

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
        showLoader();

        let moongcledeal = <?= json_encode($moongcledeal); ?>;

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const moongcledealStopReopen = document.getElementById("moongcledealStopReopen");

            moongcledealStopReopen.addEventListener("click", async function() {
                try {
                    let url = '/api/moongcledeal/reopen';
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
                        window.location.href = '/moongcledeal/edit/01/<?= $moongcledeal->moongcledeal_idx ?>';
                    } else {
                        console.error('idx 값이 응답에 포함되지 않았습니다.');
                    }
                } catch (error) {
                    console.error('API 요청 중 오류 발생:', error);
                    throw error;
                }
            });
        });

        function gotoMoongcleoffer(event, partnerIdx) {
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
            window.location.href = `/moongcleoffer/product/${partnerIdx}?${queryParams.toString()}`;
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