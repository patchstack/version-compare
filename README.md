# patchstack/version-compare

Framework-agnostic version comparison library for vulnerability checking. Handles the inconsistent versioning found in WordPress, Drupal, and other ecosystems where PHP's native `version_compare()` produces incorrect results.

## The Problem

PHP's `version_compare('3.5', '3.41')` returns `-1` (meaning 3.5 < 3.41), because it compares segment-by-segment as strings. But in many WordPress plugins, `3.5` means `3.50` and should be greater than `3.41`. This causes false positives in vulnerability scanners.

## Installation

```bash
composer require patchstack/version-compare
```

For private repo access, add the VCS repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:patchstack/version-compare.git"
        }
    ]
}
```

## Usage

### Basic Version Comparison

```php
use Patchstack\VersionCompare\CompareVersions;
use Patchstack\VersionCompare\NormalizeVersionPair;
use Patchstack\VersionCompare\VersionStrategy;

$compare = new CompareVersions(new NormalizeVersionPair());

// Standard PHP behaviour (default)
$compare->execute('3.5', '3.41', '<'); // true (PHP native)

// Decimal-normalized strategy (the fix)
$compare->execute('3.5', '3.41', '<', VersionStrategy::DecimalNormalized); // false
```

### Checking Vulnerability affected_in Strings

```php
use Patchstack\VersionCompare\CheckVersionVulnerability;
use Patchstack\VersionCompare\CompareVersions;
use Patchstack\VersionCompare\NormalizeVersionPair;
use Patchstack\VersionCompare\VersionStrategy;

$checker = new CheckVersionVulnerability(
    new CompareVersions(new NormalizeVersionPair())
);

// Supports: *, <= X, < X, comma-separated, ranges (X-Y), exact match
$checker->execute('3.40', '<= 3.41');                                        // true
$checker->execute('3.5', '<= 3.41', VersionStrategy::DecimalNormalized);     // false
$checker->execute('1.5', '1.0, 1.5, 2.0');                                  // true
$checker->execute('1.5', '1.0-2.0');                                         // true
$checker->execute('1.0', '*');                                               // true
```

### Strategy Selection

Use `VersionStrategy::fromAbnormalFlag()` to select the strategy from a database flag:

```php
use Patchstack\VersionCompare\VersionStrategy;

$strategy = VersionStrategy::fromAbnormalFlag($vulnerability->abnormal_version);
// Returns DecimalNormalized when true, Standard when false
```

### Drupal Version Handling

Drupal uses major version prefixes like `8.x-` and `9.x-` that need special handling:

```php
use Patchstack\VersionCompare\CompareVersions;
use Patchstack\VersionCompare\Drupal\CheckDrupalVersionVulnerability;
use Patchstack\VersionCompare\Drupal\DrupalVersionNormalizer;
use Patchstack\VersionCompare\NormalizeVersionPair;

$checker = new CheckDrupalVersionVulnerability(
    new CompareVersions(new NormalizeVersionPair()),
    new DrupalVersionNormalizer(),
);

$checker->execute('8.x-3.4', '<= 8.x-3.5');  // true
$checker->execute('9.x-3.4', '<= 8.x-3.5');  // false (different major)
```

## Supported Version Formats

| Format | Example | Notes |
|---|---|---|
| Semver | `1.2.3` | Standard dotted versions |
| Comparison operators | `<= 3.41`, `< 2.0` | With or without spaces |
| Comma-separated | `1.0, 1.5, 2.0` | Exact match list |
| Ranges | `1.0-2.0` | Inclusive on both ends |
| Wildcards | `*` | All versions affected |
| Date-based | `20251210` | Common in some WP plugins |
| Suffixed | `5.0.37.decaf` | Non-numeric segments preserved |
| Pre-release | `4.5.5-beta` | Works with version_compare |
| Revision | `6.3-revision-0` | Treated as version segments |
| Drupal prefixed | `8.x-3.5`, `9.x-1.0` | Major version prefix handling |

## Classes

| Class | Purpose |
|---|---|
| `CompareVersions` | Wraps `version_compare()` with optional decimal normalization |
| `NormalizeVersionPair` | Right-pads numeric segments so `3.5` becomes `3.50` when compared to `3.41` |
| `CheckVersionVulnerability` | Parses `affected_in` specification strings |
| `VersionStrategy` | Enum: `Standard` (PHP native) or `DecimalNormalized` |
| `Drupal\DrupalVersionNormalizer` | Strips `8.x-`/`9.x-` prefixes, validates major version matching |
| `Drupal\CheckDrupalVersionVulnerability` | Drupal-specific vulnerability checking with prefix handling |

## Testing

```bash
composer test
```

## Requirements

- PHP 8.1+
- No framework dependencies
