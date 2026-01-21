<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
$startDate = new DateTime($startDate);

$rooms = $data['rooms'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">요금 및 인벤토리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">요금 캘린더</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">요금 캘린더</h6>
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
                        
                        <?php if (count($rooms) === 0) : ?>
                            <div class="d-flex align-items-center justify-content-center" style="height: 50vh;">
                                등록되어있는 요금이 없습니다.
                            </div>
                        <?php else : ?>
                        <div class="btnWrap d-flex justify-content-between align-items-center" style="padding:20px 20px 0;">
                            <div class="left d-flex gap-2">
                                <button class="btn btn-outline-active mb-0" type="button" id="roomsCheck" data-bs-toggle="dropdown" aria-expanded="false">객실 선택</button>
                                <div class="dropdown-menu p-0" aria-labelledby="roomsCheck" style="box-shadow: 0px 0px 20px rgba(0, 0, 0, 9%); min-width: 15%;">
                                    <div class="p-3">
                                        <?php foreach ($data['roomList'] as $room_idx => $room) : ?>
                                            <div class="form-check d-flex align-items-center gap-2 mb-1">
                                            <input 
                                                class="form-check-input mt-0" 
                                                type="checkbox" 
                                                id="room_<?= $room['room_idx']; ?>"
                                                name="roomName">
                                            <label class="cursor-pointer" for="room_<?= $room['room_idx']; ?>">
                                                <?= $room['room_name']; ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="allCheckBox p-3 d-flex justify-content-between" style="border-top: 1px solid #e9ecef;">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" id="allRoom" class="form-check-input mt-0" name="allRoom" onclick="selectAllRoom();">
                                            <label for="allRoom" class="cursor-pointer">전체 선택/해제</label>
                                        </div>
                                        <button class="btn btn-primary btn-sm mb-0 px-3" id="roomApply">
                                            적용
                                        </button>
                                    </div>
                                </div>

                                <button class="btn btn-outline-active mb-0" type="button" id="rateplanCheck" data-bs-toggle="dropdown" aria-expanded="false">요금제 선택</button>
                                <div class="dropdown-menu p-0" aria-labelledby="rateplanCheck" style="box-shadow: 0px 0px 20px rgba(0, 0, 0, 9%); min-width: 15%;">
                                    <div class="p-3">
                                        <?php foreach ($data['rateplanList'] as $rateplan_idx => $rateplan) : ?>
                                            <div class="form-check d-flex align-items-center gap-2 mb-1">
                                                <input 
                                                    class="form-check-input mt-0" 
                                                    type="checkbox" 
                                                    id="rateplan_<?= $rateplan['rateplan_idx']; ?>"
                                                    name="rateplanName">
                                                <label class="cursor-pointer" for="rateplan_<?= $rateplan['rateplan_idx']; ?>">
                                                    <?= $rateplan['rateplan_name']; ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="allCheckBox p-3 d-flex justify-content-between" style="border-top: 1px solid #e9ecef;">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" id="allRateplan" class="form-check-input mt-0" name="allRateplan" onclick="selectAllRateplan();">
                                            <label for="allRateplan" class="cursor-pointer">전체 선택/해제</label>
                                        </div>
                                        <button class="btn btn-primary btn-sm mb-0 px-3" id="rateplanApply">
                                            적용
                                        </button>
                                    </div>
                                </div>
                                
                                <button class="btn btn-outline-active mb-0" type="button" id="viewItemsCheck" data-bs-toggle="dropdown" aria-expanded="false">보기 항목 선택</button>
                                <div class="dropdown-menu p-0" aria-labelledby="viewItemsCheck" style="box-shadow: 0px 0px 20px rgba(0, 0, 0, 9%); min-width: 15%;">
                                    <div class="p-3">
                                        <div class="form-check d-flex align-items-center gap-2 mb-1">
                                            <input 
                                                class="form-check-input mt-0" 
                                                type="checkbox" 
                                                id="viewItems_quantity"
                                                name="viewItemsCheck">
                                            <label class="cursor-pointer" for="viewItems_quantity">재고</label>
                                        </div>
                                        <div class="form-check d-flex align-items-center gap-2 mb-1">
                                            <input 
                                                class="form-check-input mt-0" 
                                                type="checkbox" 
                                                id="viewItems_price"
                                                name="viewItemsCheck">
                                            <label class="cursor-pointer" for="viewItems_price">요금</label>
                                        </div>
                                        <div class="form-check d-flex align-items-center gap-2 mb-1">
                                            <input 
                                                class="form-check-input mt-0" 
                                                type="checkbox" 
                                                id="viewItems_select"
                                                name="viewItemsCheck">
                                            <label class="cursor-pointer" for="viewItems_select">마감 여부</label>
                                        </div>
                                    </div>
                                    <div class="allCheckBox p-3 d-flex justify-content-between" style="border-top: 1px solid #e9ecef;">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" id="allViewItems" class="form-check-input mt-0" name="allViewItems" onclick="selectAllViewItems();">
                                            <label for="allViewItems" class="cursor-pointer">전체 선택/해제</label>
                                        </div>
                                        <button class="btn btn-primary btn-sm mb-0 px-3" id="viewItemsApply">
                                            적용
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <button class="btn btn-secondary mb-0 mx-2" type="button" style="background: rgb(244, 239, 255); color: #2d303b; <?= $selectedPartner->partner_thirdparty === "sanha" ? 'display: none;' : ''; ?>" id="selectDateBtn">
                                    <img src="/assets/manage/images/ico_mng_moongcledeal_06.png" alt="체크" class="me-1"> 선택 날짜 마감 여부 수정
                                </button>
                                <button class="btn btn-secondary mb-0" type="button" data-bs-toggle="modal" data-bs-target="#discount-modification">
                                    <img src="/assets/manage/images/ico_mng_moongcledeal_05.png" alt="번개" class="me-1"> 상시 할인(%) 일괄 수정
                                </button>
                                <button class="btn btn-secondary mb-0" type="button" data-bs-toggle="modal" data-bs-target="#batch-modification" style="<?= $selectedPartner->partner_thirdparty === "sanha" ? 'display: none;' : ''; ?>">
                                    <img src="/assets/manage/images/ico_mng_moongcledeal_02.png" alt="날짜" class="me-2"> 기간 지정 일괄 수정
                                </button>
                                <button type="button" id="submitForm" name="submitForm" class="btn bg-gradient-primary m-0 ms-2" onclick="saveInventory()">저장하기</button>
                            </div>
                        </div>
                        

                        <div class="tableWrap">
                            <div class="tableScoll">
                                <table id="calendar" class="calendar">
                                    <colgroup>
                                        <col style="width: 62%;">
                                        <col style="width: 35%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-xs border-right sticky-header-calendar w-50" colspan="2">
                                                <p class="text-xs fw-bold">시작일 선택</p>
                                                <div class="dateSelectBox my-2" style="width: 330px;">
                                                    <div id="startDate-selector">
                                                        <div class="relative">
                                                            <div class="cursor-pointer d-inline-block" id="prev-date">
                                                                <i class="fa-solid fa-chevron-left"></i>
                                                            </div>

                                                            <div class="d-inline-block mx-3">
                                                                <i class="fa-regular fa-calendar" style="color:#fff"></i>
                                                                <input id="startDate" type="date" name="startDate" class="datepicker text-white fw-bold text-center" style="width: 5rem !important;" />
                                                            </div>
                                                            
                                                            <div class="cursor-pointer d-inline-block" id="next-date">
                                                                <i class="fa-solid fa-chevron-right"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <p class="text-xs fw-bold">시작일로부터 +45일까지의 요금이 보입니다.</p>
                                            </th>
                                            <?php
                                                $today = new DateTime();
                                                $today->setTime(0, 0, 0);

                                                for ($i = 0; $i < 45; $i++) :
                                                    $currentDate = clone $startDate;
                                                    $currentDate->modify("+$i days");

                                                    $dayOfWeek = ['일', '월', '화', '수', '목', '금', '토'][$currentDate->format('w')];
                                                    $formattedDate = $currentDate->format('m/d');
                                                    $date = $currentDate->format('Y-m-d');

                                                    $extraClass = ($dayOfWeek === '토') ? 'saturday' : (($dayOfWeek === '일') ? 'sunday' : '');
                                                    $isPastDate = ($currentDate < $today) ? 'past-date' : '';
                                                ?>
                                                <th class="text-uppercase w-20 <?= $extraClass; ?> position-relative">
                                                    <input 
                                                        type="checkbox" 
                                                        id="date_<?= $date; ?>" 
                                                        name="date-select[]" 
                                                        data-date-info="<?= $formattedDate; ?>(<?= $dayOfWeek; ?>)" 
                                                        class="d-none date-select <?= $isPastDate; ?>">
                                                    <label for="date_<?= $date; ?>" class="date_lavel"></label>
                                                    <p class="text-xs fw-bold"><?= $dayOfWeek; ?></p>
                                                    <p class="text-xs fw-bold"><?= $formattedDate; ?></p>
                                                </th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>

                                    <tbody id="calendarBody">
                                        <?php foreach ($rooms as $roomIdx => $room) : ?>
                                            <tr class="head">
                                                <th class="d-flex align-items-start justify-content-center gap-2">
                                                    <button type="button" id="toggleButton" class="toggle-btn" data-group="room<?= $roomIdx; ?>">
                                                        <i id="toggleIcon" class="fa-solid fa-chevron-down"></i>
                                                    </button>
                                                    <span class="d-inline-block w-80">
                                                        <?= $room['room_name']; ?>
                                                    </span>
                                                </th>
                                                <th class="second-th">보유 재고 / 판매 수량</th>

                                                <?php foreach ($room['quantity'] as $date  => $quantity) : ?>
                                                    <?php $isPast = strtotime($date) < strtotime(date('Y-m-d')); ?>
                                                    <td style="<?= $isPast ? "background: #2e303b08;" : "" ?> <?= $selectedPartner->partner_thirdparty === "sanha" ? 'background: #2e303b08;' : ''; ?>" >
                                                        <input class="w-15 text-end quantity" 
                                                            value="<?= $quantity; ?>" 
                                                            type="text" 
                                                            name="rooms[<?= $roomIdx; ?>][quantity][<?= $date; ?>]" 
                                                            style="margin-left: -0.7rem;"
                                                            oninput="inputQuantity(this);"
                                                            onkeyup="this.value=this.value.replace(/[^-0-9,]/g,'');"
                                                            onkeydown="priceNegative(event);"
                                                            <?= $selectedPartner->partner_thirdparty === "sanha" ? 'disabled' : ''; ?>
                                                            <?= $isPast ? 'disabled' : ''; ?>
                                                            placeholder="없음"> / 

                                                            <?php 
                                                                if (isset($room['soldQuantity'][$date])) {
                                                                    echo $room['soldQuantity'][$date] === "" ? "0" : $room['soldQuantity'][$date];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                            ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>

                                            <?php foreach ($room['rateplans'] as $rateplanIdx => $rateplan) : ?>
                                                <tr data-group="room<?= $roomIdx; ?>" class="collapsible">
                                                    <th class="bg-white" rowspan="4">
                                                        <?= $rateplan['rateplan_name'] ?>
                                                    </th>
                                                    <th class="bg-white second-th price-row">일반 요금</th>

                                                    <?php foreach ($rateplan['inventory'] as $inventoryIdx => $inventory) : ?>
                                                        <?php 
                                                            $isPast = strtotime($inventory->room_price_date) < strtotime(date('Y-m-d')); 

                                                            $hasStock = isset($room['quantity'][$inventory->room_price_date]) && $room['quantity'][$inventory->room_price_date] !== "";

                                                            $isEmptySoldQuantity = isset($room['soldQuantity'][$inventory->room_price_date]) && $room['soldQuantity'][$inventory->room_price_date] === "";

                                                            $disablePrice = $isPast || (!$hasStock && $isEmptySoldQuantity);
                                                        ?>
                                                        <td style="<?= $disablePrice ? "background: #2e303b08;" : "" ?> <?= $selectedPartner->partner_thirdparty === "sanha" ? 'background: #2e303b08;' : ''; ?>" class="price-row">
                                                            <input class="text-center priceBasic" 
                                                                value="<?= !empty($inventory->room_price_basic) ? number_format((int) $inventory->room_price_basic) : ''; ?>" 
                                                                type="text" 
                                                                id="priceBasic_<?= $roomIdx; ?><?= $rateplanIdx; ?><?= $inventory->room_price_date; ?>"
                                                                name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][<?= $inventory->room_price_date; ?>][priceBasic]"
                                                                oninput="updateAll(this);"
                                                                <?= $selectedPartner->partner_thirdparty === "sanha" ? 'disabled' : ''; ?>
                                                                <?= $isPast ? 'disabled' : ''; ?>
                                                                onkeyup="this.value=this.value.replace(/[^-0-9,]/g,''); formatPriceInput(this);"
                                                                onkeydown="priceNegative(event);"
                                                                onclick="checkStockBeforeClick(this, '<?= $roomIdx; ?>', '<?= $inventory->room_price_date; ?>')"
                                                                style="color: <?= ($inventory->is_closed === '1') ? "#ff3f3f !important" : "" ; ?>;">
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>

                                                <tr data-group="room<?= $roomIdx; ?>" class="collapsible">
                                                    <th class="bg-white second-th price-row">상시 할인(%)</th>

                                                    <?php foreach ($rateplan['inventory'] as $inventoryIdx => $inventory) : ?>
                                                            <?php 
                                                                $isPast = strtotime($inventory->room_price_date) < strtotime(date('Y-m-d')); 

                                                                $hasStock = isset($room['quantity'][$inventory->room_price_date]) && $room['quantity'][$inventory->room_price_date] !== "";

                                                                $isEmptySoldQuantity = isset($room['soldQuantity'][$inventory->room_price_date]) && $room['soldQuantity'][$inventory->room_price_date] === "";

                                                                $disablePrice = $isPast || (!$hasStock && $isEmptySoldQuantity);
                                                            ?>
                                                            <td style="<?= $disablePrice ? "background: #2e303b08;" : "" ?>" class="price-row">
                                                                <input class="text-center salePercent" 
                                                                    value="<?= $inventory->room_price_sale_percent; ?>" 
                                                                    type="text"
                                                                    id="salePercent_<?= $roomIdx; ?><?= $rateplanIdx; ?><?= $inventory->room_price_date; ?>"
                                                                    name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][<?= $inventory->room_price_date; ?>][salePercent]"
                                                                    min="0"
                                                                    max="100"
                                                                    oninput="updateDiscountPrice(this)"
                                                                    <?= $isPast ? 'disabled' : ''; ?>
                                                                    onkeydown="blockNegative(event);"
                                                                    onkeyup="this.value=this.value.replace(/[^-0-9.,]/g,'');"
                                                                    onclick="checkStockBeforeClick(this, '<?= $roomIdx; ?>', '<?= $inventory->room_price_date; ?>')"
                                                                    style="color: <?= ($inventory->is_closed === '1') ? "#ff3f3f !important" : "" ; ?>;">
                                                            </td>
                                                    <?php endforeach; ?>
                                                </tr>

                                                <tr data-group="room<?= $roomIdx; ?>" class="collapsible">
                                                    <th class="bg-white second-th price-row">할인가</th>
                                                    
                                                    <?php foreach ($rateplan['inventory'] as $inventoryIdx => $inventory) : ?>
                                                        <?php 
                                                            $isPast = strtotime($inventory->room_price_date) < strtotime(date('Y-m-d')); 

                                                            $hasStock = isset($room['quantity'][$inventory->room_price_date]) && $room['quantity'][$inventory->room_price_date] !== "";

                                                            $isEmptySoldQuantity = isset($room['soldQuantity'][$inventory->room_price_date]) && $room['soldQuantity'][$inventory->room_price_date] === "";

                                                            $disablePrice = $isPast || (!$hasStock && $isEmptySoldQuantity);
                                                        ?>
                                                        <td style="<?= $disablePrice ? "background: #2e303b08;" : "" ?> <?= $selectedPartner->partner_thirdparty === "sanha" ? 'background: #2e303b08;' : ''; ?>" class="price-row">
                                                            <input class="text-center priceSale" 
                                                                value="<?= !empty($inventory->room_price_sale) ? number_format((int) $inventory->room_price_sale) : ''; ?>" 
                                                                type="text" 
                                                                id="priceSale_<?= $roomIdx; ?><?= $rateplanIdx; ?><?= $inventory->room_price_date; ?>"
                                                                name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][<?= $inventory->room_price_date; ?>][priceSale]"
                                                                oninput="updateDiscountRate(this);"
                                                                <?= $selectedPartner->partner_thirdparty === "sanha" ? 'disabled' : ''; ?>
                                                                <?= $isPast ? 'disabled' : ''; ?>
                                                                onkeyup="this.value=this.value.replace(/[^-0-9,]/g,''); formatPriceInput(this);"
                                                                onkeydown="priceNegative(event);"
                                                                onclick="checkStockBeforeClick(this, '<?= $roomIdx; ?>', '<?= $inventory->room_price_date; ?>')"
                                                                style="color: <?= ($inventory->is_closed === '1') ? "#ff3f3f !important" : "" ; ?>;">
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>

                                                <tr data-group="room<?= $roomIdx; ?>" class="collapsible">
                                                    <th class="bg-white second-th select-row">마감 여부</th>
                                                    
                                                    <?php foreach ($rateplan['inventory'] as $inventoryIdx => $inventory) : ?>
                                                        <?php
                                                            $isPast = strtotime($inventory->room_price_date) < strtotime(date('Y-m-d')); 

                                                            $hasStock = isset($room['quantity'][$inventory->room_price_date]) && $room['quantity'][$inventory->room_price_date] !== "";

                                                            $isEmptySoldQuantity = isset($room['soldQuantity'][$inventory->room_price_date]) && $room['soldQuantity'][$inventory->room_price_date] === "";

                                                            $disablePrice = $isPast || (!$hasStock && $isEmptySoldQuantity);

                                                            $isBasicZeroOrEmpty = empty($inventory->room_price_basic);
                                                        ?>
                                                            <td style="<?= ($disablePrice || $isBasicZeroOrEmpty) ? "background: #2e303b08;" : "" ?> <?= $selectedPartner->partner_thirdparty === "sanha" ? 'background: #2e303b08;' : ''; ?>" class="select-row">
                                                                <select class="text-center isClosed"
                                                                    name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][<?= $inventory->room_price_date; ?>][isClosed]"
                                                                    <?= $selectedPartner->partner_thirdparty === "sanha" ? 'disabled' : ''; ?>
                                                                    <?= $isBasicZeroOrEmpty ? "disabled" : ""; ?>
                                                                    <?= $disablePrice ? 'disabled' : ''; ?>
                                                                    onclick="checkStockBeforeClick(this, '<?= $roomIdx; ?>', '<?= $inventory->room_price_date; ?>')"
                                                                    oninput="selectStatus(this);"
                                                                    style="color: <?= ($inventory->is_closed === '1') ? "#ff3f3f !important" : "" ; ?>;">
                                                                    <option value="empty" <?= ($inventory->is_closed !== '0' && $inventory->is_closed !== '1') ? "selected" : "" ; ?> disabled>---</option>
                                                                    <option value="open" <?= ($inventory->is_closed === '0') ? "selected" : "" ; ?>>판매 중</option>
                                                                    <option value="close" <?= ($inventory->is_closed === '1') ? "selected" : "" ; ?>>마감</option>
                                                                </select>
                                                            </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php endif; ?>
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

        <!-- 선택 날짜 마감 여부 수정 -->
        <div class="modal fade" id="isClosed-modification" tabindex="-1" role="dialog" aria-labelledby="isClosed-modification" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-m" role="document" style="max-width: 50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-6" id="exampleModalLabel">선택 날짜 마감 여부 수정</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-body">
                                <!-- 선택 날짜 -->
                                <div class="dateSelectBox">
                                    <div class="form-group row align-items-center">
                                        <label for="dateSelect" class="form-control-label col-sm-2 mb-0">선택 날짜</label>
                                        <div class="col d-flex align-items-center gap-1 p-0 flex-wrap selectDateWrap"></div>
                                    </div>
                                    <div class="form-group row align-items-center tableWrap p-0">
                                        <label for="isClosedAll" class="form-control-label col-sm-2 mb-0">일괄 적용</label>
                                        <select class="text-center d-inline-block w-auto text-xs px-1 py-1"
                                            name="isClosedAll"
                                            id="isClosedAll"
                                            style="background: #f4efff !important; border-radius: 5px;"
                                            <?= $selectedPartner->partner_thirdparty === "sanha" ? 'disabled' : ''; ?>>
                                            <option value="empty" selected>변경 없음</option>
                                            <option value="open">판매 중</option>
                                            <option value="close">마감</option>
                                        </select> 
                                    </div>
                                    <div class="caution">
                                        <p class="text-xs fw-bold mb-0">* 신규 입력해주시는 정보만 업데이트 반영되며, 비워두시면 기존 설정된 값으로 유지됩니다.</p>
                                        <p class="text-xs fw-bold mb-0" style="color: red;">* 마감 여부 수정 시 해당 객실의 모든 요금제에 반영됩니다.</p>
                                    </div>
                                </div>

                                <!-- 적용 테이블 -->
                                <div class="tableWrap p-0 mt-3 h-auto" style="max-height: 50vh; overflow-y: auto;">
                                    <table class="calendar w-100">
                                        <thead>
                                            <tr>
                                                <th>객실</th>
                                                <th class="w-50">마감 여부</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selectDateBodyModal">
                                            <?php foreach ($rooms as $roomIdx => $room) : ?>
                                                <tr class="head">
                                                    <th class="d-flex align-items-start justify-content-center gap-1">
                                                        <span class="d-inline-block w-80">
                                                            <?= $room['room_name']; ?>
                                                        </span>
                                                    </th>
                                                    <td style="<?= $selectedPartner->partner_thirdparty === "sanha" ? 'background: #2e303b08;' : ''; ?>">
                                                        <select class="text-center selectDateModalIsClosed"
                                                            name="rooms[<?= $roomIdx; ?>][isClosed]"
                                                            oninput="selectDateStatusModal(this);"
                                                            <?= $selectedPartner->partner_thirdparty === "sanha" ? 'disabled' : ''; ?>>
                                                            <option value="empty" selected>변경 없음</option>
                                                            <option value="open">판매 중</option>
                                                            <option value="close">마감</option>
                                                        </select> 
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                        <button type="button" id="createButton" class="btn bg-gradient-primary" onclick="saveSelectDateModification();">저장하기</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 상시 할인(%) 일괄 수정 -->
        <div class="modal fade" id="discount-modification" tabindex="-1" role="dialog" aria-labelledby="discount-modification" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-m" role="document" style="max-width: 50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-6" id="exampleModalLabel">상시 할인(%) 일괄 수정</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-body">
                                <!-- 기간 선택 -->
                                <div class="dateSelectBox">
                                    <div class="form-group row align-items-center">
                                        <label for="dateSelect" class="form-control-label col-sm-2 mb-0">기간 선택</label>
                                        <div class="col d-flex align-items-center gap-1 p-0 flex-wrap">
                                            <div class="dateSelectBox border-radius-md d-flex align-items-center p-2 gap-1" style="border: 1px solid #2e303b;">
                                                <i class="fa-regular fa-calendar" style="color:#2d303b;"></i>
                                                <input id="discount-date" type="text" name="discount-date" class="datepicker custom text-xs fw-bold text-center outline-none border-0" data-class="my-custom-class" >
                                            </div>
                                            
                                            <!-- 요일 선택 -->
                                            <div id="select-weekday-discount" class="d-flex align-items-center gap-1 ms-3 border-radius-md"
                                                style="border: 1px solid #2e303b; padding: 6px 0px;">
                                                
                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="mondayDiscount" class="day-checkbox-one" name="day" value="Mon">
                                                    <label for="mondayDiscount" class="day-label-discount cursor-pointer">월</label>
                                                </div>
                                                
                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="tuesdayDiscount" class="day-checkbox-one" name="day" value="Tue">
                                                    <label for="tuesdayDiscount" class="day-label-discount cursor-pointer">화</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="wednesdayDiscount" class="day-checkbox-one" name="day" value="Wed">
                                                    <label for="wednesdayDiscount" class="day-label-discount cursor-pointer">수</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="thursdayDiscount" class="day-checkbox-one" name="day" value="Thu">
                                                    <label for="thursdayDiscount" class="day-label-discount cursor-pointer">목</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="fridayDiscount" class="day-checkbox-one" name="day" value="Fri">
                                                    <label for="fridayDiscount" class="day-label-discount cursor-pointer">금</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="saturdayDiscount" class="day-checkbox-one" name="day" value="Sat">
                                                    <label for="saturdayDiscount" class="day-label-discount cursor-pointer">토</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="sundayDiscount" class="day-checkbox-one" name="day" value="Sun">
                                                    <label for="sundayDiscount" class="day-label-discount cursor-pointer" style="margin-right: 0.25rem;">일</label>
                                                </div>

                                            </div>

                                            <!-- 요일 기간 선택 -->
                                             <div class="d-flex align-items-center gap-1 ms-3" id="select-weekday-bundle">
                                                <input type="checkbox" id="allDiscount" class="day-checkbox d-none style-none" name="allDiscount">
                                                <label for="allDiscount" class="day-label-discount cursor-pointer" onclick="selectDateDiscountModal('allDiscount')">전체</label>

                                                <input type="checkbox" id="weekdayDiscount" class="day-checkbox d-none style-none" name="weekdayDiscount">
                                                <label for="weekdayDiscount" class="day-label-discount cursor-pointer" onclick="selectDateDiscountModal('weekdayDiscount')">일 ~ 목</label>

                                                <input type="checkbox" id="weekendDiscount" class="day-checkbox d-none style-none" name="weekendDiscount">
                                                <label for="weekendDiscount" class="day-label-discount cursor-pointer" onclick="selectDateDiscountModal('weekendDiscount')">금 ~ 토</label>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2 mb-0">일괄 적용</label>
                                        <div class="col d-flex align-items-center px-0 gap-1">
                                            <input class="text-center form-control w-15" 
                                                value="" 
                                                type="text"
                                                id="salePercentAll"
                                                min="0"
                                                max="100"
                                                oninput="syncSalePercent(this)"
                                                onkeydown="blockNegative(event);"
                                                onkeyup="this.value=this.value.replace(/[^-0-9.,]/g,'');">
                                                <span class="d-inline-block">%</span>
                                        </div>
                                    </div>
                                    <div class="caution">
                                        <p class="text-xs fw-bold mb-0">* 신규 입력해주시는 정보만 업데이트 반영되며, 비워두시면 기존 설정된 값으로 유지됩니다.</p>
                                    </div>
                                </div>

                                <!-- 적용 테이블 -->
                                <div class="tableWrap p-0 mt-3 h-auto" style="max-height: 50vh; overflow-y: auto;">
                                    <table class="calendar w-100">
                                        <thead>
                                            <tr>
                                                <th width="w-40">요금제</th>
                                                <th>상시 할인(%)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="calendarBodyDiscountModal">
                                            <?php foreach ($rooms as $roomIdx => $room) : ?>
                                                <tr class="head">
                                                    <th class="d-flex align-items-start justify-content-center gap-1">
                                                        <button type="button" id="toggleButton" class="toggle-btn-modal" data-group-modal="discount<?= $roomIdx; ?>">
                                                            <i id="toggleIcon" class="fa-solid fa-chevron-down"></i>
                                                        </button>
                                                        <span class="d-inline-block w-80">
                                                            <?= $room['room_name']; ?>
                                                        </span>
                                                    </th>
                                                    <td>-</td>
                                                </tr>

                                                <?php foreach ($room['rateplans'] as $rateplanIdx => $rateplan) : ?>
                                                    <tr data-group-modal="discount<?= $roomIdx; ?>"  class="collapsible">
                                                        <td class="text-xs fw-bold">
                                                            <?= $rateplan['rateplan_name'] ?>
                                                        </td>
                                                        <td>
                                                            <input class="text-center salePercentDiscountModal" 
                                                                value=""
                                                                type="text"
                                                                id="salePercentDiscountModal_<?= $roomIdx; ?><?= $rateplanIdx; ?>"
                                                                name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][salePercent]"
                                                                min="0"
                                                                max="100"
                                                                oninput="updateDiscount(this)";
                                                                onkeydown="blockNegative(event);"
                                                                onkeyup="this.value=this.value.replace(/[^-0-9.,]/g,'');">
                                                        </td>
                                                    </tr>    
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                        <button type="button" id="createButton" class="btn bg-gradient-primary" onclick="saveDiscountModification();">일괄 저장하기</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 기간 지정 일괄 수정 -->
        <div class="modal fade" id="batch-modification" tabindex="-1" role="dialog" aria-labelledby="batch-modification" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-m" role="document" style="max-width:50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-6" id="exampleModalLabel">기간 지정 일괄 수정</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-body">
                                <!-- 기간 선택 -->
                                <div class="dateSelectBox">
                                    <div class="form-group row align-items-center">
                                        <label for="dateSelect" class="form-control-label col-sm-2 mb-0">기간 선택</label>
                                        <div class="col d-flex align-items-center gap-1 p-0 flex-wrap">
                                            <div class="dateSelectBox border-radius-md d-flex align-items-center p-2 gap-1" style="border: 1px solid #2e303b;">
                                                <i class="fa-regular fa-calendar" style="color:#2d303b;"></i>
                                                <input id="period-date" type="text" name="period-date" class="datepicker custom text-xs fw-bold text-center outline-none border-0" data-class="my-custom-class" >
                                            </div>
                                            
                                            <!-- 요일 선택 -->
                                            <div id="select-weekday" class="d-flex align-items-center gap-1 ms-3 border-radius-md"
                                                style="border: 1px solid #2e303b; padding: 6px 0px;">
                                                
                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="monday" class="day-checkbox-one" name="day" value="Mon">
                                                    <label for="monday" class="day-label cursor-pointer">월</label>
                                                </div>
                                                
                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="tuesday" class="day-checkbox-one" name="day" value="Tue">
                                                    <label for="tuesday" class="day-label cursor-pointer">화</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="wednesday" class="day-checkbox-one" name="day" value="Wed">
                                                    <label for="wednesday" class="day-label cursor-pointer">수</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="thursday" class="day-checkbox-one" name="day" value="Thu">
                                                    <label for="thursday" class="day-label cursor-pointer">목</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="friday" class="day-checkbox-one" name="day" value="Fri">
                                                    <label for="friday" class="day-label cursor-pointer">금</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="saturday" class="day-checkbox-one" name="day" value="Sat">
                                                    <label for="saturday" class="day-label cursor-pointer">토</label>
                                                </div>

                                                <div class="dayCheckbox">
                                                    <input type="checkbox" id="sunday" class="day-checkbox-one" name="day" value="Sun">
                                                    <label for="sunday" class="day-label cursor-pointer" style="margin-right: 0.25rem;">일</label>
                                                </div>

                                            </div>

                                            <!-- 요일 기간 선택 -->
                                             <div class="d-flex align-items-center gap-1 ms-3">  
                                                <input type="checkbox" id="all" class="day-checkbox d-none style-none" name="all">
                                                <label for="all" class="day-label cursor-pointer" onclick="selectDateModal('all')">전체</label>

                                                <input type="checkbox" id="weekday" class="day-checkbox d-none style-none" name="weekday">
                                                <label for="weekday" class="day-label cursor-pointer" onclick="selectDateModal('weekday')">일 ~ 목</label>

                                                <input type="checkbox" id="weekend" class="day-checkbox d-none style-none" name="weekend">
                                                <label for="weekend" class="day-label cursor-pointer" onclick="selectDateModal('weekend')">금 ~ 토</label>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="caution">
                                        <p class="text-xs fw-bold mb-0">* 신규 입력해주시는 정보만 업데이트 반영되며, 비워두시면 기존 설정된 값으로 유지됩니다.</p>
                                    </div>
                                </div>

                                <!-- 적용 테이블 -->
                                <!-- 탭 추가 -->
                                <div class="w-100 mt-3 p-2 py-3" style="border: 1px solid #dee2e6; border-radius: 10px;">

                                    <!-- tab -->
                                    <div class="nav-wrapper position-relative end-0 w-50 custom">
                                        <ul class="nav nav-pills nav-fill bg-white d-inline-block w-100 modal" role="tablist">
                                            <li class="nav-item d-inline-block" role="presentation">
                                                <a class="nav-link mb-0 px-3 py-1 active" href="#priceTab" role="tab" aria-controls="priceTab" aria-selected="true">
                                                    요금
                                                </a>
                                            </li>
                                            <li class="nav-item d-inline-block" role="presentation">
                                                <a class="nav-link mb-0 px-3 py-1" href="#quantityTab" role="tab" aria-controls="quantityTab" aria-selected="false" tabindex="-1">
                                                    보유 재고
                                                </a>
                                            </li>
                                            <li class="nav-item d-inline-block" role="presentation">
                                                <a class="nav-link mb-0 px-3 py-1" href="#selectTab" role="tab" aria-controls="selectTab" aria-selected="false" tabindex="-1">
                                                    마감 여부
                                                </a>
                                            </li>
                                        </ul>
                                    </div>    
                                    
                                    <hr class="horizontal dark my-3">

                                    <!-- content -->
                                    <div class="tab-content tabChoice p-3" id="nav-tabContent">
                                        
                                        <!-- 요금 -->
                                        <div class="tab-pane fade show active" id="priceTab" role="tabpanel" aria-labelledby="nav-basic-tab">
                                            <div class="tableWrap p-0 h-auto" style="max-height: 50vh; overflow-y: auto;">
                                                <table class="calendar w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>객실 / 요금제</th>
                                                            <th class="w-20">일반 요금</th>
                                                            <th class="w-20">상시 할인(%)</th>
                                                            <th class="w-20">할인가</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="priceBodyModal">
                                                        <?php foreach ($rooms as $roomIdx => $room) : ?>
                                                            <tr class="head">
                                                                <th class="d-flex align-items-start justify-content-center gap-1">
                                                                    <button type="button" id="toggleButton" class="toggle-btn-modal" data-group-modal="roomPrice<?= $roomIdx; ?>">
                                                                        <i id="toggleIcon" class="fa-solid fa-chevron-down"></i>
                                                                    </button>
                                                                    <span class="d-inline-block w-80">
                                                                        <?= $room['room_name']; ?>
                                                                    </span>
                                                                </th>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                            </tr>

                                                            <?php foreach ($room['rateplans'] as $rateplanIdx => $rateplan) : ?>
                                                                <tr data-group-modal="roomPrice<?= $roomIdx; ?>"  class="collapsible">
                                                                    <td class="text-xs fw-bold">
                                                                        <?= $rateplan['rateplan_name'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <input class="text-center priceBasic" 
                                                                            value="" 
                                                                            type="text" 
                                                                            id="priceBasicModal_<?= $roomIdx; ?><?= $rateplanIdx; ?>"
                                                                            name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][priceBasic]"
                                                                            oninput="updateAllModal(this);"
                                                                            onkeyup="this.value=this.value.replace(/[^-0-9,]/g,''); formatPriceInput(this);"
                                                                            onkeydown="priceNegative(event);">
                                                                    </td>
                                                                    <td>
                                                                        <input class="text-center salePercentModal" 
                                                                            value="" 
                                                                            type="text"
                                                                            id="salePercentModal_<?= $roomIdx; ?><?= $rateplanIdx; ?>"
                                                                            name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][salePercent]"
                                                                            min="0"
                                                                            max="100"
                                                                            oninput="updateDiscountPriceModal(this)"
                                                                            onkeydown="blockNegative(event);"
                                                                            onkeyup="this.value=this.value.replace(/[^-0-9.,]/g,'');">
                                                                    </td>
                                                                    <td>
                                                                        <input class="text-center priceSale" 
                                                                            value="" 
                                                                            type="text" 
                                                                            id="priceSaleModal_<?= $roomIdx; ?><?= $rateplanIdx; ?>"
                                                                            name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][priceSale]"
                                                                            oninput="updateDiscountRateModal(this);"
                                                                            onkeyup="this.value=this.value.replace(/[^-0-9,]/g,''); formatPriceInput(this);"
                                                                            onkeydown="priceNegative(event);">
                                                                    </td>
                                                                </tr>    
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>             
                                        </div>

                                        <!-- 보유 재고 -->
                                        <div class="tab-pane fade" id="quantityTab" role="tabpanel" aria-labelledby="nav-basic-tab">
                                            <div class="tableWrap p-0 h-auto" style="max-height: 50vh; overflow-y: auto;">
                                                <table class="calendar w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>객실</th>
                                                            <tH class="w-50">보유 재고</tH>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="quantityBodyModal">
                                                        <?php foreach ($rooms as $roomIdx => $room) : ?>
                                                            <tr class="head">
                                                                <th class="d-flex align-items-start justify-content-center gap-1">
                                                                    <span class="d-inline-block w-80">
                                                                        <?= $room['room_name']; ?>
                                                                    </span>
                                                                </th>
                                                                <td>
                                                                    <input class="w-15 text-end" 
                                                                        value="" 
                                                                        type="text" 
                                                                        name="rooms[<?= $roomIdx; ?>][quantity]" 
                                                                        onkeyup="this.value=this.value.replace(/[^-0-9,]/g,'');"
                                                                        onkeydown="priceNegative(event);">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- 마감 여부 -->
                                        <div class="tab-pane fade" id="selectTab" role="tabpanel" aria-labelledby="nav-basic-tab">
                                            <div class="tableWrap p-0 h-auto" style="max-height: 50vh; overflow-y: auto;">
                                                <table class="calendar w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>객실 / 요금제</th>
                                                            <th class="w-50">마감 여부</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="selectBodyModal">
                                                        <?php foreach ($rooms as $roomIdx => $room) : ?>
                                                            <tr class="head">
                                                                <th class="d-flex align-items-start justify-content-center gap-1">
                                                                    <button type="button" id="toggleButton" class="toggle-btn-modal" data-group-modal="roomDiscount<?= $roomIdx; ?>">
                                                                        <i id="toggleIcon" class="fa-solid fa-chevron-down"></i>
                                                                    </button>
                                                                    <span class="d-inline-block w-80">
                                                                        <?= $room['room_name']; ?>
                                                                    </span>
                                                                </th>
                                                                <td>-</td>
                                                            </tr>

                                                            <?php foreach ($room['rateplans'] as $rateplanIdx => $rateplan) : ?>
                                                                <tr data-group-modal="roomDiscount<?= $roomIdx; ?>"  class="collapsible">
                                                                    <td class="text-xs fw-bold">
                                                                        <?= $rateplan['rateplan_name'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <select class="text-center"
                                                                            name="rooms[<?= $roomIdx; ?>][<?= $rateplanIdx; ?>][isClosed]"
                                                                            oninput="selectStatusModal(this);">
                                                                            <option value="empty" selected>변경 없음</option>
                                                                            <option value="open">판매 중</option>
                                                                            <option value="close">마감</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>    
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-link mr-auto" data-bs-dismiss="modal">취소</button>
                        <button type="button" id="createButton" class="btn bg-gradient-primary" onclick="saveBatchModification()">일괄 저장하기</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/loading.php"; ?>

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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>

    <!-- Kanban scripts -->
    <script src="/assets/manage/js/plugins/dragula/dragula.min.js"></script>
    <script src="/assets/manage/js/plugins/jkanban/jkanban.js"></script>
    <script src="/assets/manage/js/plugins/chartjs.min.js"></script>
    <script src="/assets/manage/js/plugins/threejs.js"></script>
    <script src="/assets/manage/js/plugins/orbit-controls.js"></script>

    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

    <script>
        document.getElementById('loading').style.display = 'flex';

        window.onload = function() {
            document.getElementById('loading').style.display = 'none'; 
        }
    </script>

    <script>
        // 모달 닫았을 때 입력 값 초기화
        document.addEventListener("DOMContentLoaded", () => {
            const modals = document.querySelectorAll(".modal");

            modals.forEach(modal => {
                modal.addEventListener("hidden.bs.modal", function () {

                    const inputs = modal.querySelectorAll(".calendar input, #salePercentAll");
                    const selects = modal.querySelectorAll(".calendar select, #isClosedAll");

                    inputs.forEach(input => {
                        if (input.type === "checkbox" || input.type === "radio") {
                            input.checked = false; 
                        } else {
                            input.value = ""; 
                        }

                        const tdParent = input.closest('td');
                        if (tdParent) tdParent.style.backgroundColor = "";
                    });

                    selects.forEach(select => {
                        select.value = "empty"; 

                        const tdParent = select.closest('td');
                        if (tdParent) tdParent.style.backgroundColor = "";
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const calendarBody = document.getElementById('calendarBody');

            const toggleButtons = calendarBody.querySelectorAll('.toggle-btn');

            toggleButtons.forEach((button, index) => {
                const group = button.getAttribute('data-group');
                const rows = calendarBody.querySelectorAll(`.collapsible[data-group="${group}"]`);

                if (index !== 0) {
                    rows.forEach(row => row.classList.add('cloased'));
                }
            });
        });
    </script>

    <script>
        // 객실 선택
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.getElementsByName('roomName');
            const allRoomCheckbox = document.getElementById('allRoom');
            const roomContainer = document.getElementById('calendarBody');

            const urlParams = new URLSearchParams(window.location.search);
            const selectedRooms = urlParams.has('selectedRoom') ? urlParams.get('selectedRoom') : null;

            if (selectedRooms === null) {
                checkboxes.forEach(checkbox => checkbox.checked = true);
                allRoomCheckbox.checked = true;
                roomContainer.style.display = '';
            } else if (selectedRooms.trim() === "") {
   
                roomContainer.style.display = 'none';
            } else {
                const selectedRoomArray = selectedRooms.split('|');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectedRoomArray.includes(checkbox.id.split('_')[1]);
                });

                allRoomCheckbox.checked = checkboxes.length > 0 && [...checkboxes].every(checkbox => checkbox.checked);

                roomContainer.style.display = ''; 
            }

            // 객실 선택 적용
            document.getElementById('roomApply').addEventListener('click', () => {
                document.getElementById("loading").style.display = 'flex';

                const checkedRooms = [...checkboxes]
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.split('_')[1]);

                const url = new URL(window.location.href);
                url.searchParams.set('selectedRoom', checkedRooms.join('|'));
                window.location.href = url.href;
            });
        });

        // 전체 선택
        function selectAllRoom() {
            const checkboxes = document.getElementsByName('roomName');
            const allRoomChecked = document.getElementById('allRoom').checked;

            checkboxes.forEach(checkbox => checkbox.checked = allRoomChecked);
        }
    </script>
    
    <script>
        // 요금제 선택
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxesRateplan = document.getElementsByName('rateplanName');
            const allRateplanCheckbox = document.getElementById('allRateplan');
            const rateplanContainers = document.querySelectorAll('.collapsible'); 

            const urlParamsRateplan = new URLSearchParams(window.location.search);
            const selectedRateplans = urlParamsRateplan.has('selectedRateplan') ? urlParamsRateplan.get('selectedRateplan') : null;

            if (selectedRateplans === null) {
                checkboxesRateplan.forEach(checkbox => checkbox.checked = true);
                allRateplanCheckbox.checked = true;
                rateplanContainers.forEach(container => container.style.display = ''); 
            } else if (selectedRateplans.trim() === "") {
                rateplanContainers.forEach(container => container.style.display = 'none');
            } else {
                // 특정 요금제 선택
                const selectedRateplanArray = selectedRateplans.split('|');

                checkboxesRateplan.forEach(checkbox => {
                    checkbox.checked = selectedRateplanArray.includes(checkbox.id.split('_')[1]);
                });

                // 전체 선택 상태 업데이트
                allRateplanCheckbox.checked = checkboxesRateplan.length > 0 && [...checkboxesRateplan].every(checkbox => checkbox.checked);

                rateplanContainers.forEach(container => container.style.display = ''); 
            }

            // 요금제 선택 적용
            document.getElementById('rateplanApply').addEventListener('click', () => {
                document.getElementById("loading").style.display = 'flex';

                const checkedRateplans = [...checkboxesRateplan]
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.split('_')[1]);

                const url = new URL(window.location.href);
                url.searchParams.set('selectedRateplan', checkedRateplans.join('|'));
                window.location.href = url.href;
            });
        });

        // 전체 선택
        function selectAllRateplan() {
            const checkboxesRateplan = document.getElementsByName('rateplanName');
            const allRateplanChecked = document.getElementById('allRateplan').checked;

            checkboxesRateplan.forEach(checkbox => checkbox.checked = allRateplanChecked);
        }
    </script>

    <script>
        // 보기 항목 선택
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxesViewItems = document.getElementsByName('viewItemsCheck');
            const allViewItemsCheckbox = document.getElementById('allViewItems');
            const priceContainers = document.querySelectorAll('.price-row'); 
            const selectContainers = document.querySelectorAll('.select-row');

            const urlParamsViewItems = new URLSearchParams(window.location.search);
            const selectedViewItems = urlParamsViewItems.has('viewItems') ? urlParamsViewItems.get('viewItems') : null;
            
            // 재고만
            if (selectedViewItems && selectedViewItems.trim() === "quantity") {
                priceContainers.forEach(container => container.style.display = 'none');
                selectContainers.forEach(container => container.style.display = 'none');
            } 

            // 요금만
            if (selectedViewItems && selectedViewItems.trim().includes("price")) {
                selectContainers.forEach(container => container.style.display = 'none');
            }

            // 마감 여부만
            if (selectedViewItems && selectedViewItems.trim().includes("select")) {
                priceContainers.forEach(container => container.style.display = 'none');
            }

            // 전부 다
            if (selectedViewItems && selectedViewItems.trim().includes("price") && selectedViewItems.trim().includes("select")) {
                priceContainers.forEach(container => container.style.display = '');
                selectContainers.forEach(container => container.style.display = '');
            }

            if (selectedViewItems === null) {
                checkboxesViewItems.forEach(checkbox => checkbox.checked = true);
                allViewItemsCheckbox.checked = true;

                priceContainers.forEach(container => container.style.display = '');
                selectContainers.forEach(container => container.style.display = '');
            } else if (selectedViewItems.trim() === "") {
                priceContainers.forEach(container => container.style.display = 'none');
                selectContainers.forEach(container => container.style.display = 'none');
            } else {
                // 특정 요금제 선택
                const selectedViewItemsArray = selectedViewItems.split('|');

                checkboxesViewItems.forEach(checkbox => {
                    checkbox.checked = selectedViewItemsArray.includes(checkbox.id.split('_')[1]);
                });

                // 전체 선택 상태 업데이트
                allViewItemsCheckbox.checked = checkboxesViewItems.length > 0 && [...checkboxesViewItems].every(checkbox => checkbox.checked);
            }

            // 요금제 선택 적용
            document.getElementById('viewItemsApply').addEventListener('click', () => {
                document.getElementById("loading").style.display = 'flex';
                
                const checkedViewItems = [...checkboxesViewItems]
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.split('_')[1]);

                const url = new URL(window.location.href);
                url.searchParams.set('viewItems', checkedViewItems.join('|'));
                window.location.href = url.href;
            });
        });

        // 전체 선택
        function selectAllViewItems() {
            const checkboxesViewItems = document.getElementsByName('viewItemsCheck');
            const allViewItemsChecked = document.getElementById('allViewItems').checked;

            checkboxesViewItems.forEach(checkbox => checkbox.checked = allViewItemsChecked);
        }
    </script>

    <script>
        function priceNegative(event) {
            const invalidKeys = ['-', 'e'];

            // 음수(-)나 'e' 입력 방지
            if (invalidKeys.includes(event.key)) {
                event.preventDefault();
                alert('양수만 입력 가능합니다.');
                return false;
            }
        }
    </script>

    <script>
        // 드롭다운 내부 클릭 시 닫힘 방지
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".dropdown-menu").forEach(menu => {
                menu.addEventListener("click", function(event) {
                    event.stopPropagation();
                });
            });
        });
    </script>

    <script>
        function checkStockBeforeClick(input, roomIdx, date) {
            const stockInput = document.querySelector(`input[name="rooms[${roomIdx}][quantity][${date}]"]`);

            if (stockInput && stockInput.value.trim() === '') {
                alert("보유 재고를 먼저 입력해 주세요.");
                stockInput.focus();
            }
        }
    </script>
        
    <script>
        // 요금 접기 이벤트
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.getAttribute('data-group');
                const rows = document.querySelectorAll(`.collapsible[data-group="${group}"]`);
                const icon = this.querySelector('i');

                rows.forEach(row => row.classList.toggle('cloased'));
                this.classList.toggle('active');
            });
        });

        // 모달 요금 접기 이벤트
        document.querySelectorAll('.toggle-btn-modal').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.getAttribute('data-group-modal');
                const rows = document.querySelectorAll(`.collapsible[data-group-modal="${group}"]`);
                const icon = this.querySelector('i');

                rows.forEach(row => row.classList.toggle('cloased'));
                this.classList.toggle('active');
            });
        });

        // 마감 여부 활성화
        function selectStatus(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 3) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');
            const date = nameParts[2].replace(/\[|\]/g, '');

            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceBasic]"]`);
            const isClosedSelect = document.querySelector(`select[name*="[${roomIdx}][${rateplanIdx}][${date}][isClosed]"]`);
            const tdParent = isClosedSelect ? isClosedSelect.closest('td') : null;

            // select box 배경색 변경
            if (isClosedSelect) {
                tdParent.style.backgroundColor = "#ff005120";
            } else {
                tdParent.style.backgroundColor = "";
            }

            if (isClosedSelect) {
                isClosedSelect.style.color = "#000";
            }
            
            if (priceBasicInput && isClosedSelect) {
                if (priceBasicInput.value === "") {
                    isClosedSelect.disabled = true; 
                    tdParent.style.backgroundColor = "#2e303b08";

                    const emptyOption = isClosedSelect.querySelector('option[value="empty"]');
                    if (emptyOption) {
                        emptyOption.selected = true;
                    }
                } else {
                    isClosedSelect.disabled = false;    
                }
            }
        }

        // 보유 재고 
        function inputQuantity(input) {
            const quantityInput = input;
            const quantityTdParent = quantityInput ? quantityInput.closest('td') : null;

            if (quantityTdParent) {
                quantityTdParent.style.backgroundColor = "#ff005120";
            } else {
                console.error('Parent td not found');
            }
        }

        // 입력 시 숫자 포맷팅
        function formatPriceInput(input) {
            let inputValue = input.value;
            let numberValue = inputValue.replace(/,/g, '');

            // 천 단위 콤마 추가
            numberValue = numberValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            input.value = numberValue;
        }

        // value 콤마 제거
        function removeCommas(value) {
            return value.replace(/,/g, '');
        }

        // 할인가 변동
        function updateDiscountPrice(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 3) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');
            const date = nameParts[2].replace(/\[|\]/g, '');

            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceBasic]"]`);
            const priceSaleInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceSale]"]`);
            const salePercentInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][salePercent]"]`);

            const priceBasic = parseFloat(removeCommas(priceBasicInput.value)) || 0;
            const salePercent = parseFloat(removeCommas(salePercentInput.value)) || 0;
            const priceSale = parseFloat(removeCommas(priceSaleInput.value)) || 0;

            if (priceBasicInput || priceSaleInput || salePercentInput) {
                // 수정된 필드가 priceBasic일 때
                if (input.name.includes('priceBasic')) {
                    const discount = Math.round(priceBasic * (salePercent / 100));
                    const discountPrice = priceBasic - discount;

                    priceSaleInput.value = discountPrice >= 0 ? discountPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "";
                    salePercentInput.value = priceBasic > 0 ? ((priceBasic - discountPrice) / priceBasic * 100).toFixed(2) : "";

                    // 수정된 날짜에만 반영
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceBasic]`] = removeCommas(priceBasicInput.value);
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][salePercent]`] = removeCommas(salePercentInput.value);
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceSale]`] = removeCommas(priceSaleInput.value);

                    const priceBasicTd = priceBasicInput.closest('td');
                    const priceSaleTd = priceSaleInput.closest('td');

                    if (priceBasic.value > 0) {
                        priceBasicTd.style.backgroundColor = "";
                        priceSaleTd.style.backgroundColor = "";
                    } else {
                        priceBasicTd.style.backgroundColor = "#ff005120";
                        priceSaleTd.style.backgroundColor = "#ff005120";
                    }
                }

                // 수정된 필드가 salePercent일 때
                if (input.name.includes('salePercent')) {
                    const discount = Math.round(priceBasic * (salePercent / 100));
                    const discountPrice = priceBasic - discount;

                    priceSaleInput.value = discountPrice >= 0 ? discountPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "";

                    // 수정된 날짜에만 반영
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceBasic]`] = removeCommas(priceBasicInput.value);
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][salePercent]`] = removeCommas(salePercentInput.value);
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceSale]`] = removeCommas(priceSaleInput.value);

                    const salePercentTd = salePercentInput.closest('td');
                    const priceSaleTd = priceSaleInput.closest('td');

                    if (salePercent.value > 0) {
                        salePercentTd.style.backgroundColor = "";
                        priceSaleTd.style.backgroundColor = "";
                    } else {
                        salePercentTd.style.backgroundColor = "#ff005120";
                        priceSaleTd.style.backgroundColor = "#ff005120";
                    }
                }

                // 수정된 필드가 priceSale일 때
                if (input.name.includes('priceSale')) {
                    const discountPercent = ((priceBasic - priceSale) / priceBasic) * 100;
                    salePercentInput.value = discountPercent.toFixed(2);

                    const calculatedPriceBasic = priceSale > 0 ? priceSale / (1 - (salePercent / 100)) : 0;
                    priceBasicInput.value = calculatedPriceBasic > 0 ? calculatedPriceBasic.toString() : "";

                    // 수정된 날짜에만 반영
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceBasic]`] = removeCommas(priceBasicInput.value);
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][salePercent]`] = removeCommas(salePercentInput.value);
                    modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceSale]`] = removeCommas(priceSaleInput.value);
                }
            }
        }

        // 할인율 변동
        function updateDiscountRate(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 3) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');
            const date = nameParts[2].replace(/\[|\]/g, '');

            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceBasic]"]`);
            const priceSaleInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceSale]"]`);
            const salePercentInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][salePercent]"]`);

            if (priceBasicInput && priceSaleInput && salePercentInput) {
                let priceBasic = parseFloat(removeCommas(priceBasicInput.value)) || 0;
                let priceSale = parseFloat(removeCommas(priceSaleInput.value)) || 0;

                if (priceSale > priceBasic) {
                    alert('할인율은 최소 0% 이상으로 설정되어야 합니다.');
                    priceSale = priceBasic;

                    priceSaleInput.value = priceBasic.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                let discountPercent = ((priceBasic - priceSale) / priceBasic) * 100;

                if (isNaN(discountPercent)) {
                    discountPercent = 0;
                }

                if (discountPercent < 0) {
                    discountPercent = 0;
                    alert('할인율은 최소 0% 이상으로 설정되어야 합니다.');
                }

                // 값 할당 (중복 방지)
                salePercentInput.value = discountPercent.toFixed(2);

                modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][priceBasic]`] = removeCommas(priceBasicInput.value);
                modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}][salePercent]`] = salePercentInput.value;
                modifiedData[`rooms[${roomIdx}][${rateplanIdx}][${date}x][priceSale]`] = removeCommas(priceSaleInput.value);

                const salePercentTd = salePercentInput.closest('td');
                const priceSaleTd = priceSaleInput.closest('td');

                if (priceSale.value > 0) {
                    salePercentTd.style.backgroundColor = "";
                    priceSaleTd.style.backgroundColor = "";
                } else {
                    salePercentTd.style.backgroundColor = "#ff005120"; 
                    priceSaleTd.style.backgroundColor = "#ff005120";
                }
            }
        }

        // 할인율 자릿수
        function blockNegative(event) {
            const invalidKeys = ['-', 'e'];

            // 음수(-)나 'e' 입력 방지
            if (invalidKeys.includes(event.key)) {
                event.preventDefault();
                alert('양수만 입력 가능합니다.');
                return false;
            }

            let inputValue = event.target.value;

            // 100% 초과 입력 방지
            setTimeout(() => {
                if (parseFloat(event.target.value) > 99.99) {
                    alert('할인율은 최대 100% 이하로 설정되어야 합니다.');
                }
            }, 0);
        }
        
        // 일반 요금 입력 시 할인율, 할인가, 마감 여부 업데이트
        function updateAll(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 3) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');
            const date = nameParts[2].replace(/\[|\]/g, '');

            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceBasic]"]`);
            const priceSaleInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][priceSale]"]`);
            const salePercentInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][${date}][salePercent]"]`);

            // priceBasic이 빈값일 때 sale과 percent도 빈값으로 초기화
            if (priceBasicInput.value.trim() === "") {
                priceSaleInput.value = "";
                salePercentInput.value = "";
            }

            updateDiscountPrice(input);
            updateDiscountRate(input);
            selectStatus(input);
        }

        document.querySelectorAll('.salePercent').forEach(input => {
            input.addEventListener('input', function() {
                updateDiscountPrice(this);
            })
        });

        document.querySelectorAll('.priceSale').forEach(input => {
            input.addEventListener('input', function() {
                updateDiscountRate(this);
            })
        });

    </script>

    <script>
        // 오늘 날짜 class 제거
        function removeTodayClass(instance) {
            setTimeout(() => {
                const todayElements = instance.calendarContainer.querySelectorAll('.today');
                todayElements.forEach(element => element.classList.remove('today'));
            }, 10);
        }

        // 날짜 변경 이벤트
        var dateSelector = document.querySelector('#startDate');

        const params = new URLSearchParams(window.location.search);
        const startDateParam = params.get('startDate');

        const initialDate = startDateParam ? new Date(startDateParam) : new Date(); 

        window.flatpickrInstance = flatpickr(dateSelector, {
            mode: 'single',
            static: true,
            monthSelectorType: 'static',
            dateFormat: "Y-m-d",
            defaultDate: initialDate,
            prevArrow: '<i class="fa-solid fa-chevron-left"></i>',
            nextArrow: '<i class="fa-solid fa-chevron-right"></i>',
            locale: 'ko',
            onChange: (selectedDates, dateStr, instance) => {
                updateInputAndURL(dateStr);

                removeTodayClass(instance);

                location.reload();
            },
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                dayElem.classList.remove('today');
            }
        });

        // 날짜를 변경하는 함수
        function changeDate(offset) {
            var selectedDate = window.flatpickrInstance.selectedDates.length > 0 ?
                new Date(window.flatpickrInstance.selectedDates[0]) :
                new Date();

            selectedDate.setDate(selectedDate.getDate() + offset);

            window.flatpickrInstance.setDate(selectedDate, true);

            location.reload();
        }

        // URL 파라미터 업데이트
        function updateInputAndURL(newDate) {
            if (!newDate) return;

            dateSelector.value = newDate;

            const params = new URLSearchParams(window.location.search);
            params.set("startDate", newDate);
            window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
        }

        // 화살표 버튼 클릭 이벤트 추가
        document.getElementById("prev-date").addEventListener("click", function() {
            changeDate(-15);
        });
        document.getElementById("next-date").addEventListener("click", function() {
            changeDate(15);
        });
    </script>

    <script>
        const originalData = {};
        const modifiedData = {};

        // 초기 값 설정
        document.querySelectorAll('#calendarBody input, #calendarBody select').forEach(input => {
            const value = input.type === 'checkbox' ? input.checked : input.value;
            originalData[input.name] = value === undefined ? '' : value;
        });

        // 값 변경 시 추적
        document.querySelectorAll('#calendarBody input, #calendarBody select').forEach(input => {
            input.addEventListener('change', () => {
                let newValue = input.type === 'checkbox' ? input.checked : input.value;

                // 콤마가 포함된 값을 숫자로 변환 (input[type="text"] 등에서 사용)
                if (typeof newValue === 'string' && newValue.includes(',')) {
                    newValue = Number(newValue.replace(/,/g, ""));
                }

                // 숫자가 아닌 값일 경우 기본값을 0으로 설정
                newValue = newValue === '' ? '' : newValue;

                // 값이 변경되었는지 확인
                if ((originalData[input.name] === undefined ? '' : originalData[input.name]) != newValue) {
                    modifiedData[input.name] = newValue;
                } else {
                    delete modifiedData[input.name];
                }
            });
        });

        // save
        async function saveInventory() {
            if (Object.keys(modifiedData).length === 0) {
                alert('저장이 완료되었습니다.');
                return;
            }

            let requestData = {
                partnerIdx: <?= $selectedPartnerIdx; ?>,
                rooms: {}
            };

            Object.entries(modifiedData).forEach(([key, value]) => {
                const keys = key.match(/\[(.*?)\]/g)?.map(k => k.replace(/\[|\]/g, ""));

                if (keys && keys.length) {
                    const roomId = keys[0];
                    const quantityOrRateplan = keys[1];
                    const thirdKey = keys[2] || null;
                    const field = keys[3] || null;

                    const numericValue = (typeof value === 'string' && value.includes(',')) ? 
                        Number(value.replace(/,/g, "")) : value;

                    if (!field && quantityOrRateplan === 'quantity') {
                        requestData.rooms[roomId] = requestData.rooms[roomId] || {
                            quantity: {},
                            rateplans: {}
                        };
                        requestData.rooms[roomId].quantity[thirdKey] = numericValue;
                    }

                    if (thirdKey && field) {
                        requestData.rooms[roomId] = requestData.rooms[roomId] || {
                            quantity: {},
                            rateplans: {}
                        };
                        requestData.rooms[roomId].rateplans[quantityOrRateplan] = requestData.rooms[roomId].rateplans[quantityOrRateplan] || {
                            dates: {}
                        };
                        requestData.rooms[roomId].rateplans[quantityOrRateplan].dates[thirdKey] = requestData.rooms[roomId].rateplans[quantityOrRateplan].dates[thirdKey] || {};
                        if (field === "isClosed") {
                            requestData.rooms[roomId].rateplans[quantityOrRateplan].dates[thirdKey][field] = (value === 'close');
                        } else {
                            if (isNaN(value)) {
                                requestData.rooms[roomId].rateplans[quantityOrRateplan].dates[thirdKey][field] = value;
                            } else {
                                requestData.rooms[roomId].rateplans[quantityOrRateplan].dates[thirdKey][field] = numericValue;
                            }
                        }
                    }
                }
            });

            try {
                loading.style.display = 'flex';

                let hasLowPrice = false;

                Object.values(requestData.rooms).forEach(room => {
                    Object.values(room.rateplans).forEach(rateplan => {
                        if (rateplan.dates) {
                            Object.values(rateplan.dates).forEach(dateData => {
                                if (dateData.priceBasic < 10000 || dateData.priceSale < 10000) {
                                    hasLowPrice = true;
                                }
                            });
                        }
                    });
                });

                if (hasLowPrice) {
                    const priceConfirm = confirm('1만원 이하로 저장되는 요금이 있습니다. 저장하시겠습니까?');
                    if (!priceConfirm) {
                        loading.style.display = 'none';
                        return;
                    }
                }

                const response = await fetch('/api/partner/rates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestData),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('저장이 완료되었습니다.');
                    loading.style.display = 'flex';
                    location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            } finally {
                loading.style.display = 'none'; 
            }
        }
    </script>

    <script>
        // 선택 날짜 마감 여부 수정
        // 날짜 선택
        const dateSelects = document.querySelectorAll('.date-select');
        const selectDateBtn = document.getElementById("selectDateBtn");
        const modal = new bootstrap.Modal(document.getElementById('isClosed-modification'));

        let selectedDatesArray = []; // 체크된 날짜 배열
        let dateInfoArray = []; // 체크된 날짜 info 배열

        function updateSelectButtonState() {
            if (selectedDatesArray.length === 0) {
                selectDateBtn.style.opacity = "0.5";
            } else {
                selectDateBtn.style.opacity = "1";
            }
        }

        // 날짜 개별 선택
        dateSelects.forEach(dateSelect => {
            dateSelect.addEventListener('change', function(event) {
                const th = this.parentElement;
                
                const selectedDate = this.id.replace("date_", ""); 
                const dateInfo = this.getAttribute("data-date-info"); 

                // 체크 상태에 따라 배경색 변경
                if (this.checked) {
                    if (!selectedDatesArray.includes(selectedDate)) {
                        selectedDatesArray.push(selectedDate);
                    }
                    if (!dateInfoArray.includes(dateInfo)) {
                        dateInfoArray.push(dateInfo);
                    }

                    th.style.backgroundColor = 'rgba(244, 239, 255, 1)';
                    th.style.color = "#2d303b";
                } else {
                    // 배열에서 제거
                    selectedDatesArray = selectedDatesArray.filter(date => date !== selectedDate);
                    dateInfoArray = dateInfoArray.filter(info => info !== dateInfo);

                    th.style.backgroundColor = '';
                    th.style.color = "#fff";
                }

                // 선택 날짜에 노출
                const selectDateWrap = document.querySelector('.selectDateWrap');

                if (selectDateWrap) {

                    selectDateWrap.innerHTML = ''; 

                    dateInfoArray.forEach((dateInfo, index) => {
                        const span = document.createElement('span'); 
                        span.classList.add('selected-date', 'pe-2', 'fw-bold', 'text-sm', 'd-inline-block');

                        // 토요일
                        if (dateInfo.includes('토')) {
                            span.classList.add('saturday');
                        }
                        
                        // 일요일
                        if (dateInfo.includes('일')) {
                            span.classList.add('sunday');
                        }

                        span.textContent = index < dateInfoArray.length - 1 ? dateInfo + ',' : dateInfo;

                        selectDateWrap.appendChild(span); 
                    });
                }

                updateSelectButtonState();
            });
        });

        selectDateBtn.addEventListener('click', function(event) {
            if (selectedDatesArray.length === 0) {
                alert("날짜를 먼저 선택해 주세요.");
            } else {
                modal.show();
            }
        }); 

        updateSelectButtonState();

        // 이전 날짜 클릭 시 alert 띄우고 체크 해제
        const pastDate = document.querySelectorAll('.past-date');

        pastDate.forEach(past => {
            past.addEventListener('click', function(event) {
                alert("이전 날짜는 선택할 수 없습니다.");
                this.checked = false;
            });
        });

        // 마감 여부 활성화
        function selectDateStatusModal(input) {
            const inputNameSelectDate = input.getAttribute('name'); 

            const namePartsSelectDate = inputNameSelectDate.match(/\[(\d+)\]/g);
            if (!namePartsSelectDate || namePartsSelectDate.length < 1) return;

            const roomIdx = namePartsSelectDate[0].replace(/\[|\]/g, '');
            
            const isClosedSelectDatetModal = document.querySelector(`select[name='rooms[${roomIdx}][isClosed]']`);
            const tdParentSelectDate = isClosedSelectDatetModal ? isClosedSelectDatetModal.closest('td') : null;

            if (isClosedSelectDatetModal && tdParentSelectDate) {
                tdParentSelectDate.style.backgroundColor = "#ff005120";
            } else if (tdParentSelectDate) {
                tdParentSelectDate.style.backgroundColor = "";
            }
        }

        // 마감 여부 저장
        const modifiedDataSelectModal = {};

        // 마감 여부 일괄 적용
        document.getElementById('isClosedAll').addEventListener('input', function() {
            let selectedValue = this.value; 
            
            document.querySelectorAll(".selectDateModalIsClosed").forEach(function(select) {
                select.value = selectedValue;
                select.dispatchEvent(new Event('input')); 

                // 데이터 반영
                modifiedDataSelectModal[select.name] = selectedValue;
            });
        });

        document.querySelectorAll('#selectDateBodyModal select').forEach(input => {
            input.addEventListener('change', () => {
                let newValue = input.value;

                if (typeof newValue === 'string' && newValue.includes(',')) {
                    newValue = Number(newValue.replace(/,/g, ""));
                }

                newValue = newValue === '' ? '' : newValue;

                modifiedDataSelectModal[input.name] = newValue;
            });
        });

        // 일괄 저장하기
        async function saveSelectDateModification() {
            if (Object.keys(modifiedDataSelectModal).length === 0) {
                return;
            }

            let requestDataSelectDate = {
                partnerIdx: <?= $selectedPartnerIdx; ?>,
                rooms: {},
                dates: [],
            };

            if (selectedDatesArray.length > 0) {
                requestDataSelectDate.dates = selectedDatesArray.map(date => {
                    return new Date(date).toLocaleDateString('en-CA');
                });
            }

            Object.entries(modifiedDataSelectModal).forEach(([key, value]) => {
                const keys = key.match(/\[(.*?)\]/g)?.map(k => k.replace(/\[|\]/g, ""));

                if (keys && keys.length) {
                    const roomId = keys[0];
                    const field = keys[1] || null;

                    let numericValue = (typeof value === 'string' && value.includes(',')) ? 
                        Number(value.replace(/,/g, "")) : value;

                    // 빈 값이나 잘못된 값은 처리하지 않도록
                    if (numericValue === "" || numericValue === undefined || numericValue === null || isNaN(numericValue)) {
                        numericValue = null;
                    }

                    if (field === 'isClosed') {
                        requestDataSelectDate.rooms[roomId] = (value === 'close');
                    }
                }
            });
            
            try {
                loading.style.display = 'flex'; 

                const response = await fetch('/api/partner/rates-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestDataSelectDate),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('저장이 완료되었습니다.');
                    loading.style.display = 'flex'; 
                    location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        }
        
    </script>

    <script>
        // 상시 할인(%) 일괄 수정 모달 이벤트
        // 할인 일괄 수정 -> 일괄 적용
        function syncSalePercent(element) {
            let value = element.value;
        
            document.querySelectorAll(".salePercentDiscountModal").forEach(input => {
                if (!input.getAttribute('name') || input.getAttribute('name') === "") return;

                input.value = value;

                let newValue = input.value;

                newValue = newValue === "" || newValue;

                modifiedDataDiscountModal[input.name] = newValue;
            });
        }

        // 상시 할인(%) 기간 선택 달력
        var discountModalSelector = document.querySelector('#discount-date');
        var selectedDiscount = {};

        const urlParamsDiscount = new URLSearchParams(window.location.search);

        var startDate = new Date();
        var endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 30); 

        var previousDay = new Date(startDate);
        previousDay.setDate(startDate.getDate() - 1); 
    
        window.flatpickrInstanceModalDiscount = flatpickr(discountModalSelector, {
            mode: 'range', 
            static: true,  
            monthSelectorType: 'static',
            dateFormat: "Y-m-d", 
            defaultDate: [
                startDate,
                endDate
            ],
            locale: 'ko',
            disable: [
                {
                    from: '1999-01-01',
                    to: previousDay
                }
            ],
            onReady: function(selectedDates, dateStr, instance) {
                instance.element.value = dateStr.replace('to', '~'); 

                const customClass = instance.element.getAttribute('data-class');
                instance.calendarContainer.classList.add(customClass || 'default-class');

                if (selectedDates.length === 2) {
                    selectedDiscount.startDate = selectedDates[0]; 
                    selectedDiscount.endDate = selectedDates[1]; 
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                instance.element.value = dateStr.replace('to', '~');
                
                removeTodayClass(instance);

                if (selectedDates.length === 2) {
                    selectedDiscount.startDate = selectedDates[0]; 
                    selectedDiscount.endDate = selectedDates[1];  
                }
            },
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                dayElem.classList.remove('today');
            }
        });

        // 기존에 선택된 요일들을 저장
        let DiscountSelectedDays = [];

        // 상시 할인(%) 일괄 수정 요일 묶음 선택
        function selectDateDiscountModal(option) {
            const checkboxes = document.querySelectorAll('#select-weekday-discount input[type="checkbox"]');
            const checklabels = document.querySelectorAll('.day-label-discount');
            const selectedLabel = document.querySelector(`#select-weekday-bundle label[for="${option}"]`);
            const isActive = selectedLabel.classList.contains('active');

            // 모든 active 제거
            checklabels.forEach(label => label.classList.remove('active'));

            if (isActive) {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                selectedDays = []; 
                return;
            }

            if (selectedLabel) selectedLabel.classList.add('active');

            // 체크박스 초기화 후, 선택된 요일 체크
            checkboxes.forEach(checkbox => checkbox.checked = false);

            const days = {
                allDiscount: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                weekdayDiscount: ['Mon', 'Tue', 'Wed', 'Thu', 'Sun'],
                weekendDiscount: ['Fri', 'Sat']
            };

            // 선택된 요일
            const selectedOptionDays = days[option] || [];
            DiscountSelectedDays = selectedOptionDays; 

            checkboxes.forEach(checkbox => {
                if (selectedOptionDays.includes(checkbox.value)) {
                    checkbox.checked = true;
                }
            });
        }

        // 디폴트 선택 값 (전체)
        document.addEventListener('DOMContentLoaded', () => {
            selectDateDiscountModal('allDiscount');
        });

        // 개별 체크박스 클릭 시, 선택된 상태를 반영
        document.querySelectorAll('#select-weekday-discount input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                const checkedValues = [...document.querySelectorAll('#select-weekday-discount input[type="checkbox"]:checked')]
                    .map(chk => chk.value);
                
                // 클릭한 체크박스의 값을 확인하여 배열에서 추가 또는 제거
                const dayValue = checkbox.value;

                if (checkbox.checked) {
                    // 배열에 없으면 추가
                    if (!DiscountSelectedDays.includes(dayValue)) {
                        DiscountSelectedDays.push(dayValue);
                    }
                } else {
                    // 배열에서 있으면 제거
                    const index = DiscountSelectedDays.indexOf(dayValue);
                    if (index !== -1) {
                        DiscountSelectedDays.splice(index, 1);
                    }
                }
            });
        });

        // 상시 할인(%) 적용
        function updateDiscount(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 2) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');

            const salePercentInputs = document.querySelectorAll(`input[name*="[${roomIdx}][${rateplanIdx}][salePercent]"]`);
            const salePercentInput = Array.from(salePercentInputs).find(input => input.id === "salePercentDiscountModal");

            const salePercent = parseFloat(removeCommas(salePercentInput.value)) || 0;

            if (salePercentInput) {
                if (input.name.includes('salePercent')) {
                    const discount = Math.round(priceBasic * (salePercent / 100));

                    modifiedDataDiscountModal[`rooms[${roomIdx}][${rateplanIdx}][salePercent]`] = removeCommas(salePercentInput.value);
                }
            }
        }

        const modifiedDataDiscountModal = {};

        document.querySelectorAll('#calendarBodyDiscountModal input').forEach(input => {
            input.addEventListener('change', () => {
                let newValue = input.value;

                if (typeof newValue === 'string' && newValue.includes(',')) {
                    newValue = Number(newValue.replace(/,/g, ""));
                }

                newValue = newValue === '' ? '' : newValue;

                modifiedDataDiscountModal[input.name] = newValue;
            });
        });

        // 일괄 저장하기
        async function saveDiscountModification() {
            if (Object.keys(modifiedDataDiscountModal).length === 0) {
                return;
            }

            let requestDataDiscount = {
                partnerIdx: <?= $selectedPartnerIdx; ?>,
                rooms: {},
                dates: [],
                dateType: 'range',
                dayOfWeek: []
            };

            if (selectedDiscount.startDate && selectedDiscount.endDate) {
                let startDate = new Date(selectedDiscount.startDate);
                let endDate = new Date(selectedDiscount.endDate);

                requestDataDiscount.dates = [
                    startDate.toLocaleDateString('en-CA'), 
                    endDate.toLocaleDateString('en-CA')    
                ];
            }

            const checkedDays = document.querySelectorAll('#select-weekday-discount input[type="checkbox"]:checked');
            requestDataDiscount.dayOfWeek = Array.from(checkedDays).map(checkbox => checkbox.value);

            Object.entries(modifiedDataDiscountModal).forEach(([key, value]) => {
                const keys = key.match(/\[(.*?)\]/g)?.map(k => k.replace(/\[|\]/g, ""));

                if (keys && keys.length) {
                    const roomId = keys[0];
                    const quantityOrRateplan = keys[1];
                    const field = keys[2] || null;

                    let numericValue = (typeof value === 'string' && value.includes(',')) ? 
                        Number(value.replace(/,/g, "")) : value;

                    // 빈 값이나 잘못된 값은 처리하지 않도록
                    if (numericValue === "" || numericValue === undefined || numericValue === null || isNaN(numericValue)) {
                        numericValue = null;
                    }

                    // 다른 필드 처리
                    if (field) {
                        requestDataDiscount.rooms[roomId] = requestDataDiscount.rooms[roomId] || {
                            rateplans: {}
                        };
                        requestDataDiscount.rooms[roomId].rateplans[quantityOrRateplan] = requestDataDiscount.rooms[roomId].rateplans[quantityOrRateplan] || {};

                        if (isNaN(value)) {
                            requestDataDiscount.rooms[roomId].rateplans[quantityOrRateplan][field] = value;
                        } else {
                            requestDataDiscount.rooms[roomId].rateplans[quantityOrRateplan][field] = numericValue;
                        }
                    }
                }
            });

            try {
                if (!requestDataDiscount.dayOfWeek || requestDataDiscount.dayOfWeek.length === 0) {
                    alert("월 ~ 일 중 해당하는 요일을 선택해 주세요.");
                    return;
                }

                loading.style.display = 'flex'; 
                
                const response = await fetch('/api/partner/rates-discount', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestDataDiscount),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('저장이 완료되었습니다.');
                    loading.style.display = 'flex'; 
                    location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        }
    </script>

    <script>
        // 기간 지정 일괄 수정 모달 이벤트 
        // 기간 선택 달력
        var periodModalSelector = document.querySelector('#period-date');
        var selectedPeriod = {};

        const urlParams = new URLSearchParams(window.location.search);
        const queryStartDate = urlParams.get('startDate');

        var startDate = new Date();
        var endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 30); 

        var previousDay = new Date(startDate);
        previousDay.setDate(startDate.getDate() - 1); 
    
        window.flatpickrInstanceModal = flatpickr(periodModalSelector, {
            mode: 'range', 
            static: true,  
            monthSelectorType: 'static',
            dateFormat: "Y-m-d", 
            defaultDate: [
                startDate,
                endDate
            ],
            locale: 'ko',
            disable: [
                {
                    from: '1999-01-01',
                    to: previousDay
                }
            ],
            onReady: function(selectedDates, dateStr, instance) {
                instance.element.value = dateStr.replace('to', '~'); 

                const customClass = instance.element.getAttribute('data-class');
                instance.calendarContainer.classList.add(customClass || 'default-class');

                if (selectedDates.length === 2) {
                    selectedPeriod.startDate = selectedDates[0]; 
                    selectedPeriod.endDate = selectedDates[1]; 
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                instance.element.value = dateStr.replace('to', '~');
                
                removeTodayClass(instance);

                if (selectedDates.length === 2) {
                    selectedPeriod.startDate = selectedDates[0]; 
                    selectedPeriod.endDate = selectedDates[1];  
                }
            },
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                dayElem.classList.remove('today');
            }
        });

        // 기존에 선택된 요일들을 저장
        let selectedDays = [];

        // 요일 묶음 선택
        function selectDateModal(option) {
            const checkboxes = document.querySelectorAll('#select-weekday input[type="checkbox"]');
            const checklabels = document.querySelectorAll('.day-label');
            const selectedLabel = document.querySelector(`label[for="${option}"]`);
            const isActive = selectedLabel.classList.contains('active');

            // 모든 active 제거
            checklabels.forEach(label => label.classList.remove('active'));

            if (isActive) {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                selectedDays = []; 
                return;
            }

            if (selectedLabel) selectedLabel.classList.add('active');

            // 체크박스 초기화 후, 선택된 요일 체크
            checkboxes.forEach(checkbox => checkbox.checked = false);

            const days = {
                all: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                weekday: ['Mon', 'Tue', 'Wed', 'Thu', 'Sun'],
                weekend: ['Fri', 'Sat']
            };

            // 선택된 요일
            const selectedOptionDays = days[option] || [];
            selectedDays = selectedOptionDays; 

            checkboxes.forEach(checkbox => {
                if (selectedOptionDays.includes(checkbox.value)) {
                    checkbox.checked = true;
                }
            });
        }

        // 디폴트 선택 값 (전체)
        document.addEventListener('DOMContentLoaded', () => {
            selectDateModal('all');
        });

        // 개별 체크박스 클릭 시, 선택된 상태를 반영
        document.querySelectorAll('#select-weekday input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                const checkedValues = [...document.querySelectorAll('#select-weekday input[type="checkbox"]:checked')]
                    .map(chk => chk.value);
                
                // 클릭한 체크박스의 값을 확인하여 배열에서 추가 또는 제거
                const dayValue = checkbox.value;

                if (checkbox.checked) {
                    // 배열에 없으면 추가
                    if (!selectedDays.includes(dayValue)) {
                        selectedDays.push(dayValue);
                    }
                } else {
                    // 배열에서 있으면 제거
                    const index = selectedDays.indexOf(dayValue);
                    if (index !== -1) {
                        selectedDays.splice(index, 1);
                    }
                }
            });
        });

        // 마감 여부 활성화
        function selectStatusModal(input) {
            const inputNameModal = input.getAttribute('name'); 

            const namePartsModal = inputNameModal.match(/\[(\d+|\d{4}-\d{2})\]/g);
            if (!namePartsModal || namePartsModal.length < 2) return;

            const roomIdx = namePartsModal[0].replace(/\[|\]/g, '');
            const rateplanIdx = namePartsModal[1].replace(/\[|\]/g, '');

            const priceBasicInputModal = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceBasic]"]`);
            const isClosedSelectModal = document.querySelector(`select[name*="[${roomIdx}][${rateplanIdx}][isClosed]"]`);
            const tdParentModal = isClosedSelectModal ? isClosedSelectModal.closest('td') : null;

            if (isClosedSelectModal) {
                tdParentModal.style.backgroundColor = "#ff005120";
            } else {
                tdParentModal.style.backgroundColor = "";
            }
        }

        // // 가격 필드 값이 비어있으면 마감 여부 비활성화 적용
        // function initializeStatusModal() {
        //     const priceBasicInputs = document.querySelectorAll('input[name*="[priceBasic]"]');
        //     priceBasicInputs.forEach(input => {
        //         selectStatusModal(input);
        //     });
        // }

        // 페이지 로드 시 자동으로 초기화
        // document.addEventListener('DOMContentLoaded', initializeStatusModal);

        // 할인가 변동
        function updateDiscountPriceModal(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 2) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');

            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceBasic]"]`);
            const priceSaleInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceSale]"]`);

            const salePercentInputsModal = document.querySelectorAll(`input[name*="[${roomIdx}][${rateplanIdx}][salePercent]"]`);
            const salePercentModal = Array.from(salePercentInputsModal).find(input => {
                const baseId = input.id.split('_')[0]; 
                return baseId === "salePercentModal";
            });            

            const priceBasic = parseFloat(removeCommas(priceBasicInput.value)) || 0;
            const salePercent = parseFloat(removeCommas(salePercentModal.value)) || 0;
            const priceSale = parseFloat(removeCommas(priceSaleInput.value)) || 0;

            if (priceBasicInput || priceSaleInput || salePercentModal) {
                // 수정된 필드가 priceBasic일 때
                if (input.name.includes('priceBasic')) {
                    const discount = Math.round(priceBasic * (salePercent / 100));
                    const discountPrice = priceBasic - discount;

                    priceSaleInput.value = discountPrice >= 0 ? discountPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "";
                    salePercentModal.value = priceBasic > 0 ? ((priceBasic - discountPrice) / priceBasic * 100).toFixed(2) : "";

                    // 수정된 날짜에만 반영
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceBasic]`] = removeCommas(priceBasicInput.value);
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][salePercent]`] = removeCommas(salePercentModal.value);
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceSale]`] = removeCommas(priceSaleInput.value);

                    const priceBasicTd = priceBasicInput.closest('td');
                    const priceSaleTd = priceSaleInput.closest('td');

                    if (priceBasic.value > 0) {
                        priceBasicTd.style.backgroundColor = "";
                        priceSaleTd.style.backgroundColor = "";
                    } else {
                        priceBasicTd.style.backgroundColor = "#ff005120";
                        priceSaleTd.style.backgroundColor = "#ff005120";
                    }
                }

                // 수정된 필드가 salePercent일 때
                if (input.name.includes('salePercent')) {
                    const discount = Math.round(priceBasic * (salePercent / 100));
                    const discountPrice = priceBasic - discount;

                    priceSaleInput.value = discountPrice >= 0 ? discountPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "";

                    // 수정된 날짜에만 반영
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceBasic]`] = removeCommas(priceBasicInput.value);
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][salePercent]`] = removeCommas(salePercentModal.value);
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceSale]`] = removeCommas(priceSaleInput.value);

                    const salePercentTd = salePercentModal.closest('td');
                    const priceSaleTd = priceSaleInput.closest('td');

                    if (salePercent.value > 0) {
                        salePercentTd.style.backgroundColor = "";
                        priceSaleTd.style.backgroundColor = "";
                    } else {
                        salePercentTd.style.backgroundColor = "#ff005120";
                        priceSaleTd.style.backgroundColor = "#ff005120";
                    }
                }

                // 수정된 필드가 priceSale일 때
                if (input.name.includes('priceSale')) {
                    const discountPercent = ((priceBasic - priceSale) / priceBasic) * 100;
                    salePercentModal.value = discountPercent.toFixed(2);

                    const calculatedPriceBasic = priceSale > 0 ? priceSale / (1 - (salePercent / 100)) : 0;
                    priceBasicInput.value = calculatedPriceBasic > 0 ? calculatedPriceBasic.toString() : "";

                    // 수정된 날짜에만 반영
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceBasic]`] = removeCommas(priceBasicInput.value);
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][salePercent]`] = removeCommas(salePercentModal.value);
                    modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceSale]`] = removeCommas(priceSaleInput.value); 
                }
            }
        }

        // 할인율 변동
        function updateDiscountRateModal(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 2) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');
            
            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceBasic]"]`);
            const priceSaleInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceSale]"]`);
            
            const salePercentInputsModal = document.querySelectorAll(`input[name*="[${roomIdx}][${rateplanIdx}][salePercent]"]`);
            const salePercentModal = Array.from(salePercentInputsModal).find(input => {
                const baseId = input.id.split('_')[0]; 
                return baseId === "salePercentModal";
            });                     

            if (priceBasicInput && priceSaleInput && salePercentModal) {
                let priceBasic = parseFloat(removeCommas(priceBasicInput.value)) || 0;
                let priceSale = parseFloat(removeCommas(priceSaleInput.value)) || 0;

                if (priceSale > priceBasic) {
                    alert('할인율은 최소 0% 이상으로 설정되어야 합니다.');
                    priceSale = priceBasic;

                    priceSaleInput.value = priceBasic.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                let discountPercent = ((priceBasic - priceSale) / priceBasic) * 100;

                if (isNaN(discountPercent)) {
                    discountPercent = 0;
                }

                if (discountPercent < 0) {
                    discountPercent = 0;
                    alert('할인율은 최소 0% 이상으로 설정되어야 합니다.');
                }

                // 값 할당 (중복 방지)
                salePercentModal.value = discountPercent.toFixed(2);

                modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceBasic]`] = removeCommas(priceBasicInput.value);
                modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][salePercent]`] = salePercentModal.value;
                modifiedDataModal[`rooms[${roomIdx}][${rateplanIdx}][priceSale]`] = removeCommas(priceSaleInput.value);

                const salePercentTd = salePercentModal.closest('td');
                const priceSaleTd = priceSaleInput.closest('td');

                if (priceSale.value > 0) {
                    salePercentTd.style.backgroundColor = "";
                    priceSaleTd.style.backgroundColor = "";
                } else {
                    salePercentTd.style.backgroundColor = "#ff005120"; 
                    priceSaleTd.style.backgroundColor = "#ff005120";
                }
            }
        }

        // 일반 요금 입력 시 할인율, 할인가 업데이트
        function updateAllModal(input) {
            const inputName = input.getAttribute('name');
            const nameParts = inputName.match(/\[(\d+|\d{4}-\d{2})\]/g);
            if (!nameParts || nameParts.length < 2) return;

            const roomIdx = nameParts[0].replace(/\[|\]/g, '');
            const rateplanIdx = nameParts[1].replace(/\[|\]/g, '');

            const priceBasicInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceBasic]"]`);
            const priceSaleInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][priceSale]"]`);
            const salePercentInput = document.querySelector(`input[name*="[${roomIdx}][${rateplanIdx}][salePercent]"]`);

            // priceBasic이 빈값일 때 sale과 percent도 빈값으로 초기화
            if (priceBasicInput && priceBasicInput.value.trim() === "") {
                if (priceSaleInput) priceSaleInput.value = "";
                if (salePercentInput) salePercentInput.value = "";
            }

            updateDiscountPriceModal(input);
            updateDiscountRateModal(input);
        }

        const modifiedDataModal = {};

        document.querySelectorAll('#quantityBodyModal input, #quantityBodyModal select, #priceBodyModal input, #priceBodyModal select, #selectBodyModal input, #selectBodyModal select').forEach(input => {
            input.addEventListener('change', () => {
                let newValue = input.type === 'checkbox' ? input.checked : input.value;

                // 콤마가 포함된 값을 숫자로 변환 (input[type="text"] 등에서 사용)
                if (typeof newValue === 'string' && newValue.includes(',')) {
                    newValue = Number(newValue.replace(/,/g, ""));
                }

                newValue = newValue === '' ? '' : newValue;

                modifiedDataModal[input.name] = newValue;
            });
        });

        // 탭 이동 시, 변경된 값 초기화
        document.addEventListener("DOMContentLoaded", () => {
            const tabs = document.querySelectorAll('.nav-link');
            let activeTab = document.querySelector('.nav-link.active');

            tabs.forEach(tab => {
                tab.addEventListener("click", (event) => {
                    if (tab === activeTab) return;

                    const activePaneId = activeTab.getAttribute("data-bs-target") || activeTab.getAttribute("href");
                    const activeTabContent = document.querySelector(activePaneId);
                    
                    let hasModifiedData = false; 

                    if (activeTabContent) {
                        // input 요소 검사
                        activeTabContent.querySelectorAll("#quantityBodyModal input, #priceBodyModal input").forEach(el => {
                            if (el.value !== "" || el.checked) {
                                hasModifiedData = true; 
                            }
                        });

                        // select 요소 검사
                        activeTabContent.querySelectorAll("#selectBodyModal select").forEach(selectEl => {
                            if (selectEl.value !== "empty") {
                                hasModifiedData = true; 
                            }
                        });

                        if (hasModifiedData) {
                            const confirmChange = confirm("현재 입력된 정보가 초기화 됩니다. 그래도 이동하시겠습니까?");
                            if (!confirmChange) {
                                event.preventDefault();
                                return;
                            }
                        }

                        // 초기화 실행
                        activeTabContent.querySelectorAll("#quantityBodyModal input, #priceBodyModal input").forEach(el => {
                            el.value = "";
                            if (el.type === "checkbox" || el.type === "radio") {
                                el.checked = false;
                            }
                            modifiedDataModal[el.name] = "";

                            // 배경색 초기화
                            const tdParent = el.closest('td');
                            if (tdParent) {
                                tdParent.style.backgroundColor = "";
                            }
                        });

                        activeTabContent.querySelectorAll("#selectBodyModal select").forEach(selectEl => {
                            selectEl.value = "empty"; 
                            modifiedDataModal[selectEl.name] = "";

                            // 배경색 초기화
                            const tdParent = selectEl.closest('td');
                            if (tdParent) {
                                tdParent.style.backgroundColor = "";
                            }
                        });
                    }

                    // 수동으로 Bootstrap 탭 전환 실행
                    const newTab = new bootstrap.Tab(tab);
                    newTab.show();
                    activeTab = tab; 
                });
            });
        });

        // 일괄 저장하기
        async function saveBatchModification() {
            if (Object.keys(modifiedDataModal).length === 0) {
                return;
            }

            let requestData = {
                partnerIdx: <?= $selectedPartnerIdx; ?>,
                rooms: {},
                dates: [],
                dateType: 'range',
                dayOfWeek: []
            };

            if (selectedPeriod.startDate && selectedPeriod.endDate) {
                let startDate = new Date(selectedPeriod.startDate);
                let endDate = new Date(selectedPeriod.endDate);

             
                requestData.dates = [
                    startDate.toLocaleDateString('en-CA'), 
                    endDate.toLocaleDateString('en-CA')    
                ];
            }

            const checkedDays = document.querySelectorAll('#select-weekday input[type="checkbox"]:checked');
            requestData.dayOfWeek = Array.from(checkedDays).map(checkbox => checkbox.value);

            Object.entries(modifiedDataModal).forEach(([key, value]) => {
                const keys = key.match(/\[(.*?)\]/g)?.map(k => k.replace(/\[|\]/g, ""));

                if (keys && keys.length) {
                    const roomId = keys[0];
                    const quantityOrRateplan = keys[1];
                    const field = keys[2] || null;

                    let numericValue = (typeof value === 'string' && value.includes(',')) ? 
                        Number(value.replace(/,/g, "")) : value;

                    // 빈 값이나 잘못된 값은 처리하지 않도록
                    if (numericValue === "" || numericValue === undefined || numericValue === null || isNaN(numericValue)) {
                        numericValue = null;
                    }

                    // quantity 처리
                    if (!field && quantityOrRateplan === 'quantity') {
                        requestData.rooms[roomId] = requestData.rooms[roomId] || {
                            rateplans: {}
                        };

                        // 기존의 quantity가 있을 경우, 수정값이 없으면 기존 값을 유지
                        if (numericValue !== null) {
                            requestData.rooms[roomId].quantity = numericValue;
                        }
                    }

                    // 다른 필드 처리
                    if (field) {
                        requestData.rooms[roomId] = requestData.rooms[roomId] || {
                            quantity: null,
                            rateplans: {}
                        };
                        requestData.rooms[roomId].rateplans[quantityOrRateplan] = requestData.rooms[roomId].rateplans[quantityOrRateplan] || {};

                        if (field === "isClosed") {
                            requestData.rooms[roomId].rateplans[quantityOrRateplan][field] = (value === 'close');
                        } else {
                            if (isNaN(value)) {
                                requestData.rooms[roomId].rateplans[quantityOrRateplan][field] = value;
                            } else {
                                requestData.rooms[roomId].rateplans[quantityOrRateplan][field] = numericValue;
                            }
                        }
                    }
                }
            });

            try {
                if (!requestData.dayOfWeek || requestData.dayOfWeek.length === 0) {
                    alert("월 ~ 일 중 해당하는 요일을 선택해 주세요.");
                    return;
                }

                loading.style.display = 'flex'; 

                let hasLowPriceModal = false;

                Object.values(requestData.rooms).forEach(room => {
                    Object.values(room.rateplans).forEach(rateplan => {
                        const basicPrice = rateplan.priceBasic !== undefined ? rateplan.priceBasic : null;
                        const salePrice = rateplan.priceSale !== undefined ? rateplan.priceSale : null;

                        if ((basicPrice !== null && basicPrice < 10000) || 
                            (salePrice !== null && salePrice < 10000)) {
                            hasLowPriceModal = true;
                        }
                    });
                });

                if (hasLowPriceModal) {
                    const rangeConfirm = confirm('1만원 이하로 저장되는 요금이 있습니다. 저장하시겠습니까?');
                    if (!rangeConfirm) {
                        loading.style.display = 'none';
                        return;
                    }
                }

                const response = await fetch('/api/partner/rates-range', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestData),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('저장이 완료되었습니다.');
                    loading.style.display = 'flex'; 
                    location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
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
</body>

</html>