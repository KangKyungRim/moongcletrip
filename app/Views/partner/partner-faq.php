<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">자주 묻는 질문</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">자주 묻는 질문</h6>
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
                    <div class="card" style="overflow: hidden;">

                        <!-- Card header -->
                        <div class="card-header pb-0 mb-5">
                            <div class="d-lg-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">자주 묻는 질문</h5>
                                </div>

                                <div class="d-flex w-40 align-items-center gap-4">
                                    <div class="ms-auto my-auto d-inline-block">
                                        <a href="/partner/partner-faq-edit" class="btn btn-primary btn-sm mb-0">+&nbsp; 생성 및 수정</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <tbody id="faqAccordion">
                                    <?php if (count($data['partnerFaq']) === 0) : ?>
                                        <tr>
                                            <td colspan="2" class="text-center py-10 px-2">
                                                아직 등록된 자주 묻는 질문이 없습니다.<br>
                                                우리 숙소에 대해 자주 여쭤보시는 고객님 질문에 대한 안내를 등록 해보세요.<br>
                                                이는 예약 전환율 상승에 도움이 되며, 불필요한 CS를 줄일 수 있습니다.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($data['partnerFaq'] as $faq_idx => $partnerFaq) : ?>
                                            <tr>
                                                <td>
                                                    <h6 class="my-2 cursor-pointer font-weight-bold text-dark text-sm d-flex" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $faq_idx; ?>" aria-expanded="false" aria-controls="collapseExample<?= $faq_idx; ?>">
                                                        <span class="d-inline-block me-2" style="color: #696D70;">Q</span>
                                                        <?= $partnerFaq->question; ?>
                                                    </h6>
                                                </td>
                                                <td class="font-weight-bold w-10">
                                                    <div class="button cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $faq_idx; ?>" aria-expanded="false" aria-controls="collapseExample<?= $faq_idx; ?>">
                                                        <i class="fa-solid fa-chevron-up collapse-open"  aria-hidden="true" style="color: #67748e;"></i>
                                                        <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color: #67748e;"></i>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- A -->
                                            <tr class="border-0 rooms">
                                                <td colspan="6" class="p-0 border-0">
                                                    <div class="text-sm collapse" id="collapseExample<?= $faq_idx; ?>" data-bs-parent="#faqAccordion">
                                                        <div class="p-4 text-start" style="background: #F8F8F8; color: #30333E;">
                                                            <?= nl2br(htmlspecialchars($partnerFaq->answer)); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/loading.php"; ?>

    <!--   Core JS Files   -->
    <script src="/assets/manage/js/jquery-3.6.0.min.js"></script>
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

    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

</body>
</html>