<?php

namespace Sb;

class Client
{

	/**
	 * client version
	 */
	const VERSION = '0.1';

	/**
	 * spambarrier api url
	 */
	const SB_API_URL = 'https://www.spambarrier.de/api/';

	/**
	 * user id
	 *
	 * @var integer
	 */
	private $userId;

	/**
	 * api token
	 *
	 * @var string
	 */
	private $apiToken;

	/**
	 * @param $userId
	 * @param $apiToken
	 */
	public function __construct($userId, $apiToken)
	{
		$this->userId = (int) $userId;
		$this->apiToken = (string) $apiToken;
	}

	/**
	 * get a list of all configured domains
	 *
	 * @return object
	 * @throws Exception
	 */
	public function getDomains()
	{
		return $this->apiRequest('get-domains');
	}

	/**
	 * do api request
	 *
	 * @param $method
	 * @param array $params
	 * @return object
	 * @throws Exception
	 */
	protected function apiRequest($method, $params = array())
	{
		$requestParams = array(
			'method' => $method,
			'params' => array_merge(array(
				'user_id' => $this->userId,
				'api_token' => $this->apiToken
			), $params)
		);

		$requestParams = json_encode($requestParams);

		$curl = curl_init(self::SB_API_URL);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestParams);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . mb_strlen($requestParams))
		);

		$response = curl_exec($curl);

		$responseParams = json_decode($response);

		if (!$responseParams) {
			throw new Exception('unable to parse response');
		}

		if ($responseParams->result === 'error') {
			throw new Exception($responseParams->params->message);
		}

		return $responseParams->params;
	}

}