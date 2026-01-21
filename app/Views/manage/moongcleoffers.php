<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$queryParams = $_GET;
$baseUrl = strtok($_SERVER["REQUEST_URI"], '?');

$currentPage = $data['pagination']['currentPage'];
$totalPages = $data['pagination']['totalPages'];
$perPage = $data['pagination']['perPage'];

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">뭉클딜</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">뭉클딜 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">뭉클딜 관리</h6>
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
                                    <h5 class="mb-0">뭉클딜 관리</h5>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="/manage/moongcleoffers/create" class="btn btn-primary btn-sm mb-0">+&nbsp; 신규 뭉클딜 생성</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <colgroup>
                                    <col style="width: 5%;">
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                    <col style="width: 20%;">
                                    <col style="width: 15%;">
                                    <col style="width: 15%;">
                                    <col style="width: 12%;">
                                    <col style="width: 12%;">
                                    <col style="width: 15%;">
                                    <col style="width: 15%;">
                                    <col style="width: 6%;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer" onclick="updateSorting('createdAt')">생성일
                                            <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="createdAt"></span>
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">뭉클딜 제안명</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상태</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">기반 요금제</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer" onclick="updateSorting('saleStartDate')">진행 상황
                                            <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="saleStartDate"></span>
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer" onclick="updateSorting('stayStartDate')">투숙 기간
                                            <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="stayStartDate"></span>
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수요 공략 태그</th> 
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">포함 혜택</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                    </tr>
                                </thead>
                                <tbody id="moongcleoffersAccordion">
                                    <?php if (count($data['moongcleoffers']) !== 0) : ?>
                                        <?php foreach ($data['moongcleoffers'] as $moongcleoffer) : ?>
                                            <tr>
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <div 
                                                        class="button cursor-pointer fw-bold" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#moreTags<?= $moongcleoffer->stay_moongcleoffer_idx ?>" 
                                                        aria-expanded="false" 
                                                        aria-controls="moreTags<?= $moongcleoffer->stay_moongcleoffer_idx ?>">
                                                        <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
                                                        <i class="fa-solid fa-chevron-up collapse-open" aria-hidden="true" style="color:#67748e;"></i>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                        <?= $moongcleoffer->updated_at; ?>
                                                    </span>                                                                                                            
                                                </td>    
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                        <?= $moongcleoffer->created_at; ?>
                                                    </span>                                                                                                            
                                                </td>    
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                        <?php echo $moongcleoffer->stay_moongcleoffer_title ? $moongcleoffer->stay_moongcleoffer_title : "이름 없음"; ?>
                                                    </span>                                                                                                            
                                                </td>                                                
                                                <td class="font-weight-bold">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <?php if ($moongcleoffer->stay_moongcleoffer_status === "enabled") : ?>
                                                            <span class="badge badge-dot py-0">
                                                                <i class="bg-success"></i>
                                                                <span class="text-dark text-xs">활성화</span>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="badge badge-dot py-0">
                                                                <i class="bg-danger"></i>
                                                                <span class="text-dark text-xs">비활성화</span>
                                                            </span>
                                                        <?php endif; ?>

                                                        <div class="form-check form-switch d-inline-block mb-0 ms-3">
                                                            <input class="form-check-input moongcleoffer-active-toggle" type="checkbox" id="checkMooncleofferActive-<?= $moongcleoffer->stay_moongcleoffer_idx; ?>" data-moongcleoffer-idx="<?= $moongcleoffer->stay_moongcleoffer_idx; ?>" <?= $moongcleoffer->stay_moongcleoffer_status === 'enabled' ? 'checked' : ''; ?>>
                                                        </div>
                                                    </div> 
                                                </td>
                                                <td class="font-weight-bold my-2 text-xs">
                                                    <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                        <?= $moongcleoffer->rateplan_name; ?>
                                                    </span>     
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?php 
                                                            $today = strtotime(date('Y-m-d'));
                                                            $start_date = $moongcleoffer->sale_start_date ? strtotime($moongcleoffer->sale_start_date) : null;
                                                            $end_date = $moongcleoffer->sale_end_date ? strtotime($moongcleoffer->sale_end_date) : null;
                                                        ?>

                                                        <?php if ($start_date === null || $end_date === null) : ?>                                                            
                                                            <span class="badge badge-info text-xs" style="background-color: rgb(231 251 255);">상시</span>
                                                        <?php elseif ($today < $start_date) : ?>
                                                            <span class="badge badge-primary text-xs" style="background-color: rgb(255 232 250); background-color: rgb(255 232 250); word-wrap: break-word; word-break: break-word; white-space: normal; line-height: 1.4;"><?= date('Y-m-d', $start_date); ?><br>발송 예정</span>
                                                        <?php elseif ($today >= $start_date && $today <= $end_date) : ?> 
                                                            <span class="badge badge-success text-xs" style="background-color: rgb(237 255 215);">발송 중</span>
                                                        <?php elseif ($today > $end_date) : ?>
                                                            <?php if ($moongcleoffer->stay_moongcleoffer_status === "enabled") : ?>
                                                                <span class="badge badge-secondary text-xs" style="background-color: rgb(245 245 245);">종료</span>
                                                            <?php elseif ($moongcleoffer->stay_moongcleoffer_status === "disabled") : ?>
                                                                <span class="badge badge-secondary text-xs" style="background-color: rgb(245 245 245);">중지됨</span>
                                                            <?php endif; ?>        
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                        <?php if ($moongcleoffer->stay_start_date === null || $moongcleoffer->stay_end_date === null) : ?>
                                                            상시
                                                        <?php else : ?>
                                                            <?= date('Y-m-d', strtotime($moongcleoffer->stay_start_date)); ?> ~ <?= date('Y-m-d', strtotime($moongcleoffer->stay_end_date)); ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div 
                                                        class="my-2 text-xs" 
                                                        style="white-space: normal; word-wrap: keep-all;">
                                                        <?php 
                                                            $tag_count = count($moongcleoffer->tag_list);
                                                            echo '<span>';
                                                            if ($tag_count > 1) {
                                                                echo '<span class="ellipsis" style="display: block;">';
                                                                
                                                                $first_tag = $moongcleoffer->tag_list[0]['tag_name'];
                                                                if ($first_tag === "커플") {
                                                                    $first_tag = "연인과";
                                                                }
                                                                echo '#' . $first_tag . '... ' . '</span> ';

                                                            } else {
                                                                foreach ($moongcleoffer->tag_list as $tag) {
                                                                    if ($tag['tag_name'] === "커플") {
                                                                        $tag['tag_name'] = "연인과";
                                                                    }
                                                                    echo '<span style="display:block;">#' . $tag['tag_name'] . '</span>';
                                                                }
                                                            }
                                                            echo '</span>';
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div 
                                                        class="my-2 text-xs" 
                                                        style="white-space: normal; word-wrap: break-word;">
                                                        <?php 
                                                            $benefits_count = count($moongcleoffer->benefits);
                                                            echo '<span>';
                                                             
                                                            if ($benefits_count > 1) {
                                                                echo '<span class="ellipsis" style="display: block;">' . $moongcleoffer->benefits[0] . '... ' . '</span> ';

                                                            } else {
                                                                $first = true;
                                                                foreach ($moongcleoffer->benefits as $benefit) {
                                                                    if (!$first) {
                                                                        echo ',<br>';
                                                                    }
                                                                    echo $benefit;
                                                                    $first = false;
                                                                }
                                                            }
                                                             
                                                            echo '</span>';
                                                        ?>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <div class="cursor-pointer">
                                                        <a href="/manage/moongcleoffers/edit?stayMoongcleofferIdx=<?= $moongcleoffer->stay_moongcleoffer_idx ?>">
                                                            <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- 상세 view -->
                                            <tr class="border-0 tags">
                                                <td colspan="11" class="p-0 border-0">
                                                    <div class="text-sm p-4 collapse" id="moreTags<?= $moongcleoffer->stay_moongcleoffer_idx ?>" data-bs-parent="#moongcleoffersAccordion" style="border-top: 1px solid rgb(233, 236, 239);">
                                                        <div class="p-5 rounded-3 text-start" style="background: #F5F5F5;">
                                                            <div class="mb-3 pb-3">
                                                                <h4 class="text-sm" style="color:#3A416F;">수요 공략 태그</h4>
                                                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                                                    <?php foreach ($moongcleoffer->tag_list as $tag) : ?>
                                                                        <span class="d-inline-block text-xs" style="color: #344767;">#<?= $tag->tag_name; ?> </span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>  

                                                            <hr class="horizontal dark my-3">
                                                           
                                                            <div>
                                                                <h4 class="text-sm" style="color:#3A416F;">포함 혜택</h4>
                                                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                                                    <?php 
                                                                        $benefits = array_map(fn($benefit) => "<span class='d-inline-block text-xs' style='color: #344767;'>$benefit</span>", $moongcleoffer->benefits);
                                                                        echo implode(' | ', $benefits);
                                                                    ?>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>  
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php else : ?>
                                        <tr>
                                            <td colspan="11" class="text-center py-10">등록된 뭉클딜이 없습니다.<br>신규 뭉클딜을 생성해 주세요.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- pagination -->
                            <?php if ($totalPages > 1) : ?>
                                <nav>
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
        // sorting
        window.onload = function() {
            let urlParams = new URLSearchParams(window.location.search);
            let sortColumn = urlParams.get("sortColumn");
            let sortOrder = urlParams.get("sortOrder");

            document.querySelectorAll(".sortIcon").forEach(icon => {
                let column = icon.getAttribute("data-column");

                icon.innerHTML = `
                    <span class="d-inline-block" style="position: absolute; left: 0; top: 0; font-size: 0.8rem;">
                        <i class="fa-solid fa-sort-up sort-up"></i>
                    </span>
                    <span class="d-inline-block" style="position: absolute; left: 0; top: 2px; font-size: 0.8rem;">
                        <i class="fa-solid fa-sort-down sort-down"></i>
                    </span>
                `;

                if (column === sortColumn) {
                    if (sortOrder === "ASC") {
                        icon.querySelector(".sort-up").style.color = "#656565"; 
                        icon.querySelector(".sort-down").style.color = "#ccc";
                    } else if (sortOrder === "DESC") {
                        icon.querySelector(".sort-up").style.color = "#ccc";
                        icon.querySelector(".sort-down").style.color = "#656565";
                    }
                } else {
                    icon.querySelector(".sort-up").style.color = "#ccc";
                    icon.querySelector(".sort-down").style.color = "#ccc"; 
                }
            });
        };

        function updateSorting(sortColumn) {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            let currentSortOrder = params.get('sortOrder') || 'ASC';
            let newSortOrder = (currentSortOrder === 'ASC') ? 'DESC' : 'ASC';

            params.set('sortColumn', sortColumn);
            params.set('sortOrder', newSortOrder);

            if (params.get("page") !== "1") {
                params.set("page", "1");
            }

            window.location.href = url.pathname + "?" + params.toString();
        }
    </script>

    <script>
        // 상태 변경
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.moongcleoffer-active-toggle').forEach((checkbox) => {
                checkbox.addEventListener('change', async function() {
                    const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
                    const moongcleofferIdx = this.getAttribute('data-moongcleoffer-idx');

                    const status = this.checked ? 'enabled' : 'disabled';

                    const formData = {
                        partnerIdx: selectedPartnerIdx,
                        moongcleoffer: {
                            stayMoongcleofferIdx: moongcleofferIdx,
                            status: status
                        }
                    };

                    // POST 요청 보내기
                    try {
                        const response = await fetch('/api/partner/edit-moongcleoffer-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(formData),
                        });

                        // 응답이 성공적이라면 페이지 새로 고침
                        if (response.ok) {
                            if (status === 'enabled') {
                                const confirmMove = alert("뭉클딜을 활성화하면 설정된 할인율과 특전이 즉시 적용됩니다. 변경 사항에 이상이 없는지 꼭 확인해 주세요.\n※ 저장하기 버튼을 꼭 눌러야 적용됩니다!");
                                window.location.href = `/manage/moongcleoffers/edit?stayMoongcleofferIdx=${moongcleofferIdx}`;
                            } else {
                                loading.style.display = 'flex';
                                window.location.reload();
                            }
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
</body>

</html>