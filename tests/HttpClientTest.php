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
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the Meplato Store 2 HTTP client.
 */
class HttpClientTest extends BaseTestCase
{
	/**
	 * Test if the HTTP client works, in general.
	 */
	#[Group('root')]
	#[Group('http')]
	public function testNewHttpClient()
	{
		$client = new HttpClient();
		$resp = $client->execute("GET", "https://store.meplato.com/");
		$this->assertEquals(200, $resp->getStatusCode());
	}
}
?>
