<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$curations = $data['curations'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">큐레이션 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">큐레이션 관리</h6>
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
                                    <h5 class="mb-0">큐레이션 관리</h5>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">큐레이션명</th>
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

    <!-- 큐레이션 목록 -->
    <script id="tmplCurationNodata" type="text/template">
    <tr>
        <td colspan="6" class="text-center py-10">
            등록된 큐레이션이 없습니다.<br>
            신규 큐레이션을 생성해 주세요.
        </td>
    </tr>
    </script>
    <script id="tmplCurationList" type="text/template">
        <@ _.each(list, function(d, i) { @>
        <!-- E: curation -->
        <tr data-cid="<@=d.curationIdx@>" data-page-num="<@=pageNum @>" class="curationTr">
            <td class="curation_change" style="display: none;">
                <i class="fa-solid fa-bars" style="cursor: pointer;"></i>
            </td>
            <td class="font-weight-bold partner-list">
                <div class="d-flex px-2">
                    <h6 class="my-2 text-xs cursor-pointer font-weight-bold name_hover text-dark">
                        <span class="my-2 text-xs cursor-pointer font-weight-bold name_hover text-dark txtCurationTitle" data-cid="<@=d.curationIdx @>" onclick="javascript:void(0)">
                            <@=d.curationTitle @>
                        </span>
                        <span class="px-2">
                            <i class="ni ni-curved-next cursor-pointer" onclick="gotoCurationtPage(<@=d.curationIdx @>, '_blank')"></i>
                        </span>
                    </h6>
                </div>
            </td>
            <td>
                <span class="text-dark text-xs">
                    <@=d.curationDescription @>
                </span>
            </td>
            <td>
                <span class="text-dark text-xs">
                    <@=d.curationItems.length @>
                </span>
            </td>
            <td class="font-weight-bold">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="badge badge-dot py-0">
                        <@ if( _.isEqual(d.isActive,1)) { @>
                            <i class="bg-success"></i>
                            <span class="text-dark text-xs">활성화</span>
                        <@ } else { @>
                            <i class="bg-danger"></i>
                            <span class="text-dark text-xs">비활성화</span>
                        <@ } @>
                    </span>
                    <div class="form-check form-switch d-inline-block mb-0 ms-3">
                        <input class="form-check-input curation-active-toggle" type="checkbox" id="checkCurationActive<@=d.curationIdx @>" 
                            data-cid="<@=d.curationIdx @>"
                            <@=_.isEqual(d.isActive,1) ? 'checked' : '' @>
                        >
                    </div>
                </div> 
            </td>
            <td>
                <@ if( _.isEmpty(d.curationVisibleFrom) && _.isEmpty(d.curationVisibleTo)) { @>
                    <!-- 상시 -->
                    <span class="badge badge-success text-xs" style="background-color: rgb(237 255 215);">노출 중</span>
                <@ } else if( !_.isEmpty(d.curationVisibleFrom) && Dates.isAfterNow(d.curationVisibleFrom)) { @>
                    <!-- 예정 -->
                    <span class="badge badge-primary text-xs" style="background-color: rgb(255 232 250); background-color: rgb(255 232 250); word-wrap: break-word; word-break: break-word; white-space: normal; ">예정</span>
                <@ } else if( !_.isEmpty(d.curationVisibleTo) && Dates.isBeforeNow(d.curationVisibleTo)) { @>
                    <!-- 종료 -->
                    <span class="badge badge-secondary text-xs" style="background-color: rgb(245 245 245);">종료</span>
                <@ } else {@>
                    <!-- 노출 중 -->
                    <span class="badge badge-success text-xs" style="background-color: rgb(237 255 215);">노출 중</span>
                <@ } @>
            </td>
            <td>
                <span class="text-dark text-xs">
                    <@=Fmt.dateTime(d.createdAt) @>
                </span>
            </td>
            <td>
                <span class="text-dark text-xs">
                    <@=Fmt.dateTime(d.updatedAt) @>
                </span>
            </td>
            <td class="font-weight-bold w-10">
                <div class="button cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseExample<@=d.curationIdx@>" aria-expanded="false" aria-controls="collapseExample<@=d.curationIdx@>">
                    <i class="fa-solid fa-chevron-up collapse-open"  aria-hidden="true" style="color:#67748e;"></i>
                    <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
                </div>
            </td>
        </tr>
        <!-- E: curation -->
        <!-- S: curation item : partner -->
        <tr class="border-0 rooms">
            <td colspan="8" class="p-0 border-0">
                <div class="text-sm collapse p-4" id="collapseExample<@=d.curationIdx@>" data-bs-parent="#rateplanAccordion" style="border-top: 1px solid rgb(233, 236, 239);">
                    <div class="p-5 rounded-3 text-start" style="background:#F5F5F5;" data-cid="<@= d.curationIdx @>"> 
                        <div class="mb-3 pb-1 d-flex justify-content-between align-items-center">
                            <h4 class="text-sm mb-0" style="color:#3A416F;">적용 숙소</h4>

                            <div class="d-inline-block me-2">
                                <button class="btn btn-secondary mb-0 partnerApplyButton p-2 px-3" type="button" style="display: none;">
                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp; 적용
                                </button>
                                <button type="button" class="btn btn-outline-active mb-0 partnerOrderButton p-2 px-3" style="background: #fff;">
                                    <i class="fa-solid fa-right-left" style="transform: rotate(90deg);"></i>&nbsp;&nbsp;&nbsp; 순서 변경
                                </button>
                            </div> 
                        </div>
                        <table class="table align-items-center mb-0" id="partnerTable" style="table-layout: fixed;">
                             <colgroup>
                                <col class="partner_change" style="width: 5%; display: none;">
                                <col style="width: 15%;">
                                <col style="width: 20%;">
                                <col style="width: 30%;">
                                <col style="width: 8%;">
                            </colgroup> 
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 partner_change w-5" style="display: none;"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">숙소명</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">한 줄 설명</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">선택된 태그</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">노출 여부</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPartnerList">
                                <@ var items = d.curationItems; @>
                                <@ _.each(items, function(ditem, i) { @>
                                <tr data-partner-idx="<@=ditem.curationItemIdx@>" class="partnerTr">
                                    <td class="partner_change" style="display: none;">
                                        <i class="fa-solid fa-bars" style="cursor: pointer;"></i>
                                    </td>
                                    <td class="font-weight-bold partner-list">
                                        <span class="text-dark text-xs">
                                            <@=ditem.targetName @>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark text-xs">
                                            <@=ditem.targetDescription ?? "-" @>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark text-xs d-inline-block" style="word-wrap: break-word; white-space: normal;"> 
                                            <@=(ditem.targetTags && ditem.targetTags.length > 0) ? _.chain(ditem.targetTags).map(function(t){ return t && t.label; }).compact().uniq().map(function(s){ return '#' + s; }).value().join(', ') : '-'@>
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="badge badge-dot py-0">
                                                <@ if( _.isEqual(ditem.isActive,1)) { @>
                                                <i class="bg-success"></i>
                                                <span class="text-dark text-xs">활성화</span>
                                                <@ } else { @>
                                                <i class="bg-danger"></i>
                                                <span class="text-dark text-xs">비활성화</span>
                                                <@ } @>
                                            </span>
                                            <div class="form-check form-switch d-inline-block mb-0 ms-3">
                                                <input class="form-check-input curationItem-active-toggle" type="checkbox" id="checkCurationItemActive<@=ditem.curationItemIdx @>" 
                                                    data-cid="<@=d.curationIdx @>"
                                                    data-citem-id="<@=ditem.curationItemIdx @>"
                                                    <@=_.isEqual(ditem.isActive,1) ? 'checked' : '' @>
                                                >
                                            </div>
                                        </div> 
                                    </td>
                                </tr>
                                <@ }); @>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        <!-- E: curation item : partner -->
        <@ }); @>
    </script>

    <script>
        function gotoCurationtPage () {
            var curationIdx = arguments[0] || null;
            var target = arguments[1] || '_self';

            if (!curationIdx) {
                alert('잘못된 접근입니다.');
                return;
            }

            var url = '/curation/' + encodeURIComponent(curationIdx);
            window.open(url, target);
        }
    </script>

    <script>
        $(function () {
            var pageRows = 100;
            var isOrdering = false;
            var orderedCurationIds = [];
            var $curationTable = $("#curationTable");
            var $tbody = $("#tbodyCurationList");
            var $paging = $("#divCurationPaging");
            var $orderBtn = $(".curationOrderButton");
            var $applyBtn = $(".curationApplyButton");

            function enableSortable() {
                // 이미 활성화돼 있으면 갱신만
                if (typeof $.fn.sortable !== "function") {
                    console.warn("jQuery UI가 로드되지 않았습니다.");
                    return;
                }
                if ($tbody.data("ui-sortable")) {
                    $tbody.sortable("refresh");
                } else {
                    $tbody
                    .sortable({
                        items: "> tr:visible",       
                        axis: "y",
                        opacity: 0.5,
                        update: function () {
                            orderedCurationIds = $("#tbodyCurationList > .curationTr").map(function (i) {
                                var currPageNum = $(this).data("page-num");
                                return {
                                    curationIdx: $(this).data("cid"),
                                    curationOrder: (currPageNum-1) * pageRows + (i + 1)
                                };
                            }).get();
                        }
                    })
                    .disableSelection();
                }
            
            }

            function disableSortable() {
                if ($tbody.data("ui-sortable")) {
                    $tbody.sortable("destroy");
                }
            }

            function showOrderControls() {
                $curationTable.find(".curation_change").each(function () {
                    this.style.display = "";
                });
                $applyBtn.css("display", "inline-block");
                $orderBtn.css("border", "2px solid #8392AB");
            }

            function hideOrderControls() {
                $curationTable.find(".curation_change").each(function () {
                    this.style.display = "none";
                });
                $applyBtn.css("display", "none");
                $orderBtn.css("border", "");
            }

            function afterRender() {
                if (isOrdering) {
                    showOrderControls();
                    enableSortable();
                } else {
                    hideOrderControls();
                    disableSortable();
                }
            }

            function fnSearch(page) {
                var data = { page: page, size: pageRows };

                Api.call({
                    url: "/api/manage/getCurations",
                    data: data,
                    success: function (res) {
                    var pageInfo = res.page;
                    var list = res.body || [];

                    if (list.length > 0) {
                        Tmpl.insert("#tbodyCurationList", "#tmplCurationList", { list: list, pageNum : page});
                    } else {
                        $tbody.empty();
                        Tmpl.insert("#tbodyCurationList", "#tmplCurationNodata");
                    }

                    afterRender();

                    // 페이징
                    $paging.empty();
                    if (Number(pageInfo.totalRows) > 0) {
                        loadPage(pageInfo);
                    }
                    }
                });
            }

            function loadPage(pageInfo) {
                var page = new Page(
                    {
                        pageRows: pageInfo.pageRows,
                        currPage: pageInfo.currPage,
                        totalRows: pageInfo.totalRows
                    },
                    "pagingBoxCallback",
                    function (page) {
                        fnSearch(page);
                    }
                );
                $paging.append(page.html(10)).show();
            }

            fnSearch(1);

            // 큐레이션 비/활성화
            $tbody.on("change", ".curation-active-toggle", function () {
                var curationIdx = $(this).data("cid");
                Api.call({
                    url: "/api/manage/putCurationActive/" + curationIdx,
                    success: function () {
                        window.location.reload();
                    },
                    failure: function() {}
                });
            });

            $tbody.on("change", ".curationItem-active-toggle", function () {
                var $el = $(this);
                var curationIdx = $el.data("cid");
                var curationItemIdx = $el.data("citem-id");
                Api.call({
                    url: "/api/manage/putCurationItemActive/" + curationIdx + "/" + curationItemIdx,
                    success: function () {
                        window.location.reload();
                    }
                });
            });

            $("#btnCurationCreate").off("click").on("click", function () {
                Comm.goTo("/manage/curation-create");
            });

            $tbody.on("click", ".txtCurationTitle", function () {
            var curationIdx = $(this).data("cid");
                Comm.goTo("/manage/curation-edit?curationIdx=" + curationIdx);
            });

            $orderBtn.on("click", function () {
                isOrdering = !isOrdering;
                if (isOrdering) {
                    showOrderControls();
                    enableSortable();
                } else {
                    hideOrderControls();
                    disableSortable();
                }
            });

            $applyBtn.on("click", async function () {
                if (!orderedCurationIds.length) {
                    alert("변경된 큐레이션이 없습니다.");
                    window.location.reload();
                    return;
                }

                try {
                    const response = await fetch("/api/manage/putCurationOrder", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(orderedCurationIds)
                    });

                    const contentType = response.headers.get("content-type");
                    const result = contentType && contentType.includes("application/json")
                    ? await response.json()
                    : await response.text();

                    if (result) {
                        alert("큐레이션 순서가 변경되었습니다.");

                        // 초기화
                        isOrdering = false;
                        hideOrderControls();
                        disableSortable();
                        window.location.reload();
                    } else {
                        alert("순서 변경에 실패했습니다.");
                    }
                } catch (e) {
                    console.error("순서 변경 중 오류:", e);
                    alert("순서 변경 중 오류가 발생했습니다.");
                }
            });

            $(document)
            .on('click', '.partnerOrderButton', function () {
                const $block  = $(this).closest('div[data-cid]'); 
                const $tbody  = $block.find('tbody#tbodyPartnerList');
                const $apply  = $block.find('.partnerApplyButton');
                const $btn    = $(this);

                const nowOrdering = !$block.data('partnerOrdering');
                $block.data('partnerOrdering', nowOrdering);

            if (nowOrdering) {
                $block.find('.partner_change').show();
                $apply.show();
                $btn.css("border","2px solid #8392AB");

                if ($tbody.data("ui-sortable")) {
                    $tbody.sortable("refresh");
                } else {
                    $tbody.sortable({
                        items: "> tr[data-partner-idx]:visible",
                        axis: "y",
                        opacity: 0.5,
                        update: function () {
                        const ordered = $tbody.find("> tr[data-partner-idx]").map(function (i) {
                            return {
                            curationItemIdx: $(this).data("partner-idx"),
                            curationItemOrder: i + 1
                            };
                        }).get();
                            $block.data('orderedPartnerIds', ordered);
                        }
                    }).disableSelection();
                }

                const initial = $tbody.find("> tr[data-partner-idx]").map(function (i) {
                    return {
                        curationItemIdx: $(this).data("partner-idx"),
                        curationItemOrder: i + 1
                    };
                }).get();
                $block.data('orderedPartnerIds', initial);

                } else {
                    $block.find('.partner_change').hide();
                    $apply.hide();
                    $btn.css("border","");
                    if ($tbody.data("ui-sortable")) $tbody.sortable("destroy");
                    $block.removeData('orderedPartnerIds');
                }
            })

            .on('click', '.partnerApplyButton', async function () {
            const $block  = $(this).closest('div[data-cid]');
            const curationIdx = $block.data('cid');
            const payload = $block.data('orderedPartnerIds') || [];

            if (!payload.length) {
                alert("변경된 숙소가 없습니다.");
                return;
            }

            try {
                const res = await fetch(`/api/manage/putCurationItemOrder/${encodeURIComponent(curationIdx)}`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(payload)
                    });

                    const result = await res.json();
                    if (result) {
                        alert("숙소 순서가 변경되었습니다.");
                        window.location.reload();
                    } else {
                        alert("숙소 순서 변경 실패");
                    }
                } catch (e) {
                    console.error(e);
                    alert("숙소 순서 변경 중 오류");
                }
            });
        });
    </script>
</body>
</html>