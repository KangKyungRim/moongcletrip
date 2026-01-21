<!DOCTYPE html>
<html lang="ko">

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="">
	<div class="container position-sticky z-index-sticky top-0">
		<div class="row">
			<div class="col-12">
				<!-- Navbar -->
				<nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
					<div class="container-fluid pe-0">
						<div class="sidenav-header sidenav_header_line_height">
							<a class="navbar-brand font-weight-bolder ms-lg-0 ms-3" href="/manage/dashboard">
								<img src="/assets/manage/images/logo-ct.png" class="navbar-brand-img h-50 border-radius-md" alt="main_logo">
								<span class="ms-1 font-weight-bold">뭉클트립</span>
							</a>
						</div>
						<button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon mt-2">
								<span class="navbar-toggler-bar bar1"></span>
								<span class="navbar-toggler-bar bar2"></span>
								<span class="navbar-toggler-bar bar3"></span>
							</span>
						</button>
						<div class="collapse navbar-collapse" id="navigation">
							<ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
								<li class="nav-item">
									<a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="/manage/dashboard">
										<i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
										뭉클트립은
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link me-2" href="/manage/register">
										<i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
										파트너 가입하기
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link me-2" href="/manage/login">
										<i class="fas fa-key opacity-6 text-dark me-1"></i>
										파트너 로그인
									</a>
								</li>
							</ul>
							<li class="nav-item d-flex align-items-center">
								<a class="btn btn-round btn-sm mb-0 btn-outline-primary me-2" target="_blank" href="https://tally.so/r/nWEqpk">입점 문의하기</a>
							</li>
							<ul class="navbar-nav d-lg-block d-none">
								<li class="nav-item">
									<a href="/" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-dark">뭉클트립 앱</a>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<!-- End Navbar -->
			</div>
		</div>
	</div>
	<main class="main-content mt-0">
		<section>
			<div class="page-header min-vh-75">
				<div class="container">
					<div class="row">
						<div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
							<div class="card card-plain mt-8">
								<div class="card-header pb-0 text-left bg-transparent">
									<h3 class="font-weight-bolder text-info text-gradient">어서오세요, 파트너 님!</h3>
									<p class="mb-0">파트너 계정으로 로그인 할 수 있어요.</p>
								</div>
								<div class="card-body">
									<form id="login-form" role="form">
										<label>Email</label>
										<div class="mb-3">
											<input type="email" id="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
										</div>
										<label>Password</label>
										<div class="mb-3">
											<input type="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" id="remember-me" checked="">
											<label class="form-check-label" for="remember-me">ID 기억하기</label>
										</div>
										<div class="text-center">
											<button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">로그인 하기</button>
										</div>
									</form>
								</div>
								<div class="card-footer text-center pt-0 px-lg-2 px-1">
									<p class="mb-4 text-sm mx-auto">
										아직 파트너가 아니신가요?
										<!-- <a href="/manage/register" class="text-info text-gradient font-weight-bold">파트너 가입하기</a> -->
                                        <a href="https://tally.so/r/nWEqpk" class="text-info text-gradient font-weight-bold" target="_blank">파트너 가입하기</a>
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
								<div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('/assets/manage/images/curved-images/curved6.jpg')"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
	<!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
	<footer class="footer py-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 mb-4 mx-auto text-center">
					<a href="/my_notice.php" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">공지사항</a>
					<a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=56454644253" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">사업자정보확인</a>
					<a href="https://www.instagram.com/moongcletrip" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">인스타그램</a>
					<a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">이용약관</a>
				</div>
			</div>
			<div class="row">
				<div class="col-8 mx-auto text-center mt-1">
					<p class="mb-0 text-secondary">
						<a href="#">
							<span class="color-dark-dark">(주)호놀룰루컴퍼니</span>
						</a>
					</p>
					<p class="small">한국관광공사 인증 관광벤처기업</p>
				</div>
			</div>
		</div>
	</footer>
	<!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
	<!--   Core JS Files   -->
	<script src="/assets/manage/js/core/popper.min.js"></script>
	<script src="/assets/manage/js/core/bootstrap.min.js"></script>
	<script src="/assets/manage/js/plugins/perfect-scrollbar.min.js"></script>
	<script src="/assets/manage/js/plugins/smooth-scrollbar.min.js"></script>
	<script>
		var win = navigator.platform.indexOf('Win') > -1;
		if (win && document.querySelector('#sidenav-scrollbar')) {
			var options = {
				damping: '0.5'
			}
			Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
		}
	</script>
	<script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.7"></script>

	<script>
		document.getElementById("login-form").addEventListener("submit", async function(event) {
			event.preventDefault();

			const email = document.getElementById("email").value;
			const password = document.getElementById("password").value;
			const rememberMe = document.getElementById("remember-me").checked;

			// POST로 데이터 전송
			const response = await fetch('/api/partners/email-login', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					email,
					password,
					rememberMe
				})
			});

			const result = await response.json();

			if (result.access_token) {
				setTokenCookie(result.access_token, 'accessTokenPartner');
				setTokenCookie(result.refresh_token, 'refreshTokenPartner');

				alert('로그인 성공');
				window.location.href = '/manage/dashboard'; // 성공 후 대시보드로 이동
			} else {
				if (result.error == 'Invalid credentials') {
					alert('이메일 인증 후 다시 로그인해주세요.');
				} else {
					console.error('Error:', result.error);
				}
			}
		});

		// JWT 토큰을 쿠키에 저장하는 함수
		function setTokenCookie(token, tokenName) {
			const expires = new Date();
			expires.setTime(expires.getTime() + (60 * 60 * 1000 * 24 * 30)); // 30일 만료 시간 예시
			document.cookie = `${tokenName}=${token}; expires=${expires.toUTCString()}; path=/; secure; SameSite=Lax`;
		}
	</script>

</body>

</html>