<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Normalizers;

use Patchstack\VersionCompare\Contracts\VersionNormalizer;

final class DrupalNormalizer implements VersionNormalizer
{
    public function normalize(string $version): string
    {
        $version = strtolower(trim($version));

        if (str_starts_with($version, 'v')) {
            $version = substr($version, 1);
        }

        return preg_replace('/([0-9]*\.x)-/m', '', $version);
    }

    /**
     * Drupal versions with different major prefixes (8.x vs 9.x) are incompatible.
     */
    public function areCompatible(string $version1, string $version2): bool
    {
        if (stripos($version1, 'x') === false || stripos($version2, 'x') === false) {
            return true;
        }

        preg_match_all('/([0-9]*\.x)-/m', $version1, $matches1);
        preg_match_all('/([0-9]*\.x)-/m', $version2, $matches2);

        if (count($matches1[0]) > 0 && count($matches2[0]) > 0 && $matches1[1][0] !== $matches2[1][0]) {
            return false;
        }

        return true;
    }
}
