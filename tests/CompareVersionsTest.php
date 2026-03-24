<?php

declare(strict_types=1);

use Patchstack\VersionCompare\CompareVersions;
use Patchstack\VersionCompare\NormalizeVersionPair;
use Patchstack\VersionCompare\VersionStrategy;

beforeEach(function () {
    $this->compare = new CompareVersions(new NormalizeVersionPair);
});

it('compares versions using standard strategy', function (string $v1, string $v2, string $op, bool $expected) {
    expect($this->compare->execute($v1, $v2, $op))->toBe($expected);
})->with([
    'less than: true' => ['1.0', '2.0', '<', true],
    'less than: false' => ['2.0', '1.0', '<', false],
    'greater than' => ['2.0', '1.0', '>', true],
    'equal' => ['1.0', '1.0', '==', true],
    'less or equal' => ['1.0', '1.0', '<=', true],
    'greater or equal' => ['2.0', '1.0', '>=', true],
    'semver less than' => ['1.2.3', '1.2.4', '<', true],
    'standard: 3.5 < 3.41 (PHP native behaviour)' => ['3.5', '3.41', '<', true],
]);

it('compares versions using decimal normalized strategy', function (string $v1, string $v2, string $op, bool $expected) {
    expect($this->compare->execute($v1, $v2, $op, VersionStrategy::DecimalNormalized))->toBe($expected);
})->with([
    'decimal: 3.5 > 3.41 (normalized)' => ['3.5', '3.41', '>', true],
    'decimal: 3.5 < 3.41 is false' => ['3.5', '3.41', '<', false],
    'decimal: 3.50 == 3.5' => ['3.50', '3.5', '==', true],
    'decimal: 3.40 < 3.41' => ['3.40', '3.41', '<', true],
    'semver still works' => ['1.2.3', '1.2.4', '<', true],
]);
