# Contribution

Thank you very much for your interest in this project! There are plenty of ways you can support us. :-)

## Code of Conduct

We like you to read and follow our [code of conduct](CODE_OF_CONDUCT.md) before contributing. Thank you.

## Use it

The best and (probably) easiest way to help is to use this library in your own projects. It would be very nice to share your thoughts with us. We love to hear from you.

If you have questions how to use it properly read the [documentation](README.md) carefully.

## Report bugs

If you find something strange please report it to [our issue tracker][].

## Report security issues

[Found a security-related issue?](SECURITY.md)

## Make a wish

Of course, there are some features in the pipeline. However, if you have good ideas how to improve this library please let us know! Write a feature request [in our issue tracker][issues].

## Setup a development environment

If you like to contribute source code, documentation snippets, self-explaining examples or other useful bits, fork this repository, setup the environment and make a pull request.

~~~ {.bash}
git clone https://github.com/i-doit/checkmk-web-api-client-php.git
~~~

If you have a GitHub account create a fork and then clone your own repository.

After that, setup the environment with Composer:

~~~ {.bash}
composer install
~~~

Now it is the time to do _your_ stuff. Do not forget to commit your changes. When you are done consider to make a pull requests.

Notice, that any of your contributions merged into this repository will be [licensed under the AGPLv3](LICENSE).

## Requirements

Developers must meet these requirements:

-   See requirements mentioned in the [documentation](README.md)
-   [Xdebug](https://xdebug.org/), needed for code coverage with phpunit
-   [Composer](https://getcomposer.org/)
-   [Git](https://git-scm.com/)

## Release new version

…and publish it to [packagist.org][packagist]. You need commit rights for this repository.

1.  Bump version: `composer config extra.version <VERSION>`
2.  Update composer: `composer update`
3.  Keep [`README.md`](README.md) and [`CHANGELOG.md`](CHANGELOG.md) up-to-date
4.  Commit changes: `git commit -a -m "Bump version to $(composer config extra.version)"`
5.  Perform some tests, for example `composer ci`
6.  Run unit tests: `composer phpunit`
7.  Create Git tag: `git tag -s -a -m "Release version $(composer config extra.version)" $(composer config extra.version)`
8.  Push changes: `git push --follow-tags`

There is already a webhook enabled to push the code from GitHub to Packagist.

## Composer scripts

This project comes with some useful composer scripts:

| Command                       | Description                                               |
| ----------------------------- | --------------------------------------------------------- |
| `composer ci`                 | Perform continuous integration tasks                      |
| `composer gitstats`           | Create Git statistics                                     |
| `composer gource`             | Visualize Git history                                     |
| `composer lint`               | Perform all lint checks                                   |
| `composer lint-php`           | Check syntax of PHP files                                 |
| `composer lint-json`          | Check syntax of JSON files                                |
| `composer lint-xml`           | Check syntax of XML files                                 |
| `composer lint-yaml`          | Check syntax of YAML files                                |
| `composer phpcompatibility`   | Run PHP compatibility checks                              |
| `composer phpcpd`             | Detect copy/paste in source code                          |
| `composer phpcs`              | Detect violations of defined coding standards             |
| `composer phploc`             | Print source code statistics                              |
| `composer phpmd`              | Detect mess in source code                                |
| `composer phpmnd`             | Detect magic numbers in source code                       |
| `composer phpstan`            | Analyze source code                                       |
| `composer phpunit`            | Perform unit tests                                        |
| `composer security-checker`   | Look for dependencies with known security vulnerabilities |
| `composer system-check`       | Run some system checks                                    |

For example, execute `composer ci`.

## Donate

If you think this project is useful for your daily work, consider a donation. What about a beer?

## Further reading

-   [How to Contribute to Open Source](https://opensource.guide/how-to-contribute/)

[issues]: https://github.com/i-doit/checkmk-web-api-client-php/issues
[packagist]: https://packagist.org/packages/idoit/checkmkwebapiclient
