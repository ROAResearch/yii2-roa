Yii2 ROA Library
=======================

This library contains the modules and controllers to build a ROA application
using the Yii2 framework.

[![Latest Stable Version](https://poser.pugx.org/roaresearch/yii2-roa/v/stable)](https://packagist.org/packages/roaresearch/yii2-roa)
[![Total Downloads](https://poser.pugx.org/roaresearch/yii2-roa/downloads)](https://packagist.org/packages/roaresearch/yii2-roa)
[![Code Coverage](https://scrutinizer-ci.com/g/roaresearch/yii2-roa/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/roaresearch/yii2-roa/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/roaresearch/yii2-roa/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/roaresearch/yii2-roa/?branch=master)

Scrutinizer [![Build Status Scrutinizer](https://scrutinizer-ci.com/g/roaresearch/yii2-roa/badges/build.png?b=master&style=flat)](https://scrutinizer-ci.com/g/roaresearch/yii2-roa/build-status/master)
Travis [![Build Status Travis](https://travis-ci.org/roaresearch/yii2-roa.svg?branch=master&style=flat?style=for-the-badge)](https://travis-ci.org/roaresearch/yii2-roa)

### Prerequisites

- Install PHP 7.1 or higher
- [Composer Installed](https://getcomposer.org/doc/00-intro.md)
- Run command `composer check-platform-reqs` to check all requirements.

### Installation
----------------

Install on a Yii2 App Advanced Project

[Create new project](https://github.com/ROAResearch/yii2-app-roa/blob/master/docs/guide/start-installation.md)

## Running the tests

This section is to run the tests on this library, to run the tests on your
application check [Yii2 App Roa Tests](https://github.com/ROAResearch/yii2-app-roa/blob/master/docs/guide/start-testing.md)

### Configure tests

The tests come preconfigured except for the database credentials, to configure
your database credentials create a file `tests/_app/config/db.local.php` with
the Yii2 configuration for `Yii::$app->db` component. Example:

```php
return [
    'dsn' => ..., 
    'username' => ..., 
    'password' => ..., 
];
```

Make sure to create a database to load the migrations, by default the name is
`yii2_roa_test`

### Deploy tests

This library includes a composer script to deploy the tests.

`composer deploy-tests`

### Run tests

This library also includes 2 composer scripts to run the tests easily.

- `composer run-tests` run all codeception tests.
- `composer run-coverage` run all codeception tests and generate coverage report

### Write Tests

You can write new tests on the `tests/` folder following
[codeception documentation](https://codeception.com/docs/)

## Examples

### Yii2 ROA Live Demo

You can run a live demo on a freshly installed project to help you run testing
or understand the responses returned by the server.

`composer yii -- serve [yii2Options]`

See https://www.yiiframework.com/doc/api/2.0/yii-console-controllers-servecontroller

Then on your browser access the route `http://localhost:8080/index.php/api`

### Other Libraries

You can clone the following repositories and use the live demo they provide

- [yii2-formgenerator](https://github.com/ROAResearch/yii2-formgenerator)
- [yii2-workflow](https://github.com/ROAResearch/yii2-workflow)

## Use Cases

TO DO

## Built With

* Yii 2: The Fast, Secure and Professional PHP Framework [http://www.yiiframework.com](http://www.yiiframework.com)

## Code of Conduct

Please read [CODE_OF_CONDUCT.md](https://github.com/ROAResearch/yii2-formgenerator/blob/master/CODE_OF_CONDUCT.md) for details on our code of conduct.

## Contributing

Please read [CONTRIBUTING.md](https://github.com/ROAResearch/yii2-roa/blob/master/CONTRIBUTING.md) for details on the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/ROAResearch/yii2-roa/tags).

_Considering [SemVer](http://semver.org/) for versioning rules 9, 10 and 11 talk about pre-releases, they will not be used within the ROAResearch._

## Authors

* [**Angel Guevara**](https://github.com/Faryshta) - Initial work
* [**Carlos Llamosas**](https://github.com/neverabe) - Initial work

See also the list of [contributors](https://github.com/ROAResearch/yii2-roa/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

