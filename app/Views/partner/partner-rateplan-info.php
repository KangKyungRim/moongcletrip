<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$rateplan = $data['rateplan'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">요금 및 인벤토리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">요금제 상세 정보</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">요금제 상세 정보</h6>
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
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <div class="card card-body p-5">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">요금제 상세 정보</h6>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" id="editForm" name="editForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-rateplan-info-edit?rateplanIdx=<?= $rateplan->rateplan_idx ?>'">수정하기</button>
                                    </div>
                                </div>
                                <hr class="horizontal dark my-3">

                                <div class="nav-wrapper position-relative end-0  mb-5">
                                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                        <li class="nav-item cursor-pointer">
                                            <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" data-bs-target="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">
                                                기본 설정
                                            </a>
                                        </li>
                                        <li class="nav-item cursor-pointer">
                                            <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#additionalInfo" role="tab" aria-controls="additionalInfo" aria-selected="false">
                                                부가 설정
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content" id="nav-tabContent">
                                    <!-- 1 -->
                                    <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="nav-basic-tab">
                                        <div>
                                            <div class="form-group row align-items-center">
                                                <label for="rateplanName" class="form-control-label col-sm-2">요금제명</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= $rateplan->rateplan_name; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanType" class="form-control-label col-sm-2">타입</label>
                                                <div class="col text-xs fw-bold">
                                                    <?php if ($rateplan->rateplan_type === 'standalone') : ?>
                                                        룸온리
                                                    <?php else : ?>
                                                        패키지
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanDescription" class="form-control-label col-sm-2">설명</label>
                                                
                                                <div class="col text-xs fw-bold description-text">
                                                    <?= nl2br(htmlspecialchars(trim($rateplan->rateplan_description), ENT_QUOTES, 'UTF-8')); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">
                                        
                                            <div class="form-group row align-items-center">
                                                <label for="rooms" class="form-control-label col-sm-2">적용 객실</label>
                                                <div class="col">
                                                    <div class="roomWrap mt-4">
                                                        <div> 
                                                            <?php foreach ($rateplan->rooms as $room_idx => $room) : ?>
                                                                <?php if ($room->room_idx === null) : ?>
                                                                    <div class="d-flex align-items-center justify-content-center py-8 text-xs fw-bold">
                                                                        <span>적용된 객실이 없습니다.</span>
                                                                    </div>
                                                                    <hr class="horizontal gray-light my-3">
                                                                <?php else : ?>
                                                                <div class="d-flex align-items-center justify-content-start">
                                                                    <div class="form-check d-inline-block mb-0">
                                                                        <input class="form-check-input room-active-toggle" type="checkbox" 
                                                                            id="checkRoomActive-<?= $room->room_idx ?>" 
                                                                            data-room-idx="<?= $room->room_idx ?>" 
                                                                            name="room[]"
                                                                            <?= ($room->room_rateplan_status === "enabled") ? 'checked' : '' ?>
                                                                            disabled>
                                                                    </div>
                                                                    <label for="checkRoomActive-<?= $room->room_idx ?>" class="text-xs fw-normal">
                                                                        <?= $room->room_name ?>
                                                                    </label>
                                                                </div>
                                                                <hr class="horizontal gray-light my-3">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 2 -->
                                    <div class="tab-pane fade" id="additionalInfo" role="tabpanel" aria-labelledby="nav-basic-tab">
                                                        
                                        <div>
                                            <div class="form-group row align-items-center">
                                                <label for="mealCheck" class="form-control-label col-sm-2">식사 포함</label>
                                                <div class="col text-xs fw-bold">
                                                    <?php
                                                        $mealOptions = [];

                                                        if ($rateplan->rateplan_has_breakfast) {
                                                            $mealOptions[] = "조식";
                                                        }
                                                        if ($rateplan->rateplan_has_lunch) {
                                                            $mealOptions[] = "중식";
                                                        }
                                                        if ($rateplan->rateplan_has_dinner) {
                                                            $mealOptions[] = "석식";
                                                        }

                                                        if (!empty($mealOptions)) {
                                                            echo implode(', ', $mealOptions);
                                                        } else {
                                                            echo '미포함';
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanRefundable" class="form-control-label col-sm-2">환불 가능 여부</label>
                                                <div class="col text-xs fw-bold">
                                                    <?php if ($rateplan->rateplan_is_refundable) : ?>
                                                        숙소 취소 규정과 동일
                                                    <?php else : ?>
                                                        환불 불가
                                                    <?php endif ; ?>
                                                </div>
                                            </div>
                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanSales" class="form-control-label col-sm-2">판매 기간</label>
                                                <div class="col d-flex align-items-center gap-2 text-xs fw-bold">
                                                    <?php if ($rateplan->rateplan_sales_from === null) : ?>
                                                        상시
                                                    <?php else : ?>
                                                        <?= $rateplan->rateplan_sales_from ?>
                                                         &nbsp;&nbsp;&nbsp;부터&nbsp;&nbsp;&nbsp;
                                                        <?= $rateplan->rateplan_sales_to ?>
                                                         &nbsp;&nbsp;&nbsp;까지
                                                    <?php endif ; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanStayMin" class="form-control-label col-sm-2">최소 숙박일</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= $rateplan->rateplan_stay_min ?><span class="d-inline-block text-xs"> 일 </span>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanStayMax" class="form-control-label col-sm-2">최대 숙박일</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= $rateplan->rateplan_stay_max ?><span class="d-inline-block text-xs"> 일 </span>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanCutoffDays" class="form-control-label col-sm-2">체크인 전<br>예약 가능 기간</label>
                                                <div class="col text-xs fw-bold">
                                                    <span class="d-inline-block text-xs">체크인 </span>
                                                    <?php if ($rateplan->rateplan_cutoff_days === null) : ?>
                                                        0
                                                    <?php else : ?>
                                                        <?= $rateplan->rateplan_cutoff_days ?>
                                                    <?php endif ; ?>
                                                    <span class="d-inline-block text-xs">일 전까지 예약 가능, 그 이후에는 예약 불가</span>
                                                </div>
                                            </div>
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
                <button type="button" id="cancelForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-rateplan-list'">뒤로</button>
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

</body>
</html>