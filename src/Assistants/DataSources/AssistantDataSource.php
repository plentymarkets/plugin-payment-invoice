<?php

namespace Invoice\Assistants\DataSources;

use Invoice\Services\SettingsService;
use Plenty\Modules\Plugin\Contracts\PluginLayoutContainerRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Models\WizardData;
use Plenty\Modules\Wizard\Services\DataSources\BaseWizardDataSource;

class AssistantDataSource extends BaseWizardDataSource
{
    /**
     * @var SettingsService
     */
    protected $settingsService;

    public function __construct(
        SettingsService $settingsService
    )
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @return WizardData WizardData
     */
    public function findData()
    {
        /** @var WizardData $wizardData */
        $wizardData = pluginApp(WizardData::class);
        $wizardData->data = ['default' => false];

        return $wizardData;
    }

    /**
     * @return array
     */
    private function getEntities()
    {
        $data = [];
        $pids = $this->settingsService->getInvoiceClients();
        foreach ($pids as $pid) {
            $settingsExist = $this->settingsService->clientSettingsExist($pid, null);
            if ($settingsExist) {
                $settings = $this->settingsService->getSettingsForPlentyId($pid, null);
                $data[$pid] = [];
                $data[$pid] = $settings;
                $data[$pid]['config_name'] = $pid;
                if ($data[$pid]['quorumOrders'] > 0 || $data[$pid]['minimumAmount'] > 0 || $data[$pid]['maximumAmount'] > 0) {
                    $data[$pid]['limit_toggle'] = true;
                }
                if ($data[$pid]['disallowInvoiceForGuest'] == 1) {
                    $data[$pid]['allowInvoiceForGuest'] = 0;
                } elseif (empty($data[$pid]['allowInvoiceForGuest']) || $data[$pid]['allowInvoiceForGuest'] == 0) {
                    $data[$pid]['allowInvoiceForGuest'] = 1;
                }
                $data[$pid]['logo_url'] = $data[$pid]['logoUrl'];
                $data[$pid]['logo_type_external'] = $data[$pid]['logo'] > 0;
                $data[$pid]['invoicePaymentMethodIcon'] = $this->logoInFooter($pid);
                $data[$pid]['info_page_toggle'] = $data[$pid]['infoPageType'] > 0;
                $data[$pid]['info_page_type'] = $data[$pid]['infoPageType'] == 2 ? 2 : 1;
                $data[$pid]['external_info_page'] = $data[$pid]['infoPageExtern'];
                $data[$pid]['internal_info_page'] = $data[$pid]['infoPageIntern'];
            }
        }
        return $data;
    }
    /**
     * Checks if the container link for the icon is set
     *
     * @param int $plentyId
     * @return boolean
     */
    private function logoInFooter($plentyId)
    {
        /** @var WebstoreRepositoryContract $webstoreRepo */
        $webstoreRepo = pluginApp(WebstoreRepositoryContract::class);
        /** Webstore $webstore **/
        $webstore = $webstoreRepo->findByPlentyId($plentyId);
        if(!is_null($webstore) && !is_null($webstore->pluginSetId)){
            /** @var PluginLayoutContainerRepositoryContract $pluginLayoutContainerRepo */
            $pluginLayoutContainerRepo = pluginApp(PluginLayoutContainerRepositoryContract::class);
            $containers = $pluginLayoutContainerRepo->all($webstore->pluginSetId);
            return $containers->pluck('dataProviderKey')->contains('Invoice\Providers\Icon\IconProvider');
        }
        return false;
    }

    /**
     * @return array
     */
    public function getIdentifiers()
    {
        return array_keys($this->getEntities());
    }

    /**
     * @return array
     */
    public function get()
    {
        $wizardData = $this->dataStructure;

        //Must be passed otherwise the tiles have no data.
        $tileConfig = [];

        $pids = $this->settingsService->getInvoiceClients();
        foreach ($pids as $pid) {
            $tileConfig[$pid] =
                [
                    'config_name' => $pid
                ];
        }
        $wizardData['data'] = $tileConfig;

        return $wizardData;
    }

    /**
     * @param string $optionId
     * @return array
     */
    public function getByOptionId(string $optionId = 'default')
    {
        $dataStructure = $this->dataStructure;
        $entities = $this->getEntities();

        // If this option already exists
        if($optionId > 0){
            $dataStructure['data'] = $entities[$optionId];
            $dataStructure['data']['config_name'] = $optionId;
        }

        return $dataStructure;

    }

    /**
     * @param array $data
     * @param string $optionId
     *
     * @return array
     * @throws \Exception
     */
    public function createDataOption(array $data = [], string $optionId = 'default')
    {
        throw new \Exception('incorrect setting data');
    }

    /**
     * @param string $optionId
     *
     * @throws \Exception
     */
    public function deleteDataOption(string $optionId)
    {
        $this->settingsService->deleteSettings($optionId);
    }

    /**
     * @param string $optionId
     * @param array $data
     *
     * @throws \Exception
     */
    public function finalize(string $optionId, array $data = [])
    {
        //later :)
    }

    private function loadData()
    {
        $data = [];
        return $data;
    }
}
