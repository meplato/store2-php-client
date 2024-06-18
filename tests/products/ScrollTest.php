<?php namespace Meplato\Store2\Products\Tests;
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

/**
 * Tests scrolling through the products of a catalog.
 */
class ScrollTest extends Base
{
	/**
	 * Tests a successful start of scrolling through the products of a catalog.
	 *
	 * @group products
	 * @group products.scroll
	 */
	public function testScroll()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.scroll.success.1');

		// Get first slice of products.
		// The first call might not necessarily return products.
		// But it returns a page token which we'll use to get the next slice of products.
		$response = $service->scroll()->pin('AD8CCDD5F9')->area('work')->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#products', $response['kind']);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('nextLink', $response);
		$this->assertArrayHasKey('totalItems', $response);

		$this->assertArrayHasKey('pageToken', $response);
		$pageToken = $response['pageToken'];
		$this->assertNotNull($pageToken);

		// In general, you can start a while loop here and
		// break when pageToken is either not returned or blank.
		//
		// Example:
		// while (true) {
		//   $response = $service->scroll()->...->pageToken($pageToken)->execute();
		//   // ... iterate through $response['items'] here ...
		//   if (!array_key_exists('pageToken', $response) || $response['pageToken'] === '') {
		//     // Last page returned
		//     break;
		//   }
		// }

		// Get second slice of products.
		$this->mockResponseFromFile('products.scroll.success.2');
		$response = $service->scroll()->pin('AD8CCDD5F9')->area('work')->pageToken($pageToken)->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('nextLink', $response);
		$this->assertArrayHasKey('totalItems', $response);
		$this->assertArrayHasKey('items', $response);
		$items = $response['items'];
		$this->assertIsArray($items);
	}

	/**
	 * Tests a successful start of scrolling through a differential download of the products of a catalog.
	 *
	 * @group products
	 * @group products.scroll
	 */
	public function testDifferentialScroll()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.scroll.differential.success');

        // Get differential update (from version 2 to 3)
		// The first call might not necessarily return products.
		// But it returns a page token which we'll use to get the next slice of products.
		$response = $service->scroll()->pin('AD8CCDD5F9')->area('work')->version(3)->mode("diff")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#products', $response['kind']);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('nextLink', $response);
		$this->assertArrayHasKey('totalItems', $response);

		$this->assertArrayHasKey('pageToken', $response);
		$pageToken = $response['pageToken'];
		$this->assertNotNull($pageToken);

		foreach ($response['items'] as &$product) {
			$this->assertNotEmpty($product['id']);
			$this->assertNotEmpty($product['spn']);
			$this->assertArrayHasKey("mode", $product);
			$this->assertNotEmpty($product['mode']);
			$this->assertNotEmpty($product['created']);
			$this->assertNotEmpty($product['updated']);
		}
	}
}
?>
