<?php //strict

namespace Invoice\Methods;

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
    /**
     * @param ConfigRepository $config
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return bool
     */
    public function isActive( ConfigRepository $config,
                              BasketRepositoryContract $basketRepositoryContract):bool
    {
        $active = false;
        /**
         * Check the active flag
         */
        if($config->get('Invoice.active') == 1)
        {
            $active = true;
            $basket = $basketRepositoryContract->load();

            /**
             * Check the minimum amount
             */
            if( $config->get('Invoice.minimumAmount') > 0.00 &&
                $basket->basketAmount < $config->get('Invoice.minimumAmount'))
            {
                $active = false;
            }

            /**
             * Check the maximum amount
             */
            if( $config->get('Invoice.maximumAmount') > 0.00 &&
                $config->get('Invoice.maximumAmount') < $basket->basketAmount)
            {
                $active = false;
            }

            /**
             * Check if the invoice address is the same as the shipping address
             */
             if( $config->get('Invoice.invoiceAddressEqualShippingAddress') == 1)
             {
                 $active = false;
             }

             /**
              * Check if the user is logged in
              */
              if( $config->get('Invoice.disallowInvoiceForGuest') == 1)
              {
                  $active = false;
              }

        }

        return $active;
    }

    /**
     * @param ConfigRepository $config
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return float
     */
    public function getFee( ConfigRepository $config,
                            BasketRepositoryContract $basketRepositoryContract):float
    {
        $basket = $basketRepositoryContract->load();
        if($basket->shippingCountryId == 1)
        {
            return $config->get('Invoice.fee.domestic');
        }
        else
        {
            return $config->get('Invoice.fee.foreign');
        }
    }

    /**
     * @param ConfigRepository $config
     * @return string
     */
    public function getIcon( ConfigRepository $config ):string
    {
        if($config->get('Invoice.logo') == 1)
        {
            return $config->get('Invoice.logo.url');
        }
        return '';
    }

    /**
     * @param ConfigRepository $config
     * @return string
     */
    public function getDescription( ConfigRepository $config ):string
    {
        if($config->get('Invoice.infoPage.type') == 1)
        {
            return $config->get('Invoice.infoPage.intern');
        }
        elseif ($config->get('Invoice.infoPage.type') == 2)
        {
            return $config->get('Invoice.infoPage.extern');;
        }
        else
        {
          return '';
        }
    }
}
