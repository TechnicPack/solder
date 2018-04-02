# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
 - Added documentation (see /docs)
 - Added support for Teams
 
### Changed
 - Extracted Launcher API, and related token management to technicpack/launcher-api package
 
### Fixed
 - Running the solder:install command no longer causes an error about unknown 'name' parameter

### Upgrade Guide
These are additional instructions beyond the standard upgrade instructions in the readme. Please
read them carefully as failing to make these changes can render the application non-funcitonal. 

**Database Changes**
 - Add a nullable, unsigned integer column named `current_team_id` to the `users` table
 - Add an unsigned integer column named `team_id` to the `modpacks` table
 - Add an unsigned integer column named `team_id` to the `packages` table

## v1.0.0-alpha.1 - 2018-03-21
### Initial Release

[Unreleased]: https://github.com/technicpack/solder/compare/v1.0.0-alpha.1...HEAD
