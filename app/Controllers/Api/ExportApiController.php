<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Carbon\Carbon;
use Database;

class ExportApiController
{
	public static function downloadOndaStays()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 7) {
				header('Location: /manage/login');
				exit;
			}
		}

		// 스프레드시트 객체 생성
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// 헤더 설정
		$headers = [
			'Moongcle ID',
			'Stay ID',
			'Stay Name',
			'Room ID',
			'Room Name',
			'Room Desc',
			'Rateplan ID',
			'Rateplan Name',
			'Rateplan Desc',
			'Onda Stay Tags',
			'Onda Room Tags',
			'Stay Tags',
			'Room Benefits',
			'Room Tags',
			'Rateplan Benefits',
			'Rateplan Tags',
			'Curated Tags',
			'Discount',
		];
		$sheet->fromArray($headers, NULL, 'A1');

		$sql = "
			SELECT
				p.partner_idx,
				p.partner_onda_idx,
				p.partner_name,
				r.room_onda_idx,
				r.room_name,
				r.room_other_notes,
				rr.rateplan_onda_idx,
				rp.rateplan_name,
				rp.rateplan_description,
				GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ', ') AS stay_tags,
				GROUP_CONCAT(DISTINCT tcr.tag_name ORDER BY tcr.tag_name ASC SEPARATOR ', ') AS room_tags
			FROM
				moongcletrip.partners p
			LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
			LEFT JOIN moongcletrip.room_rateplan rr ON rr.room_idx = r.room_idx
			LEFT JOIN moongcletrip.rateplans rp ON rp.rateplan_idx = rr.rateplan_idx
			LEFT JOIN moongcletrip.tag_connections tc ON tc.item_idx = p.partner_detail_idx AND tc.item_type = 'stay'
			LEFT JOIN moongcletrip.tag_connections tcr ON tcr.item_idx = r.room_idx AND tcr.item_type = 'room'
			GROUP BY
				p.partner_onda_idx,
				p.partner_name,
				r.room_onda_idx,
				r.room_name,
				rr.rateplan_onda_idx,
				rp.rateplan_name
		";

		$stays = Database::getInstance()->getConnection()->select($sql);

		$data = [];

		foreach ($stays as $stay) {
			$data[] = [
				$stay->partner_idx,
				$stay->partner_onda_idx,
				$stay->partner_name,
				$stay->room_onda_idx,
				$stay->room_name,
				$stay->room_other_notes,
				$stay->rateplan_onda_idx,
				$stay->rateplan_name,
				$stay->rateplan_description,
				$stay->stay_tags,
				$stay->room_tags,
				'', // Stay Tags
				'', // Room Benefits
				'', // Room Tags
				'', // Rateplan Benefits
				'', // Rateplan Tags
			];
		}

		// 데이터 삽입
		$sheet->fromArray($data, NULL, 'A2');

		// 엑셀 파일 저장
		$writer = new Xlsx($spreadsheet);

		// 출력 버퍼로 스트림
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="stays-' . date('YmdHis') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // 파일 대신 출력 스트림으로 저장
		exit;
	}

	public static function downloadOndaStaysStreaming($desiredOrderString)
	{
		try {
			// 배치 크기와 파일당 최대 행 수 설정
			$batchSize = 1000; // 한 번에 처리할 행 수
			$fileSize = 25000; // 파일당 최대 행 수
			$offset = 0; // 시작 오프셋
			$fileIndex = 1; // 파일 번호

			do {
				// 임시 파일 기반의 Writer 사용
				$tempFile = tempnam(sys_get_temp_dir(), 'phpspreadsheet');
				$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
				$writer->setPreCalculateFormulas(false); // 수식 계산 비활성화로 메모리 절약

				$sheet = $spreadsheet->getActiveSheet();

				// 헤더 설정
				$headers = [
					'기존순서',
					'Moongcle ID',
					'URL',
					'Onda Stay ID',
					'Stay Status',
					'Stay Name',
					'Moongcle Room ID',
					'Onda Room ID',
					'Room Status',
					'Room Name',
					'Room Desc',
					'Moongcle Rateplan ID',
					'Onda Rateplan ID',
					'Rateplan Status',
					'Rateplan Name',
					'Rateplan Desc',
					'Rateplan Benefits',
					'Curated Tags',
					'Onda Stay Tags',
					'Onda Room Tags',
					'Stay Tags',
					'ao',
					'as',
					'Room Benefits',
					'Room Tags',
					'Rateplan Tags',
					'Discount',
				];
				$sheet->fromArray($headers, NULL, 'A1');

				$row = 2; // 첫 데이터 행

				for ($fileOffset = 0; $fileOffset < $fileSize; $fileOffset += $batchSize) {
					// 현재 배치 범위 계산
					$currentOffset = $offset + $fileOffset;

					// SQL 쿼리 실행
					$sql = "
						SELECT
							p.partner_idx,
							p.partner_onda_idx,
							p.partner_status,
							p.partner_name,
							r.room_idx,
							r.room_onda_idx,
							r.room_status,
							r.room_name,
							r.room_other_notes,
							rr.rateplan_idx,
							rr.rateplan_onda_idx,
							rp.rateplan_status,
							rp.rateplan_name,
							rp.rateplan_description,
							(
								SELECT GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ', ')
								FROM moongcletrip.tag_connections tc
								WHERE tc.item_idx = p.partner_detail_idx AND tc.item_type = 'stay'
							) AS stay_tags,
							(
								SELECT GROUP_CONCAT(DISTINCT tcr.tag_name ORDER BY tcr.tag_name ASC SEPARATOR ', ')
								FROM moongcletrip.tag_connections tcr
								WHERE tcr.item_idx = r.room_idx AND tcr.item_type = 'room'
							) AS room_tags
						FROM
							moongcletrip.partners p
						LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
						LEFT JOIN moongcletrip.room_rateplan rr ON rr.room_idx = r.room_idx
						LEFT JOIN moongcletrip.rateplans rp ON rp.rateplan_idx = rr.rateplan_idx
						WHERE p.partner_idx IN ($desiredOrderString)
						GROUP BY
							p.partner_idx,
							p.partner_name,
							r.room_onda_idx,
							r.room_name,
							rr.rateplan_onda_idx,
							rp.rateplan_name
						LIMIT :offset, :limit
						";

					// 바인딩 파라미터
					$bindings = ['offset' => $currentOffset, 'limit' => $batchSize];
					$stays = Database::getInstance()->getConnection()->select($sql, $bindings);

					// 데이터가 없으면 종료
					if (empty($stays)) {
						break;
					}

					// 데이터 삽입
					foreach ($stays as $stay) {
						$sheet->fromArray([
							'', // 기존순서
							$stay->partner_idx,
							'', // URL
							$stay->partner_onda_idx,
							$stay->partner_status,
							$stay->partner_name,
							$stay->room_idx,
							$stay->room_onda_idx,
							$stay->room_status,
							$stay->room_name,
							$stay->room_other_notes,
							$stay->rateplan_idx,
							$stay->rateplan_onda_idx,
							$stay->rateplan_status,
							$stay->rateplan_name,
							$stay->rateplan_description,
							'', // Rateplan Benefits
							'', // Curated Tags
							$stay->stay_tags,
							$stay->room_tags,
							'', // Stay Tags
							'', // AO
							'', // AS
							'', // Room Benefits
							'', // Room Tags
							'', // Rateplan Tags
							'', // Discount
						], NULL, "A$row");

						$row++;
					}

					echo "Processed rows: $currentOffset to " . ($currentOffset + $batchSize) . " for file $fileIndex\n";
				}

				// 엑셀 파일 저장
				$outputFile = __DIR__ . "/stays-part-$fileIndex" . '.xlsx';
				$writer->save($outputFile);

				echo "File $fileIndex saved: $outputFile\n";

				// 다음 파일로 이동
				$offset += $fileSize;
				$fileIndex++;

				// 메모리 해제
				$spreadsheet->disconnectWorksheets();
				unset($spreadsheet);
			} while (!empty($stays));
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public static function downloadOndaStaysStreamingOrderIdx()
	{
		try {
			// 배치 크기와 파일당 최대 행 수 설정
			$batchSize = 1000; // 한 번에 처리할 행 수
			$fileSize = 25000; // 파일당 최대 행 수
			$offset = 0; // 시작 오프셋
			$fileIndex = 1; // 파일 번호

			$timestamp = date('Ymd');

			do {
				// 임시 파일 기반의 Writer 사용
				$tempFile = tempnam(sys_get_temp_dir(), 'phpspreadsheet');
				$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
				$writer->setPreCalculateFormulas(false); // 수식 계산 비활성화로 메모리 절약

				$sheet = $spreadsheet->getActiveSheet();

				// 헤더 설정
				$headers = [
					'Moongcle ID',
					'Onda Stay ID',
					'Stay Status',
					'Stay Name',
					'Moongcle Room ID',
					'Onda Room ID',
					'Room Status',
					'Room Name',
					'Room Desc',
					'Moongcle Rateplan ID',
					'Onda Rateplan ID',
					'Rateplan Status',
					'Rateplan Name',
					'Rateplan Desc',
					'Rateplan Benefits', //추가
					'Curated Tags',		//추가
					'Onda Stay Tags',
					'Onda Room Tags',
					'Stay Tags',
					'Room Benefits',
					'Room Tags',
					'Rateplan Tags',
					'Discount',
					'Attractive Index',
					'Search Index'
				];
				$sheet->fromArray($headers, NULL, 'A1');

				$row = 2; // 첫 데이터 행

				for ($fileOffset = 0; $fileOffset < $fileSize; $fileOffset += $batchSize) {
					// 현재 배치 범위 계산
					$currentOffset = $offset + $fileOffset;

					// SQL 쿼리 실행
					$sql = "
						SELECT
							p.partner_idx,
							p.partner_onda_idx,
							p.partner_status,
							p.partner_name,
							r.room_idx,
							r.room_onda_idx,
							r.room_status,
							r.room_name,
							r.room_other_notes,
							rr.rateplan_idx,
							rr.rateplan_onda_idx,
							rp.rateplan_status,
							rp.rateplan_name,
							rp.rateplan_description,
							benefits.list AS rateplan_benefits,
    						curated.list AS curated_tags,
							stay_tags.list AS stay_tags,	
    						room_tags.list AS room_tags,
							mo.minimum_discount as discount, 				
							mo.moongcleoffer_attractive as attractive_index,
							p.search_index
						FROM
							moongcletrip.partners p
						LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
						LEFT JOIN moongcletrip.room_rateplan rr ON rr.room_idx = r.room_idx
						LEFT JOIN moongcletrip.rateplans rp ON rp.rateplan_idx = rr.rateplan_idx
						LEFT JOIN moongcletrip.moongcleoffers mo ON mo.base_product_idx = rr.room_rateplan_idx
						-- [핵심] 혜택 정보를 미리 그룹핑해서 JOIN
						LEFT JOIN (
							SELECT item_idx, GROUP_CONCAT(benefit_name ORDER BY benefit_name ASC SEPARATOR ', ') as list
							FROM moongcletrip.benefit_item
							WHERE item_type = 'rateplan'
							GROUP BY item_idx
						) AS benefits ON benefits.item_idx = rr.rateplan_idx
						-- [핵심] 큐레이션 태그를 미리 그룹핑해서 JOIN
						LEFT JOIN (
							SELECT item_idx, GROUP_CONCAT(tag_name ORDER BY tag_name ASC SEPARATOR ', ') as list
							FROM moongcletrip.curated_tags
							WHERE item_type = 'moongcleoffer'
							GROUP BY item_idx
						) AS curated ON curated.item_idx = mo.moongcleoffer_idx
						LEFT JOIN (
							SELECT item_idx, GROUP_CONCAT(tag_name ORDER BY tag_name ASC SEPARATOR ', ') as list
							FROM moongcletrip.tag_connections
							WHERE item_type = 'stay'
							GROUP BY item_idx
						) AS stay_tags ON stay_tags.item_idx = p.partner_detail_idx
						-- [핵심] 객실 태그를 미리 그룹핑해서 JOIN
						LEFT JOIN (
							SELECT item_idx, GROUP_CONCAT(tag_name ORDER BY tag_name ASC SEPARATOR ', ') as list
							FROM moongcletrip.tag_connections
							WHERE item_type = 'room'
							GROUP BY item_idx
						) AS room_tags ON room_tags.item_idx = r.room_idx
						ORDER BY
							p.partner_idx
						LIMIT :offset, :limit
						";

					// 바인딩 파라미터
					$bindings = ['offset' => $currentOffset, 'limit' => $batchSize];
					$stays = Database::getInstance()->getConnection()->select($sql, $bindings);

					// 데이터가 없으면 종료
					if (empty($stays)) {
						break;
					}
					
					// 데이터 삽입
					foreach ($stays as $stay) {
						$sheet->fromArray([
							$stay->partner_idx,			//Moongcle ID
							$stay->partner_onda_idx,	//Onda Stay ID
							$stay->partner_status,		//Stay Status
							$stay->partner_name,		//Stay Name
							$stay->room_idx,			//Moongcle Room ID
							$stay->room_onda_idx,		//Onda Room ID
							$stay->room_status,			//Room Status
							$stay->room_name,			//Room Name
							$stay->room_other_notes,	//Room Desc
							$stay->rateplan_idx,		//Moongcle Rateplan ID
							$stay->rateplan_onda_idx,	//Onda Rateplan ID
							$stay->rateplan_status,		//Rateplan Status
							$stay->rateplan_name,		//Rateplan Name
							$stay->rateplan_description,//Rateplan Desc
							$stay->rateplan_benefits, 	//Rateplan Benefits	(추가)
							$stay->curated_tags, 		//Curated Tags		(추가)
							$stay->stay_tags,			//Onda Stay Tags
							$stay->room_tags,			//Onda Room Tags
							'', 						// Stay Tags
							'', 						// Room Benefits
							'', 						// Room Tags
							'', 						// Rateplan Tags
							$stay->discount, 			// Discount
							$stay->attractive_index, 	// Attractive Index
							$stay->search_index, 		// Search Index
						], NULL, "A$row");

						$row++;
					}

					echo "Processed rows: $currentOffset to " . ($currentOffset + $batchSize) . " for file $fileIndex\n";
				}

				// 엑셀 파일 저장
				$outputFile = "/data/wwwroot/moongcletrip/storage/exports/stays-part-$fileIndex-$timestamp" . '.xlsx';
				$writer->save($outputFile);

				echo "File $fileIndex saved: $outputFile\n";

				// 다음 파일로 이동
				$offset += $fileSize;
				$fileIndex++;

				// 메모리 해제
				$spreadsheet->disconnectWorksheets();
				unset($spreadsheet);
			} while (!empty($stays));
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}


	public static function downloadOndaStaysStreamingOrderIdx_backup()
	{
		try {
			// 배치 크기와 파일당 최대 행 수 설정
			$batchSize = 1000; // 한 번에 처리할 행 수
			$fileSize = 25000; // 파일당 최대 행 수
			$offset = 0; // 시작 오프셋
			$fileIndex = 1; // 파일 번호

			do {
				// 임시 파일 기반의 Writer 사용
				$tempFile = tempnam(sys_get_temp_dir(), 'phpspreadsheet');
				$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
				$writer->setPreCalculateFormulas(false); // 수식 계산 비활성화로 메모리 절약

				$sheet = $spreadsheet->getActiveSheet();

				// 헤더 설정
				$headers = [
					'Moongcle ID',
					'Onda Stay ID',
					'Stay Status',
					'Stay Name',
					'Moongcle Room ID',
					'Onda Room ID',
					'Room Status',
					'Room Name',
					'Room Desc',
					'Moongcle Rateplan ID',
					'Onda Rateplan ID',
					'Rateplan Status',
					'Rateplan Name',
					'Rateplan Desc',
					'Onda Stay Tags',
					'Onda Room Tags',
					'Stay Tags',
					'Room Benefits',
					'Room Tags',
					'Rateplan Benefits',
					'Rateplan Tags',
					'Curated Tags',
					'Discount',
				];
				$sheet->fromArray($headers, NULL, 'A1');

				$row = 2; // 첫 데이터 행

				for ($fileOffset = 0; $fileOffset < $fileSize; $fileOffset += $batchSize) {
					// 현재 배치 범위 계산
					$currentOffset = $offset + $fileOffset;

					// SQL 쿼리 실행
					$sql = "
						SELECT
							p.partner_idx,
							p.partner_onda_idx,
							p.partner_status,
							p.partner_name,
							r.room_idx,
							r.room_onda_idx,
							r.room_status,
							r.room_name,
							r.room_other_notes,
							rr.rateplan_idx,
							rr.rateplan_onda_idx,
							rp.rateplan_status,
							rp.rateplan_name,
							rp.rateplan_description,
							(
								SELECT GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ', ')
								FROM moongcletrip.tag_connections tc
								WHERE tc.item_idx = p.partner_detail_idx AND tc.item_type = 'stay'
							) AS stay_tags,
							(
								SELECT GROUP_CONCAT(DISTINCT tcr.tag_name ORDER BY tcr.tag_name ASC SEPARATOR ', ')
								FROM moongcletrip.tag_connections tcr
								WHERE tcr.item_idx = r.room_idx AND tcr.item_type = 'room'
							) AS room_tags
						FROM
							moongcletrip.partners p
						LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
						LEFT JOIN moongcletrip.room_rateplan rr ON rr.room_idx = r.room_idx
						LEFT JOIN moongcletrip.rateplans rp ON rp.rateplan_idx = rr.rateplan_idx
						GROUP BY
							p.partner_idx,
							p.partner_name,
							r.room_onda_idx,
							r.room_name,
							rr.rateplan_onda_idx,
							rp.rateplan_name
						ORDER BY
							p.partner_idx
						LIMIT :offset, :limit
						";

					// 바인딩 파라미터
					$bindings = ['offset' => $currentOffset, 'limit' => $batchSize];
					$stays = Database::getInstance()->getConnection()->select($sql, $bindings);

					// 데이터가 없으면 종료
					if (empty($stays)) {
						break;
					}

					// 데이터 삽입
					foreach ($stays as $stay) {
						$sheet->fromArray([
							$stay->partner_idx,
							$stay->partner_onda_idx,
							$stay->partner_status,
							$stay->partner_name,
							$stay->room_idx,
							$stay->room_onda_idx,
							$stay->room_status,
							$stay->room_name,
							$stay->room_other_notes,
							$stay->rateplan_idx,
							$stay->rateplan_onda_idx,
							$stay->rateplan_status,
							$stay->rateplan_name,
							$stay->rateplan_description,
							$stay->stay_tags,
							$stay->room_tags,
							'', // Stay Tags
							'', // Room Benefits
							'', // Room Tags
							'', // Rateplan Benefits
							'', // Rateplan Tags
						], NULL, "A$row");

						$row++;
					}

					echo "Processed rows: $currentOffset to " . ($currentOffset + $batchSize) . " for file $fileIndex\n";
				}

				// 엑셀 파일 저장
				$outputFile = __DIR__ . "/stays-part-$fileIndex" . '.xlsx';
				$writer->save($outputFile);

				echo "File $fileIndex saved: $outputFile\n";

				// 다음 파일로 이동
				$offset += $fileSize;
				$fileIndex++;

				// 메모리 해제
				$spreadsheet->disconnectWorksheets();
				unset($spreadsheet);
			} while (!empty($stays));
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public static function downloadOndaStay($partnerOndaIdx)
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		// 스프레드시트 객체 생성
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// 헤더 설정
		$headers = [
			'Moongcle ID',
			'Stay ID',
			'Stay Name',
			'Room ID',
			'Room Name',
			'Room Desc',
			'Rateplan ID',
			'Rateplan Name',
			'Rateplan Desc',
			'Onda Stay Tags',
			'Onda Room Tags',
			'Stay Tags',
			'Room Benefits',
			'Room Tags',
			'Rateplan Benefits',
			'Rateplan Tags',
			'Curated Tags',
			'Discount',
		];
		$sheet->fromArray($headers, NULL, 'A1');

		$sql = "
			SELECT
				p.partner_idx,
				p.partner_onda_idx,
				p.partner_name,
				r.room_onda_idx,
				r.room_name,
				r.room_other_notes,
				rr.rateplan_onda_idx,
				rp.rateplan_name,
				rp.rateplan_description,
				GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ', ') AS stay_tags,
				GROUP_CONCAT(DISTINCT tcr.tag_name ORDER BY tcr.tag_name ASC SEPARATOR ', ') AS room_tags
			FROM
				moongcletrip.partners p
			LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
			LEFT JOIN moongcletrip.room_rateplan rr ON rr.room_idx = r.room_idx
			LEFT JOIN moongcletrip.rateplans rp ON rp.rateplan_idx = rr.rateplan_idx
			LEFT JOIN moongcletrip.tag_connections tc ON tc.item_idx = p.partner_detail_idx AND tc.item_type = 'stay'
			LEFT JOIN moongcletrip.tag_connections tcr ON tcr.item_idx = r.room_idx AND tcr.item_type = 'room'
			WHERE p.partner_onda_idx = partnerOndaIdx
			GROUP BY
				p.partner_onda_idx,
				p.partner_name,
				r.room_onda_idx,
				r.room_name,
				rr.rateplan_onda_idx,
				rp.rateplan_name
		";

		$bindings = [
			'partnerOndaIdx' => $partnerOndaIdx
		];

		$stays = Database::getInstance()->getConnection()->select($sql, $bindings);

		$data = [];

		foreach ($stays as $stay) {
			$data[] = [
				$stay->partner_idx,
				$stay->partner_onda_idx,
				$stay->partner_name,
				$stay->room_onda_idx,
				$stay->room_name,
				$stay->room_other_notes,
				$stay->rateplan_onda_idx,
				$stay->rateplan_name,
				$stay->rateplan_description,
				$stay->stay_tags,
				$stay->room_tags,
				'', // Stay Tags
				'', // Room Benefits
				'', // Room Tags
				'', // Rateplan Benefits
				'', // Rateplan Tags
			];
		}

		// 데이터 삽입
		$sheet->fromArray($data, NULL, 'A2');

		// 엑셀 파일 저장
		$writer = new Xlsx($spreadsheet);

		// 출력 버퍼로 스트림
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="stays-' . $partnerOndaIdx . '-' . date('YmdHis') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // 파일 대신 출력 스트림으로 저장
		exit;
	}

	public static function downloadOndaStayOnServer(array $partnerOndaIdxs)
	{
		try {
			// 스프레드시트 객체 생성
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// 헤더 설정
			$headers = [
				'Moongcle ID',
				'Stay ID',
				'Stay Name',
				'Room ID',
				'Room Name',
				'Room Desc',
				'Rateplan ID',
				'Rateplan Name',
				'Rateplan Desc',
				'Onda Stay Tags',
				'Onda Room Tags',
				'Stay Tags',
				'Room Benefits',
				'Room Tags',
				'Rateplan Benefits',
				'Rateplan Tags',
				'Curated Tags',
				'Discount',
			];
			$sheet->fromArray($headers, NULL, 'A1');

			$data = [];

			foreach ($partnerOndaIdxs as $partnerOndaIdx) {
				// partners에서 데이터를 우선 가져옴
				$partner = Database::getInstance()->getConnection()->selectOne("
					SELECT partner_onda_idx, partner_name, partner_idx 
					FROM moongcletrip.partners 
					WHERE partner_onda_idx = :partnerOndaIdx
				", ['partnerOndaIdx' => $partnerOndaIdx]);

				if (!$partner) {
					// partners에 데이터가 없는 경우, onda_idx만 추가
					$data[] = [
						'',
						$partnerOndaIdx,
						'', // partner_name (없음)
						'', // room_onda_idx
						'', // room_name
						'', // room_desc
						'', // rateplan_onda_idx
						'', // rateplan_name
						'', // rateplan_desc
						'', // stay_tags
						'', // room_tags
						'', // Stay Tags
						'', // Room Benefits
						'', // Room Tags
						'', // Rateplan Benefits
						'', // Rateplan Tags
					];
					continue;
				}

				// rooms와 rateplans 포함 데이터를 가져옴
				$sql = "
					SELECT
						p.partner_idx,
						p.partner_onda_idx,
						p.partner_name,
						r.room_onda_idx,
						r.room_name,
						r.room_other_notes,
						rr.rateplan_onda_idx,
						rp.rateplan_name,
						rp.rateplan_description,
						GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ', ') AS stay_tags,
						GROUP_CONCAT(DISTINCT tcr.tag_name ORDER BY tcr.tag_name ASC SEPARATOR ', ') AS room_tags
					FROM
						moongcletrip.partners p
					LEFT JOIN moongcletrip.rooms r ON r.partner_idx = p.partner_idx
					LEFT JOIN moongcletrip.room_rateplan rr ON rr.room_idx = r.room_idx
					LEFT JOIN moongcletrip.rateplans rp ON rp.rateplan_idx = rr.rateplan_idx
					LEFT JOIN moongcletrip.tag_connections tc ON tc.item_idx = p.partner_detail_idx AND tc.item_type = 'stay'
					LEFT JOIN moongcletrip.tag_connections tcr ON tcr.item_idx = r.room_idx AND tcr.item_type = 'room'
					WHERE p.partner_onda_idx = :partnerOndaIdx
					GROUP BY
						p.partner_onda_idx,
						p.partner_name,
						r.room_onda_idx,
						r.room_name,
						rr.rateplan_onda_idx,
						rp.rateplan_name
				";

				$bindings = ['partnerOndaIdx' => $partnerOndaIdx];
				$stays = Database::getInstance()->getConnection()->select($sql, $bindings);

				if (empty($stays)) {
					// stays가 비어 있는 경우 partner만 추가
					$data[] = [
						$partner->partner_idx,
						$partner->partner_onda_idx,
						$partner->partner_name,
						'', // room_onda_idx
						'', // room_name
						'', // room_desc
						'', // rateplan_onda_idx
						'', // rateplan_name
						'', // rateplan_desc
						'', // stay_tags
						'', // room_tags
						'', // Stay Tags
						'', // Room Benefits
						'', // Room Tags
						'', // Rateplan Benefits
						'', // Rateplan Tags
					];
				} else {
					// stays 결과가 있는 경우 추가
					foreach ($stays as $stay) {
						$data[] = [
							$stay->partner_idx,
							$stay->partner_onda_idx,
							$stay->partner_name,
							$stay->room_onda_idx,
							$stay->room_name,
							$stay->room_other_notes,
							$stay->rateplan_onda_idx,
							$stay->rateplan_name,
							$stay->rateplan_description,
							$stay->stay_tags,
							$stay->room_tags,
							'', // Stay Tags
							'', // Room Benefits
							'', // Room Tags
							'', // Rateplan Benefits
							'', // Rateplan Tags
						];
					}
				}
			}

			// 데이터 삽입
			$sheet->fromArray($data, NULL, 'A2');

			// 엑셀 파일 저장 경로
			$outputFile = __DIR__ . '/stays-' . date('YmdHis') . '.xlsx';

			// 엑셀 파일 저장
			$writer = new Xlsx($spreadsheet);
			$writer->save($outputFile);

			echo "Excel file generated: $outputFile\n";
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public static function downloadUsers()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 6) {
				header('Location: /manage/login');
				exit;
			}
		}

		$searchField = $_GET['field'] ?? null;
		$searchValue = $_GET['keyword'] ?? null;

		$allowedFields = [
			'user_id',
			'user_nickname',
			'user_email',
			'reservation_name',
			'reservation_email',
			'reservation_phone',
			'user_agree_marketing'
		];

		$where = " WHERE u.user_is_guest = 0 ";
		$bindings = [];

		if (in_array($searchField, $allowedFields) && !empty($searchValue)) {
			$where .= "AND u.{$searchField} LIKE ?";
			$bindings[] = "%{$searchValue}%";
		}

		$sql = "
			SELECT
				u.user_idx,
				u.user_is_guest,
				u.user_id,
				u.user_nickname,
				u.user_email,
				u.reservation_name,
				u.reservation_email,
				u.reservation_phone,
				u.user_agree_marketing,
				u.user_created_at
			FROM moongcletrip.users u
			$where
			ORDER BY u.user_created_at DESC
		";

		$users = Database::getInstance()->getConnection()->select($sql, $bindings);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$headers = ['IDX', 'GUEST?', 'ID', '닉네임', '이메일', '예약자명', '예약 이메일', '전화번호', '마케팅동의', '가입일'];
		$sheet->fromArray($headers, null, 'A1');

		$rowNum = 2;
		foreach ($users as $user) {
			$sheet->fromArray([
				$user->user_idx,
				$user->user_is_guest ? 'Yes' : 'No',
				$user->user_id,
				$user->user_nickname,
				$user->user_email,
				$user->reservation_name,
				$user->reservation_email,
				$user->reservation_phone,
				$user->user_agree_marketing ? 'Yes' : 'No',
				$user->user_created_at,
			], null, "A$rowNum");
			$rowNum++;
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="user_list.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}
