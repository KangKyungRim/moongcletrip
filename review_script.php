<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

use App\Models\Review;
use App\Models\ReviewTag;
use App\Models\MoongcleTag;

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = '/home/ubuntu/review-2025-01-14.xlsx';

$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

foreach ($rows as $rowKey => $row) {
	if (empty($row[1])) {
		continue;
	}

	$reviewIdx = $row[1];

	$review = Review::find($reviewIdx);

	$review->is_active = true;
	$review->product_idx = $row[15];
	$review->save();

	$tagNames = explode(', ', $row[8]);

	foreach ($tagNames as $key => $tagName) {
		$tag = MoongcleTag::where('tag_name', $tagName)->first();

		if (!empty($tag)) {
			ReviewTag::create([
				'tag_idx' => $tag->tag_idx,
				'tag_name' => $tag->tag_name,
				'tag_machine_name' => $tag->tag_machine_name,
				'review_idx' => $review->review_idx,
				'tag_order' => $key
			]);
		}
	}
}
