<?php namespace Meplato\Store2\Availabilities\Tests;
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
use Meplato\Store2\Availabilities\Service;

class UpsertTest extends BaseTestCase
{
	/**
	 * Tests a successful call to upsert of availabilities.
	 *
	 * @group availabilities
	 * @group availabilities.upsert
	 */
	public function testUpsert()
	{
		$availability = [
			'Message'=> "not in stock",
			'Quantity'=>  0,
			'Region'=> "AQ",
			'Updated'=> "Q1/2024",
			'ZipCode'=> "1234",
		];

		$service = $this->getService();

		// Upsert availabilities, i.e. either create or update.
		$this->mockResponseFromFile('availabilities.upsert.success');
		$response = $service->upsert()->spn(1234)->availability($availability)->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#availabilities/upsertResponse', $response['kind']);
		$this->assertArrayHasKey('link', $response);
	}
}
?>
