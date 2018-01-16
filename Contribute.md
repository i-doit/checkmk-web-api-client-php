#   Contribution

Thank you very much for your interest in this project! There are plenty of ways you can support us. :-)


##  Code of Conduct

We like you to read and follow our [code of conduct](CODE_OF_CONDUCT.md) before contributing. Thank you.


##  Use it

The best and (probably) easiest way is to use the API client library for your own projects. It would be very nice to share your thoughts with us. We love to hear from you.

If you have questions how to use it properly read the [documentation](README.md) carefully.


##  Report bugs

If you find something strange please report it to [our issue tracker](https://github.com/bheisig/checkmkwebapi/issues).


##  Make a wish

Of course, there are some features in the pipeline. However, if you have good ideas how to improve this project please let us know! Write a feature request [in our issue tracker](https://github.com/bheisig/checkmkwebapi/issues).


##  Setup a development environment

If you like to contribute source code, documentation snippets, self-explaining examples or other useful bits, fork this repository, setup the environment and make a pull request.

~~~ {.bash}
git clone https://github.com/bheisig/checkmkwebapi.git
~~~

If you have a GitHub account create a fork and then clone your own repository.

After that, setup the environment with Composer:

~~~ {.bash}
composer install
~~~

Now it is the time to do _your_ stuff. Do not forget to commit your changes. When you are done consider to make a pull requests.

Notice, that any of your contributions merged into this repository will be [licensed under the AGPLv3](LICENSE).


##  Requirements

This project has some dependencies for developers:

*   See requirements mentioned in the [documentation](README.md)
*   [Composer](https://getcomposer.org/)
*   [Git](https://git-scm.com/)
*   make


##  Run unit tests

Unit tests are located under `tests/`. Just call `make phpunit` to execute all of them.


##  Release new version

… and publish it to [packagist.org](https://packagist.org/packages/bheisig/checkmkwebapi):

*   Bump version in `composer.json`
*   Create a tag with

    `git tag -s -m "Release version <VERSION>" <VERSION>`
    
    `git push --tags`

There is already a webhook enabled to push the code from GitHub to packagist. This needs commit rights on this repository.


##  Make rules

This project comes with some [rules](Makefile) which will be used by `make`:

| Make rule     | Description                                                           |
| ------------- | --------------------------------------------------------------------- |
| `gitstats`    | Create a little website with Git statistics located under `gitstats`  |
| `gource`      | Visualize git commits                                                 |
| `phpdox`      | Create a source code documentation                                    |
| `phploc`      | Print source code statistics                                          |
| `phpunit`     | Run unit tests                                                        |

For example, execute `make gource`.


##  Donate

Last but not least, if you think this script is useful for your daily work, consider a donation. What about a beer?
