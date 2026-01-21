<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$partnerServices = $data['partnerServices'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">뭉클맘들의 소셜 후기</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">뭉클맘들의 소셜 후기</h6>
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
                                    <h5 class="mb-0">뭉클맘들의 소셜 후기</h5>
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <a href="/manage/partner-social-create" class="btn btn-primary btn-sm mb-0">+&nbsp; 신규 생성</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" style="table-layout: fixed;">
                                <colgroup>
                                    <col style="width: 40%;">
                                    <col style="width: 30%;">
                                    <col style="width: 30%;">
                                    <col style="width: 30%;">
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">블로그</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">제목</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">썸네일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">링크</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">삭제</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold my-2 text-xs">00맘의 블로그 3</td>
                                        <td class="font-weight-bold my-2 text-xs">호텔 필수 코스 웨스턴 수영장</td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer">
                                                <img src="/images/sample/service_sample1.jpg" alt="썸네일" style="width: 100px; height: auto; border-radius: 8px;">
                                            </div>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <a href="https://www.naver.com/" target="_blank">https://www.naver.com/</a>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer">
                                                <a href="/manage/partner-social-edit">
                                                    <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer deleteBtn">                                            
                                                <i class="fa-solid fa-trash" style="color:#67748e;"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold my-2 text-xs">00맘의 블로그 2</td>
                                        <td class="font-weight-bold my-2 text-xs">호텔 필수 코스 웨스턴 수영장</td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer">
                                                <img src="/images/sample/service_sample1.jpg" alt="썸네일" style="width: 100px; height: auto; border-radius: 8px;">
                                            </div>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <a href="https://www.naver.com/" target="_blank">https://www.naver.com/</a>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer">
                                                <a href="/manage/partner-social-edit">
                                                    <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer deleteBtn">                                            
                                                <i class="fa-solid fa-trash" style="color:#67748e;"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold my-2 text-xs">00맘의 블로그 1</td>
                                        <td class="font-weight-bold my-2 text-xs">호텔 필수 코스 웨스턴 수영장</td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer">
                                                <img src="/images/sample/service_sample1.jpg" alt="썸네일" style="width: 100px; height: auto; border-radius: 8px;">
                                            </div>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <a href="https://www.naver.com/" target="_blank">https://www.naver.com/</a>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer">
                                                <a href="/manage/partner-social-edit">
                                                    <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold my-2 text-xs">
                                            <div class="cursor-pointer deleteBtn">                                            
                                                <i class="fa-solid fa-trash" style="color:#67748e;"></i>
                                            </div>
                                        </td>
                                    </tr>
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

    <script>
        // 삭제하기
        document.querySelectorAll('.deleteBtn').forEach(function (button) {
            button.addEventListener('click', async function () {
                const serviceIdx = this.getAttribute('data-service-idx');

                if (!serviceIdx) {
                    alert('삭제할 항목의 정보가 없습니다.');
                    return;
                }

                const isConfirmed = confirm('정말 삭제하시겠습니까?');
                if (!isConfirmed) {
                    return;
                }

                const formData = {
                    partnerIdx: <?= $selectedPartnerIdx; ?>,
                    serviceDetailIdx: serviceIdx
                };

                try {
                    const response = await fetch('/api/partner/delete-service-detail', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('삭제가 완료되었습니다.');
                    window.location.reload();
                } else {
                    alert(result.error || '삭제 중 문제가 발생했습니다.');
                }
                } catch (error) {
                    console.error('Error:', error);
                    alert('삭제 중 오류가 발생했습니다.');
                }
            });
        });
    </script>
</body>

</html>