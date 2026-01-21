<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$article = $data['article'];

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
                <button type="button" class="btn-back" onclick="goBack()"><span class="blind">뒤로가기</span></button>
                <p class="header-product-name"><?= $article->title; ?></p>
            </div>
        </header>

        <div class="container__wrap">
            <section class="layout__wrap pt20">
                <div class="article__con">
                    <?= $article->article_body; ?>
                </div>
            </section>
            <!-- 하단 버튼 영역 -->
            <div class="bottom-fixed__wrap">
                <div class="btn__wrap">
                    <button type="button" class="btn-full__line__gray"><i class="ico ico-share"></i></button>
                    <button type="button" class="btn-full__primary" onclick="gotoArticleLink(<?= $article->article_button_link; ?>)"><?= $article->article_button_name; ?></button>
                </div>
            </div>
            <!-- //하단 버튼 영역 -->
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
    </script>

</body>

</html>