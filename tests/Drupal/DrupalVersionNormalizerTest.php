<?php

declare(strict_types=1);

use Patchstack\VersionCompare\Normalizers\DrupalNormalizer;

beforeEach(function () {
    $this->normalizer = new DrupalNormalizer;
});

it('strips major version prefixes', function (string $input, string $expected) {
    expect($this->normalizer->normalize($input))->toBe($expected);
})->with([
    '8.x prefix' => ['8.x-3.5', '3.5'],
    '9.x prefix' => ['9.x-1.0', '1.0'],
    'no prefix' => ['3.5', '3.5'],
    'multiple prefixes in range' => ['8.x-3.0', '3.0'],
]);

it('checks major version matching', function (string $v1, string $v2, bool $expected) {
    expect($this->normalizer->areCompatible($v1, $v2))->toBe($expected);
})->with([
    'same major: 8.x' => ['8.x-3.0', '8.x-3.5', true],
    'different major: 8.x vs 9.x' => ['8.x-3.0', '9.x-3.5', false],
    'no prefix on either' => ['3.0', '3.5', true],
    'prefix on one only' => ['8.x-3.0', '3.5', true],
]);
