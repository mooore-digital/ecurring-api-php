<?php

namespace Mooore\eCurring\Endpoint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mooore\eCurring\Resource\Transaction;

class TransactionEndpointTest extends BaseEndpointTest
{
    public function testCreateTransaction()
    {
        $this->mockApiCall(
            new Request(
                'POST',
                '/transactions',
                [],
                '{
                    "data": {
                        "type": "transaction",
                        "attributes": {
                            "subscription_id": 1,
                            "amount": 34.5,
                            "due_date": "2019-01-01T00:00:00+01:00"
                        }
                    }
                }'
            ),
            new Response(
                201,
                [],
                '{
                        "links": {
                            "self": "https://api.ecurring.com/transactions/ffa38848-6abc-4d22-b6b0-63fe1780969c"
                        },
                        "data": {
                            "type": "transaction",
                            "id": "ffa38848-6abc-4d22-b6b0-63fe1780969c",
                            "links": {
                                "self": "https://api.ecurring.com/transactions/ffa38848-6abc-4d22-b6b0-63fe1780969c"
                            },
                            "attributes": {
                                "status": "queued",
                                "scheduled_on": null,
                                "due_date": "2019-01-01T00:00:00+01:00",
                                "amount": 34.50,
                                "canceled_on": null,
                                "webhook_url": null,
                                "payment_method": "directdebit",
                                "history": []
                            }
                        }
                    }'
            )
        );

        $transaction = $this->apiClient->transactions->create(
            1,
            34.50,
            ['due_date' => '2019-01-01T00:00:00+01:00']
        );
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('ffa38848-6abc-4d22-b6b0-63fe1780969c', $transaction->id);

        //$this->assertEquals(Transaction::STATUS_FULFILLED, $transaction->status);
        $this->assertNull($transaction->scheduled_on);
        $this->assertEquals(new \DateTime('2019-01-01T00:00:00+01:00'), $transaction->due_date);
        $this->assertEquals(34.50, $transaction->amount);
        $this->assertNull($transaction->cancelled_on);
        $this->assertNull($transaction->webhook_url);
        $this->assertEquals('directdebit', $transaction->payment_method);
    }

    public function testDeleteTransaction()
    {
        $this->mockApiCall(
            new Request('DELETE', '/transactions/ffa38848-6abc-4d22-b6b0-63fe1780969c'),
            new Response(204)
        );

        $transaction = new Transaction($this->apiClient);
        $transaction->id = 'ffa38848-6abc-4d22-b6b0-63fe1780969c';
        $transaction->delete();
    }

    public function testGetTransaction()
    {
        $this->mockApiCall(
            new Request('GET', '/transactions/ffa38848-6abc-4d22-b6b0-63fe1780969c'),
            new Response(
                200,
                [],
                '{
                    "links": {
                        "self": "https://api.ecurring.com/transactions/ffa38848-6abc-4d22-b6b0-63fe1780969c"
                    },
                    "data": {
                        "type": "transaction",
                        "id": "ffa38848-6abc-4d22-b6b0-63fe1780969c",
                        "links": {
                            "self": "https://api.ecurring.com/transactions/ffa38848-6abc-4d22-b6b0-63fe1780969c"
                        },
                        "attributes": {
                            "status": "fulfilled",
                            "scheduled_on": "2017-11-01T11:35:00+01:00",
                            "due_date": "2017-11-07T00:00:00+01:00",
                            "amount": 50.6,
                            "canceled_on": null,
                            "webhook_url": null,
                            "payment_method": "directdebit",
                            "history": [
                                {
                                    "attempt": 1,
                                    "external_payment_id": null,
                                    "reason": null,
                                    "reason_code": null,
                                    "recorded_on": "2017-11-01T11:35:00+01:00",
                                    "status": "scheduled",
                                    "new_due_date": null
                                },
                                {
                                    "attempt": 1,
                                    "external_payment_id": "tr_7UhSN1zuXS",
                                    "reason": null,
                                    "reason_code": null,
                                    "recorded_on": "2017-11-02T07:00:02+01:00",
                                    "status": "succeeded",
                                    "new_due_date": null
                                },
                                {
                                    "attempt": 1,
                                    "external_payment_id": "tr_7UhSN1zuXS",
                                    "reason": null,
                                    "reason_code": null,
                                    "recorded_on": "2017-11-06T04:14:37+01:00",
                                    "status": "fulfilled",
                                    "new_due_date": null
                                },
                                {
                                    "attempt": 1,
                                    "external_payment_id": "tr_7UhSN1zuXS",
                                    "reason": "Insufficient funds",
                                    "reason_code": "AM04",
                                    "recorded_on": "2017-11-10T03:43:23+01:00",
                                    "status": "charged_back",
                                    "new_due_date": null
                                },
                                {
                                    "attempt": 1,
                                    "external_payment_id": null,
                                    "reason": null,
                                    "reason_code": null,
                                    "recorded_on": "2017-11-10T03:43:23+01:00",
                                    "status": "rescheduled",
                                    "new_due_date": "2017-11-13T00:00:00+01:00"
                                },
                                {
                                    "attempt": 2,
                                    "external_payment_id": "tr_WDqYK6vllg",
                                    "reason": null,
                                    "reason_code": null,
                                    "recorded_on": "2017-11-13T07:00:00+01:00",
                                    "status": "succeeded",
                                    "new_due_date": null
                                },
                                {
                                    "attempt": 2,
                                    "external_payment_id": "tr_WDqYK6vllg",
                                    "reason": null,
                                    "reason_code": null,
                                    "recorded_on": "2017-11-14T03:54:52+01:00",
                                    "status": "fulfilled",
                                    "new_due_date": null
                                }
                            ]
                        }
                    }
                }'
            )
        );

        $transaction = $this->apiClient->transactions->get('ffa38848-6abc-4d22-b6b0-63fe1780969c');
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('ffa38848-6abc-4d22-b6b0-63fe1780969c', $transaction->id);
        $this->assertEquals(Transaction::STATUS_FULFILLED, $transaction->status);
        $this->assertEquals(new \DateTime('2017-11-01T11:35:00+01:00'), $transaction->scheduled_on);
        $this->assertEquals(new \DateTime('2017-11-07T00:00:00+01:00'), $transaction->due_date);
        $this->assertEquals(50.6, $transaction->amount);
        $this->assertNull($transaction->cancelled_on);
        $this->assertNull($transaction->webhook_url);
        $this->assertEquals('directdebit', $transaction->payment_method);
        $this->assertCount(7, $transaction->history);
        foreach ($transaction->history as $history) {
            $this->assertInstanceOf(\stdClass::class, $history);
        }
    }
}
