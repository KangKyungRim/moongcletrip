<!DOCTYPE html>
<html lang="ko">

<?php

// 쿠키 저장
setcookie('accessToken', $_GET['a'], time() + (60 * 60 * 24 * 30), '/', $_ENV['HOST_NAME'], true, true);
setcookie('refreshToken', $_GET['b'], time() + (60 * 60 * 24 * 30), '/', $_ENV['HOST_NAME'], true, true);

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<?php if ($_GET['type'] == 'login') : ?>
	<script>
		parent.document.location.href = '/mypage';
	</script>
<?php else : ?>
	<script>
		parent.document.location.href = '/users/signup-complete';
	</script>
<?php endif; ?>