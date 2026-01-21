<!DOCTYPE html>
<html lang="ko">

<?php

$headers = $data['headers'];
$service = $data['service'];

$state = "kakao_" . md5(microtime() . mt_rand());

// state 값과 만료 시간 저장
$stateData = [
	'state' => $state,
	'expires_at' => time() + 600, // 만료 시간: 10분
	'return' => empty($_GET['return']) ? '' : $_GET['return']
];
file_put_contents('/tmp/kakao_state_' . $state . '.json', json_encode($stateData));

$authUrl = $service->getAuthUrl($state);

// header('Location: ' . $authUrl);
// exit;

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<script>
	parent.document.location.href = '<?= $authUrl; ?>';
</script>