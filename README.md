<img align="right" src="https://raw.githubusercontent.com/TechnicPack/TechnicSolder/master/public/img/500error2.png">

# TechnicSolder
> Supercharge Your Modpack with TechnicSolder

[![Build Status](https://travis-ci.org/Indemnity83/TechnicSolder.svg?branch=develop)](https://travis-ci.org/Indemnity83/TechnicSolder)
[![Code Coverage](https://scrutinizer-ci.com/g/Indemnity83/TechnicSolder/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Indemnity83/TechnicSolder/?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Indemnity83/TechnicSolder/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Indemnity83/TechnicSolder/?branch=develop)
[![StyleCI](https://styleci.io/repos/32042637/shield?branch=develop)](https://styleci.io/repos/32042637)

Solder helps you lower bandwidth usage, keep old versions of your modpack available to users and provides faster, streamlined updates. You won't know how you managed a modpack before you used Solder.

## Installing / Getting started

If you're interested in getting checking out the application locally, or helping out with development; the below series of commands should get you started.

```shell
git clone https://github.com/indemnity83/technicsolder
cd technicsolder
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan serve
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Kyle Klaus](https://github.com/indemnity83)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
