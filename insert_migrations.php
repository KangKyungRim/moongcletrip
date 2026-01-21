<?php

require 'config/bootstrap.php';

foreach (glob('migrations/insert/*.php') as $filename) {
	require $filename;
}

echo "All migrations have been inserted successfully.\n";
