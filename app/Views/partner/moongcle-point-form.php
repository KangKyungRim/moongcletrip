<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$moongclePoint = $data['moongclePoint'];

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">뭉클포인트</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">뭉클포인트</h6>
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
                            <h4 class="text-center mb-4">뭉클포인트</h4>
                            <form method="post" name="moongclePointForm" id="moongclePointForm">
                                <input type="hidden" name="partnerIdx" id="partnerIdx" value="<?= $selectedPartnerIdx; ?>">
                                <input type="hidden" id="pointImageMeta" name="pointImageMeta" value="">

                                <!-- 한 줄 소개 -->
                                <div class="mb-4">
                                    <label for="introduction" class="form-label">뭉클 한 줄 소개</label>
                                    <textarea id="introduction" name="introduction" class="form-control" rows="3" placeholder="한 줄 소개를 입력해주세요" required><?= !empty($moongclePoint->moongcle_point_introduction) ? $moongclePoint->moongcle_point_introduction : ''; ?></textarea>
                                    <div class="invalid-feedback">
                                        한 줄 소개를 입력해주세요.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-title">뭉클 포인트</label>
                                    <div id="moongcle-points">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row mb-3 align-items-center">
                                                    <div>
                                                        <label for="point1" class="form-label">뭉클 포인트 01</label>
                                                        <input type="text" id="point1" name="points[01][title]" class="form-control" placeholder="뭉클 포인트 01" value="<?= !empty($moongclePoint->moongcle_point_1_title) ? $moongclePoint->moongcle_point_1_title : ''; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 align-items-center">
                                                    <div>
                                                        <label for="point2" class="form-label">뭉클 포인트 02</label>
                                                        <input type="text" id="point2" name="points[02][title]" class="form-control" placeholder="뭉클 포인트 02" value="<?= !empty($moongclePoint->moongcle_point_2_title) ? $moongclePoint->moongcle_point_2_title : ''; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 align-items-center">
                                                    <div>
                                                        <label for="point3" class="form-label">뭉클 포인트 03</label>
                                                        <input type="text" id="point3" name="points[03][title]" class="form-control" placeholder="뭉클 포인트 03" value="<?= !empty($moongclePoint->moongcle_point_3_title) ? $moongclePoint->moongcle_point_3_title : ''; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 align-items-center">
                                                    <div>
                                                        <label for="point4" class="form-label">뭉클 포인트 04</label>
                                                        <input type="text" id="point4" name="points[04][title]" class="form-control" placeholder="뭉클 포인트 04" value="<?= !empty($moongclePoint->moongcle_point_4_title) ? $moongclePoint->moongcle_point_4_title : ''; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 align-items-center">
                                                    <div>
                                                        <label for="point5" class="form-label">뭉클 포인트 05</label>
                                                        <input type="text" id="point5" name="points[05][title]" class="form-control" placeholder="뭉클 포인트 05" value="<?= !empty($moongclePoint->moongcle_point_5_title) ? $moongclePoint->moongcle_point_5_title : ''; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row mb-3 align-items-center">
                                                    <div>
                                                        <label for="pointImage" class="form-label">뭉클 포인트 01~05 이미지 업로드</label>
                                                        <div class="form-control dropzone dz-clickable" id="pointImage">
                                                            <div class="dz-default dz-message">
                                                                <button class="dz-button" type="button">여기에 이미지를 드롭하거나 클릭하여 업로드하세요</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit 버튼 -->
                                <div class="text-center">
                                    <button id="submitForm" class="btn btn-primary">제출하기</button>
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

        var existingImages = <?= $moongclePoint->images ?? json_encode([]); ?>;

        var pointImageMeta = [];
        var pointImage = document.getElementById('pointImage');
        var pointDropzone = new Dropzone(pointImage, {
            url: "/api/moongcle-point-images/upload",
            method: "post",
            paramName: "image",
            params: {
                entity_id: <?= json_encode(!empty($selectedPartner->partner_detail_idx) ? $selectedPartner->partner_detail_idx : 'noid') ?>
            },
            uploadMultiple: true,
            addRemoveLinks: false,
            parallelUploads: 1,
            maxFiles: 5,
            maxFilesize: 1024 * 10,
            acceptedFiles: 'image/png,image/jpeg,image/jpg',
            dictDefaultMessage: "여기에 이미지를 드롭하거나 클릭하여 업로드하세요",
            init: function() {
                var myDropzone = this;

                if (existingImages.length > 0) {
                    existingImages.forEach(function(image) {
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

                        pointImageMeta.push(image);
                        pointImageMetaInput();
                    });
                }

                myDropzone.on("success", function(file, response) {
                    var parsedResponse = JSON.parse(response);
                    pointImageMeta.push(parsedResponse.uploaded_images[0]);
                    pointImageMetaInput();
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
                    const index = pointImageMeta.findIndex(img => img.image_origin_name === filename);
                    if (index !== -1) {
                        pointImageMeta.splice(index, 1);
                    }
                    pointImageMetaInput();
                });

                myDropzone.on("maxfilesexceeded", function(file) {
                    alert("이미 최대 파일 수에 도달했습니다.");

                    this.removeFile(file);
                });
            }
        });

        function pointImageMetaInput() {
            document.getElementById("pointImageMeta").value = JSON.stringify(pointImageMeta);
        }

        new Sortable(pointImage, {
            draggable: ".dz-preview",
            onEnd: function(evt) {
                var orderedImages = [];

                document.querySelectorAll("#pointImage .dz-preview").forEach(function(element) {
                    var imageName = element.querySelector("[data-dz-name]").innerText;

                    // 기존 pointImageMeta 배열에서 현재 순서의 이미지 데이터 추출
                    var foundImage = pointImageMeta.find(function(image) {
                        return image.image_origin_name === imageName;
                    });

                    // 순서대로 새로운 배열에 추가
                    if (foundImage) {
                        orderedImages.push(foundImage);
                    }
                });

                pointImageMeta = orderedImages;
                pointImageMetaInput();
            }
        });

        document.getElementById('submitForm').addEventListener('click', async function() {
            const formData = {
                partnerIdx: document.getElementById('partnerIdx').value,
                introduction: document.getElementById('introduction').value,
                pointImage: document.getElementById('pointImageMeta').value,
                point1: document.getElementById('point1').value,
                point2: document.getElementById('point2').value,
                point3: document.getElementById('point3').value,
                point4: document.getElementById('point4').value,
                point5: document.getElementById('point5').value,
            };

            try {
                const response = await fetch('/api/partner/moongcle-point', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const result = await response.json();
                if (response.ok) {
                    alert('이미지 정보가 성공적으로 저장되었습니다.');
                    window.location.href="/partner/partner-moongcle-point";
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