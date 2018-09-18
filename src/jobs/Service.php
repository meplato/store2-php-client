<?php namespace Meplato\Store2\Jobs;
// Copyright (c) 2015-2016 Meplato GmbH, Switzerland.
//
// Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
// in compliance with the License. You may obtain a copy of the License at
//
// http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software distributed under the License
// is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
// or implied. See the License for the specific language governing permissions and limitations under
// the License.

use Meplato\Store2;

/**
 * Service is the entry-point to the Meplato Store API.
 *
 * @copyright 2014-2018 Meplato GmbH, Switzerland.
 * @author Meplato API Team <support@meplato.com>
 * @version 2.1.4
 * @license Copyright (c) 2015-2018 Meplato GmbH, Switzerland. All rights reserved.
 * @link https://developer.meplato.com/store2/#terms Terms of Service
 * @link https://developer.meplato.com/store2/ External documentation
 */
class Service
{
	/** @@var string API title */
	const TITLE = "Meplato Store API";
	/** @@var string API version */
	const VERSION = "2.1.4";
	/** @@var string Base URL of the service, including the path */
	const BASE_URL = "https://store.meplato.com/api/v2";
	/** @@var string User Agent string that will be sent to the server */
	const USER_AGENT = "meplato-php-client/2.0";

	/** @var HttpClientInterface Interface for making HTTP requests */
	private $client;
	/** @var string Base URL for the API, including the path (default: self::BASE_URL) */
	private $baseURL;
	/** @var string User name part of the credentials */
	private $user;
	/** @var string Password */
	private $password;

	function __construct($client)
	{
		$this->client = $client;
		$this->baseURL = self::BASE_URL;
	}

	function getClient()
	{
		return $this->client;
	}

	function getBaseURL()
	{
		return $this->baseURL;
	}

	function setBaseURL($baseURL)
	{
		$this->baseURL = $baseURL;
	}

	function getUser()
	{
		return $this->user;
	}

	function setUser($user)
	{
		$this->user = $user;
	}

	function getPassword()
	{
		return $this->password;
	}

	function setPassword($password)
	{
		$this->password = $password;
	}

	function get()
	{
		return new GetService($this);
	}

	function search()
	{
		return new SearchService($this);
	}
}


/**
 * Get a single job.
 */
class GetService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $id;

	/**
	 * Creates a new instance of GetService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * ID of the job.
	 *
	 * @param $id (string)
	 * @return $this so that the function is chainable
	 */
	function id($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - catalogId (int64): ID of the catalog.
	 * - catalogName (string): Name of the catalog.
	 * - completed (array): Completed is the date and time when the job has been completed, either successfully or failed.
	 * - created (array): Created is the creation date and time of the job.
	 * - email (string): Email of the user that initiated the job.
	 * - id (string): ID is a unique (internal) identifier of the job.
	 * - kind (string): Kind is store#job for a job entity.
	 * - merchantId (int64): ID of the merchant.
	 * - merchantMpcc (string): MPCC of the merchant.
	 * - merchantName (string): Name of the merchant.
	 * - selfLink (string): URL to this page.
	 * - started (array): Started is the date and time when the job has been started.
	 * - state (string): State describes the current state of the job, i.e. one of waiting,working,succeeded, or failed.
	 * - topic (string): Topic of the job, e.g. if it was an import or a validation task.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["id"] = $this->id;

		// HTTP Headers
		$headers = [
			"User-Agent"   => Service::USER_AGENT,
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];

		$user = $this->service->getUser();
		$pass = $this->service->getPassword();
		if (!empty($user) || !empty($pass)) {
			$credentials = base64_encode("{$user}:{$pass}");
			$headers["Authorization"] = "Basic {$credentials}";
		}

		$urlTemplate = $this->service->getBaseURL() . "/jobs/{id}";

		$body = NULL;

		// Execute request
		$response = $this->service->getClient()->execute("get", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return $response->getBodyJSON();
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}



/**
 * Search for jobs.
 */
class SearchService
{
	private $service;
	private $opt = [];
	private $hdr = [];

	/**
	 * Creates a new instance of SearchService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Skip specifies how many catalogs to skip (default 0).
	 *
	 * @param $skip (int64)
	 * @return $this so that the function is chainable
	 */
	function skip($skip)
	{
		$this->opt["skip"] = $skip;
		return $this;
	}

	/**
	 * State filter, e.g. waiting,working,succeeded,failed.
	 *
	 * @param $state (string)
	 * @return $this so that the function is chainable
	 */
	function state($state)
	{
		$this->opt["state"] = $state;
		return $this;
	}

	/**
	 * Take defines how many catalogs to return (max 100, default 20).
	 *
	 * @param $take (int64)
	 * @return $this so that the function is chainable
	 */
	function take($take)
	{
		$this->opt["take"] = $take;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - items (array): Items is the slice of jobs of this result.
	 * - kind (string): Kind is store#jobs for this kind of response.
	 * - nextLink (string): NextLink returns the URL to the next slice of jobs (if any).
	 * - previousLink (string): PreviousLink returns the URL of the previous slice of jobs (if any).
	 * - selfLink (string): SelfLink returns the URL to this page.
	 * - totalItems (int64): TotalItems describes the total number of jobs found.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		if (array_key_exists("skip", $this->opt)) {
			$params["skip"] = $this->opt["skip"];
		}
		if (array_key_exists("state", $this->opt)) {
			$params["state"] = $this->opt["state"];
		}
		if (array_key_exists("take", $this->opt)) {
			$params["take"] = $this->opt["take"];
		}

		// HTTP Headers
		$headers = [
			"User-Agent"   => Service::USER_AGENT,
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];

		$user = $this->service->getUser();
		$pass = $this->service->getPassword();
		if (!empty($user) || !empty($pass)) {
			$credentials = base64_encode("{$user}:{$pass}");
			$headers["Authorization"] = "Basic {$credentials}";
		}

		$urlTemplate = $this->service->getBaseURL() . "/jobs{?merchantId,skip,take,state}";

		$body = NULL;

		// Execute request
		$response = $this->service->getClient()->execute("get", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return $response->getBodyJSON();
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}


?>
