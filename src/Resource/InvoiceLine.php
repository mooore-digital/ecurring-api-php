<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\Exception\ApiException;

/**
 * Class InvoiceLine
 * @package Mooore\eCurring\Resource
 *
 * @see https://docs.ecurring.com/invoice-lines/get
 */
class InvoiceLine extends AbstractResource
{

    /**
     * @var string
     */
    public $description;

    /**
     * @var double
     */
    public $amount_excl;

    /**
     * @var double
     */
    public $amount_incl;

    /**
     * @var double
     */
    public $tax_rate;

    /**
     * @var string
     */
    public $tax_amount;

    /**
     * @var bool
     */
    public $tax_included;
    /**
     * @var int
     */
    public $quantity;


    /**
     * @var array
     */
    protected $exportProperties = [
        'description',
        'amount_excl',
        'amount_incl',
        'tax_rate',
        'tax_amount',
        'tax_included',
        'quantity',
    ];

    /**
     * @param array $data
     * @return InvoiceLine
     * @throws ApiException
     */
    public function update(array $data): InvoiceLine
    {
        $updated = $this->client->invoiceLines->update($this->id, $data)->toArray();

        foreach ($updated as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * @throws ApiException
     */
    public function delete() : void
    {
        $this->client->invoiceLines->delete($this->id);
    }
}
