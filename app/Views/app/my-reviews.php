<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$myReviews = $data['myReviews'];

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



	<div id="mobileWrap" style="height: 100vh;">
		<header class="header__wrap">
			<div class="header__inner">
				<button class="btn-back" onclick="gotoMypage()"><span class="blind">뒤로가기</span></button>
				<p class="header-tit__center">내 후기</p>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">
			<section class="layout__wrap pt20">
				<!-- 리뷰 리스트 -->
				<div class="review-list__wrap" style="position: relative;">
                    <?php if (!empty($myReviews)) : ?>
					    <?php foreach ($myReviews as $myReview) : ?>
					        <div class="review-list__con">
							<div class="review-top">
								<div class="flex-center">
									<?php if ($myReview->review_category == 'stay') : ?>
										<div class="badge badge__lavender">숙소</div>
									<?php endif; ?>
									<div class="rating__wrap">
										<div data-rating="<?= $myReview->rating; ?>" data-width="12" data-spacing="2" data-rateyo-read-only="true" class="rating"></div>
									</div>
								</div>
								<!-- <div class="filter-select__wrap">
									<p class="filter-select__tit">
										<i class="ico ico-dotmenu"></i>
									</p>
									<div class="filter-select__list">
										<ul>
											<li><a class="fnOpenPop" data-name="alert">수정</a></li>
											<li><a href="">삭제</a></li>
										</ul>
									</div>
								</div> -->
							</div>
							<div class="thumb__wrap">
								<p class="thumb__img small"><img src="<?= $myReview->main_image; ?>" alt=""></p>
								<div class="thumb__con">
									<p class="detail-name"><?= $myReview->partner_name; ?></p>
									<p class="detail-sub"><?= $myReview->product_detail_name; ?></p>
								</div>
							</div>
							<div class="review-txt">
								<p class="review"><?= nl2br($myReview->review_content); ?></p>
								<a class="btn-more">더보기</a>
							</div>
							<div class="file__wrap">
								<ul class="file__list">
									<li class="file__con">
										<?php $images = json_decode($myReview->image_list); ?>

										<?php foreach ($images as $image) : ?>
											<img src="<?= $image->path; ?>" alt="">
										<?php endforeach; ?>
									</li>
								</ul>
							</div>
						    </div>
					    <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no__wrap" style="position: absolute; left: auto; top: 0; width: 100%; height: 94vh; display: flex; align-items: center; justify-content: center;">
                                <div class="nodata__con" style="font-size: 1.4rem;">
                                    아직 작성된 후기가 없습니다
                                </div>
                            </div>
                    <?php endif; ?>
				</div>
				<!-- //리뷰 리스트 -->
			</section>
		</div>

		<!-- 알럿 팝업 -->
		<div id="alert" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">후기를 삭제하시겠습니까?</p>
						<p class="desc">
							삭제 시 복원할 수 없습니다.
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">아니요</button>
						<button class="btn-full__primary">삭제하기</button>
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
		$(".rating").each(function(e) {
			var $rating = $(this).attr("data-rating");
			var $starWidth = $(this).attr("data-width");
			var $spacing = $(this).attr("data-spacing");
			$(this).rateYo({
				rating: $rating,
				starWidth: $starWidth,
				numStars: 5,
				halfStar: true,
				normalFill: "#EBEBEB",
				spacing: $spacing,
			});
		});
		$('.jq-ry-normal-group').find('path').attr('fill', $('.jq-ry-normal-group').find('svg').attr('fill'));
		$('.jq-ry-normal-group').find('path').attr('stroke', $('.jq-ry-normal-group').find('svg').attr('fill'));
	</script>

    <script>
        // 3줄 체크
        function checkReviewLineCount() {
            document.querySelectorAll('.review-txt').forEach(function(el) {
                const review = el.querySelector('.review');
                const btnMore = el.querySelector('.btn-more');

                if (!review || !btnMore) return;

                const computedStyle = window.getComputedStyle(review);
                const lineHeight = parseFloat(computedStyle.lineHeight);
                const totalHeight = review.scrollHeight;
                const lineCount = Math.round(totalHeight / lineHeight);

                if (lineCount <= 3) {
                    btnMore.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
             // 더보기 노출 컨트롤
             checkReviewLineCount();
        });
    </script>

</body>

</html>