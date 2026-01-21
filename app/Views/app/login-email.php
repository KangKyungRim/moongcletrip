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
				<h2><span class="blind">로그인</span></h2>
				<button type="button" class="btn-close" onclick="previousPage()"><span class="blind">닫기</span></button>
			</div>
		</header>

		<div class="container__wrap">
			<!-- 로그인 화면 -->
			<div class="login__wrap">
				<p class="logo"><img src="/assets/app/images/common/logo_text_big.png" alt="뭉클트립"></p>
				<div class="input__wrap">
					<div class="input__con">
						<label for="email" class="input-label">이메일</label>
						<input id="email" type="email" class="input-default" placeholder="이메일을 입력해주세요">
					</div>
					<div class="input__con">
						<label for="password" class="input-label">비밀번호 입력</label>
						<input id="password" type="password" class="input-default" placeholder="비밀번호 입력해주세요">
					</div>
				</div>
				<button id="loginButton" type="button" class="btn-full__primary">로그인</button>

				<div class="join__btn">
					<button type="button" class="btn-txt__underline" onclick="gotoSignupAgree()">이메일로 회원가입</button>
				</div>

			</div>
			<!-- //로그인 화면 -->
		</div>

		<!-- 알럿 팝업 -->
		<div id="alert" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">입력한 정보를 다시 확인해주세요.</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__primary fnClosePop">확인</button>
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
		document.addEventListener("DOMContentLoaded", () => {
			const loginButton = document.getElementById("loginButton");
			const emailInput = document.getElementById("email");
			const passwordInput = document.getElementById("password");

			document.addEventListener("keydown", (event) => {
				if (event.key === "Enter") {
					if (!loginButton.disabled) {
						loginButton.click();
					}
				}
			});

			loginButton.addEventListener("click", async () => {
				const email = emailInput.value.trim();
				const password = passwordInput.value.trim();

				// 입력값 유효성 검사
				if (!email || !password) {
					fnOpenLayerPop('alert');
					return;
				}

				const loginData = {
					email: email,
					password: password
				};

				try {
					const response = await fetch('/api/users/login-email', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
						},
						body: JSON.stringify(loginData),
					});

					const result = await response.json();

					if (result.success) {
						window.location.href = '/mypage';
					} else {
						fnOpenLayerPop('alert');
					}
				} catch (error) {
					fnOpenLayerPop('alert');
				}
			});
		});
	</script>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

</body>

</html>