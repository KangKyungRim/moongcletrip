<!DOCTYPE html>
<html lang="ko">

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<body>
	<?php if ($_ENV['ANALYTICS_ENV'] == 'production' || $_ENV['ANALYTICS_ENV'] == 'staging') : ?>
		<script>
			document.addEventListener("DOMContentLoaded", () => {
				window.dataLayer.push({
					event: "purchase",
				});
			});
		</script>
	<?php endif; ?>
</body>

</html>