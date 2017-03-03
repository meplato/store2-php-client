<?php namespace Meplato\Store2;
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

/**
 * Service is the entry-point to the Meplato Store API.
 *
 * @copyright 2014-2017 Meplato GmbH, Switzerland.
 * @author Meplato API Team <support@meplato.com>
 * @version 2.0.1
 * @license Copyright (c) 2015-2017 Meplato GmbH, Switzerland. All rights reserved.
 * @link https://developer.meplato.com/store2/#terms Terms of Service
 * @link https://developer.meplato.com/store2/ External documentation
 */
class Service
{
	/** @@var string API title */
	const TITLE = "Meplato Store API";
	/** @@var string API version */
	const VERSION = "2.0.1";
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

	function me()
	{
		return new MeService($this);
	}

	function ping()
	{
		return new PingService($this);
	}
}


/**
 * Me returns information about your user profile and the API endpoints of the
 * Meplato Store 2.0 API.
 */
class MeService
{
	private $service;
	private $opt = [];
	private $hdr = [];

	/**
	 * Creates a new instance of MeService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - catalogsLink (string): CatalogsLink is the URL for retrieving the list of catalogs.
	 * - kind (string): Kind is store#me for this entity.
	 * - merchant (array): Merchant returns information about your merchant account.
	 * - selfLink (string): SelfLink is the URL of this request.
	 * - user (array): User returns information about your user account.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];

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

		$urlTemplate = $this->service->getBaseURL() . "/";

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
 * Ping allows you to test if the Meplato Store 2.0 API is currently
 * operational.
 */
class PingService
{
	private $service;
	private $opt = [];
	private $hdr = [];

	/**
	 * Creates a new instance of PingService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Execute the service call.
	 *
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];

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

		$urlTemplate = $this->service->getBaseURL() . "/";

		$body = NULL;

		// Execute request
		$response = $this->service->getClient()->execute("head", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return;
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}


?>
