<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Normalizers;

use Patchstack\VersionCompare\Contracts\VersionNormalizer;

final class GenericNormalizer implements VersionNormalizer
{
    public function normalize(string $version): string
    {
        $version = strtolower(trim($version));

        if (str_starts_with($version, 'v')) {
            $version = substr($version, 1);
        }

        return $version;
    }

    public function areCompatible(string $version1, string $version2): bool
    {
        return true;
    }
}
