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
 * Tests publishing a catalog.
 */
class PublishTest extends BaseTest
{
	/**
	 * Tests a successful publishing a catalog.
	 *
	 * @group catalogs
	 * @group catalogs.publish
	 */
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.publish.success');
		$response = $service->publish()->pin("DEADBEEF")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('statusLink', $response);
		$this->assertEquals('store#catalogPublish', $response['kind']);
	}

	/**
	 * Tests polling for publish status.
	 *
	 * @group catalogs
	 * @group catalogs.publish
	 */
	public function testStatusBusy()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.publish.busy');
		$response = $service->publishStatus()->pin("DEADBEEF")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('status', $response);
		$this->assertEquals('store#catalogPublishStatus', $response['kind']);
		$this->assertEquals('busy', $response['status']);
	}

	/**
	 * Tests polling for completed publish status.
	 *
	 * @group catalogs
	 * @group catalogs.publish
	 */
	public function testStatusDone()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('catalogs.publish.done');
		$response = $service->publishStatus()->pin("DEADBEEF")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('status', $response);
		$this->assertEquals('store#catalogPublishStatus', $response['kind']);
		$this->assertEquals('done', $response['status']);
	}
}
?>
