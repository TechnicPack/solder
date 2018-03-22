---
date: 2017-11-11 23:17:00
title: Installing Solder Docker
categories:
  - Installation
description:
type: Document
---

[Solder Docker](https://github.com/indemnity83/solder-docker) is a docker environment utilizing docker-compose to facilitate running Solder. The only pre-requisites are git, docker and docker-compose. Great if you want to just test it out locally, or even to deploy for production.

## Requirements
 * Git
 * Docker >= 1.12.0+

## Install Solder Docker

Installing Solder-Docker is as easy as cloning the solder-docker repository and running the install script docker-compose install container.

```
$> git clone https://github.com/indemnity83/solder-docker
$> cd solder-docker
$> docker-compose run --rm composer install --no-dev
$> docker-compose run --rm composer run-script post-root-package-install
```

The install container will run once and quit automatically. This container is responsible for pulling in any required dependencies and generally preparing the application.

Once you've installed the dependencies and set the application key, you can migrate the database and install the passport keys. A fully encapsulated container is provided for each function to keep the requirements list down.

```
$> docker-compose run --rm php php artisan key:generate
$> docker-compose run --rm php php artisan migrate --seed
$> docker-compose run --rm php php artisan passport:install
```

**Note:** If you see an error about `Connection Refused` don't panic, the very first time you run this command the mysql container will be spun up and can take a few seconds to create the database and start accepting connections. During that time you'll see this error.

_simply wait 30 seconds and try again_

## Starting and Stopping Solder Docker

Once you have completed all the installation steps you can startup the php, db and web servers all at once by running the `up` command (the `-d` flag will run the servers in the background).

```
$> docker-compose up -d
```

Because of a quirk of docker-compose, the install, migrate and passport containers will be started as well. This isn't an issue, they will see they have no work to do and exit.

Stopping all the containers is as easy as calling `stop`

```
$> docker-compose stop
```

## Upgrading Solder

The solder codebase is configured as a submodule of solder-docker. So updating to the latest commit of solder requires doing a submodule pull, then re-running `composer install` and `artisan migrate`.

```
$> git submodule update
$> docker-compose run --rm composer install --no-dev
$> docker-compose run --rm php php artisan migrate
```
