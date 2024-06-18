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

/**
 * Tests the entry point for Me and Ping service.
 */
class ServiceTest extends BaseTestCase
{
	/**
	 * Tests a generic call into the service.
	 *
	 * @group root
	 * @group service
	 */
  public function testService()
  {
	$client = new HttpClient();
	$service = new Service($client);
	$service->setBaseURL("http://store2.go/api/v2");
	$this->assertNotNull($service);
	$this->assertNotNull($service->getClient());
	$this->assertNotNull($service->me());
	$this->assertNotNull($service->ping());
  }
}
?>
