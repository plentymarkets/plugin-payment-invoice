<?php

namespace Invoice\Assistants\DataSources;

use Invoice\Services\SettingsService;
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
        //for WizardContainer ($wizardArray['isCompleted'] = $dataSource->findData()->data->default ? true : false;)
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
            $settings = $this->settingsService->loadClientSettingsIfExist($pid, null);
            if (count($settings)) {
                $settings = $this->settingsService->getSettingsForPlentyId($pid, null);
                $data[$pid]['config_name'] = $pid;
                $data[$pid] = $settings;
                if ($data[$pid]['quorumOrders'] > 0 || $data[$pid]['minimumAmount'] > 0 || $data[$pid]['maximumAmount'] > 0) { //untoggle limits if set
                    $data[$pid]['limit_toggle'] = true;
                }

                if ($data[$pid]['disallowInvoiceForGuest'] == 1) {
                    $data[$pid]['allowInvoiceForGuest'] = 0;
                } elseif (empty($data[$pid]['allowInvoiceForGuest']) || $data[$pid]['allowInvoiceForGuest'] == 0) {
                    $data[$pid]['allowInvoiceForGuest'] = 1;
                }
            }
        }
        return $data;
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
            //if(array_key_exists($optionId, $entities)) {
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
