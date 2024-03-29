<?php
namespace Invoice\Assistants\SettingsHandlers;
use Invoice\Helper\InvoiceHelper;
use Invoice\Services\SettingsService;
use Plenty\Modules\Order\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Plenty\Modules\Plugin\Contracts\PluginLayoutContainerRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Contracts\WizardSettingsHandler;
use Plenty\Plugin\Log\Loggable;

class InvoiceAssistantSettingsHandler implements WizardSettingsHandler
{
    use Loggable;

    /**
     * @var Webstore
     */
    private $webstore;
    /**
     * @var Plugin
     */
    private $invoicePlugin;
    /**
     * @var Plugin
     */
    private $ceresPlugin;

    /**
     * @var Plugin
     */
    private $language;

    /**
     * @param array $parameter
     * @return bool
     */
    public function handle(array $parameter)
    {
        $data = $parameter['data'];
        $webstoreId = $data['config_name'];
        if(!is_numeric($webstoreId) || $webstoreId <= 0){
            $webstoreId = $this->getWebstore($parameter['optionId'])->storeIdentifier;
        }

        $this->saveInvoiceSettings($webstoreId, $data);
        $this->createContainer($webstoreId, $data);
        $this->activateLegacyPaymentMethod();
        
        return true;
    }

    /**
     * @param int $webstoreId
     * @param array $data
     */
    private function saveInvoiceSettings($webstoreId, $data)
    {
        $settings = [
            'name' => $data['name'] ?? '',
            'infoPageType' => $data['info_page_toggle'] ? ($data['info_page_type'] ?? 0) : 0,
            'infoPageIntern' => $data['info_page_toggle'] ? ($data['internal_info_page'] ?? '') : '',
            'infoPageExtern' => $data['info_page_toggle'] ? ($data['external_info_page'] ?? '') : '',
            'logo' => $data['logo_type_external'] ? 1 : 2,
            'logoUrl' => $data['logo_url'] ?? '',
            'description' => $data['description'] ?? '',
            'designatedUse' => $data['designatedUse'] ?? '',
            'showDesignatedUse' => $data['showDesignatedUse'] ?? 0,
            'plentyId' => $webstoreId,
            'showBankData' => $data['showBankData'] ?? 0,
            'invoiceEqualsShippingAddress' => $data['invoiceEqualsShippingAddress'] ?? '',
            'disallowInvoiceForGuest' => isset($data['allowInvoiceForGuest']) ? (int) !$data['allowInvoiceForGuest'] : 0,
            'quorumOrders' => isset($data['limit_toggle']) && $data['limit_toggle'] && isset($data['quorumOrders']) ? $data['quorumOrders'] : 0,
            'minimumAmount' => isset($data['limit_toggle']) && $data['limit_toggle'] && isset($data['minimumAmount']) ? $data['minimumAmount'] : 0,
            'maximumAmount' => isset($data['limit_toggle']) && $data['limit_toggle'] && isset($data['maximumAmount']) ? $data['maximumAmount'] : 0,
            'shippingCountries' => $data['shippingCountries'] ?? [],
            'feeDomestic' => 0.00,
            'feeForeign' => 0.00,
            'lang' => $this->getLanguage()
        ];
        /** @var SettingsService $settingsService */
        $settingsService = pluginApp(SettingsService::class);
        $settingsService->saveSettings($settings);
    }

    /**
     * @return string
     */
    private function getLanguage()
    {
        if ($this->language === null) {
            $this->language =  \Locale::getDefault();
        }

        return $this->language;
    }

    /**
     * @param int $webstoreId
     * @return Webstore
     */
    private function getWebstore($webstoreId)
    {
        if ($this->webstore === null) {
            /** @var WebstoreRepositoryContract $webstoreRepository */
            $webstoreRepository = pluginApp(WebstoreRepositoryContract::class);
            $this->webstore = $webstoreRepository->findByStoreIdentifier($webstoreId);
        }

        return $this->webstore;
    }

    /**
     * Check if a string is a valid UUID.
     *
     * @param string $string
     * @return false|int
     */
    public static function isValidUUIDv4($string)
    {
        $regex = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        return preg_match($regex, $string);
    }

    /**
     * @param int $webstoreId
     * @return Plugin
     */
    private function getCeresPlugin($webstoreId)
    {
        if ($this->ceresPlugin === null) {
            $webstore = $this->getWebstore($webstoreId);
            $pluginSet = $webstore->pluginSet;
            $plugins = $pluginSet->plugins();
            $this->ceresPlugin = $plugins->where('name', 'Ceres')->first();
        }

        return $this->ceresPlugin;
    }

