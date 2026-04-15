<?php

declare(strict_types=1);

use Patchstack\VersionCompare\Comparators\DecimalComparator;
use Patchstack\VersionCompare\Comparators\StandardComparator;
use Patchstack\VersionCompare\VersionStrategy;

it('creates strategy from abnormal version flag', function () {
    expect(VersionStrategy::fromAbnormalFlag(true))->toBe(VersionStrategy::DecimalNormalized)
        ->and(VersionStrategy::fromAbnormalFlag(false))->toBe(VersionStrategy::Standard);
});

it('creates the correct comparator', function () {
    expect(VersionStrategy::Standard->comparator())->toBeInstanceOf(StandardComparator::class)
        ->and(VersionStrategy::DecimalNormalized->comparator())->toBeInstanceOf(DecimalComparator::class);
});
