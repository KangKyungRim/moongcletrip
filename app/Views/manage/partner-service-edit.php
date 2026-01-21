<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$partnerService = $data['partnerService'];

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">아이와 함께 이용 가능 서비스 & 꿀팁 수정</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">아이와 함께 이용 가능 서비스 & 꿀팁 수정</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <input type="hidden" id="facilitiesBasicImageMeta" name="facilitiesBasicImageMeta" value="">

                            <div class="card card-body p-5">
                                <p class="text-sm mb-0">정보를 입력해 주세요.</p>
                                <hr class="horizontal dark my-3">

                                <div>
                                    <div class="form-group row align-items-center">
                                        <label for="serviceName" class="form-control-label col-sm-2">타이틀 <b class="text-danger">*</b></label>
                                        <div class="col">
                                            <input type="text" class="form-control" id="serviceName" name="serviceName" placeholder="타이틀을 입력해 주세요." value="<?= $partnerService->service_name; ?>">
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">

                                    <div class="form-group row align-items-center">
                                        <label for="serviceDescription" class="form-control-label col-sm-2">설명 <b class="text-danger">*</b></label>
                                        <div class="col">
                                            <input type="text" class="form-control" id="serviceDescription" name="serviceDescription" placeholder="설명을 입력해 주세요." value="<?= $partnerService->service_description; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- button wrap -->
            <div class="d-flex justify-content-center mt-4">
                <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0" onclick="location.href='/manage/partner-service'">취소</button>
                <button id="submitForm" class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">저장하기</button>
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
    <script src="/assets/manage/js/plugins/sortable.min.js"></script>
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
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.7"></script>

    <script>
        // 저장하기
        document.getElementById('submitForm').addEventListener('click', async function() {

            const formData = {
                partnerIdx: <?= $selectedPartnerIdx; ?>,
                serviceName: document.getElementById('serviceName').value,
                serviceDescription: document.getElementById('serviceDescription').value,
                serviceDetailIdx: <?= $partnerService->service_detail_idx; ?>
            }

            // 유효성 검증
            if (!formData.serviceName || !formData.serviceDescription) {
                // 필수 항목 입력 여부 확인 후, 해당 필드에 포커스
                if (!formData.serviceName) {
                    document.getElementById('serviceName').focus();
                } else if (!formData.serviceDescription) {
                    document.getElementById('serviceDescription').focus();
                }

                alert('필수 항목을 모두 입력해 주세요.');
                return;
            }

            try {
                const response = await fetch('/api/partner/edit-service-detail', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('서비스 수정이 완료되었습니다.');
                    window.location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        })
    </script>
</body>
</html>