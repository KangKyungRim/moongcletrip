<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

$featuredTags = $data['featuredTags'];

$encodedTagGroups = array_map(function ($group) {
    return [
        'tags' => $group,
        'encoded' => base64_encode(json_encode($group)),
    ];
}, $featuredTags);

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
                <button class="btn-back" onclick="goBack()"><span class="blind">뒤로가기</span></button>
                <p class="process-txt"><span>2단계</span> 남았어요</p>
            </div>
        </header>

        <div class="container__wrap mkcle-create__wrap">
            <!-- 프로그레스바 -->
            <div class="progress__wrap fnStickyTop">
                <div class="progress__inner"></div>
            </div>
            <!-- //프로그레스바 -->

            <section class="layout__wrap">
                <div class="tit__wrap">
                    <p class="ft-xxxl">어떤 좋은 숙소를 찾아드릴까요?</p>
                </div>
                <div class="btn__wrap">
                    <a href="/moongcledeal/create/02" class="btn-full__primary">처음부터 직접 만들어볼래요!</a>
                </div>
            </section>

            <hr class="divide">

            <section class="layout__wrap">
                <div class="tit__wrap">
                    <p class="ft-xl">요즘 인기있는 옵션을 추천드려요</p>
                </div>

                <div id="recommendList" class="recommend-list__wrap">
                </div>

                <div class="btn__wrap">
                    <button id="tagRefresh" class="btn-txt btn-txt__default">다른 추천 받아보기 <i class="ico ico-refresh"></i></button>
                </div>
            </section>
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

<script>
    const encodedTagGroups = <?= json_encode($encodedTagGroups); ?>;

    function renderRandomTagGroups() {
        const randomGroups = [];
        const indices = [...Array(encodedTagGroups.length).keys()];
        while (randomGroups.length < 3 && indices.length) {
            const randomIndex = Math.floor(Math.random() * indices.length);
            randomGroups.push(encodedTagGroups[indices.splice(randomIndex, 1)[0]]);
        }

        const recommendList = document.getElementById('recommendList');
        recommendList.innerHTML = ''; // 기존 내용 초기화

        randomGroups.forEach(group => {
            const link = document.createElement('a');
            link.href = `/moongcledeal/create/02?selected=${group.encoded}`;
            link.className = 'recommend-list';

            group.tags.forEach(tag => {
                const item = document.createElement('div');
                item.className = 'item';

                const img = document.createElement('img');
                img.src = `/uploads/tags/${tag.tag_machine_name}.png<?= '?v=' . $_ENV['VERSION']; ?>`;
                img.alt = tag.tag_name;

                const span = document.createElement('span');
                span.textContent = tag.tag_name;

                item.appendChild(img);
                item.appendChild(span);
                link.appendChild(item);
            });

            recommendList.appendChild(link);
        });
    }

    // "다른 추천 받아보기" 버튼에 클릭 이벤트 추가
    document.getElementById('tagRefresh').addEventListener('click', renderRandomTagGroups);

    // 초기 랜더링
    renderRandomTagGroups();
</script>

</html>