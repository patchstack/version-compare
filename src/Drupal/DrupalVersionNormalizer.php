<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Drupal;

final class DrupalVersionNormalizer
{
    /**
     * Strip Drupal major version prefixes (e.g., "8.x-", "9.x-") from a version string.
     */
    public function normalize(string $version): string
    {
        return preg_replace('/([0-9]*\.x)-/m', '', $version);
    }

    /**
     * Check if two versions share the same Drupal major version prefix.
     *
     * Returns true if both have matching prefixes (e.g., both "8.x-") or neither has one.
     * Returns false if they have different prefixes.
     */
    public function majorVersionsMatch(string $version1, string $version2): bool
    {
        if (stripos($version1, 'x') === false || stripos($version2, 'x') === false) {
            return true;
        }

        $matches1 = $matches2 = null;
        preg_match_all('/([0-9]*\.x)-/m', $version1, $matches1, PREG_SET_ORDER, 0);
        preg_match_all('/([0-9]*\.x)-/m', $version2, $matches2, PREG_SET_ORDER, 0);

        if (count($matches1) > 0 && count($matches2) > 0 && $matches1[0][1] !== $matches2[0][1]) {
            return false;
        }

        return true;
    }
}
