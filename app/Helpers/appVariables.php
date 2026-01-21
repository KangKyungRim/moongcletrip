<?php


function getProductCategory($category, $language = 'ko')
{
	if ($language === 'ko') {
		switch ($category) {
			case 'stay':
				return '숙박';
			case 'activity':
				return '체험';
			case 'tour':
				return '투어';
			case 'air':
				return '항공';
			default:
				return '';
		}
	}

	return '';
}

function getProductStatus($status, $language = 'ko')
{
	if ($language === 'ko') {
		switch ($status) {
			case 'confirmed':
				return '이용 전';
			case 'completed':
				return '이용 완료';
			case 'canceled':
				return '취소';
			default:
				return '';
		}
	}

	return '';
}

function getVisitWay($visit, $language = 'ko')
{
	if ($language === 'ko') {
		switch ($visit) {
			case 'vehicle':
				return '차량';
			case 'walk':
				return '도보';
			default:
				return '';
		}
	}

	return '';
}

function getDaysInOtherLanguage($dayOfWeek, $language = 'ko')
{
	if ($language === 'ko') {
		switch ($dayOfWeek) {
			case 'Sunday':
				return '일';
			case 'Monday':
				return '월';
			case 'Tuesday':
				return '화';
			case 'Wednesday':
				return '수';
			case 'Thursday':
				return '목';
			case 'Friday':
				return '금';
			case 'Saturday':
				return '토';
			default:
				return '';
		}
	}

	return '';
}
