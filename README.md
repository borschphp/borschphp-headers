# Borsch - HTTP Headers

A library to help deal with headers.  
Convenient to deal with headers in your PSR-7 implementations.

## Installation

The package can be installed via [composer](https://getcomposer.org/). Simply run the following command:

`composer require borschphp/headers`

## Usage

```php
use Borsch\Http\Header;
use Borsch\Http\HeadersMap;

$content_type = new Header('Content-Type', 'application/json');
$content_disposition = new Header('Content-Disposition', 'attachment', 'filename="test.pdf"');
$accept_encoding = new Header('Accept-Encoding', 'gzip', 'deflate');
$cache_control = new Header('Cache-Control', 'no-cache');

$headers = new HeadersMap($content_type, $content_disposition, $accept_encoding);

$headers->add(
    new Header('Cache-Control', 'no-cache')
);

if ($headers->has('accept-encoding')) {
    echo $headers->get('ACCEPT-ENCODING')->__toString();
    // Accept-Encoding: gzip, deflate
}

foreach ($headers as $header) {
    /** @var Header $header */
    $name = $header->getName();
    $values = $header->getValues();
}
```

## Tests

The package includes a set of tests (made with [Pest](https://pestphp.com/)) to ensure that everything is working as expected.  
You can run the tests by executing the following command:

```shell
./vendor/bin/pest
```

## License

The package is licensed under the MIT license. See [License File](https://github.com/borschphp/borsch-headers/blob/master/LICENSE.md)
for more information.