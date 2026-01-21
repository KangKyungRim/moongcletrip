<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$users = $data['users'];

$queryParams = $_GET;
$baseUrl = strtok($_SERVER["REQUEST_URI"], '?');

$currentPage = $data['page'];
$total = $data['total'];
$perPage = $data['perPage'];
$totalPages = ceil($total / $perPage);

function buildPaginationUrl($page, $perPage, $queryParams, $baseUrl)
{
    $queryParams['page'] = $page;
    $queryParams['perPage'] = $perPage;
    return $baseUrl . '?' . http_build_query($queryParams);
}

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">회원 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">회원 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">회원 관리</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">

            <div class="d-sm-flex justify-content-end">
                <div class="d-flex">
                    <button class="btn btn-icon btn-outline-dark ms-2 export" data-type="csv" type="button" onclick="downloadCSV()">
                        <span class="btn-inner--icon"><i class="ni ni-archive-2"></i></span>
                        <span class="btn-inner--text">Export CSV</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <!-- Card header -->
                        <div class="card-header pb-0 mb-5">
                            <div class="d-lg-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">회원 관리</h5>
                                </div>
                                <div style="width: 50%; display: flex; justify-content: flex-end; gap: 0.5rem; align-items: center;">
                                    <div class="choices mb-0 w-20 d-inline-block" data-type="select-one">
                                        <select id="searchField" class="form-control py-1 w-100">
                                            <option value="">선택해 주세요.</option>
                                            <option value="user_id">아이디</option>
                                            <option value="user_nickname">닉네임</option>
                                            <option value="user_email">이메일</option>
                                            <option value="reservation_name">예약자 이름</option>
                                            <option value="reservation_email">예약자 이메일</option>
                                            <option value="reservation_phone">예약자 휴대폰번호</option>
                                            <option value="user_agree_marketing">마케팅 수신 동의</option>
                                        </select>
                                    </div>
                                    <input id="searchText" placeholder="Search..." type="text" style="font-size: 0.875rem; color: #495057; border: 1px solid #e9ecef; border-radius: 0.5rem; padding: 6px 12px;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" style="table-layout: fixed;">
                                <colgroup>
                                    <col style="width: 8%;">
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                    <col style="width: 15%;">
                                    <col style="width: 15%;">
                                    <col style="width: 8%;">
                                    <col style="width: 15%;">
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">가입일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">닉네임</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">아이디</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">이메일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">예약자</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">예약자 이메일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">예약자 휴대폰번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">마케팅 수신 동의 여부</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($users) === 0) : ?>
                                            <tr>
                                                <td colspan="9" class="py-10 text-sm">
                                                    회원 정보가 없습니다.
                                                </td>
                                            </tr>
                                        <?php else : ?>
                                        <?php foreach ($users as $user) : ?>
                                            <tr>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->user_idx ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->user_created_at ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold user-list">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->user_nickname ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->user_id ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->user_email ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->reservation_name ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->reservation_email ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs" style="white-space: normal; word-wrap: keep-all;">
                                                        <?= $user->reservation_phone ?? '-'; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <?php if ($user->user_agree_marketing) : ?>
                                                        <span class="badge bg-success" style="white-space: normal; word-wrap: keep-all;">동의</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-secondary" style="white-space: normal; word-wrap: keep-all;">미동의</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <?php if ($totalPages > 1) : ?>
                                <nav class="mt-5">
                                    <ul class="pagination justify-content-center pb-2">
                                        <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= buildPaginationUrl($currentPage - 1, $perPage, $queryParams, $baseUrl) ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        <?php
                                        $startPage = max(1, $currentPage - 5);
                                        $endPage = min($totalPages, $currentPage + 5);

                                        if ($startPage > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="' . buildPaginationUrl(1, $perPage, $queryParams, $baseUrl) . '">1</a></li>';
                                            if ($startPage > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                <a class="page-link" style="<?= ($i == $currentPage) ? 'color: #fff;' : '' ?>" href="<?= buildPaginationUrl($i, $perPage, $queryParams, $baseUrl) ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($endPage < $totalPages) {
                                            if ($endPage < $totalPages - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="' . buildPaginationUrl($totalPages, $perPage, $queryParams, $baseUrl) . '">' . $totalPages . '</a></li>';
                                        } ?>

                                        <li class="page-item <?= ($currentPage == $totalPages) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1, $perPage, $queryParams, $baseUrl) ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>
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
        window.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const field = params.get("field");

            if (field) {
                const select = document.getElementById("searchField");
                select.value = field;
            }
        });

        document.getElementById("searchText").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();

                const field = document.getElementById("searchField").value;
                const keyword = this.value.trim();

                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                if (keyword) {
                    params.set("field", field);
                    params.set("keyword", keyword);
                } else {
                    params.delete("field");
                    params.delete("keyword");
                }

                if (params.get("page") !== "1") {
                    params.set("page", "1");
                }

                window.location.href = url.pathname + "?" + params.toString();
            }
        });

        // 엑셀 다운로드
        function downloadCSV() {
            const currentUrl = new URL(window.location.href);
            const params = currentUrl.search;
            const downloadUrl = `/api/export/users${params}`;

            window.location.href = downloadUrl;
        }
    </script>
    
</body>
</html>