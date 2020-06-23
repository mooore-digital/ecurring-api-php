<?php

namespace Mooore\eCurring\Endpoint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mooore\eCurring\Resource\Invoice;
use Mooore\eCurring\Resource\InvoiceLine;

class InvoiceLineEndpointTest extends BaseEndpointTest
{
    public function testCreateInvoiceLine()
    {
        $this->mockApiCall(
            new Request(
                'POST',
                '/invoice-lines',
                [],
                '{
                     "data": {
                        "type": "invoice-line",
                        "attributes": {
                            "invoice_id": 637377985,
                            "description": "lunchbox",
                            "amount": 10.00,
                            "tax_rate": 21.0,
                            "tax_included": false,
                            "quantity": 2
                        }
                    }
                }'
            ),
            new Response(
                201,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/invoice-lines/1562050264"
                    },
                    "data": {
                        "type": "invoice-line",
                        "id": "1562050264",
                        "links": {
                            "self": "https://api.ecurring.com/invoice-lines/1562050264"
                        },
                        "attributes": {
                            "description": "lunchbox",
                            "amount_excl": "10.00",
                            "amount_incl": "12.10",
                            "tax_rate": "21.00",
                            "tax_amount": "2.10",
                            "tax_included": false,
                            "quantity": 2,
                            "created_at": "2019-10-04T14:44:47+02:00",
                            "updated_at": "2019-10-04T14:44:47+02:00"
                        }
                    }
                }'
            )
        );

        $invoice = new Invoice($this->apiClient);
        $invoice->id = 637377985;

        $invoiceLine = $invoice->createInvoiceLine("lunchbox", 10.00, 21.0, false, 2);

        $this->assertInstanceOf(InvoiceLine::class, $invoiceLine);
        $this->assertEquals("lunchbox", $invoiceLine->description);
        $this->assertEquals(10.0, $invoiceLine->amount_excl);
        $this->assertEquals(12.1, $invoiceLine->amount_incl);
        $this->assertEquals(21.0, $invoiceLine->tax_rate);
        $this->assertEquals(false, $invoiceLine->tax_included);
        $this->assertEquals(2, $invoiceLine->quantity);
    }

    public function testGetInvoiceLine()
    {
        $this->mockApiCall(
            new Request('GET', '/invoice-lines/1562050264'),
            new Response(
                200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/invoice-lines/1562050264"
                    },
                    "data": {
                        "type": "invoice-line",
                        "id": "1562050264",
                        "links": {
                            "self": "https://api.ecurring.com/invoice-lines/1562050264"
                        },
                        "attributes": {
                            "description": "lunchbox",
                            "amount_excl": "10.00",
                            "amount_incl": "12.10",
                            "tax_rate": "21.00",
                            "tax_amount": "2.10",
                            "tax_included": false,
                            "quantity": 2,
                            "created_at": "2019-10-04T14:44:47+02:00",
                            "updated_at": "2019-10-04T14:44:47+02:00"
                        }
                    }
                }'
            )
        );

        $invoiceLine = $this->apiClient->invoiceLines->get(1562050264);

        $this->assertInstanceOf(InvoiceLine::class, $invoiceLine);
        $this->assertEquals("lunchbox", $invoiceLine->description);
        $this->assertEquals(10.0, $invoiceLine->amount_excl);
        $this->assertEquals(12.1, $invoiceLine->amount_incl);
        $this->assertEquals(21.0, $invoiceLine->tax_rate);
        $this->assertEquals(2.1, $invoiceLine->tax_amount);
        $this->assertEquals(false, $invoiceLine->tax_included);
        $this->assertEquals(2, $invoiceLine->quantity);
    }

    public function testUpdateInvoiceLine()
    {
        $this->mockApiCall(
            new Request(
                'PATCH',
                '/invoice-lines/1562050264',
                [],
                '{
                    "data": {
                        "type": "invoice-line",
                        "id": "1562050264",
                        "attributes": {
                            "quantity": 7
                        }
                    }
                }'
            ),
            new Response(
                200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/invoice-lines/1562050264"
                    },
                    "data": {
                        "type": "invoice-line",
                        "id": "1562050264",
                        "links": {
                            "self": "https://api.ecurring.com/invoice-lines/1562050264"
                        },
                        "attributes": {
                            "description": "lunchbox",
                            "amount_excl": "10.00",
                            "amount_incl": "12.10",
                            "tax_rate": "21.00",
                            "tax_amount": "2.10",
                            "tax_included": false,
                            "quantity": 7,
                            "created_at": "2019-10-04T14:44:47+02:00",
                            "updated_at": "2019-10-04T14:44:47+02:00"
                        }
                    }
                }'
            )
        );

        $invoiceLine = new InvoiceLine($this->apiClient);
        $invoiceLine->id = 1562050264;
        $invoiceLine->update(['quantity' => 7]);

        $this->assertInstanceOf(InvoiceLine::class, $invoiceLine);
        $this->assertEquals("lunchbox", $invoiceLine->description);
        $this->assertEquals(10.0, $invoiceLine->amount_excl);
        $this->assertEquals(12.1, $invoiceLine->amount_incl);
        $this->assertEquals(21.0, $invoiceLine->tax_rate);
        $this->assertEquals(2.1, $invoiceLine->tax_amount);
        $this->assertEquals(false, $invoiceLine->tax_included);
        $this->assertEquals(7, $invoiceLine->quantity);
    }

    public function testDeleteInvoiceLine()
    {
        $this->mockApiCall(
            new Request('DELETE', '/invoice-lines/1562050264'),
            new Response(204)
        );

        $invoiceLine = new InvoiceLine($this->apiClient);
        $invoiceLine->id = 1562050264;

        $invoiceLine->delete();
    }
}
