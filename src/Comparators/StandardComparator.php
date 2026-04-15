<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Comparators;

use Patchstack\VersionCompare\Contracts\VersionComparator;

final class StandardComparator implements VersionComparator
{
    public function compare(string $version1, string $version2, string $operator): bool
    {
        return version_compare($version1, $version2, $operator);
    }
}
