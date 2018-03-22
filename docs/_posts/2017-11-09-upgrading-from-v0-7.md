---
date: 2017-11-09 21:17:00
title: Upgrading from TechnicSolder
categories:
  - Installation
description:
type: Document
---


*note that the current version of solder is very early alpha and under active development, so its critical you have a solid backup before doing anything! There is no downgrade option if there are any issues encountered, and there is a strong possibility of breaking changes in future releases!*

**This guide IS NOT intended for casual users, this is intended for power users and developers to do testing and troubleshooting.**

## Known Limitations[#](#known-limitations){: .header-link}

* Upgrades are only supported for MySQL backed installs. SQLite is not yet supported.
* IP logging on User account updates (created by IP, updated by IP, etc) will be dropped.
* You must be able to upgrade PHP on your server to version 7.1 or newer. If you’re running any other services or applications with active users, it is safest to first test this process in a staging environment.

## Maintenance Mode[#](#maintenance-mode){: .header-link}

Put the Solder application into maintenance mode.

<div class="language-shell highlighter-rouge"><pre class="highlight"><code><span class="gp">$&gt; </span>php artisan down
</code></pre></div>

## Upgrade PHP version[#](#upgrade-php-version){: .header-link}

This part generally goes outside the scope of this document, as its dependent on your operating system, and may impact more than just Solder. But this is necessary to complete the upgrade in-place. Digital Ocean provides fairly thorough guides for common operating systems:

* [How To Upgrade to PHP 7 on Ubuntu 14.04 - DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-upgrade-to-php-7-on-ubuntu-14-04)
* [How To Upgrade to PHP 7 on CentOS 7 - DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-upgrade-to-php-7-on-centos-7)

## Update application code[#](#update-application-code){: .header-link}

Because Solder shares no development history with TechnicSolder we’re going to have to redirect our current clone to the Solder repository then rebase the code on the latest branch:

<div class="language-shell highlighter-rouge"><pre class="highlight"><code><span class="gp">$&gt; </span>git remote <span class="nb">set</span>-url origin https://github.com/technicpack/solder
<span class="gp">$&gt; </span>git fetch
<span class="gp">$&gt; </span>git pull --rebase
</code></pre></div>

Its important to note that any files you’ve stored in your `public/resources`{: .highlighter-rouge} path will be kept and re-linked during the upgrade process. However, if you’ve modified the directory structure or stored content outside of the standard resources folder you may receive errors. Reach out for support in the Discord server if you run into trouble with this upgrade.

Next, update all the application dependencies using composer. This process may take a few minutes to pull everything in, and will shed light on anything you might need to tweak about your server to make it compatible with Solder.

<div class="language-shell highlighter-rouge"><pre class="highlight"><code><span class="gp">$&gt; </span>composer install --no-dev
</code></pre></div>

## Configuration[#](#configuration){: .header-link}

Solder stores all configuration parameters as environmental variables and supports using a&nbsp;`.env`{: .highlighter-rouge} file for local configuration. Copy&nbsp;`.env.example`{: .highlighter-rouge} and open up the `.env`{: .highlighter-rouge} file in your favorite editor to configure the mysql database (*hint: you can look in app/config/database.php to get your current mysql parameters*)

<div class="language-shell highlighter-rouge"><pre class="highlight"><code><span class="gp">$&gt; </span>cp .env.example .env
$&gt; vi .env                      # Use your favorite editor to set db configs
</code></pre></div>

## Pull in and run the upgrade[#](#pull-in-and-run-the-upgrade){: .header-link}

The upgrade script is a separate package which will make sure your current TechnicSolder v0.7 database is up-to-date and then massage the data into the correct format for Solder. All this is done with a single command to keep things simple and once the process is complete you can remove the package (no remaining bloat).

<div class="language-shell highlighter-rouge"><pre class="highlight"><code><span class="gp">$&gt; </span>composer require solderio/upgrade
<span class="gp">$&gt; </span>php artisan solder:upgrade
</code></pre></div>

Finally, bring Solder back online for users

<div class="language-shell highlighter-rouge"><pre class="highlight"><code><span class="gp">$&gt; </span>php artisan up
</code></pre></div>
