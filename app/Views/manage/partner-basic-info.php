<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

// 게시
$publish = $data['publish'];

 // 심사
$draft = $data['draft'];

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
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">숙소 정보 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">숙소 정보 관리</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4 position-relative overflow-hidden">
            <?php if ($selectedPartnerIdx > 0) : ?>

                <input type="hidden" name="partnerIdx" id="partnerIdx" value="<?= $publish['partner']->partner_idx ?>">

                <div class="nav-wrapper position-fixed w-3 checking">
                  <ul class="nav nav-pills nav-fill flex-column p-1" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#postingInfo" role="tab" aria-controls="postingInfo" aria-selected="true">
                        <span style="color: #344767;">게시</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#auditInfo" role="tab" 
                        aria-controls="auditInfo" aria-selected="false"
                        style="color: #344767; <?php if ($draft['partner']['is_approved']) : ; ?>opacity: 0.4; pointer-events: none;<?php endif; ?>"
                        onclick="saveActiveTab('auditInfo');">
                        <span style="color: #344767;">심사</span>
                      </a>
                    </li>
                  </ul>
                </div>
                
                <div class="float-end tablet-float" style="width:94%;">

                  <!-- tab content -->
                  <div class="tab-content" id="nav-tabContent">

                    <!-- 게시 중 -->
                    <div class="tab-pane fade show active" id="postingInfo" role="tabpanel" aria-labelledby="nav-home-tab">
                      <div class="row">
                        <div class="col-12 mx-auto">
                            <div class="card card-body p-5">
                                <div id="collapsePublishedStay" class="collapse show">
                                  <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">게시된 정보</h6>
                                        <?php if ($publish['partner']->partner_address1 !== null) : ; ?>
                                            <div class="d-flex justify-content-end align-items-center">
                                                <button type="button" id="editForm_publish" name="editForm" class="btn btn-light m-0" onclick="location.href='/manage/partner-basic-info-edit'">수정하기</button>
                                            </div>  
                                        <?php endif; ?>
                                    </div>
                                    <hr class="horizontal dark my-3">
                                    <?php if ($publish['partner']->partner_address1 !== null) : ; ?>

                                        <div class="nav-wrapper position-relative end-0">
                                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                                <li class="nav-item cursor-pointer">
                                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" data-bs-target="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">
                                                        기본 정보
                                                    </a>
                                                </li>
                                                <li class="nav-item cursor-pointer">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#facilityInfo" role="tab" aria-controls="facilityInfo" aria-selected="false">
                                                        시설 정보
                                                    </a>
                                                </li>
                                                <li class="nav-item cursor-pointer">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#stayPicture" role="tab" aria-controls="stayPicture" aria-selected="false">
                                                        숙소 사진
                                                    </a>
                                                </li>
                                                <li class="nav-item cursor-pointer">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#cancelRules" role="tab" aria-controls="cancelRules" aria-selected="false">
                                                        취소 및 환불 규정
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <hr class="horizontal white my-3">

                                        <div>

                                            <div class="tab-content" id="nav-tabContent">
                                              <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="nav-home-tab">          

                                                    <!-- 기본 정보 -->
                                                    <div>

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">숙소명</label>
                                                            <div class="col fw-bold text-xs">
                                                                <?= $publish['partner']->partner_name; ?>
                                                            </div>
                                                        </div>

                                                        <hr class="horizontal gray-light my-3">

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2" for="partnerCategory">카테고리</label>

                                                            <div class="row col align-items-center">
                                                                <div class="col">
                                                                <div class="form-check d-inline-block mb-0">
                                                                        <input class="form-check-input" type="radio" disabled <?= $publish['partner']['partner_category'] == 'stay' ? 'checked' : ''; ?>>
                                                                        <label class="custom-control-label mb-0">스테이</label>
                                                                    </div>
                                                                    <div class="form-check d-inline-block mx-3 mb-0">
                                                                        <input class="form-check-input" type="radio" disabled <?= $publish['partner']['partner_category'] == 'activity' ? 'checked' : ''; ?>>
                                                                        <label class="custom-control-label mb-0">액티비티&체험</label>
                                                                    </div>
                                                                    <div class="form-check d-inline-block mb-0">
                                                                        <input class="form-check-input" type="radio" disabled <?= $publish['partner']['partner_category'] == 'tour' ? 'checked' : ''; ?>>
                                                                        <label class="custom-control-label mb-0">투어</label>
                                                                    </div>
                                                                </div>

                                                                <!-- <div id="subCategory" class="col-sm-2 <?= $selectedPartner['partner_category'] == 'stay' ? '' : 'd-none'; ?>">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" disabled <?= $selectedPartner['partner_type'] == 'hotel' ? 'checked' : ''; ?>>
                                                                        <label class="custom-control-label mb-0">호텔</label>
                                                                    </div>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" disabled <?= $selectedPartner['partner_type'] == 'pension' ? 'checked' : ''; ?>>
                                                                        <label class="custom-control-label mb-0">펜션</label>
                                                                    </div>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" disabled <?= $selectedPartner['partner_type'] == 'resort' ? 'checked' : ''; ?>>
                                                                        <label class="custom-control-label mb-0">리조트</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4 <?= $selectedPartner['partner_type'] == 'hotel' ? '' : 'd-none'; ?>">
                                                                    <div class="dropdown">
                                                                        <button class="btn bg-gradient-secondary dropdown-toggle" type="button" onclick="return false">
                                                                            <?= !empty($selectedPartner['partner_grade']) ? $selectedPartner['partner_grade'] : '등급'; ?>
                                                                        </button>
                                                                    </div>
                                                                </div> -->
                                                            </div>

                                                        </div>

                                                        <hr class="horizontal gray-light my-3">

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">주소</label>
                                                            <div class="col text-xs fw-bold">
                                                            <?= $publish['partner']['partner_origin_address1']; ?> 
                                                            <?= $publish['partner']['partner_origin_address2']; ?> <?= $publish['partner']['partner_origin_address3']; ?> 
                                                            <?= $publish['partner']['partner_zip']; ?>
                                                            </div>
                                                        </div>

                                                        <hr class="horizontal gray-light my-3">

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">대표 전화번호</label>
                                                            <div class="col text-xs fw-bold">
                                                                <?= $publish['partner']['partner_phonenumber']; ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">대표 이메일</label>
                                                            <div class="col text-xs fw-bold">
                                                                <?= $publish['partner']['partner_email']; ?>
                                                            </div>
                                                        </div>

                                                        <hr class="horizontal gray-light my-3">

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">예약실 전화번호</label>
                                                            <div class="col text-xs fw-bold">
                                                                <?= $publish['partner']['partner_reservation_phonenumber']; ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">예약실 이메일</label>
                                                            <div class="col text-xs fw-bold">
                                                                <?= $publish['partner']['partner_reservation_email']; ?>
                                                            </div>
                                                        </div>

                                                        <hr class="horizontal gray-light my-3">

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">판매 담당자 휴대폰 번호</label>
                                                            <div class="col text-xs fw-bold">
                                                                <?= $publish['partner']['partner_manager_phonenumber']; ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row align-items-center">
                                                            <label class="form-control-label col-sm-2">판매 담당자 이메일</label>
                                                            <div class="col text-xs fw-bold">
                                                                <?= $publish['partner']['partner_manager_email']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                      

                                                <div class="tab-pane fade" id="facilityInfo" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">숙소 구분</label>

                                                        <?php 
                                                            if (isset($publish['stayType']) && is_object($publish['stayType'])) {
                                                                $publish['stayType'] = json_decode(json_encode($publish['stayType']), true);
                                                            }
                                                            $selectedStayTypes = isset($publish['stayType']) 
                                                            ? array_map(fn($tag) => "stayType-" . $tag, array_column($publish ['stayType'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['stayTypes'] as $key => $stayType) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="stayType-<?= $stayType['tag_idx']; ?>" id="stayType-<?= $stayType['tag_idx']; ?>" name="stayType[]"
                                                                    <?php if (!empty($selectedStayTypes) && in_array("stayType-" . $stayType['tag_idx'], $selectedStayTypes)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="stayType-<?= $stayType['tag_idx']; ?>"><?= $stayType['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">숙소 상세</label>

                                                        <?php 
                                                            if (isset($publish['stayTypeDetail']) && is_object($publish['stayTypeDetail'])) {
                                                                $publish['stayTypeDetail'] = json_decode(json_encode($publish['stayTypeDetail']), true);
                                                            }
                                                            $selectedStayTypeDetails = isset($publish['stayTypeDetail']) 
                                                            ? array_map(fn($tag) => "stayTypeDetail-" . $tag, array_column($publish['stayTypeDetail'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['stayTypeDetail'] as $key => $stayTypeDetail) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="stayTypeDetail-<?= $stayTypeDetail['tag_idx']; ?>" id="stayTypeDetail-<?= $stayTypeDetail['tag_idx']; ?>" name="stayTypeDetail[]"
                                                                    <?php if (!empty($selectedStayTypeDetails) && in_array("stayTypeDetail-" . $stayTypeDetail['tag_idx'], $selectedStayTypeDetails)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="stayTypeDetail-<?= $stayTypeDetail['tag_idx']; ?>"><?= $stayTypeDetail['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">편의시설</label>

                                                        <?php 
                                                            if (isset($publish['facility']) && is_object($publish['facility'])) {
                                                                $publish['facility'] = json_decode(json_encode($publish['facility']), true);
                                                            }
                                                            $selectedFacilities = isset($publish['facility']) 
                                                            ? array_map(fn($tag) => "facility-" . $tag, array_column($publish['facility'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['facilityTags'] as $key => $facility) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="facility-<?= $facility['tag_idx']; ?>" id="facility-<?= $facility['tag_idx']; ?>" name="facility[]"
                                                                    <?php if (!empty($selectedFacilities) && in_array("facility-" . $facility['tag_idx'], $selectedFacilities)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="facility-<?= $facility['tag_idx']; ?>"><?= $facility['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">주변 즐길거리</label>

                                                        <?php 
                                                            if (isset($publish['attraction']) && is_object($publish['attraction'])) {
                                                                $publish['attraction'] = json_decode(json_encode($publish['attraction']), true);
                                                            }
                                                            $selectedAttractions = isset($publish['attraction']) 
                                                            ? array_map(fn($tag) => "attraction-" . $tag, array_column($publish['attraction'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['attractionTags'] as $key => $attraction) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="attraction-<?= $attraction['tag_idx']; ?>" id="attraction-<?= $attraction['tag_idx']; ?>" name="attraction[]"
                                                                    <?php if (!empty($selectedAttractions) && in_array("attraction-" . $attraction['tag_idx'], $selectedAttractions)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="attraction-<?= $attraction['tag_idx']; ?>"><?= $attraction['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">서비스</label>

                                                        <?php 
                                                            if (isset($publish['service']) && is_object($publish['service'])) {
                                                                $publish['service'] = json_decode(json_encode($publish['service']), true);
                                                            }
                                                            $selectedServices = isset($publish['service']) 
                                                            ? array_map(fn($tag) => "service-" . $tag, array_column($publish['service'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['serviceTags'] as $key => $service) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="service-<?= $service['tag_idx']; ?>" id="service-<?= $service['tag_idx']; ?>" name="service[]"
                                                                    <?php if (!empty($selectedServices) && in_array("service-" . $service['tag_idx'], $selectedServices)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="service-<?= $service['tag_idx']; ?>"><?= $service['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">반려동물 동반 정보 (가능 시)</label>

                                                        <?php 
                                                            if (isset($publish['pet']) && is_object($publish['pet'])) {
                                                                $publish['pet'] = json_decode(json_encode($publish['pet']), true);
                                                            }
                                                            $selectedPets = isset($publish['pet']) 
                                                            ? array_map(fn($tag) => "pet-" . $tag, array_column($publish['pet'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['petTags'] as $key => $pet) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="pet-<?= $pet['tag_idx']; ?>" id="pet-<?= $pet['tag_idx']; ?>" name="pet[]"
                                                                    <?php if (!empty($selectedPets) && in_array("pet-" . $pet['tag_idx'], $selectedPets)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="pet-<?= $pet['tag_idx']; ?>"><?= $pet['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">베리어프리 시설 및 서비스<br>(공용공간)</label>

                                                        <?php 
                                                            if (isset($publish['barrierfreePublic']) && is_object($publish['barrierfreePublic'])) {
                                                                $publish['barrierfreePublic'] = json_decode(json_encode($publish['barrierfreePublic']), true);
                                                            }
                                                            $selectedBarrierfreePublics = isset($publish['barrierfreePublic']) 
                                                            ? array_map(fn($tag) => "barrierfreePublic-" . $tag, array_column($publish['barrierfreePublic'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['barrierfreePublicTags'] as $key => $bfPublic) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="barrierfreePublic-<?= $bfPublic['tag_idx']; ?>" id="barrierfreePublic-<?= $bfPublic['tag_idx']; ?>" name="bfPublic[]"
                                                                    <?php if (!empty($selectedBarrierfreePublics) && in_array("barrierfreePublic-" . $bfPublic['tag_idx'], $selectedBarrierfreePublics)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="barrierfreePublic-<?= $bfPublic['tag_idx']; ?>"><?= $bfPublic['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">숙소 취향</label>

                                                        <?php 
                                                            if (isset($publish['barrierfreeRoom']) && is_object($publish['barrierfreeRoom'])) {
                                                                $publish['barrierfreeRoom'] = json_decode(json_encode($publish['barrierfreeRoom']), true);
                                                            }
                                                            $selectedBarrierfreeRooms = isset($publish['barrierfreeRoom']) 
                                                            ? array_map(fn($tag) => "barrierfreeRoom-" . $tag, array_column($publish['barrierfreeRoom'], 'tag_idx')) 
                                                            : [];
                                                        ?>
                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['newStayTasteTags'] as $key => $bfRoom) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input class="form-check-input" type="checkbox" value="barrierfreeRoom-<?= $bfRoom['tag_idx']; ?>" id="barrierfreeRoom-<?= $bfRoom['tag_idx']; ?>" name="bfRoom[]"
                                                                    <?php if (!empty($selectedBarrierfreeRooms) && in_array("barrierfreeRoom-" . $bfRoom['tag_idx'], $selectedBarrierfreeRooms)) : ?> 
                                                                        checked 
                                                                    <?php endif; ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="barrierfreeRoom-<?= $bfRoom['tag_idx']; ?>"><?= $bfRoom['tag_name']; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">체크인/체크아웃</label>
                                                        <div class="col text-xs fw-bold">
                                                          <?= !empty($publish['stay']['stay_checkin_rule']) ? substr($publish['stay']['stay_checkin_rule'], 0, 5) : '체크인'; ?>
                                                          ~ 
                                                          <?= !empty($publish['stay']['stay_checkout_rule']) ? substr($publish['stay']['stay_checkout_rule'], 0, 5) : '체크아웃'; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <div class="col-sm-2">
                                                            <label class="form-control-label">검색 결과 노출 뱃지</label>
                                                            <small class="text-xs mt-1 d-block">
                                                                * 앱 내 검색 시 숙소 정보와 함께 뱃지 형식으로 노출됩니다. (최대 3개)
                                                            </small>
                                                        </div>

                                                        <?php
                                                        $selectedSearchBadges = isset($publish['partner']['partner_search_badge']) && is_array($publish['partner']['partner_search_badge'])
                                                            ? $publish['partner']['partner_search_badge']
                                                            : [];
                                                        ?>

                                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                            <?php foreach ($data['tags']['searchBadgeTags'] as $key => $facility) : ?>
                                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        value="<?= htmlspecialchars($facility['tag_name']) ?>"
                                                                        id="searchBadge-<?= htmlspecialchars($facility['tag_name']) ?>"
                                                                        name="searchBadge[]"
                                                                        <?= in_array($facility['tag_name'], $selectedSearchBadges) ? 'checked' : '' ?>
                                                                    disabled>
                                                                    <label class="custom-control-label" for="searchBadge-<?= htmlspecialchars($facility['tag_name']) ?>">
                                                                        <?= htmlspecialchars($facility['tag_name']) ?>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">기본 정보</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_basic_info']); ?>
                                                        </div> 
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">공지사항</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_notice_info']); ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">중요사항</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_important_info']); ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">부대 시설 정보</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_amenity_info']); ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">조식 정보</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_breakfast_info']); ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">인원 정보</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_personnel_info']); ?>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="tab-pane fade" id="stayPicture" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">숙소 이미지</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?php foreach ($publish['basicImage'] as $image) : ?>
                                                              <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">
                                                            공용공간 접근성 및 배리어프리 물품 대여서비스
                                                        </label>
                                                        <div class="col text-xs fw-bold">
                                                          <?php foreach ($publish['bfPublicImage'] as $image) : ?>
                                                            <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                          <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">
                                                            배리어프리 객실 및 객실내 화장실
                                                        </label>
                                                        <div class="col text-xs fw-bold">
                                                            <?php foreach ($publish['bfRoomImage'] as $image) : ?>
                                                              <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="cancelRules" role="tabpanel" aria-labelledby="nav-contact-tab">
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">취소 및 환불 입력</label>
                                                        <div class="col text-xs fw-bold">
                                                            <?= textToTag($publish['stay']['stay_cancel_info']); ?>
                                                        </div>
                                                    </div>

                                                    <hr class="horizontal dark my-3">

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-2">취소 및 환불 규정</label>
                                                        <div class="col text-xs fw-bold">
                                                            <table class="table align-items-center mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">날짜</th>
                                                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">시각</th>
                                                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">환불금액</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($publish['cancelRules'] as $cancelRule) : ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center justify-content-center">
                                                                                    <div class="text-xs">체크인</div>
                                                                                    <div class="text-center m-2">
                                                                                        <?= $cancelRule['cancel_rules_day']; ?>
                                                                                    </div>
                                                                                    <div class="text-xs">일 전</div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex align-items-center justify-content-center">
                                                                                    <div class="text-center m-2">
                                                                                        <?= $cancelRule['cancel_rules_time']; ?>
                                                                                    </div>
                                                                                    <div class="text-xs">전까지 취소 시</div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex align-items-center justify-content-center">
                                                                                    <div class="text-xs">환불금액</div>
                                                                                    <div class="text-center m-2">
                                                                                        <?= $cancelRule['cancel_rules_percent']; ?>
                                                                                    </div>
                                                                                    <div class="text-xs">%</div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    <?php else : ?>
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <span class="alert-icon text-white align-middle"><i class="ni ni-bulb-61"></i></span>
                                            <span class="alert-text text-white p-2"><strong>심사 완료 후 상세 정보가 게시됩니다.</strong></span>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                      </div>
                    </div>

                    <!-- 심사 중 -->
                    <div class="tab-pane fade" id="auditInfo" role="tabpanel" aria-labelledby="nav-draft-tab">
                      <!-- 상세 정보 -->
                      <?php if (!$draft['partner']['is_approved']) : ?>
                      <div class="row">
                          <div class="col-12 mx-auto">
                              <div class="card card-body p-5">
                                  <div class="mb-0 d-flex justify-content-between align-items-center">
                                      <h6>심사 중인 정보</h6>

                                      <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" id="editForm_draft" name="editForm" class="btn btn-light m-0"  onclick="location.href='/manage/partner-basic-info-edit'">수정하기</button>
                                        <?php if ($data['user']['partner_user_level'] >= 7 && !$draft['partner']['is_approved']) : ?>
                                            <button type="button" id="approve" name="approve" class="btn bg-gradient-primary m-0 ms-2">승인하기</button>
                                        <?php endif; ?>
                                      </div>
                                  </div>

                                  <hr class="horizontal dark my-3">

                                  <div id="collapseDraftStay" class="collapse show">
                                      <div class="nav-wrapper position-relative end-0">
                                          <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                              <li class="nav-item cursor-pointer">
                                                  <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" data-bs-target="#basicInfoDraft" role="tab" aria-controls="basicInfoDraft" aria-selected="true">
                                                      기본 정보
                                                  </a>
                                              </li>
                                              <li class="nav-item cursor-pointer">
                                                  <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#facilityInfoDraft" role="tab" aria-controls="facilityInfoDraft" aria-selected="false">
                                                      시설 정보
                                                  </a>
                                              </li>
                                              <li class="nav-item cursor-pointer">
                                                  <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#stayPictureDraft" role="tab" aria-controls="stayPictureDraft" aria-selected="false">
                                                      숙소 사진
                                                  </a>
                                              </li>
                                              <li class="nav-item cursor-pointer">
                                                  <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#cancelRulesDraft" role="tab" aria-controls="cancelRulesDraft" aria-selected="false">
                                                      취소 및 환불 규정
                                                  </a>
                                              </li>
                                          </ul>
                                      </div>

                                      <hr class="horizontal white my-3">
                                      
                                      <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="basicInfoDraft" role="tabpanel" aria-labelledby="nav-basic-tab">
                                          <div class="row">
                                              <div class="col-12 mx-auto">
                                                  <div>

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">숙소명</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_name']; ?>
                                                          </div>
                                                      </div>

                                                      <hr class="horizontal gray-light my-3">

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">카테고리</label>
                                                          <div class="row col align-items-center">
                                                              <div class="col">
                                                                <div class="form-check d-inline-block mb-0">
                                                                      <input class="form-check-input" type="radio" disabled <?= $draft['partner']['partner_category'] == 'stay' ? 'checked' : ''; ?>>
                                                                      <label class="custom-control-label mb-0">스테이</label>
                                                                  </div>
                                                                  <div class="form-check d-inline-block mx-3 mb-0">
                                                                      <input class="form-check-input" type="radio" disabled <?= $draft['partner']['partner_category'] == 'activity' ? 'checked' : ''; ?>>
                                                                      <label class="custom-control-label mb-0">액티비티&체험</label>
                                                                  </div>
                                                                  <div class="form-check d-inline-block mb-0">
                                                                      <input class="form-check-input" type="radio" disabled <?= $draft['partner']['partner_category'] == 'tour' ? 'checked' : ''; ?>>
                                                                      <label class="custom-control-label mb-0">투어</label>
                                                                  </div>
                                                              </div>

                                                              <!-- <div id="subCategory" class="col-sm-4 <?= $draft['partner']['partner_category'] == 'stay' ? '' : 'd-none'; ?>">
                                                                  <div class="form-check">
                                                                      <input class="form-check-input" type="radio" disabled <?= $draft['partner']['partner_type'] == 'hotel' ? 'checked' : ''; ?>>
                                                                      <label class="custom-control-label mb-0">호텔</label>
                                                                  </div>

                                                                  <div class="form-check">
                                                                      <input class="form-check-input" type="radio" disabled <?= $draft['partner']['partner_type'] == 'pension' ? 'checked' : ''; ?>>
                                                                      <label class="custom-control-label mb-0">펜션</label>
                                                                  </div>

                                                                  <div class="form-check">
                                                                      <input class="form-check-input" type="radio" disabled <?= $draft['partner']['partner_type'] == 'resort' ? 'checked' : ''; ?>>
                                                                      <label class="custom-control-label mb-0">리조트</label>
                                                                  </div>
                                                              </div>

                                                              <div class="col-sm-4 <?= $draft['partner']['partner_type'] == 'hotel' ? '' : 'd-none'; ?>">
                                                                  <div class="dropdown">
                                                                      <button class="btn bg-gradient-secondary dropdown-toggle" type="button" onclick="return false">
                                                                          <?= !empty($draft['partner']['partner_grade']) ? $draft['partner']['partner_grade'] : '등급'; ?>
                                                                      </button>
                                                                  </div>
                                                              </div> -->
                                                          </div>

                                                      </div>

                                                      <hr class="horizontal gray-light my-3">

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">주소</label>
                                                          <div class="col text-xs fw-bold">
                                                            <?= $draft['partner']['partner_origin_address1']; ?> 
                                                            <?= $draft['partner']['partner_origin_address2']; ?> <?= $draft['partner']['partner_origin_address3']; ?> 
                                                            <?= $draft['partner']['partner_zip']; ?>
                                                          </div>
                                                      </div>

                                                      <hr class="horizontal gray-light my-3">

                                                      <div class="form-group row align-items-center">
                                                          <label for="partnerPhonenumber" class="form-control-label col-sm-2">대표 전화번호</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_phonenumber']; ?>
                                                          </div>
                                                      </div>

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">대표 이메일</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_email']; ?>
                                                          </div>
                                                      </div>

                                                      <hr class="horizontal gray-light my-3">

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">예약실 전화번호</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_reservation_phonenumber']; ?>
                                                          </div>
                                                      </div>

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">예약실 이메일</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_reservation_email']; ?>
                                                          </div>
                                                      </div>

                                                      <hr class="horizontal gray-light my-3">

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">판매 담당자 휴대폰 번호</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_manager_phonenumber']; ?>
                                                          </div>
                                                      </div>

                                                      <div class="form-group row align-items-center">
                                                          <label class="form-control-label col-sm-2">판매 담당자 이메일</label>
                                                          <div class="col text-xs fw-bold">
                                                              <?= $draft['partner']['partner_manager_email']; ?>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                        </div>

                                        <div class="tab-pane fade" id="facilityInfoDraft" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">숙소 구분</label>

                                                <?php 
                                                    if (isset($draft['stayType']) && is_object($draft['stayType'])) {
                                                        $draft['stayType'] = json_decode(json_encode($draft['stayType']), true);
                                                    }
                                                    $selectedStayTypes = isset($draft['stayType']) 
                                                    ? array_map(fn($tag) => "stayType-" . $tag, array_column($draft ['stayType'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['stayTypes'] as $key => $stayType) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="stayType-<?= $stayType['tag_idx']; ?>" id="stayType-<?= $stayType['tag_idx']; ?>" name="stayType[]"
                                                            <?php if (!empty($selectedStayTypes) && in_array("stayType-" . $stayType['tag_idx'], $selectedStayTypes)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="stayType-<?= $stayType['tag_idx']; ?>"><?= $stayType['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">숙소 상세</label>

                                                <?php 
                                                    if (isset($draft['stayTypeDetail']) && is_object($draft['stayTypeDetail'])) {
                                                        $draft['stayTypeDetail'] = json_decode(json_encode($draft['stayTypeDetail']), true);
                                                    }
                                                    $selectedStayTypeDetails = isset($draft['stayTypeDetail']) 
                                                    ? array_map(fn($tag) => "stayTypeDetail-" . $tag, array_column($draft['stayTypeDetail'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['stayTypeDetail'] as $key => $stayTypeDetail) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="stayTypeDetail-<?= $stayTypeDetail['tag_idx']; ?>" id="stayTypeDetail-<?= $stayTypeDetail['tag_idx']; ?>" name="stayTypeDetail[]"
                                                            <?php if (!empty($selectedStayTypeDetails) && in_array("stayTypeDetail-" . $stayTypeDetail['tag_idx'], $selectedStayTypeDetails)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="stayTypeDetail-<?= $stayTypeDetail['tag_idx']; ?>"><?= $stayTypeDetail['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">편의시설</label>

                                                <?php 
                                                    if (isset($draft['facility']) && is_object($draft['facility'])) {
                                                        $draft['facility'] = json_decode(json_encode($draft['facility']), true);
                                                    }
                                                    $selectedFacilities = isset($draft['facility']) 
                                                    ? array_map(fn($tag) => "facility-" . $tag, array_column($draft['facility'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['facilityTags'] as $key => $facility) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="facility-<?= $facility['tag_idx']; ?>" id="facility-<?= $facility['tag_idx']; ?>" name="facility[]"
                                                            <?php if (!empty($selectedFacilities) && in_array("facility-" . $facility['tag_idx'], $selectedFacilities)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="facility-<?= $facility['tag_idx']; ?>"><?= $facility['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">주변 즐길거리</label>

                                                <?php 
                                                    if (isset($draft['attraction']) && is_object($draft['attraction'])) {
                                                        $draft['attraction'] = json_decode(json_encode($draft['attraction']), true);
                                                    }
                                                    $selectedAttractions = isset($draft['attraction']) 
                                                    ? array_map(fn($tag) => "attraction-" . $tag, array_column($draft['attraction'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['attractionTags'] as $key => $attraction) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="attraction-<?= $attraction['tag_idx']; ?>" id="attraction-<?= $attraction['tag_idx']; ?>" name="attraction[]"
                                                            <?php if (!empty($selectedAttractions) && in_array("attraction-" . $attraction['tag_idx'], $selectedAttractions)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="attraction-<?= $attraction['tag_idx']; ?>"><?= $attraction['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">서비스</label>

                                                <?php 
                                                    if (isset($draft['service']) && is_object($draft['service'])) {
                                                        $draft['service'] = json_decode(json_encode($draft['service']), true);
                                                    }
                                                    $selectedServices = isset($draft['service']) 
                                                    ? array_map(fn($tag) => "service-" . $tag, array_column($draft['service'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['serviceTags'] as $key => $service) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="service-<?= $service['tag_idx']; ?>" id="service-<?= $service['tag_idx']; ?>" name="service[]"
                                                            <?php if (!empty($selectedServices) && in_array("service-" . $service['tag_idx'], $selectedServices)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="service-<?= $service['tag_idx']; ?>"><?= $service['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">반려동물 동반 정보 (가능 시)</label>

                                                <?php 
                                                    if (isset($draft['pet']) && is_object($draft['pet'])) {
                                                        $draft['pet'] = json_decode(json_encode($draft['pet']), true);
                                                    }
                                                    $selectedPets = isset($draft['pet']) 
                                                    ? array_map(fn($tag) => "pet-" . $tag, array_column($draft['pet'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['petTags'] as $key => $pet) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="pet-<?= $pet['tag_idx']; ?>" id="pet-<?= $pet['tag_idx']; ?>" name="pet[]"
                                                            <?php if (!empty($selectedPets) && in_array("pet-" . $pet['tag_idx'], $selectedPets)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="pet-<?= $pet['tag_idx']; ?>"><?= $pet['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">베리어프리 시설 및 서비스<br>(공용공간)</label>

                                                <?php 
                                                    if (isset($draft['barrierfreePublic']) && is_object($draft['barrierfreePublic'])) {
                                                        $draft['barrierfreePublic'] = json_decode(json_encode($draft['barrierfreePublic']), true);
                                                    }
                                                    $selectedBarrierfreePublics = isset($draft['barrierfreePublic']) 
                                                    ? array_map(fn($tag) => "barrierfreePublic-" . $tag, array_column($draft['barrierfreePublic'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['barrierfreePublicTags'] as $key => $bfPublic) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="barrierfreePublic-<?= $bfPublic['tag_idx']; ?>" id="barrierfreePublic-<?= $bfPublic['tag_idx']; ?>" name="bfPublic[]"
                                                            <?php if (!empty($selectedBarrierfreePublics) && in_array("barrierfreePublic-" . $bfPublic['tag_idx'], $selectedBarrierfreePublics)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="barrierfreePublic-<?= $bfPublic['tag_idx']; ?>"><?= $bfPublic['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">숙소 취향</label>

                                                <?php 
                                                    if (isset($draft['barrierfreeRoom']) && is_object($draft['barrierfreeRoom'])) {
                                                        $draft['barrierfreeRoom'] = json_decode(json_encode($draft['barrierfreeRoom']), true);
                                                    }
                                                    $selectedBarrierfreeRooms = isset($draft['barrierfreeRoom']) 
                                                    ? array_map(fn($tag) => "barrierfreeRoom-" . $tag, array_column($draft['barrierfreeRoom'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['newStayTasteTags'] as $key => $bfRoom) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" value="barrierfreeRoom-<?= $bfRoom['tag_idx']; ?>" id="barrierfreeRoom-<?= $bfRoom['tag_idx']; ?>" name="bfRoom[]"
                                                            <?php if (!empty($selectedBarrierfreeRooms) && in_array("barrierfreeRoom-" . $bfRoom['tag_idx'], $selectedBarrierfreeRooms)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="barrierfreeRoom-<?= $bfRoom['tag_idx']; ?>"><?= $bfRoom['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div> 

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">체크인/체크아웃</label>
                                                <div class="col text-xs fw-bold">
                                                  <?= !empty($draft['stay']['stay_checkin_rule']) ? substr($draft['stay']['stay_checkin_rule'], 0, 5) : '체크인'; ?>
                                                  ~
                                                  <?= !empty($draft['stay']['stay_checkout_rule']) ? substr($draft['stay']['stay_checkout_rule'], 0, 5) : '체크아웃'; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <div class="col-sm-2">
                                                    <label class="form-control-label">검색 결과 노출 뱃지</label>
                                                    <small class="text-xs mt-1 d-block">
                                                        * 앱 내 검색 시 숙소 정보와 함께 뱃지 형식으로 노출됩니다. (최대 3개)
                                                    </small>
                                                </div>

                                                <?php
                                                $selectedSearchBadges = isset($draft['partner']['partner_search_badge']) && is_array($draft['partner']['partner_search_badge'])
                                                    ? $draft['partner']['partner_search_badge']
                                                    : [];
                                                ?>

                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['searchBadgeTags'] as $key => $facility) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                value="<?= htmlspecialchars($facility['tag_name']) ?>"
                                                                id="searchBadge-<?= htmlspecialchars($facility['tag_name']) ?>"
                                                                name="searchBadge[]"
                                                                <?= in_array($facility['tag_name'], $selectedSearchBadges) ? 'checked' : '' ?>
                                                            disabled>
                                                            <label class="custom-control-label" for="searchBadge-<?= htmlspecialchars($facility['tag_name']) ?>">
                                                                <?= htmlspecialchars($facility['tag_name']) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">기본 정보</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_basic_info']); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">공지 사항</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_notice_info']); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">중요 사항</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_important_info']); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">부대 시설 정보</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_amenity_info']); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">조식 정보</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_breakfast_info']); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">인원 정보</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_personnel_info']); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="stayPictureDraft" role="tabpanel" aria-labelledby="nav-profile-tab">
                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">숙소 이미지</label>
                                                <div class="col text-xs fw-bold">
                                                    <?php foreach ($draft['basicImage'] as $image) : ?>
                                                      <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">
                                                    공용공간 접근성 및 배리어프리 물품 대여서비스
                                                </label>
                                                <div class="col text-xs fw-bold">
                                                  <?php foreach ($draft['bfPublicImage'] as $image) : ?>
                                                    <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                  <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">
                                                    배리어프리 객실 및 객실내 화장실
                                                </label>
                                                <div class="col text-xs fw-bold">
                                                  <?php foreach ($draft['bfRoomImage'] as $image) : ?>
                                                    <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                  <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="cancelRulesDraft" role="tabpanel" aria-labelledby="nav-contact-tab">
                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">취소 및 환불</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= textToTag($draft['stay']['stay_cancel_info']); ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">
                                                    
                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">취소 및 환불 규정</label>
                                                <div class="col text-xs fw-bold">
                                                    <table class="table align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">날짜</th>
                                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">시각</th>
                                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">환불금액</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($draft['cancelRules'] as $cancelRule) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center justify-content-center">
                                                                            <div class="text-xs">체크인</div>
                                                                            <div class="text-center m-2">
                                                                                <?= $cancelRule['cancel_rules_day']; ?>
                                                                            </div>
                                                                            <div class="text-xs">일 전</div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center justify-content-center">
                                                                            <div class="text-center m-2">
                                                                                <?= $cancelRule['cancel_rules_time']; ?>
                                                                            </div>
                                                                            <div class="text-xs">전까지 취소 시</div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center justify-content-center">
                                                                            <div class="text-xs">환불금액</div>
                                                                            <div class="text-center m-2">
                                                                                <?= $cancelRule['cancel_rules_percent']; ?>
                                                                            </div>
                                                                            <div class="text-xs">%</div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
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
                      </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

              <?php else : ?>
                <div class="row">
                    <div class="col-12 mb-xl-0 mb-4">
                        <div class="card height-600 card-plain border">
                            <div class="card-body d-flex flex-column justify-content-center text-center">
                                <a href="/manage/partner-select">
                                    <i class="fa fa-plus text-secondary mb-3" aria-hidden="true"></i>
                                    <h5 class=" text-secondary"> 숙소 생성하기 </h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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

    <?php if ($data['user']['partner_user_level'] >= 7 && !$draft['partner']['is_approved']) : ?>
        <script>
            document.getElementById('approve').addEventListener('click', async function() {
                // 양식 데이터 수집
                const formData = {
                    partnerIdx: document.getElementById('partnerIdx').value,
                };

                // 주소 입력 안되어있으면 승인 불가 
                const partnerAddress = '<?= $draft['partner']->partner_address1 ?>';
                
                if (!partnerAddress) {
                    alert('숙소 최초 정보를 입력해 주세요.');
                    return;
                }

                try {
                    // 서버로 POST 요청
                    const response = await fetch('/api/partners/approve', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    // 응답 처리
                    const result = await response.json();
                    if (response.ok) {
                        alert('승인을 완료했습니다.');
                        loading.style.display = 'flex'; 
                        window.location.href = '/manage/partner-basic-info';
                    } else {
                        alert(result.error || '승인 중 문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('승인 중 오류가 발생했습니다.');
                }
            });
        </script>
    <?php endif; ?>
</body>

</html>