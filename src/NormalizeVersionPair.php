<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare;

final class NormalizeVersionPair
{
    /**
     * Normalize two version strings by right-padding numeric segments to equal length.
     *
     * This ensures decimal-style versioning compares correctly:
     * e.g., "3.5" vs "3.41" becomes "3.50" vs "3.41".
     *
     * @return array{string, string}
     */
    public function execute(string $version1, string $version2): array
    {
        $segments1 = explode('.', $version1);
        $segments2 = explode('.', $version2);

        $maxSegments = max(count($segments1), count($segments2));

        $segments1 = array_pad($segments1, $maxSegments, '0');
        $segments2 = array_pad($segments2, $maxSegments, '0');

        for ($i = 0; $i < $maxSegments; $i++) {
            if (! ctype_digit($segments1[$i]) || ! ctype_digit($segments2[$i])) {
                continue;
            }

            $maxLength = max(strlen($segments1[$i]), strlen($segments2[$i]));
            $segments1[$i] = str_pad($segments1[$i], $maxLength, '0', STR_PAD_RIGHT);
            $segments2[$i] = str_pad($segments2[$i], $maxLength, '0', STR_PAD_RIGHT);
        }

        return [
            implode('.', $segments1),
            implode('.', $segments2),
        ];
    }
}
