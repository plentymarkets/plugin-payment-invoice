<?php //strict

namespace Invoice\Methods;

use Invoice\Helper\InvoiceHelper;
use Invoice\Helper\SettingsHelper;
use Invoice\Services\InvoiceLimitationsService;
use Invoice\Services\SessionStorageService;
use Invoice\Services\SettingsService;
use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plenty\Modules\Frontend\Services\AccountService;
use Plenty\Modules\Frontend\Services\SystemService;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Modules\Payment\Method\Services\PaymentMethodBaseService;
use Plenty\Plugin\Application;
use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\Translation\Translator;
use Plenty\Modules\Webshop\Contracts\UrlBuilderRepositoryContract;

/**
 * Class InvoicePaymentMethod
 * @package Invoice\Methods
 */
class InvoicePaymentMethod extends PaymentMethodBaseService
{
    /** @var SystemService */
    protected $systemService;
    
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
        SystemService $systemService,
        SettingsService $settings,
        SessionStorageService $session,
        Checkout $checkout,
        AccountService $accountService,
        InvoiceHelper $invoiceHelper
    ) {
        $this->systemService = $systemService;
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
     * @return bool
     * @throws \Plenty\Exceptions\ValidationException
     */
    public function isActive():bool
    {
        /** @var BasketRepositoryContract $basketRepositoryContract */
        $basketRepositoryContract = pluginApp(BasketRepositoryContract::class);

        /** @var InvoiceLimitationsService $service */
        $service = pluginApp(InvoiceLimitationsService::class);
        
        /** @var ContactRepositoryContract $contactRepo */
        $contactRepo = pluginApp(ContactRepositoryContract::class);
        
        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();
        
        $isGuest = !($this->accountService->getIsAccountLoggedIn() && $basket->customerId > 0);
        $contact = null;
        if(!$isGuest) {
            try {
                $contact = $contactRepo->findContactById($basket->customerId, ['orderSummary']);
            } catch(\Exception $ex) {}
        }
        
        return $service->respectsAllLimitations(
            pluginApp(SettingsHelper::class, [$this->settings, $this->systemService->getPlentyId(), $this->session->getLang()]),
            $this->checkout->getShippingCountryId() ?? 1,
            $isGuest,
            $basket->basketAmount ?? 0,
            $basket->currency ?? "EUR",
            $this->checkout->getCustomerInvoiceAddressId(),
            $this->checkout->getCustomerShippingAddressId(),
            $contact
        );
    }

    /**
     * Get shown name
     *
     * @param string $lang
     * @return string
     */
    public function getName(string $lang = 'de'): string
    {
        /** @var Translator $translator */
        $translator = pluginApp(Translator::class);
        return $translator->trans('Invoice::PaymentMethod.paymentMethodName',[],$lang);
    }

    /**
     * Get additional costs for the payment method. Additional costs can be entered in the config.json.
     *
     * @return float
     */
    public function getFee():float
    {
        return 0.00;
    }

    /**
     * Get the path of the icon
     *
     * @param string $lang
     * @return string
     */
    public function getIcon(string $lang = 'de'):string
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
     * @param string $lang
     * @return string
     */
    public function getSourceUrl(string $lang = 'de'): string
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
                    /** @var InvoiceHelper $invoiceHelper */
                    $invoiceHelper = pluginApp(InvoiceHelper::class);
                    $urlBuilderRepository = pluginApp(UrlBuilderRepositoryContract::class);
                    
                    $urlQuery = $urlBuilderRepository->buildCategoryUrl($categoryId, $lang);
                    
                    $defaultLanguage = $invoiceHelper->getWebstoreConfig()->defaultLanguage;
                    $includeLanguage = false;
                    if ($lang != $defaultLanguage) {
                        $includeLanguage = true;
                    }
                    
                    return $invoiceHelper->getDomain() . $urlQuery->toRelativeUrl($includeLanguage);
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
     * @param string $lang
     * @return string
     */
    public function getDescription(string $lang = 'de'):string
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
        /** @var InvoiceLimitationsService $service */
        $service = pluginApp(InvoiceLimitationsService::class);
        
        //  If order ID is given check the order data
        if(!is_null($orderId) && $orderId > 0) {
            /** @var OrderRepositoryContract $orderRepo */
            $orderRepo = pluginApp(OrderRepositoryContract::class);
            $filters = $orderRepo->getFilters();
            $filters['addOrderItems'] = false;
            $orderRepo->setFilters($filters);
            
            try {
                $order = $orderRepo->findOrderById($orderId, ['amounts', 'addresses']);
                $contact = $order->contactReceiver;
                
                return $service->respectsAllLimitations(
                    pluginApp(SettingsHelper::class, [$this->settings, $order->plentyId, $this->session->getLang()]),
                    $order->deliveryAddress->countryId,
                    $contact === null || $contact->singleAccess === "1",
                    $order->amount->invoiceTotal,
                    $order->amount->currency,
                    $order->billingAddress->id,
                    $order->deliveryAddress->id,
                    $contact
                );
            } catch(\Exception $e) {
                return false;
            }
        }
        //  Else check the basket data
        else {
            /** @var BasketRepositoryContract $basketRepo */
            $basketRepo = pluginApp(BasketRepositoryContract::class);
            /** @var ContactRepositoryContract $contactRepo */
            $contactRepo = pluginApp(ContactRepositoryContract::class);
            
            $basket = $basketRepo->load();
            $isGuest = !($this->accountService->getIsAccountLoggedIn() && $basket->customerId > 0);
            $contact = null;
            if(!$isGuest) {
                try {
                    $contact = $contactRepo->findContactById($basket->customerId, ['orderSummary']);
                } catch(\Exception $ex) {}
            }
            
            return $service->respectsAllLimitations(
                pluginApp(SettingsHelper::class, [$this->settings, $this->systemService->getPlentyId(), $this->session->getLang()]),
                $this->checkout->getShippingCountryId() ?? 1,
                $isGuest,
                $basket->basketAmount,
                $basket->currency,
                $this->checkout->getCustomerInvoiceAddressId(),
                $this->checkout->getCustomerShippingAddressId(),
                $contact
            );
        }
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
    public function getBackendName(string $lang = 'de'):string
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

    /**
     * Get the url for the backend icon
     *
     * @return string
     */
    public function getBackendIcon(): string
    {
        $app = pluginApp(Application::class);
        $icon = $app->getUrlPath('invoice').'/images/logos/invoice_backend_icon.svg';
        return $icon;
    }
}
