<?php namespace Meplato\Store2\Tests\Availabilities;
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
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests deleting availabilities.
 */
class DeleteTest extends BaseTestCase
{
	/**
	 * Tests a successful call to delete availabilities.
	 */
	#[Group('availabilities')]
	#[Group('availabilities.delete')]
	public function testDelete()
	{

		$service = $this->getService();
		$this->mockResponseFromFile('availabilities.delete.success');
		$response = $service->delete()->spn('1234')->execute();

		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#availabilities/deleteResponse', $response['kind']);
	}
}
?>
