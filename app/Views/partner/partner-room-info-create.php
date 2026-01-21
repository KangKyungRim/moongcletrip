<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">객실 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">신규 객실 생성</h6>
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
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <input type="hidden" id="roomBasicImageMeta" name="roomBasicImageMeta" value="">

                            <div class="card card-body p-5">
                                <h6 class="mb-0">객실 정보</h6>
                                <p class="text-sm mb-0">객실 정보를 입력해 주세요.</p>
                                <hr class="horizontal dark my-3">

                                <div>
                                    <div class="form-group row align-items-center">
                                        <label for="roomName" class="form-control-label col-sm-2">객실명 <b class="text-danger">*</b></label>
                                        <div class="col">
                                            <input type="text" class="form-control" id="roomName" name="roomName" placeholder="객실 이름을 입력해 주세요 (최소 2자 ~ 최대 50자)">
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">

                                    <div class="form-group row align-items-center">
                                        <label for="roomOtherNotes" class="form-control-label col-sm-2">객실 정보<b class="text-danger">*</b></label>
                                        <div class="col">
                                            <textarea class="form-control" id="roomOtherNotes" name="roomOtherNotes" rows="7" placeholder="객실 정보를 입력해 주세요"></textarea>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-3">

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">객실 타입</label>
                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                            <?php foreach ($data['tags']['roomtypeTags'] as $key => $roomtype) : ?>
                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                    <input class="form-check-input" type="checkbox" id="roomtype-<?= $roomtype['tag_idx']; ?>" value="roomtype-<?= $roomtype['tag_idx']; ?>" name="roomtype[]">
                                                    <label class="custom-control-label" for="roomtype-<?= $roomtype['tag_idx']; ?>"><?= $roomtype['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-3">

                                    <div class="form-group row align-items-center">
                                        <label for="roomBeds" class="form-control-label col-sm-2">침대 구성 <b class="text-danger">*</b><br>(숫자만 입력)</label>
                                        <div class="col">
                                            <div class="row">
                                                <div class="d-inline-block w-auto">
                                                    <div class="d-flex align-items-center">
                                                        <label class="custom-control-label w-50" for="dormitory_beds">도미토리</label>
                                                        <input class="form-control mx-3" type="numebr" id="dormitory_beds" name="dormitory_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="single_beds">싱글 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="single_beds" name="single_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="super_single_beds">슈퍼 싱글 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="super_single_beds" name="super_single_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="semi_double_beds">세미 더블 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="semi_double_beds" name="semi_double_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="double_beds">더블 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="double_beds" name="double_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                </div>
                                                <div class="d-inline-block w-auto">
                                                    <div class="d-flex align-items-center">
                                                        <label class="custom-control-label w-50" for="queen_beds">퀸 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="queen_beds" name="queen_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="king_beds">킹 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="king_beds" name="king_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="hollywood_twin_beds">할리우드 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="hollywood_twin_beds" name="hollywood_twin_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="double_story_beds">이층 침대</label>
                                                        <input class="form-control mx-3" type="numebr" id="double_story_beds" name="double_story_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="bunk_beds">벙크 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="bunk_beds" name="bunk_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                </div>
                                                <div class="d-inline-block w-auto">
                                                    <div class="d-flex align-items-center">
                                                        <label class="custom-control-label w-50" for="rollaway_beds">간이 침대</label>
                                                        <input class="form-control mx-3" type="numebr" id="rollaway_beds" name="rollaway_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="futon_beds">요이불 세트</label>
                                                        <input class="form-control mx-3" type="numebr" id="futon_beds" name="futon_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="capsule_beds">캡슐 침대</label>
                                                        <input class="form-control mx-3" type="numebr" id="capsule_beds" name="capsule_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="sofa_beds">소파 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="sofa_beds" name="sofa_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="custom-control-label w-50" for="air_beds">에어 베드</label>
                                                        <input class="form-control mx-3" type="numebr" id="air_beds" name="air_beds" value="0" placeholder="0" style="width:6rem;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>      

                                    <hr class="horizontal gray-light my-3">

                                    <div class="form-group row align-items-center">
                                        <label for="roomSize" class="form-control-label col-sm-2">객실 크기 <b class="text-danger">*</b></label>
                                        <div class="col d-flex flex-column gap-3">
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="roomSize" name="roomSize" value="0" placeholder="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">㎡</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">나이 기준 <b class="text-danger">*</b></label>
                                        <div class="col d-flex flex-column gap-3">
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8 d-flex align-items-center justify-content-between">
                                                    <label for="childAge">아동</label>
                                                    <span class="d-inline-block">만</span>
                                                </div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="childAge" name="childAge" value="12" placeholder="12" aria-label="childAge" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">세 이하</div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"><label for="infantMonth">유아</label></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="infantMonth" name="infantMonth" value="36" placeholder="36" aria-label="infantMonth" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">개월 이하</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">

                                    <div class="form-group row align-items-center">
                                        <label for="roomSize" class="form-control-label col-sm-2">이용 인원 <b class="text-danger">*</b></label>
                                        <div class="col d-flex flex-column gap-3">
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"><label for="standardPerson">기준 인원</label></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="standardPerson" name="standardPerson" value="2" placeholder="2" aria-label="standardPerson" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">명</div>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"><label for="maxPerson">최대 인원</label></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="maxPerson" name="maxPerson" value="4" placeholder="4" aria-label="maxPerson" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">명</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">                             

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">기준 인원 이외<br>추가 인원 요금<br>(현장 결제)</label>
                                        <div class="col d-flex flex-column gap-3">
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"><label for="extraAdultFee">성인 1명</label></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="extraAdultFee" name="extraAdultFee" value="0" placeholder="0" aria-label="extraAdultFee" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">원</div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"><label for="extraChildFee">아동 1명</label></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="extraChildFee" name="extraChildFee" value="0" placeholder="0" aria-label="extraChildFee" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">원</div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="text-xs w-8"><label for="extraInfantFee">유아 1명</label></div>
                                                <div class="mx-2">
                                                    <input type="number" class="form-control" id="extraInfantFee" name="extraInfantFee" value="0" placeholder="0" aria-label="extraInfantFee" min="0"  style="width:6rem; flex: none;">
                                                </div>
                                                <div class="text-xs">원</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-3">

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">전망</label>
                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                            <?php foreach ($data['tags']['viewTags'] as $key => $view) : ?>
                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                    <input class="form-check-input" type="checkbox" id="view-<?= $view['tag_idx']; ?>" value="view-<?= $view['tag_idx']; ?>" name="view[]">
                                                    <label class="custom-control-label" for="view-<?= $view['tag_idx']; ?>"><?= $view['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-3">

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">편의시설</label>
                                        <div class="col text-xs d-flex flex-wrap" style="max-width: 80%;">
                                            <?php foreach ($data['tags']['amenityTags'] as $key => $amenity) : ?>
                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                    <input class="form-check-input" type="checkbox" id="amenity-<?= $amenity['tag_idx']; ?>" value="amenity-<?= $amenity['tag_idx']; ?>" name="amenity[]">
                                                    <label class="custom-control-label" for="amenity-<?= $amenity['tag_idx']; ?>"><?= $amenity['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-3">

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">객실 내 베리어프리<br>시설 및 서비스 (해당 시)</label>
                                        <div class="col text-xs d-flex flex-wrap"  style="max-width: 80%;">
                                            <?php foreach ($data['tags']['barrierfreeRoomTags'] as $key => $barrierfreeRoom) : ?>
                                                <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                                    <input class="form-check-input" type="checkbox" id="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>" value="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>" name="barrierfreeRoom[]">
                                                    <label class="custom-control-label" for="barrierfreeRoom-<?= $barrierfreeRoom['tag_idx']; ?>"><?= $barrierfreeRoom['tag_name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-3">

                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-2">객실 이미지 <b class="text-danger">*</b></label>
                                        <div class="col">
                                            <div class="form-control dropzone" id="roomBasicImage"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- button wrap -->
            <div class="d-flex justify-content-center mt-4">
                <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-room-list'">취소</button>
                <button id="submitForm" class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">저장하기</button>
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

    <script>
        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }

        var roomBasicImageMeta = [];
        Dropzone.autoDiscover = false;
        var roomBasicImage = document.getElementById('roomBasicImage');
        var roomBasicImageDropzone = new Dropzone(roomBasicImage, {
            url: "/api/images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($data['selectedPartner']['partner_detail_idx']) ? $data['selectedPartner']['partner_detail_idx'] : 'noid') ?>,
                entity_type: 'room',
                image_type: 'basic'
            },
            addRemoveLinks: false,
            parallelUploads: 1,
            maxFilesize: 1024 * 10,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            init: function() {
                var myDropzone = this;

                myDropzone.on("success", function(file, response) {
                    const parsedResponse = JSON.parse(response);
                    const uploaded = parsedResponse.uploaded_images?.[0];

                    if (uploaded) {
                        uploaded.client_name = file.name;
                        roomBasicImageMeta.push(uploaded);

                        const previewElement = file.previewElement;
                        if (previewElement) {
                            const dzNameElement = previewElement.querySelector("[data-dz-name]");
                            if (dzNameElement) {
                                dzNameElement.innerText = uploaded.image_origin_path; 
                            }
                        }
                    }
                    
                    roomBasicImageMetaInput();
                });

                myDropzone.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Dropzone에서 해당 파일 제거
                        myDropzone.removeFile(file);

                        // 배열에서 해당 파일만 삭제
                        roomBasicImageMeta = roomBasicImageMeta.filter(function(meta) {
                            return meta.image_origin_path !== file.image_origin_path;  // 삭제하려는 파일이 아닌 것들만 남김
                        });

                        roomBasicImageMetaInput();
                    });

                    file.previewElement.appendChild(removeButton);
                });

                myDropzone.on("removedfile", function(file) {
                    // 배열에서 해당 파일만 삭제
                    roomBasicImageMeta = roomBasicImageMeta.filter(meta => meta.image_origin_path !== file.image_origin_path);
                    roomBasicImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            }
        });

        function roomBasicImageMetaInput() {
            document.getElementById("roomBasicImageMeta").value = JSON.stringify(roomBasicImageMeta);
        }

        new Sortable(roomBasicImage, {
            draggable: ".dz-preview", // Dropzone 썸네일을 드래그 가능하게 설정
            onEnd: function(evt) {
                // 정렬 후 새 순서를 확인할 수 있습니다.
                var orderedImages = [];

                document.querySelectorAll("#roomBasicImage .dz-preview").forEach(function(element) {
                    const dzElement = element.querySelector("[data-dz-name]");
                    if (!dzElement) return;

                    const dzName = dzElement.innerText.trim();
                    const dzFileName = dzName.split('/').pop().toLowerCase();

                    const matchIndex = roomBasicImageMeta.findIndex((image, idx) => {
                        if (!image.image_origin_path) return false;
                        const metaFileName = image.image_origin_path.split('/').pop().toLowerCase();
                        return metaFileName === dzFileName;
                    });

                    if (matchIndex > -1) {
                        orderedImages.push(roomBasicImageMeta[matchIndex]);
                    }
                });

                roomBasicImageMeta = orderedImages;
                roomBasicImageMetaInput();
            }
        });

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
        document.getElementById('submitForm').addEventListener('click', async function() {
            const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);

            const formData = {
                partnerIdx: selectedPartnerIdx, // index
                roomName: document.getElementById('roomName').value, // 객실명
                roomOrder: 0, // 객실 순서
                roomOtherNotes: document.getElementById('roomOtherNotes').value, // 객실 정보
                roomBeds: {  // JSON 객체로 보내기
                    dormitory_beds: Number(document.getElementById('dormitory_beds').value),
                    single_beds: Number(document.getElementById('single_beds').value),
                    super_single_beds: Number(document.getElementById('super_single_beds').value),
                    semi_double_beds: Number(document.getElementById('semi_double_beds').value),
                    double_beds: Number(document.getElementById('double_beds').value),
                    queen_beds: Number(document.getElementById('queen_beds').value),
                    king_beds: Number(document.getElementById('king_beds').value),
                    hollywood_twin_beds: Number(document.getElementById('hollywood_twin_beds').value),
                    double_story_beds: Number(document.getElementById('double_story_beds').value),
                    bunk_beds: Number(document.getElementById('bunk_beds').value),
                    rollaway_beds: Number(document.getElementById('rollaway_beds').value),
                    futon_beds: Number(document.getElementById('futon_beds').value),
                    capsule_beds: Number(document.getElementById('capsule_beds').value),
                    sofa_beds: Number(document.getElementById('sofa_beds').value),
                    air_beds: Number(document.getElementById('air_beds').value),
                }, // 침대 구성
                roomSize: Number(document.getElementById('roomSize').value), // 객실 크기
                childAge: Number(document.getElementById('childAge').value), // 아동 나이
                infantMonth: Number(document.getElementById('infantMonth').value), // 유아 개월 수
                standardPerson: Number(document.getElementById('standardPerson').value), // 기준 인원
                maxPerson: Number(document.getElementById('maxPerson').value), // 최대 인원
                extraAdultFee: Number(document.getElementById('extraAdultFee').value), // 성인 추가 요금
                extraChildFee: Number(document.getElementById('extraChildFee').value),  // 아동 추가 요금
                extraInfantFee: Number(document.getElementById('extraInfantFee').value), // 유아 추가 요금
                roomtype: [], // 객실 타입
                view: [], // 전망
                amenity: [], // 편의 시설
                barrierfreeRoom: [], //  객실 내 베리어프리 시설 및 서비스
                roomBasicImage: document.getElementById('roomBasicImageMeta').value // 객실 이미지
            };

            // 유효성 검증
            if (
                !formData.roomName || 
                !formData.roomOtherNotes
            ) {
                // 필수 항목 입력 여부 확인 후, 해당 필드에 포커스
                if (!formData.roomName) {
                    document.getElementById('roomName').focus();
                } else if (!formData.roomOtherNotes) {
                    document.getElementById('roomOtherNotes').focus();  
                }

                alert('필수 항목을 입력해 주세요.');
                return;
            }

            if (!formData.roomBasicImage) {
                alert('객실 이미지를 1장 이상 업로드해 주세요.');
                return;
            }

            // 객실 타입 수집
            document.querySelectorAll('input[name="roomtype[]"]:checked').forEach((checkbox) => {
                formData.roomtype.push(checkbox.value);
            });

            // 전망 수집
            document.querySelectorAll('input[name="view[]"]:checked').forEach((checkbox) => {
                formData.view.push(checkbox.value);
            });

            // 편의 시설 수집
            document.querySelectorAll('input[name="amenity[]"]:checked').forEach((checkbox) => {
                formData.amenity.push(checkbox.value);
            });

            // 베리어프리 수집
            document.querySelectorAll('input[name="barrierfreeRoom[]"]:checked').forEach((checkbox) => {
                formData.barrierfreeRoom.push(checkbox.value);
            });

            try {
                // 서버로 POST 요청
                const response = await fetch('/api/partner/room/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                // 응답이 JSON인지 확인
                const contentType = response.headers.get('content-type');
                let result;

                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    result = await response.text(); // JSON이 아니면 텍스트로 읽기
                    console.warn('응답이 JSON 형식이 아닙니다:', result);
                }

                if (response.ok) {
                    alert('신규 객실이 생성되었습니다.');
                    loading.style.display = 'flex'; 
                    window.location.href = '/partner/partner-room-list';
                } else {
                    alert(result?.error || '객실 생성 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('객실 생성 중 오류 발생:', error);
                alert('객실 생성 중 오류가 발생했습니다.');
            }
        });
    </script>
</body>
</html>