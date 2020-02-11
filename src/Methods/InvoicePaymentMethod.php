<?php //strict

namespace Invoice\Methods;

use Invoice\Helper\InvoiceHelper;
use Invoice\Services\SessionStorageService;
use Invoice\Services\SettingsService;
use Plenty\Legacy\Repositories\Frontend\CurrencyExchangeRepository;
use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plenty\Modules\Frontend\Contracts\CurrencyExchangeRepositoryContract;
use Plenty\Modules\Frontend\Services\AccountService;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Plugin\Application;
use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\Translation\Translator;

/**
 * Class InvoicePaymentMethod
 * @package Invoice\Methods
 */
class InvoicePaymentMethod extends PaymentMethodService
{
    /** @var SettingsService */
    protected $settings;

    /** @var  SessionStorageService */
    protected $session;

    /** @var  Checkout */
    protected $checkout;

    /** @var AccountService */
    protected $accountService;

    /** @var InvoiceHelper  */
    protected $invoiceHelper;

    public function __construct(
        SettingsService $settings,
        SessionStorageService $session,
        Checkout $checkout,
        AccountService $accountService,
        InvoiceHelper $invoiceHelper
    ) {
        $this->settings = $settings;
        $this->session  = $session;
        $this->checkout = $checkout;
        $this->accountService = $accountService;
        $this->invoiceHelper = $invoiceHelper;
    }

