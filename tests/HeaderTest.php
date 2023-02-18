<?php

use Borsch\Http\Header;

test('constructor with 1 value', function () {
    $header = new Header('Content-Type', 'application/json');

    expect($header->getValues())->toBeArray()->toHaveCount(1);
});

test('constructor with more than 1 value', function () {
    $header = new Header('Content-Type', 'application/json', 'charset=UTF-8');

    expect($header->getValues())->toBeArray()->toHaveCount(2);
});

test('getName() should return header name as is', function () {
    $header = new Header('Content-Type', 'application/json');

    expect($header->getName())->toBe('Content-Type')
        ->and($header->getName())->not()->toBe('content-type');
});

test('getValues() should return array of values', function () {
    $header = new Header('Content-Type', 'application/json', 'charset=UTF-8');

    expect($header->getValues())
        ->toBeArray()
        ->toHaveCount(2)
        ->toMatchArray(['application/json', 'charset=UTF-8']);
});

test('hasValue() find match case-sensitive', function () {
    $header = new Header('Content-Type', 'application/json', 'charset=UTF-8', 'FooBar');

    expect($header->hasValue('application/json'))->toBeTrue()
        ->and($header->hasValue('charset=UTF-8'))->toBeTrue()
        ->and($header->hasValue('FooBar'))->toBeTrue();
});

test('hasValue() find match case-insensitive', function () {
    $header = new Header('Content-Type', 'application/json', 'charset=UTF-8', 'FooBar');

    expect($header->hasValue('APPLICATION/JSON'))->toBeTrue()
        ->and($header->hasValue('CharSet=Utf-8'))->toBeTrue()
        ->and($header->hasValue('FoOBaR'))->toBeTrue();
});

test('hasValue() does not find match', function () {
    $header = new Header('Content-Type', 'application/json');

    expect($header->hasValue('CharSet=Utf-8'))->toBeFalse();
});

test('addValue() append value to the list of values', function () {
    $header = new Header('Content-Type', 'application/json');

    expect($header->hasValue('CharSet=Utf-8'))->toBeFalse();

    $header->addValues('charset=UTF-8');

    expect($header->hasValue('CharSet=Utf-8'))->toBeTrue();
});

test('addValues() append values to the list of values', function () {
    $header = new Header('Content-Type', 'application/json');

    expect($header->hasValue('CharSet=Utf-8'))->toBeFalse()
        ->and($header->hasValue('FooBar'))->toBeFalse();

    $header->addValues('charset=UTF-8');
    $header->addValues('FooBar');

    expect($header->hasValue('CharSet=Utf-8'))->toBeTrue()
        ->and($header->hasValue('FooBar'))->toBeTrue();
});

test('equals() returns true on same Header instance', function () {
    $header = new Header('Content-Type', 'application/json');

    expect($header->equals($header))->toBeTrue();
});

test('equals() returns true on cloned Header instance', function () {
    $header = new Header('Content-Type', 'application/json');
    $other = clone $header;

    expect($header->equals($other))->toBeTrue();
});

test('equals() returns true on equal Header instance', function () {
    $header = new Header('Content-Type', 'application/json');
    $other = new Header('Content-Type', 'application/json');

    expect($header->equals($other))->toBeTrue();
});

test('equals() returns false on different values count', function () {
    $header = new Header('Content-Type', 'application/json');
    $other = new Header('Content-Type', 'application/json', 'charset=UTF-8');

    expect($header->equals($other))->toBeFalse();
});

test('equals() returns false on same values count but different values', function () {
    $header = new Header('Content-Type', 'application/json');
    $other = new Header('Content-Type', 'application/pdf');

    expect($header->equals($other))->toBeFalse();
});

test('__toString() returns a comma-separated string of the values', function () {
    $header = new Header('Content-Type', 'application/json', 'charset=UTF-8');

    expect((string)$header)->toBe('Content-Type: application/json, charset=UTF-8');
});
