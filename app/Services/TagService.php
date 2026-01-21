<?php

namespace App\Services;

class TagService
{
	// public function getHomeMainTags()
	// {
	// 	$tags = [
	// 		[
	// 			'tag_name' => '혼자',
	// 			'tag_machine_name' => 'alone'
	// 		],
	// 		[
	// 			'tag_name' => '친구와',
	// 			'tag_machine_name' => 'with_friends'
	// 		],
	// 		[
	// 			'tag_name' => '연인과',
	// 			'tag_machine_name' => 'couple'
	// 		],
	// 		[
	// 			'tag_name' => '배우자와',
	// 			'tag_machine_name' => 'with_spouse'
	// 		],
	// 		[
	// 			'tag_name' => '아이와',
	// 			'tag_machine_name' => 'with_kids'
	// 		],
	// 		[
	// 			'tag_name' => '가족과',
	// 			'tag_machine_name' => 'family'
	// 		],
	// 		[
	// 			'tag_name' => '부모님과',
	// 			'tag_machine_name' => 'with_parents'
	// 		],
	// 		[
	// 			'tag_name' => '반려동물과',
	// 			'tag_machine_name' => 'pet_friendly'
	// 		],
	// 		[
	// 			'tag_name' => '직장동료',
	// 			'tag_machine_name' => 'with_colleagues'
	// 		],
	// 		[
	// 			'tag_name' => '단체',
	// 			'tag_machine_name' => 'group_MT_workshop'
	// 		],
	// 		[
	// 			'tag_name' => '서울',
	// 			'tag_machine_name' => 'seoul'
	// 		],
	// 		[
	// 			'tag_name' => '제주',
	// 			'tag_machine_name' => 'jeju'
	// 		],
	// 		[
	// 			'tag_name' => '부산',
	// 			'tag_machine_name' => 'busan'
	// 		],
	// 		[
	// 			'tag_name' => '인천',
	// 			'tag_machine_name' => 'incheon'
	// 		],
	// 		[
	// 			'tag_name' => '강릉',
	// 			'tag_machine_name' => 'gangneung'
	// 		],
	// 		[
	// 			'tag_name' => '속초',
	// 			'tag_machine_name' => 'sokcho'
	// 		],
	// 		[
	// 			'tag_name' => '여수',
	// 			'tag_machine_name' => 'yeosu'
	// 		],
	// 		[
	// 			'tag_name' => '경주',
	// 			'tag_machine_name' => 'gyeongju'
	// 		],
	// 		[
	// 			'tag_name' => '대구',
	// 			'tag_machine_name' => 'daegu'
	// 		],
	// 		[
	// 			'tag_name' => '대전',
	// 			'tag_machine_name' => 'daejeon'
	// 		],
	// 		[
	// 			'tag_name' => '울산',
	// 			'tag_machine_name' => 'ulsan'
	// 		],
	// 		[
	// 			'tag_name' => '평창',
	// 			'tag_machine_name' => 'pyeongchang'
	// 		],
	// 		[
	// 			'tag_name' => '전주',
	// 			'tag_machine_name' => 'jeonju'
	// 		],
	// 		// [
	// 		// 	'tag_name' => '군산',
	// 		// 	'tag_machine_name' => 'jeonju'
	// 		// ],
	// 		[
	// 			'tag_name' => '양양',
	// 			'tag_machine_name' => 'yangyang'
	// 		],
	// 		[
	// 			'tag_name' => '아이와 갈만한 곳',
	// 			'tag_machine_name' => 'places_to_visit_with_kids',
	// 		],
	// 		[
	// 			'tag_name' => '가성비 중요',
	// 			'tag_machine_name' => 'value_for_money_important',
	// 		],
	// 		[
	// 			'tag_name' => '이국적인',
	// 			'tag_machine_name' => 'exotic_atmosphere',
	// 		],
	// 		[
	// 			'tag_name' => '로맨틱 분위기',
	// 			'tag_machine_name' => 'romantic_atmosphere',
	// 		],
	// 		[
	// 			'tag_name' => '데이트장소 추천',
	// 			'tag_machine_name' => 'romantic_spot_recommendation',
	// 		],
	// 		[
	// 			'tag_name' => '도심 속 호캉스',
	// 			'tag_machine_name' => 'urban_hotel_staycation',
	// 		],
	// 		[
	// 			'tag_name' => '숲캉스',
	// 			'tag_machine_name' => 'forest_staycation',
	// 		],
	// 		[
	// 			'tag_name' => '야경즐기기',
	// 			'tag_machine_name' => 'enjoy_nightscape',
	// 		],
	// 		[
	// 			'tag_name' => '여유로운 평일여행',
	// 			'tag_machine_name' => 'relaxed_weekday_travel',
	// 		],
	// 		[
	// 			'tag_name' => '자연과 함께',
	// 			'tag_machine_name' => 'with_nature',
	// 		],
	// 		[
	// 			'tag_name' => '여유로운 힐링',
	// 			'tag_machine_name' => 'relaxing_healing',
	// 		],
	// 		[
	// 			'tag_name' => '사색있는 여행',
	// 			'tag_machine_name' => 'reflective_travel',
	// 		],
	// 		[
	// 			'tag_name' => '체인호텔 선호',
	// 			'tag_machine_name' => 'prefer_chain_hotels',
	// 		],
	// 		[
	// 			'tag_name' => '리뷰좋은 곳',
	// 			'tag_machine_name' => 'places_with_good_reviews',
	// 		],
	// 		[
	// 			'tag_name' => '액티비티 체험',
	// 			'tag_machine_name' => 'activity_needed',
	// 		],
	// 		[
	// 			'tag_name' => '이색적인 체험',
	// 			'tag_machine_name' => 'unique_experiences',
	// 		],
	// 		[
	// 			'tag_name' => '등산/트레킹은 필수',
	// 			'tag_machine_name' => 'hiking_trekking_required',
	// 		],
	// 		[
	// 			'tag_name' => '골프는 필수',
	// 			'tag_machine_name' => 'golf_required',
	// 		],
	// 		[
	// 			'tag_name' => '테마파크 즐기기',
	// 			'tag_machine_name' => 'enjoy_theme_parks',
	// 		],
	// 		[
	// 			'tag_name' => '도보여행 선호',
	// 			'tag_machine_name' => 'prefer_walking_tour',
	// 		],
	// 		[
	// 			'tag_name' => '효도 여행',
	// 			'tag_machine_name' => 'filial_piety_trip',
	// 		],
	// 		[
	// 			'tag_name' => '비즈니스 출장',
	// 			'tag_machine_name' => 'business_trip',
	// 		],
	// 		[
	// 			'tag_name' => '일주일 살기',
	// 			'tag_machine_name' => 'one_week_stay',
	// 		],
	// 		[
	// 			'tag_name' => '한달 살기',
	// 			'tag_machine_name' => 'one_month_stay',
	// 		],
	// 		[
	// 			'tag_name' => '워케이션',
	// 			'tag_machine_name' => 'workation',
	// 		],
	// 		[
	// 			'tag_name' => '커플기념일',
	// 			'tag_machine_name' => 'couple_anniversary',
	// 		],
	// 		[
	// 			'tag_name' => '결혼기념일',
	// 			'tag_machine_name' => 'wedding_anniversary',
	// 		],
	// 		[
	// 			'tag_name' => '프로포즈',
	// 			'tag_machine_name' => 'proposal',
	// 		],
	// 		// [
	// 		// 	'tag_name' => '생일',
	// 		// 	'tag_machine_name' => 'birthday',
	// 		// ],
	// 		// [
	// 		// 	'tag_name' => '돌&백일',
	// 		// 	'tag_machine_name' => 'first_birthday_and_100th_day_ceremony',
	// 		// ],
	// 		// [
	// 		// 	'tag_name' => '허니문',
	// 		// 	'tag_machine_name' => 'honeymoon',
	// 		// ],
	// 		// [
	// 		// 	'tag_name' => '브라이덜샤워',
	// 		// 	'tag_machine_name' => 'bridal_shower',
	// 		// ],
	// 		// [
	// 		// 	'tag_name' => '회사 이벤트',
	// 		// 	'tag_machine_name' => 'company_event',
	// 		// ],
	// 		[
	// 			'tag_name' => '조식은 필수',
	// 			'tag_machine_name' => 'breakfast_service',
	// 		],
	// 		[
	// 			'tag_name' => '수영장',
	// 			'tag_machine_name' => 'swimming_pool',
	// 		],
	// 		[
	// 			'tag_name' => '사계절 온수/미온수풀',
	// 			'tag_machine_name' => 'year_round_heated_pool',
	// 		],
	// 		[
	// 			'tag_name' => '인피니티풀',
	// 			'tag_machine_name' => 'infinity_pool',
	// 		],
	// 		[
	// 			'tag_name' => '개별 수영장',
	// 			'tag_machine_name' => 'private_pool_available',
	// 		],
	// 		[
	// 			'tag_name' => '워터파크',
	// 			'tag_machine_name' => 'water_park',
	// 		],
	// 		[
	// 			'tag_name' => 'SNS에서 인기많은',
	// 			'tag_machine_name' => 'popular_on_SNS',
	// 		],
	// 		[
	// 			'tag_name' => '저렴하지만 깨끗한',
	// 			'tag_machine_name' => 'clean_but_affordable',
	// 		],
	// 		[
	// 			'tag_name' => '힙한 분위기',
	// 			'tag_machine_name' => 'hip_atmosphere',
	// 		],
	// 		[
	// 			'tag_name' => '감성 넘치는 분위기',
	// 			'tag_machine_name' => 'emotional_vibe',
	// 		],
	// 		[
	// 			'tag_name' => '반려동물 동반가능',
	// 			'tag_machine_name' => 'pet_friendly',
	// 		],
	// 		[
	// 			'tag_name' => '대중교통 접근 가능',
	// 			'tag_machine_name' => 'accessible_by_public_transport',
	// 		],
	// 		[
	// 			'tag_name' => 'OTT(넷플릭스 등)',
	// 			'tag_machine_name' => 'OTT_services_(e.g._Netflix)',
	// 		],
	// 		[
	// 			'tag_name' => '바베큐장',
	// 			'tag_machine_name' => 'barbecue_area',
	// 		],
	// 		[
	// 			'tag_name' => '취사가능',
	// 			'tag_machine_name' => 'cooking_allowed',
	// 		],
	// 		[
	// 			'tag_name' => '피트니스',
	// 			'tag_machine_name' => 'fitness_center',
	// 		],
	// 		[
	// 			'tag_name' => '키즈플레이룸',
	// 			'tag_machine_name' => 'kids_playroom',
	// 		],
	// 		[
	// 			'tag_name' => '스파/월풀',
	// 			'tag_machine_name' => 'spa_whirlpool',
	// 		],
	// 		[
	// 			'tag_name' => '사우나',
	// 			'tag_machine_name' => 'sauna',
	// 		],
	// 		[
	// 			'tag_name' => '비즈니스센터',
	// 			'tag_machine_name' => 'business_center',
	// 		],
	// 		// [
	// 		// 	'tag_name' => '루프탑',
	// 		// 	'tag_machine_name' => 'rooftop',
	// 		// ],
	// 		[
	// 			'tag_name' => '패밀리룸 보유(3인)',
	// 			'tag_machine_name' => 'family_room_for_3_people',
	// 		],
	// 		[
	// 			'tag_name' => '패밀리룸 보유(4인)',
	// 			'tag_machine_name' => 'family_room_for_4_people',
	// 		],
	// 		[
	// 			'tag_name' => '대형 객실 보유(5인+)',
	// 			'tag_machine_name' => 'large_rooms_for_5_or_more_people',
	// 		],
	// 		// [
	// 		// 	'tag_name' => '장애인객실',
	// 		// 	'tag_machine_name' => 'accessible_rooms',
	// 		// ],
	// 		// [
	// 		// 	'tag_name' => '파티룸',
	// 		// 	'tag_machine_name' => 'party_room',
	// 		// ],
	// 		[
	// 			'tag_name' => '룸서비스',
	// 			'tag_machine_name' => 'room_service',
	// 		],
	// 		[
	// 			'tag_name' => '전망좋은 곳',
	// 			'tag_machine_name' => 'places_with_great_views',
	// 		],
	// 		[
	// 			'tag_name' => '바다 전망',
	// 			'tag_machine_name' => 'sea_view',
	// 		],
	// 		[
	// 			'tag_name' => '산 전망',
	// 			'tag_machine_name' => 'mountain_view',
	// 		],
	// 		[
	// 			'tag_name' => '도시 전망',
	// 			'tag_machine_name' => 'city_view',
	// 		],
	// 		[
	// 			'tag_name' => '강 전망',
	// 			'tag_machine_name' => 'river_view',
	// 		],
	// 		[
	// 			'tag_name' => '높은 층 선호',
	// 			'tag_machine_name' => 'prefer_high_floors',
	// 		],
	// 		[
	// 			'tag_name' => '낮은 층 선호',
	// 			'tag_machine_name' => 'prefer_low_floors',
	// 		],
	// 		[
	// 			'tag_name' => '계곡 주변',
	// 			'tag_machine_name' => 'near_valley',
	// 		],
	// 		[
	// 			'tag_name' => '골프장 주변',
	// 			'tag_machine_name' => 'near_golf_course',
	// 		],
	// 		[
	// 			'tag_name' => '낚시장 주변',
	// 			'tag_machine_name' => 'near_fishing_spots',
	// 		],
	// 		[
	// 			'tag_name' => '스키장 주변',
	// 			'tag_machine_name' => 'near_ski_resort',
	// 		],
	// 		[
	// 			'tag_name' => '해수욕장 주변',
	// 			'tag_machine_name' => 'near_beach',
	// 		],
	// 		[
	// 			'tag_name' => '강/호수 주변',
	// 			'tag_machine_name' => 'near_river_or_lake',
	// 		],
	// 		[
	// 			'tag_name' => '공항 근처',
	// 			'tag_machine_name' => 'near_airport',
	// 		],
	// 		[
	// 			'tag_name' => '주차가능',
	// 			'tag_machine_name' => 'parking_available',
	// 		],
	// 		[
	// 			'tag_name' => '호텔',
	// 			'tag_machine_name' => 'hotel',
	// 		],
	// 		[
	// 			'tag_name' => '리조트',
	// 			'tag_machine_name' => 'resort',
	// 		],
	// 		[
	// 			'tag_name' => '레지던스',
	// 			'tag_machine_name' => 'residence',
	// 		],
	// 		[
	// 			'tag_name' => '풀빌라',
	// 			'tag_machine_name' => 'private_pool_villa',
	// 		],
	// 		[
	// 			'tag_name' => '한옥',
	// 			'tag_machine_name' => 'hanok_traditional_house',
	// 		],
	// 		[
	// 			'tag_name' => '펜션',
	// 			'tag_machine_name' => 'pension',
	// 		],
	// 		[
	// 			'tag_name' => '카라반',
	// 			'tag_machine_name' => 'caravan',
	// 		],
	// 		[
	// 			'tag_name' => '글램핑',
	// 			'tag_machine_name' => 'glamping',
	// 		],
	// 		[
	// 			'tag_name' => '캠핑',
	// 			'tag_machine_name' => 'camping',
	// 		],
	// 		[
	// 			'tag_name' => '캐릭터룸 보유',
	// 			'tag_machine_name' => 'character_rooms_available',
	// 		],
	// 		[
	// 			'tag_name' => '애견펜션',
	// 			'tag_machine_name' => 'pet_friendly_pension',
	// 		],
	// 		[
	// 			'tag_name' => '키즈펜션',
	// 			'tag_machine_name' => 'kids_friendly_pension',
	// 		],
	// 		[
	// 			'tag_name' => '부티크',
	// 			'tag_machine_name' => 'boutique',
	// 		],
	// 		[
	// 			'tag_name' => '독채형',
	// 			'tag_machine_name' => 'private_house_type',
	// 		],
	// 		[
	// 			'tag_name' => '더블룸',
	// 			'tag_machine_name' => 'double_room',
	// 		],
	// 		[
	// 			'tag_name' => '트윈룸',
	// 			'tag_machine_name' => 'twin_room',
	// 		],
	// 		[
	// 			'tag_name' => '온돌룸',
	// 			'tag_machine_name' => 'traditional_ondol_room',
	// 		],
	// 		[
	// 			'tag_name' => '트리플룸',
	// 			'tag_machine_name' => 'triple_room',
	// 		],
	// 		[
	// 			'tag_name' => '패밀리룸',
	// 			'tag_machine_name' => 'family_room',
	// 		],
	// 		[
	// 			'tag_name' => '스위트룸',
	// 			'tag_machine_name' => 'suite_room',
	// 		],
	// 		[
	// 			'tag_name' => '5성',
	// 			'tag_machine_name' => '5_star',
	// 		],
	// 		[
	// 			'tag_name' => '4성',
	// 			'tag_machine_name' => '4_star',
	// 		],
	// 		[
	// 			'tag_name' => '3성',
	// 			'tag_machine_name' => '3_star',
	// 		],
	// 		[
	// 			'tag_name' => '2성',
	// 			'tag_machine_name' => '2_star',
	// 		],
	// 		[
	// 			'tag_name' => '1성',
	// 			'tag_machine_name' => '1_star',
	// 		],
	// 		[
	// 			'tag_name' => '신라스테이',
	// 			'tag_machine_name' => 'shilla_stay',
	// 		],
	// 		[
	// 			'tag_name' => 'GLAD',
	// 			'tag_machine_name' => 'GLAD',
	// 		],
	// 		[
	// 			'tag_name' => '롯데 호텔&리조트',
	// 			'tag_machine_name' => 'lotte_hotels_and_resorts',
	// 		],
	// 		[
	// 			'tag_name' => '롯데시티호텔',
	// 			'tag_machine_name' => 'lotte_city_hotel',
	// 		],
	// 		[
	// 			'tag_name' => '나인트리 호텔',
	// 			'tag_machine_name' => 'nine_tree_hotel',
	// 		],
	// 		[
	// 			'tag_name' => '켄싱턴호텔앤리조트',
	// 			'tag_machine_name' => 'kensington_hotels_and_resorts',
	// 		],
	// 		[
	// 			'tag_name' => '아코르 앰배서더',
	// 			'tag_machine_name' => 'accor_ambassador',
	// 		],
	// 		[
	// 			'tag_name' => '반얀트리 그룹',
	// 			'tag_machine_name' => 'banyan_tree_group',
	// 		],
	// 		[
	// 			'tag_name' => '라마다',
	// 			'tag_machine_name' => 'ramada',
	// 		],
	// 		[
	// 			'tag_name' => '하얏트',
	// 			'tag_machine_name' => 'hyatt',
	// 		],
	// 		[
	// 			'tag_name' => '오라카이',
	// 			'tag_machine_name' => 'orakai',
	// 		],
	// 		// [
	// 		// 	'tag_name' => '호텔 더 디자이너스',
	// 		// 	'tag_machine_name' => 'hotel_the_designers',
	// 		// ],
	// 		[
	// 			'tag_name' => '기타 체인호텔',
	// 			'tag_machine_name' => 'other_chain_hotels',
	// 		],
	// 	];

