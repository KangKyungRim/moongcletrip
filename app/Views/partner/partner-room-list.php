<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$rooms = $data['rooms'];

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">객실 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">객실 관리</h6>
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
                                    <h5 class="mb-0">객실 관리</h5>
                                </div>
                                
                                <div class="right">
                                    <div class="d-inline-block me-2">
                                        <button class="btn btn-secondary mb-0 applyButton" type="button" style="display: none;">
                                            <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp; 적용
                                        </button>
                                        <button class="btn btn-outline-active mb-0 orderButton" type="button">
                                            <i class="fa-solid fa-right-left" style="transform: rotate(90deg);"></i>&nbsp;&nbsp;&nbsp; 순서 변경
                                        </button>
                                    </div>

                                    <div class="d-inline-block">
                                        <a href="/partner/partner-room-info-create" class="btn btn-primary btn-sm mb-0" style="padding: 0.75rem 2rem;">+&nbsp; 신규 객실 생성</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="roomTable" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 room_change w-5" style="display: none;"></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">객실명</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">게시 여부</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상태</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">객실 등록일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상세</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">복사</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    <?php if (!$data['rooms']) : ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-10">
                                                객실이 존재하지 않습니다.<br>신규 객실을 생성해 주세요.
                                            </td>
                                        </tr>
                                        <?php else : ?>
                                        <?php foreach ($rooms as $room) : ?>
                                            <tr data-room-idx="<?= $room->room_idx; ?>">
                                                <td class="room_change" style="display: none;">
                                                    <i class="fa-solid fa-bars" style="cursor: pointer;"></i>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2">
                                                        <div class="my-auto">
                                                            <a href="/partner/partner-room-info?roomIdx=<?= $room->room_idx; ?>">
                                                                <div class="d-flex">
                                                                    <h6 class="my-2 text-xs cursor-pointer font-weight-bold name_hover text-dark"><?= $room->room_name; ?></h6>
                                                                    <!-- <i class="ni ni-curved-next text-xs px-2" aria-hidden="true"></i> -->
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <?php if ($room->room_status === "enabled") : ?>
                                                            <span class="badge badge-dot me-4 py-0">
                                                                <i class="bg-success"></i>
                                                                <span class="text-dark text-xs">게시</span>
                                                            </span>
                                                            <?php else : ?>
                                                            <span class="badge badge-dot me-4 py-0">
                                                                <i class="bg-danger"></i>
                                                                <span class="text-dark text-xs">숨김</span>
                                                            </span>
                                                        <?php endif; ?>

                                                        <?php if ($room->review_status === "first_review") : ?>
                                                            <div class="form-check form-switch d-inline-block mb-0">
                                                                <input class="form-check-input room-active-toggle" type="checkbox" id="checkRoomActive-<?= $room->room_idx; ?>" disabled>
                                                            </div>
                                                            <?php else : ?>
                                                            <div class="form-check form-switch d-inline-block mb-0">
                                                                <input class="form-check-input room-active-toggle" type="checkbox" id="checkRoomActive-<?= $room->room_idx; ?>" data-room-idx="<?= $room->room_idx ?>" <?= $room->room_status === 'enabled' ? 'checked' : ''; ?>>
                                                            </div>
                                                        <?php endif; ?>

                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <?php if ($room->review_status === 'reviewed') : ?>
                                                            <span class="badge badge-dot me-4">
                                                                <i class="bg-success"></i>
                                                                <span class="text-dark text-xs">검토 완료</span>
                                                            </span>
                                                        <?php elseif ($room->review_status === 'first_review') : ?>
                                                            <span class="badge badge-dot me-4">
                                                                <i class="bg-danger"></i>
                                                                <span class="text-dark text-xs">검토 중</span>
                                                            </span>
                                                        <?php elseif ($room->review_status === 'nth_review') : ?>
                                                            <span class="badge badge-dot me-4">
                                                                <i class="bg" style="background-color:#fbcf33;"></i>
                                                                <span class="text-dark text-xs">검토 완료 & 신규 버전 검토 중</span>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-dark text-xs">
                                                        <?= $room->room_created_at; ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="cursor-pointer">
                                                        <a href="/partner/partner-room-info?roomIdx=<?= $room->room_idx ?>">
                                                            <i class="ni ni-zoom-split-in" style="color:#67748e;"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="cursor-pointer">
                                                        <a href="/partner/partner-room-info-edit?roomIdx=<?= $room->room_idx; ?>">
                                                            <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="cursor-pointer">
                                                        <a class="roomCopyBtn" data-room-idx="<?= $room->room_idx; ?>">
                                                            <i class="fa-solid fa-copy" style="color:#67748e;"></i>
                                                        </a>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
    <!-- Kakao Map -->
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=a09a9506a8284c662059e618d6ec7b42&libraries=services"></script>
    <script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>

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
        document.addEventListener("DOMContentLoaded", function () {
            // 순서 변경 버튼 노출
            const orderButton = document.querySelector(".orderButton");
            const applyButton = document.querySelector(".applyButton");
            const roomChangeButtons = document.querySelectorAll(".room_change");

            let isOrdering = false; 
            let sortableInstance = null;

            var orderedRoomIds = [];

            // 순서 변경 버튼 클릭 시
            orderButton.addEventListener("click", function () {
                isOrdering = !isOrdering; 

                if (isOrdering) {
                    orderButton.style.border = "2px solid #8392AB";
                    applyButton.style.display = "inline-block";
                        
                    roomChangeButtons.forEach(button => {
                        button.style.display = "";                                                
                    });

                    // 순서 변경 활성화
                    $(function() {
                        $("#sortable").sortable({
                            items:$('#sortable tr'),
                            axis: "y",
                            opacity: 0.5,
                            update: function(event, ui) {
                                orderedRoomIds = $("#sortable tr").map(function() {
                                    return $(this).data("room-idx");
                                }).get();  
                            }
                        });
                        $("#sortable").disableSelection();
                    });
                } else {
                    window.location.reload();

                    // 순서 변경 비활성화
                    if (sortableInstance) {
                        sortableInstance.destroy();
                        sortableInstance = null;
                    }
                }
            });
            
            // 적용 버튼 클릭 시
            applyButton.addEventListener("click", async function () {

                const formData = {
                    partnerIdx: Number(<?php echo json_encode($selectedPartnerIdx); ?>), // index
                    rooms: [ ...orderedRoomIds ] // room idx
                }

                // 순서 변경 없을 때
                if (orderedRoomIds.length === 0) {
                    alert('객실 순서가 변경되었습니다.'); 
                    window.location.reload(); 
                    return;
                }

                // 순서 변경 사항 적용
                try {
                    const response = await fetch('/api/partner/room/change-order', {
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

                    if (result.success === true) {
                        alert('객실 순서가 변경되었습니다.'); 
                        loading.style.display = 'flex'; 
                        window.location.reload(); 
                    } else {
                        alert('순서 변경에 실패했습니다.');
                    }
                } catch (error) {
                    console.error('순서 변경 중 오류 발생:', error);
                    alert('순서 변경 중 오류가 발생했습니다.');
                }
            });
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 게시 여부 토글
            document.querySelectorAll('.room-active-toggle').forEach((checkbox) => {
                checkbox.addEventListener('change', async function() {
                    const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
                    const roomIdx = this.getAttribute('data-room-idx');

                    const roomStatus = this.checked ? 'enabled' : 'disabled';

                    const formData = {
                        partnerIdx: selectedPartnerIdx,
                        roomIdx: Number(roomIdx),
                        roomStatus: roomStatus
                    };

                    // POST 요청 보내기
                    try {
                        const response = await fetch('/api/partner/room/status', {
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

                        // 응답이 성공적이라면 페이지 새로 고침
                        if (result.success === true) {
                            loading.style.display = 'flex'; 
                            window.location.reload(); 
                        } else {
                            alert('게시 여부 변경에 실패했습니다.');
                        }
                    } catch (error) {
                        console.error('게시 여부 변경 중 오류 발생:', error);
                        alert('게시 여부 변경 중 오류가 발생했습니다.');
                    }
                });
            });

            // 객실 복사
            document.querySelectorAll('.roomCopyBtn').forEach((checkbox) => {
                checkbox.addEventListener('click', async function() {
                    const confirmCopy = confirm('해당 객실을 복사하시겠습니까?');

                    if (confirmCopy) {
                        const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
                        const roomIdx = this.getAttribute('data-room-idx');

                        const formData = {
                            partnerIdx: selectedPartnerIdx,
                            roomIdx: Number(roomIdx)
                        };

                        // POST 요청 보내기
                        try {
                            const response = await fetch('/api/partner/room/copy', {
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

                            if (result.success === true) {
                                loading.style.display = 'flex'; 
                                window.location.reload(); 
                            } else {
                                alert('객실 복사에 실패했습니다.');
                            }
                        } catch (error) {
                            console.error('객실 복사 중 오류 발생:', error);
                            alert('객실 복사 중 오류가 발생했습니다.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
