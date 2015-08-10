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
 * Tests updating products.
 *
 * Updating a product means changing the content of the specified fields.
 * Fields not specified in the request are not touched.
 * If you want to change all fields of a product, use Replace.
 *
 */
class UpdateTest extends BaseTest
{
	/**
	 * Tests a successful call to update a product.
	 *
	 * @group products
	 * @group products.update
	 */
	public function testUpdate()
	{
		// Update just updates the given fields, let's the rest unchanged.
		// Use Replace to replace all fields.
		$updateProduct = [
			'description' => 'This is a product description.',
			'price'       => 1150.00
		];

		$service = $this->getService();

		// Update product
		$this->mockResponseFromFile('products.update.success');
		$response = $service->update()->pin('AD8CCDD5F9')->area('work')->id('MBA11@12')->product($updateProduct)->execute();
		$this->assertInternalType('array', $response);
		$this->assertArrayHasKey('id', $response);
		$this->assertArrayHasKey('link', $response);
		// ID in response contains the identifier of the product -- will be unchanged
		$this->assertNotNull($response['id']);
		$this->assertEquals($response['id'], 'MBA11@12');
	}
}
?>
