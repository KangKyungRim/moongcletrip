<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

$cityAndDate = $data['cityAndDate']; // 도시별 수요 (전국)
$myCityAndDate = $data['myCityAndDate']; // 도시별 수요 (우리 지역)
$companions = $data['companions']; // 고객 유형별 수요 (전국)
$myRegionCompanions = $data['myRegionCompanions']; // 고객 유형별 수요 (우리 지역)
$tastes = $data['tastes']; // 취향별 수요 (전국)
$myRegionTastes = $data['myRegionTastes']; // 취향별 수요 (우리 지역)
$moongcleTags = $data['moongcleTags'];

?>

<?php

$queryParams = $_GET;
$baseUrl = strtok($_SERVER["REQUEST_URI"], '?');

$currentPage = $data['pagination']['currentPage'];
$totalPages = $data['pagination']['totalPages'];
$perPage = $data['pagination']['perPage'];

function buildPaginationUrl($page, $perPage, $queryParams, $baseUrl)
{
    $queryParams['page'] = $page;
    $queryParams['perPage'] = $perPage;
    return $baseUrl . '?' . http_build_query($queryParams);
}

?>

<style>
    .carousel-indicators {
        bottom: -3rem !important;
    }

    .carousel-indicators .dots {
        background-color: #67748e !important;
        opacity: 0.3 !important;
    }

    .carousel-indicators .active {
        opacity: 1 !important;
    }

    .carousel-control-prev {
        left: -4rem !important;
    }

    .carousel-control-next {
        right: -4rem !important;
    }

    .carousel-control-prev, .carousel-control-next {
        color: #67748e !important;
    }

    .carousel-control-prev, .carousel-control-next i {
        font-size: 2rem !important;
        line-height: 1.0 !important;
    }

    .card.dashboard {
        min-height: 465px;
        height: auto;
    }

    .chart-canvas {
        height: 300px !important;
    }
