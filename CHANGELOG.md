#   Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


##  [Unreleased]


### Added

-   Read hardware/software inventory data for hosts (see class `Inventory`)
-   Configure API entry point for each call by passing it to `API::request()`


### Changed

-   Switched configuration setting for URL to base URL; remove entry point `webapi.py` from your code; this is done automatically


### Fixed

-   PHP error while activating changes on all sites
-   Broken unit test for activating changes


##  0.1 – 2018-01-16

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


[Unreleased]: https://github.com/bheisig/checkmkwebapi/compare/0.1...HEAD
