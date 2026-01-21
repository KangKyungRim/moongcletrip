<?php

require 'config/bootstrap.php';

require './migrations/0085_create_facility_images_table.php';
require './migrations/0086_create_facility_detail_table.php';
require './migrations/0087_create_service_detail_table.php';

echo "All migrations have been run successfully.\n";
