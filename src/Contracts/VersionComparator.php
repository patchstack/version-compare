<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Contracts;

interface VersionComparator
{
    public function compare(string $version1, string $version2, string $operator): bool;
}
