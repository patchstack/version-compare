<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare;

final class CompareVersions
{
    public function __construct(private readonly NormalizeVersionPair $normalizeVersionPair) {}

    public function execute(string $version1, string $version2, string $operator, VersionStrategy $strategy = VersionStrategy::Standard): bool
    {
        if ($strategy === VersionStrategy::DecimalNormalized) {
            [$version1, $version2] = $this->normalizeVersionPair->execute($version1, $version2);
        }

        return version_compare($version1, $version2, $operator);
    }
}
