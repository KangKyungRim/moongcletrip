<!DOCTYPE html>
<html lang="ko">

<?php

$user = $data['user'];
$isGuest = $data['isGuest'];
$deviceType = $data['deviceType'];
$pendingMoongcledeal = $data['pendingMoongcledeal'];
$inProgressMoongcledeal = $data['inProgressMoongcledeal'];
$stopMoongcledeal = $data['stopMoongcledeal'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<script>
    const data = <?= json_encode($data) ?>;
    console.log(data);
</script>


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
                <div style="display: flex; gap: 0.5rem;">
                    <a href="/moongcledeal/create/01" class="btn-txt__small" style="font-weight: 600; color: #714cdc;">신규 뭉클딜</a>
                    <button type="button" class="btn-create" onclick="location.href='/moongcledeal/create/01'"><span class="blind">신규 뭉클딜</span></button>
                    <!-- <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button> -->
                </div>
            </div>
        </header>

        <div class="container__wrap mkcle-home__wrap">
            <div class="tab__wrap tab-line__wrap">
                <ul class="tab__inner fnStickyTop">
                    <li class="tab-line__con active">
                        <a>전체보기</a>
                    </li>
                    <li class="tab-line__con">
                        <a>나만의 뭉클딜</a>
                    </li>
                    <li class="tab-line__con">
                        <a>재오픈 알림</a>
                    </li>
                    <li class="tab-line__con">
                        <a>알림꺼진 뭉클딜</a>
                    </li>
                </ul>

                <div class="tab-contents__wrap">
                    <!-- 전체보기 탭 -->
                    <div class="tab-contents active">
                        <section class="layout__wrap pt20">
                            <?php if (!empty($pendingMoongcledeal)) : ?>
                                <div class="btn__wrap btn-header">
                                    <button type="button" class="btn-full__black btn-full__small" onclick="location.href='/moongcledeal/create/02?moongcledeal_idx=<?= $pendingMoongcledeal->moongcledeal_idx; ?>'">미완료된 뭉클딜이 있어요. 마저 작성할까요?</button>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($inProgressMoongcledeal) && $inProgressMoongcledeal->count() !== 0) : ?>
                                <div class="tit__wrap tit__member">
                                    <p class="ft-xxl">
                                        <?php if ($isGuest) : ?>
                                            <span class="txt-point">뭉클러</span> 님에게 <br>
                                            어떤 설레는 뭉클딜이 도착할까요?
                                        <?php else : ?>
                                            <span class="txt-point"><?= $user->user_nickname; ?></span> 님에게 <br>
                                            어떤 설레는 뭉클딜이 도착할까요?
                                        <?php endif; ?>
                                    </p>
                                    <p class="desc">내 취향에 꼭 맞는 여행 알림이 도착해요!</p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($user)) : ?>
                                <?php if (!empty($inProgressMoongcledeal) && $inProgressMoongcledeal->count() !== 0) : ?>
                                    <!-- 나만의 뭉클딜 -->
                                    <div class="has-mymkcle__wrap">
                                        <div class="tit__wrap align__center">
                                            <p class="ft-xl">나만의 뭉클딜<span class="beta-text">Beta</span></p>
                                        </div>
                                        <div class="recommend-list__wrap">
                                            <?php foreach ($inProgressMoongcledeal as $moongcledeal) : ?>
                                                <?php $priority = $moongcledeal->priority; ?>
                                                <a href="" class="recommend-list" onclick="gotoMoongcleDeatilPage(event, '/moongcledeal/detail/<?= $moongcledeal->moongcledeal_idx; ?>')">
                                                    <?php if (!empty($moongcledeal->unread_deal_count)) : ?>
                                                        <span class="num-purple"><?= $moongcledeal->unread_deal_count; ?></span>
                                                    <?php endif; ?>
                                                    <span style="display: flex; width: 100%;">
                                                        <p class="ft-xxs"><?= !empty($moongcledeal->selected['city']['tag_name']) ? '#' . $moongcledeal->selected['city']['tag_name'] . ' ' : ''; ?><?= !empty($moongcledeal->selected['personnel']) ? '#' . $moongcledeal->selected['personnel'] . '명 ' : ''; ?><?= !empty($moongcledeal->selected['companion']['tag_name']) ? '#' . $moongcledeal->selected['companion']['tag_name'] . ' ' : ''; ?></p>
                                                        <p class="ft-xxs" style="text-align: right; white-space: nowrap;">받은 제안: <?= $moongcledeal->deal_count; ?>개 (<?= date('m월 d일 생성됨', strtotime($moongcledeal->created_at)); ?>)</p>
                                                    </span>
                                                    <?php foreach ($priority as $tag) : ?>
                                                        <div class="item">
                                                            <img src="/uploads/tags/<?= $tag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                            <span><?= $tag['tag_name']; ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <!-- //나만의 뭉클딜 -->
                                <?php else : ?>
                                    <!-- 뭉클딜 없을 때 -->
                                    <div class="no-mymkcle__wrap">
                                        <div class="tit__wrap align__center">
                                            <p class="ft-xl">
                                                아직 등록된 뭉클딜이 없어요. <br>
                                                뭉클딜을 등록해볼까요?
                                            </p>
                                        </div>
                                        <div class="splide splide__default">
                                            <div class="splide__track">
                                                <ul class="splide__list">
                                                    <li class="splide__slide"><img src="/assets/app/images/main/02.gif" alt=""></li>
                                                    <li class="splide__slide"><img src="/assets/app/images/main/03.gif" alt=""></li>
                                                    <li class="splide__slide"><img src="/assets/app/images/main/04.gif" alt=""></li>
                                                    <li class="splide__slide"><img src="/assets/app/images/main/05.gif" alt=""></li>
                                                    <li class="splide__slide"><img src="/assets/app/images/main/01.gif" alt=""></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="btn__wrap">
                                            <button type="button" class="btn-full__primary" onclick="openMoongcledeal()">나만의 뭉클딜 제안받기</button>
                                        </div>
                                    </div>
                                    <!-- //뭉클딜 없을 때 -->
                                <?php endif; ?>
                            <?php else : ?>
                                <!-- 로그인하지 않았을 때 -->
                                <div class="no-mymkcle__wrap">
                                    <div class="tit__wrap align__center">
                                        <p class="ft-xl">
                                            아직 등록된 뭉클딜이 없어요. <br>
                                            뭉클딜을 등록해볼까요?
                                        </p>
                                    </div>
                                    <div class="splide splide__default">
                                        <div class="splide__track <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup2' : '' ?>">
                                            <ul class="splide__list">
                                                <li class="splide__slide"><img src="/assets/app/images/main/02.gif" alt=""></li>
                                                <li class="splide__slide"><img src="/assets/app/images/main/03.gif" alt=""></li>
                                                <li class="splide__slide"><img src="/assets/app/images/main/04.gif" alt=""></li>
                                                <li class="splide__slide"><img src="/assets/app/images/main/05.gif" alt=""></li>
                                                <li class="splide__slide"><img src="/assets/app/images/main/01.gif" alt=""></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="btn__wrap">
                                        <button type="button" class="btn-full__primary <?= $deviceType !== 'app' ? 'fnOpenPop' : '' ?>" data-name="<?= $deviceType !== 'app' ? 'appDownPopup1' : '' ?>">나만의 뭉클딜 제안받기</button>
                                    </div>
                                </div>
                                <!-- //로그인하지 않았을 때 -->
                            <?php endif; ?>
                        </section>

                        <!-- <hr class="divide"> -->

                        <!-- 재오픈 알림 -->
                        <!-- <section class="layout__wrap">
                            <div class="tit__wrap align__center">
                                <p class="ft-xl">재오픈 알림</p>
                            </div>
                            <div class="box-gray__list">
                                <div class="box-gray__wrap">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img medium"><img src="/assets/app/images/demo/img_hotel_medium.png" alt=""></p>
                                        <div class="thumb__con">
                                            <p class="detail-sub">
                                                <span>제주특별자치도 서귀포시</span>
                                                <span>5성급</span>
                                            </p>
                                            <p class="detail-name">스위트호텔 제주</p>
                                            <p class="detail-sub multiline">
                                                <span>슈페리어 더블 룸온리</span>
                                                <span>2024.09.09 ~ 09.11(2박)</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="btn__wrap">
                                        <button type="button" class="btn-sm__primary">재오픈했어요!</button>
                                    </div>
                                </div>
                                <div class="box-gray__wrap">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img medium"><img src="/assets/app/images/demo/img_hotel_medium.png" alt=""></p>
                                        <div class="thumb__con">
                                            <p class="detail-sub">
                                                <span>제주특별자치도 서귀포시</span>
                                                <span>5성급</span>
                                            </p>
                                            <p class="detail-name">스위트호텔 제주</p>
                                            <p class="detail-sub multiline">
                                                <span>슈페리어 더블 룸온리</span>
                                                <span>2024.09.09 ~ 09.11(2박)</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="btn__wrap">
                                        <button type="button" class="btn-sm__black">재오픈 알림끄기</button>
                                    </div>
                                </div>
                            </div>
                        </section> -->
                        <!-- //재오픈 알림 -->

                        <!-- <hr class="divide"> -->

                        <!-- 알림이 꺼진 뭉클딜 -->
                        <?php if (!empty($stopMoongcledeal) && $stopMoongcledeal->count() !== 0) : ?>
                            <section class="layout__wrap">
                                <div class="tit__wrap align__center">
                                    <p class="ft-xl">알림이 꺼진 뭉클딜</p>
                                </div>
                                <div class="recommend-list__wrap">
                                    <?php foreach ($stopMoongcledeal as $moongcledeal) : ?>
                                        <?php $priority = $moongcledeal->priority; ?>
                                        <a href="" class="recommend-list" onclick="gotoMoongcleDeatilPage(event, '/moongcledeal/detail/<?= $moongcledeal->moongcledeal_idx; ?>')">
                                            <?php if (!empty($moongcledeal->unread_deal_count)) : ?>
                                                <span class="num-purple"><?= $moongcledeal->unread_deal_count; ?></span>
                                            <?php endif; ?>
                                            <span style="display: flex; width: 100%;">
                                                <p class="ft-xxs"><?= !empty($moongcledeal->selected['city']['tag_name']) ? '#' . $moongcledeal->selected['city']['tag_name'] . ' ' : ''; ?><?= !empty($moongcledeal->selected['personnel']) ? '#' . $moongcledeal->selected['personnel'] . '명 ' : ''; ?><?= !empty($moongcledeal->selected['companion']['tag_name']) ? '#' . $moongcledeal->selected['companion']['tag_name'] . ' ' : ''; ?></p>
                                                <p class="ft-xxs" style="text-align: right; white-space: nowrap;">받은 제안: <?= $moongcledeal->deal_count; ?>개 (<?= date('m월 d일 생성됨', strtotime($moongcledeal->created_at)); ?>)</p>
                                            </span>
                                            <?php foreach ($priority as $tag) : ?>
                                                <div class="item">
                                                    <img src="/uploads/tags/<?= $tag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                    <span><?= $tag['tag_name']; ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <!-- //알림이 꺼진 뭉클딜 -->
                    </div>
                    <!-- //전체보기 탭 -->
                    <!-- 나만의 뭉클딜 탭 -->
                    <div class="tab-contents">
                        <section class="layout__wrap pt20">
                            <?php if (!empty($pendingMoongcledeal)) : ?>
                                <div class="btn__wrap btn-header">
                                    <button type="button" class="btn-full__black btn-full__small" onclick="location.href='/moongcledeal/create/02?moongcledeal_idx=<?= $pendingMoongcledeal->moongcledeal_idx; ?>'">미완료된 뭉클딜이 있어요. 마저 작성할까요?</button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($inProgressMoongcledeal) && $inProgressMoongcledeal->count() !== 0) : ?>
                                <!-- 나만의 뭉클딜 -->
                                <div class="tit__wrap align__center">
                                    <p class="ft-xl">나만의 뭉클딜</p>
                                </div>
                                <div class="recommend-list__wrap">
                                    <?php foreach ($inProgressMoongcledeal as $moongcledeal) : ?>
                                        <?php $priority = $moongcledeal->priority; ?>
                                        <a href="" class="recommend-list" onclick="gotoMoongcleDeatilPage(event, '/moongcledeal/detail/<?= $moongcledeal->moongcledeal_idx; ?>')">
                                            <?php if (!empty($moongcledeal->unread_deal_count)) : ?>
                                                <span class="num-purple"><?= $moongcledeal->unread_deal_count; ?></span>
                                            <?php endif; ?>
                                            <span style="display: flex; width: 100%;">
                                                <p class="ft-xxs"><?= !empty($moongcledeal->selected['city']['tag_name']) ? '#' . $moongcledeal->selected['city']['tag_name'] . ' ' : ''; ?><?= !empty($moongcledeal->selected['personnel']) ? '#' . $moongcledeal->selected['personnel'] . '명 ' : ''; ?><?= !empty($moongcledeal->selected['companion']['tag_name']) ? '#' . $moongcledeal->selected['companion']['tag_name'] . ' ' : ''; ?></p>
                                                <p class="ft-xxs" style="text-align: right; white-space: nowrap;">받은 제안: <?= $moongcledeal->deal_count; ?>개 (<?= date('m월 d일 생성됨', strtotime($moongcledeal->created_at)); ?>)</p>
                                            </span>
                                            <?php foreach ($priority as $tag) : ?>
                                                <div class="item">
                                                    <img src="/uploads/tags/<?= $tag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                    <span><?= $tag['tag_name']; ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                                <!-- //나만의 뭉클딜 -->
                            <?php endif; ?>
                        </section>
                    </div>
                    <!-- //나만의 뭉클딜 탭 -->
                    <!-- 재오픈 알림 -->
                    <div class="tab-contents">
                        <section class="layout__wrap pt20">
                            <!-- <div class="btn__wrap btn-header">
                                <button type="button" class="btn-full__black btn-full__small">미완료된 뭉클딜이 있어요. 마저 작성할까요?</button>
                            </div> -->
                            <!-- <div class="tit__wrap align__center">
                                <p class="ft-xl">재오픈 알림</p>
                            </div>
                            <div class="box-gray__list">
                                <div class="box-gray__wrap">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img medium"><img src="/assets/app/images/demo/img_hotel_medium.png" alt=""></p>
                                        <div class="thumb__con">
                                            <p class="detail-sub">
                                                <span>제주특별자치도 서귀포시</span>
                                                <span>5성급</span>
                                            </p>
                                            <p class="detail-name">스위트호텔 제주</p>
                                            <p class="detail-sub multiline">
                                                <span>슈페리어 더블 룸온리</span>
                                                <span>2024.09.09 ~ 09.11(2박)</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="btn__wrap">
                                        <button type="button" class="btn-sm__primary">재오픈했어요!</button>
                                    </div>
                                </div>
                                <div class="box-gray__wrap">
                                    <div class="thumb__wrap">
                                        <p class="thumb__img medium"><img src="/assets/app/images/demo/img_hotel_medium.png" alt=""></p>
                                        <div class="thumb__con">
                                            <p class="detail-sub">
                                                <span>제주특별자치도 서귀포시</span>
                                                <span>5성급</span>
                                            </p>
                                            <p class="detail-name">스위트호텔 제주</p>
                                            <p class="detail-sub multiline">
                                                <span>슈페리어 더블 룸온리</span>
                                                <span>2024.09.09 ~ 09.11(2박)</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="btn__wrap">
                                        <button type="button" class="btn-sm__black">재오픈 알림끄기</button>
                                    </div>
                                </div>
                            </div> -->

                            <div class="no-contents__wrap">
                                <p class="title">재오픈 알림은 어떤 기능인가요?</p>
                                <p class="desc">
                                    혹시 가보고 싶은 날짜에 원하는 여행상품이 <br>
                                    이미 매진이 되어 속상하지 않으셨나요?<br>
                                    이제는 걱정마세요.<br><br>

                                    뭉클에서 재오픈 알림 신청만 해두면 취소 또는 추가 수량이<br>
                                    등록되면 가장 빠르게 알려드려요! <br>
                                    꼭 가보고 싶었던 여행, 이번엔 놓치지 마세요
                                </p>
                            </div>
                        </section>
                    </div>
                    <!-- //재오픈 알림 -->
                    <!-- 알림이 꺼진 뭉클딜 -->
                    <div class="tab-contents">
                        <section class="layout__wrap pt20">
                            <?php if (!empty($pendingMoongcledeal)) : ?>
                                <div class="btn__wrap btn-header">
                                    <button type="button" class="btn-full__black btn-full__small" onclick="location.href='/moongcledeal/create/02?moongcledeal_idx=<?= $pendingMoongcledeal->moongcledeal_idx; ?>'">미완료된 뭉클딜이 있어요. 마저 작성할까요?</button>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($stopMoongcledeal) && $stopMoongcledeal->count() !== 0) : ?>
                                <div class="tit__wrap align__center">
                                    <p class="ft-xl">알림이 꺼진 뭉클딜</p>
                                </div>
                                <div class="recommend-list__wrap">
                                    <?php foreach ($stopMoongcledeal as $moongcledeal) : ?>
                                        <?php $priority = $moongcledeal->priority; ?>
                                        <a href="" class="recommend-list" onclick="gotoMoongcleDeatilPage(event, '/moongcledeal/detail/<?= $moongcledeal->moongcledeal_idx; ?>')">
                                            <p class="ft-xxs">생성일: <?= date('Y년 m월 d일', strtotime($moongcledeal->created_at)); ?></p>
                                            <?php foreach ($priority as $tag) : ?>
                                                <div class="item">
                                                    <img src="/uploads/tags/<?= $tag['tag_machine_name']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                    <span><?= $tag['tag_name']; ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </section>
                    </div>
                    <!-- //알림이 꺼진 뭉클딜 -->
                </div>
            </div>

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

        </div>

        <!-- 토스트팝업 -->
        <div id="toastPopup" class="toast__wrap">
            <div class="toast__container">
                <i class="ico ico-info"></i>
                <p>아직 뭉클딜이 도착하지 않았어요. 도착하면 알려드릴게요!</p>
            </div>
        </div>
        <!-- //토스트팝업 -->

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
            location.href = '/moongcledeal/create/01';
        }

        function openMoongcledeal() {
            <?php if ($deviceType == 'pc') : ?>
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
        // fnToastPop('toastPopup');
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>