<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->table('partners', function (Blueprint $table) {
    $table->json('partner_search_badge')->nullable()->after('partner_status');
});

Capsule::schema()->table('partners_draft', function (Blueprint $table) {
    $table->json('partner_search_badge')->nullable()->after('partner_calculation_type');
});
