<?php //strict

namespace Invoice\Helper;

/**
 * Class InvoiceHelper
 *
 * @package Invoice\Helper
 */
class InvoiceHelper
{
    /**
     * Load the ID of the payment method
     * Return the ID for the payment method
     *
     * @return int
     */
    public function getInvoiceMopId()
    {
        /**
         * Use the payment method id from the system
         */
        return 2;
    }
}
