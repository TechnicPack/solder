# Solder [![Build Status](https://travis-ci.org/solderio/solder.svg?branch=master)](https://travis-ci.org/solderio/solder) [![Style](https://styleci.io/repos/32042637/shield?style=flat&branch=master)](https://styleci.io/repos/32042637)

Supercharge Your Modpack with Solder. Build, and maintain multiple versions of modpacks and manage all required downloads in one place. 

## Installation

### Step 1

> To run this project, you must have PHP 7 installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all dependencies.

```bash
git clone git@github.com:solderio/solder.git
cd solder && composer install --no-dev
```

### Step 2.

Next, create a new database and reference its name and username/password within the project's `.env` file. In the example below, we've named the database, "solder".

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=solder
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3

We need to populate the database, generate application keys and make the file storage folder available publicly.

```bash
php artisan key:generate
php artisan migrate --seed
php artisan passport:keys
php artisan storage:link
```

### Step 4

Finally, boot up a server and visit your application. If using a tool like Laravel Valet, of course the URL will default to `http://solder.test`. 

1. Visit: `http://solder.test/login`. The default username is `admin@example.com` and the default password is `secret`
