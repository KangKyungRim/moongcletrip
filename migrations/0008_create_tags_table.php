<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('tags')) {
    Capsule::schema()->create('tags', function (Blueprint $table) {
        $table->bigIncrements('tag_idx');
        // $table->string('tag_type', 20)->index(); // amenity, who, where, when, how, why, what
        // $table->string('tag_subtype', 30)->index(); // amenity, barrierfree, room_amenity, room_barrierfree
        $table->string('tag_name', 255)->index();
        $table->string('tag_machine_name', 255)->index();
        $table->timestamp('tag_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('tag_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();
    });

    Capsule::table('tags')->insert([
        [
            'tag_name' => '가성비 중요',
            'tag_machine_name' => 'value_for_money_important',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '여유로운 힐링',
            'tag_machine_name' => 'relaxing_healing',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '도보여행 선호',
            'tag_machine_name' => 'prefer_walking_tour',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '도심 속 호캉스',
            'tag_machine_name' => 'urban_hotel_staycation',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '전망좋은 곳',
            'tag_machine_name' => 'places_with_great_views',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '리뷰좋은 곳',
            'tag_machine_name' => 'places_with_good_reviews',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '로맨틱 분위기',
            'tag_machine_name' => 'romantic_atmosphere',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '야경즐기기',
            'tag_machine_name' => 'enjoy_nightscape',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '여유로운 평일여행',
            'tag_machine_name' => 'relaxed_weekday_travel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '자연과 함께',
            'tag_machine_name' => 'with_nature',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '숲캉스',
            'tag_machine_name' => 'forest_staycation',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '주변먹거리 많음',
            'tag_machine_name' => 'plenty_of_nearby_food_options',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '체인호텔 선호',
            'tag_machine_name' => 'prefer_chain_hotels',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '로컬호텔 선호',
            'tag_machine_name' => 'prefer_local_hotels',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '등산/트레킹은 필수',
            'tag_machine_name' => 'hiking_trekking_required',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '골프는 필수',
            'tag_machine_name' => 'golf_required',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '일주일 살기',
            'tag_machine_name' => 'one_week_stay',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '한달 살기',
            'tag_machine_name' => 'one_month_stay',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '워케이션',
            'tag_machine_name' => 'workation',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '럭셔리 여행',
            'tag_machine_name' => 'luxury_travel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '비즈니스 출장',
            'tag_machine_name' => 'business_trip',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '높은 층 선호',
            'tag_machine_name' => 'prefer_high_floors',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '낮은 층 선호',
            'tag_machine_name' => 'prefer_low_floors',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '사색있는 여행',
            'tag_machine_name' => 'reflective_travel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '관광보다는 휴양',
            'tag_machine_name' => 'prefer_rest_over_sightseeing',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '휴양보다는 관광',
            'tag_machine_name' => 'prefer_sightseeing_over_rest',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '패키지 여행',
            'tag_machine_name' => 'package_tour',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '패키지(노쇼핑)',
            'tag_machine_name' => 'package_no_shopping',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '에어텔(숙소+항공)',
            'tag_machine_name' => 'airtel_accommodation_and_flight',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '입장권/티켓',
            'tag_machine_name' => 'admission_tickets',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '테마파크 즐기기',
            'tag_machine_name' => 'enjoy_theme_parks',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공연/전시 즐기기',
            'tag_machine_name' => 'enjoy_performances_exhibitions',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '가이드 투어 체험',
            'tag_machine_name' => 'guided_tour_experience',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '유명 랜드마크',
            'tag_machine_name' => 'famous_landmarks',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '쇼핑은 즐거워',
            'tag_machine_name' => 'enjoy_shopping',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '로컬 맛집 탐방',
            'tag_machine_name' => 'explore_local_cuisine',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '기차 투어',
            'tag_machine_name' => 'train_tour',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '현지인과 함께',
            'tag_machine_name' => 'with_locals',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '로컬 시장 체험',
            'tag_machine_name' => 'local_market_experience',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '배낭여행',
            'tag_machine_name' => 'backpacking',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '당일치기 여행',
            'tag_machine_name' => 'day_trip',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '가슴아픈 역사가있는',
            'tag_machine_name' => 'places_with_painful_history',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '역사가 가득한',
            'tag_machine_name' => 'historical_places',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '드라이브 하기 좋은',
            'tag_machine_name' => 'great_for_driving',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '자전거 여행',
            'tag_machine_name' => 'bicycle_trip',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '효도 여행',
            'tag_machine_name' => 'filial_piety_trip',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '시티투어 체험',
            'tag_machine_name' => 'city_tour_experience',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '역사/문화 투어',
            'tag_machine_name' => 'history_culture_tour',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '데이트장소 추천',
            'tag_machine_name' => 'romantic_spot_recommendation',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '아이와 갈만한 곳',
            'tag_machine_name' => 'places_to_visit_with_kids',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '이색적인 체험',
            'tag_machine_name' => 'unique_experiences',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '이국적인',
            'tag_machine_name' => 'exotic_atmosphere',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '캐릭터룸 보유',
            'tag_machine_name' => 'character_rooms_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '스파/월풀',
            'tag_machine_name' => 'spa_whirlpool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '노래방',
            'tag_machine_name' => 'karaoke',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '농구장',
            'tag_machine_name' => 'basketball_court',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '매점/편의점',
            'tag_machine_name' => 'convenience_store',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '바베큐장',
            'tag_machine_name' => 'barbecue_area',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '세미나실',
            'tag_machine_name' => 'seminar_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '수영장',
            'tag_machine_name' => 'swimming_pool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '워터슬라이드',
            'tag_machine_name' => 'water_slide',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '족구장',
            'tag_machine_name' => 'foot_volleyball_court',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '찜질방',
            'tag_machine_name' => 'jjimjilbang_sauna',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '축구장/풋살장',
            'tag_machine_name' => 'soccer_field_futsal_field',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '카페',
            'tag_machine_name' => 'cafe',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '탁구장',
            'tag_machine_name' => 'table_tennis_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '피트니스',
            'tag_machine_name' => 'fitness_center',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공용스파',
            'tag_machine_name' => 'shared_spa',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '골프장',
            'tag_machine_name' => 'golf_course',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '레스토랑',
            'tag_machine_name' => 'restaurant',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '키즈플레이룸',
            'tag_machine_name' => 'kids_playroom',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공용샤워실',
            'tag_machine_name' => 'shared_shower_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공용화장실',
            'tag_machine_name' => 'shared_restroom',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공용주방',
            'tag_machine_name' => 'shared_kitchen',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '워터파크',
            'tag_machine_name' => 'water_park',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '온천',
            'tag_machine_name' => 'hot_spring',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '사우나',
            'tag_machine_name' => 'sauna',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '연회장',
            'tag_machine_name' => 'banquet_hall',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '비즈니스센터',
            'tag_machine_name' => 'business_center',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '루프탑',
            'tag_machine_name' => 'rooftop',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '유아시설',
            'tag_machine_name' => 'infant_facilities',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '실내수영장',
            'tag_machine_name' => 'indoor_pool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '야외 수영장',
            'tag_machine_name' => 'outdoor_pool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '인피니티풀',
            'tag_machine_name' => 'infinity_pool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '사계절 온수/미온수풀',
            'tag_machine_name' => 'year_round_heated_pool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '어린이수영장',
            'tag_machine_name' => 'kids_pool',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '패밀리룸 보유(3인)',
            'tag_machine_name' => 'family_room_for_3_people',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '패밀리룸 보유(4인)',
            'tag_machine_name' => 'family_room_for_4_people',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '장애인객실',
            'tag_machine_name' => 'accessible_rooms',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '룸서비스',
            'tag_machine_name' => 'room_service',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '24시간 데스크',
            'tag_machine_name' => '24_hour_front_desk',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '오락실',
            'tag_machine_name' => 'arcade_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '셀프세탁실',
            'tag_machine_name' => 'self_laundry_facilities',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'OTT(넷플릭스 등)',
            'tag_machine_name' => 'OTT_services_(e.g._Netflix)',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'SNS에서 인기많은',
            'tag_machine_name' => 'popular_on_SNS',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '저렴하지만 깨끗한',
            'tag_machine_name' => 'clean_but_affordable',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '힙한 분위기',
            'tag_machine_name' => 'hip_atmosphere',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '감성 넘치는 분위기',
            'tag_machine_name' => 'emotional_vibe',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '중식',
            'tag_machine_name' => 'lunch_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '디너',
            'tag_machine_name' => 'dinner_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '파티룸 보유',
            'tag_machine_name' => 'party_room_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '미슐랭 레스토랑 보유',
            'tag_machine_name' => 'michelin_starred_restaurant',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '대형 객실 보유(5인+)',
            'tag_machine_name' => 'large_rooms_for_5_or_more_people',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '이그제큐티브 라운지',
            'tag_machine_name' => 'executive_lounge',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '대중교통 접근 가능',
            'tag_machine_name' => 'accessible_by_public_transport',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '대실 운영 중',
            'tag_machine_name' => 'short_stay_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '루프탑 칵테일',
            'tag_machine_name' => 'rooftop_cocktail',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '계곡 주변',
            'tag_machine_name' => 'near_valley',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '골프장 주변',
            'tag_machine_name' => 'near_golf_course',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '낚시장 주변',
            'tag_machine_name' => 'near_fishing_spots',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '수목원/휴양림 주변',
            'tag_machine_name' => 'near_botanical_gardens_or_forests',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '수상레져',
            'tag_machine_name' => 'water_sports_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '스키장 주변',
            'tag_machine_name' => 'near_ski_resort',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '해수욕장 주변',
            'tag_machine_name' => 'near_beach',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '강/호수 주변',
            'tag_machine_name' => 'near_river_or_lake',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공항 근처',
            'tag_machine_name' => 'near_airport',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '반려동물 동반가능',
            'tag_machine_name' => 'pet_friendly',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '짐보관',
            'tag_machine_name' => 'luggage_storage',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '취사가능',
            'tag_machine_name' => 'cooking_allowed',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '픽업',
            'tag_machine_name' => 'pickup_service_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '개별 수영장',
            'tag_machine_name' => 'private_pool_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '프로포즈',
            'tag_machine_name' => 'proposal',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '결혼기념일',
            'tag_machine_name' => 'wedding_anniversary',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '커플기념일',
            'tag_machine_name' => 'couple_anniversary',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '생일',
            'tag_machine_name' => 'birthday',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '돌&백일',
            'tag_machine_name' => 'first_birthday_and_100th_day_ceremony',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '허니문',
            'tag_machine_name' => 'honeymoon',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '브라이덜샤워',
            'tag_machine_name' => 'bridal_shower',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '회사 이벤트',
            'tag_machine_name' => 'company_event',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '호텔',
            'tag_machine_name' => 'hotel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '펜션',
            'tag_machine_name' => 'pension',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '게스트하우스',
            'tag_machine_name' => 'guesthouse',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '모텔',
            'tag_machine_name' => 'motel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '카라반',
            'tag_machine_name' => 'caravan',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '글램핑',
            'tag_machine_name' => 'glamping',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '캠핑',
            'tag_machine_name' => 'camping',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '리조트',
            'tag_machine_name' => 'resort',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '한옥',
            'tag_machine_name' => 'hanok_traditional_house',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '풀빌라',
            'tag_machine_name' => 'private_pool_villa',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '레지던스',
            'tag_machine_name' => 'residence',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '감성 숙소',
            'tag_machine_name' => 'emotional_accommodation',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '렌터카',
            'tag_machine_name' => 'rental_car',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '여행자 보험',
            'tag_machine_name' => 'travel_insurance',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '유심/이심',
            'tag_machine_name' => 'SIM_or_eSIM',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '항공 (비즈니스석)',
            'tag_machine_name' => 'flight_business_class',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '항공 (이코노미석)',
            'tag_machine_name' => 'flight_economy_class',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '항공 (퍼스트클래스)',
            'tag_machine_name' => 'flight_first_class',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '직항 선호',
            'tag_machine_name' => 'prefer_direct_flights',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '경유편 상관없음',
            'tag_machine_name' => 'no_preference_on_stopover_flights',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '항공 제외',
            'tag_machine_name' => 'exclude_flights',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '크루즈/유람선',
            'tag_machine_name' => 'cruise_or_ferry',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공항 짐보관',
            'tag_machine_name' => 'airport_luggage_storage',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '메리어트 본보이',
            'tag_machine_name' => 'marriott_bonvoy',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '힐튼',
            'tag_machine_name' => 'hilton',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '아코르 앰배서더',
            'tag_machine_name' => 'accor_ambassador',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '롯데 호텔&리조트',
            'tag_machine_name' => 'lotte_hotels_and_resorts',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '롯데시티호텔',
            'tag_machine_name' => 'lotte_city_hotel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '신라 호텔',
            'tag_machine_name' => 'shilla_hotel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '신라스테이',
            'tag_machine_name' => 'shilla_stay',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '하얏트',
            'tag_machine_name' => 'hyatt',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '반얀트리 그룹',
            'tag_machine_name' => 'banyan_tree_group',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '스카이파크',
            'tag_machine_name' => 'skypark',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '나인트리 호텔',
            'tag_machine_name' => 'nine_tree_hotel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '파르나스 호텔',
            'tag_machine_name' => 'parnas_hotel',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '호텔 더 디자이너스',
            'tag_machine_name' => 'hotel_the_designers',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'IHG 호텔&리조트',
            'tag_machine_name' => 'IHG_hotels_and_resorts',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '트래블로지',
            'tag_machine_name' => 'travelodge',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'GLAD',
            'tag_machine_name' => 'GLAD',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'SPG 호텔',
            'tag_machine_name' => 'SPG_hotels',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '오라카이',
            'tag_machine_name' => 'orakai',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '라마다',
            'tag_machine_name' => 'ramada',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '켄싱턴호텔앤리조트',
            'tag_machine_name' => 'kensington_hotels_and_resorts',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '기타 체인호텔',
            'tag_machine_name' => 'other_chain_hotels',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '혼자',
            'tag_machine_name' => 'alone',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '친구와',
            'tag_machine_name' => 'with_friends',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '배우자와',
            'tag_machine_name' => 'with_spouse',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '아이와',
            'tag_machine_name' => 'with_kids',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '부모님과',
            'tag_machine_name' => 'with_parents',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '직장동료',
            'tag_machine_name' => 'with_colleagues',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '기타',
            'tag_machine_name' => 'others',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '가족',
            'tag_machine_name' => 'family',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '고급/럭셔리',
            'tag_machine_name' => 'luxury',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '단체/MT/워크샵',
            'tag_machine_name' => 'group_MT_workshop',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '커플',
            'tag_machine_name' => 'couple',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '애견펜션',
            'tag_machine_name' => 'pet_friendly_pension',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '키즈펜션',
            'tag_machine_name' => 'kids_friendly_pension',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '5성',
            'tag_machine_name' => '5_star',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '4성',
            'tag_machine_name' => '4_star',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '3성',
            'tag_machine_name' => '3_star',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '2성',
            'tag_machine_name' => '2_star',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '1성',
            'tag_machine_name' => '1_star',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '특급1급',
            'tag_machine_name' => 'first_class_and_grade_1',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '특급',
            'tag_machine_name' => 'first_class',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '1급',
            'tag_machine_name' => 'grade_1',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '관광',
            'tag_machine_name' => 'tourism',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '비즈니스',
            'tag_machine_name' => 'business',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '부티크',
            'tag_machine_name' => 'boutique',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'WIFI',
            'tag_machine_name' => 'WIFI',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '발렛파킹',
            'tag_machine_name' => 'valet_parking',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '보드게임',
            'tag_machine_name' => 'board_games',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '셔틀버스',
            'tag_machine_name' => 'shuttle_bus',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '영화관람',
            'tag_machine_name' => 'movie_screening',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '자전거대여',
            'tag_machine_name' => 'bicycle_rental',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '조식 서비스',
            'tag_machine_name' => 'breakfast_service',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '캠프파이어',
            'tag_machine_name' => 'campfire_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '프로포즈/파티/이벤트',
            'tag_machine_name' => 'proposals_parties_events',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '프린터 사용',
            'tag_machine_name' => 'printer_usage',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '상비약',
            'tag_machine_name' => 'basic_first_aid_kit',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '금연',
            'tag_machine_name' => 'non_smoking',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '객실내흡연',
            'tag_machine_name' => 'in_room_smoking_allowed',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '개인사물함',
            'tag_machine_name' => 'personal_lockers',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '무료주차',
            'tag_machine_name' => 'free_parking',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '장애인편의시설',
            'tag_machine_name' => 'accessible_facilities',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공항 셔틀',
            'tag_machine_name' => 'airport_shuttle',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '바/라운지',
            'tag_machine_name' => 'bar_or_lounge',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '마사지',
            'tag_machine_name' => 'massage_service',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '주차가능',
            'tag_machine_name' => 'parking_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '기본양념',
            'tag_machine_name' => 'basic_seasonings_provided',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '가스레인지/인덕션',
            'tag_machine_name' => 'gas_stove_or_induction_cooktop',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '개별/테라스 바베큐',
            'tag_machine_name' => 'private_or_terrace_barbecue',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '냉장고',
            'tag_machine_name' => 'refrigerator',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '다리미',
            'tag_machine_name' => 'iron',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '드라이기',
            'tag_machine_name' => 'hairdryer',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '미니바',
            'tag_machine_name' => 'mini_bar',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '벽난로',
            'tag_machine_name' => 'fireplace',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '쇼파',
            'tag_machine_name' => 'sofa',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '식탁',
            'tag_machine_name' => 'dining_table',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '에어컨',
            'tag_machine_name' => 'air_conditioner',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '욕실용품',
            'tag_machine_name' => 'bathroom_amenities',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '욕조',
            'tag_machine_name' => 'bathtub',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '전기밥솥',
            'tag_machine_name' => 'electric_rice_cooker',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '전자레인지',
            'tag_machine_name' => 'microwave',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '취사도구',
            'tag_machine_name' => 'cooking_utensils',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '커피포트',
            'tag_machine_name' => 'electric_kettle',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '타월',
            'tag_machine_name' => 'towels',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'TV',
            'tag_machine_name' => 'TV',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '비데',
            'tag_machine_name' => 'bidet',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '흡연가능',
            'tag_machine_name' => 'smoking_allowed',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '파티룸',
            'tag_machine_name' => 'party_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '화장실',
            'tag_machine_name' => 'toilet',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => 'VOD',
            'tag_machine_name' => 'VOD_services',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '알람시계',
            'tag_machine_name' => 'alarm_clock',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '무료생수',
            'tag_machine_name' => 'free_bottled_water',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '책상',
            'tag_machine_name' => 'desk',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '안전금고',
            'tag_machine_name' => 'safety_box',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '전화',
            'tag_machine_name' => 'telephone',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '욕실',
            'tag_machine_name' => 'bathroom',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '목욕가운',
            'tag_machine_name' => 'bathrobe',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '슬리퍼',
            'tag_machine_name' => 'slippers',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '옷장',
            'tag_machine_name' => 'closet',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '칫솔/치약',
            'tag_machine_name' => 'toothbrush_and_toothpaste',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '샴푸/바디샴푸/컨디셔너',
            'tag_machine_name' => 'shampoo_bodywash_conditioner',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '와인잔',
            'tag_machine_name' => 'wine_glasses',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '샤워부스',
            'tag_machine_name' => 'shower_booth',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '발코니/테라스',
            'tag_machine_name' => 'balcony_or_terrace',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '난방',
            'tag_machine_name' => 'heating',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '블루투스 스피커',
            'tag_machine_name' => 'bluetooth_speaker',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '도미토리형',
            'tag_machine_name' => 'dormitory_type',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '독채형',
            'tag_machine_name' => 'private_house_type',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '복층형',
            'tag_machine_name' => 'duplex_type',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '원룸형',
            'tag_machine_name' => 'studio_type',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '분리형',
            'tag_machine_name' => 'separate_room_type',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '싱글룸',
            'tag_machine_name' => 'single_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '더블룸',
            'tag_machine_name' => 'double_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '트윈룸',
            'tag_machine_name' => 'twin_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '온돌룸',
            'tag_machine_name' => 'traditional_ondol_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '트리플룸',
            'tag_machine_name' => 'triple_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '패밀리룸',
            'tag_machine_name' => 'family_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '스위트룸',
            'tag_machine_name' => 'suite_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '펜트하우스',
            'tag_machine_name' => 'penthouse',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '여성전용',
            'tag_machine_name' => 'female_only',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '남성전용',
            'tag_machine_name' => 'male_only',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '남녀공용',
            'tag_machine_name' => 'mixed_gender',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '장애인 전용 주차',
            'tag_machine_name' => 'disabled_parking_only',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '체크인데스크(좌식)',
            'tag_machine_name' => 'seated_check_in_desk',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '공용공간 장애인화장실',
            'tag_machine_name' => 'shared_restrooms_for_disabled',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '엘레베이터',
            'tag_machine_name' => 'elevator_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '시각장애인 유도블럭',
            'tag_machine_name' => 'tactile_blocks_for_visually_impaired',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '수유실',
            'tag_machine_name' => 'nursing_room_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '휠체어 진입 가능한 중앙문(로비문)',
            'tag_machine_name' => 'wheelchair_accessible_main_door',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '부대시설에 휠체어 진입 가능',
            'tag_machine_name' => 'wheelchair_accessible_facilities',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '종합 점자안내판',
            'tag_machine_name' => 'braille_overview_map_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '엘레베이터 버튼 점자',
            'tag_machine_name' => 'braille_elevator_buttons',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '휠체어 대여가능',
            'tag_machine_name' => 'wheelchair_rental_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '청각 장애용 전화기',
            'tag_machine_name' => 'telephone_for_hearing_impaired',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '아기침대 대여',
            'tag_machine_name' => 'baby_crib_rental_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '침대가드 설치가능',
            'tag_machine_name' => 'bed_guard_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '임산부 필로우 대여',
            'tag_machine_name' => 'pregnancy_pillow_rental',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '유모차 대여',
            'tag_machine_name' => 'stroller_rental',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '호텔 입구 경사로 설치',
            'tag_machine_name' => 'ramp_installed_at_hotel_entrance',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '화장실 내 핸드레일',
            'tag_machine_name' => 'bathroom_handrails',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '휠체어 통과가능 문',
            'tag_machine_name' => 'wheelchair_accessible_doorways',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '낮은 카드키(문 손잡이)',
            'tag_machine_name' => 'low_card_keys_or_door_handles',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '핸드레일이 달린 욕조',
            'tag_machine_name' => 'bathtub_with_handrails',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '화장실 내 샤워의자',
            'tag_machine_name' => 'shower_chair_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '낮은 침대높이(45cm이하)',
            'tag_machine_name' => 'low_bed_height_under_45cm',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '화장실 냉온수 촉지판',
            'tag_machine_name' => 'temperature_adjustable_taps_in_bathroom',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '낮은 옷걸이',
            'tag_machine_name' => 'low_hangers_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '화장실 내 비상전화기',
            'tag_machine_name' => 'emergency_phone_in_bathroom',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '낮은 샤워기',
            'tag_machine_name' => 'low_shower_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '화장실 내 롤인샤워',
            'tag_machine_name' => 'roll_in_shower_available',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '객실 내부 휠체어 회전 가능',
            'tag_machine_name' => 'wheelchair_rotation_space_in_room',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '객실번호 점자안내판',
            'tag_machine_name' => 'braille_room_number_signs',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '출입구 시각장애인 유도블럭',
            'tag_machine_name' => 'tactile_blocks_at_room_entrances',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '바다 전망',
            'tag_machine_name' => 'sea_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '산 전망',
            'tag_machine_name' => 'mountain_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '도시 전망',
            'tag_machine_name' => 'city_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '정원 전망',
            'tag_machine_name' => 'garden_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '수영장 전망',
            'tag_machine_name' => 'pool_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '강 전망',
            'tag_machine_name' => 'river_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ],
        [
            'tag_name' => '항구 전망',
            'tag_machine_name' => 'harbor_view',
            'tag_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
            'tag_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
        ]
    ]);
}
