<?php

require 'config/bootstrap.php';

foreach (glob('migrations/demo/*.php') as $filename) {
	require $filename;
}

echo "All migrations have been demo successfully.\n";
