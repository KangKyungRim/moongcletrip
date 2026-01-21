<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$partners = $data['partners'];
$totalCount = $data['totalCount'];
$totalPages = $data['totalPages'];
$page = !empty($_GET['page']) ? (int)$_GET['page'] : 0;

$range = 3;
$start = max(1, $page - $range);
$end = min($totalPages, $page + $range);

$queryParams = $_GET;

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">숙소 선택</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">숙소 선택</h6>
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
                    <div class="card">

                        <!-- Card header -->
                        <div class="card-header pb-0 mb-5">
                            <div class="d-lg-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">숙소 선택</h5>
                                </div>

                                <div class="d-flex w-40 align-items-center gap-4">
                                    <div class="ms-auto my-auto d-inline-block">
                                        <a href="/manage/partner-basic-info-create" class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modal-form">+&nbsp; 신규 숙소 생성</a>
                                    </div>
                                    <!-- create form modal -->
                                    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fs-6" id="exampleModalLabel">신규 숙소 생성</h5>
                                                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="card card-plain">
                                                        <div class="card-body">
                                                            <form role="form text-left">
                                                                <div class="form-group row align-items-center">
                                                                    <label for="partnerName" class="form-control-label col-sm-2 mb-0">숙소명</label>
                                                                    <div class="col">
                                                                        <input class="form-control" type="text" id="partnerName" placeholder="숙소 이름을 입력해 주세요" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row align-items-center">
                                                                    <label class="form-control-label col-sm-2 mb-0" for="partnerCategory">카테고리</label>
                                                                    <div class="row col align-items-center">
                                                                        <div class="col">
                                                                            <div class="form-check d-inline-block mb-0">
                                                                                <input class="form-check-input" type="radio" name="partnerCategory" id="stay" checked>
                                                                                <label class="custom-control-label mb-0" for="stay">스테이</label>
                                                                            </div>
                                                                            <div class="form-check d-inline-block mx-3 mb-0">
                                                                                <input class="form-check-input" type="radio" name="partnerCategory" id="activity">
                                                                                <label class="custom-control-label mb-0" for="activity">액티비티&체험</label>
                                                                            </div>
                                                                            <div class="form-check d-inline-block mb-0">
                                                                                <input class="form-check-input" type="radio" name="partnerCategory" id="tour">
                                                                                <label class="custom-control-label mb-0" for="tour">투어</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                                                    <button type="button" id="createButton" class="btn bg-gradient-primary">생성하기</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <div class="input-group">
                                                <input id="searchInput" class="form-control" placeholder="숙소명 검색" type="text" value="<?= $_GET['search'] ?? ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>                              
                            </div>

                            <!-- 필터링 버튼 -->
                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/partner-select'">전체보기</button>
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/partner-select?thirdparty=sanha'">직계약</button>
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/partner-select?thirdparty=custom'">수기</button>
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/partner-select?thirdparty=onda'">온다</button>
                            </div>  
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">등록일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-dark">숙소명 (선택)</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">주소</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">전화 번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">담당자 전화 번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">담당자 이메일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">공개 여부</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">유형</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수수료</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">검색 지수</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상세</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($partners[0])) : ?>
                                        <?php foreach ($partners as $partner) : ?>
                                            <tr>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                      <?= $partner->partner_idx; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partner->partner_created_at; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold partner-list">
                                                    <span class="my-2 text-xs cursor-pointer font-weight-bold name_hover text-dark" onclick="selectPartner(<?= $partner->partner_idx; ?>)"><?= $partner->partner_name; ?></span>
                                                    <span class="px-2"><i class="ni ni-curved-next cursor-pointer" onclick="gotoProductPage(<?= $partner->partner_idx ?>, '_blank')"></i></span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs"><?= $partner->partner_address1 . ' ' . $partner->partner_address2 . ' ' . $partner->partner_address3 . ' ' . $partner->partner_zip; ?></span>
                                                </td>
                                                <td class="font-weight-bold">
                                                  <span class="my-2 text-xs">
                                                    <?= $partner->partner_phonenumber; ?>
                                                  </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                      <?= $partner->partner_manager_phonenumber; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                      <?= $partner->partner_manager_email; ?>
                                                    </span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <div>
                                                        <?php if ($partner->partner_status == 'enabled') : ?>
                                                          <span class="badge badge-dot">
                                                            <i class="bg-success"></i>
                                                            <span class="text-dark text-xs">노출</span>
                                                          </span>
                                                        <?php else : ?>
                                                          <span class="badge badge-dot">
                                                            <i class="bg-danger"></i>
                                                            <span class="text-dark text-xs">미노출</span>
                                                          </span>
                                                        <?php endif; ?>
                                                        <span class="px-1" role="button" data-bs-toggle="modal" data-bs-target="#partner_detail_setting_<?= $partner->partner_idx; ?>"><i class="fa-solid fa-pen"></i></span>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                      <?= $partner->partner_thirdparty; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                  <span class="my-2 text-xs">
                                                    <?= $partner->partner_charge; ?>%
                                                  </span>
                                                  <span class="px-1 text-xs" role="button"  data-bs-toggle="modal" data-bs-target="#partner_detail_setting_<?= $partner->partner_idx; ?>"><i class="fa-solid fa-pen"></i></span>
                                                </td>
                                                <!-- 검색 노출 지수 -->
                                                <td class="font-weight-bold">
                                                  <span class="my-2 text-xs">
                                                    <?= $partner->search_index; ?>
                                                  </span>
                                                  <span class="px-1 text-xs" role="button"  data-bs-toggle="modal" data-bs-target="#partner_search_setting_<?= $partner->partner_idx; ?>"><i class="fa-solid fa-pen"></i></span>
                                                </td>
                                                <td class="font-weight-bold">
                                                  <div class="cursor-pointer">
                                                    <a onclick="selectPartner(<?= $partner->partner_idx; ?>)">
                                                      <i class="ni ni-zoom-split-in"></i>
                                                    </a>
                                                  </div>
                                                </td>
                                            </tr>

                                            <!-- 공개 여부, 수수료 수정 모달 -->
                                            <div class="modal fade" id="partner_detail_setting_<?= $partner->partner_idx; ?>" tabindex="-1" role="dialog" aria-labelledby="partner_detail_setting_<?= $partner->partner_idx; ?>">
                                                <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fs-6" id="exampleModalLabel">공개 여부 및 수수료 설정</h5>
                                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                                <span>×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-0">
                                                            <div class="card card-plain">
                                                                <div class="card-body">
                                                                    <form role="form text-left">
                                                                        <div class="form-group row align-items-center">
                                                                            <label for="partner_status" class="form-control-label col-sm-2 mb-0">공개 여부</label>
                                                                            <div class="col">
                                                                                <div class="form-check form-switch d-inline-block mb-0">
                                                                                    <input class="form-check-input status-active-toggle" type="checkbox" id="checkStatusActive-<?= $partner->partner_idx; ?>" data-partner-idx="<?= $partner->partner_idx ?>" <?= $partner->partner_status === 'enabled' ? 'checked' : ''; ?>>
                                                                                </div>   
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row align-items-center mb-0">
                                                                            <label class="form-control-label col-sm-2 mb-0" for="partner_charge">수수료</label>
                                                                            <div class="row col align-items-center">
                                                                                <div class="col d-flex align-items-center gap-1">
                                                                                    <input class="form-control w-30 partner_charge" type="number" id="partner_charge"
                                                                                        value="<?= $partner->partner_charge; ?>" required>
                                                                                    <span class="d-inline-block">%</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                                                            <button type="button" id="saveButton" class="btn bg-gradient-primary saveButton" data-bs-target="partner_detail_setting_<?= $partner->partner_idx; ?>" data-partner-idx="<?= $partner->partner_idx; ?>">저장하기</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 검색 노출 지수 수정 모달 -->
                                            <div class="modal fade" id="partner_search_setting_<?= $partner->partner_idx; ?>" tabindex="-1" role="dialog" aria-labelledby="partner_search_setting_<?= $partner->partner_idx; ?>">
                                                <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fs-6" id="exampleModalLabel">검색 노출 지수 설정</h5>
                                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                                <span>×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-0">
                                                            <div class="card card-plain">
                                                                <div class="card-body">
                                                                    <form role="form text-left">
                                                                        <div class="form-group row align-items-center mb-0">
                                                                            <div class="row col align-items-center">
                                                                                <div class="col d-flex align-items-center gap-1">
                                                                                    <input class="form-control w-30 search_index" type="number" id="search_index"
                                                                                        value="<?= $partner->search_index; ?>" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                                                            <button type="button" id="searchEditBtn" class="btn bg-gradient-primary searchEditBtn" data-bs-target="partner_search_setting_<?= $partner->partner_idx; ?>" data-partner-idx="<?= $partner->partner_idx; ?>">저장하기</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <ul class="pagination pagination-info" style="display: flex; justify-content: center; padding: 1rem 2rem .5rem 0;">
                            <?php if ($page > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])); ?>" aria-label="Previous">
                                        <span aria-hidden="true"><i class="ni ni-bold-left" aria-hidden="true"></i></span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($start > 1) : ?>
                                <li class="page-item active">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => 1])); ?>">1</a>
                                </li>
                                <?php if ($start > 2) : ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++) : ?>
                                <li class="page-item <?= $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $i])); ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $totalPages) : ?>
                                <?php if ($end < $totalPages - 1) : ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $totalPages])); ?>"><?= $totalPages; ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if ($page < $totalPages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])); ?>" aria-label="Next">
                                        <span aria-hidden="true"><i class="ni ni-bold-right" aria-hidden="true"></i></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

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

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/loading.php"; ?>

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
    
    <script>
        // 공개 여부, 수수료 수정 이벤트
        document.querySelectorAll('.saveButton').forEach(button => {
            button.addEventListener('click', async function() {
                const partnerIdx = this.getAttribute('data-partner-idx');

                const modalId = this.getAttribute('data-bs-target').replace('partner_detail_setting_', '');
                const modal = document.querySelector(`#partner_detail_setting_${modalId}`);

                const checkbox = modal.querySelector('.status-active-toggle');

                const partnerStatus = checkbox.checked ? 'enabled' : 'disabled';

                const inputField = modal.querySelector('.partner_charge');

                const partnerCharge = inputField.value;

                const formData = {
                    partnerIdx: Number(partnerIdx),
                    partnerStatus: partnerStatus,
                    partnerCharge: Number(partnerCharge),
                };

                try {
                    // 서버 요청
                    const response = await fetch('/api/partners/toggle-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    // 응답이 JSON 형식인지 확인
                    let result = {};
                    if (response.headers.get('Content-Type')?.includes('application/json')) {
                        result = await response.json();
                    }

                    if (response) {
                        alert('수정이 완료되었습니다.');
                        loading.style.display = 'flex'; 
                        location.reload();
                    } else {
                        alert(result.error || '수정 중 문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('수정 중 오류가 발생했습니다.');
                }               
            
            });
        });

        // 검색 지수 수정 이벤트
        document.querySelectorAll('.searchEditBtn').forEach(button => {
            button.addEventListener('click', async function() {
                const partnerIdx = this.getAttribute('data-partner-idx');

                const modalId = this.getAttribute('data-bs-target').replace('partner_search_setting_', '');
                const modal = document.querySelector(`#partner_search_setting_${modalId}`);

                const inputField = modal.querySelector('.search_index');

                const rawValue = inputField.value.trim();

                // 숫자만 허용 (정수, 0 이상)
                const numberRegex = /^\d+$/; 

                if (!numberRegex.test(rawValue)) {
                    alert('검색 지수는 0 이상의 숫자만 입력할 수 있습니다.'); 
                    return;
                }

                const partnerSearchIndex = Number(rawValue);

                // 음수 방지 (정규식상 음수는 걸러지지만, 혹시 몰라 추가)
                if (partnerSearchIndex < 0) {
                    alert('검색 지수는 0 이상의 숫자만 입력할 수 있습니다.');
                    return;
                }

                const formData = {
                    partnerIdx: Number(partnerIdx),
                    partnerSearchIndex: Number(partnerSearchIndex),
                };

                try {
                    // 서버 요청
                    const response = await fetch('/api/partners/edit-search-index', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    // 응답이 JSON 형식인지 확인
                    let result = {};
                    if (response.headers.get('Content-Type')?.includes('application/json')) {
                        result = await response.json();
                    }

                    if (response) {
                        alert('수정이 완료되었습니다.');
                        loading.style.display = 'flex'; 
                        location.reload();
                    } else {
                        alert(result.error || '수정 중 문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('수정 중 오류가 발생했습니다.');
                }               
            
            });
        });
    </script>
    
    <script>
        // 숙소 생성 이벤트
        document.getElementById('createButton').addEventListener('click', async function() {
            // 데이터
            const formData = {
                partnerName: document.getElementById('partnerName').value,
                partnerCategory: document.querySelector('input[name="partnerCategory"]:checked')?.id,
            };

            // 유효성 검증
            if (!formData.partnerName || !formData.partnerCategory) {
                alert('모든 항목을 입력해 주세요.');
                return;
            }

            // api
            try {
                // 서버 요청
                const response = await fetch('/api/partners/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                // 응답이 JSON 형식인지 확인
                let result = {};
                if (response.headers.get('Content-Type')?.includes('application/json')) {
                    result = await response.json();
                }

                if (response.ok) {
                    alert('숙소가 성공적으로 생성되었습니다.');
                    document.cookie = `partner=${result.data.partnerIdx}; path=/; max-age=86400;`;
                    window.location.href = '/manage/partner-select';
                } else {
                    alert(result.error || '생성 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('생성 중 오류가 발생했습니다.');
            }
        });
    </script>

    <script>
        // 숙소 선택
        function selectPartner(partnerIdx) {
            if (partnerIdx) {
                document.cookie = `partner=${partnerIdx}; path=/; max-age=86400;`;
                location.href= '/manage/partner-basic-info'
            } else {
                alert("숙소를 선택해 주세요.");
            }
        }

        // 해당 숙소 뷰페이지 이동
        function gotoProductPage(partnerIdx) {
            if (partnerIdx) {
                const url = '/stay/detail/' + partnerIdx;
                window.open(url, '_blank');
            }
        }

        document.getElementById('searchInput').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                const searchQuery = this.value.trim();
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchQuery);
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            }
        });
    </script>

    <script>
        $(document).ready(function () {     
            $('.modal').on('hide.bs.modal', function () {
                $('div, button, input, select, textarea').each(function () {
                    $(this).blur();
                });
            });
        });
    </script>
</body>
</html>