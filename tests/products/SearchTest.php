<?php namespace Meplato\Store2\Tests\Products;
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
use Meplato\Store2\Products\Service;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests searching for products.
 */
class SearchTest extends BaseTestCase
{
	/**
	 * Tests a successful call to search for products.
	 */
	#[Group('products')]
	#[Group('products.search')]
	public function testSearch()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.search.success');
		$response = $service->search()->pin("AD8CCDD5F9")->area('work')->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('totalItems', $response);
		$this->assertArrayHasKey('items', $response);
		$this->assertEquals('store#products', $response['kind']);
	}

	/**
	 * Tests what happens when user is not authenticated.
	 */
	#[Group('products')]
	#[Group('products.search')]
	public function testSearchUnauthorized()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.search.unauthorized');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Unauthorized");

		$service->search()->pin('AD8CCDD5F9')->area('work')->execute();
	}
}
?>
