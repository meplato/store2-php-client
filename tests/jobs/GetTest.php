<?php namespace Meplato\Store2\Jobs\Tests;
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
use Meplato\Store2\Jobs\Service;

/**
 * Tests retrieving a job.
 */
class GetTest extends Base
{
	/**
	 * Tests a successful call to retrieve a job.
	 *
	 * @group jobs
	 * @group jobs.get
	 */
	public function testSuccess()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('jobs.get.success');
		$response = $service->get()->id("58097dc3-b279-49b5-a5da-23eb1c77d840")->execute();
		$this->assertIsArray($response);
		$this->assertArrayHasKey('kind', $response);
		$this->assertArrayHasKey('selfLink', $response);
		$this->assertArrayHasKey('id', $response);
		$this->assertEquals('store#job', $response['kind']);
	}

	/**
	 * Tests call to a job that does not exist.
     *
	 * @group jobs
	 * @group jobs.get
	 */
	public function testNotFound()
	{
		$service = $this->getService();
		$this->mockResponseFromFile('jobs.get.not_found');

		$this->expectException(\Meplato\Store2\ServiceException::class);
		$this->expectExceptionMessage("Job not found");

		$response = $service->get()->id("no-such-job")->execute();
		$this->assertNull($response);
	}
}
?>
