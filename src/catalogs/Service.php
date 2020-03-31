<?php namespace Meplato\Store2\Catalogs;
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
 * @version 2.1.6
 * @license Copyright (c) 2015-2020 Meplato GmbH. All rights reserved.
 * @link https://developer.meplato.com/store2/#terms Terms of Service
 * @link https://developer.meplato.com/store2/ External documentation
 */
class Service
{
	/** @@var string API title */
	const TITLE = "Meplato Store API";
	/** @@var string API version */
	const VERSION = "2.1.6";
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

	function create()
	{
		return new CreateService($this);
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

	function purge()
	{
		return new PurgeService($this);
	}

	function search()
	{
		return new SearchService($this);
	}
}


/**
 * Create a new catalog (admin only).
 */
class CreateService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $catalog;

	/**
	 * Creates a new instance of CreateService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Catalog properties of the new catalog.
	 *
	 * @param $catalog (array)
	 * @return $this so that the function is chainable
	 */
	function catalog($catalog)
	{
		$this->catalog = $catalog;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - country (string): Country is the ISO-3166 alpha-2 code for the country that the catalog is destined for (e.g. DE or US).
	 * - created (array): Created is the creation date and time of the catalog.
	 * - currency (string): Currency is the ISO-4217 currency code that is used for all products in the catalog (e.g. EUR or USD).
	 * - custFields (array): CustFields is an array of generic name/value pairs for customer-specific attributes.
	 * - description (string): Description of the catalog.
	 * - downloadChecksum (string): DownloadChecksum represents the checksum of the catalog last downloaded.
	 * - downloadInterval (string): DownloadInterval represents the interval to use for checking new versions of a catalog at the DownloadURL.
	 * - downloadUrl (string): DownloadURL represents a URL which is periodically downloaded and imported as a new catalog.
	 * - erpNumberBuyer (string): ERPNumberBuyer is the number of the merchant of this catalog in the SAP/ERP system of the buyer.
	 * - expired (boolean): Expired indicates whether the catalog is expired as of now.
	 * - hubUrl (string): HubURL represents the Meplato Hub URL for this catalog, e.g. https://hub.meplato.de/forward/12345/shop
	 * - id (int64): ID is a unique (internal) identifier of the catalog.
	 * - keepOriginalBlobs (boolean): KeepOriginalBlobs indicates whether the URLs in a blob will be passed through and not cached by Store.
	 * - kind (string): Kind is store#catalog for a catalog entity.
	 * - kpiSummary (array): KPISummary returns the outcome of analyzing the contents for key performance indicators.
	 * - language (string): Language is the IETF language tag of the language of all products in the catalog (e.g. de or pt-BR).
	 * - lastImported (array): LastImported is the date and time the catalog was last imported.
	 * - lastPublished (array): LastPublished is the date and time the catalog was last published.
	 * - lockedForDownload (boolean): LockedForDownload indicates whether a catalog is locked and cannot be downloaded.
	 * - merchantId (int64): ID of the merchant.
	 * - merchantMpcc (string): MPCC of the merchant.
	 * - merchantMpsc (string): MPSC of the merchant.
	 * - merchantName (string): Name of the merchant.
	 * - name (string): Name of the catalog.
	 * - numProductsLive (array): Number of products currently in the live area (only returned when getting the details of a catalog).
	 * - numProductsWork (array): Number of products currently in the work area (only returned when getting the details of a catalog).
	 * - ociUrl (string): OciURL represents the OCI punchout URL that the supplier specified for this catalog, e.g. https://my-shop.com/oci?param1=a
	 * - pin (string): PIN of the catalog.
	 * - project (array): Project references the project that this catalog belongs to.
	 * - projectId (int64): ID of the project.
	 * - projectMpbc (string): MPBC of the project.
	 * - projectMpcc (string): MPCC of the project.
	 * - projectName (string): Name of the project.
	 * - publishedVersion (array): PublishedVersion is the version number of the published catalog. It is incremented when the publish task publishes the catalog.
	 * - sageContract (string): SageContract represents the internal identifier at Meplato for the contract of this catalog.
	 * - sageNumber (string): SageNumber represents the internal identifier at Meplato for the merchant of this catalog.
	 * - selfLink (string): URL to this page.
	 * - slug (string): Slug of the catalog.
	 * - state (string): State describes the current state of the catalog, e.g. idle.
	 * - supportsOciBackgroundsearch (boolean): SupportsOciBackgroundsearch indicates whether a catalog supports the OCI BACKGROUNDSEARCH transaction.
	 * - supportsOciDetail (boolean): SupportsOciDetail indicates whether a catalog supports the OCI DETAIL transaction.
	 * - supportsOciDetailadd (boolean): SupportsOciDetailadd indicates whether a catalog supports the OCI DETAILADD transaction.
	 * - supportsOciDownloadjson (boolean): SupportsOciDownloadjson indicates whether a catalog supports the OCI DOWNLOADJSON transaction.
	 * - supportsOciQuantitycheck (boolean): SupportsOciQuantitycheck indicates whether a catalog supports the OCI QUANTITYCHECK transaction.
	 * - supportsOciSourcing (boolean): SupportsOciSourcing indicates whether a catalog supports the OCI SOURCING transaction.
	 * - supportsOciValidate (boolean): SupportsOciValidate indicates whether a catalog supports the OCI VALIDATE transaction.
	 * - target (string): Target represents the target system which can be either an empty string, "catscout" or "mall".
	 * - type (string): Type of catalog, e.g. corporate or basic.
	 * - updated (array): Updated is the last modification date and time of the catalog.
	 * - validFrom (array): ValidFrom is the date the catalog becomes effective (YYYY-MM-DD).
	 * - validUntil (array): ValidUntil is the date the catalog expires (YYYY-MM-DD).
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs";

		$body = json_encode($this->catalog);

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
	 * - country (string): Country is the ISO-3166 alpha-2 code for the country that the catalog is destined for (e.g. DE or US).
	 * - created (array): Created is the creation date and time of the catalog.
	 * - currency (string): Currency is the ISO-4217 currency code that is used for all products in the catalog (e.g. EUR or USD).
	 * - custFields (array): CustFields is an array of generic name/value pairs for customer-specific attributes.
	 * - description (string): Description of the catalog.
	 * - downloadChecksum (string): DownloadChecksum represents the checksum of the catalog last downloaded.
	 * - downloadInterval (string): DownloadInterval represents the interval to use for checking new versions of a catalog at the DownloadURL.
	 * - downloadUrl (string): DownloadURL represents a URL which is periodically downloaded and imported as a new catalog.
	 * - erpNumberBuyer (string): ERPNumberBuyer is the number of the merchant of this catalog in the SAP/ERP system of the buyer.
	 * - expired (boolean): Expired indicates whether the catalog is expired as of now.
	 * - hubUrl (string): HubURL represents the Meplato Hub URL for this catalog, e.g. https://hub.meplato.de/forward/12345/shop
	 * - id (int64): ID is a unique (internal) identifier of the catalog.
	 * - keepOriginalBlobs (boolean): KeepOriginalBlobs indicates whether the URLs in a blob will be passed through and not cached by Store.
	 * - kind (string): Kind is store#catalog for a catalog entity.
	 * - kpiSummary (array): KPISummary returns the outcome of analyzing the contents for key performance indicators.
	 * - language (string): Language is the IETF language tag of the language of all products in the catalog (e.g. de or pt-BR).
	 * - lastImported (array): LastImported is the date and time the catalog was last imported.
	 * - lastPublished (array): LastPublished is the date and time the catalog was last published.
	 * - lockedForDownload (boolean): LockedForDownload indicates whether a catalog is locked and cannot be downloaded.
	 * - merchantId (int64): ID of the merchant.
	 * - merchantMpcc (string): MPCC of the merchant.
	 * - merchantMpsc (string): MPSC of the merchant.
	 * - merchantName (string): Name of the merchant.
	 * - name (string): Name of the catalog.
	 * - numProductsLive (array): Number of products currently in the live area (only returned when getting the details of a catalog).
	 * - numProductsWork (array): Number of products currently in the work area (only returned when getting the details of a catalog).
	 * - ociUrl (string): OciURL represents the OCI punchout URL that the supplier specified for this catalog, e.g. https://my-shop.com/oci?param1=a
	 * - pin (string): PIN of the catalog.
	 * - project (array): Project references the project that this catalog belongs to.
	 * - projectId (int64): ID of the project.
	 * - projectMpbc (string): MPBC of the project.
	 * - projectMpcc (string): MPCC of the project.
	 * - projectName (string): Name of the project.
	 * - publishedVersion (array): PublishedVersion is the version number of the published catalog. It is incremented when the publish task publishes the catalog.
	 * - sageContract (string): SageContract represents the internal identifier at Meplato for the contract of this catalog.
	 * - sageNumber (string): SageNumber represents the internal identifier at Meplato for the merchant of this catalog.
	 * - selfLink (string): URL to this page.
	 * - slug (string): Slug of the catalog.
	 * - state (string): State describes the current state of the catalog, e.g. idle.
	 * - supportsOciBackgroundsearch (boolean): SupportsOciBackgroundsearch indicates whether a catalog supports the OCI BACKGROUNDSEARCH transaction.
	 * - supportsOciDetail (boolean): SupportsOciDetail indicates whether a catalog supports the OCI DETAIL transaction.
	 * - supportsOciDetailadd (boolean): SupportsOciDetailadd indicates whether a catalog supports the OCI DETAILADD transaction.
	 * - supportsOciDownloadjson (boolean): SupportsOciDownloadjson indicates whether a catalog supports the OCI DOWNLOADJSON transaction.
	 * - supportsOciQuantitycheck (boolean): SupportsOciQuantitycheck indicates whether a catalog supports the OCI QUANTITYCHECK transaction.
	 * - supportsOciSourcing (boolean): SupportsOciSourcing indicates whether a catalog supports the OCI SOURCING transaction.
	 * - supportsOciValidate (boolean): SupportsOciValidate indicates whether a catalog supports the OCI VALIDATE transaction.
	 * - target (string): Target represents the target system which can be either an empty string, "catscout" or "mall".
	 * - type (string): Type of catalog, e.g. corporate or basic.
	 * - updated (array): Updated is the last modification date and time of the catalog.
	 * - validFrom (array): ValidFrom is the date the catalog becomes effective (YYYY-MM-DD).
	 * - validUntil (array): ValidUntil is the date the catalog expires (YYYY-MM-DD).
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
 * Purge the work or live area of a catalog, i.e. remove all products in the
 * given area, but do not delete the catalog itself.
 */
class PurgeService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;

	/**
	 * Creates a new instance of PurgeService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog to purge, i.e. work or live.
	 *
	 * @param $area (string)
	 * @return $this so that the function is chainable
	 */
	function area($area)
	{
		$this->area = $area;
		return $this;
	}

	/**
	 * PIN of the catalog to purge.
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
	 * - kind (string): Kind is store#catalogPurge for this kind of response.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["area"] = $this->area;
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}";

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
	 * Q defines are full text query.
	 *
	 * @param $q (string)
	 * @return $this so that the function is chainable
	 */
	function q($q)
	{
		$this->opt["q"] = $q;
		return $this;
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
	 * Sort order, e.g. name or id or -created (default: score).
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
		if (array_key_exists("q", $this->opt)) {
			$params["q"] = $this->opt["q"];
		}
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs{?q,skip,take,sort}";

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
