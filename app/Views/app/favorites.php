<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$myFavorites = $data['myFavorites'];

?>

<!-- Head -->
<?php 
    // $page_title = "";
    // $page_description = "";

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
				<button class="btn-back" onclick="goBack()"><span class="blind">뒤로가기</span></button>
				<h2 class="header-tit__center">찜하기</h2>
			</div>
		</header>

		<div class="container__wrap wishlist__wrap">

			<div class="tab__wrap tab-line__wrap full-line">
				<ul class="tab__inner">
					<li class="tab-line__con active"><a>숙박</a></li>
					<li class="tab-line__con"><a></a></li>
					<li class="tab-line__con"><a></a></li>
					<!-- <li class="tab-line__con"><a>판매자</a></li> -->
				</ul>
				<div class="tab-contents__wrap">
					<!-- 숙박 -->
					<div class="tab-contents active">
                        <?php if (count($myFavorites) !== 0) : ?>
                            <section class="layout__wrap pt20 bg-gray">
                                <div class="box-white__list">

                                    <?php foreach ($myFavorites as $myFavorite) : ?>
                                        <div class="box-white__wrap">
                                            <a href="<?= $myFavorite->target == 'partner' ? '/stay/detail/' . $myFavorite->partner_idx : '/moongcleoffer/product/' . $myFavorite->partner_idx; ?>">
                                                <div class="thumb__wrap">
                                                    <?php $images = explode(':-:', $myFavorite->image_paths) ?>
                                                    <p class="thumb__img large"><img class="thumbImage" src="<?= $images[0]; ?>" alt=""></p>
                                                    <div class="thumb__con">
                                                        <?php if ($myFavorite->target == 'partner') : ?>
                                                            <span class="badge badge__yellow" style="margin-bottom: 0.8rem;">상시상품</span>
                                                        <?php else : ?>
                                                            <span class="badge badge__lavender" style="margin-bottom: 0.8rem;">나만의 뭉클딜</span>
                                                        <?php endif; ?>
                                                        <p class="detail-sub">
                                                            <span><?= $myFavorite->partner_address1 ?></span>
                                                            <?php $stayTypes = explode(':-:', $myFavorite->types); ?>
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

                                                            if (!empty($myFavorite->tags)) {
                                                                foreach ($ratingKeywords as $keyword) {
                                                                    if (strpos($myFavorite->tags, $keyword) !== false) {
                                                                        $hasRating = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $rating = extract_stay_rating($myFavorite->tags);
                                                            ?>

                                                            <?php if ($hasRating && !empty($rating)) : ?>
                                                                <span><?= $rating ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="detail-name"><?= $myFavorite->partner_name ?></p>
                                                    </div>
                                                    <div class="thumb__price">
                                                        <div>
                                                            <button onclick="location.href='<?= $myFavorite->target == 'partner' ? '/stay/detail/' . $myFavorite->partner_idx : '/moongcleoffer/product/' . $myFavorite->partner_idx; ?>'" class="btn-reservation btn-reservation__review" style="background-color: #00cb9c;">
                                                                지금 예약하기
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </section>
                        <?php else : ?>
                            <div class="no__wrap" style="position: absolute; left: auto; top: 0; width: 100%; height: 94vh; display: flex; align-items: center; justify-content: center;">
                                <div class="nodata__con" style="font-size: 1.4rem;">
                                    아직 찜한 숙소가 없어요!
                                    <br>맘에 드는 숙소가 있다면 찜해두세요
                                </div>
                            </div>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

</body>

</html>