    /**
     * Check the configuration if the payment method is active
     * Return true if the payment method is active, else return false
     *
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return bool
     * @throws \Plenty\Exceptions\ValidationException
     */
    public function isActive( BasketRepositoryContract $basketRepositoryContract):bool
    {

        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();

        $isInvoiceAvailableForLoggedInCustomer = $this->invoiceHelper->isInvoiceAvailableForCurrentLoggedInCustomer(
            $this->accountService,
            $this->settings,
            $basket->customerId
        );

        if (null !== $isInvoiceAvailableForLoggedInCustomer) {
            return $isInvoiceAvailableForLoggedInCustomer;
        }
        
        /** @var CurrencyExchangeRepository $currencyService */
        $currencyService = pluginApp(CurrencyExchangeRepositoryContract::class);
        $minAmount = (float)$currencyService->convertFromDefaultCurrency($basket->currency, $this->settings->getSetting('minimumAmount'));
        $maxAmount = (float)$currencyService->convertFromDefaultCurrency($basket->currency, $this->settings->getSetting('maximumAmount'));

        /**
         * Check the minimum amount
         */
        if( $minAmount > 0.00 && $basket->basketAmount < $minAmount)
        {
            return false;
        }

        /**
         * Check the maximum amount
         */
        if( $maxAmount > 0.00 && $maxAmount < $basket->basketAmount)
        {
            return false;
        }

        $lang = $this->session->getLang();

        /**
         * Check whether the invoice address is the same as the shipping address
         */
        if( $this->settings->getSetting('invoiceEqualsShippingAddress',$lang) == 1)
        {
            $invoiceAddressId = $basket->customerInvoiceAddressId;
            $shippingAddressId = $basket->customerShippingAddressId;

            if($shippingAddressId != null && $invoiceAddressId != $shippingAddressId)
            {
                return false;
            }
        }

        /**
         * Check whether the user is logged in
         */
        if( $this->settings->getSetting('disallowInvoiceForGuest',$lang) == 1 && !$this->accountService->getIsAccountLoggedIn())
        {
            return false;
        }

        if(!in_array($this->checkout->getShippingCountryId(), $this->settings->getShippingCountries()))
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
    public function getName($lang = 'de')
    {
        /** @var Translator $translator */
        $translator = pluginApp(Translator::class);
        return $translator->trans('Invoice::PaymentMethod.paymentMethodName',[],$lang);
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
     * @throws \Plenty\Exceptions\ValidationException
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
     * @throws \Plenty\Exceptions\ValidationException
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
        /** @var Translator $translator */
        $translator = pluginApp(Translator::class);
        return $translator->trans('Invoice::PaymentMethod.paymentMethodDescription',[],$lang);
    }

    /**
     * Check if it is allowed to switch to this payment method
     *
     * @param int|null $orderId
     *
     * @return bool
     */
    public function isSwitchableTo(int $orderId = null):bool
    {
        if(!is_null($orderId) && $orderId > 0) {

            try {

                /** @var OrderRepositoryContract $orderRepo */
                $orderRepo = pluginApp(OrderRepositoryContract::class);
                $filters = $orderRepo->getFilters();
                $filters['addOrderItems'] = false;
                $orderRepo->setFilters($filters);

                $order = $orderRepo->findOrderById($orderId, ['amounts', 'relations', 'billingAddress', 'deliveryAddress']);
                $amount = $order->amount;

                $customerId = $this->invoiceHelper->getCustomerId($order);

                $isInvoiceAvailableForLoggedInCustomer = $this->invoiceHelper->isInvoiceAvailableForCurrentLoggedInCustomer(
                    $this->accountService,
                    $this->settings,
                    $customerId
                );

                if (null !== $isInvoiceAvailableForLoggedInCustomer) {
                    return $isInvoiceAvailableForLoggedInCustomer;
                }

                /** @var CurrencyExchangeRepository $currencyService */
                $currencyService = pluginApp(CurrencyExchangeRepositoryContract::class);
                $minAmount = (float)$currencyService->convertFromDefaultCurrency($amount->currency, $this->settings->getSetting('minimumAmount'));
                $maxAmount = (float)$currencyService->convertFromDefaultCurrency($amount->currency, $this->settings->getSetting('maximumAmount'));

                /**
                 * Check the minimum amount
                 */
                if( $minAmount > 0.00 && $amount->invoiceTotal < $minAmount) {
                    return false;
                }
        
                /**
                 * Check the maximum amount
                 */
                if( $maxAmount > 0.00 && $maxAmount < $amount->invoiceTotal) {
                    return false;
                }

                $lang = $this->session->getLang();

                /**
                 * Check whether the invoice address is the same as the shipping address
                 */
                if( $this->settings->getSetting('invoiceEqualsShippingAddress',$lang) == 1)
                {
                    $invoiceAddressId = $order->billingAddress->id;
                    $shippingAddressId = $order->deliveryAddress->id;

                    if($shippingAddressId != null && $invoiceAddressId != $shippingAddressId)
                    {
                        return false;
                    }
                }

                /**
                 * Check whether the user is logged in
                 */
                if( $this->settings->getSetting('disallowInvoiceForGuest',$lang) == 1 && !$this->accountService->getIsAccountLoggedIn())
                {
                    return false;
                }

                if(!in_array($this->checkout->getShippingCountryId(), $this->settings->getShippingCountries()))
                {
                    return false;
                }

                return true;

            } catch(\Exception $e) {}

        } else {

            try {

                $basketRepositoryContract = pluginApp(BasketRepositoryContract::class);

                /** @var Basket $basket */
                $basket = $basketRepositoryContract->load();

                $isInvoiceAvailableForLoggedInCustomer = $this->invoiceHelper->isInvoiceAvailableForCurrentLoggedInCustomer(
                    $this->accountService,
                    $this->settings,
                    $basket->customerId
                );

                if (null !== $isInvoiceAvailableForLoggedInCustomer) {
                    return $isInvoiceAvailableForLoggedInCustomer;
                }

                /** @var CurrencyExchangeRepository $currencyService */
                $currencyService = pluginApp(CurrencyExchangeRepositoryContract::class);
                $minAmount = (float)$currencyService->convertFromDefaultCurrency($basket->currency, $this->settings->getSetting('minimumAmount'));
                $maxAmount = (float)$currencyService->convertFromDefaultCurrency($basket->currency, $this->settings->getSetting('maximumAmount'));

                /**
                 * Check the minimum amount
                 */
                if( $minAmount > 0.00 && $basket->basketAmount < $minAmount)
                {
                    return false;
                }

                /**
                 * Check the maximum amount
                 */
                if( $maxAmount > 0.00 && $maxAmount < $basket->basketAmount)
                {
                    return false;
                }

                $lang = $this->session->getLang();

                /**
                 * Check whether the invoice address is the same as the shipping address
                 */
                if( $this->settings->getSetting('invoiceEqualsShippingAddress',$lang) == 1)
                {
                    $invoiceAddressId = $basket->customerInvoiceAddressId;
                    $shippingAddressId = $basket->customerShippingAddressId;

                    if($shippingAddressId != null && $invoiceAddressId != $shippingAddressId)
                    {
                        return false;
                    }
                }

                /**
                 * Check whether the user is logged in
                 */
                if( $this->settings->getSetting('disallowInvoiceForGuest',$lang) == 1 && !$this->accountService->getIsAccountLoggedIn())
                {
                    return false;
                }

                if(!in_array($this->checkout->getShippingCountryId(), $this->settings->getShippingCountries()))
                {
                    return false;
                }

                return true;

            } catch(\Exception $e) {}

        }

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

    /**
     * Check if this payment method should be searchable in the backend
     *
     * @return bool
     */
    public function isBackendSearchable():bool
    {
        return true;
    }

    /**
     * Check if this payment method should be active in the backend
     *
     * @return bool
     */
    public function isBackendActive():bool
    {
        return true;
    }

    /**
     * Get the name for the backend
     *
     * @param string $lang
     * @return string
     */
    public function getBackendName(string $lang):string
    {
        return $this->getName($lang);
    }

    /**
     * Check if this payment method can handle subscriptions
     *
     * @return bool
     */
    public function canHandleSubscriptions():bool
    {
        return true;
    }
}
