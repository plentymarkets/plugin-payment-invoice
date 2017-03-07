<?php

namespace Invoice\Migrations;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Invoice\Helper\InvoiceHelper;

/**
 * Migration to create payment mehtod
 *
 * Class CreatePaymentMethod
 * @package Invoice\Migrations
 */
class CreatePaymentMethod
{
    /**
     * @var PaymentMethodRepositoryContract
     */
    private $paymentMethodRepositoryContract;

    /**
     * @var InvoiceHelper
     */
    private $invoiceHelper;

    /**
     * CreatePaymentMethod constructor.
     * @param PaymentMethodRepositoryContract $paymentMethodRepositoryContract
     * @param InvoiceHelper $invoiceHelper
     */
    public function __construct(PaymentMethodRepositoryContract $paymentMethodRepositoryContract, InvoiceHelper $invoiceHelper)
    {
        $this->paymentMethodRepositoryContract = $paymentMethodRepositoryContract;
        $this->invoiceHelper = $invoiceHelper;
    }

    /**
     * Run on plugin build
     *
     * Create Method of Payment ID for Invoice if it doesn't exist
     */
    public function run()
    {
        /**
         * Check if the payment method exist
         */
        if($this->invoiceHelper->getInvoiceMopId() == 'no_paymentmethod_found')
        {
            $paymentMethodData = array( 'pluginKey'     => 'plenty_invoice',
                                        'paymentKey'    => 'INVOICE',
                                        'name'          => 'Vorkasse');

            //Call Payment Method Repository and Save data to DB
            $this->paymentMethodRepositoryContract->createPaymentMethod($paymentMethodData);
        }
    }
}