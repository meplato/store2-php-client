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
 * Tests searching for/listing catalogs.
 */
class SearchTest extends BaseTestCase
{
	/**
	 * Tests a successful searching for catalogs.
	 *
	 * @group catalogs
	 * @group catalogs.search
	 */
	public function testSearch()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.search.success');
		$response = $service->search()->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('totalItems', $response);
		$this->assertArrayHasKey('items', $response);
		$this->assertEquals('store#catalogs', $response['kind']);
	}

	/**
	 * Tests a searching for catalogs when unauthorized.
	 *
	 * @group catalogs
	 * @group catalogs.search
	 */
	public function testSearchUnauthorized()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.search.unauthorized');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Unauthorized");

		$service->search()->execute();
	}
}
?>
