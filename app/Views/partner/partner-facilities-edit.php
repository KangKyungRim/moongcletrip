<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$partnerFacility = $data['partnerFacility'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">부대 시설 수정</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">부대 시설 수정</h6>
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
                            <input type="hidden" id="facilitiesBasicImageMeta" name="facilitiesBasicImageMeta" value="">

                            <div class="card card-body p-5">
                                <p class="text-sm mb-0">부대 시설 정보를 입력해 주세요.</p>
                                <hr class="horizontal dark my-3">

                                <div>
                                    <div class="form-group row align-items-center">
                                        <label for="facilityName" class="form-control-label col-sm-2">시설 이름 <b class="text-danger">*</b></label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="facilityName" name="facilityName" placeholder="이름을 입력해 주세요." value="<?= $partnerFacility->facility_name; ?>">
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">
                                    
                                    <div class="form-group row align-items-center">
                                        <label for="facilityName" class="form-control-label col-sm-2">시설 정보 <b class="text-danger">*</b></label>
                                        <div class="col-md-6">
                                            <div class="form-control dropzone mb-3" id="facilitiesBasicImage"></div>
                                            <textarea class="form-control" id="facilityInfo" name="facilityInfo" rows="7" placeholder="설명을 입력해 주세요."><?= $partnerFacility->facility_description; ?></textarea>
                                        </div>
                                    </div>

                                    <hr class="horizontal gray-light my-3">

                                    <div class="form-group row align-items-center">
                                        <label for="facilitySubInfo" class="form-control-label col-sm-2">추가 정보 <b class="text-danger">*</b></label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="facilitySubInfo" name="facilitySubInfo" placeholder="간단한 추가 정보를 입력해 주세요. (예시 : 오픈 시간, 위치 등)" value="<?= $partnerFacility->facility_sub; ?>">
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
                <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0" onclick="location.href='/partner/partner-facilities'">취소</button>
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
        var existingBasicImages = <?= json_encode(json_decode($partnerFacility->images) ?? []); ?>;

        var facilitiesBasicImageMeta = [];
        Dropzone.autoDiscover = false;
        var facilitiesBasicImage = document.getElementById('facilitiesBasicImage');
        var facilitiesBasicImageDropzone = new Dropzone(facilitiesBasicImage, {
            url: "/api/images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($data['selectedPartner']['partner_detail_idx']) ? $data['selectedPartner']['partner_detail_idx'] : 'noid') ?>,
                entity_type: 'facility',
                image_type: 'basic'
            },
            addRemoveLinks: false,
            parallelUploads: 1,
            maxFilesize: 1024 * 10,
            maxFiles: 1,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            init: function() {
                var myDropzone = this;

                // 이미 저장된 이미지를 Dropzone에 추가
                existingBasicImages.forEach(function(image) {
                    var mockFile = image;
                    mockFile.name = image.image_origin_path;
                    mockFile.size = image.image_origin_size;
                    mockFile.accepted = true;

                    myDropzone.displayExistingFile(mockFile, image.image_origin_path);
                    myDropzone.files.push(mockFile);

                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        myDropzone.removeFile(mockFile);
                    });

                    mockFile.previewElement.appendChild(removeButton);

                    facilitiesBasicImageMeta.push(image);
                    facilitiesBasicImageMetaInput();
                });

                myDropzone.on("success", function(file, response) {
                    const parsedResponse = JSON.parse(response);
                    const uploaded = parsedResponse.uploaded_images?.[0];

                    if (uploaded) {
                        uploaded.client_name = file.name;
                        facilitiesBasicImageMeta.push(uploaded);

                        const previewElement = file.previewElement;
                        if (previewElement) {
                            const dzNameElement = previewElement.querySelector("[data-dz-name]");
                            if (dzNameElement) {
                                dzNameElement.innerText = uploaded.image_origin_path; 
                            }
                        }
                    }

                    facilitiesBasicImageMetaInput();
                });

                myDropzone.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Dropzone에서 해당 파일 제거
                        myDropzone.removeFile(file);

                        // 배열에서 해당 파일만 삭제
                        facilitiesBasicImageMeta = facilitiesBasicImageMeta.filter(function(meta) {
                            return meta.image_origin_path !== file.image_origin_path;  // 삭제하려는 파일이 아닌 것들만 남김
                        });

                        facilitiesBasicImageMetaInput();
                    });

                    file.previewElement.appendChild(removeButton);
                });

                myDropzone.on("removedfile", function(file) {
                    // 배열에서 해당 파일만 삭제
                    facilitiesBasicImageMeta = facilitiesBasicImageMeta.filter(meta => meta.image_origin_path !== file.image_origin_path);
                    facilitiesBasicImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            }
        });

        function facilitiesBasicImageMetaInput() {
            document.getElementById("facilitiesBasicImageMeta").value = JSON.stringify(facilitiesBasicImageMeta);
        }
    </script>

    <script>
        // 저장하기
        document.getElementById('submitForm').addEventListener('click', async function() {

            const formData = {
                partnerIdx: <?= $selectedPartnerIdx; ?>,
                facilityDetailIdx: <?= $partnerFacility->facility_detail_idx; ?>,
                facilityName: document.getElementById('facilityName').value,
                facilitySub: document.getElementById('facilitySubInfo').value,
                facilityDescription: document.getElementById('facilityInfo').value,
                image: document.getElementById('facilitiesBasicImageMeta').value
            }

             // 유효성 검증
             if (!formData.facilityName) {
                // 필수 항목 입력 여부 확인 후, 해당 필드에 포커스
                if (!formData.facilityName) {
                    document.getElementById('facilityName').focus();
                } 

                alert('필수 항목을 입력해 주세요.');
                return;
            }

            if (formData.image === "" || formData.image === "[]" || formData.image === []) {
                alert('부대 시설 이미지를 업로드해 주세요.');
                return;
            }

            try {
                const response = await fetch('/api/partner/edit-facility-detail', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const result = await response.json();

                if (response.ok) {
                    alert('부대 시설 수정이 완료되었습니다.');
                    window.location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        })
    </script>
</body>
</html>