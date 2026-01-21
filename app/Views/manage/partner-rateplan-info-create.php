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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">요금 및 인벤토리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">신규 요금제 생성</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">신규 요금제 생성</h6>
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
                            <div class="card card-body p-5">
                                <h6 class="mb-0">신규 요금제 생성</h6>
                                <p class="text-sm mb-0">새로운 요금제 생성을 위해 기본 설정 및 부가 설정을 입력해 보세요</p>

                                <div class="nav-wrapper position-relative end-0 mb-5 mt-5">
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
                                            <?php
                                                $rateplanType = $_POST['rateplanType'] ?? 'standalone'; 
                                            ?>
                                            <div class="form-group row align-items-center">
                                                <label for="rateplanName" class="form-control-label col-sm-2">요금제명 <b class="text-danger">*</b></label>
                                                <div class="col">
                                                    <input type="text" class="form-control" id="rateplanName" name="rateplanName"
                                                        placeholder="<?= ($rateplanType === 'standalone') ? 'room only' : '요금제 이름을 입력해 주세요' ?>"
                                                        value="<?= ($rateplanType === 'standalone') ? 'room only' : '' ?>"
                                                        <?= ($rateplanType === 'standalone') ? 'disabled' : '' ?>>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanType" class="form-control-label col-sm-2">타입 <b class="text-danger">*</b></label>
                                                <div class="col">
                                                    <div class="form-check d-inline-block mb-0">
                                                        <input class="form-check-input" type="radio" id="standalone" name="rateplanType" 
                                                            value="standalone" <?= ($rateplanType === 'standalone') ? 'checked' : '' ?> onclick="toggleRateplanInput()">
                                                        <label class="custom-control-label" for="standalone">기본 요금제</label>
                                                    </div>
                                                    <div class="form-check d-inline-block mx-3 mb-0">
                                                        <input class="form-check-input" type="radio" id="package" name="rateplanType" 
                                                            value="package" <?= ($rateplanType === 'package') ? 'checked' : '' ?> onclick="toggleRateplanInput()">
                                                        <label class="custom-control-label" for="package">추가 요금제</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanDescription" class="form-control-label col-sm-2">설명 <b class="text-danger">*</b></label>
                                                <div class="col">
                                                    <textarea class="form-control" id="rateplanDescription" name="rateplanDescription" rows="7" placeholder="요금제에 대한 설명을 입력해 주세요"></textarea>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">
                                        
                                            <div class="form-group row align-items-center">
                                                <label for="rooms" class="form-control-label col-sm-2">적용할 객실 고르기 <b class="text-danger">*</b></label>
                                                <div class="col">
                                                    <div class="d-flex align-items-center">
                                                        <button class="button cursor-pointer d-flex align-items-center btn btn-outline-secondary mb-0 roomAllBtn" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#collapseExampleRooms" 
                                                            aria-expanded="true" 
                                                            aria-controls="collapseExampleRooms">
                                                            
                                                            <span class="custom-control-label fw-normal d-inline-block me-3">객실 전체 보기</span>    
                                                            <i class="fa-solid fa-chevron-up collapse-open" aria-hidden="true" style="color:#67748e;"></i>
                                                            <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
                                                        </button>
                                                    </div>

                                                    <div class="roomWrap collapse mt-4 show" id="collapseExampleRooms"> 
                                                        <hr class="horizontal gray-light my-3">
                                                        <div>
                                                            <?php if (count($data['rooms']) === 0) : ?>
                                                                <div class="d-flex align-items-center justify-content-center py-8 text-xs fw-bold">
                                                                    <span>생성된 객실이 없습니다.</span>
                                                                </div>
                                                                <hr class="horizontal gray-light my-3">
                                                            <?php else : ?>
                                                            <?php foreach ($data['rooms'] as $room_idx => $room) : ?>
                                                                <div class="d-flex align-items-center justify-content-start">
                                                                    <div class="form-check d-inline-block mb-0">
                                                                        <input class="form-check-input room-active-toggle" type="checkbox" 
                                                                            id="checkRoomActive-<?= $room->room_idx ?>" 
                                                                            data-room-idx="<?= $room->room_idx ?>" 
                                                                            name="room[]">
                                                                    </div>
                                                                    <label for="checkRoomActive-<?= $room->room_idx ?>" class="text-xs">
                                                                        <?= $room->room_name ?>
                                                                    </label>
                                                                </div>
                                                                <hr class="horizontal gray-light my-3">
                                                            <?php endforeach; ?>
                                                            <?php endif; ?>
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
                                                <div class="col">
                                                    <div class="form-check d-inline-block mb-0">
                                                        <input class="form-check-input" type="radio" id="mealIncluding" name="mealCheck" value="true" onchange="toggleMealOptions()">
                                                        <label for="mealIncluding" class="custom-control-label">포함</label>
                                                    </div>
                                                    <div class="form-check d-inline-block mb-0 mx-3">
                                                        <input class="form-check-input" type="radio" id="notMealIncluding" name="mealCheck" value="false" onchange="toggleMealOptions()" checked>
                                                        <label for="notMealIncluding" class="custom-control-label">미포함</label>
                                                    </div>
                                                    

                                                    <div id="mealOptions" style="display:none;">
                                                        <hr class="horizontal gray-light my-3">
                                                        <div class="form-check d-inline-block mb-0">
                                                            <input class="form-check-input" type="checkbox" id="rateplanHasBreakfast" name="rateplanHasBreakfast" value="true">
                                                            <label for="rateplanHasBreakfast" class="custom-control-label">조식</label>
                                                        </div>
                                                        <div class="form-check d-inline-block mb-0 mx-3">
                                                            <input class="form-check-input" type="checkbox" id="rateplanHasLunch" name="rateplanHasLunch" value="true">
                                                            <label for="rateplanHasLunch" class="custom-control-label">중식</label>
                                                        </div>
                                                        <div class="form-check d-inline-block mb-0">
                                                            <input class="form-check-input" type="checkbox" id="rateplanHasDinner" name="rateplanHasDinner" value="true">
                                                            <label for="rateplanHasDinner" class="custom-control-label">석식</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanRefundable" class="form-control-label col-sm-2">환불 가능 여부</label>
                                                <div class="col">
                                                    <div class="form-check d-inline-block mb-0">
                                                        <input class="form-check-input" type="radio" id="refundable" name="rateplanRefundable" value="true" checked>
                                                        <label for="refundable" class="custom-control-label">숙소 취소 규정과 동일</label>
                                                    </div>
                                                    <div class="form-check d-inline-block mb-0 mx-3">
                                                        <input class="form-check-input" type="radio" id="nonRefundable" name="rateplanRefundable" value="false">
                                                        <label for="nonRefundable" class="custom-control-label">환불 불가</label>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="timeCheck" class="form-control-label col-sm-2">판매 기간</label>
                                                <div class="col">

                                                    <div class="form-check d-inline-block mb-0">
                                                        <input class="form-check-input" type="radio" id="allTimes" name="timeCheck" value="true" onchange="toggleTimesOptions()" checked>
                                                        <label for="allTimes" class="custom-control-label">상시</label>
                                                    </div>
                                                    <div class="form-check d-inline-block mb-0 mx-3">
                                                        <input class="form-check-input" type="radio" id="selectTimes" name="timeCheck" value="false" onchange="toggleTimesOptions()">
                                                        <label for="selectTimes" class="custom-control-label">판매 기간 지정</label>
                                                    </div>

                                                    <div id="timeOptions" style="display:none;" >
                                                        <hr class="horizontal gray-light my-3">

                                                        <?php
                                                            // 서버의 타임존을 설정 (예: 'Asia/Seoul'로 설정)
                                                            date_default_timezone_set('Asia/Seoul');
                                                        ?>
                                                        <div class="d-flex align-items-center gap-4">
                                                            <input class="form-control w-35" type="datetime-local" id="rateplanSalesFrom" name="rateplanSalesFrom">
                                                            <span class="d-inline-block text-xs"> 부터 </span>
                                                            <input class="form-control w-35" type="datetime-local" id="rateplanSalesTo" name="rateplanSalesTo">
                                                            <span class="d-inline-block text-xs"> 까지 </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanStayMin" class="form-control-label col-sm-2">최소 숙박일</label>
                                                <div class="col d-flex align-items-center gap-2">
                                                    <input class="form-control w-8" type="number" id="rateplanStayMin" name="rateplanStayMin" value="1" placeholder="1" min="1">
                                                    <span class="d-inline-block text-xs"> 일 </span>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanStayMax" class="form-control-label col-sm-2">최대 숙박일</label>
                                                <div class="col d-flex align-items-center gap-2">
                                                    <input class="form-control w-8" type="number" id="rateplanStayMax" name="rateplanStayMax" value="0" placeholder="0" min="0">
                                                    <span class="d-inline-block text-xs"> 일 </span>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="rateplanCutoffDays" class="form-control-label col-sm-2">체크인 전<br>예약 가능 기간</label>
                                                <div class="col d-flex align-items-center gap-2">
                                                    <span class="d-inline-block text-xs">체크인 </span>
                                                    <input class="form-control w-8" type="number" id="rateplanCutoffDays" name="rateplanCutoffDays" value="0" placeholder="0" min="0">
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
                <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0" onclick="location.href='/manage/partner-rateplan-list'">취소</button>
                <button id="submitForm" class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">생성하기</button>
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

        function standaloneCheck() {
            const data = <?php echo json_encode($data); ?>;

            // 이미 룸온리 요금제가 존재한다면
            if (data.standaloneExist === true) {
                document.getElementById('package').checked = true;
                document.getElementById('standalone').disabled = true;
                return;
            }
        }
        
        standaloneCheck();

        // room only 요금제 명 고정
        function toggleRateplanInput() {
            const rateplanInput = document.getElementById('rateplanName');
            const isStandalone = document.getElementById('standalone').checked;

            if (isStandalone) {
                rateplanInput.value = 'room only';
                rateplanInput.placeholder = "room only";
                rateplanInput.disabled = true; 
            } else {
                rateplanInput.value = '';
                rateplanInput.placeholder = "요금제 이름을 입력해 주세요";
                rateplanInput.disabled = false; 
            }
        }

        toggleRateplanInput();

        // 식사 포함
        function toggleMealOptions() {
            const mealOptions = document.getElementById('mealOptions');
            const mealCheckValue = document.querySelector('input[name="mealCheck"]:checked').value;
            
            // '미포함'을 선택했을 때
            if (mealCheckValue === 'false') {
                document.getElementById('rateplanHasBreakfast').checked = false;
                document.getElementById('rateplanHasLunch').checked = false;
                document.getElementById('rateplanHasDinner').checked = false;
                mealOptions.style.display = 'none';  // 식사 옵션 숨기기
            } else {
                mealOptions.style.display = 'block'; // 식사 옵션 보이기
            }
        }

        // 판매 기간 여부
        function toggleTimesOptions() {
            const timeOptions = document.getElementById('timeOptions');
            const timeCheckValue = document.querySelector('input[name="timeCheck"]:checked').value;

            const now = new Date();

            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const currentDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

            if (timeCheckValue === 'false') {
                timeOptions.style.display = 'block'; 
                document.getElementById("rateplanSalesFrom").value = currentDateTime;
                document.getElementById("rateplanSalesTo").value = currentDateTime;
            } else {
                timeOptions.style.display = 'none'; 
                document.getElementById('rateplanSalesFrom').value = "";
                document.getElementById('rateplanSalesTo').value = "";
            }
        }
    </script>

    <script>
        document.getElementById('submitForm').addEventListener('click', async function() {
            const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);

            const formData = {
                partnerIdx: selectedPartnerIdx, // index
                rateplanName: document.getElementById('rateplanName').value, // 요금제 명
                rateplanType: document.querySelector('input[name="rateplanType"]:checked')?.id, // 타입
                rateplanDescription: document.getElementById('rateplanDescription').value, // 요금제 설명
                rateplanStayMin: Number(document.getElementById('rateplanStayMin').value), // 최소 숙박일
                rateplanStayMax: Number(document.getElementById('rateplanStayMax').value), // 최대 숙박일
                rateplanSalesFrom: document.getElementById('rateplanSalesFrom').value, // 판매 시작일
                rateplanSalesTo: document.getElementById('rateplanSalesTo').value, // 판매 종료일
                rateplanCutoffDays: Number(document.getElementById('rateplanCutoffDays').value), // 컷오프
                rateplanRefundable: document.querySelector('input[name="rateplanRefundable"]:checked')?.value === "true", // 환불 가능 여부
                rateplanHasBreakfast: document.querySelector('input[name="rateplanHasBreakfast"]:checked')? true : false, // 조식
                rateplanHasLunch: document.querySelector('input[name="rateplanHasLunch"]:checked')? true : false, // 중식
                rateplanHasDinner: document.querySelector('input[name="rateplanHasDinner"]:checked')? true : false, // 석식
                rooms: {} // 적용 객실
            };

            document.querySelectorAll('.room-active-toggle').forEach(checkbox => {
                const room_idx = checkbox.dataset.roomIdx;
                formData.rooms[room_idx] = checkbox.checked ? 'enabled' : 'disabled';
            });

            // 유효성 검증
            if (
                !formData.rateplanName || 
                !formData.rateplanType ||
                !formData.rateplanDescription ||
                !formData.rooms
            ) {
                alert('필수 항목을 입력해 주세요.');
                return;
            }

            try {
                // 서버로 POST 요청
                const response = await fetch('/api/partner/rateplan/create', {
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
                    result = await response.text();
                    console.warn('응답이 JSON 형식이 아닙니다:', result);
                }

                if (response.ok) {
                    alert('신규 요금제가 생성되었습니다.');
                    loading.style.display = 'flex'; 
                    window.location.href = '/manage/partner-rateplan-list';
                } else {
                    alert(result?.error || '요금제 생성 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('요금제 생성 중 오류 발생:', error);
                alert('요금제 생성 중 오류가 발생했습니다.');
            }
        });
    </script>
</body>
</html>