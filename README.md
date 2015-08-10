# Meplato Store 2 API for PHP

This is the PHP client for the Meplato Store 2 API. It consists of a library
to integrate your infrastructure with Meplato suite for suppliers.

## Prerequisites

You need at two things to use the Meplato Store 2 API.

1. A login to Meplato Store 2.
2. An API token.

Get your login by contacting Meplato Supplier Network Services. The API token
is required to securely communicate with the Meplato Store 2 API. You can
find it in the personalization section when logged into Meplato Store.

## Installation

1. Install [composer](https://getcomposer.org/)
2. Run `composer install`

## Getting started

Using the library is actually quite simple. All functionality is separated
into services. So you e.g. have a service to work with catalogs, another
service to work with products in a catalog etc. All services need to be
initialized with your API token.

The following code snippet shows how to list your catalogs on Meplato Store.

```php
// Client is responsible for performing HTTP requests.
// The API comes with a default (based on GuzzleHttp),
// but feel free to create your own.
$client = new \Meplato\Store2\HttpClient();

// Create and initialize the Catalogs service with your API token.
$service = new \Meplato\Store2\Catalogs\Service($client);
$service->setUser("<your-api-token>");

// Now get the catalogs and print them.
$response = $service->search()->skip(0)->take(0)->sort("-created,name")->execute();
echo "You have " . $response["totalItems"] . " catalogs.\n";
foreach ($response['items'] as $catalog) {
  echo "Catalog " . $catalog["id"] . " has name " . $catalog["name"] . "\n";
}
```

Feel free to read the unit tests for the various usage scenarios of the
library.

## Documentation

Complete documentation for the Meplato Store 2 API can be found at
[https://developer.meplato.com/store2](https://developer.meplato.com/store2).

## Testing

We use [phpunit](https://phpunit.de/) for testing:

```sh
$ phpunit
$ phpunit --group me
$ phpunit --group catalogs
```

All tests are mocked, i.e. there is no real access to a web server on
the internet.

Tests are tagged with `@group` to allow for easy testing of certain features.
If you e.g. want to only run tests of the Me Service, run `phpunit --group me`.

# License

This software is licensed under the Apache 2 license.

    Copyright (c) 2015 Meplato GmbH, Switzerland <http://www.meplato.com>

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
