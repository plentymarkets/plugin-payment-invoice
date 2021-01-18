<?php //strict

namespace Invoice\Helper;

use Plenty\Modules\Helper\Services\WebstoreHelper;

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

    /**
     * @return string
     */
    public function getDomain()
    {
        /** @var WebstoreHelper $webstoreHelper */
        $webstoreHelper = pluginApp(WebstoreHelper::class);

        /** @var \Plenty\Modules\System\Models\WebstoreConfiguration $webstoreConfig */
        $webstoreConfig = $webstoreHelper->getCurrentWebstoreConfiguration();

        $domain = $webstoreConfig->domainSsl;
        if (strpos($domain, 'master.plentymarkets') || $domain == 'http://dbmaster.plenty-showcase.de' || $domain == 'http://dbmaster-beta7.plentymarkets.eu' || $domain == 'http://dbmaster-stable7.plentymarkets.eu') {
            $domain = 'https://master.plentymarkets.com';
        }

        return $domain;
    }
}
