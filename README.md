# Checkmk Web API Client

Easy-to-use, but feature-rich client for Checkmk Web API

[![Latest Stable Version](https://img.shields.io/packagist/v/idoit/checkmkwebapiclient.svg)](https://packagist.org/packages/idoit/checkmkwebapiclient)
[![Minimum PHP Version](https://img.shields.io/badge/php-%5E7.4%7C%5E8.0-8892BF.svg)](https://php.net/)
[![Build status](https://github.com/i-doit/checkmk-web-api-client-php/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/i-doit/checkmk-web-api-client-php/actions)

**Please note: This project is not an official product by synetics GmbH. synetics GmbH doesn't provide any commercial support.**

## About

[Checkmk](https://checkmk.com/) is a software application for network monitoring. The community edition ("raw") is licensed under the GPLv2.

This client communicates with Checkmk over its Web API. It provides a simple, but powerful abstraction layer for written in PHP. Feel free to use it as a library in your own projects.

## Requirements

Meet these simple requirements before using the client:

-   One or more Checkmk sites, version `1.4` or higher (most calls work since `1.5`)
-   PHP, version `8.0` or higher (`8.1` is recommended, `7.4` should work but is deprecated)
-   PHP modules `curl`, `date`, `json`, `openssl`, `spl` and `zlib`

## Installation

It is recommended to install this client via [Composer](https://getcomposer.org/). Change to your project's root directory and fetch the latest stable version:

~~~ {.bash}
composer require idoit/checkmkwebapiclient
~~~

This command installs the latest stable version. Instead of sticking to a specific/minimum version you may switch to the current development branch by using `@DEV`:

~~~ {.bash}
composer require "idoit/checkmkwebapiclient=@DEV"
~~~

## Updates

Composer has the great advantage (besides many others) that you can simply update the client by running:

~~~ {.bash}
composer update
~~~

## Usage

Composer comes with its own autoloader. Include this line into your PHP code:

~~~ {.php}
require_once 'vendor/autoload.php';
~~~

This is it. All other files will be auto-loaded on-the-fly if needed.

## First call

This is a simple "Hello, world!" example. It fetches all configured hosts from Checkmk:

~~~ {.php}
use Idoit\CheckmkWebAPIClient\API;
use Idoit\CheckmkWebAPIClient\Config;
use Idoit\CheckmkWebAPIClient\Host;

$config = new Config();
$config
    ->setURL('https://monitoring.example.org/mysite/check_mk/')
    ->setUsername('automation')
    ->setSecret('abc123');

$api = new API($config);

$request = new Host($api);
$hosts = $request->getAll();

var_dump($hosts);
~~~

## Configuration

The `API` class requires configuration settings passed to its constructor:

~~~ {.php}
use Idoit\CheckmkWebAPIClient\API;
use Idoit\CheckmkWebAPIClient\Config;

$config = new Config();
$config
    ->setURL('https://monitoring.example.org/mysite/check_mk/')
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
| `setURL()`            | string    | yes       | URL to Checkmk *without* entry point, for example `https://monitoring.example.com/mysite/check_mk/`   |
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

## Hosts

Class `Host` with public methods:

| API Call              | Class Method          | Description                                                               |
| --------------------- | --------------------- | ------------------------------------------------------------------------- |
| `get_host`            | `get()`               | Read information about a host by its hostname                             |
| `get_all_hosts`       | `getAll()`            | Read information about all hosts                                          |
| `add_host`            | `add()`               | Create new host with some attributes and tags                             |
| `edit_host`           | `edit()`              | Edit host, adds new attributes, changes attributes, or unsets attributes  |
| `delete_host`         | `delete()`            | Delete a host by its hostname                                             |
| `discover_services`   | `discoverServices()`  | Discover services of a host                                               |

## Sites

Class `Site` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_site`            | `get()`               | Read information about a site by its identifier                   |
| –                     | `getAll()`            | Read information about all sites                                  |

## Folders

Class `Folder` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_folder`          | `get()`               | Read information about a folder by its path                       |
| `get_all_folders`     | `getAll()`            | Read information about all folders                                |
| `add_folder`          | `add()`               | Create new folder with some attributes                            |
| `edit_folder`         | `edit()`              | Edit a folder's attributes                                        |
| `delete_folder`       | `delete()`            | Delete a folder by its path                                       |

## Groups

Classes `HostGroup`, `ServiceGroup` and `ContactGroup` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_all_*groups`     | `getAll()`            | Read information about all groups                                 |
| –                     | `get()`               | Read information about a group by its name                        |
| `add_*group`          | `add()`               | Create new group with name and alias                              |
| `edit_*group`         | `edit()`              | Change the alias of a group                                       |
| `delete_*group`       | `delete()`            | Delete contact group by its name                                  |

## Host Tags

Class `HostTag` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_hosttags`        | `getAll()`            | Read information about all host tag groups and auxiliary tags     |
| `set_hosttags`        | `set()`               | Overwrite all host tag groups and auxiliary tags                  |

## Users

Class `Users` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| –                     | `get()`               | Read information about an user by its identifier                  |
| `get_all_users`       | `getAll()`            | Read information about all users                                  |
| –                     | `add()`               | Create new user with some attributes                              |
| `add_users`           | `batchAdd()`          | Create new users with some attributes                             |
| –                     | `delete()`            | Delete a user by its identifier                                   |
| `delete_users`        | `batchDelete()`       | Delete users by their identifiers                                 |

## Rulesets

Class `Ruleset` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_ruleset`         | `get()`               | Read information about a ruleset by its name                      |
| `get_rulesets_info`   | `getAll()`            | Read information about all rulesets                               |

## Agents

Class `Agent` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `bake_agents`         | `bake()`              | Bake agents but not sign them                                     |

## Activate Changes

Class `Change` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `activate_changes`    | `activate()`          | Activate changes on specific sites                                |
| –                     | `activateEverywhere`  | Activate changes on all sites                                     |

## Metrics

Class `Graph` with public method:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| `get_graph`           | `get()`               | Get metrics as a graph                                            |

## Inventory

Checkmk can collect various information about your hardware/software inventory.

Class `Inventory` with public methods:

| API Call              | Class Method          | Description                                                       |
| --------------------- | --------------------- | ------------------------------------------------------------------|
| -                     | `getHost()`           | Read hardware/software inventory data for a specific host         |
| -                     | `getHosts()`          | Read hardware/software inventory data for one or more hosts       |

## Contribute

Please, report any issues to [our issue tracker](https://github.com/i-doit/checkmk-web-api-client-php/issues). Pull requests are very welcomed. If you like to get involved see file [`CONTRIBUTING.md`](CONTRIBUTING.md) for details.

## Copyright & License

Copyright (C) 2022 [synetics GmbH](https://i-doit.com/)

Copyright (C) 2018-22 [Benjamin Heisig](https://benjamin.heisig.name/)

Licensed under the [GNU Affero GPL version 3 or later (AGPLv3+)](https://gnu.org/licenses/agpl.html). This is free software: you are free to change and redistribute it. There is NO WARRANTY, to the extent permitted by law.
