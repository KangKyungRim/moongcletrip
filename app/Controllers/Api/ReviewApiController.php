<?php

namespace App\Controllers\Api;

use App\Models\Review;
use App\Models\PaymentItem;
use App\Models\ReviewImage;

use App\Helpers\ImageUploadService;

use App\Helpers\ResponseHelper;

use Illuminate\Database\Capsule\Manager as Capsule;

use Database;

class ReviewApiController
{
    public static function create()
    {
        if (empty($_POST['reviewContent']) || empty($_POST['rating']) || empty($_POST['userIdx']) || empty($_POST['paymentItemIdx'])) {
            return ResponseHelper::jsonResponse([
                'message' => '리뷰 등록에 실패했습니다.',
                'success' => false,
            ], 400);
        }

        $files = $_FILES['uploadedFiles'];

        try {
            Capsule::beginTransaction();

            $paymentItem = PaymentItem::find($_POST['paymentItemIdx']);

            $review = Review::create([
                'user_idx' => $_POST['userIdx'],
                'partner_idx' => $paymentItem->partner_idx,
                'partner_name' => $paymentItem->product_partner_name,
                'moongcledeal_idx' => $paymentItem->product_type == 'moongcledeal' ? $paymentItem->product_idx : 0,
                'product_idx' => $paymentItem->product_type == 'moongcledeal' ? 0 : $paymentItem->product_idx,
                'payment_item_idx' => $paymentItem->payment_item_idx,
                'review_category' => $paymentItem->product_category,
                'rating' => $_POST['rating'],
                'review_content' => $_POST['reviewContent'],
                'is_active' => true,
            ]);

            if (!empty($files['tmp_name'])) {
                if (!is_array($files['tmp_name'])) {
                    $files = [
                        'tmp_name' => [$files['tmp_name']],
                        'name' => [$files['name']],
                        'type' => [$files['type']],
                        'extension' => pathinfo($files['name'], PATHINFO_EXTENSION)  // 확장자 추출
                    ];
                }

                $imageService = new ImageUploadService();

                // 업로드된 모든 파일을 처리
                foreach ($files['tmp_name'] as $index => $tmpName) {
                    // 각각의 파일을 처리하여 업로드
                    $imageData = $imageService->uploadReviewImage(
                        $tmpName,                // 임시 파일 경로
                        $files['name'][$index],  // 원본 파일 이름
                        $files['type'][$index],  // 파일 MIME 타입
                        pathinfo($files['name'][$index], PATHINFO_EXTENSION),
                        $review->review_idx
                    );

                    ReviewImage::create([
                        'review_idx' => $review->review_idx,
                        'review_image_origin_path' => $imageData['image_origin_path'],
                        'review_image_small_path' => $imageData['image_small_path'],
                        'review_image_normal_path' => $imageData['image_normal_path'],
                        'review_image_big_path' => $imageData['image_big_path'],
                        'review_image_order' => $index,
                        'review_image_extension' => pathinfo($files['name'][$index], PATHINFO_EXTENSION),
                    ]);
                }
            }

            Capsule::commit();

            return ResponseHelper::jsonResponse([
                'message' => '리뷰가 성공적으로 등록되었습니다.',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            Capsule::rollBack();

            return ResponseHelper::jsonResponse([
                'debug' => $e,
                'message' => '리뷰 등록에 실패했습니다.',
                'success' => false,
            ], 400);
        }
    }
}
