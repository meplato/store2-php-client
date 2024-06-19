<?php namespace Meplato\Store2\Catalogs\Tests;
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
use Meplato\Store2\Catalogs\Service;

/**
 * Tests creating a catalog.
 */
class CreateTest extends BaseTest
{
	/**
	 * Tests a successful call to create a catalog
	 *
	 * @group catalogs
	 * @group catalogs.create
	 */
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.create.success');

		$createCatalog = [
			'merchantId' => 1,
			'name'  => 'test',
			'projectMpcc' => 'meplato',
			'validFrom' => null,
			'validUntil' => null,
			'country' => 'DE',
			'currency' => 'EUR',
			'language' => 'de',
			'target' => 'mall',
			'type' => 'CC',
			'sageNumber' => '',
			'sageContract' => ''
		];

		$response = $service->create()->catalog($createCatalog)->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#catalog', $response['kind']);
		$this->assertEquals('48F31F33AD', $response['pin']);
		$this->assertEquals('CC', $response['type']);
		$this->assertEquals(81, $response['id']);
	}
}
?>
