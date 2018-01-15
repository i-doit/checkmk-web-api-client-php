#   Check_MK Web API Client

Easy-to-use, but feature-rich client for Check_MK Web API

[![Latest Stable Version](https://img.shields.io/packagist/v/bheisig/checkmkwebapi.svg)](https://packagist.org/packages/bheisig/checkmkwebapi)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)


##  About

[Check_MK](https://mathias-kettner.de/check_mk.html) is a software application for network monitoring. The community edition ("raw") is licensed under the GPLv2.

This client communicates with Check_MK over its Web API. It provides a simple, but powerful abstraction layer for written in PHP. Feel free to use it as a library in your own projects.


##  Requirements

Meet these simple requirements before using the client:

*   One or more Check_MK sites, version 1.4 or higher (most calls only work with 1.5)
*   PHP, version 5.6 or higher (7.1 is recommended)
*   PHP modules `curl` and `json`


##  Installation

It is recommended to install this client via [Composer](https://getcomposer.org/). Change to your project's root directory and fetch the latest stable version:

~~~ {.bash}
composer require "bheisig/checkmkwebapi=>=0.1"
~~~

For a system-wide installation add `global` as an option:

~~~ {.bash}
composer global require "bheisig/checkmkwebapi=>=0.1"
~~~

As an alternative add a new dependency on `bheisig/idoitapi` to your project's `composer.json` file. Here is a minimal example to install the current development branch locally:

~~~ {.json}
{
    "require": {
        "bheisig/checkmkwebapi": ">=0.1"
    }
}
~~~

After that you need to call Composer to install this client (under `vendor/bheisig/checkmkwebapi/` by default):

~~~ {.bash}
composer install
~~~

This command installs version `0.1` or higher if available. Instead of sticking to a specific/minimum version you may switch to the current development branch by using `@DEV`:

~~~ {.bash}
composer require "bheisig/checkmkwebapi=@DEV"
composer install
~~~


##  Updates

Composer has the great advantage (besides many others) that you can simply update the client by running:

~~~ {.bash}
composer update
~~~


##  Usage

Composer comes with its own autoloader. Include this line into your PHP code:

~~~ {.php}
require_once 'vendor/autoload.php';
~~~

This is it. All other files will be auto-loaded on-the-fly if needed.


##  First call

This is a simple "Hello, world!" example. It fetches all configured hosts from Check_MK:

~~~ {.php}
use bheisig\checkmkwebapi\API;
use bheisig\checkmkwebapi\Config;
use bheisig\checkmkwebapi\Host;

$config = new Config();
$config
    ->setURL('https://monitoring.example.org/mysite/check_mk/webapi.py')
    ->setUsername('automation')
    ->setSecret('abc123')

$api = new API($config);

$request = new Host($api);
$hosts = $request->getAll();

var_dump($hosts);
~~~


##  Configuration

The `API` class requires configuration settings passed to its constructor:

~~~ {.php}
use bheisig\checkmkwebapi\API;
use bheisig\checkmkwebapi\Config;

$config = new Config();
$config
    ->setURL('https://monitoring.example.org/mysite/check_mk/webapi.py')
    ->setPort(443)
    ->setUsername('automation')
    ->setSecret('abc123')
    ->enableProxy()
    //->disableProxy()
        ->useHTTPProxy()
        //->useSOCKS5Proxy()
        ->setProxyHost('proxy.example.net')
        ->setProxyPort(8080)
        ->setProxyUsername('proxyuser')
        ->setProxyPassword('verysecure');

$api = new API($config);
~~~

The `Config` class has public methods which must be called to configure the API:

| Setting               | Parameter | Required  | Description                                                                                           |
| --------------------- | --------- | --------- | ----------------------------------------------------------------------------------------------------- |
| `setURL()`            | string    | yes       | URL to Check_MK Web API, probably the base URL appended by `webapi.py`                                |
| `setPort()`           | integer   | no        | Port on which the Web server listens; if not set port `80` will be used for HTTP and `443` for HTTPS  |
| `setUsername()`       | string    | yes       | User for authentication, probably `automation`                                                        |
| `setSecret()`         | string    | yes       | Secret specified for user                                                                             |
| `enableProxy()`       | –         | no        | Use a proxy between client and server; see below for details                                          |

Optional proxy settings:

| Setting               | Parameter | Required  | Description                                   |
| --------------------- | --------- | --------- | --------------------------------------------- |
| `disableProxy()`      | boolean   | no        | Disable proxy settings; this is the default   |
| `useHTTPProxy()`      | –         | yes       | Use a HTTP(S) proxy                           |
| `useSOCKS5Proxy()`    | –         | yes       | Use a SOCKS5 proxy                            |
| `setProxyHost()`      | string    | yes       | FQDN or IP address to proxy                   |
| `setProxyPort()`      | integer   | yes       | port on which the proxy server listens        |
| `setProxyUsername()`  | string    | no        | Authenticate against proxy                    |
| `setProxyPassword()`  | string    | no        | Specified password for authentication         |


##  Hosts

Class `Host` with public methods:

| API Call              | Class Method          | Description                                                               |
| --------------------- | --------------------- | ------------------------------------------------------------------------- |
| `get_host`            | `get()`               | Read information about a host by its hostname                             |
| `get_all_hosts`       | `getAll()`            | Read information about all hosts                                          |
| `add_host`            | `add()`               | Create new host with some attributes and tags                             |
| `edit_host`           | `edit()`              | Edit host, adds new attributes, changes attributes, or unsets attributes  |
| `delete_host`         | `delete()`            | Delete a host by its hostname                                             |
| `discover_services`   | `discoverServices()`  | Discover services of a host                                               |


##  Sites

Class `Site` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_site`            | `get()`               | Read information about a site by its identifier                   |
| –                     | `getAll()`            | Read information about all sites                                  |


##  Folders

Class `Folder` with pulic methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_folder`          | `get()`               | Read information about a folder by its path                       |
| `get_all_folders`     | `getAll()`            | Read information about all folders                                |
| `add_folder`          | `add()`               | Create new folder with some attributes                            |
| `edit_folder`         | `edit()`              | Edit a folder's attributes                                        |
| `delete_folder`       | `delete()`            | Delete a folder by its path                                       |


##  Groups

Classes `HostGroup`, `ServiceGroup` and `ContactGroup` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_all_*groups`     | `getAll()`            | Read information about all groups                                 |
| –                     | `get()`               | Read information about a group by its name                        |
| `add_*group`          | `add()`               | Create new group with name and alias                              |
| `edit_*group`         | `edit()`              | Change the alias of a group                                       |
| `delete_*group`       | `delete()`            | Delete contact group by its name                                  |


##  Rulesets

Class `Ruleset` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_ruleset`         | `get()`               | Read information about a ruleset by its name                      |
| `get_rulesets_info`   | `getAll()`            | Read information about all rulesets                               |


##  Agents

Class `Agent` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `bake_agents`         | `bake()`              | Bake agents but not sign them                                     |


##  Activate Changes

Class `Change` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `activate_changes`    | `activate()`          | Activate changes on specific sites                                |
| –                     | `activateEverywhere`  | Activate changes on all sites                                     |


##  Contribute

Please, report any issues to [our issue tracker](https://github.com/bheisig/check_mk-web-api/issues). Pull requests are very welcomed. If you like to get involved see file [`Contribute.md`](Contribute.md) for details.


##  Copyright & License

Copyright (C) 2018 [Benjamin Heisig](https://benjamin.heisig.name/)

Licensed under the [GNU Affero GPL version 3 or later (AGPLv3+)](https://gnu.org/licenses/agpl.html). This is free software: you are free to change and redistribute it. There is NO WARRANTY, to the extent permitted by law.
