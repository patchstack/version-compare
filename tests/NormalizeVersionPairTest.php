<?php

declare(strict_types=1);

use Patchstack\VersionCompare\Comparators\DecimalComparator;

/*
|--------------------------------------------------------------------------
| Decimal segment padding — tested through the DecimalComparator
|--------------------------------------------------------------------------
|
| The DecimalComparator right-pads numeric segments so "3.5" becomes "3.50",
| ensuring decimal-style versions compare correctly. These tests verify the
| padding behavior via comparison results.
|
*/

it('pads shorter numeric segments to equal length', function (string $v1, string $v2, string $op, bool $expected) {
    expect((new DecimalComparator())->compare($v1, $v2, $op))->toBe($expected);
})->with([
    'decimal: 3.5 vs 3.41' => ['3.5', '3.41', '>', true],
    'decimal: 3.41 vs 3.5' => ['3.41', '3.5', '<', true],
    'equal length: no change' => ['3.41', '3.42', '<', true],
    'semver: already equal' => ['1.2.3', '1.2.4', '<', true],
    'different segment counts' => ['3.5', '3.41.2', '>', true],
    'single segment' => ['5', '41', '>', true],
]);

it('skips non-numeric segments', function () {
    $comparator = new DecimalComparator();

    expect($comparator->compare('5.0.37.decaf', '5.0.53.decaf', '<'))->toBeTrue()
        ->and($comparator->compare('5.0.53.decaf', '5.0.37.decaf', '>'))->toBeTrue();
});
