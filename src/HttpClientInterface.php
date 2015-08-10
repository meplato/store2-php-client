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

/**
 * Client interface for sending HTTP requests.
 */
interface HttpClientInterface
{
  /**
   * Executes a HTTP request. Notice that the execute method
   * takes a URI template, not a URL. It must convert the
   * template and the params to generate the final URL.
   *
   * @param string $method HTTP method, e.g. GET or POST
   * @param string $urlTemplate URL template
   * @param array $params URI and query string parameters
   * @param array $headers HTTP headers
   * @param $body HTTP body
   *
   * @return HttpResponseInterface
   */
  public function execute($method, $urlTemplate, $params = [], $headers = [], $body = NULL);
}

/**
 * HTTP Response that will be returned from the execute
 * method of HttpClientInterface.
 */
interface HttpResponseInterface
{
  /**
   * Returns the HTTP status code of the response.
   *
   * @return int
   */
  public function getStatusCode();

  /**
   * Returns the body of the HTTP response.
   */
  public function getBody();

  /**
   * Returns the body of the HTTP response, interpreted as JSON.
   *
   * @return array
   */
  public function getBodyJSON();
}
?>
