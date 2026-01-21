<?php

namespace App\Services;

class SearchService
{
	public static function getPopularTerms()
	{
		$terms = [
			[
				'term_name' => '부산',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/busan.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '제주',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/jeju.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '여수',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/yeosu.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '인천',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/incheon.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '강릉',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/gangneung.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '양양',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/yangyang.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '포항',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/pohang.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '울산',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/ulsan.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '전주',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/jeonju.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '대구',
				'category_type' => 'city',
				'image_path' => '/uploads/tags/daegu.png?v=' . $_ENV['VERSION']
			],
		];

		return $terms;
	}

	public static function getRisingCities()
	{
		$cities = [
			[
				'term_name' => '남해',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/namhae.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '거제',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/geoje.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '가평',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/gapyeong.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '울진',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/uljin.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '경주',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/gyeongju.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '영덕',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/yeongdeok.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '속초',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/sokcho.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '안동',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/andong.jpg?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '군산',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/gunsan.png?v=' . $_ENV['VERSION']
			],
			[
				'term_name' => '보령',
				'category_type' => 'city',
				'image_path' => '/assets/app/images/city/boryeong.jpg?v=' . $_ENV['VERSION']
			],
		];

		return $cities;
	}
}
