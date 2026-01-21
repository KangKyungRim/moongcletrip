<?php

namespace App\Services;

use GuzzleHttp\Client;

use App\Helpers\MiddleHelper;
use Carbon\Carbon;

class WaugAPIService
{
	private $clientId;
	private $clientSecret;
	private $domain;

	public function __construct()
	{
		$this->clientId = $_ENV['WAUG_ID'];
		$this->clientSecret = $_ENV['WAUG_SECRET'];

		if ($_ENV['APP_ENV'] == 'PRODUCTION') {
			$this->domain = $_ENV['WAUG_PRODUCTION_DOMAIN'];
		} else {
			$this->domain = $_ENV['WAUG_TEST_DOMAIN'];
		}
	}

	public function getAccessToken()
	{
		$client = new Client();

		$response = $client->post($this->domain . '/v1/token', [
			'auth' => [$this->clientId, $this->clientSecret]
		]);

		$data = json_decode($response->getBody(), true);

		return $data;
	}

	public function getCountries()
	{
		$client = new Client();

		$response = $client->get($this->domain . '/v1/countries', [
			'headers' => [
				'Authorization' => 'Bearer ExampleTokenValue',
				'Accept' => 'application/json',
			],
		]);

		$data = json_decode($response->getBody(), true);

		return $data;
	}

	public function getCountry()
	{
		$client = new Client();

		$response = $client->post($this->domain . '/v1/countries', [
			'auth' => [$this->clientId, $this->clientSecret]
		]);

		$data = json_decode($response->getBody(), true);

		return $data;
	}
}
