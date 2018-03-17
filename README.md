HETIC Administration Project
========================

**Build status:** [![Build Status](https://travis-ci.com/QRaimbault/hetic_administration_site.svg?token=FA1wAxykejW5sUvRGpAj&branch=master)](https://travis-ci.com/QRaimbault/hetic_administration_site)

Webapp for HETIC school built over [Symfony 4.0][3]

Requirements
------------

  * PHP 7.2 or higher;
  * Node.JS;
  * Yarn;
  * Composer;
  * MySQL database;
  * PDO PHP extension;
  * and the [usual Symfony application requirements][1].
  
Installation
------------

Clone the repository in a folder.

Create a copy of phpunit.xml.dist to phpunit.xml and configure database credentials and db name inside at the line :
```
<env name="DATABASE_URL" value="'mysql://db_user:db_password@127.0.0.1:3306/db_name'"/>
```

Create a copy of .env.dist to .env and configure the following lines (at least) :
```
APP_ENV=dev
APP_SECRET=544c2150ed525244567040561a3ca173
DATABASE_URL='mysql://db_user:db_password@127.0.0.1:3306/db_name'
```

Create a copy of .php_cs.dist to .php_cs, there should not be anything to configure inside.

Run these commands:

```bash
$ composer self-update
$ composer install
$ yarn install
$ yarn run encore production
$ ./bin/console doctrine:migration:migrate
```

Tests
-----

Tests are configured over travis but you can run it manually this way:

```bash
$ ./vendor/bin/simple-phpunit
```

If you want to fully verify the application including code style, config files, twig templates syntax, composer validation & known vulnerabilites:

```bash
$ ./vendor/bin/simple-phpunit
$ ./vendor/bin/php-cs-fixer fix --diff --dry-run -v
$ ./bin/console lint:yaml config
$ ./bin/console lint:twig templates
$ ./bin/console lint:xliff translations
$ ./bin/console security:check --end-point=http://security.sensiolabs.org/check_lock
$ composer validate --strict
```

Some documentation about web server configuration for symfony [here][2].

[1]: https://symfony.com/doc/current/reference/requirements.html
[2]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[3]: https://symfony.com/