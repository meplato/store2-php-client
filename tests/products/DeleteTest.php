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
 * Tests deleting a product.
 */
class DeleteTest extends BaseTest
{
	/**
	 * Tests a successful call to delete a product.
	 *
	 * @group products
	 * @group products.delete
	 */
	public function testDelete()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.delete.success');
		$service->delete()->pin('AD8CCDD5F9')->area('work')->spn('50763599')->execute();
	}

	/**
	 * Test what happens when a product is not found.
	 *
	 * @expectedException        Meplato\Store2\ServiceException
	 * @expectedExceptionMessage Product not found
	 *
	 * @group products
	 * @group products.delete
	 */
	public function testDeleteNotFound()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('products.delete.not_found');
		$service->delete()->pin('AD8CCDD5F9')->area('work')->spn('no-such-product')->execute();
	}
}
?>
