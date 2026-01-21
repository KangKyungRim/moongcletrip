<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$partner = $data['partner'];
$mainImages = $partner->image_paths;

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">숙소 사진 보강</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">숙소 사진 보강</h6>
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
                        <div class="container mt-5">
                            <h4 class="text-center mb-4">숙소 사진 보강</h4>
                            <form method="post" name="curatedImageForm" id="curatedImageForm">
                                <input type="hidden" name="partnerIdx" id="partnerIdx" value="<?= $selectedPartnerIdx; ?>">
                                <input type="hidden" id="mainImageMeta" name="mainImageMeta" value="">

                                <div class="row mb-3 align-items-center">
                                    <div>
                                        <label for="mainImage" class="form-label">메인 사진 업로드</label>
                                        <div class="form-control dropzone" id="mainImage">
                                            <div class="dz-default dz-message">
                                                <button class="dz-button" type="button">여기에 이미지를 드롭하거나 클릭하여 업로드하세요</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit 버튼 -->
                                <div class="text-center">
                                    <button type="button" id="submitForm" class="btn btn-primary">저장하기</button>
                                </div>
                            </form>
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
    <script src="/assets/manage/js/plugins/sortable.min.js"></script>
    <!-- Kanban scripts -->
    <script src="/assets/manage/js/plugins/dragula/dragula.min.js"></script>
    <script src="/assets/manage/js/plugins/jkanban/jkanban.js"></script>
    <script src="/assets/manage/js/plugins/chartjs.min.js"></script>
    <script src="/assets/manage/js/plugins/threejs.js"></script>
    <script src="/assets/manage/js/plugins/orbit-controls.js"></script>

    <script>
        Dropzone.autoDiscover = false;

        var existingMainImages = <?= $mainImages ?? json_encode([]); ?>;

        var mainImageMeta = [];
        var mainImage = document.getElementById('mainImage');
        var mainDropzone = new Dropzone(mainImage, {
            url: "/api/curated-images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($partner->partner_detail_idx) ? $partner->partner_detail_idx : 'noid') ?>,
                entity_type: 'stay',
                image_type: 'basic'
            },
            uploadMultiple: true,
            addRemoveLinks: false,
            parallelUploads: 1,
            maxFiles: 100,
            maxFilesize: 1024 * 30,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            init: function() {
                var myDropzone = this;

                // 이미 저장된 이미지를 Dropzone에 추가
                if (existingMainImages.length > 0) {
                    existingMainImages.forEach(function(image) {
                        var mockFile = image;
                        mockFile.name = image.image_origin_name;
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

                        mainImageMeta.push(image);
                        mainImageMetaInput();
                    });
                }

                myDropzone.on("success", function(file, response) {
                    var parsedResponse = JSON.parse(response);
                    mainImageMeta.push(parsedResponse.uploaded_images[0]);
                    mainImageMetaInput();
                });

                myDropzone.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='dz-remove-btn'>&times;</button>");

                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        myDropzone.removeFile(file);
                    });

                    file.previewElement.appendChild(removeButton);
                });

                myDropzone.on("removedfile", function(file) {
                    const filename = file.name;
                    const index = mainImageMeta.findIndex(img => img.image_origin_name === filename);
                    if (index !== -1) {
                        mainImageMeta.splice(index, 1);
                    }
                    mainImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    alert("이미 최대 파일 수에 도달했습니다.");

                    this.removeFile(file);
                });

            }
        });

        function mainImageMetaInput() {
            document.getElementById("mainImageMeta").value = JSON.stringify(mainImageMeta);
        }

        new Sortable(mainImage, {
            draggable: ".dz-preview",
            onEnd: function(evt) {
                var orderedImages = [];

                document.querySelectorAll("#mainImage .dz-preview").forEach(function(element) {
                    var imageName = element.querySelector("[data-dz-name]").innerText;

                    // 기존 mainImageMeta 배열에서 현재 순서의 이미지 데이터 추출
                    var foundImage = mainImageMeta.find(function(image) {
                        return image.image_origin_name === imageName;
                    });

                    // 순서대로 새로운 배열에 추가
                    if (foundImage) {
                        orderedImages.push(foundImage);
                    }
                });

                mainImageMeta = orderedImages;
                mainImageMetaInput();
            }
        });

        document.getElementById('submitForm').addEventListener('click', async function() {
            const formData = {
                partnerIdx: document.getElementById('partnerIdx').value,
                mainImage: document.getElementById('mainImageMeta').value
            };

            try {
                const response = await fetch('/api/partner/curated-images', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const result = await response.json();
                if (response.ok) {
                    alert('이미지 정보가 성공적으로 저장되었습니다.');
                    window.location.reload();
                } else {
                    alert(result.error || '저장 중 문제가 발생했습니다.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('저장 중 오류가 발생했습니다.');
            }
        });
    </script>

    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

</body>

</html>