<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

use App\Services\ReservationHandlerService;

$service = new ReservationHandlerService();
$service->processReservationStatus();