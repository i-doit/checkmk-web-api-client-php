# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased][]

### Added

-   Add support of PHP `8.0` and `8.1`

### Changed

-   **Breaking:** Change Composer package to `idoit/checkmkwebapiclient`
-   **Breaking:** Change PHP namespace to `Idoit\CheckmkWebAPIClient`
-   **Breaking:** Move repository to `github.com/i-doit/checkmk-web-api-client-php`
-   Recommend PHP `8.1`

### Deprecated

-   Deprecate support of PHP version `7.4`

### Removed

-   **Breaking:** Drop support of PHP `7.2` and `7.3`

## [0.6][] – 2020-10-22

### Changed

-   Mark PHP versions 7.2 as deprecated
-   Recommend PHP 7.4
-   Declare strict types
-   Require PHP extension `spl`
-   Re-name "Check_MK" to "Checkmk"

### Fixed

-   Allow empty objects in Python output (thanks to @skekec1)
-   Trim empty spaces at the beginning of each line in Python output
-   Do not send POST data from a previous API request

## Removed

-   Drop support for PHP version 5.6, 7.0 and 7.1

## [0.5][] – 2019-05-08

### Added

-   Configuration setting to disable security-related cURL options (boolean)

### Fixed

-   Encode parsed Python output into Unicode (UTF-8)

## [0.4][] – 2018-12-17

### Changed

-   `Host::getAll()`/`Host::get()`: Fetch all "effective" attributes from hosts by default
-   `Folder::get()`/`Folder::getAll()`: Switch from `output_format=python` to `output_format=json`

### Fixed

-   Python dictionary may contain floating number in tupel

## [0.3][] – 2018-04-25

### Added

-   Throw exception when parsing of "Python output" failed

### Fixed

-   "Python output" often causes problems because of tupels in Python dictionaries
-   Unsetting attributes without changes on other attributes caused an error (method `Host::edit()`)

## [0.2][] – 2018-02-02

### Added

-   Read hardware/software inventory data for hosts (see class `Inventory`)
-   Configure API entry point for each call by passing it to `API::request()`

### Changed

-   Switched configuration setting for URL to base URL; remove entry point `webapi.py` from your code; this is done automatically

### Fixed

-   PHP error while activating changes on all sites
-   Broken unit test for activating changes

## 0.1 – 2018-01-16

Initial release

### Added

-   Create, read, update and delete hosts
-   Discover services on hosts
-   Create, read, update and delete sites
-   Create, read, update and delete folders
-   Create, read, update and delete host groups, service groups, contact groups
-   Read and overwrite host tag groups and auxiliary tags
-   Create, read and delete users
-   Read rulesets
-   Bake agents
-   Activate changes
-   Get metrics

[Unreleased]: https://github.com/i-doit/checkmk-web-api-client-php/compare/0.6...HEAD
[0.6]: https://github.com/i-doit/checkmk-web-api-client-php/compare/0.5...0.6
[0.5]: https://github.com/i-doit/checkmk-web-api-client-php/compare/0.4...0.5
[0.4]: https://github.com/i-doit/checkmk-web-api-client-php/compare/0.3...0.4
[0.3]: https://github.com/i-doit/checkmk-web-api-client-php/compare/0.2...0.3
[0.2]: https://github.com/i-doit/checkmk-web-api-client-php/compare/0.1...0.2