    /**
     * @param int $webstoreId
     * @return Plugin
     */
    private function getInvoicePlugin($webstoreId)
    {
        if ($this->invoicePlugin === null) {
            $webstore = $this->getWebstore($webstoreId);
            $pluginSet = $webstore->pluginSet;
            $plugins = $pluginSet->plugins();
            $this->invoicePlugin = $plugins->where('name', 'Invoice')->first();
        }

        return $this->invoicePlugin;
    }

    /**
     * @param int $webstoreId
     * @param array $data
     */
    private function createContainer($webstoreId, $data)
    {
        try {

            $webstore = $this->getWebstore($webstoreId);
            $invoicePlugin = $this->getInvoicePlugin($webstoreId);
            $ceresPlugin = $this->getCeresPlugin($webstoreId);

            if( ($webstore && $webstore->pluginSetId) &&  $invoicePlugin !== null && $ceresPlugin !== null) {
                /** @var PluginLayoutContainerRepositoryContract $pluginLayoutContainerRepo */
                $pluginLayoutContainerRepo = pluginApp(PluginLayoutContainerRepositoryContract::class);

                $containerListEntries = [];

                // Default entries
                $containerListEntries[] = $this->createContainerDataListEntry(
                    $webstoreId,
                    'Ceres::MyAccount.OrderHistoryPaymentInformation',
                    'Invoice\Providers\InvoiceOrderConfirmationDataProvider'
                );

                $containerListEntries[] = $this->createContainerDataListEntry(
                    $webstoreId,
                    'Ceres::OrderConfirmation.AdditionalPaymentInformation',
                    'Invoice\Providers\InvoiceOrderConfirmationDataProvider'
                );

                if (isset($data['invoicePaymentMethodIcon']) && $data['invoicePaymentMethodIcon']) {
                    $containerListEntries[] = $this->createContainerDataListEntry(
                        $webstoreId,
                        'Ceres::Homepage.PaymentMethods',
                        'Invoice\Providers\Icon\IconProvider'
                    );
                } else {
                    $pluginLayoutContainerRepo->removeOne(
                        $webstore->pluginSetId,
                        'Ceres::Homepage.PaymentMethods',
                        'Invoice\Providers\Icon\IconProvider',
                        $ceresPlugin->id,
                        $invoicePlugin->id
                    );
                }

                $pluginLayoutContainerRepo->addNew($containerListEntries, $webstore->pluginSetId);
            }

        } catch(\Throwable $e){
            $this->getLogger(__CLASS__.'::'.__FUNCTION__)->error("Error creating containers webstore: $webstoreId", $e->getMessage());
        }
    }

    /**
     * @param int $webstoreId
     * @param string $containerKey
     * @param string $dataProviderKey
     * @return array
     */
    private function createContainerDataListEntry($webstoreId, $containerKey, $dataProviderKey)
    {
        $webstore = $this->getWebstore($webstoreId);
        $invoicePlugin = $this->getInvoicePlugin($webstoreId);
        $ceresPlugin = $this->getCeresPlugin($webstoreId);

        $dataListEntry = [];

        $dataListEntry['containerKey'] = $containerKey;
        $dataListEntry['dataProviderKey'] = $dataProviderKey;
        $dataListEntry['dataProviderPluginId'] = $invoicePlugin->id;
        $dataListEntry['containerPluginId'] = $ceresPlugin->id;
        $dataListEntry['pluginSetId'] = $webstore->pluginSetId;
        $dataListEntry['dataProviderPluginSetEntryId'] = $invoicePlugin->pluginSetEntries[0]->id;
        $dataListEntry['containerPluginSetEntryId'] = $ceresPlugin->pluginSetEntries[0]->id;

        return $dataListEntry;
    }

    /**
     * Activate the legacy payment method. This is needed to use the invoice payment method.
     */
    private function activateLegacyPaymentMethod()
    {
        /** @var InvoiceHelper $invoiceHelper */
        $invoiceHelper = pluginApp(InvoiceHelper::class);
        /** @var PaymentMethodRepositoryContract $paymentMethodRepository */
        $paymentMethodRepository = pluginApp(PaymentMethodRepositoryContract::class);
        
        $paymentMethodRepository->activatePaymentMethod($invoiceHelper->getInvoiceMopId());
    }
}
