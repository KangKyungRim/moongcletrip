<!DOCTYPE html>
<html lang="ko">

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
        <div class="container">
            <div class="sidenav-header sidenav_header_line_height">
                <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="/manage/dashboard">
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
                            <i class="fa fa-chart-pie opacity-6 me-1"></i>
                            뭉클트립은
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="/manage/register">
                            <i class="fas fa-user-circle opacity-6  me-1"></i>
                            파트너 가입하기
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="/manage/login">
                            <i class="fas fa-key opacity-6  me-1"></i>
                            파트너 로그인
                        </a>
                    </li>
                </ul>
                <li class="nav-item d-flex align-items-center">
                    <a class="btn btn-round btn-sm mb-0 btn-outline-white me-2" target="_blank" href="https://tally.so/r/nWEqpk">입점 문의하기</a>
                </li>
                <ul class="navbar-nav d-lg-block d-none">
                    <li class="nav-item">
                        <a href="/" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-light">뭉클트립 앱</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <main class="main-content  mt-0">
        <section class="min-vh-100 mb-8">
            <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('/assets/manage/images/curved-images/curved14.jpg');">
                <span class="mask bg-gradient-dark opacity-6"></span>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 text-center mx-auto">
                            <h1 class="text-white mb-2 mt-5">어서오세요!</h1>
                            <p class="text-lead text-white">파트너로 가입하고 놀라운 매출을 경험해보세요.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row mt-lg-n10 mt-md-n11 mt-n10">
                    <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                        <div class="card z-index-0">
                            <div class="card-body">
                                <form id="register-form" role="form text-left">
                                    <div class="mb-3">
                                        <input type="text" id="name" class="form-control" placeholder="이름" aria-label="Name" aria-describedby="name-addon" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" id="email" class="form-control" placeholder="이메일" aria-label="Email" aria-describedby="email-addon" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="tel" id="mobileNumber" class="form-control" placeholder="휴대폰 번호" aria-label="MobileNumber" aria-describedby="number-addon" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" id="password" class="form-control" placeholder="비밀번호" aria-label="Password" aria-describedby="password-addon" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" id="passwordConfirm" class="form-control" placeholder="비밀번호 확인" aria-label="Password Verify" aria-describedby="password-addon" required>
                                    </div>
                                    <div class="form-check form-check-info text-left">
                                        <input class="form-check-input" type="checkbox" value="" id="terms" checked>
                                        <label class="form-check-label" for="terms">
                                            <a href="javascript:;" class="text-dark font-weight-bolder">이용약관</a>에 동의합니다.
                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">가입하기</button>
                                    </div>
                                    <p class="text-sm mt-3 mb-0">이미 파트너 계정이 있으신가요? <a href="javascript:;" class="text-dark font-weight-bolder">파트너 로그인</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
    </main>
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
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            // 폼 데이터 가져오기
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const mobileNumber = document.getElementById('mobileNumber').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('passwordConfirm').value;
            const terms = document.getElementById('terms').checked;

            // 검증
            if (password !== passwordConfirm) {
                alert("비밀번호가 일치하지 않습니다.");
                return;
            }

            if (!terms) {
                alert("이용약관에 동의해야 합니다.");
                return;
            }

            // POST로 데이터 전송
            const response = await fetch('/api/partners/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    mobileNumber: mobileNumber,
                    password: password
                })
            });

            const result = await response.json();

            if (response.ok) {
                alert('회원가입 성공! 로그인 페이지로 이동합니다.');
                window.location.href = '/manage/login'; // 로그인 페이지로 이동
            } else {
                alert('회원가입에 실패하였습니다.');
            }
        });
    </script>
</body>

</html>