	// 	return $tags;
	// }

	public function getFeaturedTags()
	{
		$tags = [
			[
				[
					'tag_name' => '가성비 중요',
					'tag_machine_name' => 'value_for_money_important',
				],
				[
					'tag_name' => '여유로운 힐링',
					'tag_machine_name' => 'relaxing_healing',
				],
				[
					'tag_name' => '도심 속 호캉스',
					'tag_machine_name' => 'urban_hotel_staycation',
				]
			],
			[
				[
					'tag_name' => '친구와',
					'tag_machine_name' => 'with_friends',
				],
				[
					'tag_name' => '서울',
					'tag_machine_name' => 'seoul',
				],
				[
					'tag_name' => '한옥',
					'tag_machine_name' => 'hanok_traditional_house',
				],
			],
			[
				[
					'tag_name' => '대형 객실 보유(5인+)',
					'tag_machine_name' => 'large_rooms_for_5_or_more_people',
				],
				[
					'tag_name' => 'SNS에서 인기많은',
					'tag_machine_name' => 'popular_on_SNS',
				],
				[
					'tag_name' => '저렴하지만 깨끗한',
					'tag_machine_name' => 'clean_but_affordable',
				],
			],
			[
				[
					'tag_name' => '결혼기념일',
					'tag_machine_name' => 'wedding_anniversary',
				],
				[
					'tag_name' => '커플기념일',
					'tag_machine_name' => 'couple_anniversary',
				],
				[
					'tag_name' => '생일',
					'tag_machine_name' => 'birthday',
				],
			]
		];

		return $tags;
	}

