<?php namespace Meplato\Store2\Catalogs;
// Copyright (c) 2015 Meplato GmbH, Switzerland.
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
 * Service is the entry-point to the Meplato Store 2 API.
 *
 * @copyright 2014-2015 Meplato GmbH, Switzerland.
 * @author Meplato API Team <support@meplato.com>
 * @version 2.0.0.beta2
 * @license Copyright (c) 2015 Meplato GmbH, Switzerland. All rights reserved.
 * @link https://developer.meplato.com/store2/#terms Terms of Service
 * @link https://developer.meplato.com/store2/ External documentation
 */
class Service
{
	/** @@var string API title */
	const TITLE = "Meplato Store 2 API";
	/** @@var string API version */
	const VERSION = "2.0.0.beta2";
	/** @@var string Base URL of the service, including the path */
	const BASE_URL = "https://store2.meplato.com/api/v2";
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

	function publish()
	{
		return new PublishService($this);
	}

	function publishStatus()
	{
		return new PublishStatusService($this);
	}

	function search()
	{
		return new SearchService($this);
	}
}


/**
 * Get a single catalog.
 */
class GetService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;

	/**
	 * Creates a new instance of GetService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * PIN of the catalog.
	 *
	 * @param $pin (string)
	 * @return $this so that the function is chainable
	 */
	function pin($pin)
	{
		$this->pin = $pin;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - created (array): Created is the creation date and time of the catalog.
	 * - currency (string): Currency is the ISO-4217 currency code that is used for all products in the catalog.
	 * - description (string): Description of the catalog.
	 * - erpNumberBuyer (string): ERPNumberBuyer is the number of the merchant of this catalog in the SAP/ERP system of the buyer.
	 * - id (int64): ID is a unique (internal) identifier of the catalog.
	 * - kind (string): Kind is store#catalog for a catalog entity.
	 * - language (string): Language is the IETF language tag of the language of all products in the catalog.
	 * - lastImported (array): LastImported is the date and time the catalog was last imported.
	 * - lastPublished (array): LastPublished is the date and time the catalog was last published.
	 * - merchantId (int64): ID of the merchant.
	 * - merchantName (string): Name of the merchant.
	 * - name (string): Name of the catalog.
	 * - numProductsLive (array): Number of products currently in the live area (only returned when getting the details of a catalog).
	 * - numProductsWork (array): Number of products currently in the work area (only returned when getting the details of a catalog).
	 * - pin (string): PIN of the catalog.
	 * - projectId (int64): ID of the project.
	 * - publishedVersion (array): PublishedVersion is the version number of the published catalog. It is incremented when the publish task publishes the catalog.
	 * - selfLink (string): URL to this page.
	 * - slug (string): Slug of the catalog.
	 * - state (string): State describes the current state of the catalog, e.g. idle.
	 * - updated (array): Updated is the last modification date and time of the catalog.
	 * - validFrom (array): ValidFrom is the date the catalog becomes effective.
	 * - validUntil (array): ValidUntil is the date the catalog expires.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["pin"] = $this->pin;

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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}";

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
 * Publishes a catalog.
 */
class PublishService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;

	/**
	 * Creates a new instance of PublishService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * PIN of the catalog to publish.
	 *
	 * @param $pin (string)
	 * @return $this so that the function is chainable
	 */
	function pin($pin)
	{
		$this->pin = $pin;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - kind (string): Kind is store#catalogPublish for this kind of response.
	 * - selfLink (string): SelfLink returns the URL to this page.
	 * - statusLink (string): StatusLink returns the URL that returns the current status of the request.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["pin"] = $this->pin;

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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/publish";

		$body = NULL;

		// Execute request
		$response = $this->service->getClient()->execute("post", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return $response->getBodyJSON();
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}



/**
 * Status of a publish process.
 */
class PublishStatusService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;

	/**
	 * Creates a new instance of PublishStatusService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * PIN of the catalog to get the publish status from.
	 *
	 * @param $pin (string)
	 * @return $this so that the function is chainable
	 */
	function pin($pin)
	{
		$this->pin = $pin;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - busy (boolean): Busy indicates whether the catalog is still busy.
	 * - canceled (boolean): Canceled indicates whether the publishing process has been canceled.
	 * - currentStep (int64): CurrentStep is an indicator of the current step in the total list of steps. Use in combination with TotalSteps to retrieve the progress in percent.
	 * - done (boolean): Done indicates whether publishing is finished.
	 * - kind (string): Kind is store#catalogPublishStatus for this kind of response.
	 * - percent (int): Percent indicates the progress of the publish request.
	 * - selfLink (string): SelfLink returns the URL to this page.
	 * - status (string): Status describes the general status of the publish request.
	 * - totalSteps (int64): TotalSteps is an indicator of the total number steps required to complete the publish request. Use in combination with CurrentStep.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["pin"] = $this->pin;

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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/publish/status";

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
 * Search for catalogs.
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
	 * Sort order, e.g. name or id or -created (default: name).
	 *
	 * @param $sort (string)
	 * @return $this so that the function is chainable
	 */
	function sort($sort)
	{
		$this->opt["sort"] = $sort;
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
	 * - items (array): Items is the slice of catalogs of this result.
	 * - kind (string): Kind is store#catalogs for this kind of response.
	 * - nextLink (string): NextLink returns the URL to the next slice of catalogs (if any).
	 * - previousLink (string): PreviousLink returns the URL of the previous slice of catalogs (if any).
	 * - selfLink (string): SelfLink returns the URL to this page.
	 * - totalItems (int64): TotalItems describes the total number of catalogs found.
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
		if (array_key_exists("sort", $this->opt)) {
			$params["sort"] = $this->opt["sort"];
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs{?skip,take,sort}";

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
