<?php
namespace Invoice\Assistants\SettingsHandlers;
use Invoice\Services\SettingsService;
use Plenty\Modules\Plugin\Contracts\PluginLayoutContainerRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Contracts\WizardSettingsHandler;

class InvoiceAssistantSettingsHandler implements WizardSettingsHandler
{
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
     * @param array $parameter
     * @return bool
     */
    public function handle(array $parameter)
    {
        $data = $parameter['data'];
        if (!$this->isValidUUIDv4($parameter['optionId'])) {
            $webstoreId = $parameter['optionId'];
        } else {
            $webstoreId = $data['config_name'];
        }
        $this->saveInvoiceSettings($webstoreId, $data);
        $this->createContainer($webstoreId, $data);
        return true;
    }

    /**
     * @param int $webstoreId
     * @param array $data
     */
    private function saveInvoiceSettings($webstoreId, $data)
    {
        $webstore = $this->getWebstore($webstoreId);

        $settings = [
            'name' => $data['name'],
            'infoPageType' => $data['info_page_type'],
            'infoPageIntern' => $data['internal_info_page'],
            'infoPageExtern' => $data['external_info_page'],
            'logo' => $data['logo_type_external'],
            'logoUrl' => $data['logo_url'],
            'description' => $data['description'],
            'designatedUse' => $data['designatedUse'],
            'showDesignatedUse' => $data['showDesignatedUse'],
            'plentyId' => $webstore->storeIdentifier,
            'showBankData' => $data['showBankData'],
            'invoiceEqualsShippingAddress' => $data['invoiceEqualsShippingAddress'],
            'disallowInvoiceForGuest' => (int) !$data['allowInvoiceForGuest'],
            'quorumOrders' => $data['limit_toggle'] ? $data['quorumOrders'] : 0,
            'minimumAmount' => $data['limit_toggle'] ? $data['minimumAmount'] : 0,
            'maximumAmount' => $data['limit_toggle'] ? $data['maximumAmount'] : 0,
            'shippingCountries' => $data['shippingCountries'],
            'feeDomestic' => 0.00,
            'feeForeign' => 0.00
        ];
        /** @var SettingsService $settingsService */
        $settingsService = pluginApp(SettingsService::class);
        $settingsService->saveSettings($settings);
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
            $this->webstore = $webstoreRepository->findById($webstoreId);
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
}
