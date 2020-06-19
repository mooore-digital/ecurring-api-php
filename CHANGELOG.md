# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.4.0] - 2020-06-19
### Added
- Invoice and invoice lines API endpoint.
- Transactions API endpoint.
- Unit tests for all resources.
### Changed
- Resource type is now integrated in endpoint implementations.
### Fixed
- ID not present in payload for update subscription.
- PostalCode not set for Customer.

## [0.3.0] - 2019-11-27
### Changed
- Changed vendor name from Marissen to Mooore.

## [0.2.0] - 2019-09-05
### Added
- PHP version check to make sure we're running on the right version.

## [0.1.1] - 2019-04-25
### Added
- Passing extra attributes to Customer::createSubscription and SubscriptionPlan::createSubscription() is now possible.
### Changed
- Moved status values from inplace strings to class constants in resource Subscription.

## [0.1.0] - 2019-04-05
### Added
- Initial release.
