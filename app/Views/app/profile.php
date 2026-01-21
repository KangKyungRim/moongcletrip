<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];

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
				<h2 class="header-tit__center">프로필 수정</h2>
			</div>
		</header>

		<div class="container__wrap">
			<div class="join__wrap">
				<!-- 사용자 설정 이미지 -->
				<div class="user-img__wrap">
					<span class="user-img">
						<img src="/assets/app/images/common/no_profile.jpg" alt="">
					</span>
					<p class="name"><?= $user->user_nickname; ?></p>
				</div>
				<!-- //사용자 설정 이미지 -->

				<div class="input__wrap" style="margin-top: 5rem;">
					<p class="title">개인정보</p>
					<div class="input__con">
						<label for="email" class="input-label">이메일</label>
                        <input id="email" type="text" class="input-default" value="<?= $user->user_email; ?>" readonly style="color:rgb(147, 147, 147);">
					</div>
					<div class="input__con">
						<label for="nickname" class="input-label">닉네임</label>
						<input id="nickname" type="text" class="input-default" placeholder="닉네임을 입력해주세요" value="<?= $user->user_nickname; ?>">
					</div>
					<!-- <div class="input__con">
						<label for="tel" class="input-label">연락처</label>
						<div class="input__btn">
							<input id="tel" type="tel" class="input-default" placeholder="연락처를 입력해주세요" value="01012341234">
							<button type="button" class="btn-full__black">인증</button>
						</div>
					</div>
					<div class="input__con has-time">
						<input id="name" type="text" class="input-default" placeholder="인증번호를 입력하세요">
						<span class="expire-time">05:00</span>
					</div> -->
				</div>

				<div class="join__btn type-bottom">
					<button type="button" class="btn-txt__underline btn-txt__small fnOpenPop" data-name="deleteAccountAlert">탈퇴하기</button>
				</div>
			</div>
			<!-- 하단 버튼 영역 -->
			<div class="bottom-fixed__wrap">
				<div class="btn__wrap">
					<button id="update" class="btn-full__primary disabled" disabled>변경 완료</button>
				</div>
			</div>
			<!-- //하단 버튼 영역 -->
		</div>

		<!-- 알럿 팝업 -->
		<div id="deleteAccountAlert" class="layerpop__wrap type-alert">
			<div class="layerpop__container">
				<div class="layerpop__contents">
					<div class="tit__wrap">
						<p class="title">탈퇴를 계속 진행하시겠습니까?</p>
						<p class="desc">
							탈퇴한 계정은 복구할 수 없으며, 동일한 아이디로는 재가입이 불가능합니다.
						</p>
						<p class="desc">
							탈퇴 시 활동 내역들은 복구가 불가능합니다.
						</p>
						<p class="desc">
							그래도 계속 진행하시겠습니까?
						</p>
					</div>
				</div>
				<div class="layerpop__footer">
					<div class="btn__wrap">
						<button class="btn-full__secondary fnClosePop">아니요</button>
						<button id="deleteAccount" class="btn-full__primary">탈퇴하기</button>
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
		document.addEventListener("DOMContentLoaded", function() {
			const nicknameInput = document.getElementById("nickname");
			const updateButton = document.getElementById("update");
			const deleteButton = document.getElementById("deleteAccount");
			const originalNickname = nicknameInput.value; // 원래 닉네임

			// 닉네임 변경 감지
			nicknameInput.addEventListener("input", function() {
				const currentNickname = nicknameInput.value.trim();
				if (currentNickname !== originalNickname && currentNickname.length > 0) {
					updateButton.classList.remove("disabled");
					updateButton.disabled = false;
				} else {
					updateButton.classList.add("disabled");
					updateButton.disabled = true;
				}
			});

			// 변경 완료 버튼 클릭 이벤트
			updateButton.addEventListener("click", function() {
				if (updateButton.disabled) return;

				const updatedNickname = nicknameInput.value.trim();
				if (!updatedNickname) {
					alert("닉네임을 입력해주세요.");
					return;
				}

				// API 호출
				fetch("/api/user/update-profile", {
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: JSON.stringify({
							user_nickname: updatedNickname,
						}),
					})
					.then((response) => {
						if (!response.ok) {
							throw new Error("서버 요청이 실패했습니다.");
						}
						return response.json();
					})
					.then((data) => {
						if (data.success) {
							history.replaceState(null, "", "<?= $_ENV['APP_HTTP']; ?>/mypage");
							window.location.href = "/mypage";
						} else {
							alert(data.message || "업데이트 중 오류가 발생했습니다.");
						}
					})
					.catch((error) => {
						console.error("API 요청 중 오류:", error);
						alert("업데이트 요청 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.");
					});
			});

			deleteButton.addEventListener("click", function() {
				fetch("/api/user/delete-account", {
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: {},
					})
					.then((response) => {
						if (!response.ok) {
							throw new Error("서버 요청이 실패했습니다.");
						}
						return response.json();
					})
					.then((data) => {
						if (data.success) {
							history.replaceState(null, "", "<?= $_ENV['APP_HTTP']; ?>/mypage");
							window.location.href = "/users/logout";
						} else {
							alert(data.message || "탈퇴 요청 중 오류가 발생했습니다.");
						}
					})
					.catch((error) => {
						console.error("API 요청 중 오류:", error);
						alert("탈퇴 요청 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.");
					});
			});
		});
	</script>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

</body>

</html>