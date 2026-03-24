<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare;

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
}
