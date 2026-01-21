<!DOCTYPE html>
<html lang="ko">

<?php

$user = $data['user'];
$isGuest = $data['isGuest'];
$deviceType = $data['deviceType'];

?>

<script>
    const data = <?= json_encode($data) ?>;
    console.log(data);
</script>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>

<!-- Head -->
<script type="text/javascript" src="/assets/app/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/assets/app/js/underscore-min.1.13.7.js"></script>
<script type="text/javascript" src="/assets/app/js/moment.2.30.1.js"></script>
<script type="text/javascript" src="/assets/app/js/commonNew.js?v=<?= $_ENV['VERSION']; ?>"></script>
<script type="text/javascript" src="/assets/app/js/commonWeb.js?v=<?= $_ENV['VERSION']; ?>"></script>

<body>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
    }
    ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>

    <div id="mobileWrap">
        <header class="header__wrap center">
            <div class="header__inner">
                <div class="logo">
                    <a href="/">
                        <img src="/assets/app/images/common/moongcle_color_807_257.png" alt="로고 이미지" class="logo_bar">
                    </a>
                </div>
                <h2 class="header-tit__center">
                    나를 위한 추천
                </h2>
                
                <div class="btn__wrap">
                    <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
                    <button type="button" class="btn-my" onclick="gotoMypage()" style="margin-left: 0.5rem;"><span class="blind">마이페이지</span></button>
                </div>
            </div>  
		</header>

        <div class="container__wrap">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/refresh.php"; ?>

            <div class="layout__wrap" style="padding: 2.4rem 0;">

                <div class="refresh__wrap" style="padding-bottom: 7.4rem;">
                    <div class="profile__wrap">
                        <div class="profile_img">
                            <img src="/assets/app/images/common/no_profile.jpg" alt="프로필 사진">
                        </div>
                        <div class="profile_info">
                            <p class="profile_name">
                                <?php   
                                    $nickname = null;

                                    if ($user) {
                                        if (is_array($user)) {
                                            $nickname = $user['user_nickname'] ?? null;
                                        } elseif (is_object($user)) {
                                            $nickname = $user->user_nickname ?? null;
                                        }
                                    }
                                ?>
                                <span class="user_name"><?= htmlspecialchars($nickname ?: '뭉클러', ENT_QUOTES, 'UTF-8') ?></span>님,
                            </p>
                            <p class="profile_desc">맞춤 숙소를 확인해 보세요!</p>
                        </div>
                    </div>         
                </div>
            </div>
        </div>

        <!-- <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?> -->

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
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>