<!DOCTYPE html>
<html lang="ko">

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/partner/blocks/head.php"; ?>
<!-- Head -->

<body class="error-page">

	<main class="main-content  mt-0">
		<div>
			<section class="min-vh-100 d-flex align-items-center">
				<div class="container">
					<div class="row mt-lg-0 mt-8">
						<div class="col-lg-5 my-auto">
							<h1 class="display-1 text-bolder text-gradient text-warning fadeIn1 fadeInBottom mt-5">Error 500</h1>
							<h2 class="fadeIn3 fadeInBottom opacity-8">Something went wrong</h2>
							<p class="lead opacity-6 fadeIn2 fadeInBottom">We suggest you to go to the homepage while we solve this issue.</p>
							<button id="gotoDashboard" type="button" class="btn bg-gradient-warning mt-4 fadeIn2 fadeInBottom">Go to Homepage</button>
						</div>
						<div class="col-lg-7 my-auto">
							<img class="w-100 fadeIn1 fadeInBottom" src="/assets/manage/images/illustrations/error-500.png" alt="500-error">
						</div>
					</div>
				</div>
			</section>
		</div>
	</main>

	<footer class="footer py-5">
		<div class="container-fluid">
			<div class="row align-items-center justify-content-lg-between">
				<div class="col-lg-6 mb-lg-0 mb-4">
					<div class="copyright text-center text-sm text-muted text-lg-start">
						© 2025,
						made with <i class="fa fa-heart"></i> by
						<a href="https://www.moongcletrip.com" class="font-weight-bold" target="_blank">Honolulu Company</a>
						for a better.
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

	<!--   Core JS Files   -->
	<script src="/assets/manage/js/core/popper.min.js"></script>
	<script src="/assets/manage/js/core/bootstrap.min.js"></script>
	<script src="/assets/manage/js/plugins/perfect-scrollbar.min.js"></script>
	<script src="/assets/manage/js/plugins/smooth-scrollbar.min.js"></script>
	<script src="/assets/manage/js/plugins/choices.min.js"></script>
	<script src="/assets/manage/js/plugins/quill.min.js"></script>
	<script src="/assets/manage/js/plugins/flatpickr.min.js"></script>
	<script src="/assets/manage/js/plugins/dropzone.min.js"></script>
	<script src="/assets/manage/js/plugins/multistep-form.js"></script>
	<script src="/assets/manage/js/plugins/sortable.min.js"></script>
	<!-- Kanban scripts -->
	<script src="/assets/manage/js/plugins/dragula/dragula.min.js"></script>
	<script src="/assets/manage/js/plugins/jkanban/jkanban.js"></script>
	<script src="/assets/manage/js/plugins/chartjs.min.js"></script>
	<script src="/assets/manage/js/plugins/threejs.js"></script>
	<script src="/assets/manage/js/plugins/orbit-controls.js"></script>

	<script>
		document.getElementById('gotoDashboard').addEventListener('click', function() {
			window.location.href = '/partner/dashboard';
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