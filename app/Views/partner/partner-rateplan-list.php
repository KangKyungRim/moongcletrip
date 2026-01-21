<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$rateplans = $data['rateplans'];

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">요금 및 인벤토리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">요금제 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">요금제 관리</h6>
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
                                    <h5 class="mb-0">요금제 관리</h5>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="/partner/partner-rateplan-info-create" class="btn btn-primary btn-sm mb-0">+&nbsp; 신규 요금제 생성</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">요금제명</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">타입</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상태</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상세</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">적용 객실</th>
                                    </tr>
                                </thead>
                                <tbody id="rateplanAccordion">
                                    <?php if (!$data['rateplans']) : ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            요금제가 존재하지 않습니다.<br>신규 요금제을 생성해 주세요.
                                        </td>
                                    </tr>
                                    <?php else : ?>
                                    <?php foreach ($rateplans as $rateplan) : ?>
                                        <tr>
                                            <td class="font-weight-bold partner-list">
                                                <a href="/partner/partner-rateplan-info?rateplanIdx=<?= $rateplan->rateplan_idx ?>">
                                                    <div class="d-flex px-2">
                                                        <h6 class="my-2 text-xs cursor-pointer font-weight-bold name_hover text-dark"><?= $rateplan->rateplan_name; ?></h6>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="font-weight-bold partner-list">
                                                <span class="my-2 text-xs cursor-pointer">
                                                    <?php if ($rateplan->rateplan_type === "standalone") : ?>
                                                        <span class="text-dark text-xs status-text">기본 요금제</span>
                                                        <?php else : ?>
                                                        <span class="text-dark text-xs status-text">추가 요금제</span>
                                                        </span>
                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                            <td class="font-weight-bold partner-list">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <?php if ($rateplan->rateplan_status === "enabled") : ?>
                                                        <span class="badge badge-dot py-0">
                                                            <i class="bg-success"></i>
                                                            <span class="text-dark text-xs status-text">활성화</span>
                                                        </span>
                                                        <?php else : ?>
                                                        <span class="badge badge-dot py-0">
                                                            <i class="bg-danger"></i>
                                                            <span class="text-dark text-xs status-text">비활성화</span>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <div class="form-check form-switch d-inline-block mb-0 ms-3">
                                                        <input class="form-check-input rateplan-active-toggle" type="checkbox" id="checkRateplanActive-<?= $rateplan->rateplan_idx; ?>" data-rateplan-idx="<?= $rateplan->rateplan_idx ?>" <?= $rateplan->rateplan_status === 'enabled' ? 'checked' : ''; ?>>
                                                    </div>    
                                                </div>
                                            </td>
                                            <td class="font-weight-bold w-10">
                                                <div class="cursor-pointer">
                                                    <a href="/partner/partner-rateplan-info?rateplanIdx=<?= $rateplan->rateplan_idx ?>">
                                                        <i class="ni ni-zoom-split-in" style="color:#67748e;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="font-weight-bold w-10">
                                                <div class="cursor-pointer">
                                                    <a href="/partner/partner-rateplan-info-edit?rateplanIdx=<?= $rateplan->rateplan_idx ?>">
                                                        <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="font-weight-bold w-10">
                                                <div class="button cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $rateplan->rateplan_idx ?>" aria-expanded="false" aria-controls="collapseExample<?= $rateplan_idx ?>">
                                                    <i class="fa-solid fa-chevron-up collapse-open"  aria-hidden="true" style="color:#67748e;"></i>
                                                    <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- room -->
                                        <tr class="border-0 rooms">
                                            <td colspan="6" class="p-0 border-0">
                                                <div class="text-sm collapse p-4" id="collapseExample<?= $rateplan->rateplan_idx ?>" data-bs-parent="#rateplanAccordion" style="border-top: 1px solid rgb(233, 236, 239);">
                                                    <div class="p-5 rounded-3 text-start" style="background:#F5F5F5;">
                                                        <div class="mb-3 pb-3">
                                                            <h4 class="text-sm" style="color:#3A416F;">해당 요금제 객실 적용</h4>
                                                        </div>
                                                        <?php foreach ($rateplan->rooms as $room_idx => $room) : ?>
                                                            <?php if ($room->room_idx === null) : ?>
                                                                <div class="d-flex align-items-center justify-content-center py-5">
                                                                    <span>적용된 객실이 없습니다.</span>
                                                                </div>
                                                            <?php else : ?>
                                                            <hr class="horizontal gray-light my-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <label class="text-xs fw-normal ms-0">
                                                                    <?= $room->room_name ?>
                                                                </label>
                                                                <div class="form-check form-switch d-inline-block mb-0">
                                                                    <input class="form-check-input room-active-toggle" type="checkbox" id="checkRoomActive-<?= $rateplan->rateplan_idx; ?>-<?= $room_idx ?>" data-rateplan-idx="<?= $rateplan->rateplan_idx ?>" data-room-idx="<?= $room->room_idx ?>" <?= $room->room_rateplan_status === 'enabled' ? 'checked' : ''; ?> style="height:20px;">
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
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

    <script>
        // 상태 변경
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.rateplan-active-toggle').forEach((checkbox) => {
                checkbox.addEventListener('change', async function() {
                    const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
                    const rateplanIdx = Number(this.getAttribute('data-rateplan-idx'));

                    const status = this.checked ? 'enabled' : 'disabled';

                    const formData = {
                        partnerIdx: selectedPartnerIdx,
                        rateplanIdx: rateplanIdx,
                        rateplanStatus: status
                    };

                    // POST 요청 보내기
                    try {
                        const response = await fetch('/api/partner/rateplan/status-toggle-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(formData),
                        });

                        // 응답이 성공적이라면 페이지 새로 고침
                        if (response.ok) {
                            loading.style.display = 'flex'; 
                            window.location.reload(); 
                        } else {
                            alert('상태 변경에 실패했습니다.');
                        }
                    } catch (error) {
                        console.error('상태 변경 중 오류 발생:', error);
                        alert('상태 변경 중 오류가 발생했습니다.');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 객실 적용
            document.querySelectorAll('.room-active-toggle').forEach((checkbox) => {
                checkbox.addEventListener('change', async function() {
                    const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
                    const rateplanIdx = Number(this.getAttribute('data-rateplan-idx'));
                    const roomIdx = this.getAttribute('data-room-idx');

                    const roomRateplanStatus = this.checked ? 'enabled' : 'disabled';

                    const formData = {
                        partnerIdx: selectedPartnerIdx,
                        roomIdx: Number(roomIdx),
                        rateplanIdx: rateplanIdx,
                        roomRateplanStatus: roomRateplanStatus
                    };

                    // POST 요청 보내기
                    try {
                        const response = await fetch('/api/partner/rateplan/status-toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(formData),
                        });

                        const contentType = response.headers.get('content-type');
                        let result;

                        if (contentType && contentType.includes('application/json')) {
                            result = await response.json();
                        } else {
                            result = await response.text();
                            console.warn('응답이 JSON 형식이 아닙니다:', result);
                        }
                    } catch (error) {
                        console.error('객실 적용 중 오류 발생:', error);
                        alert('객실 적용 중 오류가 발생했습니다.');
                    }
                });
            });
        });
    </script>
</body>
</html>