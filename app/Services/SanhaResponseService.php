<?php

namespace App\Services;

use Carbon\Carbon;

class SanhaResponseService
{
	public static function AvailabilityAndRatesSuccess($call = false)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if($call) {
			$root = $dom->createElement('OTAProcessCall_RS');
		} else {
			$root = $dom->createElement('OTAAvailabilityAndRates_RS');
		}
		
		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$success = $dom->createElement('Success');
		$root->appendChild($success);

		$xmlString = $dom->saveXML();

		header('Content-Type: application/xml; charset=UTF-8');
		echo $xmlString;

		exit;
	}

	public static function noData() {
		header('Content-Type: application/json; charset=utf-8');
		
		echo json_encode(['error' => 'No data received or error reading input.']);
	}

	public static function warningW101($call = false)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if($call) {
			$root = $dom->createElement('OTAProcessCall_RS');
		} else {
			$root = $dom->createElement('OTAAvailabilityAndRates_RS');
		}

		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$warning = $dom->createElement('Warning');
		$warning->setAttribute('Code', 'W101');

		$message = $dom->createElement('Message');
		$messageText = $dom->createTextNode('처리할 데이터가 없습니다.');
		$message->appendChild($messageText);

		$warning->appendChild($message);

		$root->appendChild($warning);

		$xmlString = $dom->saveXML();

		header('Content-Type: application/xml; charset=UTF-8');
		echo $xmlString;

		exit;
	}

	public static function errorE102($call = false)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if($call) {
			$root = $dom->createElement('OTAProcessCall_RS');
		} else {
			$root = $dom->createElement('OTAAvailabilityAndRates_RS');
		}

		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$error = $dom->createElement('Errors');
		$error->setAttribute('Code', 'E102');

		$message = $dom->createElement('Message');
		$messageText = $dom->createTextNode('채널 코드를 확인할 수 없습니다.');
		$message->appendChild($messageText);

		$error->appendChild($message);

		$root->appendChild($error);

		$xmlString = $dom->saveXML();

		header('Content-Type: application/xml; charset=UTF-8');
		echo $xmlString;

		exit;
	}

	public static function errorE111($call = false)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if($call) {
			$root = $dom->createElement('OTAProcessCall_RS');
		} else {
			$root = $dom->createElement('OTAAvailabilityAndRates_RS');
		}

		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$error = $dom->createElement('Errors');
		$error->setAttribute('Code', 'E111');

		$message = $dom->createElement('Message');
		$messageText = $dom->createTextNode('인증 정보가 없습니다.');
		$message->appendChild($messageText);

		$error->appendChild($message);

		$root->appendChild($error);

		$xmlString = $dom->saveXML();

		header('Content-Type: application/xml; charset=UTF-8');
		echo $xmlString;

		exit;
	}

	public static function errorE112($call = false)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if($call) {
			$root = $dom->createElement('OTAProcessCall_RS');
		} else {
			$root = $dom->createElement('OTAAvailabilityAndRates_RS');
		}

		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$error = $dom->createElement('Errors');
		$error->setAttribute('Code', 'E112');

		$message = $dom->createElement('Message');
		$messageText = $dom->createTextNode('인증 실패했습니다.');
		$message->appendChild($messageText);

		$error->appendChild($message);

		$root->appendChild($error);

		$xmlString = $dom->saveXML();

		header('Content-Type: application/xml; charset=UTF-8');
		echo $xmlString;

		exit;
	}

	public static function errorE301($call = false)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');

		if($call) {
			$root = $dom->createElement('OTAProcessCall_RS');
		} else {
			$root = $dom->createElement('OTAAvailabilityAndRates_RS');
		}
		
		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$error = $dom->createElement('Errors');
		$error->setAttribute('Code', 'E301');

		$message = $dom->createElement('Message');
		$messageText = $dom->createTextNode('존재하지 않는 룸타입 코드가 있습니다.');
		$message->appendChild($messageText);

		$error->appendChild($message);

		$root->appendChild($error);

		$xmlString = $dom->saveXML();

		header('Content-Type: application/xml; charset=UTF-8');
		echo $xmlString;

		exit;
	}
}
