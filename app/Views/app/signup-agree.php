<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

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
				<h2 class="header-tit__center">약관 동의</h2>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">
			<section class="join__wrap">
				<div class="tit__wrap">
					<p class="ft-xxxl">설레는 여행 준비</p>
					<p class="ft-default">
						원하는 여행을 등록하면 뭉클트립이 <br>
						딱 맞는 제안을 드릴게요!
					</p>
				</div>

				<!-- 전체 동의 -->
				<div class="checkbox__wrap check-list__wrap agree__list">
					<div class="check-list__all">
						<div class="checkbox">
							<input type="checkbox" id="agreeAll" />
							<label for="agreeAll">
								<span class="ft-default">전체 동의</span>
							</label>
						</div>
					</div>
					<div class="check-list__con">
						<div class="checkbox">
							<input type="checkbox" id="agreeAge" />
							<label for="agreeAge">
								<p class="flex-between">
									<span class="ft-s">[필수] 만 14세 이상입니다</span>
								</p>
							</label>
						</div>
						<div class="checkbox">
							<input type="checkbox" id="agreeTerms" />
							<label for="agreeTerms">
								<p class="flex-between">
									<span class="ft-s">[필수] 서비스 이용약관</span>
									<a href="" class="btn-checkbox__more"><i class="ico ico-arrow__right"></i></a>
								</p>
							</label>
						</div>
						<div class="checkbox">
							<input type="checkbox" id="agreePrivacy" />
							<label for="agreePrivacy">
								<p class="flex-between">
									<span class="ft-s">[필수] 개인정보 처리방침</span>
									<a href="" class="btn-checkbox__more"><i class="ico ico-arrow__right"></i></a>
								</p>
							</label>
						</div>
						<div class="checkbox">
							<input type="checkbox" id="agreeLocation" />
							<label for="agreeLocation">
								<p class="flex-between">
									<span class="ft-s">[필수] 위치기반 이용약관</span>
									<a href="" class="btn-checkbox__more"><i class="ico ico-arrow__right"></i></a>
								</p>
							</label>
						</div>
						<div class="checkbox">
							<input type="checkbox" id="agreeMarketing" />
							<label for="agreeMarketing">
								<p class="flex-between">
									<span class="ft-s">[선택] 마케팅 수신 동의(혜택알림 푸시, 메일, 문자)</span>
								</p>
							</label>
						</div>
					</div>
				</div>
				<!-- //전체 동의 -->
			</section>

			<!-- 하단 버튼 영역 -->
			<div class="bottom-fixed__wrap">
				<div class="btn__wrap">
					<button id="nextButton" class="btn-full__primary disabled" disabled>다음</button>
				</div>
			</div>
			<!-- //하단 버튼 영역 -->
		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const currentType = '<?= $_GET['type'] ?? 'email'; ?>';
			const agreeAllCheckbox = document.getElementById("agreeAll"); // 전체 동의
			const agreeAgeCheckbox = document.getElementById("agreeAge"); // 만 14세 이상
			const agreeTermsCheckbox = document.getElementById("agreeTerms"); // 서비스 이용약관
			const agreePrivacyCheckbox = document.getElementById("agreePrivacy"); // 개인정보 처리방침
			const agreeLocationCheckbox = document.getElementById("agreeLocation"); // 위치기반 이용약관
			const agreeMarketingCheckbox = document.getElementById("agreeMarketing"); // 마케팅 수신 동의(선택)
			const nextButton = document.getElementById("nextButton");

			const params = new URLSearchParams(window.location.search); // 현재 URL의 쿼리 스트링 가져오기
			const returnParam = params.get('return');

			// 필수 체크박스를 배열로 묶어서 관리
			const requiredCheckboxes = [agreeAgeCheckbox, agreeTermsCheckbox, agreePrivacyCheckbox, agreeLocationCheckbox];

			// 전체 동의 체크박스가 변경될 때
			agreeAllCheckbox.addEventListener("change", () => {
				const isChecked = agreeAllCheckbox.checked;
				requiredCheckboxes.forEach(checkbox => checkbox.checked = isChecked);
				agreeMarketingCheckbox.checked = isChecked;
				updateButtonState();
			});

			// 개별 체크박스가 변경될 때 전체 동의 체크 여부 확인
			requiredCheckboxes.forEach(checkbox => {
				checkbox.addEventListener("change", () => {
					agreeAllCheckbox.checked = requiredCheckboxes.every(checkbox => checkbox.checked);
					updateButtonState();
				});
			});

			// 버튼 활성화 여부를 업데이트하는 함수
			function updateButtonState() {
				const isAllRequiredChecked = requiredCheckboxes.every(checkbox => checkbox.checked);
				nextButton.disabled = !isAllRequiredChecked;
				nextButton.classList.toggle("disabled", !isAllRequiredChecked);
			}

			// 개별 선택 체크박스가 변경될 때 버튼 상태를 업데이트
			agreeMarketingCheckbox.addEventListener("change", updateButtonState);

			// 초기 버튼 상태 업데이트
			updateButtonState();

			nextButton.addEventListener("click", () => {
				// 각 체크박스 상태를 URL 파라미터로 추가
				const queryParams = new URLSearchParams({
					agree_age: agreeAgeCheckbox.checked ? 1 : 0,
					agree_terms: agreeTermsCheckbox.checked ? 1 : 0,
					agree_privacy: agreePrivacyCheckbox.checked ? 1 : 0,
					agree_location: agreeLocationCheckbox.checked ? 1 : 0,
					agree_marketing: agreeMarketingCheckbox.checked ? 1 : 0,
				});

				// 가입 페이지로 이동하면서 체크박스 상태 전달
				if (currentType == 'email') {
					window.location.href = `/users/signup-email?${queryParams.toString()}`;
				} else {
					const formData = {
						agreeAge: agreeAgeCheckbox.checked ? 1 : 0,
						agreeTerms: agreeTermsCheckbox.checked ? 1 : 0,
						agreePrivacy: agreePrivacyCheckbox.checked ? 1 : 0,
						agreeLocation: agreeLocationCheckbox.checked ? 1 : 0,
						agreeMarketing: agreeMarketingCheckbox.checked ? 1 : 0,
					};

					try {
						fetch("/api/users/update-agrees", {
								method: "POST",
								headers: {
									"Content-Type": "application/json",
								},
								body: JSON.stringify(formData),
							})
							.then(response => response.json())
							.then(data => {
								if (data.success) {
									const redirectUrl = returnParam ?
										`/users/signup-complete?return=${encodeURIComponent(returnParam)}` :
										'/users/signup-complete';
									window.location.href = redirectUrl;
								} else {
									alert('정보 입력 중 문제가 발생했습니다. 입력하신 정보를 확인 후 다시 시도해주시기 바랍니다.');
								}
							})
							.catch(error => {
								console.error('Error:', error);
								alert('정보 입력 중 문제가 발생했습니다. 입력하신 정보를 확인 후 다시 시도해주시기 바랍니다.');
							});
					} catch (error) {
						console.error('Error:', error);
						alert('가입 중 문제가 발생했습니다. 입력하신 정보를 확인 후 다시 시도해주시기 바랍니다.');
					}
				}
			});
		});
	</script>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

	<?php if ($_ENV['ANALYTICS_ENV'] == 'production' || $_ENV['ANALYTICS_ENV'] == 'staging') : ?>
		<script>
			document.addEventListener("DOMContentLoaded", () => {
				window.dataLayer.push({
					event: "sign_up",
				});
			});
		</script>
	<?php endif; ?>

</body>

</html>