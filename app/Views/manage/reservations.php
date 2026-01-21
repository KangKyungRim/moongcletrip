<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$reservations = $data['reservations'];

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

<body class="g-sidenav-show bg-gray-100">

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">예약 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">예약 관리</h6>
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
                    <button class="btn btn-icon btn-outline-dark me-2 export" type="button">
                        <span class="btn-inner--text">숨겨진 기능 보기</span>
                    </button>
                    <div class="dropdown d-inline">
                        <a href="javascript:;" class="btn btn-outline-dark dropdown-toggle " data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                            Filters
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-start px-2 py-3" aria-labelledby="navbarDropdownMenuLink2" data-popper-placement="left-start">
                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Status: Paid</a></li>
                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Status: Refunded</a></li>
                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Status: Canceled</a></li>
                            <li>
                                <hr class="horizontal dark my-2">
                            </li>
                            <li><a class="dropdown-item border-radius-md text-danger" href="javascript:;">Remove Filter</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-icon btn-outline-dark ms-2 export" data-type="csv" type="button" onclick="downloadCSV()">
                        <span class="btn-inner--icon"><i class="ni ni-archive-2"></i></span>
                        <span class="btn-inner--text">Export CSV</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div style="padding: 1.5rem;">
                            <!-- 필터링 버튼 -->
                            <div class="d-inline-flex gap-2 align-items-center">
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/reservations'">전체보기</button>
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/reservations?thirdparty=sanha'">직계약</button>
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/reservations?thirdparty=custom'">수기</button>
                                <button class="btn btn-outline-active mb-0" type="button" onclick="location.href='/manage/reservations?thirdparty=onda'">온다</button>

                                <div class="dropdown">
                                    <a href="javascript:;" class="btn bg-gradient-dark dropdown-toggle mb-0" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2" style="padding: 1rem 1..5rem;">
                                       보기
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                        <li>
                                            <a class="dropdown-item" href="/manage/reservations">
                                                전체보기
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/manage/reservations?perPage=20">
                                                20개씩 보기
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/manage/reservations?perPage=50">
                                                50개씩 보기
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/manage/reservations?perPage=100">
                                                100개씩 보기
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/manage/reservations?perPage=500">
                                                500개씩 보기
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/manage/reservations?perPage=1000">
                                                1,000개씩 보기
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>  

                            <div style="float: right;">
                                <input id="searchText" placeholder="Search..." type="text" style="font-size: 0.875rem; color: #495057; border: 1px solid #e9ecef; border-radius: 0.5rem; padding: 6px 12px;">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">상세보기</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7; cursor: pointer;" onclick="updateSorting('updatedAt')">예약일
                                            <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="updatedAt"></span> 
                                        </th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">취소일</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">상품</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">결제상태</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">예약상태</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">예약번호</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">예약확정번호</th>
                                        <th style="min-width: 8.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7; cursor: pointer;" onclick="updateSorting('checkin')">체크인-체크아웃
                                            <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="checkin"></span>
                                        </th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7; cursor: pointer;" onclick="updateSorting('reservationName')">예약자
                                            <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="reservationName"></span>
                                        </th>
                                        <th style="min-width: 8.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">예약자 휴대폰번호</th>
                                        <th style="min-width: 8.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">예약자 이메일</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">숙소명</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">객실명</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">특전</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">객실수량</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">인원</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">쿠폰</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">정상가</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">판매가</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">쿠폰할인금액</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">고객결제금액</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">환불금액</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">입금가</th>
                                        <th style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7;">방문수단</th>
                                        <th class="hidden-col" style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7; display: none;">재전송</th>
                                        <th class="hidden-col" style="min-width: 6.5rem; font-weight: bolder; font-size: 0.65rem; color: #8392ab; opacity: 0.7; display: none;">강제 취소</th>
                                    </tr>
                                </thead>
                                <tbody id="hiddenFeaturesAccordion">
                                    <?php if (count($reservations) === 0) : ?>
                                        <tr>
                                            <td colspan="16" class="py-10 text-sm">
                                                예약 내역이 없습니다.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($reservations as $reservation) : ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center cursor-pointer" onclick="alert('준비중입니다.')">
                                                        <i class="ni ni-zoom-split-in"></i>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->created_at; ?></span>
                                                </td>
                                                <td class="font-weight-bold text-center">
                                                    <span class="my-2 text-xs">
                                                        <?php if ($reservation->payment_status_main == 'canceled') : ?>
                                                            <?= $reservation->updated_at; ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold text-center">
                                                    <span class="my-2 text-xs">
                                                        <?php if ($reservation->product_type == 'room_rateplan') : ?>
                                                            상시상품 <i class="ni ni-curved-next cursor-pointer" onclick="gotoProductPage(<?= $reservation->partner_idx ?>, <?= $reservation->product_idx ?>, '<?= $reservation->product_type ?>')"></i>
                                                        <?php elseif ($reservation->product_type == 'moongcledeal') : ?>
                                                            뭉클딜 <i class="ni ni-curved-next cursor-pointer" onclick="gotoProductPage(<?= $reservation->partner_idx ?>, <?= $reservation->product_idx ?>, '<?= $reservation->product_type ?>')"></i>
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($reservation->payment_status_main == 'paid') : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-check" aria-hidden="true"></i></button>
                                                            <span>결제완료</span>
                                                        <?php elseif ($reservation->payment_status_main == 'canceled') : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-times" aria-hidden="true"></i></button>
                                                            <span>결제취소</span>
                                                        <?php else : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-dark mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-undo" aria-hidden="true"></i></button>
                                                            <span>결제부분취소</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($reservation->reservation_status == 'confirmed') : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-check" aria-hidden="true"></i></button>
                                                            <span>예약완료</span>
                                                        <?php elseif ($reservation->reservation_status == 'completed') : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-info mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-check" aria-hidden="true"></i></button>
                                                            <span>투숙완료</span>
                                                        <?php elseif ($reservation->reservation_status == 'canceled') : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-times" aria-hidden="true"></i></button>
                                                            <span>예약취소</span>
                                                        <?php else : ?>
                                                            <button class="btn btn-icon-only btn-rounded btn-outline-dark mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-undo" aria-hidden="true"></i></button>
                                                            <span>확인필요</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->reservation_number; ?></span>
                                                </td>
                                                <td class="font-weight-bold text-info text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->reservation_confirmed_code; ?></span>
                                                    <span class="px-1" role="button" data-bs-toggle="modal" data-bs-target="#reservation_confirmed_code_<?= $reservation->reservation_number; ?>"><i class="fa-solid fa-pen"></i></span>
                                                </td>
                                                <?php
                                                $startDateTime = new DateTime(substr($reservation->start_date, 0, 19));
                                                $endDateTime = new DateTime(substr($reservation->end_date, 0, 19));

                                                $dateInterval = $startDateTime->diff($endDateTime);
                                                $nights = $dateInterval->days;

                                                $formattedStartDate = $startDateTime->format("Y-m-d");
                                                $formattedEndDate = $endDateTime->format("Y-m-d");
                                                ?>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <div>
                                                        <div class="font-medium text-center"><?= $nights; ?>박</div>
                                                        <div class="text-center">(<?= $formattedStartDate; ?> ~ <?= $formattedEndDate; ?>)</div>
                                                    </div>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->reservation_name; ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->reservation_phone; ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->reservation_email; ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->product_name; ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->product_detail_name; ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <?php 
                                                        $benefits = json_decode($reservation->product_benefits, true);
                                                        if (is_array($benefits)) {
                                                            foreach ($benefits as $benefit) {
                                                                if (!empty($benefit['benefit_name'])) {
                                                                    echo '<div class="my-1 text-xs">' . htmlspecialchars($benefit['benefit_name']) . '</div>';
                                                                }
                                                            }
                                                        } else {
                                                            echo '-';
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= $reservation->quantity; ?></span>
                                                </td>
                                                <?php $personnel = json_decode($reservation->reservation_personnel, true); ?>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <div class="text-center">
                                                        <?php if (!empty($personnel['adult'])) : ?>
                                                            성인 <?= $personnel['adult']; ?><br>
                                                        <?php endif; ?>
                                                        <?php if (!empty($personnel['child'])) : ?>
                                                            아동 <?= $personnel['child']; ?>
                                                            <?php if (!empty($personnel['childAge']) && count($personnel['childAge']) > 0) : ?>
                                                                (<?= implode(", ", array_map(fn($age) => $age . "세", $personnel['childAge'])); ?>)
                                                            <?php endif; ?>
                                                            <br>
                                                        <?php endif; ?>

                                                        <?php if (!empty($personnel['infant'])) : ?>
                                                            유아 <?= $personnel['infant']; ?>
                                                            <?php if (!empty($personnel['infantMonth']) && count($personnel['infantMonth']) > 0) : ?>
                                                                (<?= implode(", ", array_map(fn($month) => $month . "개월", $personnel['infantMonth'])); ?>)
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <?php if (!empty($reservation->coupon_name)) : ?>
                                                        <span class="my-2 text-xs">사용</span>
                                                    <?php else : ?>
                                                        <span class="my-2 text-xs">미사용</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= number_format($reservation->item_basic_price); ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= number_format($reservation->item_sale_price); ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= number_format($reservation->coupon_discount_amount); ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= number_format($reservation->item_sale_price - $reservation->coupon_discount_amount); ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <span class="my-2 text-xs"><?= number_format($reservation->refund_amount); ?></span>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <?php if ($reservation->partner_thirdparty == 'onda') : ?>
                                                        <span class="my-2 text-xs"><?= number_format($reservation->item_origin_sale_price * 0.93); ?></span>
                                                    <?php else : ?>
                                                        <?php if (!empty($reservation->payment_partner_charge)) : ?>
                                                            <span class="my-2 text-xs"><?= number_format($reservation->item_sale_price * ((100 - $reservation->payment_partner_charge) / 100)); ?></span>
                                                        <?php else : ?>
                                                            <span class="my-2 text-xs"><?= number_format($reservation->item_sale_price * ((100 - $reservation->partner_charge) / 100)); ?></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-xs font-weight-bold text-center">
                                                    <?php if ($reservation->visit_way == 'vehicle') : ?>
                                                        <span class="my-2 text-xs">차량</span>
                                                    <?php else : ?>
                                                        <span class="my-2 text-xs">도보</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="hidden-col text-xs font-weight-bold text-center" style="display: none;">
                                                    <button type="button" class="btn btn-default retransmission" data-bs-toggle="modal" data-bs-target="#retransmission_confirmed_code_<?= $reservation->reservation_number; ?>">예약 재전송</button>
                                                </td>
                                                <td class="hidden-col text-xs font-weight-bold text-center" style="display: none;">
                                                    <button type="button" class="btn btn-danger cancellation" data-bs-toggle="modal" data-bs-target="#cancellation_confirmed_code_<?= $reservation->reservation_number; ?>">예약 강제 취소</button>
                                                </td>
                                            </tr>

                                            <!-- 예약 확정 번호 수정 -->
                                            <div class="modal fade" id="reservation_confirmed_code_<?= $reservation->reservation_number; ?>" tabindex="-1" role="dialog" aria-labelledby="reservation_confirmed_code_<?= $reservation->reservation_number; ?>" insert>
                                                <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fs-6" id="exampleModalLabel">예약 확정 번호 수정</h5>
                                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                                <span insert>×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-0">
                                                            <div class="card card-plain">
                                                                <div class="card-body">
                                                                    <div class="form-group row align-items-center mb-0">
                                                                        <div class="row col align-items-center">
                                                                            <input 
                                                                                class="form-control w-100 code_input" 
                                                                                type="text" 
                                                                                id="reservation_confirmed_code_<?= $reservation->payment_idx; ?>"
                                                                                data-reservation-code="<?= $reservation->reservation_confirmed_code; ?>"
                                                                                value="<?= $reservation->reservation_confirmed_code; ?>" 
                                                                                placeholder="예약 확정 번호를 입력해 주세요" 
                                                                                data-payment-idx="<?= $reservation->payment_idx; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                                                            <button type="button" id="saveButton" class="btn bg-gradient-primary saveButton" data-payment-idx="<?= $reservation->payment_idx; ?>" data-bs-target="reservation_confirmed_code_<?= $reservation->reservation_number; ?>">저장하기</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 예약 재전송 확인 -->
                                            <div class="modal fade" id="retransmission_confirmed_code_<?= $reservation->reservation_number; ?>" tabindex="-1" role="dialog" aria-labelledby="retransmission_confirmed_code_<?= $reservation->reservation_number; ?>" insert>
                                                <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card card-plain">
                                                                <div class="card-body text-center">
                                                                    <p class="fw-bold text-sm mb-3" style="color: #344767;">예약을 재전송 하시겠습니까?</p>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input mt-0" type="checkbox" id="retransmission_confirmed_<?= $reservation->payment_idx; ?>" data-payment-idx="<?= $reservation->payment_idx; ?>" style="float: none; vertical-align: middle;">
                                                                        <label class="cursor-pointer" for="retransmission_confirmed_<?= $reservation->payment_idx; ?>">확인</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                                                            <button type="button" id="retransmissionButton" class="btn bg-gradient-primary retransmissionButton" data-payment-idx="<?= $reservation->payment_idx; ?>" data-bs-target="retransmission_confirmed_code_<?= $reservation->reservation_number; ?>">재전송</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                             <!-- 예약 강제 취소 확인 -->
                                             <div class="modal fade" id="cancellation_confirmed_code_<?= $reservation->reservation_number; ?>" tabindex="-1" role="dialog" aria-labelledby="cancellation_confirmed_code_<?= $reservation->reservation_number; ?>" insert>
                                                <div class="modal-dialog modal-dialog-centered modal-m" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card card-plain">
                                                                <div class="card-body text-center">
                                                                    <p class="fw-bold text-sm mb-3" style="color: #344767;">예약을 강제 취소 하시겠습니까?</p>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input mt-0" type="checkbox" id="cancellation_confirmed_<?= $reservation->payment_idx; ?>" data-payment-idx="<?= $reservation->payment_idx; ?>" style="float: none; vertical-align: middle;">
                                                                        <label class="cursor-pointer" for="cancellation_confirmed_<?= $reservation->payment_idx; ?>">확인</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                                                            <button type="button" id="cancellationButton" class="btn bg-gradient-primary cancellationButton" data-payment-idx="<?= $reservation->payment_idx; ?>" data-bs-target="cancellation_confirmed_code_<?= $reservation->reservation_number; ?>">강제 취소</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>

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
    <script src="/assets/manage/js/plugins/datatables.js"></script>

    <script>
        // 숨겨진 기능 보기
        $(document).ready(function () {
            $('.export').on('click', function () {
                const $hiddenElements = $('.hidden-col');
                const $firstButton = $hiddenElements.find('button').first();

                // 버튼 스타일 토글
                $(this).toggleClass('active');

                if ($hiddenElements.first().is(':visible')) {
                    $hiddenElements.fadeOut(200);
                } else {
                    $hiddenElements.fadeIn(300, function () {
                        // 버튼에 포커스 줘서 자동 스크롤 유도
                        $firstButton.trigger('focus');
                    });
                }
            });
        });
    </script>

    <script>
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

        document.getElementById("searchText").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();

                let url = new URL(window.location.href);
                let params = new URLSearchParams(url.search);

                let searchText = this.value.trim();
                if (searchText) {
                    params.set("searchText", searchText);
                } else {
                    params.delete("searchText");
                }

                if (params.get("page") !== "1") {
                    params.set("page", "1");
                }

                window.location.href = url.pathname + "?" + params.toString();
            }
        });

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

        // 예약 확정 번호 수정
        document.querySelectorAll('.saveButton').forEach(button => {
            button.addEventListener('click', async function() {
                const paymentItemIdx = this.getAttribute('data-payment-idx');

                const modalId = this.getAttribute('data-bs-target').replace('reservation_confirmed_code_', '');
                const modal = document.querySelector(`#reservation_confirmed_code_${modalId}`);

                const inputField = modal.querySelector('.code_input');

                const confirmedCode = inputField.value;

                const formData = {
                    paymentItemIdx: Number(paymentItemIdx),
                    code: confirmedCode
                };

                try {
                    // 서버 요청
                    const response = await fetch('/api/reservation/edit-confirmed-code', {
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
        // 예약 재전송
        document.querySelectorAll('.retransmissionButton').forEach(button => {
            button.addEventListener('click', async function() {
                const paymentIdx = this.getAttribute('data-payment-idx');

                const modalId = this.getAttribute('data-bs-target');
                const modal = document.getElementById(modalId);
                const checkbox = document.getElementById(`retransmission_confirmed_${paymentIdx}`);

                if (!checkbox.checked) {
                    alert('확인을 체크해 주세요.');
                    return;
                }

                const formData = {
                    paymentIdx: Number(paymentIdx)
                };

                try {
                    // 서버 요청
                    const response = await fetch('/api/reservation/resend-reservation', {
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
                        alert('완료되었습니다.');
                        loading.style.display = 'flex';
                        location.reload();
                    } else {
                        alert(result.error || '문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('오류가 발생했습니다.');
                }
            });
        });

        // 예약 강제 취소
        document.querySelectorAll('.cancellationButton').forEach(button => {
            button.addEventListener('click', async function() {
                const paymentIdx = this.getAttribute('data-payment-idx');

                const modalId = this.getAttribute('data-bs-target');
                const modal = document.getElementById(modalId);
                const checkbox = document.getElementById(`cancellation_confirmed_${paymentIdx}`);

                if (!checkbox.checked) {
                    alert('확인을 체크해 주세요.');
                    return;
                }

                const formData = {
                    paymentIdx: Number(paymentIdx)
                };

                try {
                    // 서버 요청
                    const response = await fetch('/api/reservation/resend-reservation-cancel', {
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
                        alert('완료되었습니다.');
                        loading.style.display = 'flex';
                        location.reload();
                    } else {
                        alert(result.error || '문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('오류가 발생했습니다.');
                }
            });
        });

        // 모달 닫히면 초기화
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const checkbox = modal.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = false;
                }

                const inputField = modal.querySelector('input[id^="reservation_confirmed_code_"]');
                if (inputField) {
                    const originalValue = inputField.getAttribute('data-reservation-code');
                    inputField.value = originalValue;
                }
            });
        });
    </script>

    <script>
        function downloadCSV() {
            let table = document.querySelector(".table.table-flush");
            let rows = table.querySelectorAll("tr");
            let csvContent = [];

            rows.forEach(row => {
                let rowData = [];
                row.querySelectorAll("th, td").forEach(cell => {
                    let text = cell.innerText.replace(/"/g, '""');
                    rowData.push(`"${text}"`);
                });
                csvContent.push(rowData.join(","));
            });

            let csvString = csvContent.join("\n");
            let blob = new Blob([csvString], {
                type: "text/csv;charset=utf-8;"
            });
            let url = URL.createObjectURL(blob);

            let a = document.createElement("a");
            a.href = url;
            a.download = "reservations.csv"; // 파일명 설정
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function gotoProductPage(partnerIdx, productIdx, productType) {
            if (!partnerIdx || !productIdx || !productType) {
                return;
            }

            let url = `/stay/detail/${partnerIdx}`;

            if (productType == 'moongcledeal') {
                url = `/moongcleoffer/product/${partnerIdx}`;
            }

            window.open(url, '_blank');
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

    <script>
        $(document).ready(function () {     
            $('.modal').on('hide.bs.modal', function () {
                $('div, button, input, select, textarea').each(function () {
                    $(this).blur();
                });
            });
        });
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.7"></script>

</body>

</html>