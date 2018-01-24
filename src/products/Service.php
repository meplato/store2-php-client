<?php namespace Meplato\Store2\Products;
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
 * @version 2.1.2
 * @license Copyright (c) 2015-2018 Meplato GmbH, Switzerland. All rights reserved.
 * @link https://developer.meplato.com/store2/#terms Terms of Service
 * @link https://developer.meplato.com/store2/ External documentation
 */
class Service
{
	/** @@var string API title */
	const TITLE = "Meplato Store API";
	/** @@var string API version */
	const VERSION = "2.1.2";
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

	function delete()
	{
		return new DeleteService($this);
	}

	function get()
	{
		return new GetService($this);
	}

	function replace()
	{
		return new ReplaceService($this);
	}

	function scroll()
	{
		return new ScrollService($this);
	}

	function search()
	{
		return new SearchService($this);
	}

	function update()
	{
		return new UpdateService($this);
	}

	function upsert()
	{
		return new UpsertService($this);
	}
}


/**
 * Create a new product in the given catalog and area.
 */
class CreateService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;
	private $product;

	/**
	 * Creates a new instance of CreateService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * Product properties of the new product.
	 *
	 * @param $product (array)
	 * @return $this so that the function is chainable
	 */
	function product($product)
	{
		$this->product = $product;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - kind (string): Kind describes this entity.
	 * - link (string): Link returns a URL to the representation of the newly created product.
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products";

		$body = json_encode($this->product);

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
 * Delete a product.
 */
class DeleteService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;
	private $spn;

	/**
	 * Creates a new instance of DeleteService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * SPN is the supplier part number of the product to delete.
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
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["area"] = $this->area;
		$params["pin"] = $this->pin;
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products/{spn}";

		$body = NULL;

		// Execute request
		$response = $this->service->getClient()->execute("delete", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return;
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}



/**
 * Get returns a single product by its Supplier Part Number (SPN).
 */
class GetService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;
	private $spn;

	/**
	 * Creates a new instance of GetService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * SPN is the supplier part number of the product to get.
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
	 * - asin (string): ASIN is the unique Amazon article number of the product.
	 * - autoConfigure (array): AutoConfigure is a flag that indicates whether this product can be configured automatically.
	 * - availability (array): Availability allows the update of product availability data, e.g. the number of items in stock or the date when the product will be available again. 
	 * - blobs (array): Blobs specifies external data, e.g. images or datasheets.
	 * - boostFactor (array): BoostFactor represents a positive or negative boost for the product.
	 * - bpn (string): BPN is the buyer part number of the product.
	 * - catalogId (int64): ID of the catalog this products belongs to.
	 * - catalogManaged (boolean): CatalogManaged is a flag that indicates whether this product is configurable (or catalog managed in OCI parlance).
	 * - categories (array): Categories is a list of (supplier-specific) category names the product belongs to.
	 * - conditions (array): Conditions describes the product conditions, e.g. refurbished or used.
	 * - contract (string): Contract represents the contract number to be used when purchasing this product.
	 * - contractItem (string): ContractItem represents line number in the contract to be used when purchasing this product. See also Contract.
	 * - conversionDenumerator (array): ConversionDenumerator is the denumerator for calculating price quantities.
	 * - conversionNumerator (array): ConversionNumerator is the numerator for calculating price quantities.
	 * - created (array): Created is the creation date and time of the product.
	 * - cu (string): ContentUnit is the content unit of the product, a 3-character ISO code (usually project-specific).
	 * - cuPerOu (float64): CuPerOu describes the number of content units per order unit, e.g. the 12 in '1 case contains 12 bottles'.
	 * - currency (string): Currency is the ISO currency code for the prices, e.g. EUR or GBP.
	 * - custField1 (string): CustField1 is the CUST_FIELD1 of the SAP OCI specification. It has a maximum length of 10 characters. 
	 * - custField2 (string): CustField2 is the CUST_FIELD2 of the SAP OCI specification. It has a maximum length of 10 characters. 
	 * - custField3 (string): CustField3 is the CUST_FIELD3 of the SAP OCI specification. It has a maximum length of 10 characters. 
	 * - custField4 (string): CustField4 is the CUST_FIELD4 of the SAP OCI specification. It has a maximum length of 20 characters. 
	 * - custField5 (string): CustField5 is the CUST_FIELD5 of the SAP OCI specification. It has a maximum length of 50 characters. 
	 * - custFields (array): CustFields is an array of generic name/value pairs for customer-specific attributes.
	 * - customField10 (string): CustomField10 represents the 10th customer-specific field.
	 * - customField11 (string): CustomField11 represents the 11th customer-specific field.
	 * - customField12 (string): CustomField12 represents the 12th customer-specific field.
	 * - customField13 (string): CustomField13 represents the 13th customer-specific field.
	 * - customField14 (string): CustomField14 represents the 14th customer-specific field.
	 * - customField15 (string): CustomField15 represents the 15th customer-specific field.
	 * - customField16 (string): CustomField16 represents the 16th customer-specific field.
	 * - customField17 (string): CustomField17 represents the 17th customer-specific field.
	 * - customField18 (string): CustomField18 represents the 18th customer-specific field.
	 * - customField19 (string): CustomField19 represents the 19th customer-specific field.
	 * - customField20 (string): CustomField20 represents the 20th customer-specific field.
	 * - customField21 (string): CustomField21 represents the 21st customer-specific field.
	 * - customField22 (string): CustomField22 represents the 22nd customer-specific field.
	 * - customField23 (string): CustomField23 represents the 23rd customer-specific field.
	 * - customField24 (string): CustomField24 represents the 24th customer-specific field.
	 * - customField25 (string): CustomField25 represents the 25th customer-specific field.
	 * - customField26 (string): CustomField26 represents the 26th customer-specific field.
	 * - customField27 (string): CustomField27 represents the 27th customer-specific field.
	 * - customField28 (string): CustomField28 represents the 28th customer-specific field.
	 * - customField29 (string): CustomField29 represents the 29th customer-specific field.
	 * - customField30 (string): CustomField30 represents the 30th customer-specific field.
	 * - customField6 (string): CustomField6 represents the 6th customer-specific field.
	 * - customField7 (string): CustomField7 represents the 7th customer-specific field.
	 * - customField8 (string): CustomField8 represents the 8th customer-specific field.
	 * - customField9 (string): CustomField9 represents the 9th customer-specific field.
	 * - datasheet (string): Datasheet is the name of an datasheet file (in the media files) or a URL to the datasheet on the internet.
	 * - datasheetURL (string): DatasheetURL is the URL to the data sheet (if available).
	 * - description (string): Description of the product.
	 * - eclasses (array): Eclasses is a list of eCl@ss categories the product belongs to.
	 * - erpGroupSupplier (string): erpGroupSupplier is the material group of the product on the merchant-/supplier-side.
	 * - excluded (boolean): Excluded is a flag that indicates whether to exclude this product from the catalog. If true, this product will not be published into the live area.
	 * - extCategory (string): ExtCategory is the EXT_CATEGORY field of the SAP OCI specification.
	 * - extCategoryId (string): ExtCategoryID is the EXT_CATEGORY_ID field of the SAP OCI specification.
	 * - extConfigForm (string): ExtConfigForm represents information required to make the product configurable.
	 * - extConfigService (string): ExtConfigService represents additional information required to make the product configurable. See also ExtConfigForm.
	 * - extProductId (string): ExtProductID is the EXT_PRODUCT_ID field of the SAP OCI specification. It is e.g. required for configurable/catalog managed products.
	 * - extSchemaType (string): ExtSchemaType is the EXT_SCHEMA_TYPE field of the SAP OCI specification.
	 * - features (array): Features defines product features, i.e. additional properties of the product.
	 * - glAccount (string): GLAccount represents the GL account number to use for this product.
	 * - gtin (string): GTIN is the global trade item number of the product (used to be EAN).
	 * - hazmats (array): Hazmats classifies hazardous/dangerous goods.
	 * - id (string): ID is a unique (internal) identifier of the product.
	 * - image (string): Image is the name of an image file (in the media files) or a URL to the image on the internet.
	 * - imageURL (string): ImageURL is the URL to the image.
	 * - incomplete (array): Incomplete is a flag that indicates whether this product is incomplete.
	 * - isPassword (array): IsPassword is a flag that indicates whether this product will be used to purchase a password, e.g. for a software product.
	 * - keepPrice (array): KeepPrice is a flag that indicates whether the price of the product will or will not be calculated by the catalog.
	 * - keywords (array): Keywords is a list of aliases for the product.
	 * - kind (string): Kind is store#product for a product entity.
	 * - leadtime (array): Leadtime is the number of days for delivery.
	 * - listPrice (float64): ListPrice is the net list price of the product.
	 * - manufactcode (string): Manufactcode is the manufacturer code as used in the SAP OCI specification.
	 * - manufacturer (string): Manufacturer is the name of the manufacturer.
	 * - matgroup (string): Matgroup is the material group of the product on the buy-side.
	 * - meplatoPrice (float64): MeplatoPrice is the Meplato price of the product.
	 * - merchantId (int64): ID of the merchant.
	 * - mpn (string): MPN is the manufacturer part number.
	 * - multiSupplierId (string): MultiSupplierID represents an optional field for the unique identifier of a supplier in a multi-supplier catalog.
	 * - multiSupplierName (string): MultiSupplierName represents an optional field for the name of the supplier in a multi-supplier catalog.
	 * - name (string): Name of the product.
	 * - needsGoodsReceipt (array): NeedsGoodsReceipt is a flag that indicates whether this product requires a goods receipt process.
	 * - nfBasePrice (array): NFBasePrice represents a part for calculating metal surcharges.
	 * - nfBasePriceQuantity (array): NFBasePriceQuantity represents a part for calculating metal surcharges.
	 * - nfCndId (string): NFCndID represents the key to calculate metal surcharges.
	 * - nfScale (array): NFScale represents a part for calculating metal surcharges.
	 * - nfScaleQuantity (array): NFScaleQuantity represents a part for calculating metal surcharges.
	 * - orderable (array): Orderable is a flag that indicates whether this product will be orderable to the end-user when shopping.
	 * - ou (string): OrderUnit is the order unit of the product, a 3-character ISO code (usually project-specific).
	 * - price (float64): Price is the net price (per order unit) of the product for the end-user.
	 * - priceFormula (string): PriceFormula represents the formula to calculate the price of the product.
	 * - priceQty (float64): PriceQty is the quantity for which the price is specified (default: 1.0).
	 * - projectId (int64): ID of the project.
	 * - quantityInterval (array): QuantityInterval is the interval in which this product can be ordered. E.g. if the quantity interval is 5, the end-user can only order in quantities of 5,10,15 etc. 
	 * - quantityMax (array): QuantityMax is the maximum order quantity for this product.
	 * - quantityMin (array): QuantityMin is the minimum order quantity for this product.
	 * - rateable (array): Rateable is a flag that indicates whether the product can be rated by end-users.
	 * - rateableOnlyIfOrdered (array): RateableOnlyIfOrdered is a flag that indicates whether the product can be rated only after being ordered.
	 * - references (array): References defines cross-product references, e.g. alternatives or follow-up products.
	 * - safetysheet (string): Safetysheet is the name of an safetysheet file (in the media files) or a URL to the safetysheet on the internet.
	 * - safetysheetURL (string): SafetysheetURL is the URL to the safety data sheet (if available).
	 * - scalePrices (array): ScalePrices can be used when the price of the product is dependent on the ordered quantity.
	 * - selfLink (string): URL to this page.
	 * - service (boolean): Service indicates if the is a good (false) or a service (true). The default value is false.
	 * - spn (string): SPN is the supplier part number.
	 * - taxCode (string): TaxCode to use for this product.
	 * - taxRate (float64): TaxRate for this product, a numeric value between 0.0 and 1.0.
	 * - thumbnail (string): Thumbnail is the name of an thumbnail image file (in the media files) or a URL to the image on the internet.
	 * - thumbnailURL (string): ThubmnailURL is the URL to the thumbnail image.
	 * - unspscs (array): Unspscs is a list of UNSPSC categories the product belongs to.
	 * - updated (array): Updated is the last modification date and time of the product.
	 * - visible (array): Visible is a flag that indicates whether this product will be visible to the end-user when shopping.
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products/{spn}";

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
 * Replace all fields of a product. Use Update to update only certain fields of
 * a product.
 */
class ReplaceService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;
	private $spn;
	private $product;

	/**
	 * Creates a new instance of ReplaceService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * New properties of the product.
	 *
	 * @param $product (array)
	 * @return $this so that the function is chainable
	 */
	function product($product)
	{
		$this->product = $product;
		return $this;
	}

	/**
	 * SPN is the supplier part number of the product to replace.
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
	 * - kind (string): Kind describes this entity.
	 * - link (string): Link returns a URL to the representation of the replaced product.
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products/{spn}";

		$body = json_encode($this->product);

		// Execute request
		$response = $this->service->getClient()->execute("put", $urlTemplate, $params, $headers, $body);
		$status = $response->getStatusCode();
		if ($status >= 200 && $status <= 299) {
			return $response->getBodyJSON();
		}
		throw new \Meplato\Store2\ServiceException($response);
	}
}



/**
 * Scroll through products of a catalog (area). If you need to iterate through
 * all products in a catalog, this is the most effective way to do so. If you
 * want to search for products, use the Search endpoint. 
 */
class ScrollService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;

	/**
	 * Creates a new instance of ScrollService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * PageToken must be passed in the 2nd and all consective requests to get the
	 * next page of results. You do not need to pass the page token manually. You
	 * should just follow the nextUrl link in the metadata to get the next slice of
	 * data. If there is no nextUrl returned, you have reached the last page of
	 * products for the given catalog. A scroll request is kept alive for 2 minutes.
	 * If you fail to ask for the next slice of products within this period, a new
	 * scroll request will be created and you start over at the first page of
	 * results. 
	 *
	 * @param $pageToken (string)
	 * @return $this so that the function is chainable
	 */
	function pageToken($pageToken)
	{
		$this->opt["pageToken"] = $pageToken;
		return $this;
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
	 * - items (array): Items is the slice of products of this result.
	 * - kind (string): Kind is store#products/scroll for this kind of response.
	 * - nextLink (string): NextLink returns the URL to the next slice of products (if any).
	 * - pageToken (string): PageToken needs to be passed to get the next slice of products. It is blank if there are no more products. Instead of using pageToken for your next request, you can also follow nextLink.
	 * - previousLink (string): PreviousLink returns the URL of the previous slice of products (if any).
	 * - selfLink (string): SelfLink returns the URL to this page.
	 * - totalItems (int64): TotalItems describes the total number of products found.
	 *
	 * @return array Deserialized JSON object
	 * @throws \Meplato\Store2\ServiceException if something goes wrong
	 */
	function execute()
	{
		// Parameters (in template and query string)
		$params = [];
		$params["area"] = $this->area;
		if (array_key_exists("pageToken", $this->opt)) {
			$params["pageToken"] = $this->opt["pageToken"];
		}
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products/scroll{?pageToken}";

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
 * Search for products. Do not use this method for iterating through all of the
 * products in a catalog; use the Scroll endpoint instead. It is much more
 * efficient. 
 */
class SearchService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;

	/**
	 * Creates a new instance of SearchService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * Skip specifies how many products to skip (default 0).
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
	 * Sort order, e.g. name, spn, id or -created (default: score).
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
	 * Take defines how many products to return (max 100, default 20).
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
	 * - items (array): Items is the slice of products of this result.
	 * - kind (string): Kind is store#products/search for this kind of response.
	 * - nextLink (string): NextLink returns the URL to the next slice of products (if any).
	 * - previousLink (string): PreviousLink returns the URL of the previous slice of products (if any).
	 * - selfLink (string): SelfLink returns the URL to this page.
	 * - totalItems (int64): TotalItems describes the total number of products found.
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products{?q,skip,take,sort}";

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
 * Update the fields of a product selectively. Use Replace to replace the
 * product as a whole.
 */
class UpdateService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;
	private $spn;
	private $product;

	/**
	 * Creates a new instance of UpdateService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * Products properties of the updated product.
	 *
	 * @param $product (array)
	 * @return $this so that the function is chainable
	 */
	function product($product)
	{
		$this->product = $product;
		return $this;
	}

	/**
	 * SPN is the supplier part number of the product to update.
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
	 * - kind (string): Kind describes this entity.
	 * - link (string): Link returns a URL to the representation of the updated product.
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products/{spn}";

		$body = json_encode($this->product);

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
 * Upsert a product in the given catalog and area. Upsert will create if the
 * product does not exist yet, otherwise it will update.
 */
class UpsertService
{
	private $service;
	private $opt = [];
	private $hdr = [];
	private $pin;
	private $area;
	private $product;

	/**
	 * Creates a new instance of UpsertService.
	 */
	function __construct($service)
	{
		$this->service = $service;
	}

	/**
	 * Area of the catalog, e.g. work or live.
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
	 * Product properties of the new product.
	 *
	 * @param $product (array)
	 * @return $this so that the function is chainable
	 */
	function product($product)
	{
		$this->product = $product;
		return $this;
	}

	/**
	 * Execute the service call.
	 *
	 * The return values has the following properties:
	 * - kind (string): Kind describes this entity.
	 * - link (string): Link returns a URL to the representation of the created or updated product.
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

		$urlTemplate = $this->service->getBaseURL() . "/catalogs/{pin}/{area}/products/upsert";

		$body = json_encode($this->product);

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
