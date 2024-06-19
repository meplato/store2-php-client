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

/**
 * Tests replacing a product.
 */
class ReplaceTest extends BaseTestCase
{
	/**
	 * Tests a successful call to replace a product.
	 *
	 * @group products
	 * @group products.replace
	 */
	public function testReplace()
	{
		// Replace replaces all fields of a product, unlike Update.
		$replaceProduct = [
			'name'        => 'Produkt 1000 (NEU!)',
			'description' => 'Hier noch eine Produktbeschreibung.',
			'price'       => 1225.50,
			'ou'          => 'PCE'
		];

		$service = $this->getService();

		// Update product
		$this->mockResponseFromFile('products.update.success');
		$response = $service->replace()->pin('AD8CCDD5F9')->area('work')->spn('MBA11')->product($replaceProduct)->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('link', $response);
	}
}
?>
