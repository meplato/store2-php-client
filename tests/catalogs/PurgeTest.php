<?php namespace Meplato\Store2\Catalogs\Tests;
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

/**
 * Tests purging a catalog.
 */
class PurgeTest extends BaseTest
{
	/**
	 * Tests a successful call to purge the work area of a catalog.
	 * This API removes all products from an area.
	 *
	 * @group catalogs
	 * @group catalogs.purge
	 */
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.purge.success');
		$response = $service->purge()->pin("DEADBEEF")->area("work")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertEquals('store#catalogPurge', $response['kind']);
	}
}
?>
