<?php namespace Meplato\Store2\Tests;
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

use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Message;

/**
 * Base class for all tests.
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
	/** Meplato Store 2 HTTP client. */
	private $httpClient;
	/** Guzzle HTTP client, used by $httpClient internally. */
	private $guzzleClient;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Meplato Store 2 HTTP client.
	 */
	public function getHttpClient()
	{
		if ($this->httpClient === null) {
			$this->httpClient = new \Meplato\Store2\HttpClient();
		}
		return $this->httpClient;
	}

	/**
	 * Returns the Service interface for Me and Ping services.
	 * Will be overridden by BaseTest in sub-directories.
	 */
	public function getService()
	{
		$client = $this->getHttpClient();
		$this->service = new \Meplato\Store2\Service($client);
		$this->service->setBaseURL("http://store2.go/api/v2");
		return $this->service;
	}

	/**
	 * Mocks a response.
	 *
	 * Example:
	 *
	 *     $this->mockResponse(['status' => 500, 'headers' => [], 'body' => '']);
	 */
	protected function mockResponse($response) {
		$mock = new MockHandler($response);
		$guzzleClient = new \GuzzleHttp\Client(['handler' => $mock]);
		$this->httpClient->setClient($guzzleClient);
	}

	/**
	 * Read a HTTP response from a file, then mocks it.
	 * Response files are read from ./tests/mock/responses.
	 *
	 * Example:
	 *
	 *     $this->mockResponseFromFile('ping.unauthorized');
	 */
	protected function mockResponseFromFile($file) {
		$contents = file_get_contents(__DIR__ . '/mock/responses/' . $file);

		$parser = new Message();
		$response = $parser->parseResponse($contents); //$this->fromMessage($contents);

		$mock = new MockHandler([$response]);
		$guzzleClient = new \GuzzleHttp\Client(['handler' => $mock]);
		$this->httpClient->setClient($guzzleClient);
	}

	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}
}
?>
