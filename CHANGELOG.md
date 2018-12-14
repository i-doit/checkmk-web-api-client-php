#   Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


##  [Unreleased]

_tbd_


##  [0.4]


### Changed

-   `Host::getAll()`/`Host::get()`: Fetch all "effective" attributes from hosts by default
-   `Folder::get()`/`Folder::getAll()`: Switch from `output_format=python` to `output_format=json`


### Fixed

-   Python dictionary may contain floating number in tupel


##  [0.3] – 2018-04-25


### Added

-   Throw exception when parsing of "Python output" failed


### Fixed

-   "Python output" often causes problems because of tupels in Python dictionaries
-   Unsetting attributes without changes on other attributes caused an error (method `Host::edit()`) 


##  [0.2] – 2018-02-02


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


[Unreleased]: https://github.com/bheisig/checkmkwebapi/compare/0.4...HEAD
[0.4]: https://github.com/bheisig/checkmkwebapi/compare/0.3...0.4
[0.3]: https://github.com/bheisig/checkmkwebapi/compare/0.2...0.3
[0.2]: https://github.com/bheisig/checkmkwebapi/compare/0.1...0.2
