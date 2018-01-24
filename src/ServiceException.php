<?php namespace Meplato\Store2;
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

class ServiceException extends \RuntimeException
{
  private $response;

  public function __construct($response, \Exception $previous = null)
  {
	$this->response = $response;

	$body = $response->getBodyJSON();
	$code = $response->getStatusCode();
	$message = "Service failed with status code {$code}";
	if ($body != null && array_key_exists('error', $body)) {
	  if (array_key_exists('message', $body['error'])) {
		$message = $body['error']['message'];
	  }
	}
	parent::__construct($message, $code, $previous);
  }

  public function getResponse()
  {
	return $this->response;
  }
}
?>
