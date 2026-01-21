<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$realtime_popularity = $data['realtime_popularity'];
$with_child = $data['with_child'];
$around_beach = $data['around_beach'];
$with_nature = $data['with_nature'];
$with_swimming = $data['with_swimming'];
$character_room = $data['character_room'];
$kids_pension = $data['kids_pension'];
$cost_effective = $data['cost_effective'];
$glamping = $data['glamping'];
$hanok_experience = $data['hanok_experience'];
$having_large_room = $data['having_large_room'];

?>           

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">숙소 노출 관리</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">숙소 노출 관리</h6>
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
                    <div class="card p-3 w-50 mx-auto">
                        <div class="card-header px-0">
                            <p class="text-sm mb-0">&nbsp; 각 카테고리에 해당하는 메인 화면 노출 숙소를 설정해 주세요.</p>
                        </div>  

                        <div style="border: 1px solid #dee2e6; border-radius: 10px; padding-top: 1.2rem;">
                            <div class="w-100" >
                                <!-- tab -->
                                <div class="nav-wrapper position-relative end-0 w-100 custom">
                                    <ul class="nav nav-pills nav-fill p-1 bg-white d-inline-block w-100" role="tablist">
                                        <li class="nav-item d-inline-block" role="presentation">
                                            <a class="nav-link mb-0 px-3 py-1 active" data-bs-toggle="tab" href="#main1" role="tab" aria-controls="main1" aria-selected="true">
                                                숙소가 다했다! 실시간 인기 뭉클딜 🔥
                                            </a>
                                        </li>
                                        <li class="nav-item d-inline-block" role="presentation">
                                            <a class="nav-link mb-0 px-3 py-1" data-bs-toggle="tab" href="#main2" role="tab" aria-controls="main2" aria-selected="false" tabindex="-1">
                                                뭉클맘들이 극찬한 숙소
                                            </a>
                                        </li>
                                    </ul>
                                    <hr class="horizontal dark my-3">     
                                </div>                 
                            </div>

                            <div class="tab-content tabChoice p-3" id="nav-tabContent">
                                <div class="tab-pane fade show active category-block" id="main1" role="tabpanel" aria-labelledby="nav-basic-tab" data-list-type="realtime_popularity">
                                    <div class="card-body p-0">
                                        <div>
                                            <div class="form-group row align-items-center">
                                                <label for="1" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="1" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="2" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="2" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="3" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="3" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="4" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="4" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="5" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="5" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="6" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="6" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="7" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="7" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="8" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="8" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="9" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="9" style="width: auto;">
                                                </div>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="10" class="form-control-label col-sm-2">-</label>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="10" style="width: auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- button wrap -->
                                    <div class="d-flex justify-content-center my-4">
                                        <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="main2" role="tabpanel" aria-labelledby="nav-basic-tab">
                                    <div class="card-body p-0">
                                        <div>
                                            <div class="form-group row align-items-center category-block" data-list-type="with_child">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="with_child" class="form-control-label col-sm-2">아이와 함께</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="with_child_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_child_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="with_child_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_child_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="with_child_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_child_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="with_swimming">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="with_swimming" class="form-control-label col-sm-2">수영장이 있는</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="with_swimming_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_swimming_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="with_swimming_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_swimming_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="with_swimming_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_swimming_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="with_nature">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="with_nature" class="form-control-label col-sm-2">자연과 함께</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="with_nature_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_nature_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="with_nature_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_nature_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="with_nature_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="with_nature_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="around_beach">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="around_beach" class="form-control-label col-sm-2">해주욕장 주변</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="around_beach_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="around_beach_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="around_beach_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="around_beach_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="around_beach_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="around_beach_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="character_room">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="character_room" class="form-control-label col-sm-2">캐릭터룸</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="character_room_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="character_room_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="character_room_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="character_room_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="character_room_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="character_room_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="kids_pension">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="kids_pension" class="form-control-label col-sm-2">키즈 펜션</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="kids_pension_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="kids_pension_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="kids_pension_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="kids_pension_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="kids_pension_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="kids_pension_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="cost_effective">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="cost_effective" class="form-control-label col-sm-2">가성비 좋은</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="cost_effective_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="cost_effective_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="cost_effective_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="cost_effective_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="cost_effective_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="cost_effective_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="glamping">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="glamping" class="form-control-label col-sm-2">글램핑</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="glamping_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="glamping_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="glamping_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="glamping_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="glamping_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="glamping_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="hanok_experience">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="hanok_experience" class="form-control-label col-sm-2">한옥 체험</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="hanok_experience_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="hanok_experience_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="hanok_experience_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="hanok_experience_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="hanok_experience_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="hanok_experience_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>

                                            <hr class="horizontal dark my-3 mb-5">

                                            <div class="form-group row align-items-center category-block" data-list-type="having_large_room">
                                                <div class="d-flex align-items-center gap-6">
                                                    <div class="form-group w-70 mb-0">
                                                        <div class="form-group d-flex align-items-center gap-3">
                                                            <label for="having_large_room" class="form-control-label col-sm-2">대형 객실 보유</label>
                                                            <div class="col">
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="having_large_room_1" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="having_large_room_1" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px; margin: 0.5rem 0;">
                                                                    <label for="having_large_room_2" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="having_large_room_2" style="width: auto;">
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                                    <label for="having_large_room_3" class="form-control-label">-</label> 
                                                                    <input type="number" class="form-control" id="having_large_room_3" style="width: auto;">
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>

                                                    <button class="btn bg-gradient-primary ms-2 mb-0 text-white" type="button" title="Save">적용하기</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

    <script>
        // 저장된 데이터 불러오기
        const allInputData = {
            realtime_popularity: <?= json_encode($realtime_popularity); ?>,
            with_child: <?= json_encode($with_child); ?>,
            around_beach: <?= json_encode($around_beach); ?>,
            with_nature: <?= json_encode($with_nature); ?>,
            with_swimming: <?= json_encode($with_swimming); ?>,
            character_room: <?= json_encode($character_room); ?>,
            kids_pension: <?= json_encode($kids_pension); ?>,
            cost_effective: <?= json_encode($cost_effective); ?>,
            glamping: <?= json_encode($glamping); ?>,
            hanok_experience: <?= json_encode($hanok_experience); ?>,
            having_large_room: <?= json_encode($having_large_room); ?>,
        };

        function setAllInputValues(allData) {
            for (const listType in allData) {
                if (Array.isArray(allData[listType])) {
                setInputValues(listType, allData[listType]);
                }
            }
        }

        function setInputValues(listType, valuesArray) {
            const block = document.querySelector(`.category-block[data-list-type="${listType}"]`);
            if (!block) return;

            const inputs = block.querySelectorAll('input[type="number"]');

            inputs.forEach((input, index) => {
                    if (valuesArray[index] !== undefined) {
                    input.value = valuesArray[index];
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            setAllInputValues(allInputData);
        });
    </script>

    <script>
        function bindSaveButtonEvents() {
            document.querySelectorAll('.category-block').forEach((block) => {
                const saveBtn = block.querySelector('button');

                if (!saveBtn || saveBtn.dataset.bound === 'true') return;

                // 기호 입력 방지
                const inputs = block.querySelectorAll('input[type="number"]');
                inputs.forEach(input => {
                    input.addEventListener('keydown', (e) => {
                        const invalidKeys = ['e', 'E', '+', '-', '.'];
                        if (invalidKeys.includes(e.key)) {
                            e.preventDefault();
                        }
                    });

                    // 붙여넣기로 들어오는 경우도 차단
                    input.addEventListener('input', () => {
                        input.value = input.value.replace(/[eE+\-\.]/g, '');
                    });
                });

                saveBtn.addEventListener('click', async () => {
                    const inputs = block.querySelectorAll('input[type="number"]');
                    const data = {};
                    let index = 1;

                    inputs.forEach(input => {
                        const value = parseInt(input.value, 10);
                        if (!isNaN(value)) {
                            data[`partnerIdx${index}`] = value;
                            index++;
                        }
                    });

                    const listType = block.getAttribute('data-list-type') || '기본';
                    data.listType = listType;

                    // 입력 수 검증
                    const requiredCount = listType === 'realtime_popularity' ? 10 : 3;
                    const filledCount = Object.keys(data).filter(key => key.startsWith('partnerIdx')).length;

                    if (filledCount < requiredCount) {
                        alert("순서를 모두 입력해 주세요.");
                        return;
                    }

                    try {
                        const response = await fetch('/api/partners/exposure', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        let result = {};
                        if (response.headers.get('Content-Type')?.includes('application/json')) {
                            result = await response.json();
                        }

                        if (response.ok) {
                            alert('적용되었습니다.');
                            window.location.reload();
                        } else {
                            alert(result.error || '적용 중 문제가 발생했습니다.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('적용 중 오류가 발생했습니다.');
                    }
                });

                // 이미 바인딩했는지 표시
                saveBtn.dataset.bound = 'true';
            });
        }

        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', () => {
                bindSaveButtonEvents(); 
            });
        });
        
        document.addEventListener('DOMContentLoaded', () => {
            bindSaveButtonEvents(); 
        });
    </script>

</body>
</html>