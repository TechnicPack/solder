# TechnicPack Solder

[![CircleCI](https://circleci.com/gh/TechnicPack/solder.svg?style=shield)](https://circleci.com/gh/TechnicPack/solder) [![Coverage Status](https://coveralls.io/repos/github/TechnicPack/solder/badge.svg?branch=develop)](https://coveralls.io/github/TechnicPack/solder?branch=develop) [![StyleCI](https://styleci.io/repos/32042637/shield?branch=develop)](https://styleci.io/repos/32042637)

Supercharge Your Modpack with Solder. Build, and maintain multiple versions of modpacks and manage all required 
downloads in one place.

## Getting Started 

These instructions will get you a locally running version of solder for development and testing purposes. Its important 
to note that this application is still in early development and updates may break functionality or require special
upgrade steps. Do not use this in production. 

### Prerequisites
To run this project, you must have PHP 7.1.3 or newer, composer and npm installed. You will also probably want to have a web server running to serve the site (although this isn't strictly required for testing).

### Setup

Begin by cloning this repository to your machine, and installing all dependencies.

```bash
$ git clone https://github.com/solderio/solder.git
$ cd solder && composer install --no-dev && npm install --only=production
$ npm run production
$ php artisan solder:install
```

Next, boot up your webserver and visit your application. If using a tool like Laravel Valet, of course the URL will 
default to `http://solder.test`. If you aren't using a webserver you can start up a php webserver with the `php artisan 
serve` command.  

The default username is `admin@example.com` and the default password is `secret`

### Upgrading

Begin by placing the application in maintenance mode, updating the repository and all dependencies. 

```bash
$ php artisan down
$ git fetch && git pull
$ composer install --no-dev && npm install --only=production
```

Finally, rebuild any generated resources, migrate the database and put the application back online

```bash
$ npm run production
$ php artisan migrate --force
$ php artisan up
```

## Running the tests

Its important that the project maintain very high test coverage to ensure that changes to the code don't break any expected behavior from the API. This API is called on nearly every time a user runs the TechnicPack Launcher, its an invisible part of what makes Technic work, and we want to keep it invisible to the day-to-day user.

### PHPUnit Feature and Unit tests

A majority of the testing is done in feature tests which test the expected output of API endpoints under various 
conditions and with various inputs. You can run the full suite of unit and feature tests with PHPUnit.

```bash
$ vendor/bin/phpunit
```

### Code style tests

Code style is also very important to us, a consistent code style makes the project easier to maintain and the pre-
defined rules for how code should look lets everyone focus on function over form. Any push or PR will be checked by 
StyleCI before being merged. In order to reduce the number of commits, a local config and tool are included to allow 
you to run a fixer on your code before pushing it up to github.

```bash
$ vendor/bin/php-cs-fixer fix -v
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this 
repository](https://github.com/technicpack/solder/tags).

## Contributing

Please read [CONTRIBUTING.md](https://github.com/technicpack/solder/CONTRIBUTING.md) for details on our code of conduct, 
and the process for submitting pull requests to us.

## Authors

* **Kyle Klaus** - *Initial work* - [Indemnity83](https://github.com/indemnity83)

See also the list of [contributors](https://github.com/technicpack/solder/contributors) who participated in this 
project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
