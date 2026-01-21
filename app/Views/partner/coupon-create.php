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
						<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">리워드 관리</a></li>
						<li class="breadcrumb-item text-sm text-dark active" aria-current="page">쿠폰 생성</li>
					</ol>
					<h6 class="font-weight-bolder mb-0">쿠폰 생성</h6>
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
					<div class="card card-body mt-4 p-5">
						<h6 class="mb-0">쿠폰 생성</h6>
						<p class="text-sm mb-0">쿠폰 정보를 입력해주세요.</p>
						<hr class="horizontal dark my-3">

						<form method="post" name="couponForm" id="couponForm">

							<div class="form-group row align-items-center">
								<label for="couponName" class="form-control-label col-sm-2">
									쿠폰명 <b class="text-danger">*</b>
								</label>
								<div class="col">
									<input class="form-control" type="text" value="" id="couponName" placeholder="ex) 웰컴쿠폰팩 (3만원 할인)" required>
								</div>
							</div>

							<hr class="horizontal gray-light my-3">

							<div class="form-group row align-items-center">
								<label for="discountAmount" class="form-control-label col-sm-2">
									할인 금액 <b class="text-danger">*</b>
								</label>
								<div class="col">
									<input type="number" class="form-control" value="" name="discountAmount" id="discountAmount" placeholder="ex) 5000" required Numberonly />
								</div>
							</div>

							<div class="form-group row align-items-center">
								<label for="minimumOrderPrice" class="form-control-label col-sm-2">
									최소 구매 금액 <b class="text-danger">*</b>
								</label>
								<div class="col">
									<input type="number" class="form-control" value="" name="minimumOrderPrice" id="minimumOrderPrice" placeholder="ex) 50000" required Numberonly />
								</div>
							</div>

							<hr class="horizontal gray-light my-3">

							<div class="form-group row align-items-center">
								<label for="totalIssuance" class="form-control-label col-sm-2">
									총 발행 수 <b class="text-danger">*</b>
								</label>
								<div class="col">
									<input type="number" class="form-control" value="" name="totalIssuance" id="totalIssuance" placeholder="ex) 1000" required Numberonly />
								</div>
							</div>

							<hr class="horizontal gray-light my-3">

							<div class="form-group row align-items-center">
								<label for="isActive" class="form-control-label col-sm-2">
									활성화
								</label>
								<div class="col">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" id="isActive">
									</div>
								</div>
							</div>

							<div class="form-group row align-items-center">
								<label for="showInCouponWallet" class="form-control-label col-sm-2">
									쿠폰 공개
								</label>
								<div class="col">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" id="showInCouponWallet">
									</div>
								</div>
							</div>

							<hr class="horizontal gray-light my-3">

							<div class="form-group row align-items-center">
								<label for="couponStartDate" class="form-control-label col-sm-2">
									쿠폰 사용 가능 시작일
								</label>
								<div class="col">
									<input id="couponStartDate" class="form-control datepicker" placeholder="Please select date" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
								</div>
							</div>

							<div class="form-group row align-items-center">
								<label for="couponEndDate" class="form-control-label col-sm-2">
									쿠폰 사용 가능 종료일
								</label>
								<div class="col">
									<input id="couponEndDate" class="form-control datepicker" placeholder="Please select date" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
								</div>
							</div>

						</form>

						<div class="d-flex justify-content-end mt-4">
							<button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0">취소</button>
							<button type="button" id="submitForm" name="submitForm" class="btn bg-gradient-primary m-0 ms-2">저장하기</button>
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
		let startDate = null;
		let endDate = null;

		flatpickr('#couponStartDate', {
			minDate: 'today',
			defaultDate: 'today',
			dateFormat: 'Y-m-d H:i:S',
			defaultHour: 0,
			defaultMinute: 0,
			defaultSeconds: 0,
			enableTime: true,
			onReady: function(selectedDates, dateStr, instance) {
				const defaultDate = instance.input.value;
				if (defaultDate) {
					startDate = instance.formatDate(new Date(defaultDate), "Y-m-d H:i:S");
				}
			},
			onChange: function(selectedDates, dateStr, instance) {
				if (selectedDates.length > 0) {
					startDate = instance.formatDate(selectedDates[0], "Y-m-d H:i:S");
				}
			}
		});

		flatpickr('#couponEndDate', {
			minDate: 'today',
			dateFormat: 'Y-m-d H:i:S',
			defaultHour: 23,
			defaultMinute: 59,
			defaultSeconds: 59,
			enableTime: true,
			onChange: function(selectedDates, dateStr, instance) {
				if (selectedDates.length > 0) {
					endDate = instance.formatDate(selectedDates[0], "Y-m-d H:i:S");
				}
			}
		});

		document.addEventListener("DOMContentLoaded", () => {
			const submitFormButton = document.getElementById("submitForm");
			const cancelFormButton = document.getElementById("cancelForm");

			submitFormButton.addEventListener("click", async () => {
				// 폼 데이터 수집
				const couponData = {
					couponName: document.getElementById("couponName").value.trim(),
					discountAmount: parseFloat(document.getElementById("discountAmount").value.trim()),
					minimumOrderPrice: parseFloat(document.getElementById("minimumOrderPrice").value.trim()),
					totalIssuance: parseInt(document.getElementById("totalIssuance").value.trim(), 10),
					isActive: document.getElementById("isActive").checked,
					showInCouponWallet: document.getElementById("showInCouponWallet").checked,
					couponStartDate: startDate,
					couponEndDate: endDate,
				};

				// 유효성 검사
				if (!couponData.couponName || isNaN(couponData.discountAmount) || isNaN(couponData.minimumOrderPrice) || isNaN(couponData.totalIssuance)) {
					alert("필수 입력값을 확인하세요.");
					return;
				}

				try {
					// API 호출
					const response = await fetch('/api/reward/discount-coupon/store', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
						},
						body: JSON.stringify(couponData),
					});

					// 응답 처리
					if (!response.ok) {
						throw new Error(`API 요청 실패: ${response.statusText}`);
					}

					const result = await response.json();

					if (result.success) {
						alert("신규 쿠폰을 생성했습니다.");
						window.location.href = '/partner/reward/discount-coupons';
					} else {
						alert("쿠폰 저장에 실패했습니다. 다시 시도해주세요.");
					}
				} catch (error) {
					console.error("API 요청 중 오류 발생:", error);
					alert("API 요청 중 오류가 발생했습니다. 콘솔을 확인하세요.");
				}
			});

			cancelFormButton.addEventListener("click", async () => {
				location.href = '/partner/reward/discount-coupons';
			});
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
</body>

</html>