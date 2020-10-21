<?php

namespace Mooore\eCurring\Endpoint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mooore\eCurring\Resource\Customer;
use Mooore\eCurring\Resource\CustomerCollection;
use Mooore\eCurring\Resource\Invoice;
use Mooore\eCurring\Resource\InvoiceCollection;

class CustomerEndpointTest extends BaseEndpointTest
{
    public function testCreateCustomer()
    {
        $this->mockApiCall(
            new Request(
                'POST',
                '/customers',
                [],
                '{
                "data": {
                    "type": "customer",
                    "attributes": {
                        "first_name": "Customer",
                        "last_name": "Test",
                        "email": "customer.test@ecurring.com"
                    }
                }
            }'
            ),
            new Response(
                201,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.test/customers/1"
                    },
                    "data": {
                        "type": "customer",
                        "id": "1",
                        "links": {
                            "self": "https://api.ecurring.test/customers/1"
                        },
                        "attributes": {
                            "gender": null,
                            "first_name": "Customer",
                            "middle_name": null,
                            "last_name": "Test",
                            "company_name": null,
                            "vat_number": null,
                            "bank_holder": null,
                            "iban": null,
                            "payment_method": "directdebit",
                            "bank_verification_method": null,
                            "card_holder": null,
                            "card_number": null,
                            "postalcode": null,
                            "house_number": null,
                            "house_number_add": null,
                            "street": null,
                            "city": null,
                            "country_iso2": null,
                            "email": "customer.test@ecurring.com",
                            "telephone": null,
                            "language": "nl",
                            "created_at": "2018-10-10T12:09:24+02:00",
                            "updated_at": "2018-10-10T12:09:24+02:00"
                        },
                        "relationships": {
                            "subscriptions": {
                                "links": {
                                    "related": "https://api.ecurring.test/customers/1/subscriptions"
                                },
                                "data": []
                            }
                        }
                    }
                }'
            )
        );

        /** @var Customer $customer */
        $customer = $this->apiClient->customers->create([
            'first_name' => 'Customer',
            'last_name' => 'Test',
            'email' => 'customer.test@ecurring.com'
        ]);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals("1", $customer->id);
        $this->assertEquals("Customer", $customer->first_name);
        $this->assertEquals("Test", $customer->last_name);
        $this->assertEquals("customer.test@ecurring.com", $customer->email);
        $this->assertEquals(new \DateTime("2018-10-10T12:09:24+02:00"), $customer->created_at);
        $this->assertEquals(new \DateTime("2018-10-10T12:09:24+02:00"), $customer->updated_at);
    }

    public function testGetCustomer()
    {
        $this->mockApiCall(
            new Request(
                'GET',
                '/customers/1'
            ),
            new Response(
                200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/customers/1"
                    },
                    "data": {
                        "type": "customer",
                        "id": "1",
                        "links": {
                            "self": "https://api.ecurring.com/customers/1"
                        },
                        "attributes": {
                            "gender": "m",
                            "first_name": "Jeroen",
                            "middle_name": null,
                            "last_name": "van der Geer",
                            "company_name": null,
                            "vat_number": null,
                            "bank_holder": "J van der Geer",
                            "iban": "NL66ECUR0123456789",
                            "payment_method": "directdebit",
                            "bank_verification_method": null,
                            "card_holder": "J van der Geer",
                            "card_number": "6789",
                            "postalcode": "1016EE",
                            "house_number": "313",
                            "house_number_add": "",
                            "street": "Keizersgracht",
                            "city": "Amsterdam",
                            "country_iso2": "NL",
                            "language": "nl",
                            "email": "support@ecurring.com",
                            "telephone": "+31202616739",
                            "created_at": "2018-02-01T11:21:09+01:00",
                            "updated_at": "2018-02-01T11:21:09+01:00"
                        },
                        "relationships": {
                            "subscriptions": {
                                "links": {
                                    "related": "https://api.ecurring.com/customers/1/subscriptions"
                                },
                                "data": [
                                    {
                                        "type": "subscription",
                                        "id": "1"
                                    }
                                ]
                            }
                        }
                    }
                }'
            )
        );

        /** @var Customer $customer */
        $customer = $this->apiClient->customers->get(1);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals("1", $customer->id);
        $this->assertEquals("Jeroen", $customer->first_name);
        $this->assertEquals("van der Geer", $customer->last_name);
        $this->assertEquals("+31202616739", $customer->telephone);
        $this->assertEquals("Keizersgracht", $customer->street);
        $this->assertEquals("1016EE", $customer->postalcode);
        $this->assertEquals("313", $customer->house_number);
        $this->assertEquals("J van der Geer", $customer->card_holder);
        $this->assertEquals("6789", $customer->card_number);
        $this->assertEquals("J van der Geer", $customer->bank_holder);
        $this->assertEquals("NL66ECUR0123456789", $customer->iban);
        $this->assertEquals("directdebit", $customer->payment_method);
        $this->assertEquals("support@ecurring.com", $customer->email);
        $this->assertEquals(new \DateTime("2018-02-01T11:21:09+01:00"), $customer->created_at);
        $this->assertEquals(new \DateTime("2018-02-01T11:21:09+01:00"), $customer->updated_at);
    }

    public function testListCustomer()
    {
        $this->mockApiCall(
            new Request(
                'GET',
                '/customers?page[number]=1&page[size]=10'
            ),
            new Response(
                200,
                [],
                '{
                  "meta": {
                    "total": 10
                  },
                  "links": {
                    "self": "https://api.ecurring.com/customers?page[number]=1&page[size]=10",
                    "first": "https://api.ecurring.com/customers?page[number]=1&page[size]=10",
                    "last": "https://api.ecurring.com/customers?page[number]=18&page[size]=10",
                    "prev": null,
                    "next": "https://api.ecurring.com/customers?page[number]=2&page[size]=10"
                  },
                  "data": [
                    {
                        "type": "customer",
                        "id": "1",
                        "links": {
                          "self": "https://api.ecurring.com/customers/1"
                        },
                        "attributes": {
                          "gender": "m",
                          "first_name": "Jeroen",
                          "middle_name": null,
                          "last_name": "van der Geer",
                          "company_name": null,
                          "vat_number": null,
                          "bank_holder": "J van der Geer",
                          "iban": "NL66ECUR0123456789",
                          "payment_method": "directdebit",
                          "bank_verification_method": null,
                          "card_holder": "J van der Geer",
                          "card_number": "6789",
                          "postalcode": "1016EE",
                          "house_number": "313",
                          "house_number_add": "",
                          "street": "Keizersgracht",
                          "city": "Amsterdam",
                          "country_iso2": "NL",
                          "language": "nl",
                          "email": "jvdgeer@example.com",
                          "telephone": "+31202616739",
                          "created_at": "2018-02-01T11:21:09+01:00",
                          "updated_at": "2018-02-01T11:21:09+01:00"
                        },
                        "relationships": {
                          "subscriptions": {
                            "links": {
                              "related": "https://api.ecurring.com/customers/1/subscriptions"
                            },
                            "data": [
                              {
                                "type": "subscription",
                                "id": "1"
                              }
                            ]
                          }
                        }
                    }
                  ]
                }'
            )
        );

        // Please note the total value does not match the number of customers returned in the response, the
        // source of the sample response is https://docs.ecurring.com/customers/list
        $customers = $this->apiClient->customers->page();
        $this->assertEquals(10, $customers->count);
        $this->assertInstanceOf(CustomerCollection::class, $customers);
        $this->assertTrue($customers->hasNext());
        $this->assertFalse($customers->hasPrevious());
        foreach ($customers as $customer) {
            $this->assertInstanceOf(Customer::class, $customer);
        }
    }

    public function testUpdateCustomerPartialUpdate()
    {
        $this->mockApiCall(
            new Request(
                'PATCH',
                '/customers/1',
                [],
                '{
                "data": {
                    "type": "customer",
                    "id": "1",
                    "attributes": {
                        "street": "Payment lane",
                        "house_number": "1",
                        "city": "Amsterdam",
                        "postalcode": "1000AA"
                    }
                }
            }'
            ),
            new Response(
                200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/customers/1"
                    },
                    "data": {
                        "type": "customer",
                        "id": "1",
                        "links": {
                            "self": "https://api.ecurring.com/customers/1"
                        },
                        "attributes": {
                            "gender": "f",
                            "first_name": "Customer",
                            "middle_name": null,
                            "last_name": "Test",
                            "company_name": null,
                            "vat_number": null,
                            "bank_holder": "C Test",
                            "iban": "NL19ABNA0940161854",
                            "payment_method": "directdebit",
                            "bank_verification_method": null,
                            "card_holder": "C Test",
                            "card_number": "1854",
                            "postalcode": "1000AA",
                            "house_number": "1",
                            "house_number_add": null,
                            "street": "Payment lane",
                            "city": "Amsterdam",
                            "country_iso2": "NL",
                            "language": "nl",
                            "email": "customer.test@ecurring.com",
                            "telephone": "+31202616739",
                            "created_at": "2018-02-01T11:21:09+01:00",
                            "updated_at": "2018-02-05T13:00:00+01:00"
                        },
                        "relationships": {
                            "subscriptions": {
                                "links": {
                                    "related": "https://api.ecurring.com/customers/1/subscriptions"
                                },
                                "data": []
                            }
                        }
                    }
                }'
            )
        );
        $customer = new Customer($this->apiClient);
        $customer->id = 1;
        $updatedCustomer = $customer->update([
            "street" => "Payment lane",
            "house_number" => "1",
            "city" => "Amsterdam",
            "postalcode" => "1000AA"
        ]);

        $this->assertInstanceOf(Customer::class, $updatedCustomer);
        $this->assertEquals("1", $updatedCustomer->id);
        $this->assertEquals("Amsterdam", $updatedCustomer->city);
        $this->assertEquals("1000AA", $updatedCustomer->postalcode);
        $this->assertEquals("Payment lane", $updatedCustomer->street);
        $this->assertEquals("1", $updatedCustomer->house_number);
    }
}
