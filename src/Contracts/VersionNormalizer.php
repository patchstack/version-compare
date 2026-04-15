<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Contracts;

interface VersionNormalizer
{
    /**
     * Clean a version string for comparison (strip prefixes, lowercase, trim).
     */
    public function normalize(string $version): string;

    /**
     * Check if two versions can be meaningfully compared.
     *
     * For example, Drupal versions with different major prefixes (8.x vs 9.x)
     * are never vulnerable to each other. Called with raw (pre-normalized) strings.
     */
    public function areCompatible(string $version1, string $version2): bool;
}
