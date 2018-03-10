# Solder [![Build Status](https://travis-ci.org/solderio/solder.svg?branch=master)](https://travis-ci.org/solderio/solder) [![Style](https://styleci.io/repos/32042637/shield?style=flat&branch=master)](https://styleci.io/repos/32042637) [![Maintainability](https://api.codeclimate.com/v1/badges/583fba700663d63e4b6c/maintainability)](https://codeclimate.com/github/solderio/solder/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/583fba700663d63e4b6c/test_coverage)](https://codeclimate.com/github/solderio/solder/test_coverage)

Supercharge Your Modpack with Solder. Build, and maintain multiple versions of modpacks and manage all required downloads in one place. 

## Installation

### Step 1

> To run this project, you must have PHP 7, composer and npm installed as a prerequisites.

Begin by cloning this repository to your machine, and installing all dependencies.

```bash
git clone https://github.com/solderio/solder.git
cd solder && composer install --no-dev && npm install --only=production
npm run production
php artisan solder:install
```

If this is your first time using Solder (eg. a new installation), do make sure to run the migration when asked.

### Step 2

Next, boot up a server and visit your application. If using a tool like Laravel Valet, of course the URL will default to `http://solder.test`. 

1. Visit: `http://solder.test/login`. The default username is `admin@example.com` and the default password is `secret`

## Upgrade

### Step 1

Begin by placing the application in maintenance mode, updating the repository and all dependencies. 

```bash
php artisan down
get fetch && git pull
composer install --no-dev && npm install --only=production
```

### Step 2

Finally, rebuild any generated resources, migrate the database and put the application back online

```bash
npm run production
php artisan migrate --force
php artisan up
```
