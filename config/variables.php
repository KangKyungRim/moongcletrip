<?php
// variables.php

$_PROTOCOL = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
$_OG_URL = $_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$_SITE_NAME = '뭉클트립 | 뭉클한 여행 공동구매';
$_APP_TITLE = '뭉클트립 | 뭉클한 여행 공동구매';
$_OG_DESCRIPTION = '뭉쳐서 만드는 놀라운 여행 초특가';
$_OG_KEYWORD = '여행공동구매, 호텔공동구매, 호텔최저가, 여행최저가';

$_EXCLUDED_PARTNER_IDX = [0];
$_EXCLUDED_PARTNER_STRING = '(0)';

if($_ENV['APP_ENV'] == 'production') {
	$_EXCLUDED_PARTNER_IDX = [1, 2, 3, 4, 3932, 4411, 6541, 9566, 10556, 10958, 11010, 11038, 11043, 11060, 11061, 11062, 11146, 11312, 11374, 11420, 12294];
	$_EXCLUDED_PARTNER_STRING = '(1, 2, 3, 4, 3932, 4411, 6541, 9566, 10556, 10958, 11010, 11038, 11043, 11060, 11061, 11062, 11146, 11312, 11374, 11420, 12294)';
}
