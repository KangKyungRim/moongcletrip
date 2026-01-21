<!DOCTYPE html>
<html lang="ko">

<?php



?>           

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">앱 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">배너 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">배너 관리</h6>
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
                    <div class="card" style="overflow: hidden;">

                        <!-- Card header -->
                        <div class="card-header pb-0 mb-5">
                            <div class="d-lg-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">배너 관리</h5>
                                </div>

                                <div class="right">
                                    <div class="d-inline-block me-2">
                                        <button class="btn btn-secondary mb-0 curationApplyButton" type="button" style="display: none;">
                                            <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp; 적용
                                        </button>
                                        <button class="btn btn-outline-active mb-0 curationOrderButton" type="button">
                                            <i class="fa-solid fa-right-left" style="transform: rotate(90deg);"></i>&nbsp;&nbsp;&nbsp; 순서 변경
                                        </button> 
                                    </div>

                                    <div class="d-inline-block">
                                        <a href="javascript:void(0)" id="btnCurationCreate" class="btn btn-primary btn-sm mb-0" style="padding: 0.75rem 2rem;">+&nbsp; 신규 생성</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center" id="curationTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 curation_change w-5" style="display: none;"></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">배너</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">큐레이션 설명</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">숙소</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">노출 여부</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">노출 기간</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">생성일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">적용 숙소</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCurationList">
                                </tbody>
                            </table>
                            <!-- S: pagination -->
                            <nav>
                                <div id="divCurationPaging" class="page-navi">
                                </div>
                            </nav>
                            <!-- E: pagination -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- footer -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/bottom.php"; ?>
            <!-- footer -->

        </div>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/loading.php"; ?>

    <!--   Core JS Files   -->
    <script type="text/javascript" src="/assets/manage/js/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="/assets/manage/js/plugins/underscore-min.1.13.7.js"></script>
    <script type="text/javascript" src="/assets/manage/js/plugins/moment.2.30.1.js"></script>
    <script type="text/javascript" src="/assets/manage/js/commonWeb.js?v=<?= $_ENV['VERSION']; ?>"></script>
    <script type="text/javascript" src="/assets/manage/js/common.js?v=<?= $_ENV['VERSION']; ?>"></script>

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