</style>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show bg-gray-100">

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
						<li class="breadcrumb-item text-sm text-dark active" aria-current="page">대시보드</li>
					</ol>
					<h6 class="font-weight-bolder mb-0"><?= $data['user']->partner_user_name; ?>님, 뭉클 파트너 센터에 오신 걸 환영합니다.</h6>
				</nav>

				<!-- Navigation Bar -->
				<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/navbar.php"; ?>
				<!-- Navigation Bar -->

			</div>
		</nav>
		<!-- End Navbar -->

		<div class="container-fluid py-4">

            <!-- 차트 -->
            <div class="test row">
                <div class="px-4 mb-3">
                    뭉클트립 파트너님의 숙소 수요 통계를 확인하세요.
                </div>

                <div class="col-md-4  mt-md-0 mt-4">
                    <div class="card dashboard z-index-2">
                        <div class="card-header p-3 pb-0 d-flex justify-content-between align-items-center">
                        </div>

                        <div class="card-body p-3 pb-5" style="display: flex; justify-content: center;">
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="width: 18rem;">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active dots" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class="dots"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class="dots"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4" class="dots"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5" class="dots"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6" class="dots"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6" aria-label="Slide 7" class="dots"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7" aria-label="Slide 8" class="dots"></button>
                                </div>
                                <div class="carousel-inner" style="border-radius: 40px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 15%);">
                                    <div class="carousel-item active" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_01.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_02.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_03.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_04.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_05.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_06.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_07.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                    <div class="carousel-item" style="width: 100%;">
                                        <img src="/assets/partner/images/moongcle_gif_08.gif" class="d-block w-100" alt="..." style="width: 100%;">
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
                                    <span class="visually-hidden">Next</span>
                                </button>   
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 도시별 수요 -->
                <div class="col-md-8  mt-md-0 mt-4">
                    <div class="card dashboard z-index-2">
                        <div class="card-header p-3 pb-0 d-flex justify-content-between align-items-center">
                            <h6>도시별 수요</h6>
                            <div class="text-sm">
                                <?php if (empty($myCityAndDate) || count($myCityAndDate) !== 1) : ?>
                                    <span class="text-secondary">전국 지역</span>
                                <?php else : ?>
                                    <span class="text-secondary">우리 지역</span>
                                <?php endif; ?>
                                최근 5개월 수요
                            </div>
                        </div>

                        <?php if (!empty($cityAndDate)) : ?>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="bar-chart1" class="chart-canvas" height="300" width="790" ></canvas>
                                </div>
                            </div>

                            <div class="px-3">
                                <button class="btn bg-gradient-primary text-white text-sm w-100" type="button" onclick="location.href='/partner/moongcleoffers'">
                                 지금 뭉클딜 만들기 <i class="fa-solid fa-chevron-right" style="margin-left: 0.8rem;"></i>
                                </button>
                            </div>
                        <?php else : ?>
                            <div class="card-body p-3 d-flex" style="align-items: center; justify-content: center;">
                                <div class="h-100 text-center text-sm">
                                    <p>데이터가 없습니다.</p>
                                </div>
                            </div>
                        <?php endif; ?> 
                        
                    </div>
                </div>

            </div>

            <div class="test row">
                <!-- 고객 유형별 수요 -->
                <div class="col-md-6 mt-4">
                    <div class="card dashboard z-index-2">
                        <div class="card-header p-3 pb-0 d-flex justify-content-between align-items-center">
                            <h6>고객 유형별 수요</h6>
                            <div class="text-sm w-50 d-flex align-items-center" style="justify-content: flex-end; gap: 1.2rem;">
                                <div class="nav-wrapper position-relative end-0 w-40">
                                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                        <li class="nav-item cursor-pointer" role="presentation">
                                            <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" data-bs-target="#allCustomerType" role="tab" aria-controls="allCustomerType" aria-selected="true">
                                                전국
                                            </a>
                                        </li>
                                        <li class="nav-item cursor-pointer" role="presentation">
                                            <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#ourCustomerType" role="tab" aria-controls="ourCustomerType" aria-selected="false" tabindex="-1">
                                                우리 지역
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade active show" id="allCustomerType" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <?php if (empty($companions['all'])): ?>
                                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -67%);">
                                            데이터가 없습니다.
                                        </div>
                                    <?php else: ?>
                                        <div class="chart">
                                            <canvas id="bar-chart2" class="chart-canvas" height="300" width="790"></canvas>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php
                                    $myRegionKeys = array_keys($myRegionCompanions);
                                    $hasOneRegion = count($myRegionKeys) === 1;
                                    $selectedKey = $hasOneRegion ? $myRegionKeys[0] : null;
                                    $selectedRegionData = $selectedKey ? $myRegionCompanions[$selectedKey] : [];
                                    $hasData = !empty($selectedRegionData);
                                ?>
                                <div class="tab-pane fade" id="ourCustomerType" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <?php if ($hasData): ?>
                                        <div class="chart">
                                            <canvas id="bar-chart3" class="chart-canvas" height="300" width="790" style="height: 300px; !important"></canvas>
                                        </div>
                                        <?php else: ?>
                                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -67%);">
                                            데이터가 없습니다.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-3">
                            <button class="btn bg-gradient-primary text-white text-sm w-100" type="button" onclick="location.href='/partner/moongcleoffers'">
                                지금 뭉클딜 만들기 <i class="fa-solid fa-chevron-right" style="margin-left: 0.8rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 취향별 수요 -->
                <div class="col-md-6 mt-4">
                    <div class="card dashboard z-index-2">
                        <div class="card-header p-3 pb-0 d-flex justify-content-between align-items-center">
                            <h6>취향별 수요</h6>
                            <div class="text-sm w-50 d-flex align-items-center" style="justify-content: flex-end; gap: 1.2rem;">
                                <div class="nav-wrapper position-relative end-0 w-40">
                                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                        <li class="nav-item cursor-pointer" role="presentation">
                                            <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" data-bs-target="#allTaste" role="tab" aria-controls="allTaste" aria-selected="true">
                                                전국
                                            </a>
                                        </li>
                                        <li class="nav-item cursor-pointer" role="presentation">
                                            <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" data-bs-target="#ourTaste" role="tab" aria-controls="ourTaste" aria-selected="false" tabindex="-1">
                                                우리 지역
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="tab-content" id="nav-tabContent"  style="height: 20rem; overflow-y: auto;">
                                <div class="tab-pane fade active show" id="allTaste" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <?php if (empty($tastes['all'])): ?>
                                        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -67%);">
                                            데이터가 없습니다.
                                        </div>
                                    <?php else: ?>
                                        <div>
                                            <ul class="p-0 d-flex flex-wrap gap-3">
                                                <?php
                                                    arsort($tastes['all']);
                                                    $rank = 1;
                                                ?>
                                                <?php foreach ($tastes['all'] as $tasteKey => $count) : ?>
                                                    <li style="list-style: none; background: #F8F8F8; gap: 1.2rem; border-radius: 2rem; width: 48%;" class="p-3 d-flex align-items-center">
                                                        <div class="icon d-flex align-items-center justify-content-center" style="background: #fff; border-radius: 100%; width: 48px; height: 48px;">
                                                            <p class="text-bolder" style="color: #714CDC;"><?= $rank; ?></p>
                                                        </div>
                                                        <div class="right">
                                                            <span class="text-sm">
                                                                <?php
                                                                    if ($tasteKey === 'value_for_money') {
                                                                        echo '가성비 중요';
                                                                    } else {
                                                                        echo isset($moongcleTags[$tasteKey]) ? $moongcleTags[$tasteKey] : $tasteKey;
                                                                    }
                                                                ?>
                                                            </span>
                                                            <p class="text-bolder"><?= number_format($count); ?>명</p>
                                                        </div>
                                                    </li>
                                                    <?php $rank++; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="tab-pane fade" id="ourTaste" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <div>
                                        <?php
                                            $selectedKey = array_key_first($myRegionTastes); 
                                            if ($selectedKey === null || empty($myRegionTastes[$selectedKey])) :
                                        ?>
                                            <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -67%);">
                                                데이터가 없습니다.
                                            </div>
                                        <?php else : ?>
                                            <ul class="p-0 d-flex flex-wrap gap-3">
                                                <?php
                                                    arsort($myRegionTastes[$selectedKey]);
                                                    $rank = 1;
                                                    foreach ($myRegionTastes[$selectedKey] as $tasteKey => $count) :
                                                ?>
                                                    <li style="list-style: none; background: #F8F8F8; gap: 1.2rem; border-radius: 2rem; width: 48%;" class="p-3 d-flex align-items-center">
                                                        <div class="icon d-flex align-items-center justify-content-center" style="background: #fff; border-radius: 100%; width: 48px; height: 48px;">
                                                            <p class="text-bolder" style="color: #714CDC;"><?= $rank; ?></p>
                                                        </div>
                                                        <div class="right">
                                                            <span class="text-sm">
                                                                <?php
                                                                    if ($tasteKey === 'value_for_money') {
                                                                        echo '가성비 중요';
                                                                    } else {
                                                                        echo isset($moongcleTags[$tasteKey]) ? $moongcleTags[$tasteKey] : $tasteKey;
                                                                    }
                                                                ?>
                                                            </span>
                                                            <p class="text-bolder"><?= number_format($count); ?>명</p>
                                                        </div>
                                                    </li>
                                                <?php
                                                        $rank++;
                                                    endforeach;
                                                ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-3">
                            <button class="btn bg-gradient-primary text-white text-sm w-100" type="button" onclick="location.href='/partner/moongcleoffers'">
                                지금 뭉클딜 만들기 <i class="fa-solid fa-chevron-right" style="margin-left: 0.8rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
                              

                <!-- 버튼 -->
                <div class="py-1">
                    <div class="my-5 d-flex align-items-center justify-content-between p-4" style="border-radius: 1rem; background: #39416e;">
                        <div>
                            <p class="text-bolder" style="color: #fff;">수요 데이터를 활용한 뭉클딜을 발송해 보세요</p>
                            <span class="text-sm" style="color: #fff;">고객 취향과 수요에 맞는 맞춤형 딜로 객실 점유율을 높여보세요</span>
                        </div>
                        <button type="button" class="btn btn-outline-active mb-0 text-sm" style="background-color: #fff;" onclick="location.href='/partner/moongcleoffers'">
                            뭉클딜 발송하기
                        </button>
                    </div>
                </div>

                <!-- 리스트 -->
                <div class="col">
                    <div class="card z-index-2">
                        <div class="card-header p-3 pb-0 d-flex justify-content-between align-items-center">
                            <h6>진행 중인 뭉클딜</h6>
                            <div class="text-sm w-50 d-flex align-items-center" style="justify-content: flex-end; gap: 1.2rem;">
                                <button type="button" class="btn btn-outline-active mb-0 p-2" style="background-color: #fff;" onclick="window.location.reload();">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <colgroup>
                                        <col style="width: 5%;">
                                        <col style="width: 10%;">
                                        <col style="width: 10%;">
                                        <col style="width: 20%;">
                                        <col style="width: 15%;">
                                        <col style="width: 15%;">
                                        <col style="width: 12%;">
                                        <col style="width: 12%;">
                                        <col style="width: 15%;">
                                        <col style="width: 15%;">
                                        <col style="width: 6%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                            <th th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정일</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer" onclick="updateSorting('createdAt')">생성일
                                                <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="createdAt"></span>
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">뭉클딜 제안명</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">상태</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">기반 요금제</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer" onclick="updateSorting('saleStartDate')">진행 상황
                                                <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="saleStartDate"></span>
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer" onclick="updateSorting('stayStartDate')">투숙 기간
                                                <span class="d-inline-block sortIcon ms-2" style="position: relative; z-index: 1000; width: 10%; height: 16px;" data-column="stayStartDate"></span>
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수요 공략 태그</th> 
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">포함 혜택</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">수정</th>
                                        </tr>
                                    </thead>
                                    <tbody id="moongcleoffersAccordion">
                                        <?php if (count($data['moongcleoffers']) !== 0) : ?>
                                            <?php foreach ($data['moongcleoffers'] as $moongcleoffer) : ?>
                                                <tr>
                                                    <td class="font-weight-bold my-2 text-xs">
                                                        <div 
                                                            class="button cursor-pointer fw-bold" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#moreTags<?= $moongcleoffer->stay_moongcleoffer_idx ?>" 
                                                            aria-expanded="false" 
                                                            aria-controls="moreTags<?= $moongcleoffer->stay_moongcleoffer_idx ?>">
                                                            <i class="fa-solid fa-chevron-down collapse-close" aria-hidden="true" style="color:#67748e;"></i>
                                                            <i class="fa-solid fa-chevron-up collapse-open" aria-hidden="true" style="color:#67748e;"></i>
                                                        </div>
                                                    </td>
                                                    <td class="font-weight-bold my-2 text-xs">
                                                        <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                            <?= $moongcleoffer->updated_at; ?>
                                                        </span>                                                                                                            
                                                    </td>    
                                                    <td class="font-weight-bold my-2 text-xs">
                                                        <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                            <?= $moongcleoffer->created_at; ?>
                                                        </span>                                                                                                            
                                                    </td>    
                                                    <td class="font-weight-bold my-2 text-xs">
                                                        <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                            <?php echo $moongcleoffer->stay_moongcleoffer_title ? $moongcleoffer->stay_moongcleoffer_title : "이름 없음"; ?>
                                                        </span>                                                                                                            
                                                    </td>                                                
                                                    <td class="font-weight-bold">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <?php if ($moongcleoffer->stay_moongcleoffer_status === "enabled") : ?>
                                                                <span class="badge badge-dot py-0">
                                                                    <i class="bg-success"></i>
                                                                    <span class="text-dark text-xs">활성화</span>
                                                                </span>
                                                            <?php else : ?>
                                                                <span class="badge badge-dot py-0">
                                                                    <i class="bg-danger"></i>
                                                                    <span class="text-dark text-xs">비활성화</span>
                                                                </span>
                                                            <?php endif; ?>

                                                            <div class="form-check form-switch d-inline-block mb-0 ms-3">
                                                                <input class="form-check-input moongcleoffer-active-toggle" type="checkbox" id="checkMooncleofferActive-<?= $moongcleoffer->stay_moongcleoffer_idx; ?>" data-moongcleoffer-idx="<?= $moongcleoffer->stay_moongcleoffer_idx; ?>" <?= $moongcleoffer->stay_moongcleoffer_status === 'enabled' ? 'checked' : ''; ?>>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                    <td class="font-weight-bold my-2 text-xs">
                                                        <span class="d-inline-block" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                            <?= $moongcleoffer->rateplan_name; ?>
                                                        </span>     
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        <span class="my-2 text-xs">
                                                            <?php 
                                                                $today = strtotime(date('Y-m-d'));
                                                                $start_date = $moongcleoffer->sale_start_date ? strtotime($moongcleoffer->sale_start_date) : null;
                                                                $end_date = $moongcleoffer->sale_end_date ? strtotime($moongcleoffer->sale_end_date) : null;
                                                            ?>

                                                            <?php if ($start_date === null || $end_date === null) : ?>                                                            
                                                                <span class="badge badge-info text-xs" style="background-color: rgb(231 251 255);">상시</span>
                                                            <?php elseif ($today < $start_date) : ?>
                                                                <span class="badge badge-primary text-xs" style="background-color: rgb(255 232 250); background-color: rgb(255 232 250); background-color: rgb(255 232 250); word-wrap: break-word; word-break: break-word; white-space: normal; line-height: 1.4;"><?= date('Y-m-d', $start_date); ?><br>발송 예정</span>
                                                            <?php elseif ($today >= $start_date && $today <= $end_date) : ?> 
                                                                <span class="badge badge-success text-xs" style="background-color: rgb(237 255 215);">발송 중</span>
                                                            <?php elseif ($today > $end_date) : ?>
                                                                <?php if ($moongcleoffer->stay_moongcleoffer_status === "enabled") : ?>
                                                                    <span class="badge badge-secondary text-xs" style="background-color: rgb(245 245 245);">종료</span>
                                                                <?php elseif ($moongcleoffer->stay_moongcleoffer_status === "disabled") : ?>
                                                                    <span class="badge badge-secondary text-xs" style="background-color: rgb(245 245 245);">중지됨</span>
                                                                <?php endif; ?>        
                                                            <?php endif; ?>
                                                        </span>
                                                    </td>
                                                    <td class="font-weight-bold p-1">
                                                        <span class="my-2 text-xs"  style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                            <?php if ($moongcleoffer->stay_start_date === null || $moongcleoffer->stay_end_date === null) : ?>
                                                                상시
                                                            <?php else : ?>
                                                                <?= date('Y-m-d', strtotime($moongcleoffer->stay_start_date)); ?> ~ <?= date('Y-m-d', strtotime($moongcleoffer->stay_end_date)); ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div 
                                                            class="my-2 text-xs" 
                                                            style="white-space: normal; word-wrap: keep-all;">
                                                            <?php 
                                                                $tag_count = count($moongcleoffer->tag_list);
                                                                echo '<span>';
                                                                if ($tag_count > 1) {
                                                                    echo '<span class="ellipsis" style="display: block;">';
                                                                    
                                                                    $first_tag = $moongcleoffer->tag_list[0]['tag_name'];
                                                                    if ($first_tag === "커플") {
                                                                        $first_tag = "연인과";
                                                                    }
                                                                    echo '#' . $first_tag . '... ' . '</span> ';
                                                                } else {
                                                                    foreach ($moongcleoffer->tag_list as $tag) {
                                                                        if ($tag['tag_name'] === "커플") {
                                                                            $tag['tag_name'] = "연인과";
                                                                        }
                                                                        echo '<span style="display:block;">#' . $tag['tag_name'] . '</span>';
                                                                    }
                                                                }
                                                                echo '</span>';
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div 
                                                            class="my-2 text-xs" 
                                                            style="white-space: normal; word-wrap: break-word;">
                                                            <?php 
                                                                $benefits_count = count($moongcleoffer->benefits);
                                                                echo '<span>';
                                                                
                                                                if ($benefits_count > 1) {
                                                                    echo '<span class="ellipsis" style="display: block;">' . $moongcleoffer->benefits[0] . '... ' . '</span> ';
                                                                } else {
                                                                    $first = true;
                                                                    foreach ($moongcleoffer->benefits as $benefit) {
                                                                        if (!$first) {
                                                                            echo ',<br>';
                                                                        }
                                                                        echo $benefit;
                                                                        $first = false;
                                                                    }
                                                                }
                                                                
                                                                echo '</span>';
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        <div class="cursor-pointer">
                                                            <a href="/partner/moongcleoffers/edit?stayMoongcleofferIdx=<?= $moongcleoffer->stay_moongcleoffer_idx ?>">
                                                                <i class="fa-solid fa-pen" style="color:#67748e;"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- 상세 view -->
                                                <tr class="border-0 tags">
                                                    <td colspan="11" class="p-0 border-0">
                                                        <div class="text-sm p-4 collapse" id="moreTags<?= $moongcleoffer->stay_moongcleoffer_idx ?>" data-bs-parent="#moongcleoffersAccordion" style="border-top: 1px solid rgb(233, 236, 239);">
                                                            <div class="p-5 rounded-3 text-start" style="background: #F5F5F5;">
                                                                <div class="mb-3 pb-3">
                                                                    <h4 class="text-sm" style="color:#3A416F;">수요 공략 태그</h4>
                                                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                                                        <?php foreach ($moongcleoffer->tag_list as $tag) : ?>
                                                                            <span class="d-inline-block text-xs" style="color: #344767;">#<?= $tag->tag_name; ?> </span>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </div>  

                                                                <hr class="horizontal dark my-3">
                                                            
                                                                <div>
                                                                    <h4 class="text-sm" style="color:#3A416F;">포함 혜택</h4>
                                                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                                                        <?php 
                                                                            $benefits = array_map(fn($benefit) => "<span class='d-inline-block text-xs' style='color: #344767;'>$benefit</span>", $moongcleoffer->benefits);
                                                                            echo implode(' | ', $benefits);
                                                                        ?>
                                                                    </div>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="11" class="text-center py-10">등록된 뭉클딜이 없습니다.<br>신규 뭉클딜을 생성해 주세요.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>

                                <!-- pagination -->
                                <?php if ($totalPages > 1) : ?>
                                    <nav>
                                        <ul class="pagination justify-content-center pb-2">
                                            <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= buildPaginationUrl($currentPage - 1, $perPage, $queryParams, $baseUrl) ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>

                                            <?php
                                            $startPage = max(1, $currentPage - 5);
                                            $endPage = min($totalPages, $currentPage + 5);

                                            if ($startPage > 1) {
                                                echo '<li class="page-item"><a class="page-link" href="' . buildPaginationUrl(1, $perPage, $queryParams, $baseUrl) . '">1</a></li>';
                                                if ($startPage > 2) {
                                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                                }
                                            }

                                            for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                    <a class="page-link" style="<?= ($i == $currentPage) ? 'color: #fff;' : '' ?>" href="<?= buildPaginationUrl($i, $perPage, $queryParams, $baseUrl) ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($endPage < $totalPages) {
                                                if ($endPage < $totalPages - 1) {
                                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                                }
                                                echo '<li class="page-item"><a class="page-link" href="' . buildPaginationUrl($totalPages, $perPage, $queryParams, $baseUrl) . '">' . $totalPages . '</a></li>';
                                            } ?>

                                            <li class="page-item <?= ($currentPage == $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1, $perPage, $queryParams, $baseUrl) ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
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
	<!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->
	<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

    <script>
        // 도시별 수요
        var canvas1 = document.getElementById("bar-chart1");

        if (canvas1) {
            const ctx1 = canvas1.getContext("2d");

             // 전국
            const cityAndDate = <?= json_encode($cityAndDate) ?>;

            // 우리 지역
            const myCityAndDate = <?= json_encode($myCityAndDate) ?>;

            // 우리 지역 없을 땐 전국으로
            const cityAndDateData = Object.keys(myCityAndDate).length === 1
            ? myCityAndDate
            : cityAndDate;

            const rawData = cityAndDateData;

            const monthSet = new Set();

            Object.values(rawData).forEach(region => {
                Object.keys(region).forEach(key => {
                    monthSet.add(key);
                });
            });

            const sortedKeys = Array.from(monthSet).sort((a, b) => {
                if (a === 'always') return 1;
                if (b === 'always') return -1;
                return a.localeCompare(b);
            });

            // 라벨 만들기
            const labels = sortedKeys.map(key =>
                key === 'always' ? '날짜 선택 없음' : `${parseInt(key.slice(5))}월`
            );

            const monthlyTotals = sortedKeys.map(monthKey => {
                return Object.values(rawData).reduce((sum, cityData) => {
                    return sum + (cityData[monthKey] || 0);
                }, 0);
            });

            const datasets = [{
                label: "전체 합계",
                data: monthlyTotals,
                backgroundColor: '#3A416F',
                borderWidth: 1,
                maxBarThickness: 35,
                borderRadius: 4,
            }];

            const cityDetailsPerMonth = sortedKeys.map(monthKey => {
            const cityDetails = {};
            Object.entries(rawData).forEach(([city, cityData]) => {
                const value = cityData[monthKey] || 0;
                    if (value > 0) {
                    cityDetails[city] = value;
                    }
                });
                return cityDetails;
            });

            const allValues = datasets.flatMap(d => d.data);
            const maxValue = Math.ceil(Math.max(...allValues) * 1.1);

            const cityNameMap = {
                seoul: '서울',
                jeju: '제주',
                busan: '부산',
                incheon: '인천',
                gyeonggi: '경기',
                gangwon: '강원',
                chungcheongnamdo: '충남',
                chungcheongbukdo: '충북',
                jeonbukstate: '전북',
                jeollanamdo: '전남',
                gyeongsangbukdo: '경북',
                gyeongsangnamdo: '경남',
                gapyeong: '가평',
                yeosu: '여수',
                sokcho: '속초',
                yangyang: '양양',
                gangneung: '강릉',
                pohang: '포항',
                gyeongju: '경주',
                ulsan: '울산',
                jeonju: '전주',
                daejeon: '대전',
                pyeongchang: '평창',
                geoje: '거제',
                daegu: '대구',
                namhae: '남해',
            };

            new Chart(ctx1, {
                type: "bar",
                data: {
                    labels,
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: rawData === cityAndDate ? {
                            enabled: false
                        } : {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const cityData = cityDetailsPerMonth[index];

                                    if (!cityData || Object.keys(cityData).length === 0) {
                                        return ['데이터 없음'];
                                    }

                                    return Object.entries(cityData).map(([city, value]) => {
                                        const formattedValue = new Intl.NumberFormat('ko-KR').format(value);
                                        return `${formattedValue}명`;
                                });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            beginAtZero: true,
                            max: maxValue,
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#9ca2b7',
                                stepSize: 500,
                                precision: 0,
                                callback: function(value) {
                                    return new Intl.NumberFormat().format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: true,
                                drawTicks: true,
                            },
                            ticks: {
                                display: true,
                                color: '#9ca2b7',
                                padding: 10
                            }
                        },
                    },
                },
            });
        }

        // 고객 유형별 수요 (전국)
        var canvas2 = document.getElementById("bar-chart2");

        if (canvas2) {
            const ctx2 = canvas2.getContext("2d");
            
            // 높은순으로 6개까지만
            const rawCompanions = <?= json_encode($companions['all']) ?>;

            const companionKeyMap = {
                alone: '혼자',
                family: '가족과',
                pet_friendly: '반려동물과',
                with_parents: '부모님과',
                with_colleagues: '직장동료',
                with_friends: '친구와',
                couple: '연인과',
                with_spouse: '배우자와',
                group_MT_workshop: '단체',
                with_kids: '아이와',
            };

            const sortedTop6 = Object.entries(rawCompanions)
                .sort((a, b) => b[1] - a[1])
                .slice(0, 6);

            const companionLabels = sortedTop6.map(([key]) => {
                const [type, count] = key.split(':');
                const label = companionKeyMap[type];

                if (!label) return key;

                // 혼자, 연인관, 부모님과, 배우자와, 친구와 -> 명 수 없이
                if (['alone', 'couple', 'with_parents', 'with_spouse'].includes(type)) {
                    return label;
                }

                return `${label} / 총: ${count}명`;
            });

            const companionsData = sortedTop6.map(([, value]) => value);

            const maxValueCompanion = companionsData.length > 0 ? Math.ceil(Math.max(...companionsData) * 1.1) : 10; 

            new Chart(ctx2, {
                type: "bar",
                data: {
                    labels: companionLabels,
                    datasets: [{
                    label: "",
                    weight: 5,
                    borderWidth: 0,
                    borderRadius: 4,
                    backgroundColor: '#3A416F',
                    data: companionsData,
                    fill: false,
                    maxBarThickness: 35
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    const formattedValue = new Intl.NumberFormat('ko-KR', { maximumFractionDigits: 0 }).format(value);
                                    return `${label}: ${formattedValue}명`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: maxValueCompanion,
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#9ca2b7',
                                stepSize: 500,
                                precision: 0,
                                callback: function(value) {
                                    return new Intl.NumberFormat().format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: true,
                                drawTicks: true,
                            },
                            ticks: {
                                display: true,
                                color: '#9ca2b7',
                                padding: 10
                            }
                        },
                    },
                },
            });
        } 

        // 고객 유형별 수요 (우리 지역)
        var canvas3 = document.getElementById("bar-chart3");

        if (canvas3) {
            const ctx3 = canvas3.getContext("2d");

            // 높은순으로 6개까지만
            const rawMyRegionCompanionsAll = <?= json_encode($myRegionCompanions) ?>;

            // key
            const keys = Object.keys(rawMyRegionCompanionsAll);
            const selectedKey = keys.length === 1 ? keys[0] : null;

            const rawMyRegionCompanions = selectedKey ? rawMyRegionCompanionsAll[selectedKey] : {};

            const myCompanionKeyMap = {
                alone: '혼자',
                family: '가족과',
                pet_friendly: '반려동물과',
                with_parents: '부모님과',
                with_colleagues: '직장동료',
                with_friends: '친구와',
                couple: '연인과',
                with_spouse: '배우자와',
                group_MT_workshop: '단체',
                with_kids: '아이와',
            };

            const mySortedTop6 = Object.entries(rawMyRegionCompanions)
                .sort((a, b) => b[1] - a[1])
                .slice(0, 6);

            const myCompanionLabels = mySortedTop6.map(([key]) => {
                const [type, count] = key.split(':');
                const label = myCompanionKeyMap[type];

                if (!label) return key;

                // 혼자, 연인관, 부모님과, 배우자와 -> 명 수 없이
                if (['alone', 'couple', 'with_parents', 'with_spouse'].includes(type)) {
                    return label;
                }

                return `${label} ${count}명`;
            });

            const myCompanionsData = mySortedTop6.map(([, value]) => value);

            const maxValueMyCompanion = myCompanionsData.length > 0 ? Math.ceil(Math.max(...myCompanionsData) * 1.1) : 10; 

            new Chart(ctx3, {
                type: "bar",
                data: {
                    labels: myCompanionLabels,
                    datasets: [{
                        label: "",
                        weight: 5,
                        borderWidth: 0,
                        borderRadius: 4,
                        backgroundColor: '#3A416F',
                        data: myCompanionsData,
                        fill: false,
                        maxBarThickness: 35
                    }],
                },
                options: {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    const formattedValue = new Intl.NumberFormat('ko-KR', { maximumFractionDigits: 0 }).format(value);
                                    return `${label}: ${formattedValue}명`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: maxValueMyCompanion,
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#9ca2b7',
                                stepSize: 500,
                                precision: 0,
                                callback: function(value) {
                                    return new Intl.NumberFormat().format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: true,
                                drawTicks: true,
                            },
                            ticks: {
                                display: true,
                                color: '#9ca2b7',
                                padding: 10
                            }
                        },
                    },
                },
            });
        }    
    </script>

    <script>
        // sorting
        window.onload = function() {
            let urlParams = new URLSearchParams(window.location.search);
            let sortColumn = urlParams.get("sortColumn");
            let sortOrder = urlParams.get("sortOrder");

            document.querySelectorAll(".sortIcon").forEach(icon => {
                let column = icon.getAttribute("data-column");

                icon.innerHTML = `
                    <span class="d-inline-block" style="position: absolute; left: 0; top: 0; font-size: 0.8rem;">
                        <i class="fa-solid fa-sort-up sort-up"></i>
                    </span>
                    <span class="d-inline-block" style="position: absolute; left: 0; top: 2px; font-size: 0.8rem;">
                        <i class="fa-solid fa-sort-down sort-down"></i>
                    </span>
                `;

                if (column === sortColumn) {
                    if (sortOrder === "ASC") {
                        icon.querySelector(".sort-up").style.color = "#656565"; 
                        icon.querySelector(".sort-down").style.color = "#ccc";
                    } else if (sortOrder === "DESC") {
                        icon.querySelector(".sort-up").style.color = "#ccc";
                        icon.querySelector(".sort-down").style.color = "#656565";
                    }
                } else {
                    icon.querySelector(".sort-up").style.color = "#ccc";
                    icon.querySelector(".sort-down").style.color = "#ccc"; 
                }
            });
        };

        function updateSorting(sortColumn) {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            let currentSortOrder = params.get('sortOrder') || 'ASC';
            let newSortOrder = (currentSortOrder === 'ASC') ? 'DESC' : 'ASC';

            params.set('sortColumn', sortColumn);
            params.set('sortOrder', newSortOrder);

            if (params.get("page") !== "1") {
                params.set("page", "1");
            }

            window.location.href = url.pathname + "?" + params.toString();
        }
    </script>

    <script>
        // 상태 변경
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.moongcleoffer-active-toggle').forEach((checkbox) => {
                checkbox.addEventListener('change', async function() {
                    const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);
                    const moongcleofferIdx = this.getAttribute('data-moongcleoffer-idx');

                    const status = this.checked ? 'enabled' : 'disabled';

                    const formData = {
                        partnerIdx: selectedPartnerIdx,
                        moongcleoffer: {
                            stayMoongcleofferIdx: moongcleofferIdx,
                            status: status
                        }
                    };

                    // POST 요청 보내기
                    try {
                        const response = await fetch('/api/partner/edit-moongcleoffer-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(formData),
                        });

                        // 응답이 성공적이라면 페이지 새로 고침
                        if (response.ok) {
                            window.location.reload(); 
                            loading.style.display = 'flex'; 
                        } else {
                            alert('상태 변경에 실패했습니다.');
                        }
                    } catch (error) {
                        console.error('상태 변경 중 오류 발생:', error);
                        alert('상태 변경 중 오류가 발생했습니다.');
                    }
                });
            });
        });
    </script>
</body>

</html>