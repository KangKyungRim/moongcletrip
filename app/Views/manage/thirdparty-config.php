<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">외부 연동 설정</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">연동 시스템 설정</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">연동 시스템 설정</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row mt-4">
                <div class="mx-auto">
                    <div class="card p-3 w-50 mx-auto">
                        <div class="card-header p-3">
                            <h5 class="mb-0">연동 시스템 선택</h5>
                            <p class="text-sm mb-0">연동 시스템을 선택해 주세요.</p>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" id="custom" name="configType"
                                        <?= ($data['partner']->partner_thirdparty === "custom") ? "checked" : ""; ?>>
                                    <label class="custom-control-label" for="custom">없음</label>
                                </div>
                                <div class="form-check d-inline-block mx-5">
                                    <input class="form-check-input" type="radio" id="tl" name="configType"
                                        <?= ($data['partner']->partner_thirdparty === "tl") ? "checked" : ""; ?>>
                                    <label class="custom-control-label" for="tl">TL</label>
                                </div>
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" id="sanha" name="configType"
                                        <?= ($data['partner']->partner_thirdparty === "sanha") ? "checked" : ""; ?>>
                                    <label class="custom-control-label" for="sanha">산하</label>
                                </div>
                            </div>
                        </div>

                        <!-- button wrap -->
                        <div class="d-flex justify-content-center my-4">
                            <button id="submitForm" class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-6">
                <div class="mx-auto">
                    <div class="card p-3 w-50 mx-auto">
                        <div class="card-header p-3">
                            <h5 class="mb-0">연동 시 금액 표현 방식 선택</h5>
                            <p class="text-sm mb-0">금액 표현 방식을 선택해 주세요.</p>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" id="sellingPrice" name="priceType"
                                        <?= ($data['partner']->partner_calculation_type === "sellingPrice" || $data['partner']->partner_calculation_type === null) ? "checked" : ""; ?>>
                                    <label class="custom-control-label" for="sellingPrice">판매가</label>
                                </div>
                                <div class="form-check d-inline-block mx-5">
                                    <input class="form-check-input" type="radio" id="payoutPrice" name="priceType"
                                        <?= ($data['partner']->partner_calculation_type === "payoutPrice") ? "checked" : ""; ?>>
                                    <label class="custom-control-label" for="payoutPrice">입금가</label>
                                </div>
                            </div>
                        </div>

                        <!-- button wrap -->
                        <div class="d-flex justify-content-center my-4">
                            <button id="priceSaveForm" class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
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

        </div>
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
        document.getElementById('submitForm').addEventListener('click', async function() {
            const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);

            const formData = {
                partnerIdx: selectedPartnerIdx, // index
                thirdparty: document.querySelector('input[name="configType"]:checked')?.id // 선택 시스템
            };

            try {
                // 서버로 POST 요청
                const response = await fetch('/api/partners/change-thirdparty', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                // 응답이 JSON인지 확인
                const contentType = response.headers.get('content-type');
                let result;

                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    result = await response.text(); // JSON이 아니면 텍스트로 읽기
                    console.warn('응답이 JSON 형식이 아닙니다:', result);
                }

                if (response.ok) {
                    alert('연동 시스템이 적용되었습니다.');
                    loading.style.display = 'flex'; 
                    location.reload();
                } else {
                    alert(result?.error || '적용 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('적용 중 오류 발생:', error);
                alert('적용 중 오류가 발생했습니다.');
            }
        });
    </script>

    <script>
        document.getElementById('priceSaveForm').addEventListener('click', async function() {
            const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);

            const formData = {
                partnerIdx: selectedPartnerIdx, // index
                calculationType: document.querySelector('input[name="priceType"]:checked')?.id // 선택 시스템
            };

            try {
                // 서버로 POST 요청
                const response = await fetch('/api/partners/change-calculation-type', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                // 응답이 JSON인지 확인
                const contentType = response.headers.get('content-type');
                let result;

                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    result = await response.text(); // JSON이 아니면 텍스트로 읽기
                    console.warn('응답이 JSON 형식이 아닙니다:', result);
                }

                if (response.ok) {
                    alert('금액 표현 방식이 적용되었습니다.');
                    loading.style.display = 'flex'; 
                    location.reload();
                } else {
                    alert(result?.error || '적용 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('적용 중 오류 발생:', error);
                alert('적용 중 오류가 발생했습니다.');
            }
        });
    </script>

</body>

</html>