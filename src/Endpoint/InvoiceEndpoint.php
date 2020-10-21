<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\Collection;
use Mooore\eCurring\Resource\Invoice;
use Mooore\eCurring\Resource\InvoiceCollection;

class InvoiceEndpoint extends AbstractCollectionEndpoint
{

    protected $resourcePath = 'invoices';

    protected $resourceType = 'invoice';

    /**
     * Creates a new invoice in eCurring.
     *
     * @param int $subscriptionId the identifier of the subscription.
     * @param array $attributes containing the optional attributes of the invoice like: invoice_date, transaction_date,
     * original_invoice_id
     * @param array $filters
     * @return Invoice
     * @throws ApiException
     */
    public function createForSubscription(int $subscriptionId, array $attributes = [], array $filters = [])
    {
        return $this->rest_create(
            $this->createPayloadFromAttributes(
                array_merge(['subscription_id' => $subscriptionId], $attributes)
            ),
            $filters
        );
    }

    /**
     * Retrieve a single invoice from eCurring.
     *
     * @param int $invoiceId the identifier of the invoice
     * @param array $parameters
     * @return Invoice
     * @throws ApiException if the invoice id is invalid or the resource cannot be found.
     */
    public function get(int $invoiceId, array $parameters = [])
    {
        return $this->rest_read($invoiceId, $parameters);
    }

    /**
     * Retrieves a collection of invoices from eCurring.
     *
     * @param int $pageNumber
     * @param int $pageSize
     * @param array $parameters
     * @return InvoiceCollection|Invoice[]
     * @throws ApiException
     */
    public function page(int $pageNumber = 1, int $pageSize = 10, array $parameters = [])
    {
        return $this->rest_list($pageNumber, $pageSize, $parameters);
    }

    /**
     * Update a draft invoice in eCurring.
     *
     * @param int $invoiceId
     * @param array $attributes
     * @return Invoice
     * @throws ApiException if the invoice id is invalid, the resource cannot be found or the invoice is NOT in
     * draft state.
     */
    public function update(int $invoiceId, array $attributes)
    {
        return $this->rest_update(
            $invoiceId,
            $this->createPayloadFromAttributes($attributes, $invoiceId)
        );
    }

    /**
     * Deletes the given invoice in eCurring.
     *
     * The API returns with HTTP status No Content (204) if successful.
     *
     * @param int $invoiceId
     * @throws ApiException if the invoice id is invalid or the resource cannot be found.
     */
    public function delete(int $invoiceId): void
    {
        $this->rest_delete($invoiceId);
    }

    /**
     * Finalise the given draft invoice in eCurring.
     *
     * @param int $invoiceId
     * @return Invoice
     * @throws ApiException if the invoice id is invalid, the resource cannot be found or the invoice is NOT in
     * draft state.
     */
    public function finalise(int $invoiceId)
    {
        $result = $this->client->performHttpCall(
            'PATCH',
            sprintf('%s/%s/finalise', $this->resourcePath, $invoiceId)
        );

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * Credit an invoice in eCurring.
     *
     * This creates a draft invoice equal to the invoice you provided the {id} for. This newly created draft
     * also has the same invoice lines, but the amounts are inverted.
     *
     * @param int $invoiceId
     * @return Invoice
     * @throws ApiException if the invoice id is invalid, the resource cannot be found.
     */
    public function credit(int $invoiceId)
    {
        $result = $this->client->performHttpCall(
            'PATCH',
            sprintf('%s/%s/credit', $this->resourcePath, $invoiceId)
        );

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * Set the status of an open invoice to paid in eCurring.
     *
     * This can only be done when the invoice is in the open state.
     *
     * @param int $invoiceId
     * @return Invoice
     * @throws ApiException if the invoice id is invalid, the resource cannot be found or the invoice is NOT in
     * open state.
     */
    public function pay(int $invoiceId)
    {
        $result = $this->client->performHttpCall(
            'PATCH',
            sprintf('%s/%s/pay', $this->resourcePath, $invoiceId)
        );

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * Get the object that is used by this API endpoint. Every API endpoint uses one type of object.
     *
     * @return Invoice
     */
    protected function getResourceObject()
    {
        return new Invoice($this->client);
    }

    /**
     * Get the collection object that is used by this API endpoint. Every API endpoint uses one type of collection
     * object.
     *
     * @param int $count
     * @param object $links
     *
     * @return InvoiceCollection
     */
    protected function getResourceCollectionObject(int $count, object $links)
    {
        return new InvoiceCollection($this->client, $this->resourceFactory, $count, $links);
    }
}
