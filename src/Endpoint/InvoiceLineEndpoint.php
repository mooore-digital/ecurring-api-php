<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\InvoiceLine;

class InvoiceLineEndpoint extends AbstractEndpoint
{

    protected $resourcePath = 'invoice-lines';
    protected $resourceType = 'invoice-line';

    protected function getResourceObject()
    {
        return new InvoiceLine($this->client);
    }

    /**
     * Retrieve a single invoice line from eCurring.
     *
     * @param int $invoiceLineId the identifier of the invoice line
     * @param array $parameters
     * @return InvoiceLine
     * @throws ApiException if the invoice line id is invalid or the resource cannot be found.
     */
    public function get(int $invoiceLineId, array $parameters = [])
    {
        return $this->rest_read($invoiceLineId, $parameters);
    }

    /**
     * Add a new invoice line to an invoice in eCurring. This can only be done while the invoice is in draft state.
     *
     * @param int $invoiceId The identifier of the invoice.
     * @param string $description The description of the invoice line. This could be the name of your product.
     * @param double $amount The amount (per unit) of the product, in euro.
     * @param double $tax_rate the tax rate (percentage) that is applied or should be applied to the amount, depending
     * on tax_included.
     * @param bool $tax_included Indicates whether or not the tax_rate is already applied to the given amount
     * @param int $quantity the quantity of the product. The total amount will be calculated by multiplying the quantity
     * with the amount.
     * @return AbstractResource|InvoiceLine
     * @throws ApiException if the invoice id is invalid, the resource cannot be found or the invoice is NOT in draft
     * state.
     */
    public function create(
        int $invoiceId,
        string $description,
        float $amount,
        float $tax_rate,
        bool $tax_included,
        int $quantity
    ) {
        return $this->rest_create($this->createPayloadFromAttributes(
            [
                'invoice_id' => $invoiceId,
                'description' => $description,
                'amount' => $amount,
                'tax_rate' => $tax_rate,
                'tax_included' => $tax_included,
                'quantity' => $quantity
            ]
        ));
    }

    /**
     * Update an invoice line in eCurring. This can only be done while the invoice is in draft state.
     *
     * @param int $invoiceLineId The identifier of the invoice line.
     * @param array $attributes
     * @return AbstractResource|InvoiceLine
     * @throws ApiException if the invoice line id is invalid, the resource cannot be found or the invoice is NOT
     * in draft state.
     */
    public function update(int $invoiceLineId, array $attributes)
    {
        return $this->rest_update(
            $invoiceLineId,
            $this->createPayloadFromAttributes($attributes, $invoiceLineId)
        );
    }

    /**
     * Delete an invoice line from a draft invoice in eCurring.
     *
     * The API returns with HTTP status No Content (204) if successful.
     *
     * @param int $invoiceLineId The identifier of the invoice line.
     * @throws ApiException if the invoice line id is invalid, the resource cannot be found or the invoice is NOT
     * in draft state.
     */
    public function delete(int $invoiceLineId): void
    {
        $this->rest_delete($invoiceLineId);
    }
}
