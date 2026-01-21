<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$moongcledealNotifications = $data['moongcledealNotifications'];
$unreadNotifications = $data['unreadNotifications'];

?>

<!-- Head -->
<?php 
    // $page_title = "알림";
    // $page_description = "알림";

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

    <div id="mobileWrap">
        <header class="header__wrap">
            <div class="header__inner">
                <button type="button" class="btn-back" onclick="previousPage()"><span class="blind">뒤로가기</span></button>
                <h2 class="header-tit__center">알림</h2>
                <!-- <button type="button" class="btn-txt__underline btn-txt__small">삭제</button> -->
            </div>
        </header>

        <div class="container__wrap notification__wrap">

            <div class="notification-list__wrap">
                <p class="title">새로운 알림</p>
                <?php if (empty($unreadNotifications) || empty($unreadNotifications->count())) : ?>
                    <a href="" class="notification-list__con">
                        <div class="thumb__wrap">
                            <p class="thumb__img logo"><img src="/assets/app/images/common/logo.png" alt=""></p>
                            <div class="thumb__con">
                                <p class="detail-name"></p>
                                <p class="detail-sub">아직은 새로운 내용이 없어요.</p>
                            </div>
                        </div>
                    </a>
                <?php else : ?>
                    <?php foreach ($unreadNotifications as $unreadNotification) : ?>
                        <?php
                        $createdAt = new \DateTime($unreadNotification->created_at);
                        $formattedCreatedAt = $createdAt->format('y.m.d H:i');
                        ?>
                        <a href="<?= $unreadNotification->link; ?>" class="notification-list__con">
                            <div class="thumb__wrap">
                                <p class="thumb__img logo"><img src="/assets/app/images/common/logo.png" alt=""></p>
                                <div class="thumb__con">
                                    <p class="detail-name"><?= $unreadNotification->title; ?></p>
                                    <p class="detail-sub"><?= $unreadNotification->message; ?></p>
                                </div>
                                <p class="notification-date"><?= $formattedCreatedAt; ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- 탭 -->
                <div class="tab__wrap tab-line__wrap full-flex">
                    <ul class="tab__inner">
                        <li class="tab-line__con active"><a>뭉클딜</a></li>
                        <li class="tab-line__con"><a>리뷰</a></li>
                        <li class="tab-line__con"><a>시스템</a></li>
                    </ul>
                    <div class="tab-contents__wrap">
                        <!-- 뭉클딜 -->
                        <div class="tab-contents active">
                            <!-- 뭉클딜 알림 -->
                            <?php if (!empty($moongcledealNotifications)) : ?>
                                <?php foreach ($moongcledealNotifications as $moongcledealNotification) : ?>
                                    <?php
                                    $createdAt = new \DateTime($moongcledealNotification->created_at);
                                    $formattedCreatedAt = $createdAt->format('y.m.d H:i');
                                    ?>
                                    <a href="<?= $moongcledealNotification->link; ?>" class="notification-list__con">
                                        <div class="thumb__wrap">
                                            <p class="thumb__img logo"><img src="/assets/app/images/common/logo.png" alt=""></p>
                                            <div class="thumb__con">
                                                <p class="detail-name"><?= $moongcledealNotification->title; ?></p>
                                                <p class="detail-sub"><?= $moongcledealNotification->message; ?></p>
                                            </div>
                                            <p class="notification-date"><?= $formattedCreatedAt; ?></p>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (count($moongcledealNotifications) === 0) : ?>
                                <div class="no__wrap">
                                    <div class="nodata__con" style="font-size: 1.4rem;">
                                        아직은 새로운 알림이 없어요.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- //뭉클딜 -->
                        <!-- 리뷰 -->
                        <div class="tab-contents">
                            <div class="no__wrap">
                                <div class="nodata__con" style="font-size: 1.4rem;">
                                    아직은 새로운 알림이 없어요.
                                </div>
                            </div>
                        </div>
                        <!-- //리뷰 -->
                        <!-- 시스템 -->
                        <div class="tab-contents">
                            <div class="no__wrap">
                                <div class="nodata__con" style="font-size: 1.4rem;">
                                    아직은 새로운 알림이 없어요.
                                </div>
                            </div>
                        </div>
                        <!-- //시스템 -->
                    </div>
                </div>
                <!-- //탭 -->
            </div>

        </div>
    </div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>