<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px--0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">신규 관리자 생성</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">신규 관리자 생성</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mx-auto">
                    <div class="card card-body p-5 w-50 mx-auto">
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">신규 관리자 생성 정보 입력</h6>
                            </div>

                            <hr class="horizontal gray-light my-3">

                            <div class="form-group row align-items-center">
                                <label for="partnerName" class="form-control-label col-sm-2">
                                    이름 <b class="text-danger">*</b>
                                </label>
                                <div class="col">
                                    <input class="form-control" type="text" id="partnerUserName" placeholder="이름을 입력해 주세요" required>
                                </div>
                            </div>

                            <hr class="horizontal gray-light my-3">

                            <div class="form-group row align-items-center">
                                <label for="partnerName" class="form-control-label col-sm-2">
                                    이메일 <b class="text-danger">*</b>
                                </label>
                                <div class="col">
                                    <input class="form-control" type="email" id="partnerUserEmail" placeholder="이메일을 입력해 주세요" required>
                                </div>
                            </div>

                            <hr class="horizontal gray-light my-3">

                            <div class="form-group row align-items-center">
                                <label for="partnerName" class="form-control-label col-sm-2">
                                    전화 번호 <b class="text-danger">*</b>
                                </label>
                                <div class="col">
                                    <input class="form-control" type="tel" id="partnerUserPhoneNumber" placeholder="전화 번호를 입력해 주세요" required>
                                </div>
                            </div>

                            <hr class="horizontal gray-light my-3">

                            <div class="form-group row align-items-center">
                                <label for="partnerName" class="form-control-label col-sm-2">
                                    비밀번호 <b class="text-danger">*</b>
                                </label>
                                <div class="col">
                                    <input class="form-control" type="password" id="partnerUserPassword" placeholder="비밀번호를 입력해 주세요 (영문, 특수문자, 숫자 포함 최소 8자 입력)" required>
                                    <p class="error-txt" style="display:none;">숫자, 영문, 특수문자 조합 최소 8자 (특수문자: $@$!%*#?&)</p>
                                </div>
                            </div>

                            <hr class="horizontal gray-light my-3">

                            <div class="form-group row align-items-center">
                                <label for="partnerName" class="form-control-label col-sm-2">
                                    비밀번호 확인 <b class="text-danger">*</b>
                                </label>
                                <div class="col">
                                    <input class="form-control" type="password" id="partnerUserPasswordRepeat" placeholder="비밀번호를 확인해 주세요 (영문, 특수문자, 숫자 포함 최소 8자 입력)" required>
                                    <p class="error-txt" style="display:none;">비밀번호가 일치하지 않습니다</p>
                                </div>
                            </div>

                            <hr class="horizontal gray-light my-3">
                        </div>
                    </div>

                    <!-- button wrap -->
                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0">취소</button>
                        <button type="button" id="submitForm" name="submitForm" class="btn bg-gradient-primary m-0 ms-2">생성하기</button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer py-5">
          <div class="container-fluid">
              <div class="row align-items-center justify-content-lg-between">
                  <div class="col-lg-6 mb-lg-0 mb-4">
                      <div class="copyright text-center text-sm text-muted text-lg-start">
                          © 2025,
                          <a href="https://www.moongcletrip.com" class="font-weight-bold" target="_blank">Honolulu Company</a>
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                          <li class="nav-item">
                              <a href="https://www.moongcletrip.com" class="nav-link text-muted" target="_blank">뭉클트립</a>
                          </li>
                          <li class="nav-item">
                              <a href="https://www.honolulu.co.kr/channels/L2NoYW5uZWxzLzE5NQ/pages/home" class="nav-link text-muted" target="_blank">호놀룰루컴퍼니</a>
                          </li>
                          <li class="nav-item">
                              <a href="https://www.instagram.com/moongcletrip/" class="nav-link text-muted" target="_blank">instagram</a>
                          </li>
                          <li class="nav-item">
                              <a href="https://www.moongcletrip.com" class="nav-link pe-0 text-muted" target="_blank">License</a>
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
      </footer>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/loading.php"; ?>

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


    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.7"></script>

    <script>
        // 취소 버튼 링크
        document.getElementById('cancelForm').addEventListener('click', async function() {
            window.location.href = '/manage/partner-user-list';
        });  
    </script>

    <script>
        // 비밀번호 유효성 검사
        document.addEventListener("DOMContentLoaded", () => {
            const createButton = document.getElementById("submitForm");
            const partnerUserName = document.getElementById('partnerUserName');
            const partnerUserEmail = document.getElementById('partnerUserEmail');
            const partnerUserPhoneNumber = document.getElementById('partnerUserPhoneNumber');
            const partnerUserPassword = document.getElementById('partnerUserPassword');
            const partnerUserPasswordRepeat = document.getElementById('partnerUserPasswordRepeat');
            const passwordError = document.querySelectorAll(".error-txt")[0];
			const passwordRepeatError = document.querySelectorAll(".error-txt")[1];

            // 비밀번호 체크
            function validatePassword(password) {
				const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[$@!%*#?&])[A-Za-z\d$@!%*#?&]{8,}$/;
				return passwordRegex.test(password);
			}

            // 오류 메시지 초기 상태
            passwordError.style.display = "none";
            passwordRepeatError.style.display = "none";

            function checkValidation() {
				const isPasswordValid = validatePassword(partnerUserPassword.value);
				const isPasswordMatch = partnerUserPassword.value === partnerUserPasswordRepeat.value;

                if (document.activeElement === partnerUserPassword) {
                    passwordError.style.display = isPasswordValid ? "none" : "block";
                } else {
                    passwordError.style.display = "none"; // focus를 잃으면 숨김
                }

				passwordRepeatError.style.display = isPasswordMatch ? "none" : "block";
			}

			partnerUserPassword.addEventListener("input", checkValidation);
			partnerUserPasswordRepeat.addEventListener("input", checkValidation);
            partnerUserPassword.addEventListener("focus", checkValidation);
            partnerUserPassword.addEventListener("blur", () => {passwordError.style.display = "none";});

            // 생성 이벤트
            createButton.addEventListener('click', async function () {

                // 쿠키에서 선택된 숙소 인덱스 가져오기
                function getCookie(name) {
                    const cookies = document.cookie.split('; ');
                    for (let cookie of cookies) {
                        const [key, value] = cookie.split('=');
                        if (key === name) return decodeURIComponent(value);
                    }
                    return null;
                }

                // 데이터
                const formData = {
                    partnerIdx: getCookie('partner'), 
                    partnerUserName: partnerUserName.value,
                    partnerUserEmail: partnerUserEmail.value,
                    partnerUserPhoneNumber: partnerUserPhoneNumber.value,
                    partnerUserPassword: partnerUserPassword.value,
                    partnerUserPasswordRepeat: partnerUserPasswordRepeat.value
                }

                // 필수 입력 필드 목록
                const requiredFields = [
                    { id: 'partnerUserName', message: '이름을 입력해 주세요' },
                    { id: 'partnerUserEmail', message: '이메일을 입력해 주세요' },
                    { id: 'partnerUserPhoneNumber', message: '전화 번호를 입력해 주세요' },
                    { id: 'partnerUserPassword', message: '비밀번호를 입력해 주세요 (영문, 특수문자, 숫자 포함 최소 8자 입력)' },
                    { id: 'partnerUserPasswordRepeat', message: '비밀번호 확인해 주세요 (영문, 특수문자, 숫자 포함 최소 8자 입력)' }
                ];

                // 비어있는 필드 찾기
                const emptyField = requiredFields.find(field => !document.getElementById(field.id).value);

                if (emptyField) {
                    alert('필수 항목을 입력하세요.');
                    document.getElementById(emptyField.id).focus();
                    return;
                }

                // api
                try {
                    // 서버로 POST 요청
                    const response = await fetch('/api/partners/create-user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    const text = await response.text();

                    // 응답 처리
                    const result = text ? JSON.parse(text) : {};

                    if (response.ok) {
                        alert('숙소 회원이 성공적으로 생성되었습니다.');
                        loading.style.display = 'flex'; 
                        window.location.href = '/manage/partner-user-list';
                    } else {
                        alert(result.error || '생성 중 문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('생성 중 오류가 발생했습니다.');
                }
            });
        });
    </script>

</body>
</html>