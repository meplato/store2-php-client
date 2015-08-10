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

use Meplato\Store2\HttpClient;
use Meplato\Store2\Service;
use Meplato\Store2\Tests\BaseTest;
use GuzzleHttp\Message\Response;

/**
 * Tests the Ping service.
 */
class PingTest extends BaseTest
{
	/**
	 * Checks when a ping is successful.
	 *
	 * @group root
	 * @group ping
	 */
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('ping.success');
		$response = $service->me()->execute();
	}

	/**
	 * Checks when a ping call is not authorized.
	 *
	 * @expectedException        Meplato\Store2\ServiceException
	 * @expectedExceptionMessage Service failed with status code 401
	 *
	 * @group root
	 * @group ping
	 */
	public function testUnauthorized()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('ping.unauthorized');
		//$this->setExpectedException('Meplato\Store2\ServiceException', 'Service failed with status code 401');
		$service->me()->execute();
	}

	/**
	 * Checks when ping returns with an internal server error.
	 *
	 * @expectedException        Meplato\Store2\ServiceException
	 * @expectedExceptionMessage Service failed with status code 500
	 *
	 * @group root
	 * @group ping
	 */
	public function testInternalError()
	{
		$service = $this->getService();
		$this->mockResponse(['status' => 500, 'headers' => [], 'body' => '']);
		//$this->setExpectedException('Meplato\Store2\ServiceException', 'Service failed with status code 500');
		$service->me()->execute();
	}
}
?>
