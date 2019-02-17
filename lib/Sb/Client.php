<?php

namespace Sb;

class Client
{

	/**
	 * client version
	 */
	const VERSION = '1.0';

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
	 * add a domain
	 *
	 * @param $domain
	 * @param $host
	 * @param null $username
	 * @param null $password
	 * @return object
	 * @throws Exception
	 */
	public function addDomain($domain, $host, $username = null, $password = null)
	{
		return $this->apiRequest('add-domain', array(
			'domain' => $domain,
			'host' => $host,
			'username' => $username,
			'password' => $password
		));
	}

	/**
	 * edit a domain
	 *
	 * @param $domain
	 * @param $host
	 * @param null $username
	 * @param null $password
	 * @return object
	 * @throws Exception
	 */
	public function editDomain($domain, $host, $username = null, $password = null)
	{
		return $this->apiRequest('edit-domain', array(
			'domain' => $domain,
			'host' => $host,
			'username' => $username,
			'password' => $password
		));
	}

	/**
	 * delete a domain
	 *
	 * @param $domain
	 * @return object
	 * @throws Exception
	 */
	public function deleteDomain($domain)
	{
		return $this->apiRequest('delete-domain', array(
			'domain' => $domain
		));
	}

	/**
	 * get filter settings for a domain
	 *
	 * @param $domain
	 * @return object
	 * @throws Exception
	 */
	public function getFilterSettings($domain)
	{
		return $this->apiRequest('get-filter-settings', array(
			'domain' => $domain
		));
	}

	/**
	 * set filter settings for a domain
	 *
	 * @param $domain
	 * @param $spamTreatment
	 * @param $spamThreshold
	 * @param $virusTreatment
	 * @param $greylisting
	 * @return object
	 * @throws Exception
	 */
	public function setFilterSettings($domain, $spamTreatment, $spamThreshold, $virusTreatment, $greylisting)
	{
		return $this->apiRequest('set-filter-settings', array(
			'domain' => $domain,
			'spam_treatment' => $spamTreatment,
			'spam_threshold' => $spamThreshold,
			'virus_treatment' => $virusTreatment,
			'greylisting' => $greylisting
		));
	}

	/**
	 * confirm domain after setting txt record
	 *
	 * @param $domain
	 * @return object
	 * @throws Exception
	 */
	public function confirmTxtRecord($domain)
	{
		return $this->apiRequest('confirm-txt-record', array(
			'domain' => $domain
		));
	}

	/**
	 * get quarantine contents for a domain
	 *
	 * @param $domain
	 * @param $from
	 * @param $to
	 * @param $searchFrom
	 * @param $searchTo
	 * @param $searchSubject
	 * @return object
	 * @throws Exception
	 */
	public function getQuarantine($domain, $from = null, $to = null, $searchFrom = null, $searchTo = null, $searchSubject = null)
	{
		return $this->apiRequest('get-quarantine', array(
			'domain' => $domain,
            'from' => $from,
            'to' => $to,
            'search_from' => $searchFrom,
            'search_to' => $searchTo,
            'search_subject' => $searchSubject,
		));
	}

    /**
     * get quarantine message for a domain and messageId
     *
     * @param $domain
     * @param $messageId
     * @return object
     * @throws Exception
     */
    public function getQuarantineMessage($domain, $messageId)
    {
        return $this->apiRequest('get-quarantine-message', array(
            'domain' => $domain,
            'message_id' => $messageId,
        ));
    }

	/**
	 * resend a quarantined message to the original recipient
	 *
	 * @param $domain
	 * @param $messageId
	 * @return object
	 * @throws Exception
	 */
	public function resendQuarantineMessage($domain, $messageId)
	{
		return $this->apiRequest('resend-quarantine-message', array(
			'domain' => $domain,
			'message_id' => $messageId
		));
	}

	/**
	 * delete a quarantined message
	 *
	 * @param $domain
	 * @param $messageId
	 * @return object
	 * @throws Exception
	 */
	public function deleteQuarantineMessage($domain, $messageId)
	{
		return $this->apiRequest('delete-quarantine-message', array(
			'domain' => $domain,
			'message_id' => $messageId
		));
	}

	/**
	 * get domain statistics
	 *
	 * @param $domain
	 * @param $from
	 * @param $to
	 * @param $interval
	 * @return object
	 * @throws Exception
	 */
	public function getDomainStatistics($domain, $from, $to, $interval = 'day')
	{
		return $this->apiRequest('get-domain-statistics', array(
			'domain' => $domain,
			'from' => $from,
			'to' => $to,
			'interval' => $interval
		));
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
