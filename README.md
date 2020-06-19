# eCurring API client library for PHP

This is an unofficial library which provides PHP bindings for the eCurring API. 

[eCurring Home](https://www.ecurring.com/) | [eCurring API Documentation](https://docs.ecurring.com/)

This library is inspired by [Mollie API client for PHP](https://github.com/mollie/mollie-api-php). 

## Requirements

In order to use this library, you need:
- An active eCurring account and API key.
- PHP >= 7.2

## Installation

```bash
composer require mooore/ecurring-api-php
```

## Getting started

Initializing the eCurring client

```php
use Mooore\eCurring\eCurringHttpClient;

$client = new eCurringHttpClient();
$client->setApiKey('your_api_key');
```

Creating a customer

```php
$customer = $client->customers->create([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'example@domain.com'
]);
```

Creating a subscription from customer

```php
$customer = $client->customers->get(200);
$subscription = $customer->createSubscription(1);
```

Creating a subscription from subscription plan

```php
$subscriptionPlan = $client->subscriptionPlans->get(1);
$subscription = $subscriptionPlan->createSubscription(200);
```

Get all subscriptions

```php
$customers = $client->customers->page();
do {
    foreach ($customers as $customer) {
        if ($subscription->isActive()) {
            // do something
        }
    }
} while ($customers = $customers->next());
```

## Roadmap

- Implement [Included resources](https://docs.ecurring.com/includes)
- Implement [Sparse Fieldsets](https://docs.ecurring.com/sparse-fieldsets)
- Implement resource relationships
