<?php

declare(strict_types=1);

use Patchstack\VersionCompare\VersionStrategy;

it('creates strategy from abnormal version flag', function () {
    expect(VersionStrategy::fromAbnormalFlag(true))->toBe(VersionStrategy::DecimalNormalized)
        ->and(VersionStrategy::fromAbnormalFlag(false))->toBe(VersionStrategy::Standard);
});
