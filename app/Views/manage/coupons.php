<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<?php

$coupons = $data['coupons'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

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
						<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">리워드 관리</a></li>
						<li class="breadcrumb-item text-sm text-dark active" aria-current="page">할인 쿠폰 관리</li>
					</ol>
					<h6 class="font-weight-bolder mb-0">할인 쿠폰 관리</h6>
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
					<div class="card">
						<!-- Card header -->
						<div class="card-header pb-0">
							<div class="d-lg-flex">
								<div>
									<h5 class="mb-0">할인 쿠폰 관리</h5>
								</div>
								<div class="ms-auto my-auto mt-lg-0 mt-4">
									<div class="ms-auto my-auto">
										<a href="/manage/reward/discount-coupon/create" class="btn btn-primary btn-sm mb-0">+&nbsp; 신규 쿠폰 생성</a>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body px-0 pb-0">
							<div class="table-responsive">
								<table class="table table-flush" id="coupons-list">
									<thead class="thead-light">
										<tr>
											<th data-sortable=""><a href="#">쿠폰명</a></th>
											<th data-sortable=""><a href="#">할인 금액</a></th>
											<th data-sortable=""><a href="#">최소 구매 금액</a></th>
											<th data-sortable=""><a href="#">총 발행 수</a></th>
											<th data-sortable=""><a href="#">다운로드 수</a></th>
											<th data-sortable=""><a href="#">사용 수</a></th>
											<th data-sortable=""><a href="#">쿠폰 활성화</a></th>
											<th data-sortable=""><a href="#">쿠폰 공개</a></th>
											<th data-sortable=""><a href="#">사용 가능 시작일</a></th>
											<th data-sortable=""><a href="#">사용 가능 종료일</a></th>
										</tr>
									</thead>
									<tbody>

										<?php foreach ($coupons as $coupon) : ?>
											<tr>
												<td>
													<a href="/manage/reward/discount-coupon/edit?coupon_idx=<?= $coupon->coupon_idx; ?>">
														<h6 class="my-auto"><?= $coupon->coupon_name; ?></h6>
													</a>
												</td>
												<td class="text-sm"><?= number_format($coupon->discount_amount); ?> 원</td>
												<td class="text-sm"><?= number_format($coupon->minimum_order_price); ?> 원</td>
												<td class="text-sm"><?= number_format($coupon->total_issuance); ?> 개</td>
												<td class="text-sm"><?= number_format($coupon->download_count); ?> 개</td>
												<td class="text-sm"><?= number_format($coupon->used_count); ?> 개</td>
												<td>
													<?php if ($coupon->is_active) : ?>
														<span class="badge badge-success badge-sm">활성화</span>
													<?php else : ?>
														<span class="badge badge-warning badge-sm">비활성화</span>
													<?php endif; ?>
												</td>
												<td>
													<?php if ($coupon->show_in_coupon_wallet) : ?>
														<span class="badge badge-success badge-sm">공개</span>
													<?php else : ?>
														<span class="badge badge-warning badge-sm">비공개</span>
													<?php endif; ?>
												</td>
												<td class="text-sm"><?= $coupon->start_date; ?></td>
												<td class="text-sm"><?= $coupon->end_date; ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>

								</table>
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
	<script src="/assets/manage/js/plugins/datatables.js"></script>

	<script>
		const dataTableSearch = new simpleDatatables.DataTable("#coupons-list", {
			searchable: true,
			fixedHeight: false,
			perPageSelect: false
		});

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
</body>

</html>