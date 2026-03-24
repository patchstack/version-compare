<?php

declare(strict_types=1);

use Patchstack\VersionCompare\NormalizeVersionPair;

beforeEach(function () {
    $this->normalizer = new NormalizeVersionPair;
});

it('pads shorter numeric segments to equal length', function (string $v1, string $v2, string $expectedV1, string $expectedV2) {
    [$result1, $result2] = $this->normalizer->execute($v1, $v2);

    expect($result1)->toBe($expectedV1)
        ->and($result2)->toBe($expectedV2);
})->with([
    'decimal: 3.5 vs 3.41' => ['3.5', '3.41', '3.50', '3.41'],
    'decimal: 3.41 vs 3.5' => ['3.41', '3.5', '3.41', '3.50'],
    'equal length: no change' => ['3.41', '3.42', '3.41', '3.42'],
    'semver: already equal' => ['1.2.3', '1.2.4', '1.2.3', '1.2.4'],
    'different segment counts' => ['3.5', '3.41.2', '3.50.0', '3.41.2'],
    'single segment' => ['5', '41', '50', '41'],
]);

it('skips non-numeric segments', function () {
    [$result1, $result2] = $this->normalizer->execute('5.0.37.decaf', '5.0.53.decaf');

    expect($result1)->toBe('5.0.37.decaf')
        ->and($result2)->toBe('5.0.53.decaf');
});
