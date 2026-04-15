<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare;

use Patchstack\VersionCompare\Comparators\DecimalComparator;
use Patchstack\VersionCompare\Comparators\StandardComparator;
use Patchstack\VersionCompare\Contracts\VersionComparator;

enum VersionStrategy: string
{
    case Standard = 'standard';
    case DecimalNormalized = 'decimal_normalized';

    public static function fromAbnormalFlag(bool $abnormalVersion): self
    {
        return $abnormalVersion
            ? self::DecimalNormalized
            : self::Standard;
    }

    public function comparator(): VersionComparator
    {
        return match ($this) {
            self::Standard => new StandardComparator(),
            self::DecimalNormalized => new DecimalComparator(),
        };
    }
}
