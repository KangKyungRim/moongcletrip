<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

// 게시
$publish = $data['publish'];

 // 검토
$draft = $data['draft'];

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">객실 상세 정보</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">객실 상세 정보</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4 position-relative overflow-hidden">

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
                        style="color: #344767; <?php if ($draft['room']['room_is_approved']) : ; ?>opacity: 0.4; pointer-events: none;<?php endif; ?>">
                            <span style="color: #344767;">검토</span>
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
                                        <?php if ($publish['room']->review_status !== "first_review") : ?>
                                            <div class="d-flex justify-content-end align-items-center">
                                                <button type="button" id="editForm_publish" name="editForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-room-info-edit?roomIdx=<?= $publish['room']['room_idx']; ?>'">수정하기</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <hr class="horizontal dark my-3">
                                    <?php if ($publish['room']->review_status !== "first_review") : ?>
                                        <div>
                                            <div class="form-group row align-items-center">
                                                <label for="roomName" class="form-control-label col-sm-2">객실명</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= $publish['room']['room_name']; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="roomOtherNotes" class="form-control-label col-sm-2">객실 정보</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= $publish['room']['room_other_notes']; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">객실 타입</label>

                                                <?php 
                                                    if (isset($publish['roomtype']) && is_object($publish['roomtype'])) {
                                                        $publish['roomtype'] = json_decode(json_encode($publish['roomtype']), true);
                                                    }
                                                    $selectedRoomtypes = isset($publish['roomtype']) 
                                                    ? array_map(fn($tag) => "roomtype-" . $tag, array_column($publish['roomtype'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['roomtypeTags'] as $key => $roomtype) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" id="roomtype-<?= $roomtype['tag_idx']; ?>" value="roomtype-<?= $roomtype['tag_idx']; ?>" name="roomtype[]"
                                                                <?php if (!empty($selectedRoomtypes) && in_array("roomtype-" . $roomtype['tag_idx'], $selectedRoomtypes)) : ?> 
                                                                    checked 
                                                                <?php endif; ?>
                                                                disabled>
                                                            <label class="custom-control-label" for="roomtype-<?= $roomtype['tag_idx']; ?>"><?= $roomtype['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="roomBeds" class="form-control-label col-sm-2">침대 구성</label>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="d-inline-block w-25">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <label class="custom-control-label w-70" for="dormitory_beds">도미토리</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['dormitory_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="single_beds">싱글 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['single_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="super_single_beds">슈퍼 싱글 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['super_single_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="semi_double_beds">세미 더블 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['semi_double_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="double_beds">더블 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['double_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-inline-block w-25" style="border-left:1px solid #cacaca66; border-right:1px solid #cacaca66; padding: 0px 20px; margin: 0 20px;">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <label class="custom-control-label w-70" for="queen_beds">퀸 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['queen_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="king_beds">킹 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['king_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="hollywood_twin_beds">할리우드 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['hollywood_twin_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="double_story_beds">이층 침대</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['double_story_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="bunk_beds">벙크 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['bunk_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-inline-block w-25">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <label class="custom-control-label w-70" for="rollaway_beds">간이 침대</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['rollaway_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="futon_beds">요이불 세트</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['futon_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="capsule_beds">캡슐 침대</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['capsule_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="sofa_beds">소파 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['sofa_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 justify-content-between">
                                                                <label class="custom-control-label w-70" for="air_beds">에어 베드</label>
                                                                <div class="text-xs fw-bold">
                                                                    <?= $publish['room']['room_bed_type']['air_beds']; ?>
                                                                    <span>개</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="roomSize" class="form-control-label col-sm-2">객실 크기</label>
                                                <div class="col text-xs fw-bold">
                                                    <?= $publish['room']['room_size']; ?><span>㎡</span>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">나이 기준</label>
                                                <div class="col d-flex flex-column gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8 d-flex align-items-center justify-content-between">
                                                            <label for="childAge">아동</label>
                                                            <span class="d-inline-block">만</span>
                                                        </div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= $publish['room']['room_child_age']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">세 이하</div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8"><label for="infantMonth">유아</label></div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= $publish['room']['room_tiny_month']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">개월 이하</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">

                                            <div class="form-group row align-items-center">
                                                <label for="roomSize" class="form-control-label col-sm-2">이용 인원</label>
                                                <div class="col d-flex flex-column gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8"><label for="standardPerson">기준 인원</label></div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= $publish['room']['room_standard_person']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">명</div>
                                                    </div>

                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8"><label for="maxPerson">최대 인원</label></div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= $publish['room']['room_max_person']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">명</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="horizontal gray-light my-3">                             

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">기준 인원 이외 추가 인원 요금 <br>(현장 결제)</label>
                                                <div class="col d-flex flex-column gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8"><label for="extraAdultFee">성인 1명</label></div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= number_format($publish['room']['room_adult_additional_price']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">원</div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8"><label for="extraChildFee">아동 1명</label></div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= number_format($publish['room']['room_child_additional_price']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">원</div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-xs w-8"><label for="extraInfantFee">유아 1명</label></div>
                                                        <div class="mx-2">
                                                            <div class="text-xs fw-bold">
                                                                <?= number_format($publish['room']['room_tiny_additional_price']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs">원</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">전망</label>

                                                <?php 
                                                    if (isset($publish['view']) && is_object($publish['view'])) {
                                                        $publish['view'] = json_decode(json_encode($publish['view']), true);
                                                    }
                                                    $selectedViews = isset($publish['view']) 
                                                    ? array_map(fn($tag) => "view-" . $tag, array_column($publish['view'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['viewTags'] as $key => $view) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" id="view-<?= $view['tag_idx']; ?>" value="view-<?= $view['tag_idx']; ?>" name="view[]"
                                                                <?php if (!empty($selectedViews) && in_array("view-" . $view['tag_idx'], $selectedViews)) : ?> 
                                                                    checked 
                                                                <?php endif; ?>
                                                                disabled>
                                                            <label class="custom-control-label" for="view-<?= $view['tag_idx']; ?>"><?= $view['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">편의시설</label>
                                                
                                                <?php 
                                                    if (isset($publish['amenity']) && is_object($publish['amenity'])) {
                                                        $publish['amenity'] = json_decode(json_encode($publish['amenity']), true);
                                                    }
                                                    $selectedAmenities = isset($publish['amenity']) 
                                                    ? array_map(fn($tag) => "amenity-" . $tag, array_column($publish['amenity'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['amenityTags'] as $key => $amenity) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" id="amenity-<?= $amenity['tag_idx']; ?>" value="amenity-<?= $amenity['tag_idx']; ?>" name="amenity[]"
                                                                <?php if (!empty($selectedAmenities) && in_array("amenity-" . $amenity['tag_idx'], $selectedAmenities)) : ?> 
                                                                    checked 
                                                                <?php endif; ?>
                                                                disabled>
                                                            <label class="custom-control-label" for="amenity-<?= $amenity['tag_idx']; ?>"><?= $amenity['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">객실 내 베리어프리<br>시설 및 서비스</label>

                                                <?php 
                                                    if (isset($publish['barrierfreeRoom']) && is_object($publish['barrierfreeRoom'])) {
                                                        $publish['barrierfreeRoom'] = json_decode(json_encode($publish['barrierfreeRoom']), true);
                                                    }
                                                    $selectedBarrierfreeRooms = isset($publish['barrierfreeRoom']) 
                                                    ? array_map(fn($tag) => "barrierfreeRoom-" . $tag, array_column($publish['barrierfreeRoom'], 'tag_idx')) 
                                                    : [];
                                                ?>
                                                <div class="col text-xs d-flex flex-wrap"  style="max-width: 80%;">
                                                    <?php foreach ($data['tags']['barrierfreeRoomTags'] as $key => $barrierfreeRoom) : ?>
                                                        <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                            <input class="form-check-input" type="checkbox" id="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>" value="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>" name="barrierfreeRoom[]"
                                                                <?php if (!empty($selectedBarrierfreeRooms) && in_array("barrierfreeRoom-" . $barrierfreeRoom['tag_idx'], $selectedBarrierfreeRooms)) : ?> 
                                                                    checked 
                                                                <?php endif; ?>
                                                                disabled>
                                                            <label class="custom-control-label" for="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>"><?= $barrierfreeRoom['tag_name']; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3">

                                            <div class="form-group row align-items-center">
                                                <label class="form-control-label col-sm-2">객실 이미지</label>
                                                <div class="col text-xs fw-bold">
                                                    <?php foreach ($publish['basicImage'] as $image) : ?>
                                                        <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <span class="alert-icon text-white align-middle"><i class="ni ni-bulb-61"></i></span>
                                            <span class="alert-text text-white p-2"><strong>검토 완료 후 상세 정보가 게시됩니다.</strong></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 검토 중 -->
                <div class="tab-pane fade" id="auditInfo" role="tabpanel" aria-labelledby="nav-draft-tab">
                    <!-- 상세 정보 -->
                    <?php if (!$draft['room']['room_is_approved']) : ?>
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <div class="card card-body p-5">
                                <div class="mb-0 d-flex justify-content-between align-items-center">
                                    <h6>검토 중인 정보</h6>

                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" id="editForm_draft" name="editForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-room-info-edit?roomIdx=<?= $draft['room']['room_idx']; ?>'">수정하기</button>
                                        <?php if ($data['user']['partner_user_level'] >= 7 && !$draft['room']['room_is_approved']) : ?>
                                            <button type="button" id="approve" name="approve" class="btn bg-gradient-primary m-0 ms-2">승인하기</button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-3">

                                <div id="collapseDraftStay" class="collapse show">
                                    <div>
                                        <div class="form-group row align-items-center">
                                            <label for="roomName" class="form-control-label col-sm-2">객실명</label>
                                            <div class="col text-xs fw-bold">
                                                <?= $draft['room']['room_name']; ?>
                                            </div>
                                        </div>

                                        <hr class="horizontal gray-light my-3">
                                        
                                        <div class="form-group row align-items-center">
                                            <label for="roomOtherNotes" class="form-control-label col-sm-2">객실 정보</label>
                                            <div class="col text-xs fw-bold">
                                                <?= $draft['room']['room_other_notes']; ?>
                                            </div>
                                        </div>

                                        <hr class="horizontal dark my-3">

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">객실 타입</label>

                                            <?php 
                                                if (isset($draft['roomtype']) && is_object($draft['roomtype'])) {
                                                    $draft['roomtype'] = json_decode(json_encode($draft['roomtype']), true);
                                                }
                                                $selectedRoomtypes = isset($draft['roomtype']) 
                                                ? array_map(fn($tag) => "roomtype-" . $tag, array_column($draft['roomtype'], 'tag_idx')) 
                                                : [];
                                            ?>
                                            <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                <?php foreach ($data['tags']['roomtypeTags'] as $key => $roomtype) : ?>
                                                    <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                        <input class="form-check-input" type="checkbox" id="roomtype-<?= $roomtype['tag_idx']; ?>" value="roomtype-<?= $roomtype['tag_idx']; ?>" name="roomtype[]"
                                                            <?php if (!empty($selectedRoomtypes) && in_array("roomtype-" . $roomtype['tag_idx'], $selectedRoomtypes)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                        <label class="custom-control-label" for="roomtype-<?= $roomtype['tag_idx']; ?>"><?= $roomtype['tag_name']; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <hr class="horizontal dark my-3">

                                        <div class="form-group row align-items-center">
                                            <label for="roomBeds" class="form-control-label col-sm-2">침대 구성</label>
                                            <div class="col">
                                                <div class="row">
                                                    <div class="d-inline-block w-25">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label class="custom-control-label w-70" for="dormitory_beds">도미토리</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['dormitory_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="single_beds">싱글 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['single_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="super_single_beds">슈퍼 싱글 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['super_single_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="semi_double_beds">세미 더블 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['semi_double_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="double_beds">더블 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['double_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-inline-block w-25" style="border-left:1px solid #cacaca66; border-right:1px solid #cacaca66; padding: 0px 20px; margin: 0 20px;">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label class="custom-control-label w-70" for="queen_beds">퀸 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['queen_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="king_beds">킹 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['king_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="hollywood_twin_beds">할리우드 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['hollywood_twin_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="double_story_beds">이층 침대</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['double_story_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="bunk_beds">벙크 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['bunk_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-inline-block w-25">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label class="custom-control-label w-70" for="rollaway_beds">간이 침대</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['rollaway_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="futon_beds">요이불 세트</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['futon_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="capsule_beds">캡슐 침대</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['capsule_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="sofa_beds">소파 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['sofa_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-3 justify-content-between">
                                                            <label class="custom-control-label w-70" for="air_beds">에어 베드</label>
                                                            <div class="text-xs fw-bold">
                                                                <?= $draft['room']['room_bed_type']['air_beds']; ?>
                                                                <span>개</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>      

                                        <hr class="horizontal gray-light my-3">

                                        <div class="form-group row align-items-center">
                                            <label for="roomSize" class="form-control-label col-sm-2">객실 크기</label>
                                            <div class="col text-xs fw-bold">
                                                <?= $draft['room']['room_size']; ?><span>㎡</span>
                                            </div>
                                        </div>

                                        <hr class="horizontal gray-light my-3">

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">나이 기준</label>
                                            <div class="col d-flex flex-column gap-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8 d-flex align-items-center justify-content-between">
                                                        <label for="childAge">아동</label>
                                                        <span class="d-inline-block">만</span>
                                                    </div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= $draft['room']['room_child_age']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">세 이하</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8"><label for="infantMonth">유아</label></div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= $draft['room']['room_tiny_month']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">개월 이하</div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="horizontal gray-light my-3">

                                        <div class="form-group row align-items-center">
                                            <label for="roomSize" class="form-control-label col-sm-2">이용 인원</label>
                                            <div class="col d-flex flex-column gap-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8"><label for="standardPerson">기준 인원</label></div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= $draft['room']['room_standard_person']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">명</div>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8"><label for="maxPerson">최대 인원</label></div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= $draft['room']['room_max_person']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">명</div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="horizontal gray-light my-3">                             

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">기준 인원 이외 추가 인원 요금 <br>(현장 결제)</label>
                                            <div class="col d-flex flex-column gap-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8"><label for="extraAdultFee">성인 1명</label></div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= number_format($draft['room']['room_adult_additional_price']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">원</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8"><label for="extraChildFee">아동 1명</label></div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= number_format($draft['room']['room_child_additional_price']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">원</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-xs w-8"><label for="extraInfantFee">유아 1명</label></div>
                                                    <div class="mx-2">
                                                        <div class="text-xs fw-bold">
                                                            <?= number_format($draft['room']['room_tiny_additional_price']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs">원</div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="horizontal dark my-3">

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">전망</label>

                                            <?php 
                                                if (isset($draft['view']) && is_object($draft['view'])) {
                                                    $draft['view'] = json_decode(json_encode($draft['view']), true);
                                                }
                                                $selectedViews = isset($draft['view']) 
                                                ? array_map(fn($tag) => "view-" . $tag, array_column($draft['view'], 'tag_idx')) 
                                                : [];
                                            ?>
                                            <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                <?php foreach ($data['tags']['viewTags'] as $key => $view) : ?>
                                                    <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                        <input class="form-check-input" type="checkbox" id="view-<?= $view['tag_idx']; ?>" value="view-<?= $view['tag_idx']; ?>" name="view[]"
                                                            <?php if (!empty($selectedViews) && in_array("view-" . $view['tag_idx'], $selectedViews)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                        <label class="custom-control-label" for="view-<?= $view['tag_idx']; ?>"><?= $view['tag_name']; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <hr class="horizontal dark my-3">

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">편의시설</label>
                                            
                                            <?php 
                                                if (isset($draft['amenity']) && is_object($draft['amenity'])) {
                                                    $draft['amenity'] = json_decode(json_encode($draft['amenity']), true);
                                                }
                                                $selectedAmenities = isset($draft['amenity']) 
                                                ? array_map(fn($tag) => "amenity-" . $tag, array_column($draft['amenity'], 'tag_idx')) 
                                                : [];
                                            ?>
                                            <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                                <?php foreach ($data['tags']['amenityTags'] as $key => $amenity) : ?>
                                                    <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                        <input class="form-check-input" type="checkbox" id="amenity-<?= $amenity['tag_idx']; ?>" value="amenity-<?= $amenity['tag_idx']; ?>" name="amenity[]"
                                                            <?php if (!empty($selectedAmenities) && in_array("amenity-" . $amenity['tag_idx'], $selectedAmenities)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                        <label class="custom-control-label" for="amenity-<?= $amenity['tag_idx']; ?>"><?= $amenity['tag_name']; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <hr class="horizontal dark my-3">

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">객실 내 베리어프리<br>시설 및 서비스</label>

                                            <?php 
                                                if (isset($draft['barrierfreeRoom']) && is_object($draft['barrierfreeRoom'])) {
                                                    $draft['barrierfreeRoom'] = json_decode(json_encode($draft['barrierfreeRoom']), true);
                                                }
                                                $selectedBarrierfreeRooms = isset($draft['barrierfreeRoom']) 
                                                ? array_map(fn($tag) => "barrierfreeRoom-" . $tag, array_column($draft['barrierfreeRoom'], 'tag_idx')) 
                                                : [];
                                            ?>
                                            <div class="col text-xs d-flex flex-wrap"  style="max-width: 80%;">
                                                <?php foreach ($data['tags']['barrierfreeRoomTags'] as $key => $barrierfreeRoom) : ?>
                                                    <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                        <input class="form-check-input" type="checkbox" id="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>" value="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>" name="barrierfreeRoom[]"
                                                            <?php if (!empty($selectedBarrierfreeRooms) && in_array("barrierfreeRoom-" . $barrierfreeRoom['tag_idx'], $selectedBarrierfreeRooms)) : ?> 
                                                                checked 
                                                            <?php endif; ?>
                                                            disabled>
                                                        <label class="custom-control-label" for="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>"><?= $barrierfreeRoom['tag_name']; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <hr class="horizontal dark my-3">

                                        <div class="form-group row align-items-center">
                                            <label class="form-control-label col-sm-2">객실 이미지</label>
                                            <div class="col text-xs fw-bold">
                                                <?php foreach ($draft['basicImage'] as $image) : ?>
                                                    <img width="150" src="<?= $image['image_small_path']; ?>" class="img-fluid rounded shadow-sm m-3 align-top" alt="<?= $image['image_origin_name']; ?>">
                                                <?php endforeach; ?>
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
        </div>

        <!-- button wrap -->
        <div class="d-flex justify-content-center mt-4">
            <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-room-list'">뒤로</button>
        </div>

        <footer class="footer py-5">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © 2025,
                            made with <i class="fa fa-heart"></i> by
                            <a href="https://www.moongcletrip.com" class="font-weight-bold" target="_blank">Honolulu Company</a>
                            for a better.
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

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/loading.php"; ?>

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

    <?php if ($data['user']['partner_user_level'] >= 7 && !$draft['room']['room_is_approved']) : ?>
    <script>
        document.getElementById('approve').addEventListener('click', async function() {
            const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
            const roomIdx = Number(<?php echo json_encode($draft['room']['room_idx']); ?>);

            const formData = {
                partnerIdx: selectedPartnerIdx,
                roomIdx: roomIdx,
            };

            try {
                // 서버로 POST 요청
                const response = await fetch('/api/partner/room/approve', {
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
                    window.location.href = '/partner/partner-room-info?roomIdx=' + '<?= $_GET['roomIdx']; ?>';
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