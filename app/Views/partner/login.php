<!DOCTYPE html>
<html lang="ko">

<style>
    .carousel-indicators {
        bottom: -3rem !important;
    }

    .carousel-indicators .dots {
        background-color: #67748e !important;
        opacity: 0.3 !important;
    }

    .carousel-indicators .active {
        opacity: 1 !important;
    }

    .carousel-control-prev {
        left: -4rem !important;
    }

    .carousel-control-next {
        right: -4rem !important;
    }

    .carousel-control-prev, .carousel-control-next {
        color: #67748e !important;
    }

    .carousel-control-prev, .carousel-control-next i {
        font-size: 2rem !important;
        line-height: 1.0 !important;
    }

    .card.dashboard {
        min-height: 465px;
        height: auto;
    }

    .chart-canvas {
        height: 300px !important;
    }
</style>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/head.php"; ?>
<!-- Head -->

<body>

    <main class="new_login">
        <header class="px-7 py-1 d-flex align-items-center justify-content-between" style="background: #30333E; position: sticky; top: 0; left: 0; right: 0; z-index: 100;">
            <h1 class="w-4"><img src="/assets/app/images/common/logo_text_white.png" class="w-100" alt="logo"></h1>
            <button type="button" class="btn mb-0" style="background: #714CDC; color: #fff;"  onclick="window.open('https://tally.so/r/nWEqpk', '_blank')">숙소 입점 신청하기</button>
        </header>

        <section class="d-flex align-items-center justify-content-center bannerSection">
            <div class="d-flex align-items-center justify-content-between w-100 px-10" style="height: 90vh; background: url('/assets/manage/images/login_bg.png') no-repeat center center; background-size: cover;" >
                <div style="color: #fff;" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="1000" class="txt_box">
                    <p style="font-size: 3.0rem; font-weight: 700;" class="mb-5 tit">빈 방을 모두<br>만실로 만들어 보세요.</p>
                    <p style="font-size: 1.4rem; fon-weight: 500;" class="txt">지금 <span style="color: #FED91A; font-weight: 600; ">108,159명</span>이 뭉클딜을 기다리고 있어요.</p>
                </div>

                <div class="card card-plain p-3" style="background: #fff; width: 30%; border-radius: 40px;">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h3 class="font-weight-bolder">어서오세요 !</h3>
                    </div>
                    <div class="card-body">
                        <form id="login-form" role="form">
                            <label>이메일</label>
                            <div class="mb-3">
                                <input type="email" id="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>비밀번호</label>
                            <div class="mb-3">
                                <input type="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="remember-me" checked="">
                                <label class="form-check-label" for="remember-me">ID 기억하기</label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn w-100 mt-4 mb-0" style="background: #714CDC; color: #fff;">로그인</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer pt-0 px-lg-2 px-1">
                        <p class="mb-4 text-sm mx-auto  text-center">
                            아직 파트너가 아니신가요?

                            <a href="https://tally.so/r/nWEqpk" class="font-weight-bold" style="color: #cb0c9f;" target="_blank">&nbsp;&nbsp;파트너 가입하기</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="d-flex align-items-center justify-content-center thinkSection" style="background: #F5F6F7;">
            <div class="w-100 px-10 py-8">
                <h3 style="font-size: 2.4rem; margin-bottom: 6rem; text-align: center;" data-aos="fade-up">뭉클트립과 많은 호텔리어는 고민했어요</h3>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <ul class="list-unstyled d-flex gap-3">
                        <li style="background: #fff; border-radius: 20px; width: 33%;" class="p-4">
                            <span class="d-inline-block" style="font-size: 14px; font-weight: 600; border-radius: 2rem; box-sizing: border-box; padding: 4px 8px; color: #714CDC; background: #F4F0FF;">Think 1</span>
                            <h4 style="font-size: 24px; font-weight: 600; margin: 8px 0 20px;">평일/비수기 공실에 대한<br>빠른 판매 방법</h4>
                            <p style="font-size: 18px;">남는 객실을 한 방에<br>판매해버릴 수는 없을까?</p>
                        </li>
                        <li style="background: #fff; border-radius: 20px; width: 33%;" class="p-4">
                            <span class="d-inline-block" style="font-size: 14px; font-weight: 600; border-radius: 2rem; box-sizing: border-box; padding: 4px 8px; color: #FF3F3F; background: #FFECEC;">Think 2</span>
                            <h4 style="font-size: 24px; font-weight: 600; margin: 8px 0 20px;">우리 호텔을 방문하는<br>고객만족도를 올릴 수 있는 방법</h4>
                            <p style="font-size: 18px;">단순 특가 말고 우리 호텔의 가치를<br>잘 알릴 수 있는 재밌는 프로모션 어디 없을까?</p>
                        </li>
                        <li style="background: #fff; border-radius: 20px; width: 33%;" class="p-4">
                            <span class="d-inline-block" style="font-size: 14px; font-weight: 600; border-radius: 2rem; box-sizing: border-box; padding: 4px 8px; color: #FED91A; background: #FFFBE8;">Think 3</span>
                            <h4 style="font-size: 24px; font-weight: 600; margin: 8px 0 20px;">과도한 OTA 수수료를<br>줄일 수 있는 방법</h4>
                            <p style="font-size: 18px;">수수료, 광고료 부담없는<br>착한 OTA는 없을까?</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="d-flex align-items-center justify-content-center mockupSection">
            <div class="w-100 px-10 py-8 d-flex flex-column align-items-center">
                <div class="d-flex align-items-center gap-7 mb-7" style="flex-direction: row-reverse;">
                    <div class="txt_box" data-aos="fade-left" data-aos-duration="1000">
                        <h3 style="font-size: 2.4rem; margin-bottom: 2rem;">세상에 없던 공동수요 기반<br>맞춤 여행딜 플랫폼 <span style="color: #714CDC;">'뭉클트립'</span></h3>
                        <p style="font-size: 1.4rem;">빈 방을 만실로 만들어 보세요</p>
                    </div>
                    <p data-aos="fade-right"><img src="/assets/manage/images/login/mobile_mockup.png" alt="image"></p>
                </div>

                <div class="d-flex align-items-center gap-11 slide_wrap">
                    <div class="txt_box" data-aos="fade-right" data-aos-duration="1000">
                        <h3 style="font-size: 2.4rem; margin-bottom: 2rem;">뭉클딜은 어떻게 작동하나요?</h3>
                        <p style="font-size: 1.4rem;">공동의 수요를 모아 판매자에게 연결해드려요</p>
                    </div>

                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="width: 22rem;" data-aos="fade-left">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active dots" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class="dots"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class="dots"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4" class="dots"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5" class="dots"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6" class="dots"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6" aria-label="Slide 7" class="dots"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7" aria-label="Slide 8" class="dots"></button>
                        </div>
                        <div class="carousel-inner" style="border-radius: 40px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 15%);">
                            <div class="carousel-item active" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_01.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_02.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_03.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_04.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_05.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_06.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_07.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                            <div class="carousel-item" style="width: 100%;">
                                <img src="/assets/partner/images/moongcle_gif_08.gif" class="d-block w-100" alt="..." style="width: 100%;">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
                            <span class="visually-hidden">Next</span>
                        </button>   
                    </div>                    
                </div>
            </div>
        </section>

        <section class="d-flex align-items-center justify-content-center partnerSection" style="background: #F5F6F7;">
            <div class="w-100 px-10 py-8">
                <h3 style="font-size: 2.4rem; margin-bottom: 6rem; text-align: center;" data-aos="fade-up">파트너 뭉클트립 제휴 후기</h3>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <ul class="list-unstyled d-flex gap-3">
                        <li style="background: #fff; border-radius: 20px; width: 33%;" class="p-4">
                            <h4 style="font-size: 24px; font-weight: 600; margin: 8px 0 20px;">평일 점유율이 작년 대비 올랐어요!</h4>
                            <p style="font-size: 18px;">
                                공실률 해결은 영업 활동의<br>
                                가장 중요한 부분이에요!<br>
                                평일 공실을 활용하여 다양한 뭉클딜을<br>
                                발송하여 매출이 크게 올랐어요
                            </p>
                            <p class="mt-4 d-flex justify-content-end"><img src="/assets/manage/images/login/review_profile01.png" alt="profile" class="w-20"></p>
                        </li>
                        <li style="background: #fff; border-radius: 20px; width: 33%;" class="p-4">
                            <h4 style="font-size: 24px; font-weight: 600; margin: 8px 0 20px;">고객 만족도가 크게 올랐어요!</h4>
                            <p style="font-size: 18px;">
                                고객 유형별 수요 정보를 한 눈에 보기 쉬워서<br>
                                좋았어요. 기존에는 OTA 판매를 진행하면서<br>
                                우리 고객은 무엇을 좋아할지 고민이 많았는데,<br>
                                이제는 맞춤형으로 제안할 수 있어요.
                            </p>
                            <p class="mt-4 d-flex justify-content-end"><img src="/assets/manage/images/login/review_profile02.png" alt="profile" class="w-20"></p>
                        </li>
                        <li style="background: #fff; border-radius: 20px; width: 33%;" class="p-4">
                            <h4 style="font-size: 24px; font-weight: 600; margin: 8px 0 20px;">뭉클트립 절대 쓰지 마세요!</h4>
                            <p style="font-size: 18px;">
                                뭉클트립 절대 쓰지 마세요!<br>
                                왜냐면 저만 쓰고 싶거든요^^<br>
                                지금껏 10년 이상 숙박업에 종사하면서 가장<br>
                                혁신적이고 효율적인 판매 유통 방식이에요.
                            </p>
                            <p class="mt-4 d-flex justify-content-end"><img src="/assets/manage/images/login/review_profile03.png" alt="profile" class="w-20"></p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="d-flex align-items-center justify-content-center customerSection" style="background: #F8F7F9;">
            <div class="w-100 px-10 py-8">
                <h3 style="font-size: 2.4rem; margin-bottom: 6rem; text-align: center;" data-aos="fade-up">지금 바로 뭉클딜을 사용하여<br><span style="color: #714CDC;">고객의 마음을 사로잡아볼까요?</span></h3>

                <p class="w-100" data-aos="fade-up" data-aos-duration="1000"><img src="/assets/manage/images/login/reviews.png" alt="reviews" class="w-100 reviews"></p>
                <p class="w-100" data-aos="fade-up" data-aos-duration="1000"><img src="/assets/manage/images/login/m_reviews.png" alt="reviews" class="w-100 d-none m_reviews"></p>
            </div>
        </section>

        <section class="d-flex align-items-center justify-content-center lastSection">
            <div class="w-100 px-10 py-8 d-flex flex-column align-items-center">
                <div class="d-flex align-items-center w-80 justify-content-between">
                    <div>
                        <h3 style="font-size: 2.4rem; margin-bottom: 2rem;">지금 바로 무료로 뭉클딜을 사용해보세요</h3>
                        <p style="font-size: 1.4rem;">뭉클딜을 무제한 발송하며<br>판매를 늘려보세요</p>
                        <button type="button" class="btn mb-0 pc_button" style="background: #714CDC; color: #fff; margin-top: 2rem;"  onclick="window.open('https://tally.so/r/nWEqpk', '_blank')">입점 신청하기</button>
                    </div>

                    <div id="lottie-container" class="ico ico-logo"  style="width: 13%;"></div>

                    <button type="button" class="btn mb-0 m_button d-none" style="background: #714CDC; color: #fff; margin-top: 2rem;"  onclick="window.open('https://tally.so/r/nWEqpk', '_blank')">입점 신청하기</button>
                </div>
            </div>
        </section>

        <footer style="background: #F5F6F7;">
            <div style="color: #696D70; font-size: 1.4rem; text-align: center; box-sizing: border-box; padding: 4rem;">
                <p>주식회사 호놀룰컴퍼니 / 대표: 김범수 / 주소: 경기도 안양시 동안구 시민대로327번길 11-41, 506호 / 대표번호: 070-7535-2694 / 이메일: contact@honolulu.co.kr</p>
                <p>사업자등록번호: 485-87-02613 / 통신판매업신고번호: 제2023-안양동안-0792호 / 관광사업자 등록번호: 제2023-000006호</p>
                <p>개인정보보호 책임자: 김범수 / 한국관광공사 인증 관광벤처기업</p>
            </div>
        </footer>
    </main>
	
	<!--   Core JS Files   -->
	<script src="/assets/manage/js/core/popper.min.js"></script>
	<script src="/assets/manage/js/core/bootstrap.min.js"></script>
	<script src="/assets/manage/js/plugins/perfect-scrollbar.min.js"></script>
	<script src="/assets/manage/js/plugins/smooth-scrollbar.min.js"></script>
	<script src="/assets/manage/js/plugins/choices.min.js"></script>
	<script src="/assets/manage/js/plugins/quill.min.js"></script>
	<script src="/assets/manage/js/plugins/flatpickr.min.js"></script>
	<script src="/assets/manage/js/plugins/dropzone.min.js"></script>
	<!-- Kanban scripts -->
	<script src="/assets/manage/js/plugins/dragula/dragula.min.js"></script>
	<script src="/assets/manage/js/plugins/jkanban/jkanban.js"></script>
	<script src="/assets/manage/js/plugins/chartjs.min.js"></script>
	<script src="/assets/manage/js/plugins/threejs.js"></script>
	<script src="/assets/manage/js/plugins/orbit-controls.js"></script>
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
				window.location.href = '/partner/dashboard'; // 성공 후 대시보드로 이동
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

    <script>
        AOS.init();
    </script>

    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // 애니메이션을 렌더할 HTML 요소
            renderer: 'svg', // SVG 형태로 렌더링
            loop: true, // 반복 재생 여부
            autoplay: true, // 자동 재생 여부
            path: '/assets/app/json/moongcleAnimation.json' // Lottie JSON 파일의 경로
        });

    </script>
</body>

</html>