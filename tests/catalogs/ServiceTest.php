<?php namespace Meplato\Store2\Tests\Catalogs;
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
use Meplato\Store2\Catalogs\Service;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the Catalogs service.
 */
class ServiceTest extends BaseTestCase
{
	/**
	 * Tests a the Catalogs service.
	 */
	#[Group('catalogs')]
	#[Group('catalogs.service')]
	#[Group('service')]
	public function testService()
	{
		$client = new HttpClient();
		$service = new Service($client);
		$service->setBaseURL("http://store2.go/api/v2");
		$service->setUser("me");
		$service->SetPassword("secret");
		$this->assertNotNull($service);
		$this->assertNotNull($service->getClient());
		$this->assertNotNull($service->search());
		$this->assertNotNull($service->get());
	}
}
?>
