<?php //strict

namespace Invoice\Methods;

use Invoice\Services\SessionStorageService;
use Invoice\Services\SettingsService;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;

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

    public function __construct(SettingsService $settings, SessionStorageService $session)
    {
        $this->settings = $settings;
        $this->session  = $session;
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
        /** @var bool $active */
        $active = true;

        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();

        $lang = $this->session->getLang();

        /**
         * Check the minimum amount
         */
        if( $this->settings->getSetting('minimumAmount',$lang) > 0.00 &&
            $basket->basketAmount < $this->settings->getSetting('minimumAmount'))
        {
            $active = false;
        }

        /**
         * Check the maximum amount
         */
        if( $this->settings->getSetting('maximumAmount',$lang) > 0.00 &&
            $this->settings->getSetting('maximumAmount',$lang) < $basket->basketAmount)
        {
            $active = false;
        }

        /**
         * Check whether the invoice address is the same as the shipping address
         */
        if( $this->settings->getSetting('invoiceAddressEqualShippingAddress',$lang) == 1)
        {
            $active = false;
        }

        /**
        * Check whether the user is logged in
        */
        if( $this->settings->getSetting('disallowInvoiceForGuest',$lang) == 1)
        {
            $active = false;
        }

        return $active;
    }

    /**
     * Get the name of the payment method. The name can be entered in the config.json.
     *
     * @return string
     */
    public function getName( ):string
    {
        $lang = $this->session->getLang();

        if(!empty($lang))
        {
            $name = $this->settings->getSetting('name', $lang);
        }
        else
        {
            $name = $this->settings->getSetting('name');
        }


        return $name;

    }

    /**
     * Get additional costs for the payment method. Additional costs can be entered in the config.json.
     *
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return float
     */
    public function getFee( BasketRepositoryContract $basketRepositoryContract):float
    {
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
        if($this->settings->getSetting('logo', $this->session->getLang()) == 1)
        {
            return $this->settings->getSetting('logoUrl', $this->session->getLang());
        }
        return '';
    }

    /**
     * Get the description of the payment method. The description can be entered in the config.json.
     *
     * @return string
     */
    public function getDescription(  ):string
    {
        switch($this->settings->getSetting('infoPageType', $this->session->getLang()))
        {
            case  1:    return $this->settings->getSetting('infoPageIntern', $this->session->getLang());
            case  2:    return $this->settings->getSetting('infoPageExtern', $this->session->getLang());
            default:    return '';
        }
    }
}
