<?php


namespace Mooore\eCurring\Endpoint;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mooore\eCurring\Resource\Invoice;
use Mooore\eCurring\Resource\InvoiceCollection;
use Mooore\eCurring\Resource\Subscription;

class InvoiceEndpointTest extends BaseEndpointTest
{
    public function testGetInvoice()
    {
        $this->mockApiCall(
            new Request(
                "GET",
                "/invoices/637377985"
            ),
            new Response(
                200,
                [],
                '{
                        "links": {
                            "self": "https://api.ecurring.com/invoices/637377985"
                        },
                        "data": {
                            "type": "invoice",
                            "id": "637377985",
                            "links": {
                                "self": "https://api.ecurring.com/invoices/637377985"
                            },
                            "attributes": {
                                "status": "draft",
                                "reference": "DRAFT",
                                "amount_excl": "10.00",
                                "amount_incl": "12.10",
                                "tax_amount": "2.10",
                                "invoice_date": "2019-10-10T00:00:00+02:00",
                                "transaction_date": "2019-10-10T00:00:00+02:00",
                                "created_at": "2019-10-03T11:33:17+02:00",
                                "updated_at": "2019-10-03T11:33:17+02:00"
                            },
                            "relationships": {
                                "subscription": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/637377985/subscription"
                                    },
                                    "data": {
                                        "type": "subscription",
                                        "id": "739578262"
                                    }
                                },
                                "customer": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/637377985/customer"
                                    },
                                    "data": {
                                        "type": "customer",
                                        "id": "149526343"
                                    }
                                },
                                "invoice-lines": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/637377985/lines"
                                    },
                                    "data": []
                                },
                                "transaction": {
                                    "data": null
                                },
                                "original-invoice": {
                                    "data": null
                                }
                            }
                        }
                    }'
            )
        );

        $invoice = $this->apiClient->invoices->get("637377985");

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('637377985', $invoice->id);
        $this->assertEquals('DRAFT', $invoice->reference);
        $this->assertEquals("10.00", $invoice->amount_excl);
        $this->assertEquals("12.10", $invoice->amount_incl);
        $this->assertEquals("2.10", $invoice->tax_amount);
        $this->assertEquals(Invoice::STATUS_DRAFT, $invoice->status);
        $this->assertEquals(new \DateTime('2019-10-10T00:00:00+02:00'), $invoice->invoice_date);
        $this->assertEquals(new \DateTime('2019-10-10T00:00:00+02:00'), $invoice->transaction_date);
        $this->assertEquals(new \DateTime('2019-10-03T11:33:17+02:00'), $invoice->created_at);
        $this->assertEquals(new \DateTime('2019-10-03T11:33:17+02:00'), $invoice->updated_at);
    }

    public function testCreateInvoice()
    {
        $this->mockApiCall(
            new Request('POST',
                '/invoices',
                [],
                '{
                    "data": {
                        "type": "invoice",
                        "attributes": {
                            "subscription_id": 739578262
                        }
                    }
                }'
            ),
            new Response(201,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.test/invoices/795664806"
                    },
                    "data": {
                        "type": "invoice",
                        "id": "795664806",
                        "links": {
                            "self": "https://api.ecurring.test/invoices/795664806"
                        },
                        "attributes": {
                            "status": "draft",
                            "reference": "DRAFT",
                            "amount_excl": "0.00",
                            "amount_incl": "0.00",
                            "tax_amount": "0.00",
                            "invoice_date": null,
                            "transaction_date": null,
                            "created_at": "2019-10-01T15:57:59+02:00",
                            "updated_at": "2019-10-01T15:57:59+02:00"
                        },
                        "relationships": {
                            "subscription": {
                                "links": {
                                    "related": "https://api.ecurring.test/invoices/795664806/subscription"
                                },
                                "data": {
                                    "type": "subscription",
                                    "id": "739578262"
                                }
                            },
                            "customer": {
                                "links": {
                                    "related": "https://api.ecurring.test/invoices/795664806/customer"
                                },
                                "data": {
                                    "type": "customer",
                                    "id": "1541610705"
                                }
                            },
                            "invoice-lines": {
                                "links": {
                                    "related": "https://api.ecurring.test/invoices/795664806/lines"
                                },
                                "data": []
                            },
                            "transaction": {
                                "data": null
                            },
                            "original-invoice": {
                                "data": null
                            }
                        }
                    }
                }')
        );

        $subscription = new Subscription($this->apiClient);
        $subscription->id = 739578262;
        $invoice = $subscription->createInvoice();

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(795664806, $invoice->id);
        $this->assertEquals('DRAFT', $invoice->reference);
        $this->assertEquals("0.00", $invoice->amount_excl);
        $this->assertEquals("0.00", $invoice->amount_incl);
        $this->assertEquals("0.00", $invoice->tax_amount);
        $this->assertEquals(Invoice::STATUS_DRAFT, $invoice->status);
        $this->assertNull($invoice->invoice_date);
        $this->assertNull($invoice->transaction_date);
        $this->assertEquals(new \DateTime('2019-10-01T15:57:59+02:00'), $invoice->created_at);
        $this->assertEquals(new \DateTime('2019-10-01T15:57:59+02:00'), $invoice->updated_at);
    }

    public function testCreditInvoice()
    {
        $this->mockApiCall(
            new Request('PATCH',
                '/invoices/637377985/credit'),
            new Response(200,
                [],
                '{
                        "links": {
                            "self": "https://api.ecurring.com/invoices/278193007"
                        },
                        "data": {
                            "type": "invoice",
                            "id": "278193007",
                            "links": {
                                "self": "https://api.ecurring.com/invoices/278193007"
                            },
                            "attributes": {
                                "status": "draft",
                                "reference": "DRAFT",
                                "amount_excl": "-993.10",
                                "amount_incl": "-1104.72",
                                "tax_rates": [
                                    {
                                        "amount": "-59.90",
                                        "rate": "21.00"
                                    },
                                    {
                                        "amount": "-36.72",
                                        "rate": "9.00"
                                    },
                                    {
                                        "amount": "-15.00",
                                        "rate": "5.00"
                                    }
                                ],
                                "tax_amount": "-111.62",
                                "invoice_date": null,
                                "transaction_date": null,
                                "created_at": "2020-04-15T16:35:26+02:00",
                                "updated_at": "2020-04-15T16:35:26+02:00"
                            },
                            "relationships": {
                                "subscription": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/278193007/subscription"
                                    },
                                    "data": {
                                        "type": "subscription",
                                        "id": "739578262"
                                    }
                                },
                                "customer": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/278193007/customer"
                                    },
                                    "data": {
                                        "type": "customer",
                                        "id": "149526343"
                                    }
                                },
                                "invoice-lines": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/278193007/lines"
                                    },
                                    "data": [
                                        {
                                            "type": "invoice-line",
                                            "id": "847442567"
                                        },
                                        {
                                            "type": "invoice-line",
                                            "id": "1803390100"
                                        },
                                        {
                                            "type": "invoice-line",
                                            "id": "1077716709"
                                        }
                                    ]
                                },
                                "transaction": {
                                    "data": null
                                },
                                "original-invoice": {
                                    "data": {
                                        "type": "invoice",
                                        "id": "637377985"
                                    }
                                }
                            }
                        }
                    }')
        );

        $invoice = $this->getInvoice();
        $updatedInvoice = $invoice->credit();

        $this->assertInstanceOf(Invoice::class, $updatedInvoice);
        $this->assertEquals('278193007', $updatedInvoice->id);
        $this->assertEquals('DRAFT', $updatedInvoice->reference);
        $this->assertEquals("-993.10", $updatedInvoice->amount_excl);
        $this->assertEquals("-1104.72", $updatedInvoice->amount_incl);
        $this->assertEquals("-111.62", $updatedInvoice->tax_amount);
        $this->assertEquals(Invoice::STATUS_DRAFT, $updatedInvoice->status);
        $this->assertNull($updatedInvoice->invoice_date);
        $this->assertNull($updatedInvoice->transaction_date);
        $this->assertEquals(new \DateTime('2020-04-15T16:35:26+02:00'), $updatedInvoice->created_at);
        $this->assertEquals(new \DateTime('2020-04-15T16:35:26+02:00'), $updatedInvoice->updated_at);
    }

    public function testUpdateInvoice()
    {
        $this->mockApiCall(
            new Request('PATCH',
                '/invoices/637377985',
                [],
                '{
                    "data": {
                        "type": "invoice",
                        "id": "637377985",
                        "attributes": {
                            "invoice_date": "2019-10-04T00:00:00+02:00"
                        }
                    }
                }'),
            new Response(200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/invoices/637377985"
                    },
                    "data": {
                        "type": "invoice",
                        "id": "637377985",
                        "links": {
                            "self": "https://api.ecurring.com/invoices/637377985"
                        },
                        "attributes": {
                            "status": "draft",
                            "reference": "DRAFT",
                            "amount_excl": "10.00",
                            "amount_incl": "12.10",
                            "tax_amount": "2.10",
                            "invoice_date": "2019-10-04T00:00:00+02:00",
                            "transaction_date": "2019-10-10T00:00:00+02:00",
                            "created_at": "2019-10-03T11:33:17+02:00",
                            "updated_at": "2019-10-04T09:57:41+02:00"
                        },
                        "relationships": {
                            "subscription": {
                                "links": {
                                    "related": "https://api.ecurring.com/invoices/637377985/subscription"
                                },
                                "data": {
                                    "type": "subscription",
                                    "id": "739578262"
                                }
                            },
                            "customer": {
                                "links": {
                                    "related": "https://api.ecurring.com/invoices/637377985/customer"
                                },
                                "data": {
                                    "type": "customer",
                                    "id": "149526343"
                                }
                            },
                            "invoice-lines": {
                                "links": {
                                    "related": "https://api.ecurring.com/invoices/637377985/lines"
                                },
                                "data": []
                            },
                            "transaction": {
                                "data": {
                                    "type": "transaction",
                                    "id": "b91c9eb1-1331-476e-aed1-344ca19d229d"
                                }
                            },
                            "original-invoice": {
                                "data": null
                            }
                        }
                    }
                }')
        );

        $invoice = $this->getInvoice();
        $updatedInvoice = $invoice->update(['invoice_date' => "2019-10-04T00:00:00+02:00"]);

        $this->assertInstanceOf(Invoice::class, $updatedInvoice);
        $this->assertEquals('637377985', $updatedInvoice->id);
        $this->assertEquals('DRAFT', $updatedInvoice->reference);
        $this->assertEquals("10.00", $updatedInvoice->amount_excl);
        $this->assertEquals("12.10", $updatedInvoice->amount_incl);
        $this->assertEquals("2.10", $updatedInvoice->tax_amount);
        $this->assertEquals(Invoice::STATUS_DRAFT, $updatedInvoice->status);
        $this->assertEquals(new \DateTime('2019-10-04T00:00:00+02:00'), $updatedInvoice->invoice_date);
        $this->assertEquals(new \DateTime('2019-10-10T00:00:00+02:00'), $updatedInvoice->transaction_date);
        $this->assertEquals(new \DateTime('2019-10-03T11:33:17+02:00'), $updatedInvoice->created_at);
        $this->assertEquals(new \DateTime('2019-10-03T11:33:17+02:00'), $updatedInvoice->updated_at);
    }

    public function testDeleteInvoice()
    {
        $this->mockApiCall(
            new Request('DELETE', '/invoices/637377985'),
            new Response(204)
        );
        $invoice = $this->getInvoice();
        $invoice->delete();
    }

    public function testFinaliseInvoice()
    {
        $this->mockApiCall(
            new Request('PATCH', '/invoices/637377985/finalise'),
            new Response(200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/invoices/637377985"
                    },
                    "data": {
                        "type": "invoice",
                        "id": "637377985",
                        "links": {
                            "self": "https://api.ecurring.com/invoices/637377985"
                        },
                        "attributes": {
                            "status": "open",
                            "reference": "ECUR-1",
                            "amount_excl": "10.00",
                            "amount_incl": "12.10",
                            "tax_amount": "2.10",
                            "invoice_date": "2019-10-04T00:00:00+02:00",
                            "transaction_date": "2019-10-10T00:00:00+02:00",
                            "created_at": "2019-10-03T11:33:17+02:00",
                            "updated_at": "2019-10-04T09:57:41+02:00"
                        },
                        "relationships": {
                            "subscription": {
                                "links": {
                                    "related": "https://api.ecurring.com/invoices/637377985/subscription"
                                },
                                "data": {
                                    "type": "subscription",
                                    "id": "739578262"
                                }
                            },
                            "customer": {
                                "links": {
                                    "related": "https://api.ecurring.com/invoices/637377985/customer"
                                },
                                "data": {
                                    "type": "customer",
                                    "id": "149526343"
                                }
                            },
                            "invoice-lines": {
                                "links": {
                                    "related": "https://api.ecurring.com/invoices/637377985/lines"
                                },
                                "data": []
                            },
                            "transaction": {
                                "data": {
                                    "type": "transaction",
                                    "id": "b91c9eb1-1331-476e-aed1-344ca19d229d"
                                }
                            }
                        }
                    }
                }')
        );

        $invoice = $this->getInvoice();
        $updatedInvoice = $invoice->finalise();

        $this->assertInstanceOf(Invoice::class, $updatedInvoice);
        $this->assertEquals('637377985', $updatedInvoice->id);
        $this->assertEquals('ECUR-1', $updatedInvoice->reference);
        $this->assertEquals("10.00", $updatedInvoice->amount_excl);
        $this->assertEquals("12.10", $updatedInvoice->amount_incl);
        $this->assertEquals("2.10", $updatedInvoice->tax_amount);
        $this->assertEquals(Invoice::STATUS_OPEN, $updatedInvoice->status);
        $this->assertEquals(new \DateTime('2019-10-04T00:00:00+02:00'), $updatedInvoice->invoice_date);
        $this->assertEquals(new \DateTime('2019-10-10T00:00:00+02:00'), $updatedInvoice->transaction_date);
        $this->assertEquals(new \DateTime('2019-10-03T11:33:17+02:00'), $updatedInvoice->created_at);
        $this->assertEquals(new \DateTime('2019-10-04T09:57:41+02:00'), $updatedInvoice->updated_at);


    }

    public function testPayInvoice()
    {
        $this->mockApiCall(
            new Request('PATCH',
                '/invoices/637377985/pay'
            ),
            new Response(
                200,
                [],
                '{
                        "links": {
                            "self": "https://api.ecurring.com/invoices/637377985"
                        },
                        "data": {
                            "type": "invoice",
                            "id": "637377985",
                            "links": {
                                "self": "https://api.ecurring.com/invoices/637377985"
                            },
                            "attributes": {
                                "status": "paid",
                                "reference": "ECUR-1",
                                "amount_excl": "10.00",
                                "amount_incl": "12.10",
                                "tax_amount": "2.10",
                                "invoice_date": "2019-10-04T00:00:00+02:00",
                                "transaction_date": "2019-10-10T00:00:00+02:00",
                                "created_at": "2019-10-03T11:33:17+02:00",
                                "updated_at": "2019-10-04T09:57:41+02:00"
                            },
                            "relationships": {
                                "subscription": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/637377985/subscription"
                                    },
                                    "data": {
                                        "type": "subscription",
                                        "id": "739578262"
                                    }
                                },
                                "customer": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/637377985/customer"
                                    },
                                    "data": {
                                        "type": "customer",
                                        "id": "149526343"
                                    }
                                },
                                "invoice-lines": {
                                    "links": {
                                        "related": "https://api.ecurring.com/invoices/637377985/lines"
                                    },
                                    "data": []
                                },
                                "transaction": {
                                    "data": {
                                        "type": "transaction",
                                        "id": "b91c9eb1-1331-476e-aed1-344ca19d229d"
                                    }
                                }
                            }
                        }
                    }'
            )
        );

        $invoice = $this->getInvoice();
        $updatedInvoice = $invoice->pay();

        $this->assertInstanceOf(Invoice::class, $updatedInvoice);
        $this->assertEquals('637377985', $updatedInvoice->id);
        $this->assertEquals('ECUR-1', $updatedInvoice->reference);
        $this->assertEquals("10.00", $updatedInvoice->amount_excl);
        $this->assertEquals("12.10", $updatedInvoice->amount_incl);
        $this->assertEquals("2.10", $updatedInvoice->tax_amount);
        $this->assertEquals(Invoice::STATUS_PAID, $updatedInvoice->status);
        $this->assertEquals(new \DateTime('2019-10-04T00:00:00+02:00'), $updatedInvoice->invoice_date);
        $this->assertEquals(new \DateTime('2019-10-10T00:00:00+02:00'), $updatedInvoice->transaction_date);
        $this->assertEquals(new \DateTime('2019-10-03T11:33:17+02:00'), $updatedInvoice->created_at);
        $this->assertEquals(new \DateTime('2019-10-04T09:57:41+02:00'), $updatedInvoice->updated_at);

    }

    public function testListInvoices()
    {
        $this->mockApiCall(
            new Request('GET', '/invoices?page[number]=1&page[size]=10'),
            new Response(200,
                [],
                '{
                        "meta": {
                            "total": 3
                        },
                        "links": {
                            "self": "https://api.ecurring.com/invoices?page[number]=1&page[size]=10",
                            "first": "https://api.ecurring.com/invoices?page[number]=1&page[size]=10",
                            "last": "https://api.ecurring.com/invoices?page[number]=1&page[size]=10",
                            "prev": null,
                            "next": null
                        },
                        "data": [
                            {
                                "type": "invoice",
                                "id": "707805321",
                                "links": {
                                    "self": "https://api.ecurring.com/invoices/707805321"
                                },
                                "attributes": {
                                    "status": "open",
                                    "reference": "DN\'S BDRJF-1",
                                    "amount_excl": "0.83",
                                    "amount_incl": "1.00",
                                    "tax_amount": "0.17",
                                    "invoice_date": "2019-07-10T00:00:00+02:00",
                                    "transaction_date": "2019-10-10T00:00:00+02:00",
                                    "created_at": "2019-07-10T10:15:07+02:00",
                                    "updated_at": "2019-10-02T15:39:53+02:00"
                                },
                                "relationships": {
                                    "subscription": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/707805321/subscription"
                                        },
                                        "data": {
                                            "type": "subscription",
                                            "id": "1180394926"
                                        }
                                    },
                                    "customer": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/707805321/customer"
                                        },
                                        "data": {
                                            "type": "customer",
                                            "id": "1116541653"
                                        }
                                    },
                                    "invoice-lines": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/707805321/lines"
                                        },
                                        "data": [
                                            {
                                                "type": "invoice-line",
                                                "id": "902823330"
                                            }
                                        ]
                                    },
                                    "transaction": {
                                        "data": {
                                            "type": "transaction",
                                            "id": "b41c3re1-1331-476e-aed1-344ca19d229d"
                                        }
                                    }
                                }
                            },
                            {
                                "type": "invoice",
                                "id": "1325564912",
                                "links": {
                                    "self": "https://api.ecurring.com/invoices/1325564912"
                                },
                                "attributes": {
                                    "status": "draft",
                                    "reference": "DRAFT",
                                    "amount_excl": "0.00",
                                    "amount_incl": "0.00",
                                    "tax_amount": "0.00",
                                    "invoice_date": "2019-10-10T00:00:00+02:00",
                                    "transaction_date": "2019-10-10T00:00:00+02:00",
                                    "created_at": "2019-10-03T11:26:49+02:00",
                                    "updated_at": "2019-10-03T11:26:49+02:00"
                                },
                                "relationships": {
                                    "subscription": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/1325564912/subscription"
                                        },
                                        "data": {
                                            "type": "subscription",
                                            "id": "739578262"
                                        }
                                    },
                                    "customer": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/1325564912/customer"
                                        },
                                        "data": {
                                            "type": "customer",
                                            "id": "149526343"
                                        }
                                    },
                                    "invoice-lines": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/1325564912/lines"
                                        },
                                        "data": []
                                    },
                                    "transaction": {
                                        "data": null
                                    }
                                }
                            },
                            {
                                "type": "invoice",
                                "id": "637377985",
                                "links": {
                                    "self": "https://api.ecurring.com/invoices/637377985"
                                },
                                "attributes": {
                                    "status": "draft",
                                    "reference": "DRAFT",
                                    "amount_excl": "0.00",
                                    "amount_incl": "0.00",
                                    "tax_amount": "0.00",
                                    "invoice_date": "2019-10-10T00:00:00+02:00",
                                    "transaction_date": "2019-10-10T00:00:00+02:00",
                                    "created_at": "2019-10-03T11:33:17+02:00",
                                    "updated_at": "2019-10-04T09:57:41+02:00"
                                },
                                "relationships": {
                                    "subscription": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/637377985/subscription"
                                        },
                                        "data": {
                                            "type": "subscription",
                                            "id": "739578262"
                                        }
                                    },
                                    "customer": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/637377985/customer"
                                        },
                                        "data": {
                                            "type": "customer",
                                            "id": "149526343"
                                        }
                                    },
                                    "invoice-lines": {
                                        "links": {
                                            "related": "https://api.ecurring.com/invoices/637377985/lines"
                                        },
                                        "data": []
                                    },
                                    "transaction": {
                                        "data": null
                                    }
                                }
                            }
                        ]
                    }')
        );

        $invoices = $this->apiClient->invoices->page();

        $this->assertEquals(3, $invoices->count);
        $this->assertInstanceOf(InvoiceCollection::class, $invoices);
        $this->assertFalse( $invoices->hasNext());
        $this->assertFalse( $invoices->hasPrevious());
        foreach($invoices as $invoice) {
            $this->assertInstanceOf(Invoice::class, $invoice);
        }
    }

    private function getInvoice(): Invoice
    {
        $attributes = [
            'id' => 637377985,
            'status' => Invoice::STATUS_DRAFT,
            'reference' => 'DRAFT',
            'amount_excl' => "0.00",
            'amount_incl' => "0.00",
            'invoice_date' => null,
            'transaction_date' => null,
            'created_at' => new \DateTime('2019-10-03T11:33:17+02:00'),
            'updated_at' => new \DateTime('2019-10-03T11:33:17+02:00')
        ];

        $invoice = new Invoice($this->apiClient);
        return $this->copy($attributes, $invoice);
    }
}