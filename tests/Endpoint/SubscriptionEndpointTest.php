<?php

namespace Mooore\eCurring\Endpoint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mooore\eCurring\Resource\Customer;
use Mooore\eCurring\Resource\Subscription;
use Mooore\eCurring\Resource\SubscriptionCollection;

class SubscriptionEndpointTest extends BaseEndpointTest
{
    public function testCreateSubscription()
    {
        $this->mockApiCall(
            new Request(
                'POST',
                '/subscriptions',
                [],
                '{
                    "data": {
                        "type": "subscription",
                        "attributes": {
                            "customer_id": 1,
                            "subscription_plan_id": 1
                        }
                    }
                }'
            ),
            new Response(
                201,
                [],
                '{
                      "links": {
                        "self": "https://api.ecurring.com/subscriptions/1"
                      },
                      "data": {
                        "type": "subscription",
                        "id": "1",
                        "links": {
                          "self": "https://api.ecurring.com/subscriptions/1"
                        },
                        "attributes": {
                          "mandate_code": "ECUR-1",
                          "mandate_accepted": false,
                          "mandate_accepted_date": null,
                          "start_date": "2017-02-11T22:11:57+01:00",
                          "status": "unverified",
                          "cancel_date": null,
                          "resume_date": null,
                          "confirmation_page": "https://app.ecurring.com/mandate/accept/1/ECUR-1",
                          "confirmation_sent": false,
                          "subscription_webhook_url": null,
                          "transaction_webhook_url": null,
                          "success_redirect_url": null,
                          "created_at": "2017-02-01T11:21:09+01:00",
                          "updated_at": "2017-02-11T00:00:00+01:00"
                        },
                        "relationships": {
                          "subscription-plan": {
                            "data": {
                              "type": "subscription-plan",
                              "id": "1"
                            }
                          },
                          "customer": {
                            "data": {
                              "type": "customer",
                              "id": "1"
                            }
                          },
                          "transactions": {
                            "links": {
                              "related": "https://api.ecurring.com/subscriptions/1/transactions"
                            },
                            "data": [
                              {
                                "type": "transaction",
                                "id": "02f3c67b-1e1a-4692-8826-14f17f9b2c61"
                              }
                            ]
                          }
                        }
                      }
                    }'
            )
        );

        $customer = new Customer($this->apiClient);
        $customer->id = 1;
        $subscription = $customer->createSubscription(1);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals("1", $subscription->id);
        $this->assertEquals('ECUR-1', $subscription->mandate_code);
        $this->assertFalse($subscription->mandate_accepted);
        $this->assertNull($subscription->mandate_accepted_date);
        $this->assertEquals(new \DateTime('2017-02-11T22:11:57+01:00'), $subscription->start_date);
        $this->assertEquals(Subscription::STATUS_UNVERIFIED, $subscription->status);
        $this->assertEquals('https://app.ecurring.com/mandate/accept/1/ECUR-1', $subscription->confirmation_page);
        $this->assertNull($subscription->subscription_webhook_url);
        $this->assertNull($subscription->transaction_webhook_url);
        $this->assertNull($subscription->success_redirect_url);
        $this->assertEquals(new \DateTime('2017-02-01T11:21:09+01:00'), $subscription->created_at);
        $this->assertEquals(new \DateTime('2017-02-11T00:00:00+01:00'), $subscription->updated_at);
    }

    public function testGetSubscription()
    {
        $this->mockApiCall(
            new Request('GET', '/subscriptions/1'),
            new Response(
                200,
                [],
                '{
                      "links": {
                        "self": "https://api.ecurring.com/subscriptions/1"
                      },
                      "data": {
                        "type": "subscription",
                        "id": "1",
                        "links": {
                          "self": "https://api.ecurring.com/subscriptions/1"
                        },
                        "attributes": {
                          "mandate_code": "ECUR-1",
                          "mandate_accepted": true,
                          "mandate_accepted_date": "2017-02-11T00:00:00+01:00",
                          "start_date": "2017-02-11T22:11:57+01:00",
                          "status": "active",
                          "cancel_date": null,
                          "resume_date": null,
                          "confirmation_page": "https://app.ecurring.com/mandate/accept/1/ECUR-1",
                          "confirmation_sent": false,
                          "subscription_webhook_url": null,
                          "transaction_webhook_url": null,
                          "success_redirect_url": null,
                          "created_at": "2017-02-01T11:21:09+01:00",
                          "updated_at": "2017-02-11T00:00:00+01:00"
                        },
                        "relationships": {
                          "subscription-plan": {
                            "data": {
                              "type": "subscription-plan",
                              "id": "1"
                            }
                          },
                          "customer": {
                            "data": {
                              "type": "customer",
                              "id": "1"
                            }
                          },
                          "transactions": {
                            "links": {
                              "related": "https://api.ecurring.com/subscriptions/1/transactions"
                            },
                            "data": [
                              {
                                "type": "transaction",
                                "id": "02f3c67b-1e1a-4692-8826-14f17f9b2c61"
                              }
                            ]
                          }
                        }
                      }
                    }'
            )
        );

        $subscription = $this->apiClient->subscriptions->get(1);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals("1", $subscription->id);
        $this->assertEquals('ECUR-1', $subscription->mandate_code);
        $this->assertTrue($subscription->mandate_accepted);
        $this->assertEquals(new \DateTime('2017-02-11T00:00:00+01:00'), $subscription->mandate_accepted_date);
        $this->assertEquals(new \DateTime('2017-02-11T22:11:57+01:00'), $subscription->start_date);
        $this->assertEquals(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertEquals('https://app.ecurring.com/mandate/accept/1/ECUR-1', $subscription->confirmation_page);
        $this->assertNull($subscription->subscription_webhook_url);
        $this->assertNull($subscription->transaction_webhook_url);
        $this->assertNull($subscription->success_redirect_url);
        $this->assertEquals(new \DateTime('2017-02-01T11:21:09+01:00'), $subscription->created_at);
        $this->assertEquals(new \DateTime('2017-02-11T00:00:00+01:00'), $subscription->updated_at);
        $this->assertEquals([
            'subscription-plan' => [
                'data' => [
                    'type' => 'subscription-plan',
                    'id' => '1',
                ],
            ],
            'customer' => [
                'data' => [
                    'type' => 'customer',
                    'id' => 1,
                ],
            ],
            'transactions' => [
                'links' => [
                    'related' => 'https://api.ecurring.com/subscriptions/1/transactions',
                ],
                'data' => [
                    [
                        'type' => 'transaction',
                        'id' => '02f3c67b-1e1a-4692-8826-14f17f9b2c61',
                    ],
                ],
            ],
        ], json_decode(json_encode($subscription->relationships), true));
    }

    public function testListSubscriptions()
    {
        $this->mockApiCall(
            new Request('GET', '/subscriptions?page[number]=1&page[size]=10'),
            new Response(
                200,
                [],
                '{
                      "meta": {
                        "total": 2
                      },
                      "links": {
                        "self": "https://api.ecurring.com/subscriptions?page[number]=1&page[size]=10",
                        "first": "https://api.ecurring.com/subscriptions?page[number]=1&page[size]=10",
                        "last": "https://api.ecurring.com/subscriptions?page[number]=85&page[size]=10",
                        "prev": null,
                        "next": null
                      },
                      "data": [
                        {
                          "type": "subscription",
                          "id": "1",
                          "links": {
                            "self": "https://api.ecurring.com/subscriptions/1"
                          },
                          "attributes": {
                            "mandate_code": "ECUR-1",
                            "mandate_accepted": true,
                            "mandate_accepted_date": "2017-02-11T00:00:00+01:00",
                            "start_date": "2017-02-11T22:11:57+01:00",
                            "status": "active",
                            "cancel_date": null,
                            "resume_date": null,
                            "confirmation_page": "https://app.ecurring.com/mandate/accept/1/ECUR-1",
                            "confirmation_sent": false,
                            "subscription_webhook_url": null,
                            "transaction_webhook_url": null,
                            "created_at": "2017-02-01T11:21:09+01:00",
                            "updated_at": "2017-02-11T00:00:00+01:00"
                          },
                          "relationships": {
                            "subscription-plan": {
                              "data": {
                                "type": "subscription-plan",
                                "id": "1"
                              }
                            },
                            "customer": {
                              "data": {
                                "type": "customer",
                                "id": "1"
                              }
                            },
                            "transactions": {
                              "links": {
                                "related": "https://api.ecurring.com/subscriptions/1/transactions"
                              },
                              "data": [
                                {
                                  "type": "transaction",
                                  "id": "02f3c67b-1e1a-4692-8826-14f17f9b2c61"
                                }
                              ]
                            }
                          }
                        }
                      ]
                    }'
            )
        );

        $subscriptions = $this->apiClient->subscriptions->page();

        $this->assertEquals(2, $subscriptions->count);
        $this->assertInstanceOf(SubscriptionCollection::class, $subscriptions);
        $this->assertFalse($subscriptions->hasNext());
        $this->assertFalse($subscriptions->hasPrevious());
        foreach ($subscriptions as $subscription) {
            $this->assertInstanceOf(Subscription::class, $subscription);
        }
    }

    public function testUpdateSubscription()
    {
        $this->mockApiCall(
            new Request(
                'PATCH',
                '/subscriptions/1',
                [],
                '{
                    "data": {
                        "type": "subscription",
                        "id": "1",
                        "attributes": {
                            "cancel_date": "2019-01-01T00:00:00+01:00"
                        }
                    }
                }'
            ),
            new Response(
                200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/subscriptions/1"
                    },
                    "data": {
                        "type": "subscription",
                        "id": "1",
                        "links": {
                            "self": "https://api.ecurring.com/subscriptions/1"
                        },
                        "attributes": {
                            "mandate_code": "UNIQUE_MANDATE_REFERENCE",
                            "mandate_accepted": true,
                            "mandate_accepted_date": "2017-10-13T00:00:00+02:00",
                            "start_date": "2017-10-13T00:00:00+02:00",
                            "status": "active",
                            "cancel_date": "2019-01-01T00:00:00+01:00",
                            "resume_date": null,
                            "confirmation_page": "https://app.ecurring.com/mandate/accept/1/UNIQUE_MANDATE_REFERENCE",
                            "confirmation_sent": true,
                            "created_at": "2017-10-13T00:00:00+01:00",
                            "updated_at": "2018-02-01T11:21:09+01:00"
                        },
                        "relationships": {
                            "subscription-plan": {
                                "data": {
                                    "type": "subscription-plan",
                                    "id": "1"
                                }
                            },
                            "customer": {
                                "data": {
                                    "type": "customer",
                                    "id": "1"
                                }
                            },
                            "transactions": {
                                "links": {
                                    "related": "https://api.ecurring.com/subscriptions/1/transactions"
                                },
                                "data": []
                            }
                        }
                    }
                }'
            )
        );

        $subscription = new Subscription($this->apiClient);
        $subscription->id = 1;
        $updatedSubscription = $subscription->update(["cancel_date" => "2019-01-01T00:00:00+01:00"]);

        $this->assertInstanceOf(Subscription::class, $updatedSubscription);
        $this->assertEquals("1", $updatedSubscription->id);
        $this->assertEquals(new \DateTime('2019-01-01T00:00:00+01:00'), $updatedSubscription->cancel_date);
    }
}
