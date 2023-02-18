<?php

use Borsch\Http\Header;
use Borsch\Http\HeadersMap;

test('constructor throws exception when headers with same name', function () {
    $header = new Header('Content-Type', 'application/json');
    $other = new Header('Content-Type', 'application/json');

    new HeadersMap($header, $other);
})->throws(InvalidArgumentException::class);

test('add() throws exception when headers with same name', function () {
    $header = new Header('Content-Type', 'application/json');

    (new HeadersMap($header))->add(new Header('Content-Type', 'application/json'));
})->throws(InvalidArgumentException::class);

test('add() should append one Header instance to the collection', function () {
    $headers = new class(new Header('Content-Type', 'application/json')) extends HeadersMap {
        public function getHeaders() { return $this->headers; }
    };

    $headers->add(new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'));

    expect($headers->getHeaders())
        ->toBeArray()
        ->toHaveCount(2)
        ->toHaveKeys(['content-type', 'content-disposition']);
});

test('add() should append many Header instances to the collection', function () {
    $content_type = new Header('Content-Type', 'application/json');
    $content_disposition = new Header('Content-Disposition', 'attachment', 'filename="test.pdf"');
    $accept_encoding = new Header('Accept-Encoding', 'gzip', 'deflate');
    $cache_control = new Header('Cache-Control', 'no-cache');

    $headers = new HeadersMap($content_type);

    $headers->add($content_disposition);
    $headers->add($accept_encoding);
    $headers->add($cache_control);

    expect($headers->getAll())
        ->toBeArray()
        ->toHaveCount(4)
        ->toHaveKeys(range(0, 3))
        ->toMatchArray([$content_type, $content_disposition, $accept_encoding, $cache_control]);;
});

test('has() find match case-sensitive', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->has('Accept-Encoding'))->toBeTrue()
        ->and($headers->has('Cache-Control'))->toBeTrue()
        ->and($headers->has('Content-Disposition'))->toBeTrue()
        ->and($headers->has('Content-Type'))->toBeTrue();
});

test('has() find match case-insensitive', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->has('accept-encoding'))->toBeTrue()
        ->and($headers->has('CACHE-CONTROL'))->toBeTrue()
        ->and($headers->has('CoNtEnT-DiSpOsItIoN'))->toBeTrue()
        ->and($headers->has('content-TYPE'))->toBeTrue();
});

test('hasValue() does not find match', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->has('Transfer-Encoding'))->toBeFalse();
});

test('get() returns Header instance case-sensitive', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->get('Content-Disposition'))->toBeInstanceOf(Header::class)
        ->and($headers->get('Content-Disposition')->getValues())->toBeArray()
        ->and($headers->get('Content-Disposition')->getValues())->toHaveCount(2)
        ->and($headers->get('Content-Disposition')->getValues())->toMatchArray(['attachment', 'filename="test.pdf"']);
});

test('get() returns Header instance case-insensitive', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->get('accept-encoding'))->toBeInstanceOf(Header::class)
        ->and($headers->get('accept-ENCODING')->getValues())->toBeArray()
        ->and($headers->get('ACCEPT-encoding')->getValues())->toHaveCount(2)
        ->and($headers->get('ACCEPT-ENCODING')->getValues())->toMatchArray(['gzip', 'deflate']);
});

test('get() returns null if not found', function () {
    expect((new HeadersMap())->get('accept-encoding'))->toBeNull();
});

test('remove() unset Header instance case-sensitive', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->has('Accept-Encoding'))->toBeTrue();

    $headers->remove('Accept-Encoding');

    expect($headers->has('Accept-Encoding'))->toBeFalse();
});

test('remove() unset Header instance case-insensitive', function () {
    $headers = new HeadersMap(
        new Header('Content-Type', 'application/json'),
        new Header('Content-Disposition', 'attachment', 'filename="test.pdf"'),
        new Header('Accept-Encoding', 'gzip', 'deflate'),
        new Header('Cache-Control', 'no-cache')
    );

    expect($headers->has('Accept-Encoding'))->toBeTrue();

    $headers->remove('accept-ENCODING');

    expect($headers->has('Accept-Encoding'))->toBeFalse();
});

test('getAll() returns 0-indexed array of all Header instances', function () {
    $content_type = new Header('Content-Type', 'application/json');
    $content_disposition = new Header('Content-Disposition', 'attachment', 'filename="test.pdf"');
    $accept_encoding = new Header('Accept-Encoding', 'gzip', 'deflate');
    $cache_control = new Header('Cache-Control', 'no-cache');

    $headers = new HeadersMap($content_type, $content_disposition, $accept_encoding, $cache_control);

    expect($headers->getAll())
        ->toBeArray()
        ->toHaveCount(4)
        ->toHaveKeys(range(0, 3))
        ->toMatchArray([$content_type, $content_disposition, $accept_encoding, $cache_control]);
});
