<?php

namespace App\Services;

class PartnerTagService
{
	public static function getStayTypes($tagData)
	{
		$tags = [
            [
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type'
			],
			[
				'tag_name' => '호텔',
				'tag_machine_name' => 'hotel'
			],
			[
				'tag_name' => '리조트',
				'tag_machine_name' => 'resort'
			],
			[
				'tag_name' => '레지던스',
				'tag_machine_name' => 'residence'
			],
			[
				'tag_name' => '풀빌라',
				'tag_machine_name' => 'private_pool_villa'
			],
			[
				'tag_name' => '한옥',
				'tag_machine_name' => 'hanok_traditional_house'
			],
			[
				'tag_name' => '펜션',
				'tag_machine_name' => 'pension'
			],
			[
				'tag_name' => '카라반',
				'tag_machine_name' => 'caravan'
			],
			[
				'tag_name' => '글램핑',
				'tag_machine_name' => 'glamping'
			],
			[
				'tag_name' => '캠핑',
				'tag_machine_name' => 'camping'
			],
			[
				'tag_name' => '게스트하우스',
				'tag_machine_name' => 'guesthouse'
			],
			[
				'tag_name' => '모텔',
				'tag_machine_name' => 'motel'
			],
			[
				'tag_name' => '감성 숙소',
				'tag_machine_name' => 'emotional_accommodation'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getStayTypeDetail($tagData)
	{
		$tags = [
			[
				'tag_name' => '가족',
				'tag_machine_name' => 'family'
			],
			[
				'tag_name' => '고급/럭셔리',
				'tag_machine_name' => 'luxury'
			],
			[
				'tag_name' => '단체/MT/워크샵',
				'tag_machine_name' => 'group_MT_workshop'
			],
			[
				'tag_name' => '커플',
				'tag_machine_name' => 'couple'
			],
			[
				'tag_name' => '애견펜션',
				'tag_machine_name' => 'pet_friendly_pension'
			],
			[
				'tag_name' => '키즈펜션',
				'tag_machine_name' => 'kids_friendly_pension'
			],
			[
				'tag_name' => '5성',
				'tag_machine_name' => '5_star'
			],
			[
				'tag_name' => '4성',
				'tag_machine_name' => '4_star'
			],
			[
				'tag_name' => '3성',
				'tag_machine_name' => '3_star'
			],
			[
				'tag_name' => '2성',
				'tag_machine_name' => '2_star'
			],
			[
				'tag_name' => '1성',
				'tag_machine_name' => '1_star'
			],
			// [
			// 	'tag_name' => '특급1급',
			// 	'tag_machine_name' => 'first_class_and_grade_1'
			// ],
			[
				'tag_name' => '특급',
				'tag_machine_name' => 'first_class'
			],
			// [
			// 	'tag_name' => '1급',
			// 	'tag_machine_name' => 'grade_1'
			// ],
			[
				'tag_name' => '관광',
				'tag_machine_name' => 'tourism'
			],
			[
				'tag_name' => '비즈니스',
				'tag_machine_name' => 'business'
			],
			[
				'tag_name' => '부티크',
				'tag_machine_name' => 'boutique'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getStayFacilityTags($tagData)
	{
		$tags = [
            [
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available'
			],
            [
				'tag_name' => '공용샤워실',
				'tag_machine_name' => 'shared_shower_room'
			],
			[
				'tag_name' => '실내수영장',
				'tag_machine_name' => 'indoor_pool'
			],
			[
				'tag_name' => '야외 수영장',
				'tag_machine_name' => 'outdoor_pool'
			],
			[
				'tag_name' => '인피니티풀',
				'tag_machine_name' => 'infinity_pool'
			],
			[
				'tag_name' => '사계절 온수/미온수풀',
				'tag_machine_name' => 'year_round_heated_pool'
			],
			[
				'tag_name' => '어린이수영장',
				'tag_machine_name' => 'kids_pool'
			],
			[
				'tag_name' => '수영장',
				'tag_machine_name' => 'swimming_pool'
			],
			[
				'tag_name' => '워터슬라이드',
				'tag_machine_name' => 'water_slide'
			],
			[
				'tag_name' => '워터파크',
				'tag_machine_name' => 'water_park'
			],
			[
				'tag_name' => '스파/월풀',
				'tag_machine_name' => 'spa_whirlpool'
			],
			// [
			// 	'tag_name' => '공용스파',
			// 	'tag_machine_name' => 'shared_spa'
			// ],
			[
				'tag_name' => '찜질방',
				'tag_machine_name' => 'jjimjilbang_sauna'
			],
			[
				'tag_name' => '온천',
				'tag_machine_name' => 'hot_spring'
			],
			[
				'tag_name' => '사우나',
				'tag_machine_name' => 'sauna'
			],
            [
				'tag_name' => '패밀리룸',
				'tag_machine_name' => 'family_room'
			],
			[
				'tag_name' => '패밀리룸 보유(3인)',
				'tag_machine_name' => 'family_room_for_3_people'
			],
			[
				'tag_name' => '패밀리룸 보유(4인)',
				'tag_machine_name' => 'family_room_for_4_people'
			],
			[
				'tag_name' => '대형 객실 보유(5인+)',
				'tag_machine_name' => 'large_rooms_for_5_or_more_people'
			],
			[
				'tag_name' => '장애인객실',
				'tag_machine_name' => 'accessible_rooms'
			],
			[
				'tag_name' => 'OTT(넷플릭스 등)',
				'tag_machine_name' => 'OTT_services_(e.g._Netflix)'
			],
			[
				'tag_name' => '바/라운지',
				'tag_machine_name' => 'bar_or_lounge'
			],
			[
				'tag_name' => '미슐랭 레스토랑 보유',
				'tag_machine_name' => 'michelin_starred_restaurant'
			],
			[
				'tag_name' => '카페',
				'tag_machine_name' => 'cafe'
			],
			[
				'tag_name' => '레스토랑',
				'tag_machine_name' => 'restaurant'
			],
			[
				'tag_name' => '매점/편의점',
				'tag_machine_name' => 'convenience_store'
			],
			[
				'tag_name' => '바베큐장',
				'tag_machine_name' => 'barbecue_area'
			],
			[
				'tag_name' => '오락실',
				'tag_machine_name' => 'arcade_room'
			],
			[
				'tag_name' => '셀프세탁실',
				'tag_machine_name' => 'self_laundry_facilities'
			],
			[
				'tag_name' => '노래방',
				'tag_machine_name' => 'karaoke'
			],
			[
				'tag_name' => '농구장',
				'tag_machine_name' => 'basketball_court'
			],
			[
				'tag_name' => '족구장',
				'tag_machine_name' => 'foot_volleyball_court'
			],
			[
				'tag_name' => '축구장/풋살장',
				'tag_machine_name' => 'soccer_field_futsal_field'
			],
			[
				'tag_name' => '탁구장',
				'tag_machine_name' => 'table_tennis_room'
			],
			[
				'tag_name' => '피트니스',
				'tag_machine_name' => 'fitness_center'
			],
			[
				'tag_name' => '골프장',
				'tag_machine_name' => 'golf_course'
			],
			[
				'tag_name' => '연회장',
				'tag_machine_name' => 'banquet_hall'
			],
			[
				'tag_name' => '세미나실',
				'tag_machine_name' => 'seminar_room'
			],
			[
				'tag_name' => '파티룸 보유',
				'tag_machine_name' => 'party_room'
			],
			[
				'tag_name' => '비즈니스센터',
				'tag_machine_name' => 'business_center'
			],
			[
				'tag_name' => '루프탑',
				'tag_machine_name' => 'rooftop'
			],
			[
				'tag_name' => '유아시설',
				'tag_machine_name' => 'infant_facilities'
			],
			[
				'tag_name' => '키즈플레이룸',
				'tag_machine_name' => 'kids_playroom'
			],
			[
				'tag_name' => '24시간 데스크',
				'tag_machine_name' => '24_hour_front_desk'
			],
			[
				'tag_name' => '공용화장실',
				'tag_machine_name' => 'shared_restroom'
			],
			[
				'tag_name' => '공용주방',
				'tag_machine_name' => 'shared_kitchen'
			],
			[
				'tag_name' => '이그제큐티브 라운지',
				'tag_machine_name' => 'executive_lounge'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getStayAttractionTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '계곡 주변',
				'tag_machine_name' => 'near_valley'
			],
			[
				'tag_name' => '골프장 주변',
				'tag_machine_name' => 'near_golf_course'
			],
			[
				'tag_name' => '낚시장 주변',
				'tag_machine_name' => 'near_fishing_spots'
			],
			[
				'tag_name' => '수목원/휴양림 주변',
				'tag_machine_name' => 'near_botanical_gardens_or_forests'
			],
			[
				'tag_name' => '수상레져',
				'tag_machine_name' => 'water_sports_available'
			],
			[
				'tag_name' => '스키장 주변',
				'tag_machine_name' => 'near_ski_resort'
			],
			[
				'tag_name' => '해수욕장 주변',
				'tag_machine_name' => 'near_beach'
			],
			[
				'tag_name' => '강/호수 주변',
				'tag_machine_name' => 'near_river_or_lake'
			],
			[
				'tag_name' => '공항 근처',
				'tag_machine_name' => 'near_airport'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getStayServiceTags($tagData)
	{
		$tags = [
			[
				'tag_name' => 'WIFI',
				'tag_machine_name' => 'WIFI'
			],
			[
				'tag_name' => '반려동물 동반가능',
				'tag_machine_name' => 'pet_friendly'
			],
			[
				'tag_name' => '발렛파킹',
				'tag_machine_name' => 'valet_parking'
			],
			// [
			// 	'tag_name' => '보드게임',
			// 	'tag_machine_name' => 'board_games'
			// ],
			[
				'tag_name' => '셔틀버스',
				'tag_machine_name' => 'shuttle_bus'
			],
			[
				'tag_name' => '영화관람',
				'tag_machine_name' => 'movie_screening'
			],
			[
				'tag_name' => '자전거대여',
				'tag_machine_name' => 'bicycle_rental'
			],
			[
				'tag_name' => '조식 서비스',
				'tag_machine_name' => 'breakfast_service'
			],
			[
				'tag_name' => '짐보관',
				'tag_machine_name' => 'luggage_storage'
			],
			[
				'tag_name' => '취사가능',
				'tag_machine_name' => 'cooking_allowed'
			],
			[
				'tag_name' => '캠프파이어',
				'tag_machine_name' => 'campfire_available'
			],
			[
				'tag_name' => '프로포즈/파티/이벤트',
				'tag_machine_name' => 'proposals_parties_events'
			],
			[
				'tag_name' => '프린터 사용',
				'tag_machine_name' => 'printer_usage'
			],
			[
				'tag_name' => '픽업',
				'tag_machine_name' => 'pickup_service_available'
			],
			[
				'tag_name' => '상비약',
				'tag_machine_name' => 'basic_first_aid_kit'
			],
			[
				'tag_name' => '금연',
				'tag_machine_name' => 'non_smoking'
			],
			// [
			// 	'tag_name' => '객실내흡연',
			// 	'tag_machine_name' => 'in_room_smoking_allowed'
			// ],
			// [
			// 	'tag_name' => '개인사물함',
			// 	'tag_machine_name' => 'personal_lockers'
			// ],
			[
				'tag_name' => '무료주차',
				'tag_machine_name' => 'free_parking'
			],
			[
				'tag_name' => '장애인편의시설',
				'tag_machine_name' => 'accessible_facilities'
			],
			[
				'tag_name' => '공항 셔틀',
				'tag_machine_name' => 'airport_shuttle'
			],
			// [
			// 	'tag_name' => '바/라운지',
			// 	'tag_machine_name' => 'bar_or_lounge'
			// ],
			[
				'tag_name' => '마사지',
				'tag_machine_name' => 'massage_service'
			],
			[
				'tag_name' => '주차가능',
				'tag_machine_name' => 'parking_available'
			],
			// [
			// 	'tag_name' => '기본양념',
			// 	'tag_machine_name' => 'basic_seasonings_provided'
			// ],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getStayPetTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '소형견 가능',
				'tag_machine_name' => 'small_dog_allowed'
			],
			[
				'tag_name' => '중형견 가능',
				'tag_machine_name' => 'medium_dog_allowed'
			],
			[
				'tag_name' => '대형견 가능',
				'tag_machine_name' => 'large_dog_allowed'
			],
			[
				'tag_name' => '맹견 가능',
				'tag_machine_name' => 'dangerous_dog_allowed'
			],
			[
				'tag_name' => '반려묘 동반가능',
				'tag_machine_name' => 'cat_allowed'
			],
			[
				'tag_name' => '1마리 가능',
				'tag_machine_name' => 'one_pet_allowed'
			],
			[
				'tag_name' => '2마리 가능',
				'tag_machine_name' => 'two_pets_allowed'
			],
			[
				'tag_name' => '3마리 가능',
				'tag_machine_name' => 'three_pets_allowed'
			],
			[
				'tag_name' => '4마리 가능',
				'tag_machine_name' => 'four_pets_allowed'
			],
			[
				'tag_name' => '마릿수 제한없음',
				'tag_machine_name' => 'pet_no_limit'
			],
			[
				'tag_name' => '무게 ~7kg',
				'tag_machine_name' => 'weight_under_7kg'
			],
			[
				'tag_name' => '무게 ~10kg',
				'tag_machine_name' => 'weight_under_10kg'
			],
			[
				'tag_name' => '무게 ~15kg',
				'tag_machine_name' => 'weight_under_15kg'
			],
			[
				'tag_name' => '무게 ~20kg',
				'tag_machine_name' => 'weight_under_20kg'
			],
			[
				'tag_name' => '무게 제한없음',
				'tag_machine_name' => 'no_weight_limit'
			],
			[
				'tag_name' => '애견전용 놀이터',
				'tag_machine_name' => 'pet_playground'
			],
			[
				'tag_name' => '공용 마당',
				'tag_machine_name' => 'shared_yard'
			],
			[
				'tag_name' => '애견가능 수영장',
				'tag_machine_name' => 'pet_allowed_pool'
			],
			[
				'tag_name' => '애견 드라이기룸',
				'tag_machine_name' => 'pet_dryer_room'
			],
			[
				'tag_name' => '애견 식사 가능 메뉴',
				'tag_machine_name' => 'pet_meal_allowed_menu'
			],
			[
				'tag_name' => '애견 침대/소파',
				'tag_machine_name' => 'pet_bed_sofa'
			],
			[
				'tag_name' => '미끄럼방지 매트',
				'tag_machine_name' => 'non_slip_mat'
			],
			[
				'tag_name' => '펫 하우스',
				'tag_machine_name' => 'pet_house'
			],
			[
				'tag_name' => '배변 패드/봉투',
				'tag_machine_name' => 'waste_pad_bag'
			],
			[
				'tag_name' => '펫 타월',
				'tag_machine_name' => 'pet_towel'
			],
			[
				'tag_name' => '애견 배변판',
				'tag_machine_name' => 'pet_litter_box'
			],
			[
				'tag_name' => '애견 식기',
				'tag_machine_name' => 'pet_dishes'
			],
			[
				'tag_name' => '애견 샴푸',
				'tag_machine_name' => 'pet_shampoo'
			],
			[
				'tag_name' => '애견 간식',
				'tag_machine_name' => 'pet_snacks'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getBarrierfreePublicTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '장애인 전용 주차',
				'tag_machine_name' => 'disabled_parking_only'
			],
			[
				'tag_name' => '체크인데스크(좌식)',
				'tag_machine_name' => 'seated_check_in_desk'
			],
			[
				'tag_name' => '공용공간 장애인화장실',
				'tag_machine_name' => 'shared_restrooms_for_disabled'
			],
			[
				'tag_name' => '엘레베이터',
				'tag_machine_name' => 'elevator_available'
			],
			[
				'tag_name' => '시각장애인 유도블럭',
				'tag_machine_name' => 'tactile_blocks_for_visually_impaired'
			],
			[
				'tag_name' => '수유실',
				'tag_machine_name' => 'nursing_room_available'
			],
			[
				'tag_name' => '휠체어 진입 가능한 중앙문(로비문)',
				'tag_machine_name' => 'wheelchair_accessible_main_door'
			],
			[
				'tag_name' => '부대시설에 휠체어 진입 가능',
				'tag_machine_name' => 'wheelchair_accessible_facilities'
			],
			[
				'tag_name' => '종합 점자안내판',
				'tag_machine_name' => 'braille_overview_map_available'
			],
			[
				'tag_name' => '엘레베이터 버튼 점자',
				'tag_machine_name' => 'braille_elevator_buttons'
			],
			[
				'tag_name' => '휠체어 대여가능',
				'tag_machine_name' => 'wheelchair_rental_available'
			],
			[
				'tag_name' => '청각 장애용 전화기',
				'tag_machine_name' => 'telephone_for_hearing_impaired'
			],
			[
				'tag_name' => '아기침대 대여',
				'tag_machine_name' => 'baby_crib_rental_available'
			],
			[
				'tag_name' => '침대가드 설치가능',
				'tag_machine_name' => 'bed_guard_available'
			],
			[
				'tag_name' => '임산부 필로우 대여',
				'tag_machine_name' => 'pregnancy_pillow_rental'
			],
			[
				'tag_name' => '유모차 대여',
				'tag_machine_name' => 'stroller_rental'
			],
			[
				'tag_name' => '호텔 입구 경사로 설치',
				'tag_machine_name' => 'ramp_installed_at_hotel_entrance'
			],
			[
				'tag_name' => '화장실 내 핸드레일',
				'tag_machine_name' => 'bathroom_handrails'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getBarrierfreeRoomTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '휠체어 통과가능 문',
				'tag_machine_name' => 'wheelchair_accessible_doorways'
			],
			[
				'tag_name' => '낮은 카드키(문 손잡이)',
				'tag_machine_name' => 'low_card_keys_or_door_handles'
			],
			[
				'tag_name' => '핸드레일이 달린 욕조',
				'tag_machine_name' => 'bathtub_with_handrails'
			],
			[
				'tag_name' => '화장실 내 샤워의자',
				'tag_machine_name' => 'shower_chair_available'
			],
			[
				'tag_name' => '낮은 침대높이(45cm이하)',
				'tag_machine_name' => 'low_bed_height_under_45cm'
			],
			[
				'tag_name' => '화장실 냉온수 촉지판',
				'tag_machine_name' => 'temperature_adjustable_taps_in_bathroom'
			],
			[
				'tag_name' => '낮은 옷걸이',
				'tag_machine_name' => 'low_hangers_available'
			],
			[
				'tag_name' => '화장실 내 비상전화기',
				'tag_machine_name' => 'emergency_phone_in_bathroom'
			],
			[
				'tag_name' => '낮은 샤워기',
				'tag_machine_name' => 'low_shower_available'
			],
			[
				'tag_name' => '화장실 내 롤인샤워',
				'tag_machine_name' => 'roll_in_shower_available'
			],
			[
				'tag_name' => '객실 내부 휠체어 회전 가능',
				'tag_machine_name' => 'wheelchair_rotation_space_in_room'
			],
			[
				'tag_name' => '객실번호 점자안내판',
				'tag_machine_name' => 'braille_room_number_signs'
			],
			[
				'tag_name' => '출입구 시각장애인 유도블럭',
				'tag_machine_name' => 'tactile_blocks_at_room_entrances'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getAmenityTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '가스레인지/인덕션',
				'tag_machine_name' => 'gas_stove_or_induction_cooktop'
			],
			[
				'tag_name' => '개별/테라스 바베큐',
				'tag_machine_name' => 'private_or_terrace_barbecue'
			],
			[
				'tag_name' => '기본양념',
				'tag_machine_name' => 'basic_seasonings_provided'
			],
			[
				'tag_name' => '냉장고',
				'tag_machine_name' => 'refrigerator'
			],
			[
				'tag_name' => '다리미',
				'tag_machine_name' => 'iron'
			],
			[
				'tag_name' => '드라이기',
				'tag_machine_name' => 'hairdryer'
			],
			[
				'tag_name' => '미니바',
				'tag_machine_name' => 'mini_bar'
			],
			[
				'tag_name' => '벽난로',
				'tag_machine_name' => 'fireplace'
			],
			[
				'tag_name' => '쇼파',
				'tag_machine_name' => 'sofa'
			],
			[
				'tag_name' => '스파/월풀',
				'tag_machine_name' => 'spa_whirlpool'
			],
			[
				'tag_name' => '식탁',
				'tag_machine_name' => 'dining_table'
			],
			[
				'tag_name' => '에어컨',
				'tag_machine_name' => 'air_conditioner'
			],
			[
				'tag_name' => '욕실용품',
				'tag_machine_name' => 'bathroom_amenities'
			],
			[
				'tag_name' => '욕조',
				'tag_machine_name' => 'bathtub'
			],
			[
				'tag_name' => '전기밥솥',
				'tag_machine_name' => 'electric_rice_cooker'
			],
			[
				'tag_name' => '전자레인지',
				'tag_machine_name' => 'microwave'
			],
			[
				'tag_name' => '취사도구',
				'tag_machine_name' => 'cooking_utensils'
			],
			[
				'tag_name' => '커피포트',
				'tag_machine_name' => 'electric_kettle'
			],
			[
				'tag_name' => '타월',
				'tag_machine_name' => 'towels'
			],
			[
				'tag_name' => 'TV',
				'tag_machine_name' => 'TV'
			],
			[
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available'
			],
			[
				'tag_name' => '비데',
				'tag_machine_name' => 'bidet'
			],
			[
				'tag_name' => '흡연가능',
				'tag_machine_name' => 'smoking_allowed'
			],
			[
				'tag_name' => '화장실',
				'tag_machine_name' => 'toilet'
			],
			[
				'tag_name' => 'VOD',
				'tag_machine_name' => 'VOD_services'
			],
			[
				'tag_name' => '알람시계',
				'tag_machine_name' => 'alarm_clock'
			],
			[
				'tag_name' => '무료생수',
				'tag_machine_name' => 'free_bottled_water'
			],
			[
				'tag_name' => '책상',
				'tag_machine_name' => 'desk'
			],
			[
				'tag_name' => '안전금고',
				'tag_machine_name' => 'safety_box'
			],
			[
				'tag_name' => '전화',
				'tag_machine_name' => 'telephone'
			],
			[
				'tag_name' => '욕실',
				'tag_machine_name' => 'bathroom'
			],
			[
				'tag_name' => '목욕가운',
				'tag_machine_name' => 'bathrobe'
			],
			[
				'tag_name' => '슬리퍼',
				'tag_machine_name' => 'slippers'
			],
			[
				'tag_name' => '옷장',
				'tag_machine_name' => 'closet'
			],
			[
				'tag_name' => '칫솔/치약',
				'tag_machine_name' => 'toothbrush_and_toothpaste'
			],
			[
				'tag_name' => '샴푸/바디샴푸/컨디셔너',
				'tag_machine_name' => 'shampoo_bodywash_conditioner'
			],
			[
				'tag_name' => '와인잔',
				'tag_machine_name' => 'wine_glasses'
			],
			[
				'tag_name' => '샤워부스',
				'tag_machine_name' => 'shower_booth'
			],
			[
				'tag_name' => '발코니/테라스',
				'tag_machine_name' => 'balcony_or_terrace'
			],
			[
				'tag_name' => '난방',
				'tag_machine_name' => 'heating'
			],
			[
				'tag_name' => '블루투스 스피커',
				'tag_machine_name' => 'bluetooth_speaker'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getRoomtypeTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '도미토리형',
				'tag_machine_name' => 'dormitory_type'
			],
			[
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type'
			],
			[
				'tag_name' => '복층형',
				'tag_machine_name' => 'duplex_type'
			],
			[
				'tag_name' => '원룸형',
				'tag_machine_name' => 'studio_type'
			],
			[
				'tag_name' => '분리형',
				'tag_machine_name' => 'separate_room_type'
			],
			[
				'tag_name' => '싱글룸',
				'tag_machine_name' => 'single_room'
			],
			[
				'tag_name' => '더블룸',
				'tag_machine_name' => 'double_room'
			],
			[
				'tag_name' => '트윈룸',
				'tag_machine_name' => 'twin_room'
			],
			[
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room'
			],
			[
				'tag_name' => '트리플룸',
				'tag_machine_name' => 'triple_room'
			],
			[
				'tag_name' => '패밀리룸',
				'tag_machine_name' => 'family_room'
			],
			[
				'tag_name' => '스위트룸',
				'tag_machine_name' => 'suite_room'
			],
			[
				'tag_name' => '펜트하우스',
				'tag_machine_name' => 'penthouse'
			],
			[
				'tag_name' => '여성전용',
				'tag_machine_name' => 'female_only'
			],
			[
				'tag_name' => '남성전용',
				'tag_machine_name' => 'male_only'
			],
			[
				'tag_name' => '남녀공용',
				'tag_machine_name' => 'mixed_gender'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

	public static function getViewTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '바다 전망',
				'tag_machine_name' => 'sea_view'
			],
			[
				'tag_name' => '산 전망',
				'tag_machine_name' => 'mountain_view'
			],
			[
				'tag_name' => '도시 전망',
				'tag_machine_name' => 'city_view'
			],
			[
				'tag_name' => '정원 전망',
				'tag_machine_name' => 'garden_view'
			],
			[
				'tag_name' => '수영장 전망',
				'tag_machine_name' => 'pool_view'
			],
			[
				'tag_name' => '강 전망',
				'tag_machine_name' => 'river_view'
			],
			[
				'tag_name' => '항구 전망',
				'tag_machine_name' => 'harbor_view'
			],
			[
				'tag_name' => '기타 전망',
				'tag_machine_name' => 'etc_view'
			],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

    public static function getSearchBadgeTags($tagData)
	{
		$tags = [
            [
				'tag_name' => '아이와 갈만한 곳',
				'tag_machine_name' => 'with_kids'
            ],
            [
				'tag_name' => '가성비 중요',
				'tag_machine_name' => 'value_for_money_important'
            ],
            [
				'tag_name' => '이국적인',
				'tag_machine_name' => 'exotic_atmosphere'
            ],
            [
				'tag_name' => '로맨틱 분위기',
				'tag_machine_name' => 'romantic_atmosphere'
            ],
            [
				'tag_name' => '데이트장소 추천',
				'tag_machine_name' => 'romantic_spot_recommendation'
            ],
            [
				'tag_name' => '도심 속 호캉스',
				'tag_machine_name' => 'urban_hotel_staycation'
            ],
            [
				'tag_name' => '숲캉스',
				'tag_machine_name' => 'forest_staycation'
            ],
            [
				'tag_name' => '야경즐기기',
				'tag_machine_name' => 'enjoy_nightscape'
            ],
            [
				'tag_name' => '자연과 함께',
				'tag_machine_name' => 'with_nature'
            ],
            [
				'tag_name' => '여유로운 힐링',
				'tag_machine_name' => 'relaxing_healing'
            ],
            [
				'tag_name' => '사색있는 여행',
				'tag_machine_name' => 'reflective_travel'
            ],
            [
				'tag_name' => '액티비티 체험',
				'tag_machine_name' => 'activity_needed'
            ],
            [
				'tag_name' => '이색적인 체험',
				'tag_machine_name' => 'unique_experiences'
            ],
            [
				'tag_name' => '효도 여행',
				'tag_machine_name' => 'filial_piety_trip'
            ],
            [
				'tag_name' => '주변먹거리 많음',
				'tag_machine_name' => 'plenty_of_nearby_food_options'
            ],
            [
				'tag_name' => '일주일 살기',
				'tag_machine_name' => 'one_week_stay'
            ],
            [
				'tag_name' => '한달 살기',
				'tag_machine_name' => 'one_month_stay'
            ],
            [
				'tag_name' => '워케이션',
				'tag_machine_name' => 'workation'
            ],
            [
				'tag_name' => '커플기념일',
				'tag_machine_name' => 'couple_anniversary'
            ],
            [
				'tag_name' => '결혼기념일',
				'tag_machine_name' => 'wedding_anniversary'
            ],
            [
				'tag_name' => '프로포즈',
				'tag_machine_name' => 'proposal'
            ],
            [
				'tag_name' => '조식 서비스',
				'tag_machine_name' => 'breakfast_service'
            ],
            [
				'tag_name' => '수영장',
				'tag_machine_name' => 'swimming_pool'
            ],
            [
				'tag_name' => '사계절 온수/미온수풀',
				'tag_machine_name' => 'year_round_heated_pool'
            ],
            [
				'tag_name' => '인피니티풀',
				'tag_machine_name' => 'infinity_pool'
            ],
            [
				'tag_name' => '실내수영장',
				'tag_machine_name' => 'indoor_pool'
            ],
            [
				'tag_name' => '야외 수영장',
				'tag_machine_name' => 'outdoor_pool'
            ],
            [
				'tag_name' => '어린이수영장',
				'tag_machine_name' => 'kids_pool'
            ],
            [
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available'
            ],
            [
				'tag_name' => '워터파크',
				'tag_machine_name' => 'water_park'
            ],
            [
				'tag_name' => 'SNS에서 인기많은',
				'tag_machine_name' => 'popular_on_SNS'
            ],
            [
				'tag_name' => '저렴하지만 깨끗한',
				'tag_machine_name' => 'clean_but_affordable'
            ],
            [
				'tag_name' => '힙한 분위기',
				'tag_machine_name' => 'hip_atmosphere'
            ],
            [
				'tag_name' => '감성 넘치는 분위기',
				'tag_machine_name' => 'emotional_vibe'
            ],
            [
				'tag_name' => '반려동물 동반가능',
				'tag_machine_name' => 'pet_friendly'
            ],
            [
				'tag_name' => 'OTT(넷플릭스 등)',
				'tag_machine_name' => 'OTT_services_(e.g._Netflix)'
            ],
            [
				'tag_name' => '바베큐장',
				'tag_machine_name' => 'barbecue_area'
            ],
            [
				'tag_name' => '취사가능',
				'tag_machine_name' => 'cooking_allowed'
            ],
            [
				'tag_name' => '피트니스',
				'tag_machine_name' => 'fitness_center'
            ],
            [
				'tag_name' => '키즈플레이룸',
				'tag_machine_name' => 'kids_playroom'
            ],
            [
				'tag_name' => '사우나',
				'tag_machine_name' => 'sauna'
            ],
            [
				'tag_name' => '패밀리룸 보유(3인)',
				'tag_machine_name' => 'family_room_for_3_people'
            ],
            [
				'tag_name' => '패밀리룸 보유(4인)',
				'tag_machine_name' => 'family_room_for_4_people'
            ],
            [
				'tag_name' => '대형 객실 보유(5인+)',
				'tag_machine_name' => 'large_rooms_for_5_or_more_people'
            ],
            [
				'tag_name' => '단체/MT/워크샵',
				'tag_machine_name' => 'group_MT_workshop'
            ],
            [
				'tag_name' => '전망좋은 곳',
				'tag_machine_name' => 'places_with_great_views'
            ],
            [
				'tag_name' => '바다 전망',
				'tag_machine_name' => 'sea_view'
            ],
            [
				'tag_name' => '산 전망',
				'tag_machine_name' => 'mountain_view'
            ],
            [
				'tag_name' => '도시 전망',
				'tag_machine_name' => 'city_view'
            ],
            [
				'tag_name' => '강 전망',
				'tag_machine_name' => 'river_view'
            ],
            [
				'tag_name' => '골프장 주변',
				'tag_machine_name' => 'near_golf_course'
            ],
            [
				'tag_name' => '스키장 주변',
				'tag_machine_name' => 'near_ski_resort'
            ],
            [
				'tag_name' => '해수욕장 주변',
				'tag_machine_name' => 'near_beach'
            ],
            [
				'tag_name' => '공항 근처',
				'tag_machine_name' => 'near_airport'
            ],
            [
				'tag_name' => '캐릭터룸 보유',
				'tag_machine_name' => 'character_rooms_available'
            ],
            [
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type'
            ],
            [
				'tag_name' => '패밀리룸',
				'tag_machine_name' => 'family_room'
            ],
            [
				'tag_name' => '5성',
				'tag_machine_name' => '5_star'
            ],
            [
				'tag_name' => '4성',
				'tag_machine_name' => '4_star'
            ],
            [
				'tag_name' => '워터슬라이드',
				'tag_machine_name' => 'water_slide'
            ],
            [
				'tag_name' => '찜질방',
				'tag_machine_name' => 'jjimjilbang_sauna'
            ],
            [
				'tag_name' => '온천',
				'tag_machine_name' => 'hot_spring'
            ],
            [
				'tag_name' => '유아시설',
				'tag_machine_name' => 'infant_facilities'
            ],
            [
				'tag_name' => '미슐랭 레스토랑 보유',
				'tag_machine_name' => 'michelin_starred_restaurant'
            ],
            [
				'tag_name' => '이그제큐티브 라운지',
				'tag_machine_name' => 'executive_lounge'
            ],
            [
				'tag_name' => '자전거대여',
				'tag_machine_name' => 'bicycle_rental'
            ],
            [
				'tag_name' => '아기침대 대여',
				'tag_machine_name' => 'baby_crib_rental_available'
            ],
            [
				'tag_name' => '침대가드 설치가능',
				'tag_machine_name' => 'bed_guard_available'
            ],
            [
				'tag_name' => '유모차 대여',
				'tag_machine_name' => 'stroller_rental'
            ],
            [
				'tag_name' => '애견전용 놀이터',
				'tag_machine_name' => 'pet_playground'
            ],
            [
				'tag_name' => '애견가능 수영장',
				'tag_machine_name' => 'pet_allowed_pool'
            ],
		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}

    public static function getNewStayTasteTags($tagData)
	{
		$tags = [
			[
				'tag_name' => '아이와 갈만한 곳',
				'tag_machine_name' => 'with_kids'
            ],
            [
				'tag_name' => '가성비 중요',
				'tag_machine_name' => 'value_for_money_important'
            ],
            [
				'tag_name' => '여유로운 힐링',
				'tag_machine_name' => 'relaxing_healing'
            ],
            [
				'tag_name' => '자연과 함께',
				'tag_machine_name' => 'with_nature'
            ],
            [
				'tag_name' => '전망좋은 곳',
				'tag_machine_name' => 'places_with_great_views'
            ],
            [
				'tag_name' => '이색적인 체험',
				'tag_machine_name' => 'unique_experiences'
            ],
            [
				'tag_name' => '여유로운 평일여행',
				'tag_machine_name' => 'relaxed_weekday_travel'
            ],
            [
				'tag_name' => '저렴하지만 깨끗한',
				'tag_machine_name' => 'clean_but_affordable'
            ],
            [
				'tag_name' => '감성 넘치는 분위기',
				'tag_machine_name' => 'emotional_vibe'
            ],
            [
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room'
            ],
            [
				'tag_name' => '캐릭터룸 보유',
				'tag_machine_name' => 'character_rooms_available'
            ],
            [
                'tag_name' => '데이트장소 추천',
                'tag_machine_name' => 'romantic_spot_recommendation',
            ],
            [
                'tag_name' => '로맨틱 분위기',
                'tag_machine_name' => 'romantic_atmosphere',
            ],
            [
                'tag_name' => '이국적인',
                'tag_machine_name' => 'exotic_atmosphere',
            ],

		];

		foreach ($tags as &$tag) {
            $machineName = $tag['tag_machine_name'];
            $tag['tag_idx'] = isset($tagData[$machineName]) ? $tagData[$machineName]['tag_idx'] : null;
        }

		return $tags;
	}
}
