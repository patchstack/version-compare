<?php

declare(strict_types=1);

namespace Patchstack\VersionCompare\Comparators;

use Patchstack\VersionCompare\Contracts\VersionComparator;

/**
 * Compares versions by treating each dot-separated segment as a decimal number.
 *
 * PHP's version_compare treats "3.5" < "3.41" (string comparison: "5" < "41").
 * This comparator right-pads segments so "3.5" becomes "3.50", making 3.50 > 3.41.
 */
final class DecimalComparator implements VersionComparator
{
    public function compare(string $version1, string $version2, string $operator): bool
    {
        [$version1, $version2] = $this->padSegments($version1, $version2);

        return version_compare($version1, $version2, $operator);
    }

    /**
     * @return array{string, string}
     */
    private function padSegments(string $version1, string $version2): array
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
