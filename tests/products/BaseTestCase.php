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

/**
 * Base test for the Products service.
 */
abstract class BaseTestCase extends \Meplato\Store2\Tests\BaseTestCase
{
	private $service;


	public function getService()
	{
		$client = $this->getHttpClient();
		$this->service = new \Meplato\Store2\Products\Service($client);
		$this->service->setBaseURL("http://store2.go/api/v2");
		return $this->service;
	}

	protected function setUp(): void
	{
	}

	protected function tearDown(): void
	{
	}
}
?>
