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
 * Tests retrieving a product.
 */
class GetTest extends Base
{
	/**
	 * Tests a successful call to retrieve a product.
	 *
	 * @group products
	 * @group products.get
	 */
	public function testGet()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.get.success');
		$response = $service->get()->pin('AD8CCDD5F9')->area('work')->spn('50763599')->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#product', $response['kind']);
		$this->assertArrayHasKey('id', $response);
		$this->assertEquals('50763599@12', $response['id']);
		$this->assertArrayHasKey('spn', $response);
		$this->assertEquals('50763599', $response['spn']);
		$this->assertArrayHasKey('name', $response);

		$this->assertArrayHasKey('custFields', $response);
		$this->assertIsArray($response['custFields']);
		$this->assertCount(1, $response['custFields']);
		$this->assertEquals('Steuersatz', $response['custFields'][0]['name']);
		$this->assertEquals('0.19', $response['custFields'][0]['value']);

		$this->assertArrayHasKey('blobs', $response);
		$this->assertIsArray($response['blobs']);
		$this->assertCount(1, $response['blobs']);
		$this->assertEquals('normal', $response['blobs'][0]['kind']);
		$this->assertEquals('50763599.jpg', $response['blobs'][0]['source']);
		$this->assertEquals('Normalbild', $response['blobs'][0]['text']);
		$this->assertEquals('de', $response['blobs'][0]['lang']);
	}

	/**
	 * Test what happens when a product is not found.
	 *
	 * @group products
	 * @group products.get
	 */
	public function testGetNotFound()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.get.not_found');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Product not found");

		$response = $service->get()->pin('AD8CCDD5F9')->area('work')->spn('no-such-product')->execute();
		$this->assertNull($response);
	}

	/**
	 * Test what happens when the user is not authenticated.
	 *
	 * @group products
	 * @group products.get
	 */
	public function testGetUnauthorized()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.get.unauthorized');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Unauthorized");

		$service->get()->pin("AD8CCDD5F9")->area('work')->spn('50763599')->execute();
	}
}
?>
