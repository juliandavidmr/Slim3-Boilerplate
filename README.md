# Slim3-Boilerplate

Boilerplate Slim 3 includes controllers, models, middleware and other utilities. Adapted for MySQL.

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install

Clone

```bash
git clone https://github.com/juliandavidmr/Slim3-Boilerplate.git
cd Slim3-Boilerplate
```

Install packages
```
composer install
// or => php composer.phar start
```

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can also run this command.

```bash
php composer.phar start
```

Run this command to run the test suite

```bash
php composer.phar test
```

That's it! Now go build something cool.

Run

```bash
php -S localhost:8080 -t public
```