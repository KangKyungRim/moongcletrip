<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];

// unset($_COOKIE['accessToken']);
// unset($_COOKIE['refreshToken']);

// setcookie('accessToken', false, -1, '/', $_ENV['HOST_NAME'], true, true);
// setcookie('refreshToken', false, -1, '/', $_ENV['HOST_NAME'], true, true);

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

<body>

    <script>
        logout();
    </script>

</body>

</html>