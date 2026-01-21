<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

use App\Services\OndaWebhookHandlerService;

$service = new OndaWebhookHandlerService();
$service->processPendingWebhooks();