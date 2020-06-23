<?php

namespace Mooore\eCurring\Endpoint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mooore\eCurring\Resource\SubscriptionPlan;
use Mooore\eCurring\Resource\SubscriptionPlanCollection;

class SubscriptionPlanEndpointTest extends BaseEndpointTest
{
    public function testGetSubscriptionPlan()
    {
        $this->mockApiCall(
            new Request('GET', '/subscription-plans/1'),
            new Response(
                200,
                [],
                '{
                  "links": {
                    "self": "https://api.ecurring.com/subscription-plans/1"
                  },
                  "data": {
                    "type": "subscription-plan",
                    "id": "1",
                    "links": {
                      "self": "https://api.ecurring.com/subscription-plans/1"
                    },
                    "attributes": {
                      "name": "One Size Fits All",
                      "description": "eCurring - One Size Fits All",
                      "start_date": "2017-01-01T00:00:00+02:00",
                      "status": "active",
                      "mandate_authentication_method": "online_payment",
                      "send_invoice": true,
                      "storno_retries": 1,
                      "terms": null
                    },
                    "relationships": {
                      "subscriptions": {
                        "links": {
                          "related": "https://api.ecurring.com/subscription-plans/1/subscriptions"
                        },
                        "data": [
                          {
                            "type": "subscription",
                            "id": "2862"
                          }
                        ]
                      }
                    }
                  }
                }'
            )
        );

        $subscriptionPlan = $this->apiClient->subscriptionPlans->get(1);

        $this->assertInstanceOf(SubscriptionPlan::class, $subscriptionPlan);
        $this->assertEquals("1", $subscriptionPlan->id);
        $this->assertEquals('One Size Fits All', $subscriptionPlan->name);
        $this->assertEquals('eCurring - One Size Fits All', $subscriptionPlan->description);
        $this->assertEquals(new \DateTime('2017-01-01T00:00:00+02:00'), $subscriptionPlan->start_date);
        $this->assertEquals(SubscriptionPlan::STATUS_ACTIVE, $subscriptionPlan->status);
        $this->assertEquals("online_payment", $subscriptionPlan->mandate_authentication_method);
        $this->assertTrue($subscriptionPlan->send_invoice);
        $this->assertEquals(1, $subscriptionPlan->storno_retries);
        $this->assertNull($subscriptionPlan->terms);
    }

    public function testListSubscriptionPlans()
    {
        $this->mockApiCall(
            new Request('GET', '/subscription-plans?page[number]=1&page[size]=10'),
            new Response(
                200,
                [],
                '{
                  "meta": {
                    "total": 2
                  },
                  "links": {
                    "self": "https://api.ecurring.com/subscription-plans?page[number]=1&page[size]=10",
                    "first": "https://api.ecurring.com/subscription-plans?page[number]=1&page[size]=10",
                    "last": "https://api.ecurring.com/subscription-plans?page[number]=1&page[size]=10",
                    "prev": null,
                    "next": null
                  },
                  "data": [
                    {
                      "type": "subscription-plan",
                      "id": "1",
                      "links": {
                        "self": "https://api.ecurring.com/subscription-plans/1"
                      },
                      "attributes": {
                        "name": "One Size Fits All",
                        "description": "eCurring - One Size Fits All",
                        "start_date": "2017-01-01T00:00:00+02:00",
                        "status": "active",
                        "mandate_authentication_method": "online_payment",
                        "send_invoice": true,
                        "storno_retries": 1,
                        "terms": null,
                        "created_at": "2018-02-01T11:21:09+01:00",
                        "updated_at": "2018-02-01T11:21:09+01:00"
                      },
                      "relationships": {
                        "subscriptions": {
                          "links": {
                            "related": "https://api.ecurring.com/subscription-plans/1/subscriptions"
                          },
                          "data": []
                        }
                      }
                    },
                     {
                      "type": "subscription-plan",
                      "id": "2",
                      "links": {
                        "self": "https://api.ecurring.com/subscription-plans/2"
                      },
                      "attributes": {
                        "name": "One Size Fits All 2",
                        "description": "eCurring - One Size Fits All 2",
                        "start_date": "2017-01-01T00:00:00+02:00",
                        "status": "active",
                        "mandate_authentication_method": "online_payment",
                        "send_invoice": true,
                        "storno_retries": 1,
                        "terms": null,
                        "created_at": "2018-02-01T11:21:09+01:00",
                        "updated_at": "2018-02-01T11:21:09+01:00"
                      },
                      "relationships": {
                        "subscriptions": {
                          "links": {
                            "related": "https://api.ecurring.com/subscription-plans/2/subscriptions"
                          },
                          "data": []
                        }
                      }
                    }
                  ]
                }'
            )
        );
        $subscriptionPlans = $this->apiClient->subscriptionPlans->page();
        $this->assertEquals(2, $subscriptionPlans->count);
        $this->assertInstanceOf(SubscriptionPlanCollection::class, $subscriptionPlans);
        $this->assertFalse($subscriptionPlans->hasNext());
        $this->assertFalse($subscriptionPlans->hasPrevious());
        foreach ($subscriptionPlans as $subscriptionPlan) {
            $this->assertInstanceOf(SubscriptionPlan::class, $subscriptionPlan);
        }
    }
}
