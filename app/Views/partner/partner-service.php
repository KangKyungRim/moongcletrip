<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$partnerServices = $data['partnerServices'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">아이와 함께 이용 가능 서비스 & 꿀팁</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">아이와 함께 이용 가능 서비스 & 꿀팁</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header pb-0 mb-5">
                            <div class="d-lg-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">아이와 함께 이용 가능 서비스 & 꿀팁</h5>
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <span class="text-sm" style="color: #67748e; margin-right: 1rem; display: inline-block;">( 최대 4개까지만 생성이 가능합니다. )</span>
                                    <a 
                                        href="<?= count($partnerServices) >= 4 ? 'javascript:void(0);' : '/partner/partner-service-create' ?>" 
                                        class="btn btn-primary btn-sm mb-0" 
                                        style="<?= count($partnerServices) >= 4 ? 'opacity: 0.5; pointer-events: none; cursor: not-allowed;' : '' ?>">
                                        +&nbsp; 신규 생성
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" style="table-layout: fixed;">
                                <colgroup>
                                    <col style="width: 40%;">
                                    <col style="width: 40%;">
                                    <col style="width: 20%;">
                                    <col style="width: 20%;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">타이틀</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">설명</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">삭제</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($partnerServices) !== 0) : ?>
                                        <?php foreach ($partnerServices as $partnerService) : ?>
                                            <tr>
                                                <td class="font-weight-bold my-2 text-xs"><?= $partnerService->service_name; ?></td>
                                                <td class="font-weight-bold my-2 text-xs"><?= $partnerService->service_description; ?></td>
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <div class="cursor-pointer">
                                                        <a href="/partner/partner-service-edit?serviceDetailIdx=<?= $partnerService->service_detail_idx; ?>">
                                                            <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <div class="cursor-pointer deleteBtn" data-service-idx="<?= $partnerService->service_detail_idx; ?>">                                            
                                                        <i class="fa-solid fa-trash" style="color:#67748e;"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4">
                                                <div class="py-10 px-3">
                                                    아직 등록된 리스트가 없습니다.<br>
                                                    아이와 함께 이용 가능 서비스 또는 꿀팁을 등록하여, 패밀리 고객의 예약 전환을 높여보세요!<br>
                                                    <p class="d-flex flex-column align-items-center mt-5 gap-2">
                                                        <span>노출 예시 <i class="fa-solid fa-play ms-1" style="transform: rotate(-30deg);"></i></span>
                                                        <a href="/assets/manage/images/service_ex.png" target="_blank">
                                                            <img src="/assets/manage/images/service_ex.png" alt="노출 예시" style="width: 100%; max-width: 400px; cursor: zoom-in; border: 1px solid #eeeeeef5;">
                                                        </a>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
    </script>

    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

    <script>
        // 삭제하기
        document.querySelectorAll('.deleteBtn').forEach(function (button) {
            button.addEventListener('click', async function () {
                const serviceIdx = this.getAttribute('data-service-idx');

                if (!serviceIdx) {
                    alert('삭제할 항목의 정보가 없습니다.');
                    return;
                }

                const isConfirmed = confirm('정말 삭제하시겠습니까?');
                if (!isConfirmed) {
                    return;
                }

                const formData = {
                    partnerIdx: <?= $selectedPartnerIdx; ?>,
                    serviceDetailIdx: serviceIdx
                };

                try {
                    const response = await fetch('/api/partner/delete-service-detail', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('삭제가 완료되었습니다.');
                    window.location.reload();
                } else {
                    alert(result.error || '삭제 중 문제가 발생했습니다.');
                }
                } catch (error) {
                    console.error('Error:', error);
                    alert('삭제 중 오류가 발생했습니다.');
                }
            });
        });
    </script>
</body>

</html>