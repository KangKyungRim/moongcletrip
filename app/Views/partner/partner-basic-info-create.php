<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$partner = $data['partner'];
$partnerDraft = $data['partnerDraft'];
$cancelRulesDraft = $data['cancelRulesDraft'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">신규 숙소 생성</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">신규 숙소 생성</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 mx-auto">
            
                    <div class="card card-body p-5">
                      <div class="nav-wrapper position-relative end-0  mb-5">
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
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#cancelRule" role="tab" aria-controls="cancelRule" aria-selected="false">
                                    취소 및 환불 규정
                                </a>
                            </li>
                        </ul>
                      </div>

                      <div class="tab-content" id="nav-tabContent">
                        <!-- 기본 정보 -->
                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="nav-basic-tab">
                          <div>
                            <form method="post" name="basicInformationForm" id="basicInformationForm">

                                <input type="hidden" name="partnerIdx" id="partnerIdx" value="<?= $_GET['partner_idx'] ?>">
                                <input type="hidden" name="partnerGrade" id="partnerGrade" value="">
                                <input type="hidden" name="mapCity" id="mapCity" value="">
                                <input type="hidden" name="mapRegion" id="mapRegion" value="">
                                <input type="hidden" name="mapRegionDetail" id="mapRegionDetail" value="">
                                <input type="hidden" name="latitude" id="latitude" size="30" maxlength="30" placeholder="위도">
                                <input type="hidden" name="longitude" id="longitude" size="30" maxlength="30" placeholder="경도">

                                <div class="form-group row align-items-center">
                                    <label for="partnerName" class="form-control-label col-sm-1">
                                        <!-- <i class="ni ni-bulb-61" data-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="스테이의 경우 숙소명을 입력해주세요."></i> -->
                                        숙소명 <b class="text-danger">*</b>
                                    </label>
                                    <div class="col">
                                        <input class="form-control" type="text" id="partnerName" placeholder="스테이의 경우 숙소명을 입력해주세요" required>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1" for="partnerCategory">카테고리 <b class="text-danger">*</b></label>

                                    <div class="row col align-items-center">
                                        <div class="col-sm-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="partnerCategory" id="stay" <?= $selectedPartner['partner_category'] == 'stay' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="stay">스테이</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="partnerCategory" id="activity" <?= $selectedPartner['partner_category'] == 'activity' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="activity">액티비티&체험</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="partnerCategory" id="tour" <?= $selectedPartner['partner_category'] == 'tour' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="tour">투어</label>
                                            </div>
                                        </div>

                                        <div id="subCategory" class="col-sm-1 <?= $selectedPartner['partner_category'] == 'stay' ? '' : 'd-none'; ?>">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="partnerType" id="hotel" <?= $selectedPartner['partner_type'] == 'hotel' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="hotel">호텔</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="partnerType" id="pension" <?= $selectedPartner['partner_type'] == 'pension' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="pension">펜션</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="partnerType" id="resort" <?= $selectedPartner['partner_type'] == 'resort' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="resort">리조트</label>
                                            </div>
                                        </div>

                                        <div id="grade" class="col-sm-4 <?= $selectedPartner['partner_type'] == 'hotel' ? '' : 'd-none'; ?>">
                                            <div class="dropdown">
                                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button" id="partnerGradeSelect" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <?= !empty($selectedPartner['partner_grade']) ? $selectedPartner['partner_grade'] : '등급'; ?>
                                                </button>
                                                <ul id="partnerGradeList" class="dropdown-menu" aria-labelledby="partnerGradeSelect">
                                                    <li><a class="dropdown-item" href="javascript:;">1성급</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;">2성급</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;">3성급</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;">4성급</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;">5성급</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="gradeDescription" class="row <?= $selectedPartner['partner_type'] == 'hotel' ? '' : 'd-none'; ?>">
                                    <div class="col-sm-1"></div>
                                    <small class="col fs_xsmall">
                                        * 숙소 등급 정보는 정확한 정보 고지를 위해 뭉클트립 담당매니저가 검토 후 공신력이 있는 성급결정통지서와 같은 인증 서류를 요청할 수 있습니다. 등급 인정서를 보유하고 있지 않다면 네이버호텔에 노출되고 있는 동일한 등급을 선택바랍니다.
                                    </small>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="address" class="form-control-label col-sm-1">주소 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <p class="d-flex flex-wrap gap-2">
                                                    <input type="text" class="form-control mb-3 col" style="width: 60% !important;" name="zipcode" id="zipcode" placeholder="우편번호" readonly onclick="zipcodeMap('basicInformationForm', 'zipcode', 'address1', 'address2', 'address3', 'addressJibeon', 'mapCity', 'mapRegion', 'mapRegionDetail');" />
                                                    <button type="button" class="btn btn-secondary" onclick="zipcodeMap('basicInformationForm', 'zipcode', 'address1', 'address2', 'address3', 'addressJibeon', 'mapCity', 'mapRegion', 'mapRegionDetail');">주소검색</button>
                                                </p>

                                                <p>
                                                    <input type="text" name="address1" id="address1" class="form-control" readonly placeholder="기본주소">
                                                </p>
                                                <p>
                                                    <input type="text" name="address2" id="address2" class="form-control" placeholder="상세주소">
                                                </p>
                                                <p>
                                                    <input type="text" name="address3" id="address3" class="form-control" readonly placeholder="참고항목">
                                                </p>
                                            </div>
                                            <div class="col" style="height: 250px;">
                                                <div>
                                                    <div style="background-color:#f9f9f9; border: 1px solid #d7d7d7; width:100%; margin-top:1px; height:250px; border-radius:4px;" id="ft_map"></div>
                                                    <div id="clickLatlng"></div>
                                                    <p id="result"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="partnerPhonenumber" class="form-control-label col-sm-1">대표 전화번호 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <input type="tel" class="form-control" name="partnerPhonenumber" id="partnerPhonenumber" placeholder="'-'없이 숫자만 입력" minlength="2" maxlength="11" Numberonly />
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label for="partnerEmail" class="form-control-label col-sm-1">대표 이메일 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <input type="email" class="form-control" name="partnerEmail" id="partnerEmail" placeholder="이메일을 입력해주세요" minlength="2" maxlength="50" />
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="partnerPhonenumber" class="form-control-label col-sm-1">예약실 전화번호 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <input type="tel" class="form-control" name="partnerReservationPhonenumber" id="partnerReservationPhonenumber" placeholder="'-'없이 숫자만 입력" minlength="2" maxlength="11" Numberonly />
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label for="partnerReservationEmail" class="form-control-label col-sm-1">예약실 이메일 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <input type="email" class="form-control" name="partnerReservationEmail" id="partnerReservationEmail" placeholder="예약확정 및 취소 수신 이메일을 입력해주세요" minlength="2" maxlength="50" />
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="partnerManagerPhonenumber" class="form-control-label col-sm-1">판매 담당자 휴대폰 번호 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <input type="tel" class="form-control" name="partnerManagerPhonenumber" id="partnerManagerPhonenumber" placeholder="판매 담당자 휴대폰 번호를 입력해주세요 ('-'없이 숫자만 입력)" minlength="2" maxlength="11" Numberonly />
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label for="partnerManagerEmail" class="form-control-label col-sm-1">판매 담당자 이메일 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <input type="email" class="form-control" name="partnerManagerEmail" id="partnerManagerEmail" placeholder="판매 담당자 이메일을 입력해주세요" minlength="2" maxlength="50" />
                                    </div>
                                </div>

                            </form>
                          </div>
                        </div>   

                          <!-- 시설 정보 -->
                          <div class="tab-pane fade" id="facilityInfo" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div>
                              <div class="multisteps-form__content">
                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">편의시설 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <div class="row">
                                            <?php foreach ($amenities as $key => $amenity) : ?>
                                                <!-- <?php if ($key % 10 == 0) : ?>
                                                    <div class="w-100"></div>
                                                <?php endif; ?> -->
                                                <div class="form-check col-md-1">
                                                    <input class="form-check-input" type="checkbox" value="amenity-<?= $amenity['tag_idx']; ?>" id="amenity-<?= $amenity['tag_idx']; ?>" name="amenities[]" <?php if (in_array($amenity['tag_idx'], $selectedAmenities)) echo 'checked'; ?>>
                                                    <label class="custom-control-label" for="amenity-<?= $amenity['tag_idx']; ?>"><?= $amenity['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-3">

                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">베리어프리 시설 및 서비스<br>(공용공간) <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <div class="row">
                                            <?php foreach ($barrierfreePublic as $key => $barrierfree) : ?>
                                                <!-- <?php if ($key % 10 == 0) : ?>
                                                    <div class="w-100"></div>
                                                <?php endif; ?> -->
                                                <div class="form-check col-md-1">
                                                    <input class="form-check-input" type="checkbox" value="barrierfreePublic-<?= $barrierfree['tag_idx']; ?>" id="barrierfreePublic-<?= $barrierfree['tag_idx']; ?>" name="barrierfreePublic[]" <?php if (in_array($barrierfree['tag_idx'], $selectedBfPublic)) echo 'checked'; ?>>
                                                    <label class="custom-control-label" for="barrierfreePublic-<?= $barrierfree['tag_idx']; ?>"><?= $barrierfree['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-3">

                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">베리어프리 시설 및 서비스<br>(장애인객실) <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <div class="row">
                                            <?php foreach ($barrierfreeRoom as $key => $barrierfree) : ?>
                                                <!-- <?php if ($key % 10 == 0) : ?>
                                                    <div class="w-100"></div>
                                                <?php endif; ?> -->
                                                <div class="form-check col-md-1">
                                                    <input class="form-check-input" type="checkbox" value="barrierfreeRoom-<?= $barrierfree['tag_idx']; ?>" id="barrierfreeRoom-<?= $barrierfree['tag_idx']; ?>" name="barrierfreeRoom[]" <?php if (in_array($barrierfree['tag_idx'], $selectedBfRoom)) echo 'checked'; ?>>
                                                    <label class="custom-control-label" for="barrierfreeRoom-<?= $barrierfree['tag_idx']; ?>"><?= $barrierfree['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-3">

                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">체크인/체크아웃 <b class="text-danger">*</b></label>
                                    <div class="col-sm-1">
                                        <div class="dropdown">
                                            <button class="btn bg-gradient-secondary dropdown-toggle w-100 mb-0" type="button" id="checkinButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?= !empty($stayDraft['stay_checkin_rule']) ? substr($stayDraft['stay_checkin_rule'], 0, 5) : '체크인'; ?>
                                            </button>
                                            <ul id="checkinList" class="dropdown-menu overflow-y-scroll max-height-300" aria-labelledby="checkinButton">
                                                <?php
                                                for ($hour = 0; $hour < 24; $hour++) {
                                                    for ($minute = 0; $minute < 60; $minute += 30) {
                                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                                        echo '<li><a class="dropdown-item" href="javascript:;">' . $time . '</a></li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    ~
                                    <div class="col-sm-1">
                                        <div class="dropdown">
                                            <button class="btn bg-gradient-secondary dropdown-toggle w-100 mb-0" type="button" id="checkoutButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?= !empty($stayDraft['stay_checkout_rule']) ? substr($stayDraft['stay_checkout_rule'], 0, 5) : '체크아웃'; ?>
                                            </button>
                                            <ul id="checkoutList" class="dropdown-menu overflow-y-scroll max-height-300" aria-labelledby="checkoutButton">
                                                <?php
                                                for ($hour = 0; $hour < 24; $hour++) {
                                                    for ($minute = 0; $minute < 60; $minute += 30) {
                                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                                        echo '<li><a class="dropdown-item" href="javascript:;">' . $time . '</a></li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <div id="checkDescription" class="row">
                                        <div class="col-sm-1"></div>
                                        <small class="col fs_xsmall mt-3">
                                            * 00:00(AM) 부터 23:30(PM) 까지 30분 단위로 선택할 수 있습니다.
                                        </small>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-3">

                                <div class="form-group row align-items-center">
                                    <label for="stayBasicInfo" class="form-control-label col-sm-1">기본 정보</label>
                                    <div class="col">
                                        <textarea class="form-control" id="stayBasicInfo" name="stayBasicInfo" rows="7" placeholder="아래 예시처럼 기재해주세요.&#10;-무료주차 가능(객실당 1대)&#10;-무료Wi-Fi 가능&#10;-전객실 금연&#10;-어메니티 제공(치약,칫솔 유료)"></textarea>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="stayImportantInfo" class="form-control-label col-sm-1">중요 공지사항</label>
                                    <div class="col">
                                        <textarea class="form-control" id="stayImportantInfo" name="stayImportantInfo" rows="7" placeholder="아래 예시처럼 기재해주세요.&#10;-미성년자는 보호자 동반 없이 이용 불가&#10;-환경보호를 위해 치약,칫솔 제공하지 않음"></textarea>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="stayAmenityInfo" class="form-control-label col-sm-1">부대 시설 정보</label>
                                    <div class="col">
                                        <textarea class="form-control" id="stayAmenityInfo" name="stayAmenityInfo" rows="7" placeholder="아래 예시처럼 기재해주세요.&#10;-디너 라운지 제공: 18:00 ~ 21:00&#10;-야외수영장 8층(투숙객 무료입장)"></textarea>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="stayBreakfastInfo" class="form-control-label col-sm-1">조식 정보</label>
                                    <div class="col">
                                        <textarea class="form-control" id="stayBreakfastInfo" name="stayBreakfastInfo" rows="7" placeholder="아래 예시처럼 기재해주세요.&#10;-조식 제공: 06:00 ~ 10:00&#10;-1층 레스토랑"></textarea>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="stayPersonnelInfo" class="form-control-label col-sm-1">인원 정보</label>
                                    <div class="col">
                                        <textarea class="form-control" id="stayPersonnelInfo" name="stayPersonnelInfo" rows="7" placeholder="아래 예시처럼 기재해주세요.&#10;-기준인원 외 추가시, 1인 33,000원&#10;-소인(13세 이하) 무료투숙(영유아 제외)&#10;-최대 인원 초과 불가&#10;-엑스트라베트 추가시, 1박 55,000원(엑스트라베드 추가 가능 객실: 0000)&#10;-현장 결제"></textarea>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label for="stayCancelInfo" class="form-control-label col-sm-1">취소 및 환불</label>
                                    <div class="col">
                                        <textarea class="form-control" id="stayCancelInfo" name="stayCancelInfo" rows="7" placeholder="아래 예시처럼 기재해주세요.&#10;-체크인일 기준 3일 전 17시까지: 100%환불&#10;-체크인일 기준 3일 전 17시이후~당일 및 노쇼: 환불불가&#10;-취소,환불 시 수수료가 발생할 수 있습니다."><?= !empty($stayDraft['stay_cancel_info']) ? $stayDraft['stay_cancel_info'] : ''; ?></textarea>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>      

                          <!-- 숙소 사진 -->
                          <div class="tab-pane fade" id="stayPicture" role="tabpanel" aria-labelledby="nav-profile-tab">
                            
                            <input type="hidden" name="partnerIdx" id="partnerIdx" value="<?= $_GET['partner_idx'] ?>">
                            <input type="hidden" id="stayBasicImageMeta" name="stayBasicImageMeta" value="">
                            <input type="hidden" id="bfPublicImageMeta" name="bfPublicImageMeta" value="">
                            <input type="hidden" id="bfRoomImageMeta" name="bfRoomImageMeta" value="">

                            <div>
                              <div class="multisteps-form__content">
                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">숙소 이미지 <b class="text-danger">*</b></label>
                                    <div class="col">
                                        <div class="form-control dropzone" id="stayBasicImage"></div>
                                    </div>
                                </div>

                                <hr class="horizontal gray-light my-3">

                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">
                                        공용공간 접근성 및 배리어프리 물품 대여서비스
                                        <br>
                                        <small class="fs_xsmall">(최대 20장)</small>
                                        <br>
                                        <small class="fs_xsmall">프론트데스크 출입구, 부대시설 출입구, 장애인화장실, 수유실, 엘리베이터, 유모차대여 서비스, 휠체어대여서비스, 아기침대, 침대가드, 가습기 사진 등</small>
                                    </label>
                                    <div class="col">
                                        <div class="form-control dropzone" id="barrierfreePublicImage"></div>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-1">
                                        배리어프리 객실 및 객실내 화장실
                                        <br>
                                        <small class="fs_xsmall">(최대 20장)</small>
                                    </label>
                                    <div class="col">
                                        <div class="form-control dropzone" id="barrierfreeRoomImage"></div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>     

                          <!--  취소 및 환불 규정 -->
                          <div class="tab-pane fade" id="cancelRule" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div>
                              <div class="multisteps-form__content">
                                  <div class="form-group row align-items-center">
                                      <label class="form-control-label col-sm-1">취소 및 환불 규정 <b class="text-danger">*</b></label>
                                      <div class="col">
                                          <table id="cancelRules" class="table align-items-center">
                                              <thead>
                                                  <tr>
                                                      <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">날짜</th>
                                                      <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">시각</th>
                                                      <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 w-30">환불금액</th>
                                                      <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7"></th>
                                                  </tr>
                                              </thead>
                                              <tbody>

                                              </tbody>
                                          </table>
                                      </div>
                                  </div>

                                  <div class="text-center">
                                    <button type="button" class="btn bg-gradient-info mt-3" id="addCancelRuleBtn">+ 규칙 추가</button>
                                  </div>
                              </div>
                            </div>
                          </div> 
                      </div>
                    </div>

                    <!-- button wrap -->
                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0">취소</button>
                        <button type="button" id="submitForm" name="submitForm" class="btn bg-gradient-primary m-0 ms-2" onclick="alert('준비 중입니다.')">생성하기</button>
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
    <script src="/assets/manage/js/plugins/sortable.min.js"></script>
    <!-- Kanban scripts -->
    <script src="/assets/manage/js/plugins/dragula/dragula.min.js"></script>
    <script src="/assets/manage/js/plugins/jkanban/jkanban.js"></script>
    <script src="/assets/manage/js/plugins/chartjs.min.js"></script>
    <script src="/assets/manage/js/plugins/threejs.js"></script>
    <script src="/assets/manage/js/plugins/orbit-controls.js"></script>
    <!-- Kakao Map -->
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=a09a9506a8284c662059e618d6ec7b42&libraries=services"></script>
    <script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>

    <script>
        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }

        if (document.getElementById('choices-multiple-remove-button')) {
            var element = document.getElementById('choices-multiple-remove-button');
            const example = new Choices(element, {
                removeItemButton: true
            });

            example.setChoices(
                [{
                        value: 'One',
                        label: 'Label One',
                        disabled: true
                    },
                    {
                        value: 'Two',
                        label: 'Label Two',
                        selected: true
                    },
                    {
                        value: 'Three',
                        label: 'Label Three'
                    },
                ],
                'value',
                'label',
                false,
            );
        }

        if (document.querySelector('.datetimepicker')) {
            flatpickr('.datetimepicker', {
                allowInput: true
            }); // flatpickr
        }

    </script>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stayRadio = document.getElementById('stay');
            const activityRadio = document.getElementById('activity');
            const tourRadio = document.getElementById('tour');
            const subCategory = document.getElementById('subCategory');
            const grade = document.getElementById('grade');
            const gradeDescription = document.getElementById('gradeDescription');
            const hotelRadio = document.getElementById('hotel');
            const gradeButton = document.getElementById('partnerGradeSelect'); // 성급 버튼
            const gradeItems = document.querySelectorAll('#partnerGradeList li a'); // 성급 선택 항목들
            const gradeInput = document.getElementById('partnerGrade');

            // 카테고리 변경 시 동작
            document.querySelectorAll('input[name="partnerCategory"]').forEach((radio) => {
                radio.addEventListener('change', function() {
                    if (stayRadio.checked) {
                        subCategory.classList.remove('d-none');

                        // '호텔'이 선택된 경우만 성급을 보여줍니다.
                        if (hotelRadio.checked) {
                            grade.classList.remove('d-none');
                            gradeDescription.classList.remove('d-none');
                        } else {
                            grade.classList.add('d-none'); 
                            gradeDescription.classList.add('d-none');
                        }
                    } else {
                        subCategory.classList.add('d-none');
                        grade.classList.add('d-none');
                        gradeDescription.classList.add('d-none');
                    }
                });
            });

            // 서브카테고리 변경 시 성급을 보여줄지 결정
            document.querySelectorAll('input[name="partnerType"]').forEach((radio) => {
                radio.addEventListener('change', function() {
                    if (hotelRadio.checked) {
                        grade.classList.remove('d-none');
                        gradeDescription.classList.remove('d-none');
                    } else {
                        grade.classList.add('d-none');
                        gradeDescription.classList.add('d-none');
                    }
                });
            });

            // 성급을 선택하면 버튼의 텍스트를 업데이트
            gradeItems.forEach((item) => {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // 링크 기본 동작 방지
                    const selectedGrade = this.textContent; // 선택된 성급 텍스트
                    gradeButton.textContent = selectedGrade; // 버튼 텍스트 변경
                    gradeInput.value = selectedGrade; // 선택된 성급을 숨겨진 필드에 저장
                });
            });
        });


        var latitude = 0;
        var longitude = 0;

        if (document.getElementById('latitude').value) {
            latitude = document.getElementById('latitude').value;
        }
        if (document.getElementById('longitude').value) {
            longitude = document.getElementById('longitude').value;
        }

        var mapContainer = document.getElementById('ft_map');
        var mapOption = {
            center: new kakao.maps.LatLng(latitude, longitude),
            level: 2
        };

        var map = new kakao.maps.Map(mapContainer, mapOption);

        //주소-좌표 변환 객체를 생성
        var geocoder = new kakao.maps.services.Geocoder();

        //마커를 미리 생성
        var marker = new kakao.maps.Marker({
            position: new kakao.maps.LatLng(33.450701, 126.570667),
            map: map
        });

        //마커를 기준으로 가운데 정렬이 될 수 있도록 추가
        var markerPosition = marker.getPosition();
        map.relayout();
        map.setCenter(markerPosition);

        //브라우저가 리사이즈될때 지도 리로드
        $(window).on('resize', function() {
            var markerPosition = marker.getPosition();
            map.relayout();
            map.setCenter(markerPosition)
        });

        kakao.maps.event.addListener(map, 'center_changed', function() {
            var level = map.getLevel();
            var latlng = map.getCenter();
            var message = '<p class=map_gps>위도 : ' + latlng.getLat() + ', 경도 ' + latlng.getLng() + '</p>';

            var resultDiv = document.getElementById('result');
            resultDiv.innerHTML = message;

            $('#latitude').val(latlng.getLat());
            $('#longitude').val(latlng.getLng());

        });

        function searchAddrFromCoords(coords, callback) {
            geocoder.coord2Address(coords.getLng(), coords.getLat(), callback);
        }

        function zipcodeMap(basicInformationForm, zipcode, address1, address2, address3, addressJibeon, mapCity, mapRegion, mapRegionDetail) {
            if (typeof daum === "undefined") {
                alert("다음 우편번호 postcode.v2.js 파일이 로드되지 않았습니다.");
                return false;
            }

            var zip_case = 1; //0이면 레이어, 1이면 페이지에 끼워 넣기, 2이면 새창

            var complete_fn = function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ""; // 최종 주소 변수
                var extraAddr = ""; // 조합형 주소 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === "R") {
                    // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;
                } else {
                    // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if (data.userSelectedType === "R") {
                    //법정동명이 있을 경우 추가한다.
                    if (data.bname !== "") {
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if (data.buildingName !== "") {
                        extraAddr += extraAddr !== "" ? ", " + data.buildingName : data.buildingName;
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    extraAddr = extraAddr !== "" ? " (" + extraAddr + ")" : "";
                }

                // // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
                var of = document.forms[basicInformationForm];

                of [zipcode].value = data.zonecode;
                of [address1].value = fullAddr;
                of [address3].value = extraAddr;
                of [mapRegion].value = data.sido;
                of [mapCity].value = data.sigungu;
                of [mapRegionDetail].value = data.bname;

                if (of [addressJibeon] !== undefined) {
                    of [addressJibeon].value = data.userSelectedType;
                }

                setTimeout(function() {
                    of [address2].focus();
                }, 100);
            };

            switch (zip_case) {
                case 1: // iframe을 이용하여 페이지에 끼워 넣기
                    var daum_pape_id = "daum_juso_page" + zipcode,
                        element_wrap = document.getElementById(daum_pape_id),
                        currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);

                    if (element_wrap == null) {
                        element_wrap = document.createElement("div");
                        element_wrap.setAttribute("id", daum_pape_id);
                        element_wrap.style.cssText =
                            "display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;";
                        element_wrap.innerHTML =
                            '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_daum_juso" alt="접기 버튼">';

                        var address1Input = document.getElementById(address1);
                        address1Input.parentNode.insertBefore(element_wrap, address1Input);

                        // 닫기 버튼 이벤트 리스너 추가
                        document.querySelector("#" + daum_pape_id + " .close_daum_juso").addEventListener("click", function(e) {
                            e.preventDefault();
                            element_wrap.style.display = "none";
                        });
                    }

                    new daum.Postcode({
                        oncomplete: function(data) {
                            complete_fn(data);
                            // iframe을 넣은 element를 안보이게 한다.
                            element_wrap.style.display = "none";
                            // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                            document.body.scrollTop = currentScroll;

                            geocoder.addressSearch(data.address, function(results, status) {
                              
                                // 정상적으로 검색이 완료됐으면
                                if (status === daum.maps.services.Status.OK) {
                                    // 첫번째 결과의 값을 활용
                                    var result = results[0];

                                    // 해당 주소에 대한 좌표를 받아서
                                    var coords = new daum.maps.LatLng(result.y, result.x);

                                    // 지도를 보여준다.
                                    map.relayout();

                                    // 지도 중심을 변경한다.
                                    map.setCenter(coords);

                                    // 좌표값을 넣어준다.
                                    document.getElementById("latitude").value = parseFloat(coords.getLat());
                                    document.getElementById("longitude").value = parseFloat(coords.getLng()); 

                                    // 마커를 결과값으로 받은 위치로 옮긴다.
                                    marker.setPosition(coords);
                                }
                            });
                        },
                        // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
                        // iframe을 넣은 element의 높이값을 조정한다.
                        onresize: function(size) {
                            element_wrap.style.height = size.height + "px";
                        },
                        maxSuggestItems: 10,
                        width: "100%",
                        height: "100%",
                    }).embed(element_wrap);

                    // iframe을 넣은 element를 보이게 한다.
                    element_wrap.style.display = "block";
                    break;
                case 2: // 새창으로 띄우기
                    new daum.Postcode({
                        oncomplete: function(data) {
                            complete_fn(data);
                        },
                    }).open();
                    break;
                default: // iframe을 이용하여 레이어 띄우기
                    var rayer_id = "daum_juso_rayer" + zipcode,
                        element_layer = document.getElementById(rayer_id);

                    if (element_layer == null) {
                        element_layer = document.createElement("div");
                        element_layer.setAttribute("id", rayer_id);
                        element_layer.style.cssText =
                            "display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000";
                        element_layer.innerHTML =
                            '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_daum_juso" alt="닫기 버튼">';

                        document.body.appendChild(element_layer);

                        // 닫기 버튼 이벤트 리스너 추가
                        document.querySelector("#" + rayer_id + " .close_daum_juso").addEventListener("click", function(e) {
                            e.preventDefault();
                            element_layer.style.display = "none";
                        });
                    }

                    new daum.Postcode({
                        oncomplete: function(data) {
                            complete_fn(data);
                            // iframe을 넣은 element를 안보이게 한다.
                            element_layer.style.display = "none";
                        },
                        maxSuggestItems: 10,
                        width: "100%",
                        height: "100%",
                    }).embed(element_layer);

                    // iframe을 넣은 element를 보이게 한다.
                    element_layer.style.display = "block";
            }
        }
    </script>

    <script>
        document.getElementById('submitForm').addEventListener('click', async function() {
            // 양식 데이터 수집
            const formData = {
                partnerIdx: document.getElementById('partnerIdx').value,
                partnerName: document.getElementById('partnerName').value,
                partnerCategory: document.querySelector('input[name="partnerCategory"]:checked')?.id || '',
                partnerType: document.querySelector('input[name="partnerType"]:checked')?.id || '',
                partnerGrade: document.getElementById('partnerGrade').value,
                zipcode: document.getElementById('zipcode').value,
                address1: document.getElementById('address1').value,
                address2: document.getElementById('address2').value,
                address3: document.getElementById('address3').value,
                mapCity: document.getElementById('mapCity').value,
                mapRegion: document.getElementById('mapRegion').value,
                mapRegionDetail: document.getElementById('mapRegionDetail').value,
                latitude: document.getElementById('latitude').value,
                longitude: document.getElementById('longitude').value,
                partnerEmail: document.getElementById('partnerEmail').value,
                partnerPhonenumber: document.getElementById('partnerPhonenumber').value,
                partnerReservationEmail: document.getElementById('partnerReservationEmail').value,
                partnerReservationPhonenumber: document.getElementById('partnerReservationPhonenumber').value,
                partnerManagerEmail: document.getElementById('partnerManagerEmail').value,
                partnerManagerPhonenumber: document.getElementById('partnerManagerPhonenumber').value
            };

            // 유효성 검증
            if (!formData.partnerName || !formData.partnerCategory || !formData.partnerEmail || !formData.partnerPhonenumber || !formData.partnerReservationEmail || !formData.partnerReservationPhonenumber || !formData.partnerManagerEmail || !formData.partnerManagerPhonenumber) {
                alert('필수 항목을 입력하세요.');
                return;
            }

            try {
                // 서버로 POST 요청
                const response = await fetch('/api/partners/draft', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                // 응답 처리
                const result = await response.json();
                if (response.ok) {
                    alert('숙소 정보가 성공적으로 저장되었습니다.');
                    loading.style.display = 'flex'; 
                    window.location.href = '/partner/partner-basic-info?partner_idx=' + '<?= $_GET['partner_idx']; ?>';
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });

        document.getElementById('cancelForm').addEventListener('click', async function() {
            window.location.href = '/partner/partner-select';
        });
    </script>

    <script>
        const cancelRulesDraft = <?= json_encode($cancelRulesDraft ?? []); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // 기존 저장된 규칙이 있을 경우에만 추가
            if (Array.isArray(cancelRulesDraft) && cancelRulesDraft.length > 0) {
                cancelRulesDraft.forEach(rule => {
                    addCancelRuleRow(rule.cancel_rules_day, rule.cancel_rules_time, rule.cancel_rules_percent);
                });
            }
        });

        // 취소 규칙을 추가하는 함수
        function addCancelRuleRow(daysBefore = '', checkinTime = '', refund = '') {
            const cancelRules = document.getElementById('cancelRules').getElementsByTagName('tbody')[0];
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="text-xs">체크인</div>
                        <div class="input-group input-group-sm m-3">
                            <input type="number" class="form-control" name="days_before[]" value="${daysBefore}" placeholder="숫자만 입력" aria-label="daysBeforeCheckin" min="0" required>
                        </div>
                        <div class="text-xs">일 전</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="input-group input-group-sm m-3">
                            <select name="checkin_time[]" class="form-select text-center" required>
                                ${generateTimeOptions(checkinTime)}
                            </select>
                        </div>
                        <div class="text-xs">전까지 취소 시</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="text-xs">환불금액</div>
                        <div class="input-group input-group-sm m-3">
                            <input type="number" class="form-control" name="refund[]" value="${refund}" placeholder="숫자만 입력" aria-label="refund" min="0" max="100" required>
                        </div>
                        <div class="text-xs">% 발생</div>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-cancel-rule-btn" onclick="removeCancelRuleRow(this)">삭제</button>
                </td>
            `;

            cancelRules.appendChild(newRow);
        }

        function removeCancelRuleRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function generateTimeOptions(selectedTime = '') {
            let options = '';
            for (let hour = 0; hour < 24; hour++) {
                for (let minute = 0; minute < 60; minute += 30) {
                    const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                    const isSelected = time === selectedTime ? 'selected' : '';
                    options += `<option value="${time}" ${isSelected}>${time}</option>`;
                }
            }
            return options;
        }

        document.getElementById('addCancelRuleBtn').addEventListener('click', addCancelRuleRow);
    </script>

    <script>
        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }

        var stayBasicImageMeta = [];
        Dropzone.autoDiscover = false;
        var stayBasicImage = document.getElementById('stayBasicImage');
        var stayBasicImageDropzone = new Dropzone(stayBasicImage, {
            url: "/api/images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($stayDraft['stay_idx']) ? $stayDraft['stay_idx'] : 'noid') ?>,
                entity_type: 'stay',
                image_type: 'main'
            },
            addRemoveLinks: false,
            parallelUploads: 1,
            maxFiles: null,
            maxFilesize: 1024 * 10,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            clickable: true,
            init: function() {
                var myDropzone = this;

                myDropzone.on("success", function(file, response) {
                    var parsedResponse = JSON.parse(response)
                    stayBasicImageMeta.push(parsedResponse.uploaded_images[0]);
                    stayBasicImageMetaInput();
                });

                myDropzone.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        myDropzone.removeFile(file);
                        stayBasicImageMeta = stayBasicImageMeta.filter(function(meta) {
                            return meta.image_origin_path !== file.image_origin_path;  // 삭제하려는 파일이 아닌 것들만 남김
                        });
                        stayBasicImageMetaInput();
                    });

                    file.previewElement.appendChild(removeButton);
                });

                myDropzone.on("removedfile", function(file) {
                    stayBasicImageMeta = stayBasicImageMeta.filter(meta => meta.image_origin_path !== file.image_origin_path);
                    stayBasicImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            }
        });

        function stayBasicImageMetaInput() {
            document.getElementById("stayBasicImageMeta").value = JSON.stringify(stayBasicImageMeta);
        }

        new Sortable(stayBasicImage, {
            draggable: ".dz-preview", // Dropzone 썸네일을 드래그 가능하게 설정
            onEnd: function(evt) {
                // 정렬 후 새 순서를 확인할 수 있습니다.
                var orderedImages = [];

                document.querySelectorAll("#stayBasicImageMeta .dz-preview").forEach(function(element) {
                    var imageName = element.querySelector("[data-dz-name]").innerText;

                    var foundImage = bfPublicImageMeta.find(function(image) {
                        return image.image_origin_name === imageName;
                    });

                    // 순서대로 새로운 배열에 추가
                    if (foundImage) {
                        orderedImages.push(foundImage);
                    }
                });

                stayBasicImageMeta = orderedImages;
                stayBasicImageMetaInput();
            }
        });

        var bfPublicImageMeta = [];
        var barrierfreePublicImage = document.getElementById('barrierfreePublicImage');
        var barrierfreePublicImageDropzone = new Dropzone(barrierfreePublicImage, {
            url: "/api/images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($stayDraft['stay_idx']) ? $stayDraft['stay_idx'] : 'noid') ?>,
                entity_type: 'stay',
                image_type: 'barrierfree_public'
            },
            uploadMultiple: true,
            parallelUploads: 1,
            addRemoveLinks: false,
            maxFiles: 20,
            maxFilesize: 1024 * 10,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            clickable: true,
            init: function() {
                var myDropzone = this;

                myDropzone.on("success", function(file, response) {
                    var parsedResponse = JSON.parse(response)
                    bfPublicImageMeta.push(parsedResponse.uploaded_images[0]);
                    bfPublicImageMetaInput();
                });

                myDropzone.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        myDropzone.removeFile(file);
                    });

                    // Dropzone 파일 미리보기 컨테이너에 커스텀 버튼 추가
                    file.previewElement.appendChild(removeButton);
                });

                myDropzone.on("removedfile", function(file) {
                    const filename = file.name;
                    const index = bfPublicImageMeta.findIndex(img => img.filename === filename);
                    if (index !== -1) {
                        bfPublicImageMeta.splice(index, 1);
                    }
                    bfPublicImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    alert("이미 최대 파일 수에 도달했습니다.");

                    this.removeFile(file);
                });
            }
        });

        function bfPublicImageMetaInput() {
            document.getElementById("bfPublicImageMeta").value = JSON.stringify(bfPublicImageMeta);
        }

        new Sortable(barrierfreePublicImage, {
            draggable: ".dz-preview", // Dropzone 썸네일을 드래그 가능하게 설정
            onEnd: function(evt) {
                // 정렬 후 새 순서를 확인할 수 있습니다.
                var orderedImages = [];

                document.querySelectorAll("#barrierfreePublicImage .dz-preview").forEach(function(element) {
                    var imageName = element.querySelector("[data-dz-name]").innerText;

                    var foundImage = bfPublicImageMeta.find(function(image) {
                        return image.image_origin_name === imageName;
                    });

                    // 순서대로 새로운 배열에 추가
                    if (foundImage) {
                        orderedImages.push(foundImage);
                    }
                });

                bfPublicImageMeta = orderedImages;
                bfPublicImageMetaInput();
            }
        });

        var bfRoomImageMeta = [];
        var barrierfreeRoomImage = document.getElementById('barrierfreeRoomImage');
        var barrierfreeRoomImageDropzone = new Dropzone(barrierfreeRoomImage, {
            url: "/api/images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($stayDraft['stay_idx']) ? $stayDraft['stay_idx'] : 'noid') ?>,
                entity_type: 'stay',
                image_type: 'barrierfree_room'
            },
            uploadMultiple: true,
            parallelUploads: 1,
            addRemoveLinks: false,
            maxFiles: 20,
            maxFilesize: 1024 * 10,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            clickable: true,
            init: function() {
                var myDropzone = this;

                myDropzone.on("success", function(file, response) {
                    var parsedResponse = JSON.parse(response)
                    bfRoomImageMeta.push(parsedResponse.uploaded_images[0]);
                    bfRoomImageMetaInput();
                });

                myDropzone.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        myDropzone.removeFile(file);
                    });

                    // Dropzone 파일 미리보기 컨테이너에 커스텀 버튼 추가
                    file.previewElement.appendChild(removeButton);
                });

                myDropzone.on("removedfile", function(file) {
                    const filename = file.name;
                    const index = bfRoomImageMeta.findIndex(img => img.filename === filename);
                    if (index !== -1) {
                        bfRoomImageMeta.splice(index, 1);
                    }
                    bfRoomImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    alert("이미 최대 파일 수에 도달했습니다.");

                    this.removeFile(file);
                });
            }
        });

        function bfRoomImageMetaInput() {
            document.getElementById("bfRoomImageMeta").value = JSON.stringify(bfRoomImageMeta);
        }

        new Sortable(barrierfreeRoomImage, {
            draggable: ".dz-preview", // Dropzone 썸네일을 드래그 가능하게 설정
            onEnd: function(evt) {
                // 정렬 후 새 순서를 확인할 수 있습니다.
                var orderedImages = [];

                document.querySelectorAll("#barrierfreeRoomImage .dz-preview").forEach(function(element) {
                    var imageName = element.querySelector("[data-dz-name]").innerText;

                    var foundImage = bfRoomImageMeta.find(function(image) {
                        return image.image_origin_name === imageName;
                    });

                    // 순서대로 새로운 배열에 추가
                    if (foundImage) {
                        orderedImages.push(foundImage);
                    }
                });

                bfRoomImageMeta = orderedImages;
                bfRoomImageMetaInput();
            }
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkinButton = document.getElementById('checkinButton');
            const checkinItems = document.querySelectorAll('#checkinList li a');
            const checkinInput = document.getElementById('checkin');

            const checkoutButton = document.getElementById('checkoutButton');
            const checkoutItems = document.querySelectorAll('#checkoutList li a');
            const checkoutInput = document.getElementById('checkout');

            checkinItems.forEach((item) => {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    const selectedCheckin = this.textContent;
                    checkinButton.textContent = selectedCheckin;
                    checkinInput.value = selectedCheckin;
                });
            });

            checkoutItems.forEach((item) => {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    const selectedCheckout = this.textContent;
                    checkoutButton.textContent = selectedCheckout;
                    checkoutInput.value = selectedCheckout;
                });
            });
        });
    </script>
</body>
</html>