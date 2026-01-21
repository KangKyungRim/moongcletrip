<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

use App\Services\OndaWebhookHandlerService;
use App\Controllers\Api\OndaRoomController;
use App\Controllers\Api\OndaRateplanController;
use App\Controllers\Api\OndaRoomInventoryController;

// OndaRoomController::storeRoomtypes();
// OndaRoomController::storeDetailRoomtypes();

// OndaRateplanController::storeRateplans();
// OndaRateplanController::storeDetailRateplans();

OndaRoomInventoryController::storeInventoriesCLI();
// OndaRoomInventoryController::storeInventoriesAfter3Month();
