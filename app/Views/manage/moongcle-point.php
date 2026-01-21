<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$moongclePoint = $data['moongclePoint'];

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">뭉클포인트</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">뭉클포인트</h6>
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

                    <?php if (empty($moongclePoint)) : ?>
                        <div class="row">
                            <div class="col-12 mb-xl-0 mb-4">
                                <div class="card height-600 card-plain border">
                                    <div class="card-body d-flex flex-column justify-content-center text-center">
                                        <a href="/manage/partner-moongcle-point/update">
                                            <i class="fa fa-plus text-secondary mb-3" aria-hidden="true"></i>
                                            <h5 class=" text-secondary"> 뭉클포인트 작성하기 </h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-light m-0" onclick="location.href='/manage/partner-moongcle-point/update'">수정하기</button>
                        </div>

                        <div class="card mt-4" style="width: 500px;">
                            <h6 class="pt-3 text-center">뭉클 한 줄 소개</h6>
                            <hr class="horizontal dark my-3">

                            <div>
                                <div class="row justify-content-start">
                                    <div class="my-auto px-5">
                                        <p class="text-muted text-sm" style="white-space: pre-line;">
                                            <?= $moongclePoint->moongcle_point_introduction; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php $images = json_decode($moongclePoint->images); ?>

                        <?php if (!empty($moongclePoint->moongcle_point_1_title)) : ?>
                            <div class="card mt-4" style="width: 500px;">
                                <div>
                                    <div class="row">
                                        <div>
                                            <img src="<?= $images[0]->image_path; ?>" alt="kal" class="border-radius-lg shadow w-100">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="p-3 text-center">
                                            <p class="text-muted text-sm font-weight-bold">
                                                <?= $moongclePoint->moongcle_point_1_title; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($moongclePoint->moongcle_point_2_title)) : ?>
                            <div class="card mt-4" style="width: 500px;">
                                <div>
                                    <div class="row">
                                        <div>
                                            <img src="<?= $images[1]->image_path; ?>" alt="kal" class="border-radius-lg shadow w-100">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="p-3 text-center">
                                            <p class="text-muted text-sm font-weight-bold">
                                                <?= $moongclePoint->moongcle_point_2_title; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($moongclePoint->moongcle_point_3_title)) : ?>
                            <div class="card mt-4" style="width: 500px;">
                                <div>
                                    <div class="row">
                                        <div>
                                            <img src="<?= $images[2]->image_path; ?>" alt="kal" class="border-radius-lg shadow w-100">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="p-3 text-center">
                                            <p class="text-muted text-sm font-weight-bold">
                                                <?= $moongclePoint->moongcle_point_3_title; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($moongclePoint->moongcle_point_4_title)) : ?>
                            <div class="card mt-4" style="width: 500px;">
                                <div>
                                    <div class="row">
                                        <div>
                                            <img src="<?= $images[3]->image_path; ?>" alt="kal" class="border-radius-lg shadow w-100">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="p-3 text-center">
                                            <p class="text-muted text-sm font-weight-bold">
                                                <?= $moongclePoint->moongcle_point_4_title; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($moongclePoint->moongcle_point_5_title)) : ?>
                            <div class="card mt-4" style="width: 500px;">
                                <div>
                                    <div class="row">
                                        <div>
                                            <img src="<?= $images[4]->image_path; ?>" alt="kal" class="border-radius-lg shadow w-100">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="p-3 text-center">
                                            <p class="text-muted text-sm font-weight-bold">
                                                <?= $moongclePoint->moongcle_point_5_title; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
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
</body>

</html>