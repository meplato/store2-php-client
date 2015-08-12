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

class CreateTest extends BaseTest
{
	/**
	 * Tests a successful call to create a product.
	 *
	 * @group products
	 * @group products.create
	 */
	public function testCreate()
	{
		$createProduct = [
			'spn'   => 'MBA11',
			'name'  => 'Apple MacBook Air 11"',
			'price' => 1199.00,
			'ou'    => 'PCE'
		];

		$service = $this->getService();

		// Create product
		$this->mockResponseFromFile('products.create.success');
		$response = $service->create()->pin('AD8CCDD5F9')->area('work')->product($createProduct)->execute();
		$this->assertInternalType('array', $response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('link', $response);

		// Retrieve the product by SPN. You can get the new product like this:
		//$product = $service->get()->pin('AD8CCDD5F9')->area('work')->spn("MBA11")->execute();
	}

	/**
	 * Tests missing mandatory field.
	 *
	 * @expectedException        Meplato\Store2\ServiceException
	 * @expectedExceptionMessage Bitte prüfen Sie Ihre Eingaben
	 *
	 * @group products
	 * @group products.create
	 */
	public function testCreateWithMissingRequiredFields()
	{
		$createProduct = [
			'spn'   => 'MBA11',
			'name'  => 'Apple MacBook Air 11"',
			'price' => 1199.00,
			'ou'    => ''       // <- no order unit specified here
		];

		$service = $this->getService();
		$this->mockResponseFromFile('products.create.parameter_missing');
		$response = $service->create()->pin('AD8CCDD5F9')->area('work')->product($createProduct)->execute();
	}
}
?>
