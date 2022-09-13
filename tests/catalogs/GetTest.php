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
 * Tests retrieving a catalog.
 */
class GetTest extends BaseTest
{
	/**
	 * Tests a successful call to retrieve a catalog.
	 *
	 * @group catalogs
	 * @group catalogs.get
	 */
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.get.success');
		$response = $service->get()->pin("DEADBEEF")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('id', $response);
		$this->assertEquals('store#catalog', $response['kind']);
	}

	/**
	 * Tests call to a catalog that does not exist.
	 *
	 * @group catalogs
	 * @group catalogs.get
	 */
	public function testNotFound()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.get.not_found');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Catalog not found");

		$response = $service->get()->pin("DEADBEEF")->execute();
		$this->assertNull($response);
	}
}
?>
