<?php namespace Meplato\Store2\Tests;
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
use Meplato\Store2\Service;
use GuzzleHttp\Message\Response;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the Me service.
 */
class MeTest extends BaseTestCase
{
	/**
	 * Tests a successful call to the Me service.
	 */
	#[Group('root')]
	#[Group('me')]
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('me.success');
		$response = $service->me()->execute();

		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('catalogsLink', $response);
		$this->assertArrayHasKey('merchant', $response);
		$this->assertArrayHasKey('user', $response);
		$this->assertEquals('store#me', $response['kind']);

		$merchant = $response['merchant'];
		$this->assertIsArray($merchant);
		$this->assertArrayHasKey('kind', $merchant);
		$this->assertEquals('store#merchant', $merchant['kind']);
		$this->assertArrayHasKey('id', $merchant);
		$this->assertArrayHasKey('name', $merchant);

		$user = $response['user'];
		$this->assertIsArray($user);
		$this->assertArrayHasKey('kind', $user);
		$this->assertEquals('store#user', $user['kind']);
		$this->assertArrayHasKey('id', $user);
		$this->assertArrayHasKey('name', $user);
		$this->assertArrayHasKey('email', $user);
	}

	/**
	 * Tests an unauthorized call to the Me service.
	 */
	#[Group('root')]
	#[Group('me')]
	public function testUnauthorized()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('me.unauthorized');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Unauthorized");
		$service->me()->execute();
	}
}
?>
