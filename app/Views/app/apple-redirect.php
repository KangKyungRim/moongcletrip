<!DOCTYPE html>
<html lang="ko">

<?php

$headers = $data['headers'];

if (empty($_COOKIE['deviceValidate'])) {
	// CSRF 방지용 state 생성
	$state = "apple_" . md5(microtime() . mt_rand());

	// state 값과 만료 시간 저장
	$stateData = [
		'state' => $state,
		'expires_at' => time() + 600, // 만료 시간: 10분
	];
	file_put_contents('/tmp/apple_state_' . $state . '.json', json_encode($stateData));

	$service = $data['service'];
	$authUrl = $service->getAuthUrl($state);

	header('Location: ' . $authUrl);
	exit;
}

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<?php if (!empty($_COOKIE['deviceValidate'])) : ?>
	<script>
		function appleLogin() {
			var dataToSend = {
				id: 'appleLogin'
			};
			window.ReactNativeWebView.postMessage(JSON.stringify(dataToSend));
		}

		appleLogin();
	</script>
<?php endif; ?>