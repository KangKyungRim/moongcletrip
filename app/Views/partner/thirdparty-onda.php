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

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">외부 연동 설정</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Onda</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Onda</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">

            <div class="row">
                <div class="row pb-3">
                    <div class="col-8">
                    </div>
                    <div class="col-4">
                        <span id="collapseAllMigrationButtons" class="badge bg-gradient-info ms-auto float-end cursor-pointer" data-bs-toggle="collapse" data-bs-target="#migrationButtons">주의! 해당 버튼은 전체 연동 로직입니다.</span>
                    </div>
                </div>
                <div id="migrationButtons" class="collapse">
                    <div class="card p-3">
                        <div class="card-body p-3 pt-1 collapse-section">
                            <button id="getProperties" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">파트너 리스트 연동</button>
                            <button id="getDetailProperties" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">파트너 디테일 연동</button>
                            <button id="getRoomtypes" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">객실 리스트 연동</button>
                            <button id="getDetailRoomtypes" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">객실 디테일 연동</button>
                            <button id="getRateplans" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">레이트플랜 리스트 연동</button>
                            <button id="getDetailRateplans" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">레이트플랜 디테일 연동</button>
                            <button id="getInventories" type="button" class="btn bg-gradient-info w-20 mt-2 mb-0">인벤토리 연동</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mx-auto">
                    <div class="card p-3">
                        <div class="card-header p-3">
                            <h5 class="mb-0">파트너-인벤토리 정보 연동</h5>
                            <p class="text-sm mb-0">파트너의 Onda Property ID를 입력하고 연동 버튼을 클릭하여 정보를 업데이트하세요.</p>
                        </div>
                        <div class="card-body">
                            <form id="partnerAllSyncForm" class="d-flex align-items-center">
                                <div class="input-group">
                                    <input type="text" id="propertyAllIdxInput" class="form-control" placeholder="파트너의 Onda Property ID를 입력하세요" />
                                    <button type="button" id="syncPartnerAllButton" class="btn btn-outline-primary mb-0">연동</button>
                                </div>
                            </form>
                            <div id="partnerAllSyncResult" class="mt-3">
                                <!-- 결과 메시지가 표시될 영역 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mx-auto">
                    <div class="card p-3">
                        <div class="card-header p-3">
                            <h5 class="mb-0">파트너 정보 연동</h5>
                            <p class="text-sm mb-0">파트너의 Onda Property ID를 입력하고 연동 버튼을 클릭하여 정보를 업데이트하세요.</p>
                        </div>
                        <div class="card-body">
                            <form id="partnerSyncForm" class="d-flex align-items-center">
                                <div class="input-group">
                                    <input type="text" id="propertyIdxInput" class="form-control" placeholder="파트너의 Onda Property ID를 입력하세요" />
                                    <button type="button" id="syncPartnerButton" class="btn btn-outline-primary mb-0">연동</button>
                                </div>
                            </form>
                            <div id="partnerSyncResult" class="mt-3">
                                <!-- 결과 메시지가 표시될 영역 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mx-auto">
                    <div class="card p-3">
                        <div class="card-header p-3">
                            <h5 class="mb-0">룸타입 정보 연동</h5>
                            <p class="text-sm mb-0">룸타입의 Onda Roomtype ID를 입력하고 연동 버튼을 클릭하여 정보를 업데이트하세요.</p>
                        </div>
                        <div class="card-body">
                            <form id="roomtypeSyncForm" class="d-flex align-items-center">
                                <div class="input-group">
                                    <input type="text" id="roomtypeIdxInput" class="form-control" placeholder="룸타입의 Onda Roomtype ID를 입력하세요" />
                                    <button type="button" id="syncRoomtypeButton" class="btn btn-outline-primary mb-0">연동</button>
                                </div>
                            </form>
                            <div id="roomtypeSyncResult" class="mt-3">
                                <!-- 결과 메시지가 표시될 영역 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mx-auto">
                    <div class="card p-3">
                        <div class="card-header p-3">
                            <h5 class="mb-0">레이트플랜 정보 연동</h5>
                            <p class="text-sm mb-0">레이트플랜의 Onda Rateplan ID를 입력하고 연동 버튼을 클릭하여 정보를 업데이트하세요.</p>
                        </div>
                        <div class="card-body">
                            <form id="rateplanSyncForm" class="d-flex align-items-center">
                                <div class="input-group">
                                    <input type="text" id="rateplanIdxInput" class="form-control" placeholder="레이트플랜의 Onda Rateplan ID를 입력하세요" />
                                    <button type="button" id="syncRateplanButton" class="btn btn-outline-primary mb-0">연동</button>
                                </div>
                            </form>
                            <div id="rateplanSyncResult" class="mt-3">
                                <!-- 결과 메시지가 표시될 영역 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mx-auto">
                    <div class="card p-3">
                        <div class="card-header p-3">
                            <h5 class="mb-0">인벤토리 연동</h5>
                            <p class="text-sm mb-0">인벤토리의 Onda Rateplan ID를 입력하고 연동 버튼을 클릭하여 정보를 업데이트하세요.</p>
                        </div>
                        <div class="card-body">
                            <form id="roomInventorySyncForm" class="d-flex align-items-center">
                                <div class="input-group">
                                    <div class="input-group mb-3">
                                        <input type="text" id="roomInventoryIdxInput" class="form-control" placeholder="인벤토리의 Onda Rateplan ID를 입력하세요" />
                                        <button type="button" id="syncRoomInventoryButton" class="btn btn-outline-primary mb-0">연동</button>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div class="input-group mb-3 w-50">
                                            <input type="text" id="fromDateInput" class="form-control" placeholder="시작 날짜 (예: 2024-11-11)" />
                                        </div>
                                        <div class="input-group mb-3 w-50">
                                            <input type="text" id="toDateInput" class="form-control" placeholder="종료 날짜 (예: 2024-12-11)" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="roomInventorySyncResult" class="mt-3">
                                <!-- 결과 메시지가 표시될 영역 -->
                            </div>
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
        if (document.getElementById('partnerChoice')) {
            var element = document.getElementById('partnerChoice');
            const partnerChoice = new Choices(element, {
                placeholder: true,
                itemSelectText: '클릭하여 선택'
            });

            element.addEventListener('change', function(event) {
                const selectedValue = event.target.value;

                if (selectedValue) {
                    // 현재 URL 가져오기
                    const url = new URL(window.location);

                    // query string에 partner_idx 추가 또는 업데이트
                    url.searchParams.set('partner_idx', selectedValue);

                    // 새로고침을 위해 href를 새 URL로 설정
                    window.location.href = url.toString();
                }
            });
        }
    </script>

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
        document.addEventListener("DOMContentLoaded", function() {
            const fromDateInput = document.getElementById("fromDateInput");
            const toDateInput = document.getElementById("toDateInput");

            // 오늘 날짜와 90일 후 날짜를 기본값으로 설정
            const today = new Date();
            const futureDate = new Date(today);
            futureDate.setDate(today.getDate() + 90);

            // 날짜를 YYYY-MM-DD 형식으로 변환하는 함수
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, "0");
                const day = String(date.getDate()).padStart(2, "0");
                return `${year}-${month}-${day}`;
            }

            fromDateInput.value = formatDate(today);
            toDateInput.value = formatDate(futureDate);
        });

        document.getElementById('syncPartnerAllButton').addEventListener('click', function() {
            const propertyIdx = document.getElementById('propertyAllIdxInput').value.trim();
            const resultDiv = document.getElementById('partnerAllSyncResult');

            if (!propertyIdx) {
                resultDiv.innerHTML = '<p class="text-danger">파트너 ID를 입력하세요.</p>';
                return;
            }

            resultDiv.innerHTML = '<div class="text-info">연동 중입니다. 잠시만 기다려주세요...</div>';

            // AJAX 요청
            fetch('/api/onda/detail-property-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        propertyIdx: propertyIdx
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        resultDiv.innerHTML = `<p class="text-success">파트너 정보가 성공적으로 업데이트되었습니다.</p>`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-danger">연동 실패: ${data.message || '알 수 없는 오류'}</p>`;
                    }
                })
                .catch((error) => {
                    resultDiv.innerHTML = `<p class="text-danger">연동 중 오류가 발생했습니다. (${error.message})</p>`;
                });
        });

        document.getElementById('syncPartnerButton').addEventListener('click', function() {
            const propertyIdx = document.getElementById('propertyIdxInput').value.trim();
            const resultDiv = document.getElementById('partnerSyncResult');

            if (!propertyIdx) {
                resultDiv.innerHTML = '<p class="text-danger">파트너 ID를 입력하세요.</p>';
                return;
            }

            resultDiv.innerHTML = '<div class="text-info">연동 중입니다. 잠시만 기다려주세요...</div>';

            // AJAX 요청
            fetch('/api/onda/detail-property', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        propertyIdx: propertyIdx
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        resultDiv.innerHTML = `<p class="text-success">파트너 정보가 성공적으로 업데이트되었습니다.</p>`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-danger">연동 실패: ${data.message || '알 수 없는 오류'}</p>`;
                    }
                })
                .catch((error) => {
                    resultDiv.innerHTML = `<p class="text-danger">연동 중 오류가 발생했습니다. (${error.message})</p>`;
                });
        });

        document.getElementById('syncRoomtypeButton').addEventListener('click', function() {
            const roomtypeIdx = document.getElementById('roomtypeIdxInput').value.trim();
            const resultDiv = document.getElementById('roomtypeSyncResult');

            if (!roomtypeIdx) {
                resultDiv.innerHTML = '<p class="text-danger">ID를 입력하세요.</p>';
                return;
            }

            resultDiv.innerHTML = '<div class="text-info">연동 중입니다. 잠시만 기다려주세요...</div>';

            // AJAX 요청
            fetch('/api/onda/detail-roomtype', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        roomtypeIdx: roomtypeIdx
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        resultDiv.innerHTML = `<p class="text-success">룸타입 정보가 성공적으로 업데이트되었습니다.</p>`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-danger">연동 실패: ${data.message || '알 수 없는 오류'}</p>`;
                    }
                })
                .catch((error) => {
                    resultDiv.innerHTML = `<p class="text-danger">연동 중 오류가 발생했습니다. (${error.message})</p>`;
                });
        });

        document.getElementById('syncRateplanButton').addEventListener('click', function() {
            const rateplanIdx = document.getElementById('rateplanIdxInput').value.trim();
            const resultDiv = document.getElementById('rateplanSyncResult');

            if (!rateplanIdx) {
                resultDiv.innerHTML = '<p class="text-danger">ID를 입력하세요.</p>';
                return;
            }

            resultDiv.innerHTML = '<div class="text-info">연동 중입니다. 잠시만 기다려주세요...</div>';

            // AJAX 요청
            fetch('/api/onda/detail-rateplan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        rateplanIdx: rateplanIdx
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        resultDiv.innerHTML = `<p class="text-success">룸타입 정보가 성공적으로 업데이트되었습니다.</p>`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-danger">연동 실패: ${data.message || '알 수 없는 오류'}</p>`;
                    }
                })
                .catch((error) => {
                    resultDiv.innerHTML = `<p class="text-danger">연동 중 오류가 발생했습니다. (${error.message})</p>`;
                });
        });

        document.getElementById('syncRoomInventoryButton').addEventListener('click', function() {
            const rateplanIdx = document.getElementById('roomInventoryIdxInput').value.trim();
            const fromDate = document.getElementById("fromDateInput").value;
            const toDate = document.getElementById("toDateInput").value;
            const resultDiv = document.getElementById('roomInventorySyncResult');

            if (!rateplanIdx || !fromDate || !toDate) {
                resultDiv.innerHTML = '<p class="text-danger">모든 필드를 입력해주세요.</p>';
                return;
            }

            // 날짜 형식 검증
            const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateRegex.test(fromDate) || !dateRegex.test(toDate)) {
                resultDiv.innerHTML = '<p class="text-danger">날짜 형식이 잘못되었습니다. (예: 2024-11-11)</p>';
                return;
            }

            // 날짜 범위 검증
            const from = new Date(fromDate);
            const to = new Date(toDate);
            const maxRange = 90 * 24 * 60 * 60 * 1000; // 90일
            if (to - from > maxRange) {
                resultDiv.innerHTML = '<p class="text-danger">날짜 범위는 최대 90일까지 가능합니다.</p>';
                return;
            }

            resultDiv.innerHTML = '<div class="text-info">연동 중입니다. 잠시만 기다려주세요...</div>';

            // AJAX 요청
            fetch('/api/onda/inventory', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        rateplanIdx: rateplanIdx,
                        fromDate: fromDate,
                        toDate: toDate,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        resultDiv.innerHTML = `<p class="text-success">인벤토리 정보가 성공적으로 업데이트되었습니다.</p>`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-danger">연동 실패: ${data.message || '알 수 없는 오류'}</p>`;
                    }
                })
                .catch((error) => {
                    resultDiv.innerHTML = `<p class="text-danger">연동 중 오류가 발생했습니다. (${error.message})</p>`;
                });
        });
    </script>

    <script>
        document.getElementById('getProperties').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/properties', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('파트너 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('getDetailProperties').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/detail-properties', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('파트너 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('getRoomtypes').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/roomtypes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('객실 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('getDetailRoomtypes').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/detail-roomtypes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('객실 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('getRateplans').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/rateplans', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('객실 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('getDetailRateplans').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/detail-rateplans', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('객실 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('getInventories').addEventListener('click', async function() {
            try {
                // 서버로 POST 요청
                const response = await fetch('/api/onda/inventories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: [],
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('객실 정보가 성공적으로 저장되었습니다.');
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });
    </script>
</body>

</html>