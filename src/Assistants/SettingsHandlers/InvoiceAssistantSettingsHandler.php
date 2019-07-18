<?php
namespace Invoice\Assistants\SettingsHandlers;
use Invoice\Services\SettingsService;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Contracts\WizardSettingsHandler;

class InvoiceAssistantSettingsHandler implements WizardSettingsHandler
{
    /**
     * @var Webstore
     */
    private $webstore;


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
            'quorumOrders' => $data['quorumOrders'],
            'minimumAmount' => $data['minimumAmount'],
            'maximumAmount' => $data['maximumAmount'],
            'shippingCountries' => $data['shippingCountries'],
            'lang' => $data['lang'] ?? 'de', //fehlt noch
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
}
