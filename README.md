decARTe
=======

Online shop for wedding invitations & gadgets.

You can use this repository as a starting point for your own PHP/Symfony application.

## Main features

* PHP 7.3
* Symfony 5.4
* Docker
* Doctrine Migrations and Extensions
* Encore Webpack with Node.js 16
* Thumbnails with LiipImagineBundle
* Image upload with VichUploaderBundle
* Symfony Mailer, Mailcatcher
* Sentry
* PayU
* Google Merchant Center products export
* Schema.org for products and pages
* PHPUnit
* PHPStan

## Commands

* `make run` - start the application with Docker Compose
* `make stop` - stop all containers
* `make shell` - open the shell on the PHP container
* `make test`, `make phpcs`, `make phpstan` (after `make shell`) - tests and code analysis
