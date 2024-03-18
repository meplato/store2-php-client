<?php namespace Meplato\Store2\Availabilities;
// Copyright (c) 2013-present Meplato GmbH.
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
 * @copyright 2013-present Meplato GmbH.
 * @author Meplato API Team <support@meplato.com>
 * @version 2.2.0
 * @license Copyright (c) 2015-2023 Meplato GmbH. All rights reserved.
 * @link https://developer.meplato.com/store2/#terms Terms of Service
 * @link https://developer.meplato.com/store2/ External documentation
 */
class Service
{
	/** @@var string API title */
	const TITLE = "Meplato Store API";
	/** @@var string API version */
	const VERSION = "2.2.0";
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

	function delete()
	{
		return new DeleteService($this);
	}

	function get()
	{
		return new GetService($this);
	}

	function upsert()
	{
		return new UpsertService($this);
	}
}


/**
 * Delete availability information of a product. It is an asynchronous
 * operation.
 */
class DeleteService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $spn;

	/**
	 * Creates a new instance of DeleteService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * 2-letter ISO code of the country/region where the product is stored
	 *
	 * @param $region (string)
	 * @return $this so that the function is chainable
	 */
	function region($region)
	{
		$this->opt["region"] = $region;
		return $this;
	}

	/**
	 * SPN is the unique identifier of a product within a merchant.
	 *
	 * @param $spn (string)
	 * @return $this so that the function is chainable
	 */
	function spn($spn)
	{
		$this->spn = $spn;
		return $this;
	}

	/**
	 * Zip code where the product is stored
	 *
	 * @param $zipCode (string)
	 * @return $this so that the function is chainable
	 */
	function zipCode($zipCode)
	{
		$this->opt["zipCode"] = $zipCode;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - kind (string): Kind describes this entity, it will be store#availability/deleteResponse.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		if (array_key_exists("region", $this->opt)) {
			$params["region"] = $this->opt["region"];
		}
		$params["spn"] = $this->spn;
		if (array_key_exists("zipCode", $this->opt)) {
			$params["zipCode"] = $this->opt["zipCode"];
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

		$urlTemplate = $this->service->getBaseURL() . "/products/{spn}/availabilities{?region,zipCode}";

		$body = NULL;

		// Execute request
		$response = $this->service->getClient()->execute("delete", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return $response->getBodyJSON();
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}



/**
 * Read availability information of a product
 */
class GetService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $spn;

	/**
	 * Creates a new instance of GetService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * 2-letter ISO code of the country/region where the product is stored
	 *
	 * @param $region (string)
	 * @return $this so that the function is chainable
	 */
	function region($region)
	{
		$this->opt["region"] = $region;
		return $this;
	}

	/**
	 * SPN is the unique identifier of a product within a merchant.
	 *
	 * @param $spn (string)
	 * @return $this so that the function is chainable
	 */
	function spn($spn)
	{
		$this->spn = $spn;
		return $this;
	}

	/**
	 * Zip code where the product is stored
	 *
	 * @param $zipCode (string)
	 * @return $this so that the function is chainable
	 */
	function zipCode($zipCode)
	{
		$this->opt["zipCode"] = $zipCode;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - items (array): Collection of availability information associated with an SPN for a merchant.
	 * - kind (string): Kind is store#availability/getResponse for this kind of response.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		if (array_key_exists("region", $this->opt)) {
			$params["region"] = $this->opt["region"];
		}
		$params["spn"] = $this->spn;
		if (array_key_exists("zipCode", $this->opt)) {
			$params["zipCode"] = $this->opt["zipCode"];
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

		$urlTemplate = $this->service->getBaseURL() . "/products/{spn}/availabilities{?region,zipCode}";

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
 * Update or create availability information of a product. It is an asynchronous
 * operation.
 */
class UpsertService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $spn;
	private $availability;

	/**
	 * Creates a new instance of UpsertService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Availability properties of the product.
	 *
	 * @param $availability (array)
	 * @return $this so that the function is chainable
	 */
	function availability($availability)
	{
		$this->availability = $availability;
		return $this;
	}

	/**
	 * SPN is the unique identifier of a product within a merchant.
	 *
	 * @param $spn (string)
	 * @return $this so that the function is chainable
	 */
	function spn($spn)
	{
		$this->spn = $spn;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - kind (string): Kind describes this entity, it will be store#availability/upsertResponse.
	 * - link (string): Link includes the URL where this resource will be available
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["spn"] = $this->spn;

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

		$urlTemplate = $this->service->getBaseURL() . "/products/{spn}/availabilities";

		$body = json_encode($this->availability);

		// Execute request
		$response = $this->service->getClient()->execute("post", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return $response->getBodyJSON();
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}


?>
