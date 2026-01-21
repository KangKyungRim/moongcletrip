<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php 

$partnerUsers = $data['partnerUsers'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px--0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">숙소 계정 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">숙소 계정 관리</h6>
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
                              <h5 class="mb-0">숙소 계정 관리</h5>
                            </div>

                            <div class="d-flex w-40 align-items-center gap-4" style="justify-content: flex-end;">
                            <?php if ($selectedPartnerIdx > 0) : ?>
                                <div class="ms-auto my-auto d-inline-block">
                                    <a href="/manage/partner-user-create" class="btn btn-primary btn-sm mb-0">
                                        +&nbsp; 신규 관리자 생성
                                    </a> 
                                </div>
                            <?php endif; ?>
                              <div class="col-md-6 d-none">
                                  <div class="form-group mb-0 d-flex align-center gap-2">
                                    <div class="input-group custom">
                                        <select class="form-select custom" id="exampleFormControlSelect1" style="width: 27%;">
                                            <option>소속</option>
                                            <option>이메일</option>
                                        </select> 
                                        <input id="searchInput" class="form-control" placeholder="검색" type="text" value="<?= $_GET['search'] ?? ''; ?>"  style="width: 73%;">
                                    </div>  
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- table -->
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">소속</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">해당 숙소 유형</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">이름</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">레벨</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">이메일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">전화 번호</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">회원 상태</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">생성일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">최근 수정일</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">마지막 로그인</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        usort($partnerUsers, function ($a, $b) {
                                            return strtotime($b->partner_user_created_at) - strtotime($a->partner_user_created_at);
                                        });
                                    ?>
                                    <?php if (!empty($partnerUsers)) : ?>
                                        <?php $index = count($partnerUsers); ?>
                                        <?php foreach ($partnerUsers as $partnerUser) : ?>
                                            <tr>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $index--; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_name; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_category; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_user_name; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <div>
                                                        <?php if ($partnerUser->partner_user_level === 5) : ?>
                                                            <span class="my-2 text-xs">메인 관리자</span>
                                                        <?php else : ?>
                                                            <span class="my-2 text-xs">서브 관리자</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_user_email; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_user_phone_number; ?>
                                                    </span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <div>
                                                        <?php if ($partnerUser->partner_user_status === "normal") : ?>
                                                          <span class="badge badge-dot">
                                                            <i class="bg-success"></i>
                                                            <span class="text-dark text-xs">정상</span>
                                                          </span>
                                                        <?php else : ?>
                                                          <span class="badge badge-dot">
                                                            <i class="bg-danger"></i>
                                                            <span class="text-dark text-xs">정지</span>
                                                          </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_user_created_at; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?= $partnerUser->partner_user_updated_at; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="my-2 text-xs">
                                                        <?php if ($partnerUser->partner_user_last_login_at) : ?>
                                                            <?= $partnerUser->partner_user_last_login_at; ?>
                                                        <?php else : ?>
                                                            -
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">
                                                    <span class="px-1" role="button" id="editForm" name="editForm" class="btn btn-light m-0" onclick="window.location='/manage/partner-user-edit?userIdx=<?= $partnerUser->partner_user_idx; ?>'">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="12" class="text-center py-10">
                                                    숙소 회원이 존재하지 않습니다.
                                                </td>
                                            </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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


    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.7"></script>
</body>

</html>