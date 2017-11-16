<?php //strict

namespace Invoice\Methods;

use Invoice\Services\SessionStorageService;
use Invoice\Services\SettingsService;
use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plenty\Plugin\Application;
use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;

/**
 * Class InvoicePaymentMethod
 * @package Invoice\Methods
 */
class InvoicePaymentMethod extends PaymentMethodService
{
    /** @var SettingsService */
    private $settings;

    /** @var  SessionStorageService */
    private $session;

    /** @var  Checkout */
    private $checkout;


    public function __construct(SettingsService $settings, SessionStorageService $session, Checkout $checkout)
    {
        $this->settings = $settings;
        $this->session  = $session;
        $this->checkout = $checkout;
    }

    /**
     * Check the configuration if the payment method is active
     * Return true if the payment method is active, else return false
     *
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return bool
     */
    public function isActive( BasketRepositoryContract $basketRepositoryContract):bool
    {

        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();

        $lang = $this->session->getLang();

        /**
         * Check the minimum amount
         */
        if( $this->settings->getSetting('minimumAmount',$lang) > 0.00 &&
            $basket->basketAmount < $this->settings->getSetting('minimumAmount'))
        {
            return false;
        }

        /**
         * Check the maximum amount
         */
        if( $this->settings->getSetting('maximumAmount',$lang) > 0.00 &&
            $this->settings->getSetting('maximumAmount',$lang) < $basket->basketAmount)
        {
            return false;
        }

        /**
         * Check whether the invoice address is the same as the shipping address
         */
        if( $this->settings->getSetting('invoiceEqualsShippingAddress',$lang) == 1)
        {
            return false;
        }

        /**
        * Check whether the user is logged in
        */
        if( $this->settings->getSetting('disallowInvoiceForGuest',$lang) == 1)
        {
            return false;
        }

        if(!in_array($this->checkout->getShippingCountryId(), $this->settings->getSetting('shippingCountries')))
        {
            return false;
        }

        return true;
    }

    /**
     * Get shown name
     *
     * @param $lang
     * @return string
     */
    public function getName($lang = '')
    {
        if($lang == '')
        {
            /** @var FrontendSessionStorageFactoryContract $session */
            $session = pluginApp(FrontendSessionStorageFactoryContract::class);
            $lang = $session->getLocaleSettings()->language;
        }

        if(!empty($lang))
        {
            return $this->settings->getSetting('name', $lang);
        }
        return $this->settings->getSetting('name');
    }

    /**
     * Get additional costs for the payment method. Additional costs can be entered in the config.json.
     *
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return float
     */
    public function getFee( BasketRepositoryContract $basketRepositoryContract):float
    {
        return 0.00;
        $basket = $basketRepositoryContract->load();
        if($basket->shippingCountryId == 1)
        {
            return $this->settings->getSetting('feeDomestic', $this->session->getLang());
        }
        else
        {
            return $this->settings->getSetting('feeForeign', $this->session->getLang());
        }
    }

    /**
     * Get the path of the icon
     *
     * @return string
     */
    public function getIcon( ):string
    {
        if( $this->settings->getSetting('logo') == 1)
        {
            return $this->settings->getSetting('logoUrl');
        }
        elseif($this->settings->getSetting('logo') == 2)
        {
            $app = pluginApp(Application::class);
            $icon = $app->getUrlPath('invoice').'/images/icon.png';

            return $icon;
        }

        return '';
    }

    /**
    * Get InvoiceSourceUrl
    *
    * @return string
    */
    public function getSourceUrl()
    {
        /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;

        $infoPageType = $this->settings->getSetting('infoPageType', $lang);

        switch ($infoPageType)
        {
            case 1:
                // internal
                $categoryId = (int) $this->settings->getSetting('infoPageIntern', $lang);
                if($categoryId  > 0)
                {
                    /** @var CategoryRepositoryContract $categoryContract */
                    $categoryContract = pluginApp(CategoryRepositoryContract::class);
                    return $categoryContract->getUrl($categoryId, $lang);
                }
                return '';
            case 2:
                // external
                return $this->settings->getSetting('infoPageExtern', $lang);
            default:
                return '';
        }
    }


    /**
     * Get the description of the payment method. The description can be entered in the config.json.
     *
     * @return string
     */
    public function getDescription():string
    {
        /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;
        return $this->settings->getSetting('description', $lang);
    }
    
    /**
     * Check if it is allowed to switch to this payment method
     *
     * @return bool
     */
    public function isSwitchableTo():bool
    {
        return true;
    }
    
    /**
     * Check if it is allowed to switch from this payment method
     *
     * @return bool
     */
    public function isSwitchableFrom():bool
    {
        return false;
    }
}
