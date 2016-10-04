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
     * Check the configuration if the payment method is active
     * Return true if the payment method is active, else return false
     *
     * @param ConfigRepository $configRepository
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return bool
     */
    public function isActive( ConfigRepository $configRepository,
                              BasketRepositoryContract $basketRepositoryContract):bool
    {
        /** @var bool $active */
        $active = true;

        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();

        /**
         * Check the minimum amount
         */
        if( $configRepository->get('Invoice.minimumAmount') > 0.00 &&
            $basket->basketAmount < $configRepository->get('Invoice.minimumAmount'))
        {
            $active = false;
        }

        /**
         * Check the maximum amount
         */
        if( $configRepository->get('Invoice.maximumAmount') > 0.00 &&
            $configRepository->get('Invoice.maximumAmount') < $basket->basketAmount)
        {
            $active = false;
        }

        /**
         * Check whether the invoice address is the same as the shipping address
         */
        if( $configRepository->get('Invoice.invoiceAddressEqualShippingAddress') == 1)
        {
            $active = false;
        }

        /**
        * Check whether the user is logged in
        */
        if( $configRepository->get('Invoice.disallowInvoiceForGuest') == 1)
        {
            $active = false;
        }

        return $active;
    }

    /**
     * Get the name of the payment method. The name can be entered in the config.json.
     *
     * @param ConfigRepository $configRepository
     * @return string
     */
    public function getName( ConfigRepository $configRepository ):string
    {
        $name = $configRepository->get('Invoice.name');

        if(!strlen($name))
        {
            $name = 'Invoice';
        }

        return $name;

    }

    /**
     * Get additional costs for the payment method. Additional costs can be entered in the config.json.
     *
     * @param ConfigRepository $configRepository
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return float
     */
    public function getFee( ConfigRepository $configRepository,
                            BasketRepositoryContract $basketRepositoryContract):float
    {
        $basket = $basketRepositoryContract->load();
        if($basket->shippingCountryId == 1)
        {
            return $configRepository->get('Invoice.fee.domestic');
        }
        else
        {
            return $configRepository->get('Invoice.fee.foreign');
        }
    }

    /**
     * Get the path of the icon
     *
     * @param ConfigRepository $configRepository
     * @return string
     */
    public function getIcon( ConfigRepository $configRepository ):string
    {
        if($configRepository->get('Invoice.logo') == 1)
        {
            return $configRepository->get('Invoice.logo.url');
        }
        return '';
    }

    /**
     * Get the description of the payment method. The description can be entered in the config.json.
     *
     * @param ConfigRepository $configRepository
     * @return string
     */
    public function getDescription( ConfigRepository $configRepository ):string
    {
        if($configRepository->get('Invoice.infoPage.type') == 1)
        {
            return $configRepository->get('Invoice.infoPage.intern');
        }
        elseif ($configRepository->get('Invoice.infoPage.type') == 2)
        {
            return $configRepository->get('Invoice.infoPage.extern');
        }
        else
        {
          return '';
        }
    }
}
