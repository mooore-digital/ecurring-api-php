<?php

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\eCurringHttpClient;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @param string $status the status
     * @param string $function the name of the function to invoke on the invoice
     * @param boolean $expected_boolean the expected result
     *
     * @dataProvider invoiceStatusDataProvider
     */
    public function testInvoiceStatus($status, $function, $expected_boolean)
    {
        $invoice = new Invoice($this->createMock(eCurringHttpClient::class));
        $invoice->status = $status;

        $this->assertEquals($expected_boolean, $invoice->{$function}());
    }

    public function invoiceStatusDataProvider()
    {
        return [
            [Invoice::STATUS_DRAFT, 'isDraft', true],
            [Invoice::STATUS_DRAFT, 'isOpen', false],
            [Invoice::STATUS_DRAFT, 'isPaid', false],
            [Invoice::STATUS_DRAFT, 'isChargedBack', false],
            [Invoice::STATUS_DRAFT, 'isCancelled', false],
            [Invoice::STATUS_DRAFT, 'isCompleted', false],

            [Invoice::STATUS_OPEN, 'isDraft', false],
            [Invoice::STATUS_OPEN, 'isOpen', true],
            [Invoice::STATUS_OPEN, 'isPaid', false],
            [Invoice::STATUS_OPEN, 'isChargedBack', false],
            [Invoice::STATUS_OPEN, 'isCancelled', false],
            [Invoice::STATUS_OPEN, 'isCompleted', false],

            [Invoice::STATUS_PAID, 'isDraft', false],
            [Invoice::STATUS_PAID, 'isOpen', false],
            [Invoice::STATUS_PAID, 'isPaid', true],
            [Invoice::STATUS_PAID, 'isChargedBack', false],
            [Invoice::STATUS_PAID, 'isCancelled', false],
            [Invoice::STATUS_PAID, 'isCompleted', false],

            [Invoice::STATUS_CHARGED_BACK, 'isDraft', false],
            [Invoice::STATUS_CHARGED_BACK, 'isOpen', false],
            [Invoice::STATUS_CHARGED_BACK, 'isPaid', false],
            [Invoice::STATUS_CHARGED_BACK, 'isChargedBack', true],
            [Invoice::STATUS_CHARGED_BACK, 'isCancelled', false],
            [Invoice::STATUS_CHARGED_BACK, 'isCompleted', false],

            [Invoice::STATUS_CANCELLED, 'isDraft', false],
            [Invoice::STATUS_CANCELLED, 'isOpen', false],
            [Invoice::STATUS_CANCELLED, 'isPaid', false],
            [Invoice::STATUS_CANCELLED, 'isChargedBack', false],
            [Invoice::STATUS_CANCELLED, 'isCancelled', true],
            [Invoice::STATUS_CANCELLED, 'isCompleted', false],

            [Invoice::STATUS_COMPLETED, 'isDraft', false],
            [Invoice::STATUS_COMPLETED, 'isOpen', false],
            [Invoice::STATUS_COMPLETED, 'isPaid', false],
            [Invoice::STATUS_COMPLETED, 'isChargedBack', false],
            [Invoice::STATUS_COMPLETED, 'isCancelled', false],
            [Invoice::STATUS_COMPLETED, 'isCompleted', true],
        ];
    }
}
