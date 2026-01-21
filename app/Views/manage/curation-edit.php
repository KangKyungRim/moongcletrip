<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$tags = $data['tags']['searchBadgeTags'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->
<!--   Core JS Files   -->
<script type="text/javascript" src="/assets/manage/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/assets/manage/js/plugins/underscore-min.1.13.7.js"></script>
<script type="text/javascript" src="/assets/manage/js/plugins/moment.2.30.1.js"></script>
<script type="text/javascript" src="/assets/manage/js/commonWeb.js?v=<?= $_ENV['VERSION']; ?>"></script>
<script type="text/javascript" src="/assets/manage/js/common.js?v=<?= $_ENV['VERSION']; ?>"></script>

<body class="g-sidenav-show  bg-gray-100">

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">앱 관리</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">큐레이션 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">큐레이션 수정</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">큐레이션 수정</h6>
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
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <div class="card card-body p-5">
                                <h6 class="mb-0">큐레이션 수정</h6>
                                <hr class="horizontal dark my-3">

                                <!-- 큐레이션 정보 -->
                                <div id="curationBasicForm"></div>
                            </div>
                        </div>

                        <!-- 숙소 정보 -->
                        <div class="addStayWrap mt-5" id="partnerForm"></div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn bg-gradient-info mt-3" id="btnAddStayBtn">+ 숙소 추가</button>
                    </div>
                </div>
            </div>

            <!-- button wrap -->
            <div class="d-flex justify-content-center mt-4">
                <button type="button" id="btnCancelForm" name="cancelForm" class="btn btn-light m-0" title="큐레이션 목록 가기">취소</button>
                <button type="button" id="btnSubmitForm" name="submitForm" class="btn bg-gradient-primary ms-2 mb-0 text-white" title="수정하기">저장하기</button>
            </div>

            <!-- footer -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/bottom.php"; ?>
            <!-- footer -->

        </div>
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

<!-- 큐레이션 정보 -->
<script id="tmplCurationBasicData" type="text/template">
    <input type="hidden" id="txtCurationIdx" value="<@=list.curationIdx @>"/>
    <div class="form-group row align-items-center">
        <label for="txtCurationTitle" class="form-control-label col-sm-2">큐레이션명 <b class="text-danger">*</b></label>
        <div class="col">
            <input type="text" class="form-control" id="txtCurationTitle" name="txtCurationTitle" placeholder="노출되는 큐레이션 이름을 입력해 주세요 (한 줄)" value="<@=list.curationTitle @>">
        </div>
    </div>

    <hr class="horizontal dark my-3">

    <div class="form-group row align-items-center">
        <label for="txtCurationDescription" class="form-control-label col-sm-2">큐레이션 설명 <b class="text-danger">*</b></label>
        <div class="col">
            <input type="text" class="form-control" id="txtCurationDescription" name="txtCurationDescription" placeholder="큐레이션명 하단에 노출되는 설명을 입력해 주세요" value="<@=list.curationDescription @>">
        </div>
    </div>

    <hr class="horizontal dark my-3">

    <div class="form-group row align-items-center">
        <label for="curationVisible" class="form-control-label col-sm-2">노출 기간</label>
        <div class="col">
            <div class="d-flex align-items-center gap-4">
                <input class="form-control w-35" type="date" id="txtCurationVisibleFrom" name="txtCurationVisibleFrom" value="<@=list.curationVisibleFrom @>">
                <span class="d-inline-block text-xs"> 부터 </span>
                <input class="form-control w-35" type="date" id="txtCurationVisibleTo" name="txtCurationVisibleTo" value="<@=list.curationVisibleTo @>">
                <span class="d-inline-block text-xs"> 까지 </span>
            </div>

            <small class="text-xs mt-2 d-block" style="color: #ea0606; font-weight: bold;">
                * 기간을 설정하지 않을 경우 상시로 노출됩니다.
            </small>
        </div>
    </div>
</script>

<!-- 숙소 등록된 정보 -->
<script id="tmplPartnerData" type="text/template">
    <@ _.each(partner, function(d, i) { @>   
        <@ num = i+1; @>     
    <div class="col-12 mt-3">
        <div class="card card-body p-5 stayAccordion" id="stayAccordion<@=num @>" data-curation-item-idx="<@=d.curationItemIdx @>">
            <div class="form-group row align-items-center justify-content-between">
                <h6 class="form-control-label col-sm-2 mb-0">
                    숙소 <@=num @>
                    <button type="button" id="btnDeletePartner<@=num@>" class="btnDeletePartner btn btn-danger btn-sm px-2 py-1 ms-2 my-0">X</button>
                </h6>
                <div class="button cursor-pointer w-auto" data-bs-toggle="collapse" data-bs-target="#collapseStay<@=num @>" aria-expanded="false" aria-controls="collapseStay<@=num @>">
                    <i class="fa-solid fa-chevron-up collapse-open"  aria-hidden="true" style="color:#67748e;"></i>
                    <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
                </div>
            </div>           
    
            <div class="text-sm collapse p-4" id="collapseStay<@=num @>">
                <hr class="horizontal dark my-3">
                <div class="form-group row align-items-center">
                    <label for="txtTargetIdx<@=num @>" class="form-control-label col-sm-2">숙소 번호</label>
                    <div class="col">
                        <input type="number" class="form-control w-50" id="txtTargetIdx<@=num @>" name="txtTargetIdx<@=num @>" placeholder="파트너 인덱스를 입력해 주세요" value="<@=d.targetIdx @>">
                    </div>
                </div> 
                
                <hr class="horizontal dark my-3">
                <div class="form-group row align-items-center">
                    <label for="tagCustomEnter<@=num @>" class="form-control-label col-sm-2">태그 선택</label>
                    <div class="col">
                        <div class="tag-select-box">
                            <!-- S: 태그 checkbox -->
                            <div id="divTag<@=num @>" class="col text-xs d-flex flex-wrap mb-4" style="max-width: 100%;">

                                <@ _.each(tags, function(t, ti) { @>
                                    <div class="form-check" style="flex:0 0 15%; min-width:140px;">
                                        <input class="form-check-input stay-tag-checkbox" type="checkbox" value="<@=t.tag_name @>" 
                                        data-tag-name="<@=t.tag_name @>"
                                        data-tag-machine-name="<@=t.tag_machine_name @>"
                                        id="searchBadge<@=num @>-<@=t.tag_idx @>" name="searchBadge<@=num @>[]"
                                        <@ if (_.some(d.targetTags, { label: t.tag_name })) { print('checked'); } @> >
                                        <label class="custom-control-label" for="searchBadge<@=num @>-<@=t.tag_idx @>"><@=t.tag_name @>
                                    </div>
                                <@ }); @>
                                
                            </div>
                            <!-- E: 태그 checkbox -->
                        </div>

                        <div class="">
                            <!-- S: 태그 직접 입력-->
                            <div class="w-100">
                                <label class="custom-control-label"><i class="fa-regular fa-keyboard"></i> &nbsp;직접 입력</label>
                            </div>
                            <div class="d-flex w-100 form-group my-3 mt-1 align-items-center justify-content-between" style=" border: 1px solid #dee2e6; border-radius: 0.5rem;">
                                <input id="tagCustomEnter<@=num @>" type="text" data-target="#benefitPreview" name="tagCustomEnter<@=num @>" class="form-control my-0" placeholder="원하는 태그를 직접 입력해 보세요" style="display: inline-block; width: 100%; border: 0px;">
                                <button class="h-100 px-4" type="button" id="btnTagCustomEnter<@=num @>" style="background: none; border: 0px; color: #cb0ca0; border-left: 1px solid #dee2e6;">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <!-- E: 태그 직접 입력-->

                            <!-- S: 선택된 tag block -->
                            <div id="tagPreviewWrap<@=num @>" class="tagPreviewWrap mb-4 gap-2 flex-wrap" style="display: flex;">
                                <@ _.each(d.targetTags, function (previewTag, pi) { @>
                                <div class="d-inline-block py-1 px-4 tag-item" style="border-radius: 100px; border: 1px solid #ededed;" 
                                    data-tag-name="<@=previewTag.label @>" 
                                    data-tag-machine-name="<@=previewTag.icon @>" 
                                    data-type="<@=_.isEqual(previewTag.icon,'basic_icon')? 'custom' : 'predefined'@>" 
                                    data-checkbox-id="searchBadge<@=num @>-">
                                    
                                    <span class="d-inline-block text-sm"><@=previewTag.label@></span>
                                    <button type="button" class="remove-tag-btn" style="background: none; border: 0px; color: #959595; margin-left: 5px;">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                </div>
                                <@ }); @>
                            </div>
                            <!-- E: 선택된 tag block -->
                        </div>
                    </div>
                </div> 

                <hr class="horizontal dark my-3">
                <div class="form-group row align-items-center">
                    <label for="txtTargetDescription<@=num @>" class="form-control-label col-sm-2">한 줄 설명 (40자 이내)</label>
                    <div class="col">
                        <input type="text" class="form-control w-50" id="txtTargetDescription<@=num @>" name="txtTargetDescription" placeholder="숙소 어필을 한 줄로 표현해 보세요" maxlength="40"  value="<@=d.targetDescription @>">
                    </div>
                </div> 

                <hr class="horizontal dark my-3">
                <div class="form-group row align-items-center">
                    <label for="targetThumbnail<@=num @>" class="form-control-label col-sm-2">썸네일 <b class="text-danger">*</b></label>
                    <div class="col">
                        <div class="form-control dropzone" id="targetThumbnail<@=num @>"></div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <@ }); @>
</script>

<!-- 숙소 신규 추가 -->
<script id="tmplPartnerForm" type="text/template">
<div class="col-12 mt-3" >
    <div class="card card-body p-5 stayAccordion" id="stayAccordion<@=num@>">
        <div class="form-group row align-items-center justify-content-between">
            <h6 class="form-control-label col-sm-2 mb-0">
                숙소 <@=num @> 
                <button type="button" id="btnDeletePartner<@=num@>" class="btnDeletePartner btn btn-danger btn-sm px-2 py-1 ms-2 my-0">X</button>
            </h6>
            <div class="button cursor-pointer w-auto" data-bs-toggle="collapse" data-bs-target="#collapseStay<@=num @>" aria-expanded="false" aria-controls="collapseStay<@=num @>">
                <i class="fa-solid fa-chevron-up collapse-open"  aria-hidden="true" style="color:#67748e;"></i>
                <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
            </div>
        </div>           

        <div class="text-sm collapse p-4" id="collapseStay<@=num @>">
            <hr class="horizontal dark my-3">
            <div class="form-group row align-items-center">
                <label for="txtTargetIdx<@=num @>" class="form-control-label col-sm-2">숙소 번호</label>
                <div class="col">
                    <input type="number" class="form-control w-50" id="txtTargetIdx<@=num @>" name="txtTargetIdx<@=num @>" placeholder="파트너 인덱스를 입력해 주세요">
                </div>
            </div> 
            
            <hr class="horizontal dark my-3">
            <div class="form-group row align-items-center">
                <label for="tagCustomEnter<@=num @>" class="form-control-label col-sm-2">태그 선택</label>
                <div class="col">
                    <div class="tag-select-box">
                        <!-- S: 태그 checkbox -->
                        <div id="divTag<@=num @>" class="col text-xs d-flex flex-wrap mb-4" style="max-width: 100%;">
                            <@ _.each(tags, function(d, i) { @>
                            <div class="form-check" style="flex: 0 0 15%; min-width: 140px;">
                                <input class="form-check-input stay-tag-checkbox" type="checkbox" value="<@=d.tag_name @>" 
                                data-tag-name="<@=d.tag_name @>"
                                data-tag-machine-name="<@=d.tag_machine_name @>"
                                id="searchBadge<@=num @>-<@=d.tag_idx @>" name="searchBadge<@=num @>[]">
                                <label class="custom-control-label" for="searchBadge<@=num @>-<@=d.tag_idx @>"><@=d.tag_name @>
                            </div>
                            <@ }); @>
                        </div>
                        <!-- E: 태그 checkbox -->
                    </div>

                    <div >
                        <!-- S: 태그 직접 입력-->
                        <div class="w-100">
                            <label class="custom-control-label"><i class="fa-regular fa-keyboard"></i> &nbsp;직접 입력</label>
                        </div>
                        <div class="d-flex w-100 form-group my-3 mt-1 align-items-center justify-content-between" style=" border: 1px solid #dee2e6; border-radius: 0.5rem;">
                            <input id="tagCustomEnter<@=num @>" type="text" data-target="#benefitPreview" name="tagCustomEnter<@=num @>" class="form-control my-0" placeholder="원하는 태그를 직접 입력해 보세요" style="display: inline-block; width: 100%; border: 0px;">
                            <button class="h-100 px-4" type="button" id="btnTagCustomEnter<@=num @>" style="background: none; border: 0px; color: #cb0ca0; border-left: 1px solid #dee2e6;">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <!-- E: 태그 직접 입력-->

                        <!-- S: 선택된 tag block -->
                        <div id="tagPreviewWrap<@=num @>" class="tagPreviewWrap mb-4 gap-2 flex-wrap" style="display: flex;">
                        </div>
                        <!-- E: 선택된 tag block -->
                    </div>
                </div>
            </div> 
            <hr class="horizontal dark my-3">
            <div class="form-group row align-items-center">
                <label for="txtTargetDescription<@=num @>" class="form-control-label col-sm-2">한 줄 설명 (40자 이내)</label>
                <div class="col">
                    <input type="text" class="form-control w-50" id="txtTargetDescription<@=num @>" name="txtTargetDescription" placeholder="숙소 어필을 한 줄로 표현해 보세요" maxlength="40">
                </div>
            </div> 
            <hr class="horizontal dark my-3">
            <div class="form-group row align-items-center">
                <label for="targetThumbnail<@=num @>" class="form-control-label col-sm-2">썸네일 <b class="text-danger">*</b></label>
                <div class="col">
                    <div class="form-control dropzone" id="targetThumbnail<@=num @>"></div>
                </div>
            </div> 
        </div>
    </div>
</div>
</script>
<!-- 미리보기 태그 요소 -->
<script id="tmplTagPreview" type="text/template">
<div class="d-inline-block py-1 px-4 tag-item" style="border-radius: 100px; border: 1px solid #ededed;" 
    data-tag-name="<@=tagName@>" 
    data-tag-machine-name="<@=tagMachineName@>" 
    data-type="<@=type@>" 
    data-checkbox-id="<@=checkboxId@>">
    
    <span class="d-inline-block text-sm"><@=tagName@></span>
    <button type="button" class="remove-tag-btn" style="background: none; border: 0px; color: #959595; margin-left: 5px;">
        <i class="fa-solid fa-circle-xmark"></i>
    </button>
</div>
</script>

<script>
$(function() {
    const TAG_CATALOG = <?= json_encode($tags) ?>;
    //큐레이션
    Global.curationIdx = getParameter('curationIdx');

    let activeUploadCount = 0;  //현재 진행중인 업로드 개수
    Dropzone.autoDiscover = false;

    function initDropzone(element, existingFile = null) {
        // 주석 처리된 코드를 참고해서 API URL을 설정
        const dropzoneUrl = '/api/images/uploadAws';

        new Dropzone(element, {
            url: dropzoneUrl,
            method: "post",
            paramName: "image",
            maxFiles: 1,                // 파일 1개만 허용
            addRemoveLinks: true,       // 파일 삭제 링크 표시
            acceptedFiles: 'image/*',   // 이미지 파일만 허용

            dictDefaultMessage: "썸네일 이미지를 여기에 드래그하거나 클릭하세요",
            dictRemoveFile: "삭제",
            dictMaxFilesExceeded: "썸네일은 1개만 등록할 수 있습니다.",

            // 초기화
            init: function() {
                // 이미 저장된 이미지를 Dropzone에 추가
                if (existingFile && existingFile.key) {
                    $(this.element).data('uploaded-file', existingFile.key);

                    let mockFile = { 
                        name: existingFile.key.split('/').pop(),
                        size: 12345,
                        s3ImageKey: existingFile.key, 
                        accepted: true
                    };
                    
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, existingFile.url); // 이미지 URL로 썸네일 표시
                    this.emit("complete", mockFile);
                    
                    // 이미 파일이 있으니 최대 파일 개수(1개)를 채운 것으로 처리
                    this.files.push(mockFile);
                }

                // 파일이 드롭 or 선택되어 큐에 추가될 때
                this.on('addedfile', function(file) {
                    // 이미지가 에러 상태가 아니거나, 미리보기가 아닐 때만 카운터 증가
                    if (file.status !== Dropzone.ERROR && !file.accepted) {
                        activeUploadCount++;
                        $('#btnSubmitForm').disabled().text('이미지 업로드 중...');

                    }
                });

                // 파일 하나가 성공/실패 여부와 관계없이 전송이 완료되었을 때
                this.on('complete', function(file) {
                    activeUploadCount--;
                    if (activeUploadCount === 0) {
                        $('#btnSubmitForm').enabled().text('생성하기');
                    }
                });
                
                // 파일 업로드 성공 시
                this.on('success', function(file, response) {
                    const fileKey = response.body[0].key;

                    // 성공한 파일 정보를 dropzone 요소에 data로 저장
                    $(this.element).data('uploaded-file', fileKey);
                    
                    file.s3ImageKey = fileKey;

                });

                // 파일 삭제 시
                this.on('removedfile', function(file) {
                    if (file.s3ImageKey) {                        

                        Api.call({
                            url : '/api/images/deleteAws', 
                            data : { key: file.s3ImageKey },
                            loading : false,
                            success : function(res) {
                            }
                        });
                    }
                    
                    $(this.element).data('uploaded-file', null);
                });

                // maxFiles를 초과해서 파일을 올렸을 때, 기존 파일을 자동으로 삭제
                this.on('maxfilesexceeded', function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            }
        });
    }

    //숙소 번호 카운터
    let stayCounter = 1;

    if(Global.curationIdx) {
        fnSearchDetail(true);
    }

    //큐레이터 조회
    function fnSearchDetail(useCurationIdx) {
        var data = {};
        if (useCurationIdx) {
            Api.call({
                url : '/api/manage/getCuration/'+Global.curationIdx,
                success : function(data) {
                    var list = data.body;
                    var partner = list.curationItems;

                    stayCounter = list.curationItems.length;

                    Tmpl.insert('#curationBasicForm', '#tmplCurationBasicData', {list: list});		
                    Tmpl.insert('#partnerForm', '#tmplPartnerData', {
                        partner,
                        tags: TAG_CATALOG 
                    });	

                    partner.forEach(function(itemData, index) {
                        const num = index + 1;
                        const dropzoneElement = document.querySelector('#targetThumbnail' + num);

                        if (dropzoneElement) {
                            // 기존 썸네일 정보를 담을 객체 생성
                            const existingFile = {
                                key: itemData.targetThumbnailPath,
                                // ⚠️ 중요: 이 URL은 S3/CloudFront의 전체 이미지 주소여야 함
                                url: 'https://da8bm4e9mdvmt.cloudfront.net/' + itemData.targetThumbnailPath,
                                size: 12345 // 임의의 사이즈
                            };
                            initDropzone(dropzoneElement, existingFile);
                        }
                    });
                    
                }
            });
        }
    }

    //숙소 추가
    $('#btnAddStayBtn').off('click').on('click', function() {
        stayCounter++;
        Tmpl.append('#partnerForm', '#tmplPartnerForm', {num : stayCounter, tags : TAG_CATALOG});

        const newDropzoneElement = document.querySelector('#stayAccordion' + stayCounter + ' .dropzone');
        if (newDropzoneElement) {
            initDropzone(newDropzoneElement);
        }
        
        renumberStays();
    });

    //숙소 삭제
    $('#partnerForm').on('click', '.btnDeletePartner', function () {

        var $el = $(this);
        var $citem = $el.closest('.stayAccordion');

        var total = $('#partnerForm .stayAccordion').length;
        if (total <= 1) {
            Msg.warn('최소 1개는 유지해야 합니다.');
            return;
        }

        $citem.slideUp(120, function () {
           $citem.remove();
           //숙소(N) 번호 재정렬
           renumberStays();
        });

    });

    //숙소(N) 번호 재정렬
    // ㄴ 라벨 번호 다시 매기기
    function renumberStays() {
        $('#partnerForm .stayAccordion').each(function(i) {
            $(this).find('h6').contents().filter(function() {
                return this.nodeType === 3;
            }).first()[0].nodeValue = '숙소 ' + (i+1) + ' ';
        });
    }

    // 태그 미리보기 담기
    function addTagToPreview($previewWrap, tagName, tagMachineName, type, checkboxId = '') {
        // type: 'predefined' (체크박스) / 'custom' (직접입력)
        
        // 태그 중복
        if ($previewWrap.find(`span:contains('${tagName}')`).length > 0) {
            if (type === 'custom') {
                 Msg.warn('이미 추가된 태그입니다.');
            }
            return false;
        }

        Tmpl.append($previewWrap, '#tmplTagPreview', {
            tagName: tagName, tagMachineName: tagMachineName,
            type: type, checkboxId: checkboxId
        });
        return true;
    }

    // 태그 체크박스 선택/해제 시
    $('#partnerForm').on('change', '.stay-tag-checkbox', function() {
        const $checkbox = $(this);
        const tagName = $checkbox.data('tag-name');
        const tagMachineName = $checkbox.data('tag-machine-name');
        const checkboxId = $checkbox.attr('id');
        
        const $previewWrap = $checkbox.closest('.stayAccordion').find('.tagPreviewWrap');

        if ($checkbox.is(':checked')) {
            // 미리보기 추가
            addTagToPreview($previewWrap, tagName, tagMachineName, 'predefined', checkboxId);
        } else {
            // 미리보기 제거
            $previewWrap.find(`div[data-checkbox-id="${checkboxId}"]`).remove();
        }
    });

    /**
     * 커스텀 태그 입력을 처리하는 함수
     */
    function handleCustomTagAdd($input) {
        const tagName = $input.val().trim();
        if (!tagName) return;

        const $accordion = $input.closest('.stayAccordion');
        let matchingCheckboxFound = false;

        // 커스텀 태그가 체크박스에 있는지 검색
        $accordion.find('.stay-tag-checkbox').each(function() {
            const $checkbox = $(this);

            //커스텀 태그가 체크박스에 있으면, 체크 change 이벤트 발생
            if ($checkbox.data('tag-name') === tagName) {
                matchingCheckboxFound = true;
                
                if (!$checkbox.is(':checked')) {
                    $checkbox.prop('checked', true).trigger('change');
                }
                return false;
            }
        });

        // 커스텀 태그 추가
        if (!matchingCheckboxFound) {
            const $previewWrap = $accordion.find('.tagPreviewWrap');
            addTagToPreview($previewWrap, tagName, 'basic_tag', 'custom');
        }

        // 커스텀 입력창 초기화
        $input.val('');
    }

    // 태그 직접입력 (엔터 키)
    $('#partnerForm').on('keydown', 'input[id^="tagCustomEnter"]', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Form 전송 방지
            handleCustomTagAdd($(this));
        }
    });

    // 태그 직접입력 (+ 버튼 클릭)
    $('#partnerForm').on('click', 'button[id^="btnTagCustomEnter"]', function() {
        const $input = $(this).siblings('input[id^="tagCustomEnter"]');
        handleCustomTagAdd($input);
    });

    // 미리보기 태그 삭제
    $('#partnerForm').on('click', '.remove-tag-btn', function() {
        const $tagItem = $(this).closest('.tag-item');
        const type = $tagItem.data('type');
        const checkboxId = $tagItem.data('checkbox-id');

        // 만약 태그가 체크박스에서 온 것이라면, 원래 체크박스를 해제
        if (type === 'predefined' && checkboxId) {
            $('#' + checkboxId).prop('checked', false);
        }

        // 미리보기에서 태그 제거
        $tagItem.remove();
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////

     //취소하기
    $('#btnCancelForm').off('click').on('click', function() {
        Comm.goTo('/manage/curations');
    });

    //생성하기
    $('#btnSubmitForm').off('click').on('click', function() {
        
        //1. 큐레이션
        var reqCurationTitle        = $('#txtCurationTitle').val();
		var reqCurationDescription  = $('#txtCurationDescription').val();
        var reqCurationVisibleFrom  = $('#txtCurationVisibleFrom').val() || null;
        var reqCurationVisibleTo    = $('#txtCurationVisibleTo').val() || null;
        
        if (_.isEmpty(reqCurationTitle)) {
			Msg.warn('큐레이션명을 입력해주세요.', function() {
                $('#txtCurationTitle').focus();
            });
			return;
		}

        if (_.isEmpty(reqCurationDescription)) {
			Msg.warn('큐레이션 설명을 입력해주세요.', function() {
                $('#txtCurationDescription').focus();
            });
			return;
		}

        if ( reqCurationVisibleFrom && reqCurationVisibleTo &&
            !Dates.isBefore(onlyNum(reqCurationVisibleFrom), onlyNum(reqCurationVisibleTo)) ) {
            Msg.warn('노출 기간 설정을 확인해주세요.');
            return;
        }
        
        // 2. 큐레이션 아이템s
        var curationItems = [];     // 각 숙소 정보를 담을 배열
        var isAllItemsValid = true; // 전체 유효성 검사 플래그

        var $stayAccordions = $('#partnerForm .stayAccordion');

        // 2-1. 최소 1개 숙소
        if ($stayAccordions.length === 0) {
            Msg.warn('숙소를 1개 이상 추가해주세요.');
            return;
        }

        // '숙소 N'
        $stayAccordions.each(function(index) {
            var $item       = $(this);
            var itemNumber  = index + 1;

            var curationItemIdx = $item.data('curation-item-idx');

            // 2-2. '숙소 N' targetIdx
            var partnerIdxInput = $item.find('input[type="number"][name^="txtTargetIdx"]');
            var partnerIdx = partnerIdxInput.val();
            if (_.isEmpty(partnerIdx)) {
                Msg.warn(itemNumber + '번째 숙소의 숙소 번호를 입력해주세요.', function() {
                    const $collapseTarget = $item.find('.collapse');
                    if (!$collapseTarget.hasClass('show')) {
                        $collapseTarget.one('shown.bs.collapse', function () {
                            partnerIdxInput.focus();
                        }).collapse('show');
                    } else {
                        partnerIdxInput.focus();
                    }
                    
                });
                isAllItemsValid = false;
                return false;
            }

            // 2-3. 태그 
            var tags = [];

            $item.find('.tagPreviewWrap .tag-item').each(function() {
                const $tag = $(this);
                tags.push({
                    label: $tag.data('tag-name'),        // 키를 'label'로 변경
                    icon: $tag.data('tag-machine-name')  // 키를 'icon'으로 변경
                });
            });

            // 2-4. 한 줄 설명 (필수x)
            var description = $item.find('input[name^="txtTargetDescription"]').val().trim() || null;

            // 2-5. 썸네일
            const $dropzoneEl = $item.find('.dropzone');
            const uploadedFile = $dropzoneEl.data('uploaded-file');

            if (!uploadedFile) {
                Msg.warn(itemNumber + '번째 숙소의 썸네일을 추가해주세요.');
                isAllItemsValid = false;
                return false; // .each() 루프 중단
            }
            
            // requset curation item
            var itemData    = {
                curationItemIdx     : curationItemIdx,
                targetIdx           : partnerIdx,
                targetDescription   : description,
                targetThumbnailPath : uploadedFile,
                targetTags          : tags
            };  // 각 숙소의 데이터를 담을 객체

            curationItems.push(itemData);
        });

        if (!isAllItemsValid) {
            return; 
        }

        var data = {
            //큐레이션
            curationIdx         : $('#txtCurationIdx').val(),
            curationTitle       : reqCurationTitle,
            curationDescription	: reqCurationDescription,
            curationVisibleFrom	: reqCurationVisibleFrom,
            curationVisibleTo	: reqCurationVisibleTo,
            //큐레이션 아이템
            curationItems       : curationItems
        };

        console.log('수정하기', data);
        

        Api.call({
            url : '/api/manage/putCuration/'+Global.curationIdx, 
            data : data,
            success : function(res) {
                if (res.header.success) {
                    Msg.info('큐레이터 수정에 성공하였습니다. ', function() {
                        Comm.goTo('/manage/curations');
                    });
                } 
            }
        });
    });

});
</script>

</body>
</html>