	public function getMoongcledealTags()
	{
		$companionTags = [
			[
				'tag_name' => '혼자',
				'tag_machine_name' => 'alone'
			],
			[
				'tag_name' => '친구와',
				'tag_machine_name' => 'with_friends'
			],
			[
				'tag_name' => '연인과',
				'tag_machine_name' => 'couple'
			],
			[
				'tag_name' => '배우자와',
				'tag_machine_name' => 'with_spouse'
			],
			[
				'tag_name' => '아이와',
				'tag_machine_name' => 'with_kids'
			],
			[
				'tag_name' => '가족과',
				'tag_machine_name' => 'family'
			],
			[
				'tag_name' => '부모님과',
				'tag_machine_name' => 'with_parents'
			],
			[
				'tag_name' => '반려동물과',
				'tag_machine_name' => 'pet_friendly'
			],
			[
				'tag_name' => '직장동료',
				'tag_machine_name' => 'with_colleagues'
			],
			[
				'tag_name' => '단체',
				'tag_machine_name' => 'group_MT_workshop'
			],
			// [
			// 	'tag_name' => '기타',
			// 	'tag_machine_name' => 'others'
			// ],
		];

		$petDetailTags = [
			'size' => [
				[
					'tag_name' => '소형견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'small_dog_allowed'
				],
				[
					'tag_name' => '중형견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'medium_dog_allowed'
				],
				[
					'tag_name' => '대형견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'large_dog_allowed'
				],
				[
					'tag_name' => '맹견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'dangerous_dog_allowed'
				],
				[
					'tag_name' => '반려묘 동반 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'cat_allowed'
				]
			],
			'weight' => [
				[
					'tag_name' => '무게 ~7kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_7kg'
				],
				[
					'tag_name' => '무게 ~10kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_10kg'
				],
				[
					'tag_name' => '무게 ~15kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_15kg'
				],
				[
					'tag_name' => '무게 ~20kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_20kg'
				],
				[
					'tag_name' => '무게 제한없음',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'no_weight_limit'
				]
			],
			'counts' => [
				[
					'tag_name' => '1마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'one_pet_allowed'
				],
				[
					'tag_name' => '2마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'two_pets_allowed'
				],
				[
					'tag_name' => '3마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'three_pets_allowed'
				],
				[
					'tag_name' => '4마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'four_pets_allowed'
				],
				[
					'tag_name' => '마릿수 제한없음',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'pet_no_limit'
				]
			]
		];

		$cityTags = [
			[
				'tag_name' => '서울',
				'tag_machine_name' => 'seoul'
			],
			[
				'tag_name' => '제주',
				'tag_machine_name' => 'jeju'
			],
			[
				'tag_name' => '부산',
				'tag_machine_name' => 'busan'
			],
            [
				'tag_name' => '인천',
				'tag_machine_name' => 'incheon'
			],
            [
				'tag_name' => '경기',
				'tag_machine_name' => 'gyeonggi'
			],
            [
				'tag_name' => '강원',
				'tag_machine_name' => 'gangwon'
			],
            [
				'tag_name' => '충남',
				'tag_machine_name' => 'chungcheongnamdo'
			],
            [
				'tag_name' => '충북',
				'tag_machine_name' => 'chungcheongbukdo'
			],
            [
				'tag_name' => '전북',
				'tag_machine_name' => 'jeonbukstate'
			],
            [
				'tag_name' => '전남',
				'tag_machine_name' => 'jeollanamdo'
			],
            [
				'tag_name' => '경북',
				'tag_machine_name' => 'gyeongsangbukdo'
			],
            [
				'tag_name' => '경남',
				'tag_machine_name' => 'gyeongsangnamdo'
			],	
			[
				'tag_name' => '가평',
				'tag_machine_name' => 'gapyeong'
			],
			[
				'tag_name' => '여수',
				'tag_machine_name' => 'yeosu'
			],
			[
				'tag_name' => '속초',
				'tag_machine_name' => 'sokcho'
			],
			[
				'tag_name' => '양양',
				'tag_machine_name' => 'yangyang'
			],
			[
				'tag_name' => '강릉',
				'tag_machine_name' => 'gangneung'
			],
			[
				'tag_name' => '포항',
				'tag_machine_name' => 'pohang'
			],
			[
				'tag_name' => '경주',
				'tag_machine_name' => 'gyeongju'
			],
			[
				'tag_name' => '전주',
				'tag_machine_name' => 'jeonju'
			],
			[
				'tag_name' => '평창',
				'tag_machine_name' => 'pyeongchang'
			],
			[
				'tag_name' => '거제',
				'tag_machine_name' => 'geoje'
			],
			[
				'tag_name' => '대구',
				'tag_machine_name' => 'daegu'
			],
			[
				'tag_name' => '남해',
				'tag_machine_name' => 'namhae'
			],
		];

		$overseasTags = [
			[
				'tag_name' => '방콕 (준비중)',
				'tag_machine_name' => 'bangkok'
			],
			[
				'tag_name' => '푸켓 (준비중)',
				'tag_machine_name' => 'phuket'
			],
			[
				'tag_name' => '치앙마이 (준비중)',
				'tag_machine_name' => 'chiangmai'
			],
			[
				'tag_name' => '도쿄 (준비중)',
				'tag_machine_name' => 'tokyo'
			],
			[
				'tag_name' => '다낭 (준비중)',
				'tag_machine_name' => 'danang'
			],
			[
				'tag_name' => '홍콩 (준비중)',
				'tag_machine_name' => 'hongkong'
			],
		];

		$travelTasteTags = [
			[
				'tag_name' => '아이와 갈만한 곳',
				'tag_machine_name' => 'places_to_visit_with_kids',
			],
			[
				'tag_name' => '가성비 중요',
				'tag_machine_name' => 'value_for_money_important',
			],
			[
				'tag_name' => '이국적인',
				'tag_machine_name' => 'exotic_atmosphere',
			],
			[
				'tag_name' => '로맨틱 분위기',
				'tag_machine_name' => 'romantic_atmosphere',
			],
			[
				'tag_name' => '데이트장소 추천',
				'tag_machine_name' => 'romantic_spot_recommendation',
			],
			[
				'tag_name' => '도심 속 호캉스',
				'tag_machine_name' => 'urban_hotel_staycation',
			],
			[
				'tag_name' => '숲캉스',
				'tag_machine_name' => 'forest_staycation',
			],
			[
				'tag_name' => '야경즐기기',
				'tag_machine_name' => 'enjoy_nightscape',
			],
			[
				'tag_name' => '여유로운 평일여행',
				'tag_machine_name' => 'relaxed_weekday_travel',
			],
			[
				'tag_name' => '자연과 함께',
				'tag_machine_name' => 'with_nature',
			],
			[
				'tag_name' => '여유로운 힐링',
				'tag_machine_name' => 'relaxing_healing',
			],
			[
				'tag_name' => '사색있는 여행',
				'tag_machine_name' => 'reflective_travel',
			],
			[
				'tag_name' => '체인호텔 선호',
				'tag_machine_name' => 'prefer_chain_hotels',
			],
			[
				'tag_name' => '리뷰좋은 곳',
				'tag_machine_name' => 'places_with_good_reviews',
			],
			[
				'tag_name' => '액티비티 체험',
				'tag_machine_name' => 'activity_needed',
			],
			[
				'tag_name' => '이색적인 체험',
				'tag_machine_name' => 'unique_experiences',
			],
			[
				'tag_name' => '등산/트레킹은 필수',
				'tag_machine_name' => 'hiking_trekking_required',
			],
			[
				'tag_name' => '골프는 필수',
				'tag_machine_name' => 'golf_required',
			],
			[
				'tag_name' => '테마파크 즐기기',
				'tag_machine_name' => 'enjoy_theme_parks',
			],
			[
				'tag_name' => '도보여행 선호',
				'tag_machine_name' => 'prefer_walking_tour',
			],
			[
				'tag_name' => '효도 여행',
				'tag_machine_name' => 'filial_piety_trip',
			],
			[
				'tag_name' => '비즈니스 출장',
				'tag_machine_name' => 'business_trip',
			],
			[
				'tag_name' => '일주일 살기',
				'tag_machine_name' => 'one_week_stay',
			],
			[
				'tag_name' => '한달 살기',
				'tag_machine_name' => 'one_month_stay',
			],
			[
				'tag_name' => '워케이션',
				'tag_machine_name' => 'workation',
			],
			// [
			// 	'tag_name' => '주변먹거리 많음',
			// 	'tag_machine_name' => 'plenty_of_nearby_food_options',
			// ],
			// [
			// 	'tag_name' => '로컬호텔 선호',
			// 	'tag_machine_name' => 'prefer_local_hotels',
			// ],
			// [
			// 	'tag_name' => '럭셔리 여행',
			// 	'tag_machine_name' => 'luxury_travel',
			// ],
			// [
			// 	'tag_name' => '유명 랜드마크',
			// 	'tag_machine_name' => 'famous_landmarks',
			// ],
			// [
			// 	'tag_name' => '쇼핑은 즐거워',
			// 	'tag_machine_name' => 'enjoy_shopping',
			// ],
			// [
			// 	'tag_name' => '로컬 맛집 탐방',
			// 	'tag_machine_name' => 'explore_local_cuisine',
			// ],
			// [
			// 	'tag_name' => '로컬 시장 체험',
			// 	'tag_machine_name' => 'local_market_experience',
			// ],
			// [
			// 	'tag_name' => '역사가 가득한',
			// 	'tag_machine_name' => 'historical_places',
			// ],
			// [
			// 	'tag_name' => '드라이브 하기 좋은',
			// 	'tag_machine_name' => 'great_for_driving',
			// ],
			// [
			// 	'tag_name' => '역사/문화 투어',
			// 	'tag_machine_name' => 'history_culture_tour',
			// ],
		];

		$eventTags = [
			[
				'tag_name' => '커플기념일',
				'tag_machine_name' => 'couple_anniversary',
			],
			[
				'tag_name' => '결혼기념일',
				'tag_machine_name' => 'wedding_anniversary',
			],
			[
				'tag_name' => '프로포즈',
				'tag_machine_name' => 'proposal',
			],
			[
				'tag_name' => '생일',
				'tag_machine_name' => 'birthday',
			],
			[
				'tag_name' => '돌&백일',
				'tag_machine_name' => 'first_birthday_and_100th_day_ceremony',
			],
			[
				'tag_name' => '허니문',
				'tag_machine_name' => 'honeymoon',
			],
			[
				'tag_name' => '브라이덜샤워',
				'tag_machine_name' => 'bridal_shower',
			],
			[
				'tag_name' => '회사 이벤트',
				'tag_machine_name' => 'company_event',
			],
		];

		$stayTasteTags = [
			[
				'tag_name' => '조식은 필수',
				'tag_machine_name' => 'breakfast_service',
			],
			[
				'tag_name' => '수영장',
				'tag_machine_name' => 'swimming_pool',
			],
			[
				'tag_name' => '사계절 온수/미온수풀',
				'tag_machine_name' => 'year_round_heated_pool',
			],
			[
				'tag_name' => '인피니티풀',
				'tag_machine_name' => 'infinity_pool',
			],
			[
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available',
			],
			[
				'tag_name' => '워터파크',
				'tag_machine_name' => 'water_park',
			],
			[
				'tag_name' => 'SNS에서 인기많은',
				'tag_machine_name' => 'popular_on_SNS',
			],
			[
				'tag_name' => '저렴하지만 깨끗한',
				'tag_machine_name' => 'clean_but_affordable',
			],
			[
				'tag_name' => '힙한 분위기',
				'tag_machine_name' => 'hip_atmosphere',
			],
			[
				'tag_name' => '감성 넘치는 분위기',
				'tag_machine_name' => 'emotional_vibe',
			],
			// [
			// 	'tag_name' => '반려동물 동반가능',
			// 	'tag_machine_name' => 'pet_friendly',
			// ],
			[
				'tag_name' => '대중교통 접근 가능',
				'tag_machine_name' => 'accessible_by_public_transport',
			],
			[
				'tag_name' => 'OTT(넷플릭스 등)',
				'tag_machine_name' => 'OTT_services_(e.g._Netflix)',
			],
			[
				'tag_name' => '바베큐장',
				'tag_machine_name' => 'barbecue_area',
			],
			[
				'tag_name' => '취사가능',
				'tag_machine_name' => 'cooking_allowed',
			],
			[
				'tag_name' => '피트니스',
				'tag_machine_name' => 'fitness_center',
			],
			[
				'tag_name' => '키즈플레이룸',
				'tag_machine_name' => 'kids_playroom',
			],
			[
				'tag_name' => '스파/월풀',
				'tag_machine_name' => 'spa_whirlpool',
			],
			[
				'tag_name' => '사우나',
				'tag_machine_name' => 'sauna',
			],
			[
				'tag_name' => '비즈니스센터',
				'tag_machine_name' => 'business_center',
			],
			[
				'tag_name' => '루프탑',
				'tag_machine_name' => 'rooftop',
			],
			[
				'tag_name' => '패밀리룸 보유(3인)',
				'tag_machine_name' => 'family_room_for_3_people',
			],
			[
				'tag_name' => '패밀리룸 보유(4인)',
				'tag_machine_name' => 'family_room_for_4_people',
			],
			[
				'tag_name' => '대형 객실 보유(5인+)',
				'tag_machine_name' => 'large_rooms_for_5_or_more_people',
			],
			[
				'tag_name' => '장애인객실',
				'tag_machine_name' => 'accessible_rooms',
			],
			[
				'tag_name' => '파티룸',
				'tag_machine_name' => 'party_room',
			],
			[
				'tag_name' => '룸서비스',
				'tag_machine_name' => 'room_service',
			],
			[
				'tag_name' => '전망좋은 곳',
				'tag_machine_name' => 'places_with_great_views',
			],
			[
				'tag_name' => '바다 전망',
				'tag_machine_name' => 'sea_view',
			],
			[
				'tag_name' => '산 전망',
				'tag_machine_name' => 'mountain_view',
			],
			[
				'tag_name' => '도시 전망',
				'tag_machine_name' => 'city_view',
			],
			[
				'tag_name' => '강 전망',
				'tag_machine_name' => 'river_view',
			],
			[
				'tag_name' => '높은 층 선호',
				'tag_machine_name' => 'prefer_high_floors',
			],
			[
				'tag_name' => '낮은 층 선호',
				'tag_machine_name' => 'prefer_low_floors',
			],
			[
				'tag_name' => '계곡 주변',
				'tag_machine_name' => 'near_valley',
			],
			[
				'tag_name' => '골프장 주변',
				'tag_machine_name' => 'near_golf_course',
			],
			[
				'tag_name' => '낚시장 주변',
				'tag_machine_name' => 'near_fishing_spots',
			],
			[
				'tag_name' => '스키장 주변',
				'tag_machine_name' => 'near_ski_resort',
			],
			[
				'tag_name' => '해수욕장 주변',
				'tag_machine_name' => 'near_beach',
			],
			[
				'tag_name' => '강/호수 주변',
				'tag_machine_name' => 'near_river_or_lake',
			],
			[
				'tag_name' => '공항 근처',
				'tag_machine_name' => 'near_airport',
			],
			[
				'tag_name' => '주차가능',
				'tag_machine_name' => 'parking_available',
			],
			// [
			// 	'tag_name' => '노래방',
			// 	'tag_machine_name' => 'karaoke',
			// ],

			// [
			// 	'tag_name' => '세미나실',
			// 	'tag_machine_name' => 'seminar_room',
			// ],
			// [
			// 	'tag_name' => '골프장',
			// 	'tag_machine_name' => 'golf_course',
			// ],
			// [
			// 	'tag_name' => '레스토랑',
			// 	'tag_machine_name' => 'restaurant',
			// ],
			// [
			// 	'tag_name' => '공용주방',
			// 	'tag_machine_name' => 'shared_kitchen',
			// ],
			// [
			// 	'tag_name' => '온천',
			// 	'tag_machine_name' => 'hot_spring',
			// ],
			// [
			// 	'tag_name' => '유아시설',
			// 	'tag_machine_name' => 'infant_facilities',
			// ],
			// [
			// 	'tag_name' => '실내수영장',
			// 	'tag_machine_name' => 'indoor_pool',
			// ],
			// [
			// 	'tag_name' => '야외 수영장',
			// 	'tag_machine_name' => 'outdoor_pool',
			// ],
			// [
			// 	'tag_name' => '어린이수영장',
			// 	'tag_machine_name' => 'kids_pool',
			// ],
			// [
			// 	'tag_name' => '오락실',
			// 	'tag_machine_name' => 'arcade_room',
			// ],
			// [
			// 	'tag_name' => '이그제큐티브 라운지',
			// 	'tag_machine_name' => 'executive_lounge',
			// ],
			// [
			// 	'tag_name' => '수목원/휴양림 주변',
			// 	'tag_machine_name' => 'near_botanical_gardens_or_forests',
			// ],
			// [
			// 	'tag_name' => '수상레져',
			// 	'tag_machine_name' => 'water_sports_available',
			// ],
			// [
			// 	'tag_name' => '짐보관',
			// 	'tag_machine_name' => 'luggage_storage',
			// ],
			// [
			// 	'tag_name' => '픽업',
			// 	'tag_machine_name' => 'pickup_service_available',
			// ],
			// [
			// 	'tag_name' => '정원 전망',
			// 	'tag_machine_name' => 'garden_view',
			// ],
			// [
			// 	'tag_name' => '수영장 전망',
			// 	'tag_machine_name' => 'pool_view',
			// ],
			// [
			// 	'tag_name' => '항구 전망',
			// 	'tag_machine_name' => 'harbor_view',
			// ],
		];

		$stayTypeTags = [
			[
				'tag_name' => '호텔',
				'tag_machine_name' => 'hotel',
			],
			[
				'tag_name' => '리조트',
				'tag_machine_name' => 'resort',
			],
			[
				'tag_name' => '레지던스',
				'tag_machine_name' => 'residence',
			],
			[
				'tag_name' => '풀빌라',
				'tag_machine_name' => 'private_pool_villa',
			],
			[
				'tag_name' => '한옥',
				'tag_machine_name' => 'hanok_traditional_house',
			],
			[
				'tag_name' => '펜션',
				'tag_machine_name' => 'pension',
			],
			[
				'tag_name' => '카라반',
				'tag_machine_name' => 'caravan',
			],
			[
				'tag_name' => '글램핑',
				'tag_machine_name' => 'glamping',
			],
			[
				'tag_name' => '캠핑',
				'tag_machine_name' => 'camping',
			],
			[
				'tag_name' => '캐릭터룸 보유',
				'tag_machine_name' => 'character_rooms_available',
			],
			[
				'tag_name' => '애견펜션',
				'tag_machine_name' => 'pet_friendly_pension',
			],
			[
				'tag_name' => '키즈펜션',
				'tag_machine_name' => 'kids_friendly_pension',
			],
			[
				'tag_name' => '부티크',
				'tag_machine_name' => 'boutique',
			],
			[
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type',
			],
			[
				'tag_name' => '더블룸',
				'tag_machine_name' => 'double_room',
			],
			[
				'tag_name' => '트윈룸',
				'tag_machine_name' => 'twin_room',
			],
			[
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room',
			],
			[
				'tag_name' => '트리플룸',
				'tag_machine_name' => 'triple_room',
			],
			[
				'tag_name' => '패밀리룸',
				'tag_machine_name' => 'family_room',
			],
			[
				'tag_name' => '스위트룸',
				'tag_machine_name' => 'suite_room',
			],
			[
				'tag_name' => '5성',
				'tag_machine_name' => '5_star',
			],
			[
				'tag_name' => '4성',
				'tag_machine_name' => '4_star',
			],
			[
				'tag_name' => '3성',
				'tag_machine_name' => '3_star',
			],
			[
				'tag_name' => '2성',
				'tag_machine_name' => '2_star',
			],
			[
				'tag_name' => '1성',
				'tag_machine_name' => '1_star',
			],
			// [
			// 	'tag_name' => '게스트하우스',
			// 	'tag_machine_name' => 'guesthouse',
			// ],
			// [
			// 	'tag_name' => '모텔',
			// 	'tag_machine_name' => 'motel',
			// ],
			// [
			// 	'tag_name' => '감성 숙소',
			// 	'tag_machine_name' => 'emotional_accommodation',
			// ],
		];

		$petFacilityTags = [
			[
				'tag_name' => '애견전용 놀이터',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_playground'
			],
			[
				'tag_name' => '공용 마당',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'shared_yard'
			],
			[
				'tag_name' => '애견가능 수영장',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_allowed_pool'
			],
			[
				'tag_name' => '애견 드라이기룸',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_dryer_room'
			],
			[
				'tag_name' => '애견 식사 가능 메뉴',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_meal_allowed_menu'
			],
			[
				'tag_name' => '애견 침대/소파',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_bed_sofa'
			],
			[
				'tag_name' => '미끄럼방지 매트',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'non_slip_mat'
			],
			[
				'tag_name' => '펫 하우스',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_house'
			],
			[
				'tag_name' => '배변 패드/봉투',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'waste_pad_bag'
			],
			[
				'tag_name' => '펫 타월',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_towel'
			],
			[
				'tag_name' => '애견 배변판',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_litter_box'
			],
			[
				'tag_name' => '애견 식기',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_dishes'
			],
			[
				'tag_name' => '애견 샴푸',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_shampoo'
			],
			[
				'tag_name' => '애견 간식',
				'tag_section' => 'petFacility',
				'tag_machine_name' => 'pet_snacks'
			],
		];

		$stayBrandTags = [
			[
				'tag_name' => '신라스테이',
				'tag_machine_name' => 'shilla_stay',
			],
			[
				'tag_name' => 'GLAD',
				'tag_machine_name' => 'GLAD',
			],
			[
				'tag_name' => '롯데 호텔&리조트',
				'tag_machine_name' => 'lotte_hotels_and_resorts',
			],
			[
				'tag_name' => '롯데시티호텔',
				'tag_machine_name' => 'lotte_city_hotel',
			],
			[
				'tag_name' => '나인트리 호텔',
				'tag_machine_name' => 'nine_tree_hotel',
			],
			[
				'tag_name' => '켄싱턴호텔앤리조트',
				'tag_machine_name' => 'kensington_hotels_and_resorts',
			],
			[
				'tag_name' => '아코르 앰배서더',
				'tag_machine_name' => 'accor_ambassador',
			],
			[
				'tag_name' => '반얀트리 그룹',
				'tag_machine_name' => 'banyan_tree_group',
			],
			[
				'tag_name' => '라마다',
				'tag_machine_name' => 'ramada',
			],
			[
				'tag_name' => '하얏트',
				'tag_machine_name' => 'hyatt',
			],
			[
				'tag_name' => '오라카이',
				'tag_machine_name' => 'orakai',
			],
			[
				'tag_name' => '호텔 더 디자이너스',
				'tag_machine_name' => 'hotel_the_designers',
			],
			[
				'tag_name' => '기타 체인호텔',
				'tag_machine_name' => 'other_chain_hotels',
			],
			// [
			// 	'tag_name' => '메리어트 본보이',
			// 	'tag_machine_name' => 'marriott_bonvoy',
			// ],
			// [
			// 	'tag_name' => '힐튼',
			// 	'tag_machine_name' => 'hilton',
			// ],
			// [
			// 	'tag_name' => '신라 호텔',
			// 	'tag_machine_name' => 'shilla_hotel',
			// ],
			// [
			// 	'tag_name' => '스카이파크',
			// 	'tag_machine_name' => 'skypark',
			// ],
			// [
			// 	'tag_name' => '파르나스 호텔',
			// 	'tag_machine_name' => 'parnas_hotel',
			// ],

			// [
			// 	'tag_name' => 'IHG 호텔&리조트',
			// 	'tag_machine_name' => 'IHG_hotels_and_resorts',
			// ],
			// [
			// 	'tag_name' => '트래블로지',
			// 	'tag_machine_name' => 'travelodge',
			// ],
			// [
			// 	'tag_name' => 'SPG 호텔',
			// 	'tag_machine_name' => 'SPG_hotels',
			// ],
		];

		$newStayTasteTags = [
			[
				'tag_name' => '아이와 갈만한 곳',
				'tag_machine_name' => 'places_to_visit_with_kids',
			],
			[
				'tag_name' => '가성비 중요',
				'tag_machine_name' => 'value_for_money_important',
			],
			[
				'tag_name' => '여유로운 힐링',
				'tag_machine_name' => 'relaxing_healing',
			],
			[
				'tag_name' => '자연과 함께',
				'tag_machine_name' => 'with_nature',
			],
			[
				'tag_name' => '해수욕장 주변',
				'tag_machine_name' => 'near_beach',
			],
			[
				'tag_name' => '전망좋은 곳',
				'tag_machine_name' => 'places_with_great_views',
			],
			[
				'tag_name' => '바다 전망',
				'tag_machine_name' => 'sea_view',
			],
			[
				'tag_name' => '이색적인 체험',
				'tag_machine_name' => 'unique_experiences',
			],
			[
				'tag_name' => '여유로운 평일여행',
				'tag_machine_name' => 'relaxed_weekday_travel',
			],
			[
				'tag_name' => '저렴하지만 깨끗한',
				'tag_machine_name' => 'clean_but_affordable',
			],
			[
				'tag_name' => '감성 넘치는 분위기',
				'tag_machine_name' => 'emotional_vibe',
			],
			[
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room',
			],
			[
				'tag_name' => '캐릭터룸',
				'tag_machine_name' => 'character_rooms_available',
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

		$newTravelTasteTags = [
			[
				'tag_name' => '조식은 필수',
				'tag_machine_name' => 'breakfast_service',
			],
			[
				'tag_name' => '수영장',
				'tag_machine_name' => 'swimming_pool',
			],
			[
				'tag_name' => '사계절 온수/미온수풀',
				'tag_machine_name' => 'year_round_heated_pool',
			],
			[
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available',
			],
			[
				'tag_name' => '인피니티풀',
				'tag_machine_name' => 'infinity_pool',
			],
			[
				'tag_name' => '키즈플레이룸',
				'tag_machine_name' => 'kids_playroom',
			],
			[
				'tag_name' => '바베큐장',
				'tag_machine_name' => 'barbecue_area',
			],
			[
				'tag_name' => '취사가능',
				'tag_machine_name' => 'cooking_allowed',
			],
			[
				'tag_name' => '패밀리룸 보유',
				'tag_machine_name' => 'family_room',
			],
			[
				'tag_name' => '패밀리룸 보유(3인)',
				'tag_machine_name' => 'family_room_for_3_people',
			],
			[
				'tag_name' => '패밀리룸 보유(4인)',
				'tag_machine_name' => 'family_room_for_4_people',
			],
			[
				'tag_name' => '대형 객실 보유(5인+)',
				'tag_machine_name' => 'large_rooms_for_5_or_more_people',
			],
			[
				'tag_name' => '스파/월풀',
				'tag_machine_name' => 'spa_whirlpool',
			],
			[
				'tag_name' => '주차가능',
				'tag_machine_name' => 'parking_available',
			],
            [
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room',
			],
            [
				'tag_name' => '반려동물 동반가능',
				'tag_machine_name' => 'pet_friendly',
			],
		];

		$newStayTypeTags = [
			[
				'tag_name' => '호텔',
				'tag_machine_name' => 'hotel',
			],
			[
				'tag_name' => '리조트',
				'tag_machine_name' => 'resort',
			],
			[
				'tag_name' => '풀빌라',
				'tag_machine_name' => 'private_pool_villa',
			],
			[
				'tag_name' => '키즈펜션',
				'tag_machine_name' => 'kids_friendly_pension',
			],
			[
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type',
			],
			[
				'tag_name' => '캐릭터룸 보유',
				'tag_machine_name' => 'character_rooms_available',
			],
			[
				'tag_name' => '한옥',
				'tag_machine_name' => 'hanok_traditional_house',
			],
			[
				'tag_name' => '펜션',
				'tag_machine_name' => 'pension',
			],
			[
				'tag_name' => '카라반',
				'tag_machine_name' => 'caravan',
			],
			[
				'tag_name' => '글램핑',
				'tag_machine_name' => 'glamping',
			],
			[
				'tag_name' => '캠핑',
				'tag_machine_name' => 'camping',
			],
			[
				'tag_name' => '애견펜션',
				'tag_machine_name' => 'pet_friendly_pension',
			],
			[
				'tag_name' => '레지던스',
				'tag_machine_name' => 'residence',
			],
		];

		$newTravelTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'travelTaste';
			return $tag;
		}, $newTravelTasteTags);

		$newStayTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayTaste';
			return $tag;
		}, $newStayTasteTags);

		$newStayTypeTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayType';
			return $tag;
		}, $newStayTypeTags);

		$travelTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'travelTaste';
			return $tag;
		}, $travelTasteTags);

		$eventTags = array_map(function ($tag) {
			$tag['tag_section'] = 'event';
			return $tag;
		}, $eventTags);

		$stayTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayTaste';
			return $tag;
		}, $stayTasteTags);

		$stayTypeTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayType';
			return $tag;
		}, $stayTypeTags);

		$stayBrandTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayBrand';
			return $tag;
		}, $stayBrandTags);

		return [
			'companionTags' => $companionTags, // 누구와 가나요?
			'petDetailTags' => $petDetailTags, // 반려동물 상세 선택
			'cityTags' => $cityTags, // 어디로 가나요? (국내)
			'overseasTags' => $overseasTags, // 어디로 가나요? (해외)
			'travelTasteTags' => $travelTasteTags, // 여행 취향
			'eventTags' => $eventTags, // 이벤트
			'stayTasteTags' => $stayTasteTags, // 숙박 취향
			'stayTypeTags' => $stayTypeTags, // 선호 숙박 유형
			'petFacilityTags' => $petFacilityTags,
			'stayBrandTags' => $stayBrandTags, // 선호 브랜드
			'newTravelTasteTags' => $newTravelTasteTags,
			'newStayTasteTags' => $newStayTasteTags,
			'newStayTypeTags' => $newStayTypeTags,
		];
	}

	public function getSearchTags()
	{
		$petDetailTags = [
			'size' => [
				[
					'tag_name' => '소형견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'small_dog_allowed'
				],
				[
					'tag_name' => '중형견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'medium_dog_allowed'
				],
				[
					'tag_name' => '대형견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'large_dog_allowed'
				],
				[
					'tag_name' => '맹견 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'dangerous_dog_allowed'
				],
				[
					'tag_name' => '반려묘 동반 가능',
					'tag_section' => 'petSize',
					'tag_machine_name' => 'cat_allowed'
				]
			],
			'weight' => [
				[
					'tag_name' => '무게 ~7kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_7kg'
				],
				[
					'tag_name' => '무게 ~10kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_10kg'
				],
				[
					'tag_name' => '무게 ~15kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_15kg'
				],
				[
					'tag_name' => '무게 ~20kg',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'weight_under_20kg'
				],
				[
					'tag_name' => '무게 제한없음',
					'tag_section' => 'petWeight',
					'tag_machine_name' => 'no_weight_limit'
				]
			],
			'counts' => [
				[
					'tag_name' => '1마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'one_pet_allowed'
				],
				[
					'tag_name' => '2마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'two_pets_allowed'
				],
				[
					'tag_name' => '3마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'three_pets_allowed'
				],
				[
					'tag_name' => '4마리 가능',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'four_pets_allowed'
				],
				[
					'tag_name' => '마릿수 제한없음',
					'tag_section' => 'petCounts',
					'tag_machine_name' => 'pet_no_limit'
				]
			],
			'facility' => [
				[
					'tag_name' => '애견전용 놀이터',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_playground'
				],
				[
					'tag_name' => '공용 마당',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'shared_yard'
				],
				[
					'tag_name' => '애견가능 수영장',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_allowed_pool'
				],
				[
					'tag_name' => '애견 드라이기룸',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_dryer_room'
				],
				[
					'tag_name' => '애견 식사 가능 메뉴',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_meal_allowed_menu'
				],
				[
					'tag_name' => '애견 침대/소파',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_bed_sofa'
				],
				[
					'tag_name' => '미끄럼방지 매트',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'non_slip_mat'
				],
				[
					'tag_name' => '펫 하우스',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_house'
				],
				[
					'tag_name' => '배변 패드/봉투',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'waste_pad_bag'
				],
				[
					'tag_name' => '펫 타월',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_towel'
				],
				[
					'tag_name' => '애견 배변판',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_litter_box'
				],
				[
					'tag_name' => '애견 식기',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_dishes'
				],
				[
					'tag_name' => '애견 샴푸',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_shampoo'
				],
				[
					'tag_name' => '애견 간식',
					'tag_section' => 'petFacility',
					'tag_machine_name' => 'pet_snacks'
				],
			]
		];

		$travelTasteTags = [
			[
				'tag_name' => '아이와 갈만한 곳',
				'tag_machine_name' => 'places_to_visit_with_kids',
			],
			[
				'tag_name' => '가성비 중요',
				'tag_machine_name' => 'value_for_money_important',
			],
			[
				'tag_name' => '이국적인',
				'tag_machine_name' => 'exotic_atmosphere',
			],
			[
				'tag_name' => '로맨틱 분위기',
				'tag_machine_name' => 'romantic_atmosphere',
			],
			[
				'tag_name' => '데이트장소 추천',
				'tag_machine_name' => 'romantic_spot_recommendation',
			],
			[
				'tag_name' => '도심 속 호캉스',
				'tag_machine_name' => 'urban_hotel_staycation',
			],
			[
				'tag_name' => '숲캉스',
				'tag_machine_name' => 'forest_staycation',
			],
			[
				'tag_name' => '야경즐기기',
				'tag_machine_name' => 'enjoy_nightscape',
			],
			[
				'tag_name' => '여유로운 평일여행',
				'tag_machine_name' => 'relaxed_weekday_travel',
			],
			[
				'tag_name' => '자연과 함께',
				'tag_machine_name' => 'with_nature',
			],
			[
				'tag_name' => '여유로운 힐링',
				'tag_machine_name' => 'relaxing_healing',
			],
			[
				'tag_name' => '사색있는 여행',
				'tag_machine_name' => 'reflective_travel',
			],
			[
				'tag_name' => '체인호텔 선호',
				'tag_machine_name' => 'prefer_chain_hotels',
			],
			[
				'tag_name' => '리뷰좋은 곳',
				'tag_machine_name' => 'places_with_good_reviews',
			],
			[
				'tag_name' => '액티비티 체험',
				'tag_machine_name' => 'activity_needed',
			],
			[
				'tag_name' => '이색적인 체험',
				'tag_machine_name' => 'unique_experiences',
			],
			[
				'tag_name' => '등산/트레킹은 필수',
				'tag_machine_name' => 'hiking_trekking_required',
			],
			[
				'tag_name' => '골프는 필수',
				'tag_machine_name' => 'golf_required',
			],
			[
				'tag_name' => '테마파크 즐기기',
				'tag_machine_name' => 'enjoy_theme_parks',
			],
			[
				'tag_name' => '도보여행 선호',
				'tag_machine_name' => 'prefer_walking_tour',
			],
			[
				'tag_name' => '효도 여행',
				'tag_machine_name' => 'filial_piety_trip',
			],
			[
				'tag_name' => '비즈니스 출장',
				'tag_machine_name' => 'business_trip',
			],
			[
				'tag_name' => '일주일 살기',
				'tag_machine_name' => 'one_week_stay',
			],
			[
				'tag_name' => '한달 살기',
				'tag_machine_name' => 'one_month_stay',
			],
			[
				'tag_name' => '워케이션',
				'tag_machine_name' => 'workation',
			],
			// [
			// 	'tag_name' => '주변먹거리 많음',
			// 	'tag_machine_name' => 'plenty_of_nearby_food_options',
			// ],
			// [
			// 	'tag_name' => '로컬호텔 선호',
			// 	'tag_machine_name' => 'prefer_local_hotels',
			// ],
			// [
			// 	'tag_name' => '럭셔리 여행',
			// 	'tag_machine_name' => 'luxury_travel',
			// ],
			// [
			// 	'tag_name' => '유명 랜드마크',
			// 	'tag_machine_name' => 'famous_landmarks',
			// ],
			// [
			// 	'tag_name' => '쇼핑은 즐거워',
			// 	'tag_machine_name' => 'enjoy_shopping',
			// ],
			// [
			// 	'tag_name' => '로컬 맛집 탐방',
			// 	'tag_machine_name' => 'explore_local_cuisine',
			// ],
			// [
			// 	'tag_name' => '로컬 시장 체험',
			// 	'tag_machine_name' => 'local_market_experience',
			// ],
			// [
			// 	'tag_name' => '역사가 가득한',
			// 	'tag_machine_name' => 'historical_places',
			// ],
			// [
			// 	'tag_name' => '드라이브 하기 좋은',
			// 	'tag_machine_name' => 'great_for_driving',
			// ],
			// [
			// 	'tag_name' => '역사/문화 투어',
			// 	'tag_machine_name' => 'history_culture_tour',
			// ],
		];

		$eventTags = [
			[
				'tag_name' => '커플기념일',
				'tag_machine_name' => 'couple_anniversary',
			],
			[
				'tag_name' => '결혼기념일',
				'tag_machine_name' => 'wedding_anniversary',
			],
			[
				'tag_name' => '프로포즈',
				'tag_machine_name' => 'proposal',
			],
			[
				'tag_name' => '생일',
				'tag_machine_name' => 'birthday',
			],
			[
				'tag_name' => '돌&백일',
				'tag_machine_name' => 'first_birthday_and_100th_day_ceremony',
			],
			[
				'tag_name' => '허니문',
				'tag_machine_name' => 'honeymoon',
			],
			[
				'tag_name' => '브라이덜샤워',
				'tag_machine_name' => 'bridal_shower',
			],
			[
				'tag_name' => '회사 이벤트',
				'tag_machine_name' => 'company_event',
			],
		];

		$stayTasteTags = [
			[
				'tag_name' => '조식은 필수',
				'tag_machine_name' => 'breakfast_service',
			],
			[
				'tag_name' => '수영장',
				'tag_machine_name' => 'swimming_pool',
			],
			[
				'tag_name' => '사계절 온수/미온수풀',
				'tag_machine_name' => 'year_round_heated_pool',
			],
			[
				'tag_name' => '인피니티풀',
				'tag_machine_name' => 'infinity_pool',
			],
			[
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available',
			],
			[
				'tag_name' => '워터파크',
				'tag_machine_name' => 'water_park',
			],
			[
				'tag_name' => 'SNS에서 인기많은',
				'tag_machine_name' => 'popular_on_SNS',
			],
			[
				'tag_name' => '저렴하지만 깨끗한',
				'tag_machine_name' => 'clean_but_affordable',
			],
			[
				'tag_name' => '힙한 분위기',
				'tag_machine_name' => 'hip_atmosphere',
			],
			[
				'tag_name' => '감성 넘치는 분위기',
				'tag_machine_name' => 'emotional_vibe',
			],
			// [
			// 	'tag_name' => '반려동물 동반가능',
			// 	'tag_machine_name' => 'pet_friendly',
			// ],
			[
				'tag_name' => '대중교통 접근 가능',
				'tag_machine_name' => 'accessible_by_public_transport',
			],
			[
				'tag_name' => 'OTT(넷플릭스 등)',
				'tag_machine_name' => 'OTT_services_(e.g._Netflix)',
			],
			[
				'tag_name' => '바베큐장',
				'tag_machine_name' => 'barbecue_area',
			],
			[
				'tag_name' => '취사가능',
				'tag_machine_name' => 'cooking_allowed',
			],
			[
				'tag_name' => '피트니스',
				'tag_machine_name' => 'fitness_center',
			],
			[
				'tag_name' => '키즈플레이룸',
				'tag_machine_name' => 'kids_playroom',
			],
			[
				'tag_name' => '스파/월풀',
				'tag_machine_name' => 'spa_whirlpool',
			],
			[
				'tag_name' => '사우나',
				'tag_machine_name' => 'sauna',
			],
			[
				'tag_name' => '비즈니스센터',
				'tag_machine_name' => 'business_center',
			],
			[
				'tag_name' => '루프탑',
				'tag_machine_name' => 'rooftop',
			],
			[
				'tag_name' => '패밀리룸 보유(3인)',
				'tag_machine_name' => 'family_room_for_3_people',
			],
			[
				'tag_name' => '패밀리룸 보유(4인)',
				'tag_machine_name' => 'family_room_for_4_people',
			],
			[
				'tag_name' => '대형 객실 보유(5인+)',
				'tag_machine_name' => 'large_rooms_for_5_or_more_people',
			],
			[
				'tag_name' => '장애인객실',
				'tag_machine_name' => 'accessible_rooms',
			],
			[
				'tag_name' => '파티룸',
				'tag_machine_name' => 'party_room',
			],
			[
				'tag_name' => '룸서비스',
				'tag_machine_name' => 'room_service',
			],
			[
				'tag_name' => '전망좋은 곳',
				'tag_machine_name' => 'places_with_great_views',
			],
			[
				'tag_name' => '바다 전망',
				'tag_machine_name' => 'sea_view',
			],
			[
				'tag_name' => '산 전망',
				'tag_machine_name' => 'mountain_view',
			],
			[
				'tag_name' => '도시 전망',
				'tag_machine_name' => 'city_view',
			],
			[
				'tag_name' => '강 전망',
				'tag_machine_name' => 'river_view',
			],
			[
				'tag_name' => '높은 층 선호',
				'tag_machine_name' => 'prefer_high_floors',
			],
			[
				'tag_name' => '낮은 층 선호',
				'tag_machine_name' => 'prefer_low_floors',
			],
			[
				'tag_name' => '계곡 주변',
				'tag_machine_name' => 'near_valley',
			],
			[
				'tag_name' => '골프장 주변',
				'tag_machine_name' => 'near_golf_course',
			],
			[
				'tag_name' => '낚시장 주변',
				'tag_machine_name' => 'near_fishing_spots',
			],
			[
				'tag_name' => '스키장 주변',
				'tag_machine_name' => 'near_ski_resort',
			],
			[
				'tag_name' => '해수욕장 주변',
				'tag_machine_name' => 'near_beach',
			],
			[
				'tag_name' => '강/호수 주변',
				'tag_machine_name' => 'near_river_or_lake',
			],
			[
				'tag_name' => '공항 근처',
				'tag_machine_name' => 'near_airport',
			],
			[
				'tag_name' => '주차가능',
				'tag_machine_name' => 'parking_available',
			],
			// [
			// 	'tag_name' => '노래방',
			// 	'tag_machine_name' => 'karaoke',
			// ],

			// [
			// 	'tag_name' => '세미나실',
			// 	'tag_machine_name' => 'seminar_room',
			// ],
			// [
			// 	'tag_name' => '골프장',
			// 	'tag_machine_name' => 'golf_course',
			// ],
			// [
			// 	'tag_name' => '레스토랑',
			// 	'tag_machine_name' => 'restaurant',
			// ],
			// [
			// 	'tag_name' => '공용주방',
			// 	'tag_machine_name' => 'shared_kitchen',
			// ],
			// [
			// 	'tag_name' => '온천',
			// 	'tag_machine_name' => 'hot_spring',
			// ],
			// [
			// 	'tag_name' => '유아시설',
			// 	'tag_machine_name' => 'infant_facilities',
			// ],
			// [
			// 	'tag_name' => '실내수영장',
			// 	'tag_machine_name' => 'indoor_pool',
			// ],
			// [
			// 	'tag_name' => '야외 수영장',
			// 	'tag_machine_name' => 'outdoor_pool',
			// ],
			// [
			// 	'tag_name' => '어린이수영장',
			// 	'tag_machine_name' => 'kids_pool',
			// ],
			// [
			// 	'tag_name' => '오락실',
			// 	'tag_machine_name' => 'arcade_room',
			// ],
			// [
			// 	'tag_name' => '이그제큐티브 라운지',
			// 	'tag_machine_name' => 'executive_lounge',
			// ],
			// [
			// 	'tag_name' => '수목원/휴양림 주변',
			// 	'tag_machine_name' => 'near_botanical_gardens_or_forests',
			// ],
			// [
			// 	'tag_name' => '수상레져',
			// 	'tag_machine_name' => 'water_sports_available',
			// ],
			// [
			// 	'tag_name' => '짐보관',
			// 	'tag_machine_name' => 'luggage_storage',
			// ],
			// [
			// 	'tag_name' => '픽업',
			// 	'tag_machine_name' => 'pickup_service_available',
			// ],
			// [
			// 	'tag_name' => '정원 전망',
			// 	'tag_machine_name' => 'garden_view',
			// ],
			// [
			// 	'tag_name' => '수영장 전망',
			// 	'tag_machine_name' => 'pool_view',
			// ],
			// [
			// 	'tag_name' => '항구 전망',
			// 	'tag_machine_name' => 'harbor_view',
			// ],
		];

		$stayTypeTags = [
			[
				'tag_name' => '호텔',
				'tag_machine_name' => 'hotel',
			],
			[
				'tag_name' => '리조트',
				'tag_machine_name' => 'resort',
			],
			[
				'tag_name' => '레지던스',
				'tag_machine_name' => 'residence',
			],
			[
				'tag_name' => '풀빌라',
				'tag_machine_name' => 'private_pool_villa',
			],
			[
				'tag_name' => '한옥',
				'tag_machine_name' => 'hanok_traditional_house',
			],
			[
				'tag_name' => '펜션',
				'tag_machine_name' => 'pension',
			],
			[
				'tag_name' => '카라반',
				'tag_machine_name' => 'caravan',
			],
			[
				'tag_name' => '글램핑',
				'tag_machine_name' => 'glamping',
			],
			[
				'tag_name' => '캠핑',
				'tag_machine_name' => 'camping',
			],
			[
				'tag_name' => '캐릭터룸 보유',
				'tag_machine_name' => 'character_rooms_available',
			],
			[
				'tag_name' => '애견펜션',
				'tag_machine_name' => 'pet_friendly_pension',
			],
			[
				'tag_name' => '키즈펜션',
				'tag_machine_name' => 'kids_friendly_pension',
			],
			[
				'tag_name' => '부티크',
				'tag_machine_name' => 'boutique',
			],
			[
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type',
			],
			[
				'tag_name' => '더블룸',
				'tag_machine_name' => 'double_room',
			],
			[
				'tag_name' => '트윈룸',
				'tag_machine_name' => 'twin_room',
			],
			[
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room',
			],
			[
				'tag_name' => '트리플룸',
				'tag_machine_name' => 'triple_room',
			],
			[
				'tag_name' => '패밀리룸',
				'tag_machine_name' => 'family_room',
			],
			[
				'tag_name' => '스위트룸',
				'tag_machine_name' => 'suite_room',
			],
			// [
			// 	'tag_name' => '게스트하우스',
			// 	'tag_machine_name' => 'guesthouse',
			// ],
			// [
			// 	'tag_name' => '모텔',
			// 	'tag_machine_name' => 'motel',
			// ],
			// [
			// 	'tag_name' => '감성 숙소',
			// 	'tag_machine_name' => 'emotional_accommodation',
			// ],
		];

		$newStayTasteTags = [
			[
				'tag_name' => '아이와 갈만한 곳',
				'tag_machine_name' => 'places_to_visit_with_kids',
			],
			[
				'tag_name' => '가성비 중요',
				'tag_machine_name' => 'value_for_money_important',
			],
			[
				'tag_name' => '여유로운 힐링',
				'tag_machine_name' => 'relaxing_healing',
			],
			[
				'tag_name' => '자연과 함께',
				'tag_machine_name' => 'with_nature',
			],
			[
				'tag_name' => '해수욕장 주변',
				'tag_machine_name' => 'near_beach',
			],
			[
				'tag_name' => '전망좋은 곳',
				'tag_machine_name' => 'places_with_great_views',
			],
			[
				'tag_name' => '바다 전망',
				'tag_machine_name' => 'sea_view',
			],
			[
				'tag_name' => '이색적인 체험',
				'tag_machine_name' => 'unique_experiences',
			],
			[
				'tag_name' => '여유로운 평일여행',
				'tag_machine_name' => 'relaxed_weekday_travel',
			],
			[
				'tag_name' => '저렴하지만 깨끗한',
				'tag_machine_name' => 'clean_but_affordable',
			],
			[
				'tag_name' => '감성 넘치는 분위기',
				'tag_machine_name' => 'emotional_vibe',
			],
			[
				'tag_name' => '온돌룸',
				'tag_machine_name' => 'traditional_ondol_room',
			],
			[
				'tag_name' => '캐릭터룸',
				'tag_machine_name' => 'character_rooms_available',
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

		$newTravelTasteTags = [
			[
				'tag_name' => '조식은 필수',
				'tag_machine_name' => 'breakfast_service',
			],
			[
				'tag_name' => '수영장',
				'tag_machine_name' => 'swimming_pool',
			],
			[
				'tag_name' => '사계절 온수/미온수풀',
				'tag_machine_name' => 'year_round_heated_pool',
			],
			[
				'tag_name' => '개별 수영장',
				'tag_machine_name' => 'private_pool_available',
			],
			[
				'tag_name' => '인피니티풀',
				'tag_machine_name' => 'infinity_pool',
			],
			[
				'tag_name' => '키즈플레이룸',
				'tag_machine_name' => 'kids_playroom',
			],
			[
				'tag_name' => '바베큐장',
				'tag_machine_name' => 'barbecue_area',
			],
			[
				'tag_name' => '취사가능',
				'tag_machine_name' => 'cooking_allowed',
			],
			[
				'tag_name' => '패밀리룸 보유',
				'tag_machine_name' => 'family_room',
			],
			[
				'tag_name' => '패밀리룸 보유(3인)',
				'tag_machine_name' => 'family_room_for_3_people',
			],
			[
				'tag_name' => '패밀리룸 보유(4인)',
				'tag_machine_name' => 'family_room_for_4_people',
			],
			[
				'tag_name' => '대형 객실 보유(5인+)',
				'tag_machine_name' => 'large_rooms_for_5_or_more_people',
			],
			[
				'tag_name' => '스파/월풀',
				'tag_machine_name' => 'spa_whirlpool',
			],
			[
				'tag_name' => '주차가능',
				'tag_machine_name' => 'parking_available',
			],
            [
                'tag_name' => '온돌룸',
                'tag_machine_name' => 'traditional_ondol_room',
            ],
            [
                'tag_name' => '반려동물 동반가능',
                'tag_machine_name' => 'pet_friendly',
            ],
		];

		$newStayTypeTags = [
			[
				'tag_name' => '호텔',
				'tag_machine_name' => 'hotel',
			],
			[
				'tag_name' => '리조트',
				'tag_machine_name' => 'resort',
			],
			[
				'tag_name' => '풀빌라',
				'tag_machine_name' => 'private_pool_villa',
			],
			[
				'tag_name' => '키즈펜션',
				'tag_machine_name' => 'kids_friendly_pension',
			],
			[
				'tag_name' => '독채형',
				'tag_machine_name' => 'private_house_type',
			],
			[
				'tag_name' => '캐릭터룸 보유',
				'tag_machine_name' => 'character_rooms_available',
			],
			[
				'tag_name' => '한옥',
				'tag_machine_name' => 'hanok_traditional_house',
			],
			[
				'tag_name' => '펜션',
				'tag_machine_name' => 'pension',
			],
			[
				'tag_name' => '카라반',
				'tag_machine_name' => 'caravan',
			],
			[
				'tag_name' => '글램핑',
				'tag_machine_name' => 'glamping',
			],
			[
				'tag_name' => '캠핑',
				'tag_machine_name' => 'camping',
			],
			[
				'tag_name' => '애견펜션',
				'tag_machine_name' => 'pet_friendly_pension',
			],
			[
				'tag_name' => '레지던스',
				'tag_machine_name' => 'residence',
			],
		];


		$newTravelTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'travelTaste';
			return $tag;
		}, $newTravelTasteTags);

		$newStayTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayTaste';
			return $tag;
		}, $newStayTasteTags);

		$newStayTypeTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayType';
			return $tag;
		}, $newStayTypeTags);

		$travelTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'travelTaste';
			return $tag;
		}, $travelTasteTags);

		$eventTags = array_map(function ($tag) {
			$tag['tag_section'] = 'event';
			return $tag;
		}, $eventTags);

		$stayTasteTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayTaste';
			return $tag;
		}, $stayTasteTags);

		$stayTypeTags = array_map(function ($tag) {
			$tag['tag_section'] = 'stayType';
			return $tag;
		}, $stayTypeTags);

		return [
			'petDetailTags' => $petDetailTags, // 반려동물 상세 선택
			'travelTasteTags' => $travelTasteTags, // 여행 취향
			'eventTags' => $eventTags, // 이벤트
			'stayTasteTags' => $stayTasteTags, // 숙박 취향
			'stayTypeTags' => $stayTypeTags, // 선호 숙박 유형
			'newTravelTasteTags' => $newTravelTasteTags,
			'newStayTasteTags' => $newStayTasteTags,
			'newStayTypeTags' => $newStayTypeTags,
		];
	}
}
