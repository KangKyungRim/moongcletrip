<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

// use RedisManager;

// $redis = RedisManager::getInstance();

// // Redis 키 설정
// $redisKey = "moongcledeal:recommend:all";

// // Redis에서 데이터 조회
// $cachedData = $redis->get($redisKey);

// print_r($cachedData);

// use App\Models\Partner;
// use App\Models\Room;
// use App\Models\Rateplan;
// use App\Services\FCMNotificationService;
// use App\Models\Token;
// use App\Models\MoongcleTag;

// use PhpOffice\PhpSpreadsheet\IOFactory;

// use Carbon\Carbon;

// $filePath = "/data/wwwroot/moongcletrip/moongcledeal-241222.xlsx";

// $spreadsheet = IOFactory::load($filePath);
// $sheet = $spreadsheet->getActiveSheet();
// $rows = $sheet->toArray();

// echo $filePath . "\n";

// $headers = array_map('strtolower', $rows[0]); // 첫 번째 줄은 헤더로 처리
// unset($rows[0]); // 헤더 제거

// foreach ($rows as $rowKey => $row) {
//     $partnerIdx = $row[1];
//     $roomIdx = $row[6];
//     $rateplanIdx = $row[11];

//     // $partner = Partner::find($partnerIdx);
//     // $room = Room::find($roomIdx);
//     // $rateplan = Rateplan::find($rateplanIdx);

//     // if ($partner->partner_status != 'enabled') {
//     //     continue;
//     // }

//     $stayTags = $row[18];
//     // $roomBenefits = $row[14];
//     $roomTags = $row[19];
//     $rateplanBenefits = $row[16];
//     // $rateplanTags = $row[12];
//     $curatedTags = $row[17];
//     $discount = $row[26];
//     $additionalTag = $row[27];
//     $whomTag = $row[28];

//     // if(!empty($row[27])) {
//     //     echo $row[27] . "\n";

//     // }

//     // echo $row[1] . "\n";
//     if (!empty($additionalTag)) {

//         $tags = explode(', ', $additionalTag);

//         foreach ($tags as $tag) {
//             $tag = trim($tag);
//             $tagData = MoongcleTag::where('tag_name', $tag)->first();

//             // echo $rowKey . " : " . $tag . "\n";

//             if (!empty($tagData)) {
//                 echo $rowKey . " : " . $tagData->tag_name . "\n";
//             }
//         }
//     }
// }

// $moongcleMatches = MoongcleMatch::where('moongcle_match_status', 'enabled')
//     ->where('notification_status', 'pending')
//     ->where('notification_time', '<', Carbon::now())
//     ->get();

// foreach ($moongcleMatches as $match) {
//     echo $match->moongcle_match_idx;
//     echo "\n";

//     $moongcledeal = $match->deal;
//     $moongcleoffer = $match->getProduct();
//     $partner = Partner::find($moongcleoffer->partner_idx);

//     echo "UserIDX : ".$moongcledeal->user_idx;
//     echo "\n";



//     $token = Token::where('user_idx', $moongcledeal->user_idx)
//         ->where('token_is_active', true)
//         ->get();

//     print_r($token);

//     if($token->isEmpty()) {
//         echo 'true';
//     } else {
//         echo 'false';
//     }

//     echo $token->count();

//     foreach($token as $t) {
//         echo $t->user_idx;
//     }

//     break;
// }

// use App\Services\FCMNotificationService;

// use App\Models\Token;

// $fcmService = new FCMNotificationService($_ENV['FCM_KEY']);

// try {
//     $tokenList = Token::where('token_is_active', true)->get();

//     $title = "예약 완료";
//     $message = "2024.10.01 (목) 뭉클호텔 예약이 완료되었습니다.";
//     $link = "https://www.moongcletrip.com/my/reservations";

//     $response = $fcmService->sendNotification($tokenList, $title, $message, $link);

//     echo "FCM Response:\n";
//     print_r($response);
// } catch (Exception $e) {
//     echo 'Error: ' . $e->getMessage();
// }

// use GuzzleHttp\Client;

// use \Firebase\JWT\JWT;
// use \Firebase\JWT\Key;
// use \Firebase\JWT\ExpiredException;

// $secretKey = $_ENV['JWT_SECRET'];

// $payload = [
//     'iss' => $_ENV['HOST_NAME'],
//     'sub' => 'onda',
//     'iat' => time(),
//     'exp' => time() + (60 * 60 * 24 * 365 * 10),  // 만료 시간 1일
// ];

// echo JWT::encode($payload, $secretKey, 'HS256');
