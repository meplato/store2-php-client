<?php namespace Meplato\Store2;
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

/**
 * Basic HTTP client that utilizes the Guzzle PHP library.
 */
class HttpClient implements HttpClientInterface
{
  /** @var \GuzzleHttp\Client HTTP client for requests to the server */
  private $client;

  /**
   * Create a new HTTP client.
   */
  public function __construct()
  {
	$this->client = new \GuzzleHttp\Client();
  }

  /**
   * Set the Guzzle HTTP client manually (e.g. for testing).
   */
  public function setClient($client) {
	$this->client = $client;
  }

  /**
   * Execute a HTTP request and return a HttpResponse.
   *
   * @param string $method HTTP method to use, e.g. GET, HEAD, POST, PUT, DELETE.
   * @param string $urlTemplate A URI template according to RFC6570 (NOT a URL!).
   * @param array $params Parameters for the URI template and the query string.
   * @param array $headers HTTP header to send.
   * @param $body An object to use as the body, e.g. a string.
   *
   * @return HttpResonse
   *
   * @link http://tools.ietf.org/html/rfc6570
   */
  public function execute($method, $urlTemplate, $params = [], $headers = [], $body = NULL)
  {
	$options = [
	  "headers"    => $headers,
	  "body"       => $body,
	  "exceptions" => false // Do not raise exceptions on error
	];

	// Guzzle has a uriTemplate method to make URL from template and params:
	// https://github.com/guzzle/guzzle/blob/master/src/Utils.php#L93
	$url = \GuzzleHttp\Utils::uriTemplate($urlTemplate, $params);

	$request = $this->client->createRequest($method, $url, $options);
	$response = $this->client->send($request);
	return new HttpResponse($response);
  }
}

/**
 * A HTTP Response that will be returned from the execute
 * method of a HttpClientInterface.
 */
class HttpResponse implements HttpResponseInterface
{
  /** @var HTTP response from Guzzle. */
  private $response;

  /**
   * Constructs a new HTTP response.
   *
   * @param $response A HTTP response from Guzzle.
   */
  public function __construct($response)
  {
	$this->response = $response;
  }

  /**
   * Returns the HTTP status code of the response.
   *
   * @return int
   */
  public function getStatusCode()
  {
	return $this->response->getStatusCode();
  }

  /**
   * Returns the body of the HTTP response.
   */
  public function getBody()
  {
	return $this->response->getBody();
  }

  /**
   * Returns the body of the HTTP response, interpreted as JSON.
   * If there is a parsing error, an empty array is returned.
   *
   * @return array
   */
  public function getBodyJSON()
  {
	try {
	  return $this->response->json();
	} catch(Exception $e) {
	  return [];
	}
  }
}
?>
