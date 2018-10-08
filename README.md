# Important
To prevent showing errors from other coding standards (e.g. PEAR) and only use CakePHP, issue the command below:

```bash
/vendor/bin/phpcs --config-set default_standard CakePHP
```

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765

or use custom IP for host

bin/cake server -H 192.168.2.117 -p 8765
```
