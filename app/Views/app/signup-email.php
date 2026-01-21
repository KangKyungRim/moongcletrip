<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<?php

$agree_age = $_GET['agree_age'] ?? 0;
$agree_terms = $_GET['agree_terms'] ?? 0;
$agree_privacy = $_GET['agree_privacy'] ?? 0;
$agree_location = $_GET['agree_location'] ?? 0;
$agree_marketing = $_GET['agree_marketing'] ?? 0;

// 체크박스 선택 상태에 따라 다음 로직을 수행
if ($agree_age != 1 || $agree_terms != 1 || $agree_privacy != 1 || $agree_location != 1) {
	echo "<script>alert('잘못된 접근입니다. 홈으로 이동합니다.');</script>";
	echo "<script>window.location.href = '/';</script>";
	exit;
}

?>

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
				<h2 class="header-tit__center">회원 가입</h2>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">
			<section class="join__wrap">
				<div class="input__wrap">
					<div class="input__con">
						<label for="email" class="input-label">이메일</label>
						<input id="email" type="email" class="input-default" placeholder="이메일을 입력해주세요">
					</div>
					<div class="input__con">
						<label for="name" class="input-label">이름</label>
						<input id="name" type="text" class="input-default" placeholder="이름을 입력해주세요">
					</div>
					<div class="input__con">
						<label for="password" class="input-label">비밀번호 입력</label>
						<input id="password" type="password" class="input-default" placeholder="영문, 특수문자, 숫자 포함 최소 8자 입력">
						<p class="error-txt d-none">숫자, 영문, 특수문자 조합 최소 8자(특수문자:$@$!%*#?&)</p>
					</div>
					<div class="input__con">
						<label for="passwordRepeat" class="input-label">비밀번호 재입력</label>
						<input id="passwordRepeat" type="password" class="input-default" placeholder="위에 작성한 비밀번호 재입력">
						<p class="error-txt d-none">비밀번호가 동일하지 않습니다.</p>
					</div>
				</div>
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
			const emailInput = document.getElementById("email");
			const nameInput = document.getElementById("name");
			const passwordInput = document.getElementById("password");
			const passwordConfirmInput = document.getElementById("passwordRepeat");
			const nextButton = document.getElementById("nextButton");
			const passwordError = document.querySelectorAll(".error-txt")[0];
			const passwordConfirmError = document.querySelectorAll(".error-txt")[1];

			function validateEmail(email) {
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				return emailRegex.test(email);
			}

			function validatePassword(password) {
				const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[$@!%*#?&])[A-Za-z\d$@!%*#?&]{8,}$/;
				return passwordRegex.test(password);
			}

			function checkValidation() {
				const isEmailValid = validateEmail(emailInput.value);
				const isNameValid = nameInput.value.trim() !== "";
				const isPasswordValid = validatePassword(passwordInput.value);
				const isPasswordMatch = passwordInput.value === passwordConfirmInput.value;

				passwordError.style.display = isPasswordValid ? "none" : "block";
				passwordConfirmError.style.display = isPasswordMatch ? "none" : "block";

				nextButton.disabled = !(isEmailValid && isNameValid && isPasswordValid && isPasswordMatch);
				nextButton.classList.toggle("disabled", !(isEmailValid && isNameValid && isPasswordValid && isPasswordMatch));
			}

			emailInput.addEventListener("input", checkValidation);
			nameInput.addEventListener("input", checkValidation);
			passwordInput.addEventListener("input", checkValidation);
			passwordConfirmInput.addEventListener("input", checkValidation);

			document.addEventListener("keydown", (event) => {
				if (event.key === "Enter") {
					if (!nextButton.disabled) {
						nextButton.click();
					}
				}
			});

			nextButton.addEventListener('click', async function() {
				const formData = {
					email: emailInput.value,
					name: nameInput.value,
					password: passwordInput.value,
					agreeAge: <?= $agree_age; ?>,
					agreeTerms: <?= $agree_terms; ?>,
					agreePrivacy: <?= $agree_privacy; ?>,
					agreeLocation: <?= $agree_location; ?>,
					agreeMarketing: <?= $agree_marketing; ?>,
				};

				try {
					const response = await fetch('/api/users/signup-email', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
						},
						body: JSON.stringify(formData),
					});

					// 응답 처리
					const result = await response.json();

					if (result.success) {
						window.location.href = '/users/signup-complete';
					} else {
						alert('가입 중 문제가 발생했습니다. 입력하신 정보를 확인 후 다시 시도해주시기 바랍니다.');
					}
				} catch (error) {
					console.error('Error:', error);
					alert('가입 중 문제가 발생했습니다. 입력하신 정보를 확인 후 다시 시도해주시기 바랍니다.');
				}
			});
		});
	</script>

</body>

</html>