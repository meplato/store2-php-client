<?php namespace Meplato\Store2\Availabilities\Tests;
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
use Meplato\Store2\Availabilities\Service;

/**
 * Tests retrieving of availabilities.
 */
class GetTest extends BaseTest
{
	/**
	 * Tests a successful call to retrieve availabilities.
	 *
	 * @group availabilities
	 * @group availabilities.get
	 */
	public function testGet()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('availabilities.get.success');
		$response = $service->get()->spn("1234")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#availabilities/getResponse', $response['kind']);

		$this->assertArrayHasKey('items', $response);
		$this->assertIsArray($response['items']);
		$this->assertCount(3, $response['items']);
		$this->assertEquals('1234', $response['items'][0]['spn']);
	}

	/**
	 * Test what happens when availabilities are not found.
	 *
	 * @group availabilities
	 * @group availabilities.get
	 */
	public function testGetNotFound()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('availabilities.get.not_found');

		$response = $service->get()->spn('no-such-product')->execute();

		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#availabilities/getResponse', $response['kind']);

		$this->assertArrayHasKey('items', $response);
		$this->assertNull($response['items']);
	}
}
?>
