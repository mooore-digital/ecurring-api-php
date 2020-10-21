<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\Exception\ApiException;

/**
 * Class Invoice
 * @package Mooore\eCurring\Resource
 *
 * See https://docs.ecurring.com/invoices/get
 */
class Invoice extends AbstractResource
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_OPEN = 'open';
    public const STATUS_PAID = 'paid';
    public const STATUS_CHARGED_BACK = 'charged_back';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $amount_excl;

    /**
     * @var string
     */
    public $amount_incl;

    /**
     * @var string
     */
    public $tax_amount;

    /**
     * @var \DateTime|null
     */
    public $invoice_date;

    /**
     * @var \DateTime|null
     */
    public $transaction_date;

    /**
     * @var \stdObject
     */
    public $relationships;

    /**
     * @var array
     */
    protected $exportProperties = [
        'status',
        'reference',
        'amount_excl',
        'amount_incl',
        'tax_amount',
        'invoice_date',
        'transaction_date',
        'relationships'
    ];

    /**
     * @param string $description The description of the invoice line. This could be the name of your product
     * (max 255 characters).
     * @param double $amount The amount (per unit) of the product, in euro.
     * @param double $tax_rate the tax rate (percentage) that is applied or should be applied to the amount,
     * depending on tax_included.
     * @param bool $tax_included Indicates whether or not the tax_rate is already applied to the given amount
     * @param int $quantity the quantity of the product. The total amount will be calculated by multiplying the
     * quantity with the amount.
     * @return InvoiceLine
     * @throws ApiException
     */
    public function createInvoiceLine(
        string $description,
        float $amount,
        float $tax_rate,
        bool $tax_included,
        int $quantity
    ) {
        return $this->client->invoiceLines->create(
            $this->id,
            $description,
            $amount,
            $tax_rate,
            $tax_included,
            $quantity
        );
    }

    /**
     * Update the invoice_date, transaction_date or original_invoice_id for the invoice in eCurring.
     *
     * @param array $data
     * @return Invoice
     * @throws ApiException
     */
    public function update(array $data = []): Invoice
    {
        $data = !empty($data) ? $data : $this->toArray();

        $updated = $this->client->invoices->update($this->id, $data)->toArray();

        foreach ($updated as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * @throws ApiException
     */
    public function credit()
    {
        return $this->client->invoices->credit($this->id);
    }

    /**
     * @throws ApiException
     */
    public function finalise()
    {
        return $this->client->invoices->finalise($this->id);
    }

    /**
     * @throws ApiException
     */
    public function pay()
    {
        return $this->client->invoices->pay($this->id);
    }

    /**
     * @throws ApiException
     */
    public function delete(): void
    {
        $this->client->invoices->delete($this->id);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isChargedBack(): bool
    {
        return $this->status === self::STATUS_CHARGED_BACK;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
