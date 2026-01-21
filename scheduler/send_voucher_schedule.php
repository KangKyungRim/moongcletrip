<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

use App\Services\SendVoucherService;

$service = new SendVoucherService();
$service->processSendEmailVouchers();
$service->processSendKakaoVouchers();
$service->processSendPartnerEmailVouchers();