<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\Transaction;

class TransactionEndpoint extends AbstractEndpoint
{
    protected $resourcePath = 'transactions';

    protected $resourceType = 'transaction';

    protected function getResourceObject()
    {
        return new Transaction($this->client);
    }

    /**
     * Create a transaction in eCurring.
     *
     * The transaction may possible not immediately be available after creation and the status will always be queued
     * in the response. An immediate request to the Get transaction endpoint for this transaction after creation may
     * result in a 404.
     *
     * @param int $subscriptionId the identifier of the subscription
     * @param float $amount The amount of the transaction, in Euro.
     * @param array $attributes containing the optional attribute: due_date.
     * @param array $filters
     * @return Transaction
     * @throws ApiException
     */
    public function create($subscriptionId, float $amount, array $attributes = [], array $filters = [])
    {
        return $this->rest_create(
            $this->createPayloadFromAttributes(
                array_merge([
                    'subscription_id' => $subscriptionId,
                    'amount' => $amount,
                ], $attributes)
            ),
            $filters
        );
    }

    /**
     * Retrieve a single transaction from eCurring.
     *
     * @param string $transactionId the identifier of the transaction
     * @param array $parameters
     * @return Transaction
     * @throws ApiException
     */
    public function get(string $transactionId, array $parameters = [])
    {
        return $this->rest_read($transactionId, $parameters);
    }

    /**
     * Delete an scheduled transaction in eCurring.
     *
     * @param string $transactionId The identifier of the transaction.
     * @throws ApiException if the transaction id is invalid, the resource cannot be found or the transaction is NOT
     * in scheduled state.
     */
    public function delete(string $transactionId): void
    {
        $this->rest_delete($transactionId);
    }
}
