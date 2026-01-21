<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$paymentItem = $data['paymentItem'];
$mainImage = $data['mainImage'];

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
				<h2 class="header-tit__center">뭉클딜 후기 작성</h2>
			</div>
		</header>

		<input type="hidden" name="userIdx" id="userIdx" value="<?= $user->user_idx; ?>" />
		<input type="hidden" name="paymentItemIdx" id="paymentItemIdx" value="<?= $paymentItem->payment_item_idx; ?>" />

		<div class="container__wrap community-review__wrap">
			<section class="layout__wrap pt20">
				<div class="thumb__wrap">
					<p class="thumb__img small"><img src="<?= $mainImage->image_small_path; ?>" alt=""></p>
					<div class="thumb__con">
						<p class="detail-name"><?= $paymentItem->product_partner_name; ?></p>
						<p class="detail-sub"><?= $paymentItem->product_detail_name; ?></p>
					</div>
				</div>
				<div class="rating__wrap">
					<!--
						data-rating : 별점 
						data-width : 별 크기
						data-spacing : 별과 별 사이 간격
					-->
					<!-- <div data-rating="5" data-width="32" data-spacing="12" data-rateyo-read-only="false" class="rating"></div> -->
					<div id="rateYo" data-rating="5" data-width="32" data-spacing="12" data-rateyo-read-only="false" class="rating"></div>
				</div>
				<div class="textarea__wrap">
					<label for="review_textarea" class="textarea-label">자세한 리뷰를 작성해주세요</label>
					<textarea name="" id="review_textarea" class="textarea" placeholder="여행 경험, 서비스, 시설, 뭉클딜 혜택등에 대한 경험을 적어주세요"></textarea>
				</div>
				<input type="file" id="uploadedFile" name="uploadedFile[]" accept=".jpg, .jpeg, .png, .mp4" class="hidden" multiple />
				<div class="btn__wrap">
					<button id="addImages" type="button" class="btn-full__line__primary">사진 첨부하기</button>
				</div>
				<div class="file__wrap">
					<ul class="file__list">
						<!-- <li class="file__con">
							<img src="/assets/app/images/demo/img_hotel_large.png" alt="">
							<a href="" class="btn-file__delete"><i class="ico ico-tag__delete"></i></a>
						</li> -->
					</ul>
				</div>
			</section>

			<!-- 하단 버튼 영역 -->
			<div class="bottom-fixed__wrap">
				<div class="btn__wrap">
					<button id="saveReview" class="btn-full__primary">후기 등록하기</button>
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
		let currentRating = 5.0;

		let rating = $("#rateYo").attr("data-rating");
		let starWidth = $("#rateYo").attr("data-width");
		let spacing = $("#rateYo").attr("data-spacing");

		$("#rateYo").rateYo({
			rating: rating,
			starWidth: starWidth,
			numStars: 5,
			halfStar: true,
			normalFill: "#EBEBEB",
			spacing: spacing,
		}).on("rateyo.change", function(e, data) {
			// 별점이 변경될 때 현재 값을 표시 (즉각 반영)
			$(this).attr("data-rating", data.rating.toFixed(1));
		}).on("rateyo.set", function(e, data) {
			// 별점이 설정(클릭)되었을 때 선택된 값을 저장
			currentRating = data.rating.toFixed(1);
		});

		$('.jq-ry-normal-group').find('path').attr('fill', $('.jq-ry-normal-group').find('svg').attr('fill'));
		$('.jq-ry-normal-group').find('path').attr('stroke', $('.jq-ry-normal-group').find('svg').attr('fill'));

		// 이미지 관련 카운트와 FormData 초기화
		let imageCount = 0;
		const formData = new FormData();

		// 파일 첨부 버튼 클릭 시 파일 입력창 열기
		document.querySelector('#addImages').addEventListener('click', function() {
			openFileInput();
		});

		function openFileInput() {
			if (imageCount < 9) {
				document.getElementById('uploadedFile').click();
			} else {
				alert('이미지는 최대 9장까지 등록 가능합니다.');
			}
		}

		// 파일 선택 시 이미지 미리보기 및 추가
		document.getElementById('uploadedFile').addEventListener('change', function(event) {
			const fileInput = event.target;
			const files = Array.from(fileInput.files);

			// 첨부 가능한 남은 이미지 개수 계산
			const availableSlots = 9 - imageCount;

			// 초과된 이미지는 제외
			if (files.length > availableSlots) {
				alert(`이미지는 최대 9장까지 등록 가능합니다.`);
			}

			// 추가 가능한 이미지만 처리
			const validFiles = files.slice(0, availableSlots);

			validFiles.forEach((file) => {
				const reader = new FileReader();

				reader.onload = function(e) {
					const imgSrc = e.target.result;

					// 새로운 li 요소 생성
					const newLi = document.createElement('li');
					newLi.className = 'file__con';

					// 이미지 요소 생성
					const imgElement = document.createElement('img');
					imgElement.src = imgSrc;
					imgElement.alt = '미리보기 이미지';

					// 삭제 버튼 생성
					const deleteButton = document.createElement('a');
					deleteButton.href = '#';
					deleteButton.className = 'btn-file__delete';
					deleteButton.innerHTML = '<i class="ico ico-tag__delete"></i>';
					deleteButton.addEventListener('click', function(event) {
						event.preventDefault();
						deleteImage(newLi, file);
					});

					newLi.appendChild(imgElement);
					newLi.appendChild(deleteButton);
					document.querySelector('.file__list').appendChild(newLi);

					// FormData에 파일 추가
					formData.append('uploadedFiles[]', file);

					// 이미지 카운트 증가
					imageCount++;
				};

				reader.readAsDataURL(file);
			});

			// 파일 선택 창 비우기
			fileInput.value = null;
		});

		// 이미지 삭제
		function deleteImage(element, deletedFile) {
			element.remove();

			// 이미지 카운트 감소
			imageCount--;

			// FormData에서 삭제된 파일 제거
			const files = formData.getAll('uploadedFiles[]');
			const updatedFiles = files.filter((file) => file !== deletedFile);

			// FormData 갱신
			formData.delete('uploadedFiles[]');
			updatedFiles.forEach((file) => formData.append('uploadedFiles[]', file));
		}

		document.querySelector('#saveReview').addEventListener('click', function() {
			showLoader();

			let form = new FormData();

			// 리뷰 데이터 가져오기
			const rating = currentRating;
			const reviewContent = document.querySelector('#review_textarea').value;
			const userIdx = document.querySelector('#userIdx').value;
			const paymentItemIdx = document.querySelector('#paymentItemIdx').value;

			if (!rating || !reviewContent.trim()) {
				alert("별점과 리뷰 내용을 작성해주세요.");
				return;
			}

			// 메타데이터 추가
			form.append('rating', rating);
			form.append('reviewContent', reviewContent);
			form.append('userIdx', userIdx);
			form.append('paymentItemIdx', paymentItemIdx);

			const files = formData.getAll('uploadedFiles[]');
			files.forEach((file) => form.append('uploadedFiles[]', file));

			// AJAX 요청 전송
			fetch('/api/review/create', {
					method: 'POST',
					body: form,
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						alert('리뷰가 성공적으로 등록되었습니다.');
						// 성공 후 리다이렉트 또는 초기화
						window.location.href = '/my/reviews';
						hideLoader();
					} else {
						hideLoader();
						alert('리뷰 등록에 실패했습니다.');
						console.error(data.message);
					}
				})
				.catch(error => {
					hideLoader();
					console.error('에러 발생:', error);
					alert('서버 요청 중 문제가 발생했습니다.');
				});
		});
	</script>

</body>

</html>