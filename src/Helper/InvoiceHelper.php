<?php //strict

namespace Invoice\Helper;

use Plenty\Modules\Helper\Services\WebstoreHelper;
use Plenty\Modules\System\Models\WebstoreConfiguration;

/**
 * Class InvoiceHelper
 *
 * @package Invoice\Helper
 */
class InvoiceHelper
{
    /**
     * @var WebstoreConfiguration
     */
    private $webstoreConfig;
    
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
        $webstoreConfig = $this->getWebstoreConfig();

        $domain = $webstoreConfig->domainSsl;
        if (strpos($domain, 'master.plentymarkets') || $domain == 'http://dbmaster.plenty-showcase.de' || $domain == 'http://dbmaster-beta7.plentymarkets.eu' || $domain == 'http://dbmaster-stable7.plentymarkets.eu') {
            $domain = 'https://master.plentymarkets.com';
        }

        return $domain;
    }

    /**
     * @return WebstoreConfiguration
     */
    public function getWebstoreConfig()
    {
        if ($this->webstoreConfig === null) {
            /** @var WebstoreHelper $webstoreHelper */
            $webstoreHelper = pluginApp(WebstoreHelper::class);
            /** @var WebstoreConfiguration $webstoreConfig */
            $this->webstoreConfig = $webstoreHelper->getCurrentWebstoreConfiguration();
        }
        
        return $this->webstoreConfig;
    }
}
