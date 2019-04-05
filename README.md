# eCurring API client library for PHP

This is an unofficial library which provides PHP bindings for the eCurring API. 

## Links

- [eCurring Home](https://www.ecurring.com/)
- [eCurring API Documentation](https://docs.ecurring.com/)

## Requirements

In order to use this library, you need:
- An active eCurring account and API key.
- PHP >= 7.2

## Installation

```bash
composer require marissen/ecurring-api-php
```

## Getting started

Initializing the eCurring client

```php
use Marissen\eCurring\eCurringHttpClient;

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

- Implement [Transactions API](https://docs.ecurring.com/transactions/create)
- Implement [Included resources](https://docs.ecurring.com/includes)
- Implement [Sparse Fieldsets](https://docs.ecurring.com/sparse-fieldsets)
- Implement resource relationships
